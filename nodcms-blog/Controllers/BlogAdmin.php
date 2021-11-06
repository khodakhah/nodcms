<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Blog\Controllers;

use Config\Services;
use NodCMS\Blog\Config\Models;
use NodCMS\Blog\Config\ViewBackend;
use NodCMS\Core\Controllers\Backend;
use NodCMS\Core\Libraries\Ajaxlist;
use NodCMS\Core\Libraries\Form;

class BlogAdmin extends Backend
{
    /**
     * Set module local view files
     */
    private function localViewConfig()
    {
        Services::layout()->setConfig(new ViewBackend());
    }

    /**
     * Blog post list
     *
     * @param int $page
     * @return false|string
     */
    function posts($page = 1)
    {
        $self_url = BLOG_ADMIN_URL."posts";
        $config = array(
            'headers'=>array(
                array(
                    'content'=>"post_image",
                    'label'=>_l("Avatar", $this),
                    'theme'=>"tiny_image",
                ),
                array('content'=>"post_name", 'label'=>_l("Name", $this)),
                array(
                    'content'=>"created_date",
                    'label'=>_l("Date", $this),
                    'callback_function'=>"my_int_date",
                ),
                array(
                    'content'=>"post_id",
                    'label'=>"",
                    'theme'=>"edit_btn",
                    'url'=>BLOG_ADMIN_URL.'postSubmit/$content',
                ),
                array(
                    'content'=>"post_id",
                    'label'=>"",
                    'theme'=>"delete_btn",
                    'url'=>BLOG_ADMIN_URL.'postDelete/$content',
                ),
            ),
            'ajaxURL'=>$self_url,
            'page'=>$page,
            'per_page'=>10,
            'listID'=>"posts-list",
        );
        $conditions = null;
        $search_form = null;
        $sort_by = array("post_id", "DESC");
        $myList = new Ajaxlist();

        $config['total_rows'] = Models::blogPost()->getCount($conditions);

        $myList->setOptions($config);

        if (Services::request()->isAJAX()) {
            $result = Models::blogPost()->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            return  $myList->ajaxData($result);
        }

        $this->data['title'] = _l("Blog posts", $this);
        $this->data['sub_title'] = _l('List', $this);
        $this->data['breadcrumb'] = array(
            array('title' => _l("Blog posts", $this)),
        );
        $this->data['actions_buttons'] = array(
            'add'=>BLOG_ADMIN_URL."postSubmit",
        );

        $this->data['the_list'] = $myList->getPage();
        $this->data['page'] = "blog_posts";
        return $this->viewRender('data_list');
    }

