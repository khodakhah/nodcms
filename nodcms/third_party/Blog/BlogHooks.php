<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-May-19
 * Time: 2:37 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class BlogHooks extends NodcmsHooks
{
    private function necessary(){
        $this->CI->load->add_package_path(APPPATH."third_party/Blog");
        $this->CI->load->model("Blog_posts_model");
        $this->CI->load->model("Blog_category_model");
        $this->CI->load->model("Blog_posts_category_model");
        $this->CI->load->model("Blog_comments_model");
        $this->CI->settings = array_merge(array(
            'blog_comments_private'=>0,
            'blog_private_preview'=>0,
            'blog_page_title'=>_l("Blog", $this->CI),
            'blog_page_description'=>"",
            'blog_page_keywords'=>"",
        ), $this->CI->settings);
    }

    function backend()
    {
        define('BLOG_ADMIN_URL',base_url().'admin-blog/');
        $this->necessary();
        $unread_comments_count = $this->CI->Blog_comments_model->getCount(array('comment_read'=>0));
        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'blog' => array(
                    'url'=>'javascript:;',
                    'icon'=>'far fa-window-maximize',
                    'title'=>_l("Blog", $this->CI),
                    'sub_menu'=>array(
                        'blog_comments' => array(
                            'url'=>BLOG_ADMIN_URL.'comments',
                            'title'=>_l("Client's Comments", $this->CI).
                                ($unread_comments_count>0?" <span class='badge badge-danger'>$unread_comments_count</span>":""),
                        ),
                        'blog_posts' => array(
                            'url'=>BLOG_ADMIN_URL.'posts',
                            'title'=>_l("Posts", $this->CI),
                        ),
                        'blog_categories' => array(
                            'url'=>BLOG_ADMIN_URL.'categories',
                            'title'=>_l("Categories", $this->CI),
                        ),
                    )
                ),
            );
            $this->CI->addToAdminSidebar($addon_sidebar);

            $this->CI->system_urls[] = array('title'=>_l("Blog", $this->CI), 'url'=>"blog");
        }
    }

    function preset($lang)
    {
        $this->necessary();
    }
}