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
use NodCMS\Blog\Config\ViewFrontend;
use NodCMS\Core\Controllers\Frontend;
use NodCMS\Core\Controllers\General;
use NodCMS\Core\Libraries\Form;

class Blog extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewFrontend());
    }

    /**
     * @return mixed
     */
    public static function home()
    {
        $lang = Services::language()->getLocale();
        $data_list = Models::blogPost()->getAllTrans(null, 6, 1, array('created_date', 'DESC'));
        foreach ($data_list as &$item) {
            $item['post_url'] = base_url("{$lang}/blog-post-{$item['post_id']}");
        }
        $data = [];
        $data['data_list'] = $data_list;
        $data['title'] = Services::settings()->get()['blog_page_title'];
        return Services::layout(new ViewFrontend(), false)->setData($data)->render("blog_home");
    }

    /**
     * Page of a post
     *
     * @param int $id
     * @return string
     */
    public function post(int $id)
    {
        $this->display_page_title = false;
        $data = Models::blogPost()->getOneTrans($id);
        if (!is_array($data) || count($data)==0) {
            return $this->showError();
        }
        $post_categories = Models::blogPostsCategory()->getAll(array('post_id'=>$id));
        if (is_array($post_categories) && count($post_categories)!=0) {
            $categories = Models::blogCategory()->getAllTrans(array('category_id IN'=>array_column($post_categories, 'category_id')));
            foreach ($categories as $key=>$item) {
                $categories[$key]['category_url'] = base_url("{$this->lang}/blog-cat-$item[category_id]");
            }
            $this->data['categories'] = $categories;

            $related_categories = Models::blogPostsCategory()->getAll(array('category_id IN'=>array_column($post_categories, 'category_id')));
            $related_posts = Models::blogPost()->getAllTrans(array(
                'post_id IN'=>array_column($related_categories, 'post_id'),
                'post_id <>'=>$id,
            ), 3, 1, array('RAND()',''));
            foreach ($related_posts as $key=>$item) {
                $related_posts[$key]['post_url'] = base_url("{$this->lang}/blog-post-$item[post_id]");
            }

            $this->data['related_posts'] = $related_posts;
        }

        if ($data['keywords']!="") {
            $keywords = explode(',', $data['keywords']);
            $_keywords = array();
            foreach ($keywords as $item) {
                $item = trim($item);
                $_keywords[] = array(
                    'url'=>base_url("{$this->lang}/blog?search=".str_replace(' ', '-', $item)),
                    'title'=>$item,
                );
            }
            $this->data['post_keywords'] = $_keywords;
        }

        $allow_comments = !$this->settings['blog_comments_private'] || $this->userdata !=null;
        if ($data['comment_status']==1 && $allow_comments) {
            $comments = Models::blogComments()->getAll(array('language_id'=>$this->language['language_id'], 'reply_to'=>0));
            if (is_array($comments)) {
                foreach ($comments as &$item) {
                    $item['reply_url'] = base_url("{$this->lang}/blog-comment-$id-$item[comment_id]");
                    $sub_items = Models::blogComments()->getAll(array(
                        'language_id'=>$this->language['language_id'],
                        'reply_to'=>$item['comment_id']
                    ));
                    if (is_array($sub_items)) {
                        foreach ($sub_items as &$sub_item) {
                            $sub_item['reply_url'] = base_url("{$this->lang}/blog-comment-$id-$sub_item[comment_id]");
                            if ($this->userdata != null && $sub_item['user_id']==$this->userdata['user_id']) {
                                $sub_item['my_comment'] = 1;
                            }
                            if ($sub_item['session_id']==session_id()) {
                                $sub_item['my_comment'] = 1;
                            }
                        }
                        $item['sub_items'] = $sub_items;
                    }
                    if ($this->userdata != null && $item['user_id']==$this->userdata['user_id']) {
                        $item['my_comment'] = 1;
                    }
                    if ($item['session_id']==session_id()) {
                        $sub_item['my_comment'] = 1;
                    }
                }
                $this->data['comments'] = $comments;
            }
            $this->data['comment_form'] = base_url("{$this->lang}/blog-comment-$id");
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Blog", $this), 'url'=>base_url("{$this->lang}/blog")),
            array('title'=>$data['title']));

        $this->data['data'] = $data;
        $this->data['title'] = $data['title'];
        $this->data['sub_title'] = str_replace("{data}", $this->settings['company'], _l("{data}'s Blog", $this));
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        return $this->viewRender('blog_post');
    }

    /**
     * Send comment form
     *
     * @param int $id
     * @param null $reply_to
     * @return \CodeIgniter\HTTP\RedirectResponse|false|mixed|string|void
     * @throws \Exception
     */
    public function comment(int $id, $reply_to = null)
    {
        $back_url = base_url("{$this->lang}/blog");
        $self_url = base_url("{$this->lang}/blog-comment-$id");
        $this->display_page_title = false;
        $data = Models::blogPost()->getOneTrans($id);
        if (!is_array($data) || count($data)==0) {
            return $this->errorMessage("Blog post not found.", base_url("{$this->lang}/blog"));
        }

        $back_url .= "-post-$id";
        if ($reply_to!=null) {
            $_reply_to = Models::blogComments()->getOne($reply_to);
            if (!is_array($_reply_to) || count($_reply_to)==0) {
                return $this->errorMessage("Blog comment not found.", $back_url);
            }
            $self_url .= "-$reply_to";
        }

        $allow_comments = !$this->settings['blog_comments_private'] || $this->userdata !=null;
        if ($data['comment_status']==0 && $allow_comments) {
            return $this->errorMessage("Send comments is not available on this post.", $back_url);
        }
        if ($this->userdata !=null) {
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
        } else {
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

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');
        if ($myform->ispost()) {
            $post_data = $myform->getPost();
            // Stop Page
            if ($post_data === false) {
                return $myform->getResponse();
            }

            $post_data['language_id'] = $this->language['language_id'];
            $post_data['post_id'] = $id;
            $post_data['session_id'] = session_id();
            if ($this->userdata != null) {
                $post_data['comment_name'] = $this->userdata['fullname'];
                $post_data['user_id'] = $this->userdata['user_id'];
                $post_data['comment_email'] = $this->userdata['email'];
            }
            if ($reply_to!=null && isset($_reply_to)) {
                $post_data['reply_to'] = $_reply_to['reply_to']!=0 ? $_reply_to['reply_to'] : $reply_to;
            }
            $new_id = Models::blogComments()->add($post_data);
            if (isset($_reply_to) && $_reply_to['comment_notification']) {
                $mail_data = array(
                    'commenter_name'=>$_reply_to['comment_name'],
                    'commenter_content'=>$_reply_to['comment_content'],
                    'replier_name'=>$post_data['comment_name'],
                    'replier_content'=>$post_data['comment_content'],
                    'blog_post_title'=>$data['title'],
                    'reference_url'=>base_url("{$this->lang}/blog-post-$id#comment$new_id"),
                );
                send_notification_email("reply_blog_comment", $_reply_to['comment_email'], $mail_data);
            }
            return $this->successMessage("Your comment has been sent successfully.", $back_url);
        }

        $myform->setSubmitLabel(_l("Send", $this));
        $myform->data['form_title'] = _l("Send a comment", $this);

        return $myform->fetch("", array('data-redirect'=>1));
    }

    /**
     * Display blog posts
     *
     * @param int $page
     * @param null $category_id
     * @return string
     */
    public function posts($page = 1, $category_id = null)
    {
        if ($category_id!=null) {
            $category = Models::blogCategory()->getOneTrans($category_id);
            if (!is_array($category) || count($category)==0) {
                return $this->showError();
            }
            $current_category = $category_id;
            $post_category = Models::blogPostsCategory()->getAll(array('category_id'=>$category_id));
            if (is_array($post_category) && count($post_category)!=0) {
                $conditions = array(
                    'post_id IN'=>array_column($post_category, 'post_id')
                );
            } else {
                $conditions = null;
            }
            $self_url = base_url("{$this->lang}/blog-cat-$category_id");
        } else {
            $current_category = 0;
            $conditions = null;
            $self_url = base_url("{$this->lang}/blog");
        }

        $search = Services::request()->getGet('search');
        if ($search!=null) {
            if (!is_array($conditions)) {
                $conditions = array();
            }
            $conditions['trans_search'] = array(
                'keywords LIKE'=>"%".str_replace('-', ' ', $search)."%",
            );
        }

        $config = array(
            'base_url'=>$self_url,
            'query_string_segment'=>'',
            'reuse_query_string'=>true,
            'total_rows'=>Models::blogPost()->getCount($conditions),
            'uri_segment'=>3,
            'per_page'=>10,
        );
        $this->mkPagination($config);
        $data_list = Models::blogPost()->getAllTrans($conditions, $config['per_page'], $page, array('created_date', 'DESC'));
        foreach ($data_list as &$item) {
            $item['post_url'] = base_url("{$this->language['code']}/blog-post-{$item['post_id']}");
        }
        $this->data['data_list'] = $data_list;
        $categories = Models::blogCategory()->getAllTrans();
        if (is_array($categories)) {
            foreach ($categories as &$item) {
                $item['selected'] = $current_category==$item['category_id'] ? 1 : 0;
                $item['category_url'] = base_url("{$this->lang}/blog-cat-$item[category_id]");
            }
            array_unshift($categories, array(
                'title'=>_l("All", $this),
                'category_url'=>base_url("{$this->lang}/blog"),
                'selected'=>$current_category==0 ? 1 : 0,
            ));
            $this->data['categories'] = $categories;
        }
        $this->data['title'] = _l("Blog posts", $this);
        $this->data['sub_title'] = str_replace("{data}", $this->settings['company'], _l("{data}'s Blog", $this));
        $this->data['description'] = $this->settings['site_description'];
        $this->data['keyword'] = $this->settings['site_keyword'];
        return $this->viewRender('blog_posts');
    }
}
