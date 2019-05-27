<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 7:52 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Pricing_table_admin extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct('backend');
    }

    /**
     * Table post list
     */
    function tables()
    {
        $this->data['title'] = _l("Pricing Tables",$this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );

        $list_items = array();
        $data_list = $this->Pricing_table_model->getAll(null,null,1,array('sort_order','asc'), null, 1, array("sort_order","ASC"));
        foreach ($data_list as &$item){
            $_sub_items = array();
            $item['table_name'] = '<i class="fas fa-table"></i> '.$item['table_name'];
            $sub_items = $this->Pricing_table_record_model->getAll(array('table_id'=>$item['table_id']), null, 1, array("sort_order","ASC"));
            if($sub_items!=null && count($sub_items)!=0){
                foreach($sub_items as $sub_item){
                    $data = array(
                        'id'=>$sub_item['record_id'],
                        'element_id'=>"record-item".$sub_item['record_id'],
                        'class'=>"child-only",
                        'visibility'=>1,
                        'title'=>$sub_item['record_name'],
                        'edit_url'=>PRICING_TABLE_ADMIN_URL."recordSubmit/$sub_item[record_id]",
                        'remove_url'=>PRICING_TABLE_ADMIN_URL."deleteRecord/$sub_item[record_id]",
                        'visibility_url'=>"",
                        'sub_items'=>"",
                    );
                    $_sub_items[] = $this->load->view($this->mainTemplate."/list_sort_item", $data, true);
                }
            }

            $data = array(
                'id'=>$item['table_id'],
                'element_id'=>"table-item".$item['table_id'],
                'visibility'=>$item['table_public'],
                'class'=>"parent-only",
                'title'=>$item['table_name'],
                'edit_url'=>PRICING_TABLE_ADMIN_URL."tableSubmit/$item[table_id]",
                'remove_url'=>PRICING_TABLE_ADMIN_URL."deleteTable/$item[table_id]",
                'visibility_url'=>PRICING_TABLE_ADMIN_URL."tableVisibility/$item[table_id]",
                'sub_items'=>join("\n",$_sub_items),
            );
            $list_items[] = $this->load->view($this->mainTemplate."/list_sort_item", $data, true);
        }
        $this->data['data_list'] = $data_list;

        $this->data['max_depth'] = 2;
        $this->data['list_items'] = join("\n", $list_items);

        $this->data['add_urls'] = array(
            array('label'=>_l("New Table", $this), 'url'=>PRICING_TABLE_ADMIN_URL."tableSubmit"),
            array('label'=>_l("New Record", $this), 'url'=>PRICING_TABLE_ADMIN_URL."recordSubmit"),
        );
        $this->data['save_sort_url'] = PRICING_TABLE_ADMIN_URL."sortSubmit/";
        $this->data['page'] = "pricing_tables";

        $this->data['content'] = $this->load->view($this->mainTemplate.'/list_sort',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Save new sort
     */
    function sortSubmit()
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        if(!$this->checkAccessGroup(1))
            return;
        $post_data = $this->input->post("data");
        if($post_data == null) {
            $this->systemError("Sort data shouldn't be empty.", $back_url);
            return;
        }
        $post_data = json_decode($post_data);
        foreach($post_data as $i=>$item){
            if(isset($item->children) && is_array($item->children)){
                foreach($item->children as $j=>$sub_item){
                    $update_data = array(
                        'sort_order'=>$j,
                        'table_id'=>$item->id,
                    );
                    $this->Pricing_table_record_model->edit($sub_item->id, $update_data);
                }
            }
            $update_data = array(
                'sort_order'=>$i,
            );
            $this->Pricing_table_model->edit($item->id, $update_data);
        }
        $this->systemSuccess("Pricing tables have been successfully sorted.", $back_url);
    }

    /**
     * Table post edit/add form
     *
     * @param null $id
     */
    function tableSubmit($id = null)
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."tableSubmit/$id";

        if($id!=null){
            $data = $this->Pricing_table_model->getOne($id);
            if(count($data)==0){
                $this->systemError("The table couldn't find.", $back_url);
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
                'field' => 'table_name',
                'label' => _l("Name", $this),
                'type' => "text",
                'rules' => 'required',
                'default'=>isset($data)?$data['table_name']:"",
            ),
            array(
                'field' => 'table_price',
                'label' => _l("Price", $this),
                'type' => "currency",
                'rules' => 'callback_validCurrency',
                'default_formatted' => $this->settings['currency_format'],
                'divider' => $this->settings['currency_code'] == "1.234,56"?',':'.',
                'after_sign' => $this->settings['currency_code'],
                'default'=>isset($data)?$data['table_price']:0,
            ),
            array(
                'field' => 'table_public',
                'label' => _l("Public", $this),
                'type' => "switch",
                'rules' => 'required|in_list[0,1]',
                'default'=>isset($data)?$data['table_public']:"",
            ),
            array(
                'field' => 'table_highlight',
                'label' => _l("Highlight", $this),
                'type' => "switch",
                'rules' => 'required|in_list[0,1]',
                'default'=>isset($data)?$data['table_highlight']:"",
            ),
            array(
                'field' => 'table_url',
                'label' => _l("URL", $this),
                'type' => "text",
                'rules' => '',
                'default'=>isset($data)?$data['table_url']:"",
            ),
        );

        $languages = $this->Public_model->getAllLanguages();
        foreach ($languages as $language){
            $translate = $this->Pricing_table_model->getTranslations($id, $language['language_id']);
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
                'field'=>$prefix."[btn_label]",
                'label'=>_l("Button label", $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['btn_label'])?$translate['btn_label']:'',
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

            if($id!=null){
                $this->Pricing_table_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Pricing_table_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("The table has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Pricing_table_model->add($post_data);
                if(isset($translates)){
                    $this->Pricing_table_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("A new table has been added successfully.", $back_url);
            }
            return;
        }

        $this->data['title'] = _l("Table tables", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Table tables", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $myform->fetch(null,$form_attr);
        $this->data['page'] = "pricing_table_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * @param $id
     *
     * Toggle table_public value
     */
    function tableVisibility($id)
    {
        if(!$this->checkAccessGroup(1)){
            return;
        }
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $data= $this->Pricing_table_model->getOne($id);
        if($data == null || count($data)==0){
            $this->systemError("Couldn't find the table.", $back_url);
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
            'table_public'=>$public
        );
        $this->Pricing_table_model->edit($id, $update_data);
        $this->systemSuccess("Success", PRICING_TABLE_ADMIN_URL."tables");
    }

    /**
     * Table post remove
     *
     * @param $id
     * @param int $confirm
     */
    function deleteTable($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."deleteTable/$id";
        $data = $this->Pricing_table_model->getOne($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the table.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the pricing table with its records.", $this).
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

        $childes = $this->Pricing_table_record_model->getAll(array('table_id'=>$id));
        foreach($childes as $item){
            $this->Pricing_table_record_model->remove($item['record_id']);
        }
        $this->Pricing_table_model->remove($id);
        $this->systemSuccess("Pricing table has been deleted successfully.", $back_url);
    }

    /**
     * Table post edit/add form
     *
     * @param null $id
     */
    function recordSubmit($id = null)
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."recordSubmit/$id";

        if($id!=null){
            $data = $this->Pricing_table_record_model->getOne($id);
            if(count($data)==0){
                $this->systemError("The record couldn't find.", $back_url);
                return;
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1,'data-redirect'=>1);
        }

        $tables = $this->Pricing_table_model->getAll();
        if($tables==null || count($tables)==0){
            $this->systemError("Before add a record you need to have a table.", $back_url);
            return;
        }

        if($id ==  null){
            array_unshift($tables, array(
                'table_name'=>_l("All tables", $this),
                'table_id'=>0,
            ));
        }

        $myform = new Form();
        $config = array(
            array(
                'field' => 'record_name',
                'label' => _l("Name", $this),
                'type' => "text",
                'rules' => 'required',
                'default'=>isset($data)?$data['record_name']:"",
            ),
            array(
                'field' => 'table_id',
                'label' => _l("Table", $this),
                'type' => "select",
                'rules' => 'required|in_list['.join(",", array_column($tables, "table_id")).']',
                'options'=>$tables,
                'option_name'=>"table_name",
                'option_value'=>"table_id",
                'default'=>isset($data)?$data['table_id']:"",
            ),
        );

        $languages = $this->Public_model->getAllLanguages();
        foreach ($languages as $language){
            $translate = $this->Pricing_table_model->getTranslations($id, $language['language_id']);
            array_push($config,array(
                'prefix_language'=>$language,
                'label'=>$language['language_title'],
                'type'=>"h4",
            ));
            $prefix = "translate[$language[language_id]]";
            array_push($config, array(
                'field'=>$prefix."[label]",
                'label'=>_l("Label", $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['title'])?$translate['title']:'',
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

            // Add all under all tables
            if($post_data['table_id']==0){
                foreach($tables as $i=>$table){
                    if($i==0)
                        continue;
                    $post_data['table_id'] = $table['table_id'];
                    $new_id = $this->Pricing_table_record_model->add($post_data);
                    if(isset($translates)){
                        $this->Pricing_table_record_model->updateTranslations($new_id,$translates,$languages);
                    }
                }
                $this->systemSuccess("A new record has been on all tables added successfully.", $back_url);
                return;
            }

            if($id!=null){
                $this->Pricing_table_record_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->Pricing_table_record_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("The table record has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->Pricing_table_record_model->add($post_data);
                if(isset($translates)){
                    $this->Pricing_table_record_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("A new table record has been added successfully.", $back_url);
            }

            return;
        }

        $this->data['title'] = _l("Table Records", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Tables Records", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $myform->fetch(null,$form_attr);
        $this->data['page'] = "pricing_table_record_submit";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Save new sort
     */
    function recordSortSubmit()
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
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
            $this->Pricing_table_record_model->edit($item->id, $update_data);
        }
        $this->systemSuccess("Pricing table records have been successfully sorted.", $back_url);
    }

    /**
     * Table post remove
     *
     * @param $id
     * @param int $confirm
     */
    function deleteRecord($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."deleteRecord/$id";
        $data = $this->Pricing_table_record_model->getOne($id);
        if($data==null || count($data)==0){
            $this->systemError("Couldn't find the record.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the pricing table record.", $this).
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

        $this->Pricing_table_record_model->remove($id);
        $this->systemSuccess("Pricing table record has been deleted successfully.", $back_url);
    }
}