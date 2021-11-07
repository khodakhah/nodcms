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

namespace NodCMS\Portfolio\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Backend;
use NodCMS\Core\Libraries\Ajaxlist;
use NodCMS\Core\Libraries\Form;
use NodCMS\Portfolio\Config\Models;
use NodCMS\Portfolio\Config\ViewBackend;

class PortfolioAdmin extends Backend
{
    /**
     * Portfolio post list
     *
     * @param int $page
     * @return false|string
     */
    function posts(int $page = 1)
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
        $myList = new Ajaxlist();

        $config['total_rows'] = Models::portfolio()->getCount($conditions);

        $myList->setOptions($config);

        if (Services::request()->isAJAX()) {
            $result = Models::portfolio()->getAll($conditions, $config['per_page'], $config['page'], $sort_by);
            return $myList->ajaxData($result);
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
        $this->data['page'] = "portfolio_posts";
        return $this->viewRender("data_list");
    }

    /**
     * Portfolio post edit/add form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function postSubmit(int $id = 0)
    {
        $back_url = PORTFOLIO_ADMIN_URL."posts";
        $self_url = PORTFOLIO_ADMIN_URL."postSubmit/$id";

        if($id!=0){
            $data = Models::portfolio()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("The post couldn't find.", $back_url);
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
                'rules' => 'validDate',
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

        $languages = Models::languages()->getAll();
        foreach ($languages as $language){
            $translate = Models::portfolio()->getTranslations($id, $language['language_id']);
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
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if(key_exists('portfolio_date', $post_data)){
                $post_data['portfolio_date'] = strtotime($post_data['portfolio_date']);
            }

            if(key_exists('translate', $post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=0){
                Models::portfolio()->edit($id, $post_data);
                if(isset($translates)){
                    Models::portfolio()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("The post has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::portfolio()->add($post_data);
                if(isset($translates)){
                    Models::portfolio()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("A new post has been added successfully.", $back_url);
            }
        }

        $this->data['title'] = _l("Portfolio Posts", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Portfolio Posts", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['page'] = "portfolio_portfolio_submit";
        return $this->viewRenderString($myform->fetch(null,$form_attr));
    }

    /**
     * Portfolio post remove
     *
     * @param int $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function postDelete(int $id, int $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = PORTFOLIO_ADMIN_URL."posts";
        $self_url = PORTFOLIO_ADMIN_URL."postDelete/$id";
        $data = Models::portfolio()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the post.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
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
        }

        Models::portfolio()->remove($id);
        return $this->successMessage("Portfolio post has been deleted successfully.", $back_url);
    }
}
