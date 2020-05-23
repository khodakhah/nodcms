<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 27-May-19
 * Time: 9:45 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Blog extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct("frontend");
    }

    static function home($CI){
        $data_list = $CI->Blog_posts_model->getAllTrans(null, 6, 1, array('created_date', 'DESC'));
        foreach($data_list as &$item){
            $item['post_url'] = base_url().$CI->language['code']."/blog-post-$item[post_id]";
        }
        $CI->data['data_list'] = $data_list;
        $CI->data['title'] = $CI->settings['blog_page_title'];
        return $CI->load->view($CI->mainTemplate."/blog_home", $CI->data, true);
    }

    /**
     * Page of a post
     *
     * @param $lang
     * @param $id
     */
    function post($lang, $id)
    {
        $this->preset($lang);
        $this->display_page_title = false;
        $data = $this->Blog_posts_model->getOneTrans($id);
        if(!is_array($data) || count($data)==0){
            $this->showError();
            return;
        }
        $post_categories = $this->Blog_posts_category_model->getAll(array('post_id'=>$id));
        if(is_array($post_categories) && count($post_categories)!=0){
            $categories = $this->Blog_category_model->getAllTrans(array('category_id IN'=>array_column($post_categories, 'category_id')));
            foreach($categories as $key=>$item){
                $categories[$key]['category_url'] = base_url()."$lang/blog-cat-$item[category_id]";
            }
            $this->data['categories'] = $categories;

            $related_categories = $this->Blog_posts_category_model->getAll(array('category_id IN'=>array_column($post_categories, 'category_id')));
            $related_posts = $this->Blog_posts_model->getAllTrans(array(
                'post_id IN'=>array_column($related_categories, 'post_id'),
                'post_id <>'=>$id,
            ),3,1,array('RAND()',''));
            foreach ($related_posts as $key=>$item){
                $related_posts[$key]['post_url'] = base_url()."$lang/blog-post-$item[post_id]";
            }

            $this->data['related_posts'] = $related_posts;
        }

        if($data['keywords']!=""){
            $keywords = explode(',',$data['keywords']);
            $_keywords = array();
            foreach ($keywords as $item){
                $item = trim($item);
                $_keywords[] = array(
                    'url'=>base_url()."$lang/blog?search=".str_replace(' ', '-', $item),
                    'title'=>$item,
                );
            }
            $this->data['post_keywords'] = $_keywords;
        }

        $allow_comments = !$this->settings['blog_comments_private'] || $this->userdata !=null;
        if($data['comment_status']==1 && $allow_comments){
            $comments = $this->Blog_comments_model->getAll(array('language_id'=>$this->language['language_id'], 'reply_to'=>0));
            if(is_array($comments)){
                    foreach ($comments as &$item){
                        $item['reply_url'] = base_url()."$lang/blog-comment-$id-$item[comment_id]";
                        $sub_items = $this->Blog_comments_model->getAll(array(
                            'language_id'=>$this->language['language_id'],
                            'reply_to'=>$item['comment_id']
                        ));
                        if(is_array($sub_items)){
                            foreach ($sub_items as &$sub_item){
                                $sub_item['reply_url'] = base_url()."$lang/blog-comment-$id-$sub_item[comment_id]";
                                if($this->userdata != null && $sub_item['user_id']==$this->userdata['user_id'])
                                    $sub_item['my_comment'] = 1;
                                if($sub_item['session_id']==session_id())
                                    $sub_item['my_comment'] = 1;
                            }
                            $item['sub_items'] = $sub_items;
                        }
                        if($this->userdata != null && $item['user_id']==$this->userdata['user_id'])
                            $item['my_comment'] = 1;
                        if($item['session_id']==session_id())
                            $sub_item['my_comment'] = 1;
                    }
                $this->data['comments'] = $comments;
            }
            $this->data['comment_form'] = base_url()."$lang/blog-comment-$id";
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog", $this), 'url'=>base_url()."$lang/blog"),
            array('title'=>$data['title']));

        $this->data['data'] = $data;
        $this->data['title'] = $data['title'];
        $this->data['sub_title'] = str_replace("{data}", $this->settings['company'],_l("{data}'s Blog", $this));
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        $this->data['content'] = $this->load->view($this->mainTemplate."/blog_post", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    function comment($lang, $id, $reply_to = null)
    {
        $this->preset($lang);
        $back_url = base_url()."$lang/blog";
        $self_url = base_url()."$lang/blog-comment-$id";
        $this->display_page_title = false;
        $data = $this->Blog_posts_model->getOneTrans($id);
        if(!is_array($data) || count($data)==0){
            $this->systemError("Blog post not found.", base_url()."$lang/blog");
            return;
        }

        $back_url .= "-post-$id";
        if($reply_to!=null){
            $_reply_to = $this->Blog_comments_model->getOne($reply_to);
            if(!is_array($_reply_to) || count($_reply_to)==0){
                $this->systemError("Blog comment not found.", $back_url);
                return;
            }
            $self_url .= "-$reply_to";
        }

        $allow_comments = !$this->settings['blog_comments_private'] || $this->userdata !=null;
        if($data['comment_status']==0 && $allow_comments){
            $this->systemError("Send comments is not available on this post.", $back_url);
            return;
        }
        if($this->userdata !=null){
            $config = array(
                array(
                    'field' => 'comment_name',
                    'label' => _l("Name", $this),
                    'type' => "static",
                    'rules' => 'required',
                    'value'=>$this->userdata['fullname'],
                ),
//                array(
//                    'field' => 'comment_notification',
//                    'label' => _l("Reply notification", $this),
//                    'type' => "checkbox",
//                    'rules' => 'in_list[0,1]',
//                    'default'=>0,
//                ),
                array(
                    'field' => 'comment_content',
                    'label' => _l("Comment", $this),
                    'type' => "textarea",
                    'rules' => 'required',
                    'default'=>"",
                ),
            );
        }else{
            $config = array(
                array(
                    'field' => 'comment_name',
                    'label' => _l("Name", $this),
                    'type' => "text",
                    'rules' => 'required',
                    'default'=>"",
                ),
//                array(
//                    'field' => 'comment_notification',
//                    'label' => "",
//                    'description' => _l("Reply notification", $this),
//                    'type' => "checkbox",
//                    'rules' => 'required|in_list[0,1]',
//                    'default'=>0,
//                    'attr'=>array(
//                        'data-role'=>"toggle-hidden",
//                        'data-target'=>".notification-on-reply",
//                    ),
//                ),
//                array(
//                    'field' => 'comment_email',
//                    'label' => _l("Email", $this),
//                    'type' => "text",
//                    'rules' => 'valid_email',
//                    'group_class' => 'notification-on-reply d-none',
//                    'default'=>"",
//                ),
                array(
                    'field' => 'comment_content',
                    'label' => _l("Comment", $this),
                    'type' => "textarea",
                    'rules' => 'required',
                    'default'=>"",
                ),
            );
        }

        $myform = new Form();
        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return;
            }

            $post_data['language_id'] = $this->language['language_id'];
            $post_data['post_id'] = $id;
            $post_data['session_id'] = session_id();
            if($this->userdata != null){
                $post_data['comment_name'] = $this->userdata['fullname'];
                $post_data['user_id'] = $this->userdata['user_id'];
                $post_data['comment_email'] = $this->userdata['email'];
            }
            if($reply_to!=null && isset($_reply_to)){
                $post_data['reply_to'] = $_reply_to['reply_to']!=0?$_reply_to['reply_to']:$reply_to;
            }
            $new_id = $this->Blog_comments_model->add($post_data);
            if(isset($_reply_to) && $_reply_to['comment_notification']){
                $mail_data = array(
                    'commenter_name'=>$_reply_to['comment_name'],
                    'commenter_content'=>$_reply_to['comment_content'],
                    'replier_name'=>$post_data['comment_name'],
                    'replier_content'=>$post_data['comment_content'],
                    'blog_post_title'=>$data['title'],
                    'reference_url'=>base_url()."$lang/blog-post-$id#comment$new_id",
                );
                send_notification_email("reply_blog_comment", $_reply_to['comment_email'], $mail_data);
            }
            $this->systemSuccess("Your comment has been sent successfully.", $back_url);
            return;
        }

        $myform->setSubmitLabel(_l("Send", $this));
        $myform->data['form_title'] = _l("Send a comment", $this);

        echo $myform->fetch("", array('data-redirect'=>1));
    }

    /**
     * Display blog posts
     *
     * @param $lang
     * @param int $page
     * @param null $category_id
     */
    function posts($lang, $page = 1, $category_id = null)
    {
        $this->preset($lang);
        if($category_id!=null){
            $category = $this->Blog_category_model->getOneTrans($category_id);
            if(!is_array($category) || count($category)==0){
                $this->showError();
                return;
            }
            $current_category = $category_id;
            $post_category = $this->Blog_posts_category_model->getAll(array('category_id'=>$category_id));
            if(is_array($post_category) && count($post_category)!=0){
                $conditions = array(
                    'post_id IN'=>array_column($post_category, 'post_id')
                );
            }else{
                $conditions = null;
            }
            $self_url = base_url()."$lang/blog-cat-$category_id";
        }
        else{
            $current_category = 0;
            $conditions = null;
            $self_url = base_url()."$lang/blog";
        }

        $search = $this->input->get('search');
        if($search!=null){
            if(!is_array($conditions))
                $conditions = array();
            $conditions['trans_search'] = array(
                'keywords LIKE'=>"%".str_replace('-',' ',$search)."%",
            );
        }

        $config = array(
            'base_url'=>$self_url,
            'query_string_segment'=>'',
            'reuse_query_string'=>TRUE,
            'total_rows'=>$this->Blog_posts_model->getCount($conditions),
            'uri_segment'=>3,
            'per_page'=>10,
        );
        $this->mkPagination($config);
        $data_list = $this->Blog_posts_model->getAllTrans($conditions, $config['per_page'], $page, array('created_date', 'DESC'));
        foreach($data_list as &$item){
            $item['post_url'] = base_url().$this->language['code']."/blog-post-$item[post_id]";
        }
        $this->data['data_list'] = $data_list;
        $categories = $this->Blog_category_model->getAllTrans();
        if(is_array($categories)){
            foreach($categories as &$item){
                $item['selected'] = $current_category==$item['category_id']?1:0;
                $item['category_url'] = base_url()."$lang/blog-cat-$item[category_id]";
            }
            array_unshift($categories, array(
                'title'=>_l("All", $this),
                'category_url'=>base_url()."$lang/blog",
                'selected'=>$current_category==0?1:0,
            ));
            $this->data['categories'] = $categories;
        }
        $this->data['title'] = _l("Blog posts", $this);
        $this->data['sub_title'] = str_replace("{data}", $this->settings['company'],_l("{data}'s Blog", $this));
        $this->data['description'] = $this->settings['site_description'];
        $this->data['keyword'] = $this->settings['site_keyword'];
        $this->data['content'] = $this->load->view($this->mainTemplate."/blog_posts", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

}