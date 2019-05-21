<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-May-19
 * Time: 2:19 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

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
                $this->systemError("The post couldn't find.", $back_url);
                return;
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
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
                'rules' => 'in_list['.join(',',array_column($categories, 'category_id')).']',
                'default'=>isset($data)?$data['post_categories']:"",
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

            if(key_exists('post_categories', $post_data) && is_array($post_data['post_categories'])){
                $post_data['post_categories'] = join(',', $post_data['post_categories']);
            }

            if($id!=null){
                $this->Blog_posts_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Blog_posts_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("The post has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Blog_posts_model->add($post_data);
                if(isset($translates)){
                    $this->Blog_posts_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("A new post has been added successfully.", $back_url);
            }
            return;
        }

        $this->data['title'] = _l("Blog Posts", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog Posts", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
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

        $myForm = new Form();
        $myForm->removeFiles($data['file_id']);
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
        $this->data['page'] = "blog_categories_list";
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
}