<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-May-19
 * Time: 2:19 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Blog_admin extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct('backend');
    }

    /**
     * Blog post list
     *
     * @param int $page
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
        $this->load->library("Ajaxlist");
        $myList = new Ajaxlist;

        $config['total_rows'] = $this->Blog_posts_model->getCount($conditions);

        $myList->setOptions($config);

        if ($this->input->is_ajax_request()) {
            $result = $this->Blog_posts_model->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            echo $myList->ajaxData($result);
            return;
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
        $this->data['content'] = $this->load->view($this->mainTemplate."/data_list", $this->data, true);
        $this->data['page'] = "blog_posts";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Blog post edit/add form
     *
     * @param null $id
     */
    function postSubmit($id = null)
    {
        $back_url = BLOG_ADMIN_URL."posts";
        $self_url = BLOG_ADMIN_URL."postSubmit/$id";

        if($id!=null){
            $data = $this->Blog_posts_model->getOne($id);
            if(count($data)==0){
                $this->systemError("Couldn't find the post.", $back_url);
                return;
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
            $saved_categories = $this->Blog_posts_category_model->getAll(array('post_id'=>$id));
            if(is_array($saved_categories) && count($saved_categories)!=0){
                $default_categories = array_column($saved_categories, 'category_id');
            }
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1,'data-redirect'=>1);
        }

        $categories = $this->Blog_category_model->getAll();

        $myform = new Form();
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
                'rules' => 'callback_validNumberListExists[Blog_category_model,idExists]',
                'default'=>isset($default_categories)?$default_categories:"",
            );
        }
        $languages = $this->Public_model->getAllLanguages();
        foreach ($languages as $language){
            $translate = $this->Blog_posts_model->getTranslations($id, $language['language_id']);
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
            if(!$this->checkAccessGroup(1))
                return;
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false || !is_array($post_data)){
                return;
            }

            if(key_exists('translate', $post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if(key_exists('post_categories', $post_data)){
                $post_categories = explode(',',$post_data['post_categories']);
                unset($post_data['post_categories']);
            }

            if($id!=null){
                $this->Blog_posts_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Blog_posts_model->updateTranslations($id,$translates,$languages);
                }
                if(isset($post_categories)){
                    $this->Blog_posts_category_model->clean(array('post_id'=>$id));
                    foreach($post_categories as $category){
                        $this->Blog_posts_category_model->add(array('category_id'=>$category,'post_id'=>$id));
                    }
                }
                $this->systemSuccess("The post has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Blog_posts_model->add($post_data);
                if(isset($translates)){
                    $this->Blog_posts_model->updateTranslations($new_id,$translates,$languages);
                }
                if(isset($post_categories)){
                    foreach($post_categories as $category){
                        $this->Blog_posts_category_model->add(array('category_id'=>$category,'post_id'=>$new_id));
                    }
                }
                $this->systemSuccess("A new post has been added successfully.", $back_url);
            }
            return;
        }

        $this->data['title'] = _l("Blog Posts", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog Posts", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        if(isset($data)){
            $this->data['breadcrumb_options'] = array(
                array('title'=>$this->data['sub_title'], 'url'=>BLOG_ADMIN_URL."postComments/$data[post_id]", 'active'=>0),
                array('title'=> _l("Edit", $this), 'url'=>$self_url, 'active'=>1)
            );
        }
        $this->data['content'] = $myform->fetch(null,$form_attr);
        $this->data['page'] = "blog_post_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Blog post remove
     *
     * @param $id
     * @param int $confirm
     */
    function postDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = BLOG_ADMIN_URL."posts";
        $self_url = BLOG_ADMIN_URL."postDelete/$id";
        $data = $this->Blog_posts_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the post.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
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
            return;
        }

        $this->Blog_posts_model->remove($id);
        $this->systemSuccess("Blog post has been deleted successfully.", $back_url);
    }

    /**
     * Blog categories page
     *
     * @param int $page
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
//                        return $this->Blog_posts_model->getCount(array('category_id'=>$the_row['category_id']));
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
        $this->load->library("Ajaxlist");
        $myList = new Ajaxlist;

        $config['total_rows'] = $this->Blog_category_model->getCount($conditions);

        $myList->setOptions($config);

        if ($this->input->is_ajax_request()) {
            $result = $this->Blog_category_model->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            echo $myList->ajaxData($result);
            return;
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
        $this->data['content'] = $this->load->view($this->mainTemplate."/data_list", $this->data, true);
        $this->data['page'] = "blog_categories";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Add/Edit form of a blog category
     *
     * @param null $id
     */
    function categorySubmit($id = null)
    {
        $back_url = BLOG_ADMIN_URL."categories";
        $self_url = BLOG_ADMIN_URL."categorySubmit/$id";

        if($id!=null){
            $data = $this->Blog_category_model->getOne($id);
            if(count($data)==0){
                $this->systemError("Couldn't find the category.", $back_url);
                return;
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

        $languages = $this->Public_model->getAllLanguages();
        foreach ($languages as $language){
            $translate = $this->Blog_category_model->getTranslations($id, $language['language_id']);
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

        $myform = new Form();
        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!$this->checkAccessGroup(1))
                return;
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return;
            }

            if(key_exists('translate',$post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=null){
                $this->Blog_category_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Blog_category_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("The category has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Blog_category_model->add($post_data);
                if(isset($translates)){
                    $this->Blog_category_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("The category has been sent successfully.", $back_url);
            }
            return;
        }

        $this->data['title'] = _l("Blog's Categories",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog's Categories", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $myform->fetch(null,$form_attr);
        $this->data['page'] = "blog_category_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Delete a gallery
     *
     * @param $id
     * @param int $confirm
     */
    function categoryDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = BLOG_ADMIN_URL."categories";
        $self_url = BLOG_ADMIN_URL."categoryDelete/$id";
        $data = $this->Blog_category_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the category.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
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
            return;
        }

        $this->Blog_category_model->remove($id);
        $this->systemSuccess("The category has been deleted successfully.", $back_url);
    }

    /**
     * Comments of a post
     *
     * @param $id
     */
    function postComments($id)
    {
        $back_url = BLOG_ADMIN_URL."posts";
        $self_url = BLOG_ADMIN_URL."postComments/$id";

        $data = $this->Blog_posts_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the post.", $back_url);
            return;
        }
        $this->data['sub_title'] = _l("Comments", $this);
        $comments = $this->Blog_comments_model->getAll(array('post_id'=>$id, 'reply_to'=>0));
        foreach ($comments as &$item){
            $item['language'] = $this->Languages_model->getOne($item['language_id']);
            $item['reply_url'] = BLOG_ADMIN_URL."commentReply/$item[comment_id]";
            $item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$id/$item[comment_id]";
            $sub_comments = $this->Blog_comments_model->getAll(array('post_id'=>$id, 'reply_to'=>$item['comment_id']));
            if(is_array($sub_comments) && count($sub_comments)!=0){
                foreach ($sub_comments as &$sub_item){
                    $sub_item['language'] = $this->Languages_model->getOne($sub_item['language_id']);
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
        $this->data['content'] = $this->load->view($this->mainTemplate."/blog_posts_comments", $this->data, true);
        $this->data['page'] = "blog_post_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Comments list
     *
     * @param int $page
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
                        $_data = $this->Blog_posts_model->getOne($data['post_id']);
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
                        $_data = $this->Users_model->getOne($data['user_id']);
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
        if($this->Languages_model->getCount() > 1) {
            array_unshift($config['headers'],
                array(
                    'label'=>"",
                    'function'=>function($data){
                        $_data = $this->Languages_model->getOne($data['language_id']);
                        if(!is_array($_data) || count($_data)==0){
                            return "-";
                        }
                        $url = base_url().$_data['image'];
                        return "<img src='$url' style='height:20px;' title='{$_data['language_title']}' alt='{$_data['code']}'>";
                    },
                )
            );
        }
        $conditions = null;
        $search_form = null;
        $sort_by = array("comment_id", "DESC");
        $this->load->library("Ajaxlist");
        $myList = new Ajaxlist;

        $config['total_rows'] = $this->Blog_comments_model->getCount($conditions);

        $myList->setOptions($config);

        if ($this->input->is_ajax_request()) {
            $result = $this->Blog_comments_model->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            echo $myList->ajaxData($result);
            return;
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
        $this->data['content'] = $this->load->view($this->mainTemplate."/data_list", $this->data, true);
        $this->data['page'] = "blog_comments";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * View a comment
     *
     * @param int $id
     */
    function comment($id)
    {
        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."comments";
        $data = $this->Blog_comments_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the comment.", $back_url);
            return;
        }

        $post = $this->Blog_posts_model->getOne($data['post_id']);
        if(!is_array($post) || count($post)==0){
            $this->systemError("Couldn't find the post.", $back_url);
            return;
        }

        $language = $this->Languages_model->getOne($data['language_id']);

        if($data['reply_to']==0){
            $data['language'] = $language;
            $data['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$post[post_id]/$id";
            $data['reply_url'] = BLOG_ADMIN_URL."commentReply/$id";
            $sub_comments = $this->Blog_comments_model->getAll(array('post_id'=>$id, 'reply_to'=>$data['comment_id']));
            if(is_array($sub_comments) && count($sub_comments)!=0){
                foreach ($sub_comments as &$sub_item){
                    $sub_item['language'] = $this->Languages_model->getOne($sub_item['language_id']);
                    $sub_item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$data[post_id]/$sub_item[comment_id]";
                }
                $item['sub_items'] = $sub_comments;
            }
            $this->data['item'] = $data;
        }
        else{
            $item = $this->Blog_comments_model->getOne($data['reply_to']);
            $item['language'] = $this->Languages_model->getOne($item['language_id']);
            $item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$item[post_id]/$item[comment_id]";
            $item['reply_url'] = BLOG_ADMIN_URL."commentReply/$item[comment_id]";
            $sub_comments = $this->Blog_comments_model->getAll(array('post_id'=>$item['post_id'], 'reply_to'=>$item['comment_id']));
            if(is_array($sub_comments) && count($sub_comments)!=0){
                foreach ($sub_comments as &$sub_item){
                    $sub_item['language'] = $this->Languages_model->getOne($sub_item['language_id']);
                    $sub_item['edit_url'] = BLOG_ADMIN_URL."commentSubmit/$id/$sub_item[comment_id]";
                }
                $item['sub_items'] = $sub_comments;
            }
            $this->data['item'] = $item;
        }

        $this->data['post'] = $post;

        if($data['comment_read']==0){
            $this->Blog_comments_model->edit($id, array('comment_read'=>1));
        }

        if($data['reply_to']!=0){
            $parent = $this->Blog_comments_model->getOne($data['reply_to']);
            if(!is_array($parent) || count($parent)==0){
                $this->systemError("Couldn't find the parent's comment.", $back_url);
                return;
            }
            $conditions = array('reply_to'=>$data['reply_to'], 'comment_id<>'=>$id);
            $childes = $this->Blog_comments_model->getAll($conditions, null, 1, array('comment_id', 'DESC'));
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
        $this->data['content'] = $this->load->view($this->mainTemplate."/blog_comment", $this->data, true);
        $this->data['page'] = "blog_category_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Add/Edit a comment
     *
     * @param int $post_id
     * @param null|int $id
     */
    function commentSubmit($post_id = null, $id = null)
    {
        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."commentSubmit/$id";

        if($post_id != null){
            $post = $this->Blog_posts_model->getOne($post_id);
            if(!is_array($post) || count($post)==0){
                $this->systemError("Couldn't find the post.", $back_url);
                return;
            }
        }

        if($id!=null){
            $data = $this->Blog_comments_model->getOne($id);
            if(count($data)==0){
                $this->systemError("Couldn't find the comment.", $back_url);
                return;
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1,'data-redirect'=>1);
        }

        $myform = new Form();
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
            if($this->Languages_model->getCount() > 1) {
                $language = $this->Languages_model->getOne($data['language_id']);
                array_unshift($config, array(
                    'field'=>"language_id",
                    'label'=>_l("Language", $this),
                    'type'=>"static",
                    'value'=>$language['language_title'],
                ));
            }
        }else{
            if($this->Languages_model->getCount() > 1) {
                $languages = $this->Languages_model->getAll();
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

        if($post_id == null) {
            $posts = $this->Blog_posts_model->getAll();
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
            if(!$this->checkAccessGroup(1))
                return;
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false || !is_array($post_data)){
                return;
            }

            if($id!=null){
                $this->Blog_comments_model->edit($id, $post_data);
                $this->systemSuccess("The comment has been edited successfully.", $back_url);
            }
            else{
                if($post_id != null)
                    $post_data['post_id'] = $post_id;
                $post_data['user_id'] = $this->userdata['user_id'];
                $post_data['admin_side'] = 1;
                $post_data['comment_name'] = $this->userdata['fullname'];

                $new_id = $this->Blog_comments_model->add($post_data);
                $this->systemSuccess("A new comment has been added successfully.", $back_url);
            }
            return;
        }

        $myform->data['form_title'] = _l("Blog comments", $this);
        echo $myform->fetch("", $form_attr);
    }

    /**
     * Replay to a comment
     *
     * @param int $id
     */
    function commentReply($id)
    {
        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."commentReply/$id";

        $data = $this->Blog_comments_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the comment.", $back_url);
            return;
        }

        $this->data['sub_title'] = _l("Replay", $this);

        $language = $this->Languages_model->getOne($data['language_id']);
        $form_attr = array('data-redirect'=>1);
        $myform = new Form();
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
            if(!$this->checkAccessGroup(1))
                return;
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false || !is_array($post_data)){
                return;
            }

            $post_data['language_id'] = $data['language_id'];
            $post_data['reply_to'] = $data['reply_to']!=0?$data['reply_to']:$id;
            $post_data['user_id'] = $this->userdata['user_id'];
            $post_data['post_id'] = $data['post_id'];
            $post_data['admin_side'] = 1;
            $post_data['comment_read'] = 1;
            $post_data['comment_name'] = $this->userdata['fullname'];

            $new_id = $this->Blog_comments_model->add($post_data);
            $this->systemSuccess("The comment's replay has been added successfully.", $back_url);
            return;
        }

        if($data['comment_read']==0){
            $this->Blog_comments_model->edit($id, array('comment_read'=>1));
        }

        $myform->data['form_title'] = _l("Comment replay", $this);
        echo $myform->fetch("", $form_attr);
    }

    /**
     * Delete a comment
     *
     * @param $id
     * @param int $confirm
     */
    function commentDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = BLOG_ADMIN_URL."comments";
        $self_url = BLOG_ADMIN_URL."commentDelete/$id";
        $data = $this->Blog_comments_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the comment.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
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
            return;
        }

        if($data['reply_to']==0){
            $this->Blog_comments_model->clean(array('reply_to'=>$id));
        }
        $this->Blog_comments_model->remove($id);
        $this->systemSuccess("The comment has been deleted successfully.", $back_url);
    }

    function settings()
    {
        $self_url = BLOG_ADMIN_URL."settings";
        $back_url = BLOG_ADMIN_URL."posts";
        $this->data['title'] = _l("Blog Settings",$this);

        $config = array();

        $languages = $this->Languages_model->getAll();
        foreach($languages as $language){
            $prefix = "options[$language[language_id]]";
            $setting = $this->Public_model->getSettings($language['language_id']);
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
        $myform = new Form();
        $myform->config($config, $self_url, 'post', 'ajax');

        if($myform->ispost()){
            $this->checkAccessGroup(1);
            $data = $myform->getPost();
            // Stop Page
            if($data == null){
                return;
            }
            $this->Nodcms_admin_model->updateSettings($data);
            if(isset($data["options"])){
                foreach($data["options"] as $language_id=>$item){
                    if(!$this->Nodcms_admin_model->updateSettings($item, $language_id)){
                        $this->systemError("A settings options could not be saved.", $this);
                        return;
                    }
                }
                unset($data["options"]);
            }
            $this->systemSuccess("Your Setting has been updated successfully!", $self_url);
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Services", $this),'url'=>$back_url),
            array('title'=>$this->data['title']),
        );

        $this->data['page'] = "services_settings";
        $this->data['content'] = $myform->fetch();
        $this->load->view($this->frameTemplate,$this->data);
    }

}