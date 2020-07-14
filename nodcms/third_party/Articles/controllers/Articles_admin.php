<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Articles_admin extends NodCMS_Controller {
    public $langArray = array();
	function __construct()
    {
        parent::__construct();
        $this->load->add_package_path(APPPATH."third_party/Articles");
        $this->load->model("Articles_model");
        $this->mainTemplate = "admin";
    }

    /**
     * Dashboard page
     */
    function dashboard()
    {
        $this->data['data_count'] = $this->Articles_model->getCount();
        echo json_encode(array(
            'status'=>"success",
            'content'=>$this->load->view($this->mainTemplate."/article_dashboard", $this->data, true)
        ));
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
        $data_list = $this->Articles_model->getAll(array('parent'=>0));
        foreach($data_list as $item) {
            $sub_data = array();
            $sub_data_list = $this->Articles_model->getAll(array('parent'=>$item['article_id']));
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
        $this->data['list_items'] = join("\n", array_merge($this->Articles_model->getAll(array('parent'=>-1)), $list_items));

        $this->data['page'] = "article_list";
        $this->data['content'] = $this->load->view($this->mainTemplate."/list_sort",$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Article edit/add form
     *
     * @param string $id
     */
    function articleForm($id=null)
    {
        $this->data['title'] = _l("Article",$this);
        $back_url = ARTICLES_ADMIN_URL."article";
        $self_url = ARTICLES_ADMIN_URL."articleForm";
        if($id!=null){
            $current_data = $this->Articles_model->getOne($id);
            if($current_data==null || count($current_data)==0){
                $this->systemError("The article couldn't find.",$back_url);
                return;
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
                'rules' => 'required|callback_validURI|callback_isUnique[article,article_uri'.(isset($current_data)?",article_id,$current_data[article_id]":"").']',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["article_uri"]:'',
                'input_prefix'=>base_url().$this->language['code']."/article/",
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
        $languages = $this->Public_model->getAllLanguages();
        foreach($languages as $language){
            $translate = $this->Articles_model->getTranslations($id, $language['language_id']);
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

        $myform = new Form();
        $myform->config($config, $self_url, 'post', 'ajax');

        if($myform->ispost()){
            if(!$this->checkAccessGroup(1)){
                return;
            }
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false || !is_array($post_data)){
                return;
            }

            if(key_exists('translate',$post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=null){
                $this->Articles_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Articles_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("Article has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Articles_model->add($post_data);
                if(isset($translates)){
                    $this->Articles_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("Article has been sent successfully.", $back_url);
            }
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l('Articles',$this),'url'=>ARTICLES_ADMIN_URL.'article'),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['parents'] = $this->Articles_model->getAll(array('parent'=>0));
        $this->data['page'] = "article_form";
        $this->data['content']=$myform->fetch('', array('data-redirect'=>1));
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Remove an article
     *
     * @param $id
     * @param int $confirm
     */
    function articleRemove($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = ARTICLES_ADMIN_URL."article";
        $self_url = ARTICLES_ADMIN_URL."articleRemove/$id";
        $data = $this->Articles_model->getOne($id);
        if(count($data)==0){
            $this->systemError("The article couldn't find.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
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
            return;
        }

        $this->Articles_model->remove($id);
        $this->systemSuccess("The article has been deleted successfully.", $back_url);
    }

    /**
     * @param $id
     */
    function articleVisibility($id)
    {
        if(!$this->checkAccessGroup(1)){
            return;
        }
        $back_url = ARTICLES_ADMIN_URL."article";
        $data= $this->Articles_model->getOne($id);
        if($data == null || count($data)==0){
            $this->systemError("Couldn't find the article.", $back_url);
            return;
        }
        $public = $this->input->post('data');
        if($public == 1){
            $public = 0;
        }elseif($public == 0){
            $public = 1;
        }else{
            $this->systemError("Visibility value isn't correct. Please reload the page to solve this problem.", $back_url);
            return;
        }
        $update_data = array(
            'public'=>$public
        );
        $this->Articles_model->edit($id, $update_data);
        $this->systemSuccess("Success", ARTICLES_ADMIN_URL."article");
    }

    /**
     * Save sort of articles
     */
    function articleSort()
    {
        if(!$this->checkAccessGroup(1)){
            return;
        }
        $i = 0;
        $index = 0;
        $parent = array(0);
        $children = array($this->input->post('data',TRUE));
        $children[$index] = json_decode($children[$index]);
        do{
            $data = $children[$index];
            foreach($data as $key=>$item){
                $i++;
                $update_data = array(
                    'order'=>$i,
                    'parent'=>$parent[$index]
                );
                $this->Articles_model->edit($item->id, $update_data);
                if(isset($item->children)){
                    $parent[$index+1] = $item->id;
                    $children[$index+1] = $item->children;
                }
            }
            $index++;
        }while(isset($children[$index]));
        $this->systemSuccess("Your articles has been successfully sorted.", ARTICLES_ADMIN_URL."article");
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
                    'url'=>base_url().$this->language['code']."/article/$item[article_uri]",
                    'label'=>_l("Display", $this)
                ),
            ),
            'sub_items'=>join("\n", $sub_item),
        );

        return $this->load->view($this->mainTemplate."/list_sort_item", $data, true);
    }
}