    /**
     * Blog post edit/add form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function postSubmit(int $id = 0)
    {
        $back_url = BLOG_ADMIN_URL."posts";
        $self_url = BLOG_ADMIN_URL."postSubmit/$id";

        if($id!=0){
            $data = Models::blogPost()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("Couldn't find the post.", $back_url);
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
            $saved_categories = Models::blogPostsCategory()->getAll(array('post_id'=>$id));
            if(is_array($saved_categories) && count($saved_categories)!=0){
                $default_categories = array_column($saved_categories, 'category_id');
            }
        }
        else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1,'data-redirect'=>1);
        }

        $categories = Models::blogCategory()->getAll();

        $myform = new Form($this);
        $config = array(
            array(
                'field' => 'post_name',
                'label' => _l("Name", $this),
                'type' => "text",
                'rules' => 'required',
                'default'=>isset($data)?$data['post_name']:"",
            ),
            array(
                'field' => 'post_image',
                'label' => _l("Avatar", $this),
                'type' => "image-library",
                'rules' => '',
                'default'=>isset($data)?$data['post_image']:"",
            ),
            array(
                'field' => 'post_public',
                'label' => _l("Public", $this),
                'type' => "switch",
                'rules' => 'required|in_list[0,1]',
                'default'=>isset($data)?$data['post_public']:"",
            ),
            array(
                'field' => 'post_private',
                'label' => _l("Private", $this),
                'type' => "switch",
                'rules' => 'required|in_list[0,1]',
                'default'=>isset($data)?$data['post_private']:"",
            ),
            array(
                'field' => 'comment_status',
                'label' => _l("Allow comments", $this),
                'type' => "switch",
                'rules' => 'required|in_list[0,1]',
                'default'=>isset($data)?$data['comment_status']:"",
            ),
        );
        if(is_array($categories) && count($categories)!=0){
            $config[] = array(
                'field' => 'post_categories',
                'label' => _l("Categories", $this),
                'type' => "select-check",
                'options' => $categories,
                'option_name' => "category_name",
                'option_value' => "category_id",
                'rules' => 'in_list['.join(',', array_column($categories, 'category_id')).']',
                'default'=>isset($default_categories)?$default_categories:"",
            );
        }
        $languages = Models::languages()->getAll();
        foreach ($languages as $language){
            $translate = Models::blogPost()->getTranslations($id, $language['language_id']);
            array_push($config,array(
                'prefix_language'=>$language,
                'label'=>$language['language_title'],
                'type'=>"h4",
            ));
            $prefix = "translate[$language[language_id]]";
            array_push($config, array(
                'field'=>$prefix."[title]",
                'label'=>_l('Title',$this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['title'])?$translate['title']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[description]",
                'label'=>_l('Description',$this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['description'])?$translate['description']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[keywords]",
                'label'=>_l('Keywords',$this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['keywords'])?$translate['keywords']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[content]",
                'label'=>_l('Page Content',$this),
                'rules'=>"",
                'type'=>"texteditor",
                'default'=>isset($translate['content'])?$translate['content']:'',
            ));
        }

        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if(key_exists('translate', $post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if(key_exists('post_categories', $post_data)){
                $post_categories = explode(',',$post_data['post_categories']);
                unset($post_data['post_categories']);
            }

            if($id!=0){
                Models::blogPost()->edit($id, $post_data);
                if(isset($translates)){
                    Models::blogPost()->updateTranslations($id,$translates,$languages);
                }
                if(isset($post_categories)){
                    Models::blogPostsCategory()->clean(array('post_id'=>$id));
                    foreach($post_categories as $category){
                        Models::blogPostsCategory()->add(array('category_id'=>$category,'post_id'=>$id));
                    }
                }
                return $this->successMessage("The post has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::blogPost()->add($post_data);
                if(isset($translates)){
                    Models::blogPost()->updateTranslations($new_id,$translates,$languages);
                }
                if(isset($post_categories)){
                    foreach($post_categories as $category){
                        Models::blogPostsCategory()->add(array('category_id'=>$category,'post_id'=>$new_id));
                    }
                }
                return $this->successMessage("A new post has been added successfully.", $back_url);
            }
        }

        $this->data['title'] = _l("Blog Posts", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog Posts", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        if(isset($data)){
            $this->data['breadcrumb_options'] = array(
                array('title'=>_l("Comments", $this), 'url'=>BLOG_ADMIN_URL."postComments/$data[post_id]", 'active'=>0),
                array('title'=> _l("Edit", $this), 'url'=>$self_url, 'active'=>1)
            );
        }
        $this->data['page'] = "blog_post_submit";
        return $this->viewRenderString($myform->fetch(null,$form_attr));
    }

    /**
     * Blog post remove
     *
     * @param $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function postDelete($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = BLOG_ADMIN_URL."posts";
        $self_url = BLOG_ADMIN_URL."postDelete/$id";
        $data = Models::blogPost()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the post.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the blog post with its comments.", $this).
                    '<br>'._l("After this, you will not to able to restore it.", $this).'</p>'.
                    '<p class="text-center font-lg bold">'._l("Are you sure to delete this?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>"$self_url/1",
                'redirect'=>1,
            ));
        }

        Models::blogPost()->remove($id);
        return $this->successMessage("Blog post has been deleted successfully.", $back_url);
    }

    /**
     * Blog categories page
     *
     * @param int $page
     * @return false|string
     */
    function categories($page = 1)
    {
        $self_url = BLOG_ADMIN_URL."categories";
        $config = array(
            'headers'=>array(
                array('content'=>"category_name", 'label'=>_l("Name", $this)),
//                array(
//                    'label'=>_l("Posts", $this),
//                    'function'=>function($the_row){
//                        return Models::self::blogPost()->getCount(array('category_id'=>$the_row['category_id']));
//                    },
//                    },
//                ),
                array(
                    'content'=>"category_id",
                    'label'=>"",
                    'theme'=>"edit_btn",
                    'url'=>BLOG_ADMIN_URL.'categorySubmit/$content',
                ),
                array(
                    'content'=>"category_id",
                    'label'=>"",
                    'theme'=>"delete_btn",
                    'url'=>BLOG_ADMIN_URL.'categoryDelete/$content',
                ),
            ),
            'ajaxURL'=>$self_url,
            'page'=>$page,
            'per_page'=>10,
            'listID'=>"categories-table-list",
        );
        $conditions = null;
        $search_form = null;
        $sort_by = null;
        $myList = new Ajaxlist();

        $config['total_rows'] = Models::blogCategory()->getCount($conditions);

        $myList->setOptions($config);

        if (Services::request()->isAJAX()) {
            $result = Models::blogCategory()->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            return $myList->ajaxData($result);
        }

        $this->data['title'] = _l("Blog Categories", $this);
        $this->data['sub_title'] = _l('List', $this);
        $this->data['breadcrumb'] = array(
            array('title' => _l("Blog Categories", $this)),
        );
        $this->data['actions_buttons'] = array(
            'add'=>BLOG_ADMIN_URL."categorySubmit",
        );

        $this->data['the_list'] = $myList->getPage();
        $this->data['page'] = "blog_categories";
        return $this->viewRender('data_list');
    }

