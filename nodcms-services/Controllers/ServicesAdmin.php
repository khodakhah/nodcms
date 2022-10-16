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

namespace NodCMS\Services\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Backend;
use NodCMS\Core\Libraries\Form;
use NodCMS\Services\Config\Models;

class ServicesAdmin extends Backend
{
    /**
     * Sortable list display
     *
     * @return string
     */
    public function services(): string
    {
        $this->data['title'] = _l("Services", $this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );
        $this->data['add_urls'] = array(
            array('label'=>_l("Add", $this), 'url'=>SERVICES_ADMIN_URL."serviceSubmit"),
        );

        $list_items = array();
        $data_list = Models::services()->getAll(null, null, 1, array('sort_order','asc'));
        foreach ($data_list as &$item) {
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
            $list_items[] = Services::layout()->setData($data)->render("list_sort_item");
        }
        $this->data['save_sort_url'] = SERVICES_ADMIN_URL."sortSubmit";
        $this->data['max_depth'] = 1;
        $this->data['list_items'] = join("\n", $list_items);

        $this->data['page'] = "services_list";
        return $this->viewRender("list_sort");
    }

    /**
     * Add/Edit submit form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string|void
     * @throws \Exception
     */
    public function serviceSubmit(int $id = 0)
    {
        $self_url = SERVICES_ADMIN_URL."serviceSubmit";
        $back_url = SERVICES_ADMIN_URL."services";
        $this->data['title'] = _l("Services", $this);
        if ($id!=0) {
            $current_data = Models::services()->getOne($id);
            if (empty($current_data)) {
                return $this->errorMessage("Service not found.", $back_url);
            }
            $form_attr = array();
            $this->data['sub_title'] = _l("Edit", $this);
            $self_url .= "/$id";
        } else {
            $form_attr = array('data-redirect'=>1);
            $this->data['sub_title'] = _l("Add", $this);
        }

        $config = array(
            array(
                'field' => 'service_name',
                'label' => _l("Name", $this),
                'rules' => 'required',
                'type' => "text",
                'default'=>isset($current_data) ? $current_data["service_name"] : ''
            ),
            array(
                'field' => 'service_public',
                'label' => _l("Public", $this),
                'rules' => 'in_list[0,1]',
                'type' => "switch",
                'default'=>isset($current_data) ? $current_data["service_public"] : ''
            ),
        );

        if ($this->settings['services_display_mode']=="icon") {
            $config[] = array(
                'field' => 'service_icon',
                'label' => _l("Font Icon", $this),
                'rules' => '',
                'type' => "icons",
                'default' => isset($current_data) ? $current_data["service_icon"] : ''
            );
        } elseif ($this->settings['services_display_mode']=="image") {
            $config[] = array(
                'field' => 'service_image',
                'label' => _l("Image", $this),
                'rules' => '',
                'type' => "image-library",
                'default'=>isset($current_data) ? $current_data["service_image"] : ''
            );
        }

        if ($this->settings['services_display_price']) {
            $config[] = array(
                'field' => 'service_price',
                'label' => _l("Price", $this),
                'type' => "currency",
                'rules' => 'validCurrency',
                'divider' => '.',
                'after_sign' => $this->settings['currency_code'],
                'default'=>isset($current_data) ? Services::currency()->formatFloat($current_data['service_price']) : "",
            );
        }

        if ($this->settings['services_page']) {
            $config[] = array(
                'field' => 'service_uri',
                'label' => _l("Service URI", $this),
                'rules' => 'required|validURI|is_unique[services.service_uri'.(isset($current_data) ? ",service_id,$current_data[service_id]" : "").']',
                'type' => "text",
                'default'=>isset($current_data) ? $current_data["service_uri"] : '',
                'input_prefix'=>base_url($this->language['code']."/service/"),
            );
        }

        $languages = Models::languages()->getAll();
        foreach ($languages as $language) {
            $translate = Models::services()->getTranslations($id, $language['language_id']);
            // Add language title
            array_push($config, array(
                'prefix_language'=>$language,
                'label'=>$language['language_title'],
                'type'=>"h4",
            ));
            $prefix = "translate[$language[language_id]]";
            array_push($config, array(
                'field'=>$prefix."[title]",
                'label'=>_l('Title', $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['title']) ? $translate['title'] : '',
            ));
            array_push($config, array(
                'field'=>$prefix."[home_preview]",
                'label'=>_l("Home Preview", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['home_preview']) ? $translate['home_preview'] : '',
            ));
            if ($this->settings['services_page']) {
                array_push($config, array(
                    'field'=>$prefix."[description]",
                    'label'=>_l("Description", $this),
                    'rules'=>"",
                    'type'=>"textarea",
                    'default'=>isset($translate['description']) ? $translate['description'] : '',
                ));
                array_push($config, array(
                    'field'=>$prefix."[keywords]",
                    'label'=>_l("Keywords", $this),
                    'rules'=>"",
                    'type'=>"textarea",
                    'default'=>isset($translate['keywords']) ? $translate['keywords'] : '',
                ));
                array_push($config, array(
                    'field'=>$prefix."[content]",
                    'label'=>_l("Page Content", $this),
                    'rules'=>"",
                    'type'=>"texteditor",
                    'default'=>isset($translate['content']) ? $translate['content'] : '',
                ));
            }
        }

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');

        if ($myform->ispost()) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }
            $post_data = $myform->getPost();
            // Stop Page
            if ($post_data === false || !is_array($post_data)) {
                return;
            }

