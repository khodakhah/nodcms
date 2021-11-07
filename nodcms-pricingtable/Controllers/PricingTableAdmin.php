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

namespace NodCMS\Pricingtable\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Backend;
use NodCMS\Core\Libraries\Form;
use NodCMS\Pricingtable\Config\Models;

class PricingTableAdmin extends Backend
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Table post list
     */
    function tables(): string
    {
        $this->data['title'] = _l("Pricing Tables",$this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );

        $list_items = array();
        $data_list = Models::pricingTable()->getAll(null,null,1,array('sort_order','ASC'));
        foreach ($data_list as &$item){
            $_sub_items = array();
            $item['table_name'] = '<i class="fas fa-table"></i> '.$item['table_name'];
            $sub_items = Models::pricingTableRecord()->getAll(array('table_id'=>$item['table_id']), null, 1, array("sort_order","ASC"));
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
                    $_sub_items[] = Services::layout()->setData($data)->render("list_sort_item");
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
            $list_items[] = Services::layout()->setData($data)->render("list_sort_item");
        }
        $this->data['data_list'] = $data_list;

        $this->data['max_depth'] = 2;
        $this->data['list_items'] = join("\n", $list_items);

        $this->data['add_urls'] = array(
            array('label'=>_l("New Table", $this), 'url'=>PRICING_TABLE_ADMIN_URL."tableSubmit"),
            array('label'=>_l("New Record", $this), 'url'=>PRICING_TABLE_ADMIN_URL."recordSubmit"),
        );
        $this->data['save_sort_url'] = PRICING_TABLE_ADMIN_URL."sortSubmit";
        $this->data['page'] = "pricing_tables";

        return $this->viewRender("list_sort");
    }

    /**
     * Save new sort
     * @throws \Exception
     */
    function sortSubmit()
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();
        $post_data = Services::request()->getPost("data");
        if($post_data == null) {
            return $this->errorMessage("Sort data shouldn't be empty.", $back_url);
        }
        $post_data = json_decode($post_data);
        foreach($post_data as $i=>$item){
            if(isset($item->children) && is_array($item->children)){
                foreach($item->children as $j=>$sub_item){
                    $update_data = array(
                        'sort_order'=>$j,
                        'table_id'=>$item->id,
                    );
                    Models::pricingTableRecord()->edit($sub_item->id, $update_data);
                }
            }
            $update_data = array(
                'sort_order'=>$i,
            );
            Models::pricingTable()->edit($item->id, $update_data);
        }
        return $this->successMessage("Pricing tables have been successfully sorted.", $back_url);
    }

    /**
     * Table post edit/add form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function tableSubmit(int $id = 0)
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."tableSubmit/$id";

        if($id!=0){
            $data = Models::pricingTable()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("The table couldn't find.", $back_url);
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
                'rules' => 'validCurrency',
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

        $languages = Models::languages()->getAll();
        foreach ($languages as $language){
            $translate = Models::pricingTable()->getTranslations($id, $language['language_id']);
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
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if(key_exists('translate', $post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=null){
                Models::pricingTable()->edit($id, $post_data);
                if(isset($translates)){
                    Models::pricingTable()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("The table has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::pricingTable()->add($post_data);
                if(isset($translates)){
                    Models::pricingTable()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("A new table has been added successfully.", $back_url);
            }
        }

        $this->data['title'] = _l("Table tables", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Table tables", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['page'] = "pricing_table_submit";
        return $this->viewRenderString($myform->fetch(null,$form_attr));
    }

    /**
     * @param $id
     *
     * Toggle table_public value
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function tableVisibility($id)
    {
        if(!Services::identity()->isAdmin()){
            return Services::identity()->getResponse();
        }
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $data= Models::pricingTable()->getOne($id);
        if($data == null || count($data)==0){
            return $this->errorMessage("Couldn't find the table.", $back_url);
        }
        $public = Services::request()->getPost('data');
        if($public == 1){
            $public = 0;
        }elseif($public == 0){
            $public = 1;
        }else{
            return $this->errorMessage("Visibility value isn't correct. Please reload the page to solve this problem.", $back_url);
        }
        $update_data = array(
            'table_public'=>$public
        );
        Models::pricingTable()->edit($id, $update_data);
        return $this->successMessage("Success", PRICING_TABLE_ADMIN_URL."tables");
    }

    /**
     * Table post remove
     *
     * @param int $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function deleteTable(int $id, int $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."deleteTable/$id";
        $data = Models::pricingTable()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the table.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
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
        }

        $childes = Models::pricingTableRecord()->getAll(array('table_id'=>$id));
        foreach($childes as $item){
            Models::pricingTableRecord()->remove($item['record_id']);
        }
        Models::pricingTable()->remove($id);
        return $this->successMessage("Pricing table has been deleted successfully.", $back_url);
    }

    /**
     * Table post edit/add form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function recordSubmit(int$id = 0)
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."recordSubmit/$id";

        if($id!=0){
            $data = Models::pricingTableRecord()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("The record couldn't find.", $back_url);
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array('data-redirect'=>1);
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1,'data-redirect'=>1);
        }

        $tables = Models::pricingTable()->getAll();
        if(empty($tables)){
            return $this->errorMessage("Before add a record you need to have a table.", $back_url);
        }

        if($id == 0){
            array_unshift($tables, array(
                'table_name'=>_l("All tables", $this),
                'table_id'=>0,
            ));
        }

        $myform = new Form($this);
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

        $languages = Models::languages()->getAll();
        foreach ($languages as $language){
            $translate = Models::pricingTable()->getTranslations($id, $language['language_id']);
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
            if(!Services::identity()->isAdmin())
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
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
                    $new_id = Models::pricingTableRecord()->add($post_data);
                    if(isset($translates)){
                        Models::pricingTableRecord()->updateTranslations($new_id,$translates,$languages);
                    }
                }
                return $this->successMessage("A new record has been on all tables added successfully.", $back_url);
            }

            if($id!=0){
                Models::pricingTableRecord()->edit($id, $post_data);
                if(isset($translates)){
                    Models::pricingTableRecord()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("The table record has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::pricingTableRecord()->add($post_data);
                if(isset($translates)){
                    Models::pricingTableRecord()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("A new table record has been added successfully.", $back_url);
            }
        }

        $this->data['title'] = _l("Table Records", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Tables Records", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['page'] = "pricing_table_record_submit";
        return $this->viewRenderString($myform->fetch(null,$form_attr));
    }

    /**
     * Save new sort
     * @throws \Exception
     */
    function recordSortSubmit()
    {
        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();
        $post_data = Services::request()->getPost("data");
        if($post_data == null) {
            return $this->errorMessage("Sort data shouldn't be empty.", $back_url);
        }
        $post_data = json_decode($post_data);
        foreach($post_data as $i=>$item){
            $update_data = array(
                'sort_order'=>$i,
            );
            Models::pricingTableRecord()->edit($item->id, $update_data);
        }
        return $this->successMessage("Pricing table records have been successfully sorted.", $back_url);
    }

    /**
     * Table post remove
     *
     * @param int $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function deleteRecord(int $id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = PRICING_TABLE_ADMIN_URL."tables";
        $self_url = PRICING_TABLE_ADMIN_URL."deleteRecord/$id";
        $data = Models::pricingTableRecord()->getOne($id);
        if($data==null || count($data)==0){
            return $this->errorMessage("Couldn't find the record.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
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
        }

        Models::pricingTableRecord()->remove($id);
        return $this->successMessage("Pricing table record has been deleted successfully.", $back_url);
    }
}