    /**
     * Add/Edit form of a blog category
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function categorySubmit(int $id = 0)
    {
        $back_url = BLOG_ADMIN_URL."categories";
        $self_url = BLOG_ADMIN_URL."categorySubmit/$id";

        if($id!=0){
            $data = Models::blogCategory()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("Couldn't find the category.", $back_url);
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array();
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1);
        }

        $config = array(
            array(
                'field' => 'category_name',
                'label' => _l("Name", $this),
                'type' => "text",
                'rules' => 'required',
                'default'=>isset($data)?$data['category_name']:"",
            ),
            array(
                'field' => 'category_image',
                'label' => _l("Image", $this),
                'type' => "image-library",
                'rules' => '',
                'default'=>isset($data)?$data['category_image']:"",
            ),
        );

        $languages = Models::languages()->getAll();
        foreach ($languages as $language){
            $translate = Models::blogCategory()->getTranslations($id, $language['language_id']);
            array_push($config,array(
                'prefix_language'=>$language,
                'label'=>$language['language_title'],
                'type'=>"h4",
            ));
            $prefix = "translate[$language[language_id]]";
            array_push($config, array(
                'field'=>$prefix."[title]",
                'label'=>_l('Title',$this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['title'])?$translate['title']:'',
            ));
        }

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if(key_exists('translate',$post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=0){
                Models::blogCategory()->edit($id, $post_data);
                if(isset($translates)){
                    Models::blogCategory()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("The category has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::blogCategory()->add($post_data);
                if(isset($translates)){
                    Models::blogCategory()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("The category has been sent successfully.", $back_url);
            }
        }

        $this->data['title'] = _l("Blog's Categories",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog's Categories", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['page'] = "blog_category_submit";
        return $this->viewRenderString($myform->fetch(null,$form_attr));
    }

    /**
     * Delete a gallery
     *
     * @param $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function categoryDelete($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = BLOG_ADMIN_URL."categories";
        $self_url = BLOG_ADMIN_URL."categoryDelete/$id";
        $data = Models::blogCategory()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the category.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the category from database.", $this).
                    '<br>'._l("After this, you will not to able to restore it.", $this).'</p>'.
                    '<p class="text-center font-lg bold">'._l("Are you sure to delete this?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>"$self_url/1",
                'redirect'=>1,
            ));
        }

        Models::blogCategory()->remove($id);
        return $this->successMessage("The category has been deleted successfully.", $back_url);
    }

    /**
     * Comments of a post
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     */
    function postComments($id)
    {
        $back_url = BLOG_ADMIN_URL."posts";
        $self_url = BLOG_ADMIN_URL."postComments/$id";

        $data = Models::blogPost()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the post.", $back_url);
        }
        $this->data['sub_title'] = _l("Comments", $this);
        $comments = Models::blogComments()->getAll(array('post_id'=>$id, 'reply_to'=>0));
        foreach ($comments as &$item){
            $item['language'] = Models::languages()->getOne($item['language_id']);
            $item['reply_url'] = BLOG_ADMIN_URL."commentReply/$item[comment_id]";
            $item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$id/$item[comment_id]";
            $sub_comments = Models::blogComments()->getAll(array('post_id'=>$id, 'reply_to'=>$item['comment_id']));
            if(is_array($sub_comments) && count($sub_comments)!=0){
                foreach ($sub_comments as &$sub_item){
                    $sub_item['language'] = Models::languages()->getOne($sub_item['language_id']);
                    $sub_item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$id/$sub_item[comment_id]";
                }
                $item['sub_items'] = $sub_comments;
            }
        }

