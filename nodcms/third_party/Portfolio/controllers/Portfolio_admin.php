<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 3:21 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Portfolio_admin extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct('backend');
    }

    /**
     * Portfolio post list
     *
     * @param int $page
     */
    function posts($page = 1)
    {
        $self_url = PORTFOLIO_ADMIN_URL."posts";
        $config = array(
            'headers'=>array(
                array(
                    'content'=>"portfolio_image",
                    'label'=>_l("Avatar", $this),
                    'theme'=>"tiny_image",
                ),
                array('content'=>"portfolio_name", 'label'=>_l("Name", $this)),
                array(
                    'content'=>"created_date",
                    'label'=>_l("Date", $this),
                    'callback_function'=>"my_int_date",
                ),
                array(
                    'content'=>"portfolio_id",
                    'label'=>"",
                    'theme'=>"edit_btn",
                    'url'=>PORTFOLIO_ADMIN_URL.'postSubmit/$content',
                ),
                array(
                    'content'=>"portfolio_id",
                    'label'=>"",
                    'theme'=>"delete_btn",
                    'url'=>PORTFOLIO_ADMIN_URL.'postDelete/$content',
                ),
            ),
            'ajaxURL'=>$self_url,
            'page'=>$page,
            'per_page'=>10,
            'listID'=>"posts-list",
        );
        $conditions = null;
        $search_form = null;
        $sort_by = array("portfolio_id", "DESC");
        $this->load->library("Ajaxlist");
        $myList = new Ajaxlist;

        $config['total_rows'] = $this->Portfolio_model->getCount($conditions);

        $myList->setOptions($config);

        if ($this->input->is_ajax_request()) {
            $result = $this->Portfolio_model->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            echo $myList->ajaxData($result);
            return;
        }

        $this->data['title'] = _l("Portfolio posts", $this);
        $this->data['sub_title'] = _l('List', $this);
        $this->data['breadcrumb'] = array(
            array('title' => _l("Portfolio posts", $this)),
        );
        $this->data['actions_buttons'] = array(
            'add'=>PORTFOLIO_ADMIN_URL."postSubmit",
        );

        $this->data['the_list'] = $myList->getPage();
        $this->data['content'] = $this->load->view($this->mainTemplate."/data_list", $this->data, true);
        $this->data['page'] = "portfolio_posts";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Portfolio post edit/add form
     *
     * @param null $id
     */
    function postSubmit($id = null)
    {
        $back_url = PORTFOLIO_ADMIN_URL."posts";
        $self_url = PORTFOLIO_ADMIN_URL."postSubmit/$id";

        if($id!=null){
            $data = $this->Portfolio_model->getOne($id);
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

        $myform = new Form();
        $config = array(
            array(
                'field' => 'portfolio_name',
                'label' => _l("Name", $this),
                'type' => "text",
                'rules' => 'required',
                'default'=>isset($data)?$data['portfolio_name']:"",
            ),
            array(
                'field' => 'portfolio_image',
                'label' => _l("Avatar", $this),
                'type' => "image-library",
                'rules' => '',
                'default'=>isset($data)?$data['portfolio_image']:"",
            ),
            array(
                'field' => 'portfolio_date',
                'label' => _l("Date", $this),
                'type' => "date",
                'rules' => 'callback_validDate',
                'datepicker' => array(
                    'todayHighlight'=>true,
                    'dateFormat'=>$this->settings['datepicker_date_format']
                ),
                'default'=>isset($data)?my_int_date($data['portfolio_date']):"",
            ),
            array(
                'field' => 'portfolio_public',
                'label' => _l("Public", $this),
                'type' => "switch",
                'rules' => 'required|in_list[0,1]',
                'default'=>isset($data)?$data['portfolio_public']:"",
            ),
        );
        
        $languages = $this->Public_model->getAllLanguages();
        foreach ($languages as $language){
            $translate = $this->Portfolio_model->getTranslations($id, $language['language_id']);
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
                'field'=>$prefix."[details]",
                'label'=>_l("Details", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['details'])?$translate['details']:'',
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

            if(key_exists('portfolio_date', $post_data)){
                $post_data['portfolio_date'] = strtotime($post_data['portfolio_date']);
            }

            if(key_exists('translate', $post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=null){
                $this->Portfolio_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Portfolio_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("The post has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Portfolio_model->add($post_data);
                if(isset($translates)){
                    $this->Portfolio_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("A new post has been added successfully.", $back_url);
            }
            return;
        }

        $this->data['title'] = _l("Portfolio Posts", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Portfolio Posts", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $myform->fetch(null,$form_attr);
        $this->data['page'] = "portfolio_portfolio_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Portfolio post remove
     *
     * @param $id
     * @param int $confirm
     */
    function postDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = PORTFOLIO_ADMIN_URL."posts";
        $self_url = PORTFOLIO_ADMIN_URL."postDelete/$id";
        $data = $this->Portfolio_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the post.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the Portfolio post with its comments.", $this).
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

        $this->Portfolio_model->remove($id);
        $this->systemSuccess("Portfolio post has been deleted successfully.", $back_url);
    }
}