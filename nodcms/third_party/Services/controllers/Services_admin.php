<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 11:16 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class Services_admin extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct('backend');
    }

    /**
     * Sortable list display
     */
    function services()
    {
        $this->data['title'] = _l("Services",$this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );
        $this->data['add_urls'] = array(
            array('label'=>_l("Add", $this), 'url'=>SERVICES_ADMIN_URL."serviceSubmit"),
        );

        $list_items = array();
        $data_list = $this->Services_model->getAll(null,null,1,array('sort_order','asc'));
        foreach ($data_list as &$item){
            $data = array(
                'id'=>$item['service_id'],
                'element_id'=>"services-item".$item['service_id'],
                'visibility'=>$item['service_public'],
                'class'=>"parent-only",
                'title'=>$item['service_name'],
                'edit_url'=>SERVICES_ADMIN_URL."serviceSubmit/$item[service_id]",
                'remove_url'=>SERVICES_ADMIN_URL."deleteService/$item[service_id]",
                'visibility_url'=>SERVICES_ADMIN_URL."serviceVisibility/$item[service_id]",
            );
            $list_items[] = $this->load->view($this->mainTemplate."/list_sort_item", $data, true);
        }
        $this->data['save_sort_url'] = SERVICES_ADMIN_URL."sortSubmit";
        $this->data['max_depth'] = 1;
        $this->data['list_items'] = join("\n", $list_items);

        $this->data['page'] = "services_list";
        $this->data['content'] = $this->load->view($this->mainTemplate.'/list_sort',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Add/Edit submit form
     *
     * @param null|int $id
     */
    function serviceSubmit($id = null)
    {
        $self_url = SERVICES_ADMIN_URL."serviceSubmit";
        $back_url = SERVICES_ADMIN_URL."services";
        $this->data['title'] = _l("Services",$this);
        if($id!=null)
        {
            $current_data = $this->Services_model->getOne($id);
            if($current_data==null || count($current_data)==0){
                $this->systemError("Service not found.", $back_url);
                return;
            }
            $form_attr = array();
            $this->data['sub_title'] = _l("Edit",$this);
            $self_url .= "/$id";
        }
        else{
            $form_attr = array('data-redirect'=>1);
            $this->data['sub_title'] = _l("Add",$this);
        }

        $config = array(
            array(
                'field' => 'service_name',
                'label' => _l("Name", $this),
                'rules' => 'required',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["service_name"]:''
            ),
            array(
                'field' => 'service_public',
                'label' => _l("Public", $this),
                'rules' => 'in_list[0,1]',
                'type' => "switch",
                'default'=>isset($current_data)?$current_data["service_public"]:''
            ),
        );

        if($this->settings['services_display_mode']=="icon") {
            $config[] = array(
                'field' => 'service_icon',
                'label' => _l("Font Icon", $this),
                'rules' => '',
                'type' => "icons",
                'default' => isset($current_data) ? $current_data["service_icon"] : ''
            );
        }
        elseif($this->settings['services_display_mode']=="image"){
            $config[] = array(
                'field' => 'service_image',
                'label' => _l("Image", $this),
                'rules' => '',
                'type' => "image-library",
                'default'=>isset($current_data)?$current_data["service_image"]:''
            );
        }

        if($this->settings['services_display_price']){
            $config[] = array(
                'field' => 'service_price',
                'label' => _l("Price", $this),
                'type' => "currency",
                'rules' => 'callback_validCurrency',
                'divider' => '.',
                'after_sign' => $this->settings['currency_code'],
                'default'=>isset($current_data)?$this->currency->formatFloat($current_data['service_price']):"",
            );
        }

        if($this->settings['services_page']){
            $config[] = array(
                'field' => 'service_uri',
                'label' => _l("Service URI", $this),
                'rules' => 'required|callback_validURI|callback_isUnique[services,service_uri'.(isset($current_data)?",service_id,$current_data[service_id]":"").']',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["service_uri"]:'',
                'input_prefix'=>base_url().$this->language['code']."/service/",
            );
        }

        $languages = $this->Languages_model->getAll();
        foreach($languages as $language){
            $translate = $this->Services_model->getTranslations($id, $language['language_id']);
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
                'field'=>$prefix."[home_preview]",
                'label'=>_l("Home Preview", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['home_preview'])?$translate['home_preview']:'',
            ));
            if($this->settings['services_page']) {
                array_push($config, array(
                    'field'=>$prefix."[description]",
                    'label'=>_l("Description", $this),
                    'rules'=>"",
                    'type'=>"textarea",
                    'default'=>isset($translate['description'])?$translate['description']:'',
                ));
                array_push($config, array(
                    'field'=>$prefix."[keywords]",
                    'label'=>_l("Keywords", $this),
                    'rules'=>"",
                    'type'=>"textarea",
                    'default'=>isset($translate['keywords'])?$translate['keywords']:'',
                ));
                array_push($config, array(
                    'field'=>$prefix."[content]",
                    'label'=>_l("Page Content", $this),
                    'rules'=>"",
                    'type'=>"texteditor",
                    'default'=>isset($translate['content'])?$translate['content']:'',
                ));
            }
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
                $this->Services_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Services_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("Service has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Services_model->add($post_data);
                if(isset($translates)){
                    $this->Services_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("Service has been sent successfully.", $back_url);
            }
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Services", $this),'url'=>$back_url),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['page'] = "service_submit_form";
        $this->data['content']=$myform->fetch('', $form_attr);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Save new sort
     */
    function sortSubmit()
    {
        $back_url = SERVICES_ADMIN_URL."services";
        if(!$this->checkAccessGroup(1))
            return;
        $post_data = $this->input->post("data");
        if($post_data == null) {
            $this->systemError("Sort data shouldn't be empty.", $back_url);
            return;
        }
        $post_data = json_decode($post_data);
        foreach($post_data as $i=>$item){
            $update_data = array(
                'sort_order'=>$i,
            );
            $this->Services_model->edit($item->id, $update_data);
        }
        $this->systemSuccess("Services have been successfully sorted.", $back_url);
    }

    /**
     * Delete a service
     *
     * @param $id
     * @param int $confirm
     */
    function deleteService($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = SERVICES_ADMIN_URL."services";
        $self_url = SERVICES_ADMIN_URL."deleteService/$id";
        $data = $this->Services_model->getOne($id);
        if(count($data)==0){
            $this->systemError("The service couldn't find.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the service from database.", $this).
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

        $this->Services_model->remove($id);
        $this->systemSuccess("Service has been deleted successfully.", $back_url);
    }

    function settings()
    {
        $self_url = SERVICES_ADMIN_URL."settings";
        $back_url = SERVICES_ADMIN_URL."services";
        $this->data['title'] = _l("Services Settings",$this);

        $display_modes = array(
            array('name'=>_l("With image", $this),'value'=>"image"),
            array('name'=>_l("With icon", $this),'value'=>"icon"),
        );
        $config = array(
            array(
                'field' => 'services_display_mode',
                'label' => _l('Display mode',$this),
                'rules' => 'required|in_list['.join(",", array_column($display_modes, "value")).']',
                'type' => "select-radio",
                'options'=>$display_modes,
                'option_name'=>"name",
                'option_value'=>"value",
                'default'=>$this->settings["services_display_mode"]
            ),
            array(
                'field' => 'services_display_price',
                'label' => _l('Display prices',$this),
                'rules' => 'required|in_list[0,1]',
                'type' => "switch",
                'default'=>$this->settings["services_display_price"]
            ),
            array(
                'field' => 'services_page',
                'label' => _l("Content page", $this),
                'help' => _l("Create a web page for each service to have some content.", $this),
                'rules' => 'required|in_list[0,1]',
                'type' => "switch",
                'default'=>$this->settings["services_page"]
            ),
        );

        $languages = $this->Languages_model->getAll();
        foreach($languages as $language){
            $config[] = array(
                'label'=>$language['language_title'],
                'type'=>"h4",
                'prefix_language'=>$language,
            );
            $prefix = "options[$language[language_id]]";
            $setting = $this->Public_model->getSettings($language['language_id']);
            $config[] = array(
                'field'=>$prefix."[services_page_title]",
                'label' => _l("Page title", $this),
                'rules' => "",
                'type' => "text",
                'default'=>isset($setting['services_page_title'])?$setting['services_page_title']:'',
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
            if(isset($data["options"])){
                foreach($data["options"] as $language_id=>$item){
                    if(!$this->Nodcms_admin_model->updateSettings($item, $language_id)){
                        $this->systemError("A settings options could not be saved.", $this);
                        return;
                    }
                }
                unset($data["options"]);
            }
            $this->Nodcms_admin_model->updateSettings($data);
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