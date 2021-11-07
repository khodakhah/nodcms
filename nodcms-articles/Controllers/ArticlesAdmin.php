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
use NodCMS\Articles\Config\ViewBackend;
use NodCMS\Core\Controllers\Backend;
use NodCMS\Core\Libraries\Form;

class ArticlesAdmin extends Backend {
    public $langArray = array();

    /**
     * ArticlesAdmin constructor.
     */
	function __construct()
    {
        parent::__construct();
    }

    /**
     * Dashboard page
     */
    public static function dashboard(): string
    {
        $data = ['data_count'=> Models::articles()->getCount()];
        return Services::layout(new ViewBackend(), false)->setData($data)->render("article_dashboard");
    }

    /**
     * List of all articles
     */
    function article()
    {
        $this->data['title'] = _l("Articles",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l('Articles',$this)),
        );

        $this->data['sub_title'] = _l("Sort",$this);

        $list_items = array();
        $data_list = Models::articles()->getAll(array('parent'=>0), null, 1, ['order', 'ASC']);
        foreach($data_list as $item) {
            $sub_data = array();
            $sub_data_list = Models::articles()->getAll(array('parent'=>$item['article_id']), null, 1, ['order', 'ASC']);
            foreach($sub_data_list as $_item) {
                $sub_data[] = $this->setSortRow($_item);
            }
            $list_items[] = $this->setSortRow($item, $sub_data);
        }

        $this->data['add_urls'] = array(
            array('url'=>ARTICLES_ADMIN_URL."articleForm", 'label'=> _l("Add", $this)),
        );
        $this->data['max_depth'] = 2;
        $this->data['save_sort_url'] = ARTICLES_ADMIN_URL."articleSort";
        $this->data['list_items'] = join("\n", array_merge(Models::articles()->getAll(array('parent'=>-1)), $list_items));