            if (key_exists('translate', $post_data)) {
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if ($id!=0) {
                Models::services()->edit($id, $post_data);
                if (isset($translates)) {
                    Models::services()->updateTranslations($id, $translates, $languages);
                }
                return $this->successMessage("Service has been edited successfully.", $back_url);
            } else {
                $new_id = Models::services()->add($post_data);
                if (isset($translates)) {
                    Models::services()->updateTranslations($new_id, $translates, $languages);
                }
                return $this->successMessage("Service has been sent successfully.", $back_url);
            }
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Services", $this),'url'=>$back_url),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['page'] = "service_submit_form";
        return $this->viewRenderString($myform->fetch('', $form_attr));
    }

    /**
     * Save new sort
     */
    public function sortSubmit()
    {
        $back_url = SERVICES_ADMIN_URL."services";
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        $post_data = Services::request()->getPost("data");
        if ($post_data == null) {
            return $this->errorMessage("Sort data shouldn't be empty.", $back_url);
        }
        $post_data = json_decode($post_data);
        foreach ($post_data as $i=>$item) {
            $update_data = array(
                'sort_order'=>$i,
            );
            Models::services()->edit($item->id, $update_data);
        }
        return $this->successMessage("Services have been successfully sorted.", $back_url);
    }

    /**
     * Delete a service
     *
     * @param $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function deleteService($id, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $back_url = SERVICES_ADMIN_URL."services";
        $self_url = SERVICES_ADMIN_URL."deleteService/$id";
        $data = Models::services()->getOne($id);
        if (count($data)==0) {
            return $this->errorMessage("The service couldn't find.", $back_url);
        }

        if ($confirm!=1) {
            return json_encode(array(
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
        }

        Models::services()->remove($id);
        return $this->successMessage("Service has been deleted successfully.", $back_url);
    }

    /**
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string|void
     * @throws \Exception
     */
    public function settings()
    {
        $self_url = SERVICES_ADMIN_URL."settings";
        $back_url = SERVICES_ADMIN_URL."services";
        $this->data['title'] = _l("Services Settings", $this);

        $display_modes = array(
            array('name'=>_l("With image", $this),'value'=>"image"),
            array('name'=>_l("With icon", $this),'value'=>"icon"),
        );
        $config = array(
            array(
                'field' => 'services_display_mode',
                'label' => _l('Display mode', $this),
                'rules' => 'required|in_list['.join(",", array_column($display_modes, "value")).']',
                'type' => "select-radio",
                'options'=>$display_modes,
                'option_name'=>"name",
                'option_value'=>"value",
                'default'=>$this->settings["services_display_mode"]
            ),
            array(
                'field' => 'services_display_price',
                'label' => _l('Display prices', $this),
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

        $languages = Models::languages()->getAll();
        foreach ($languages as $language) {
            $config[] = array(
                'label'=>$language['language_title'],
                'type'=>"h4",
                'prefix_language'=>$language,
            );
            $prefix = "options[$language[language_id]]";
            $setting = Models::settings()->getSettings($language['language_id']);
            $config[] = array(
                'field'=>$prefix."[services_page_title]",
                'label' => _l("Page title", $this),
                'rules' => "",
                'type' => "text",
                'default'=>isset($setting['services_page_title']) ? $setting['services_page_title'] : '',
            );
        }
        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');

        if ($myform->ispost()) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            if (isset($data["options"])) {
                foreach ($data["options"] as $language_id=>$item) {
                    if (!Models::settings()->updateSettings($item, $language_id)) {
                        return $this->errorMessage("A settings options could not be saved.", $this);
                    }
                }
                unset($data["options"]);
            }
            Models::settings()->updateSettings($data);
            return $this->successMessage("Your Setting has been updated successfully!", $self_url);
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Services", $this),'url'=>$back_url),
            array('title'=>$this->data['title']),
        );

        $this->data['page'] = "services_settings";
        return $this->viewRenderString($myform->fetch());
    }
}
