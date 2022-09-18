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

namespace NodCMS\Articles\Controllers;

use Config\Services;
use NodCMS\Articles\Config\Models;
use NodCMS\Articles\Config\ViewFrontend;
use NodCMS\Core\Controllers\Frontend;

class Articles extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewFrontend());
    }

    /**
     * List of all articles
     *
     * @return string
     */
    public function articles(): string
    {
        $single_image = array();
        $single_text = array();
        $group_image = array();
        $group_text = array();
        $parents =  Models::articles()->getAllTrans(array("parent"=>0));
        foreach ($parents as &$item) {
            $sub_articles =  Models::articles()->getAllTrans(array("parent"=>$item["article_id"]));
            if (count($sub_articles)!=0) {
                $item["sub_articles"] = $sub_articles;
                if ($item["image"]!=null && $item["image"]!="" && file_exists(getcwd()."/".$item["image"])) {
                    array_push($group_image, $item);
                } else {
                    array_push($group_text, $item);
                }
            } else {
                if ($item["image"]!=null && $item["image"]!="" && file_exists(getcwd()."/".$item["image"])) {
                    array_push($single_image, $item);
                } else {
                    array_push($single_text, $item);
                }
            }
        }
        $this->data["single_image"] = $single_image;
        $this->data["single_text"] = $single_text;
        $this->data["group_image"] = $group_image;
        $this->data["group_text"] = $group_text;

        $this->data['title'] = $this->settings["site_title"];
        $this->data['sub_title'] = _l("Articles", $this);
        $this->data['description'] = $this->settings['site_description'];
        $this->data['keyword'] = $this->settings["site_keyword"];
        return $this->viewRender('articles');
    }

    /**
     * Article page
     *
     * @param int $id
     */
    public function article(int $id = 0, string $uri = "")
    {
        if (!empty($uri)) {
            $article_url = base_url("{$this->lang}/article/@article_uri@");
            $data = Models::articles()->getOneTrans(null, array('article_uri'=>$uri,'public'=>1));
        } else {
            $article_url = base_url("{$this->lang}/pa-@article_id@");
            $data = Models::articles()->getOneTrans($id, array('public'=>1));
        }
        if (empty($data)) {
            return $this->showError();
        }

        if ($data['parent']!=0) {
            $other_articles_not_in = array($data['article_id']);
            $next_article = Models::articles()->getOne(null, array('public'=>1, 'parent'=>$data['parent'], 'order'=>$data['order']+1));
            if ($next_article!=null && count($next_article)!=0) {
                $next_article['article_url'] = base_url("{$this->lang}/article/$next_article[article_uri]");
                $next_article['link_title'] = str_replace("{data}", $next_article['name'], _l("Next: {data}", $this));
                $this->data['next_article'] = $next_article;
                $other_articles_not_in[] = $next_article['article_id'];
            }
            $prev_article = Models::articles()->getOne(null, array('public'=>1, 'parent'=>$data['parent'], 'order'=>$data['order']-1));
            if ($prev_article!=null && count($prev_article)!=0) {
                $prev_article['article_url'] = base_url("{$this->lang}/article/$prev_article[article_uri]");
                $prev_article['link_title'] = str_replace("{data}", $prev_article['name'], _l("Prev: {data}", $this));
                $this->data['prev_article'] = $prev_article;
                $other_articles_not_in[] = $prev_article['article_id'];
            }
            $conditions = array('public'=>1, 'parent'=>$data['parent'],'article_id NOT IN'=>$other_articles_not_in);
            $this->data['relevant_articles'] = Models::articles()->getAll($conditions, null, 1, array("order", "ASC"));
            foreach ($this->data['relevant_articles'] as $key=>$item) {
                $this->data['relevant_articles'][$key]['article_url'] = base_url("{$this->lang}/article/$item[article_uri]");
            }

            $parent_data = Models::articles()->getOneTrans($data['parent'], array('public'=>1));
            $this->data['breadcrumb'] = array(
                array('title'=>$parent_data['name'], 'url' => base_url("{$this->lang}/article/{$parent_data['article_uri']}")),
                array('title'=>$data['name']),
            );
        } else {
            $this->data['breadcrumb'] = array(
                array('title'=>$data['name']),
            );
        }
        $conditions = array(
            'public'=>1,
            'parent IN'=>"0".($data['parent']==0 ? ",".$data['article_id'] : ""),
            'article_id <>'=>$data['article_id']
        );
        $other_articles = Models::articles()->getAll($conditions, 5, 1, array("order", "ASC"));
        foreach ($other_articles as $key=>$item) {
            $other_articles[$key]['article_url'] = base_url("{$this->lang}/article/$item[article_uri]");
        }
        $this->data['other_articles'] = $other_articles;
        $this->data['data'] = $data;
        $sub_articles = Models::articles()->getAllTrans(array('parent'=>$data['article_id']));
        foreach ($sub_articles as &$item) {
            $item['article_url'] = str_replace(array("@article_id@","@article_uri@"), array($item['article_id'],$item['article_uri']), $article_url);
        }
        $this->data["sub_articles"] = $sub_articles;

        $this->data['content_type'] = $this->getContentType($data['image']);
        $this->data['title'] = $data['title'];
        $this->data['sub_title'] = "";
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        $this->data['page'] = "article_$data[article_id]";
//        $this->setSidebar($article_url);
        $this->display_page_title = false;
        return $this->viewRender('article');
    }

    /**
     * Set article sidebar
     *
     * @param $article_url
     */
    private function setSidebar($article_url)
    {
        $page_sidebar = array(
            "home"=>array(
                'url'=>base_url($this->language['code']),
                'title'=>_l("Home", $this),
                'icon'=>"icon-home",
            )
        );
        $articles =  Models::articles()->getAllTrans(array("parent"=>0));
        foreach ($articles as $item) {
            $sub_articles =  Models::articles()->getAllTrans(array("parent"=>$item["article_id"]));
            if (count($sub_articles)!=0) {
                $sidebar_sub_item = array();
                foreach ($sub_articles as $sub_item) {
                    $sidebar_sub_item["article_$sub_item[article_id]"] = array(
                        'url'=>str_replace(array("@article_id@","@article_uri@"), array($item['article_id'],$item['article_uri']), $article_url),
                        'title'=>$sub_item["name"],
                        'icon'=>"fa fa-angle-right",
                    );
                }
                $page_sidebar["article_$item[article_id]"] = array(
                    'url'=>"javascript:;",
                    'title'=>$item["name"],
                    'icon'=>"fa fa-files-o",
                    'sub_menu'=>$sidebar_sub_item
                );
            } else {
                $page_sidebar["article_$item[article_id]"] = array(
                    'url'=>str_replace(array("@article_id@","@article_uri@"), array($item['article_id'],$item['article_uri']), $article_url),
                    'title'=>$item["name"],
                    'icon'=>"fa fa-file-o",
                );
            }
        }
        $this->addToSidebar($page_sidebar);
//        $this->data["page_sidebar"] = $page_sidebar;
    }

    /**
     * RSS feeds
     */
    public function rss()
    {
        $this->data["feeds_url"] = base_url("{$this->lang}/a-feeds");
        $this->data["pre_link"] = base_url("{$this->lang}/pa-");
        $this->data["page_url"] = base_url("{$this->lang}/a-map");
        $this->data['sub_title'] = $this->settings["site_title"];
        $this->data['description'] = $this->settings["options"]['site_description'];
        $this->data["items"] =  Models::articles()->getArticleFeeds();
        return Services::layout()->setData($this->data)->render("feeds");
    }

    /**
     * @param string $image
     * @return string
     */
    private function getContentType(string $image)
    {
        if ($image == "" || !file_exists(FCPATH.$image)) {
            return "article_no_image";
        }

        $_image = getimagesize(FCPATH.$image);
        if ($_image[1] <= ($_image[0]/2)) {
            return "article_row_image";
        }

        return "article_col_image";
    }
}