        $this->data['page'] = "article_list";
        return $this->viewRender('list_sort');
    }

    /**
     * Article edit/add form
     *
     * @param int $id
     */
    function articleForm(int $id=0)
    {
        $this->data['title'] = _l("Article",$this);
        $back_url = ARTICLES_ADMIN_URL."article";
        $self_url = ARTICLES_ADMIN_URL."articleForm";
        if($id!=0){
            $current_data = Models::articles()->getOne($id);
            if(empty($current_data)){
                return $this->errorMessage("The article couldn't find.",$back_url);
            }
            $self_url.="/$id";
            $this->data['sub_title'] = _l("Edit",$this);
        }else{
            $this->data['sub_title'] = _l("Add",$this);
        }

        $config = array(
            array(
                'field' => 'article_uri',
                'label' => _l("Document URI", $this),
                'rules' => 'required|validURI|is_unique[article.article_uri'.(isset($current_data)?",article_id,$current_data[article_id]":"").']',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["article_uri"]:'',
                'input_prefix'=>base_url($this->language['code']."/article/"),
                'help'=>_l("The unique text that will be in URL to open this article.", $this),
            ),
            array(
                'field' => 'name',
                'label' => _l("Article name", $this),
                'rules' => 'required',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["name"]:''
            ),
            array(
                'field' => 'image',
                'label' => _l("Image", $this),
                'rules' => '',
                'type' => "image-library",
                'default'=>isset($current_data)?$current_data["image"]:''
            ),
            array(
                'field' => 'public',
                'label' => _l("Public", $this),
                'rules' => 'in_list[0,1]',
                'type' => "switch",
                'default'=>isset($current_data)?$current_data["public"]:''
            ),
            array(
                'label'=>_l('Page content',$this),
                'type'=>"h3",
            ),
        );
        $languages = Models::languages()->getAll();
        foreach($languages as $language){
            $translate = Models::articles()->getTranslations($id, $language['language_id']);
            // Add language title
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

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');

        if($myform->ispost()){
            if(!Services::identity()->isAdmin()){
                return Services::identity()->getResponse();
            }
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
                Models::articles()->edit($id, $post_data);
                if(isset($translates)){
                    Models::articles()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("Article has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::articles()->add($post_data);
                if(isset($translates)){
                    Models::articles()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("Article has been sent successfully.", $back_url);
            }
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l('Articles',$this),'url'=>ARTICLES_ADMIN_URL.'article'),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['parents'] = Models::articles()->getAll(array('parent'=>0));
        $this->data['page'] = "article_form";
        return $this->viewRenderString($myform->fetch('', array('data-redirect'=>1)));
    }

    /**
     * Remove an article
     *
     * @param $id
     * @param int $confirm
     */
    function articleRemove($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = ARTICLES_ADMIN_URL."article";
        $self_url = ARTICLES_ADMIN_URL."articleRemove/$id";
        $data = Models::articles()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("The article couldn't find.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the article from database.", $this).
                    '<br>'._l("After this, you will not to able to restore it.", $this).'</p>'.
                    '<p class="text-center font-lg bold">'._l("Are you sure to delete this?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>"$self_url/1",
                'redirect'=>1,
            ));
        }

        Models::articles()->remove($id);
        return $this->successMessage("The article has been deleted successfully.", $back_url);
    }

    /**
     * @param $id
     */
    function articleVisibility($id)
    {
        if(!Services::identity()->isAdmin()){
            return Services::identity()->getResponse();
        }
        $back_url = ARTICLES_ADMIN_URL."article";
        $data= Models::articles()->getOne($id);
        if($data == null || count($data)==0){
            return $this->errorMessage("Couldn't find the article.", $back_url);
        }
        $public = Services::request()->getPost('data' );
        if($public == 1){
            $public = 0;
        }elseif($public == 0){
            $public = 1;
        }else{
            return $this->errorMessage("Visibility value isn't correct. Please reload the page to solve this problem.", $back_url);
        }
        $update_data = array(
            'public'=>$public
        );
        Models::articles()->edit($id, $update_data);
        return $this->successMessage("Success", ARTICLES_ADMIN_URL."article");
    }

    /**
     * Save sort of articles
     */
    function articleSort()
    {
        if(!Services::identity()->isAdmin()){
            return Services::identity()->getResponse();
        }
        $i = 0;
        $index = 0;
        $parent = array(0);
        $children = array(Services::request()->getPost('data'));
        $children[$index] = json_decode($children[$index]);
        do{
            $data = $children[$index];
            foreach($data as $key=>$item){
                $i++;
                $update_data = array(
                    'order'=>$i,
                    'parent'=>$parent[$index]
                );
                Models::articles()->edit($item->id, $update_data);
                if(isset($item->children)){
                    $parent[$index+1] = $item->id;
                    $children[$index+1] = $item->children;
                }
            }
            $index++;
        }while(isset($children[$index]));
        return $this->successMessage("Your articles has been successfully sorted.", ARTICLES_ADMIN_URL."article");
    }

    /**
     * DB result to sortable list item
     *
     * @param array $item
     * @param array $sub_item
     * @return string
     */
    private function setSortRow($item, $sub_item = array())
    {
        $data = array(
            'id'=>$item['article_id'],
            'element_id'=>"article-item".$item['article_id'],
            'visibility'=>$item['public'],
            'title'=>$item['name'],
            'edit_url'=>ARTICLES_ADMIN_URL."articleForm/$item[article_id]",
            'remove_url'=>ARTICLES_ADMIN_URL."articleRemove/$item[article_id]",
            'visibility_url'=>ARTICLES_ADMIN_URL."articleVisibility/$item[article_id]",
            'btn_urls'=>array(
                array(
                    'target'=>"_blank",
                    'url'=>base_url($this->language['code']."/article/$item[article_uri]"),
                    'label'=>"<i class='fa fa-link'></i>"
                ),
            ),
            'sub_items'=>join("\n", $sub_item),
        );

        return Services::layout()->setData($data)->render('list_sort_item');
    }
}