        $this->data['comments'] = $comments;

        $this->data['title'] = _l("Blog Posts", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog Posts", $this), 'url'=>$back_url),
            array('title'=>$data['post_name']),
            array('title'=>$this->data['sub_title']));
        $this->data['breadcrumb_options'] = array(
            array('title'=>$this->data['sub_title'], 'url'=>$self_url, 'active'=>1),
            array('title'=> _l("Edit", $this), 'url'=>BLOG_ADMIN_URL."postSubmit/$data[post_id]", 'active'=>0)
        );
        $this->data['page'] = "blog_post_submit";
        $this->localViewConfig();
        return $this->viewRender('blog_posts_comments');
    }

    /**
     * Comments list
     *
     * @param int $page
     * @return false|string
     */
    function comments($page = 1)
    {
        $self_url = BLOG_ADMIN_URL."comments";
        $config = array(
            'headers'=>array(
                array('content'=>"comment_name", 'label'=>_l("Name", $this)),
                array(
                    'content'=>"created_date",
                    'label'=>_l("Date", $this),
                    'callback_function'=>"my_int_fullDate",
                ),
                array(
                    'label'=>_l("Comment on", $this),
                    'function'=>function($data){
                        $_data = Models::blogPost()->getOne($data['post_id']);
                        if(!is_array($_data) || count($_data)==0){
                            return "-";
                        }
                        return "<a target=\"_blank\" href=\"".BLOG_ADMIN_URL."postComments/$_data[post_id]#comment$data[comment_id]\">$_data[post_name]</a>";
                    },
                ),
                array(
                    'label'=>_l("User", $this),
                    'function'=>function($data){
                        if($data['user_id']==0){
                            return "-";
                        }
                        $_data = Models::users()->getOne($data['user_id']);
                        if(!is_array($_data) || count($_data)==0){
                            return "-";
                        }
                        return "<a href=\"javascript:;\" onclick=\"$.loadInModal('".ADMIN_URL."userProfile/$_data[user_id]');\">$_data[username]</a>";
                    },
                ),
                array(
                    'content'=>"admin_side",
                    'label'=>_l("Admin", $this),
                    'theme'=>"check_icon",
                ),
                array(
                    'content'=>"comment_id",
                    'label'=>"",
                    'theme'=>"open_btn",
                    'url'=>BLOG_ADMIN_URL.'comment/$content#comment$content',
                ),
                array(
                    'content'=>"comment_id",
                    'label'=>"",
                    'theme'=>"delete_btn",
                    'url'=>BLOG_ADMIN_URL.'commentDelete/$content',
                ),
            ),
            'callback_rows'=>array(
                'check_bold'=>array("comment_read", 0)
            ),
            'ajaxURL'=>$self_url,
            'page'=>$page,
            'per_page'=>10,
            'listID'=>"comments-list",
        );
        if(Models::languages()->getCount() > 1) {
            array_unshift($config['headers'],
                array(
                    'label'=>"",
                    'function'=>function($data){
                        $_data = Models::languages()->getOne($data['language_id']);
                        if(!is_array($_data) || count($_data)==0){
                            return "-";
                        }
                        $url = base_url($_data['image']);
                        return "<img src='$url' style='height:20px;' title='{$_data['language_title']}' alt='{$_data['code']}'>";
                    },
                )
            );
        }
        $conditions = null;
        $search_form = null;
        $sort_by = array("comment_id", "DESC");
        $myList = new Ajaxlist();

        $config['total_rows'] = Models::blogComments()->getCount($conditions);

        $myList->setOptions($config);

        if (Services::request()->isAJAX()) {
            $result = Models::blogComments()->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            return $myList->ajaxData($result);
        }

        $this->data['title'] = _l("Blog comments", $this);
        $this->data['sub_title'] = _l('List', $this);
        $this->data['breadcrumb'] = array(
            array('title' => $this->data['title']),
        );
        $this->data['actions_buttons'] = array(
            'ajax_add'=>BLOG_ADMIN_URL."commentSubmit",
        );

        $this->data['the_list'] = $myList->getPage();
        $this->data['page'] = "blog_comments";
        return $this->viewRender('data_list');
    }

    /**
     * View a comment
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     */
    function comment(int $id)
    {
        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."comments";
        $data = Models::blogComments()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the comment.", $back_url);
        }

        $post = Models::blogPost()->getOne($data['post_id']);
        if(!is_array($post) || count($post)==0){
            return $this->errorMessage("Couldn't find the post.", $back_url);
        }

        $language = Models::languages()->getOne($data['language_id']);

        if($data['reply_to']==0){
            $data['language'] = $language;
            $data['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$post[post_id]/$id";
            $data['reply_url'] = BLOG_ADMIN_URL."commentReply/$id";
            $sub_comments = Models::blogComments()->getAll(array('post_id'=>$id, 'reply_to'=>$data['comment_id']));
            if(is_array($sub_comments) && count($sub_comments)!=0){
                foreach ($sub_comments as &$sub_item){
                    $sub_item['language'] = Models::languages()->getOne($sub_item['language_id']);
                    $sub_item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$data[post_id]/$sub_item[comment_id]";
                }
                $item['sub_items'] = $sub_comments;
            }
            $this->data['item'] = $data;
        }
        else{
            $item = Models::blogComments()->getOne($data['reply_to']);
            $item['language'] = Models::languages()->getOne($item['language_id']);
            $item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$item[post_id]/$item[comment_id]";
            $item['reply_url'] = BLOG_ADMIN_URL."commentReply/$item[comment_id]";
            $sub_comments = Models::blogComments()->getAll(array('post_id'=>$item['post_id'], 'reply_to'=>$item['comment_id']));
            if(is_array($sub_comments) && count($sub_comments)!=0){
                foreach ($sub_comments as &$sub_item){
                    $sub_item['language'] = Models::languages()->getOne($sub_item['language_id']);
                    $sub_item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$id/$sub_item[comment_id]";
                }
                $item['sub_items'] = $sub_comments;
            }
            $this->data['item'] = $item;
        }

        $this->data['post'] = $post;

        if($data['comment_read']==0){
            Models::blogComments()->edit($id, array('comment_read'=>1));
        }

        if($data['reply_to']!=0){
            $parent = Models::blogComments()->getOne($data['reply_to']);
            if(!is_array($parent) || count($parent)==0){
                return $this->errorMessage("Couldn't find the parent's comment.", $back_url);
            }
            $conditions = array('reply_to'=>$data['reply_to'], 'comment_id<>'=>$id);
            $childes = Models::blogComments()->getAll($conditions, null, 1, array('comment_id', 'DESC'));
            if(is_array($childes) && count($childes)!=0){
                $comments = $childes;
            }
        }

        $this->data['data'] = $data;
        $this->data['comment_post'] = $post;

        $this->data['title'] = _l("Blog's comments",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title'], 'url'=>$back_url),
            array('title'=>str_replace("{data}", $data['comment_name'], _l("{data}'s comment", $this))));
        $this->data['page'] = "blog_category_submit";
        $this->localViewConfig();
        return $this->viewRender('blog_comment');
    }

    /**
     * Add/Edit a comment
     *
     * @param int $post_id
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|mixed|string
     * @throws \Exception
     */
    function commentSubmit(int $post_id = 0, int $id = 0)
    {
        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."commentSubmit/$post_id/$id";

        if($post_id != 0){
            $post = Models::blogPost()->getOne($post_id);
            if(!is_array($post) || count($post)==0){
                return $this->errorMessage("Couldn't find the post.", $back_url);
            }
        }

        if($id!=0){
            $data = Models::blogComments()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("Couldn't find the comment.", $back_url);
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1,'data-redirect'=>1);
        }

        $myform = new Form($this);
        $config = array(
            array(
                'field' => 'comment_content',
                'label' => _l("Comment", $this),
                'type' => "textarea",
                'rules' => 'required',
                'default'=>isset($data)?$data['comment_content']:"",
            ),
        );
        if(isset($data)){
            array_unshift($config, array(
                'field' => 'comment_name',
                'label' => _l("Name", $this),
                'type' => "text",
                'rules' => 'required',
                'default'=>isset($data)?$data['comment_name']:"",
            ));
            if(Models::languages()->getCount() > 1) {
                $language = Models::languages()->getOne($data['language_id']);
                array_unshift($config, array(
                    'field'=>"language_id",
                    'label'=>_l("Language", $this),
                    'type'=>"static",
                    'value'=>$language['language_title'],
                ));
            }
        }else{
            if(Models::languages()->getCount() > 1) {
                $languages = Models::languages()->getAll();
                array_unshift($config, array(
                    'field'=>"language_id",
                    'label'=>_l("Language", $this),
                    'type'=>"select",
                    'options'=>$languages,
                    'option_name'=>"language_title",
                    'option_value'=>"language_id",
                    'default'=>$this->language['language_id'],
                ));
            }
            else {
                array_unshift($config, array(
                    'field'=>"language_id",
                    'label'=>_l("Language", $this),
                    'type'=>"hidden",
                    'value'=>$this->language['code'],
                ));
            }
        }

        if($post_id == 0) {
            $posts = Models::blogPost()->getAll();
            array_unshift($config,
                array(
                    'field' => 'post_id',
                    'label' => _l("Posts", $this),
                    'type' => "select",
                    'options' => $posts,
                    'option_name' => "post_name",
                    'option_value' => "post_id",
                    'rules' => 'required|in_list['.join(',', array_column($posts, 'post_id')).']',
                    'default'=>isset($data)?$data['post_id']:"",
                )
            );
        }

        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if($id!=0){
                Models::blogComments()->edit($id, $post_data);
                return $this->successMessage("The comment has been edited successfully.", $back_url);
            }
            else{
                if($post_id != 0)
                    $post_data['post_id'] = $post_id;
                $post_data['user_id'] = $this->userdata['user_id'];
                $post_data['admin_side'] = 1;
                $post_data['comment_name'] = $this->userdata['fullname'];

                $new_id = Models::blogComments()->add($post_data);
                return $this->successMessage("A new comment has been added successfully.", $back_url);
            }
        }

        $myform->data['form_title'] = _l("Blog comments", $this);
        return $myform->fetch("", $form_attr);
    }

    /**
     * Replay to a comment
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|mixed|string
     * @throws \Exception
     */
    function commentReply(int $id)
    {
        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."commentReply/$id";

        $data = Models::blogComments()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the comment.", $back_url);
        }

        $this->data['sub_title'] = _l("Replay", $this);

        $language = Models::languages()->getOne($data['language_id']);
        $form_attr = array('data-redirect'=>1);
        $myform = new Form($this);
        $config = array(
            array(
                'field'=>"language_id",
                'label'=>_l("Language", $this),
                'type'=>"static",
                'value'=>$language['language_title'],
            ),
            array(
                'field' => 'comment',
                'label' => _l("Comment", $this),
                'type' => "static",
                'value'=>$data['comment_content'],
            ),
            array(
                'field' => 'comment_content',
                'label' => _l("Replay", $this),
                'type' => "textarea",
                'rules' => 'required',
            ),
        );

        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false ){
                return $myform->getResponse();
            }

            $post_data['language_id'] = $data['language_id'];
            $post_data['reply_to'] = $data['reply_to']!=0?$data['reply_to']:$id;
            $post_data['user_id'] = $this->userdata['user_id'];
            $post_data['post_id'] = $data['post_id'];
            $post_data['admin_side'] = 1;
            $post_data['comment_read'] = 1;
            $post_data['comment_name'] = $this->userdata['fullname'];

            $new_id = Models::blogComments()->add($post_data);
            return $this->successMessage("The comment's replay has been added successfully.", $back_url);
        }

        if($data['comment_read']==0){
            Models::blogComments()->edit($id, array('comment_read'=>1));
        }

        $myform->data['form_title'] = _l("Comment replay", $this);
        return $myform->fetch("", $form_attr);
    }

    /**
     * Delete a comment
     *
     * @param $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function commentDelete($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."commentDelete/$id";
        $data = Models::blogComments()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the comment.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the comment and its sub conversations from database.", $this).
                    '<br>'._l("After this, you will not to able to restore it.", $this).'</p>'.
                    '<p class="text-center font-lg bold">'._l("Are you sure to delete this?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>"$self_url/1",
                'redirect'=>1,
            ));
        }

        if($data['reply_to']==0){
            Models::blogComments()->clean(array('reply_to'=>$id));
        }
        Models::blogComments()->remove($id);
        return $this->successMessage("The comment has been deleted successfully.", $back_url);
    }

    /**
     * Blog settings
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function settings()
    {
        $self_url = BLOG_ADMIN_URL."settings";
        $back_url = BLOG_ADMIN_URL."posts";
        $this->data['title'] = _l("Blog Settings",$this);

        $config = array();

        $languages = Models::languages()->getAll();
        foreach($languages as $language){
            $prefix = "options[$language[language_id]]";
            $setting = Models::settings()->getSettings($language['language_id']);
            $config[] = array(
                'label'=>$language['language_title'],
                'type'=>"h4",
                'prefix_language'=>$language,
            );
            $config[] = array(
                'field'=>$prefix."[blog_page_title]",
                'label' => _l("Page title", $this),
                'rules' => "",
                'type' => "text",
                'default'=>isset($setting['blog_page_title'])?$setting['blog_page_title']:'',
            );
            $config[] = array(
                'field'=>$prefix."[blog_page_description]",
                'label' => _l("Page description", $this),
                'rules' => "",
                'type' => "textarea",
                'default'=>isset($setting['blog_page_description'])?$setting['blog_page_description']:'',
            );
            $config[] = array(
                'field'=>$prefix."[blog_page_keywords]",
                'label' => _l("Page keywords", $this),
                'rules' => "",
                'type' => "textarea",
                'default'=>isset($setting['blog_page_keywords'])?$setting['blog_page_keywords']:'',
            );
        }
        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');

        if($myform->ispost()){
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return $myform->getResponse();
            }
            Models::settings()->updateSettings($data);
            if(isset($data["options"])){
                foreach($data["options"] as $language_id=>$item){
                    if(!Models::settings()->updateSettings($item, $language_id)){
                        return $this->errorMessage("A settings options could not be saved.", $this);
                    }
                }
                unset($data["options"]);
            }
            return $this->successMessage("Your Setting has been updated successfully!", $self_url);
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Services", $this),'url'=>$back_url),
            array('title'=>$this->data['title']),
        );

        $this->data['page'] = "services_settings";
        return $this->viewRenderString($myform->fetch());
    }

}
