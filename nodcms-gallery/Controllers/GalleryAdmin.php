<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Gallery\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Backend;
use NodCMS\Core\Libraries\Form;
use NodCMS\Gallery\Config\Models;
use NodCMS\Gallery\Config\ViewBackend;

class GalleryAdmin extends Backend
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewBackend());
    }

    /**
     * Sortable list of galleries
     */
    function galleries()
    {
        $this->data['title'] = _l("Galleries",$this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );
        $this->data['add_urls'] = array(
            array('label'=>_l("Add", $this), 'url'=>GALLERY_ADMIN_URL."gallerySubmit"),
        );

        $list_items = array();
        $data_list = Models::gallery()->getAll(null,null,1,array('sort_order','asc'));
        foreach ($data_list as &$item){
            $data = array(
                'id'=>$item['gallery_id'],
                'element_id'=>"galleries-item".$item['gallery_id'],
                'visibility'=>$item['gallery_public'],
                'class'=>"parent-only",
                'title'=>$item['gallery_name'],
                'edit_url'=>GALLERY_ADMIN_URL."gallerySubmit/$item[gallery_id]",
                'remove_url'=>GALLERY_ADMIN_URL."galleryDelete/$item[gallery_id]",
                'visibility_url'=>GALLERY_ADMIN_URL."galleryVisibility/$item[gallery_id]",
                'btn_urls'=>array(
                    array(
                        'url'=>GALLERY_ADMIN_URL."images/$item[gallery_id]",
                        'label'=>_l("Images", $this)
                    ),
                ),
            );
            $list_items[] = Services::layout()->setData($data)->render("list_sort_item");
        }
        $this->data['save_sort_url'] = GALLERY_ADMIN_URL."sortSubmit";
        $this->data['max_depth'] = 1;
        $this->data['list_items'] = join("\n", $list_items);

        $this->data['page'] = "galleries";
        return $this->viewRender('list_sort');
    }

    /**
     * Add/Edit submit form of a gallery
     *
     * @param null|int $id
     */
    function gallerySubmit($id = null)
    {
        $self_url = GALLERY_ADMIN_URL."gallerySubmit";
        $back_url = GALLERY_ADMIN_URL."galleries";
        $this->data['title'] = _l("Galleries",$this);
        if($id!=null)
        {
            $current_data = Models::gallery()->getOne($id);
            if($current_data==null || count($current_data)==0){
                return $this->errorMessage("Gallery not found.", $back_url);
            }
            $form_attr = array();
            $this->data['sub_title'] = _l("Edit",$this);
            $self_url .= "/$id";
            $this->data['breadcrumb_options'] = array(
                array('title'=>_l("Images", $this),'url'=>GALLERY_ADMIN_URL."images/$id"),
                array('title'=>_l("Edit", $this),'url'=>GALLERY_ADMIN_URL."gallerySubmit/$id",'active'=>1),
            );
        }
        else{
            $form_attr = array('data-redirect'=>1);
            $this->data['sub_title'] = _l("Add",$this);
        }

        $config = array(
            array(
                'field' => 'gallery_uri',
                'label' => _l("Service URI", $this),
                'rules' => 'required|callback_validURI|callback_isUnique[gallery,gallery_uri'.(isset($current_data)?",gallery_id,$current_data[gallery_id]":"").']',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["gallery_uri"]:'',
                'input_prefix'=>base_url().$this->language['code']."/gallery/",
            ),
            array(
                'field' => 'gallery_name',
                'label' => _l("Name", $this),
                'rules' => 'required',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["gallery_name"]:''
            ),
            array(
                'field' => 'gallery_image',
                'label' => _l("Image", $this),
                'rules' => '',
                'type' => "image-library",
                'default'=>isset($current_data)?$current_data["gallery_image"]:''
            ),
            array(
                'field' => 'gallery_public',
                'label' => _l("Public", $this),
                'rules' => 'in_list[0,1]',
                'type' => "switch",
                'default'=>isset($current_data)?$current_data["gallery_public"]:''
            ),
            array(
                'label'=>_l('Page content',$this),
                'type'=>"h3",
            ),
        );

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');
        $languages = Models::languages()->getAll();
        foreach($languages as $language){
            $translate = Models::gallery()->getTranslations($id, $language['language_id']);
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
                'field'=>$prefix."[details]",
                'label'=>_l("Gallery description", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['details'])?$translate['details']:'',
            ));
        }

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');

        if($myform->ispost()){
            if(!Services::identity()->isAdmin(true)){
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

            if($id!=null){
                Models::gallery()->edit($id, $post_data);
                if(isset($translates)){
                    Models::gallery()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("Service has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::gallery()->add($post_data);
                if(isset($translates)){
                    Models::gallery()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("Gallery has been sent successfully.", $back_url);
            }
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Galleries", $this),'url'=>$back_url),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['page'] = "gallery_submit_form";
        $this->data['content']=$myform->fetch('', $form_attr);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Save new sort
     */
    function sortSubmit()
    {
        $back_url = GALLERY_ADMIN_URL."galleries";
        if(!Services::identity()->isAdmin(true))
            return Services::identity()->getResponse();
        $post_data = $this->input->post("data");
        if($post_data == null) {
            return $this->errorMessage("Sort data shouldn't be empty.", $back_url);
            return;
        }
        $post_data = json_decode($post_data);
        foreach($post_data as $i=>$item){
            $update_data = array(
                'sort_order'=>$i,
            );
            Models::gallery()->edit($item->id, $update_data);
        }
        return $this->successMessage("Galleries have been successfully sorted.", $back_url);
    }

    /**
     * Delete a gallery
     *
     * @param $id
     * @param int $confirm
     */
    function galleryDelete($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin(true))
            return Services::identity()->getResponse();

        $back_url = GALLERY_ADMIN_URL."galleries";
        $self_url = GALLERY_ADMIN_URL."galleryDelete/$id";
        $data = Models::gallery()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("The gallery couldn't find.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the gallery and its all images from database.", $this).
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

        $childes = Models::galleryImages()->getAll(array('gallery_id'=>$id));
        foreach($childes as $item){
            Models::galleryImages()->remove($item['image_id']);
        }
        Models::gallery()->remove($id);
        return $this->successMessage("Gallery has been deleted successfully.", $back_url);
    }

    /**
     * Display all images of a gallery
     *
     * @param int $gallery_id
     */
    function images($gallery_id)
    {
        $back_url = GALLERY_ADMIN_URL."galleries";
        $current_data = Models::gallery()->getOne($gallery_id);
        if(!is_array($current_data) || count($current_data)==0){
            return $this->errorMessage("Gallery not found.", $back_url);
            return;
        }
        $self_url = GALLERY_ADMIN_URL."images";
        $this->data['data_list'] = Models::galleryImages()->getAll(array('gallery_id'=>$gallery_id), null, 1, array('image_id','DESC'));
        $this->data["upload_url"] = GALLERY_ADMIN_URL."uploadImage/$gallery_id";
        $this->data['title'] = str_replace("{data}", $current_data['gallery_name'], _l("Gallery {data}", $this));
        $this->data['sub_title'] = _l('Images', $this);
        $this->data['breadcrumb'] = array(
            array('title' => $this->data['title']),
            array('title' => $this->data['sub_title']),
        );
        $this->data['breadcrumb_options'] = array(
            array('title'=>_l("Images", $this),'url'=>GALLERY_ADMIN_URL."images/$gallery_id",'active'=>1),
            array('title'=>_l("Edit", $this),'url'=>GALLERY_ADMIN_URL."gallerySubmit/$gallery_id"),
        );
        $this->data['page'] = "gallery_images_upload";
        $this->data['content'] = $this->load->view($this->mainTemplate."/gallery_images_upload", $this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Upload an image and add it into database
     *
     * @param $gallery_id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    function uploadImage($gallery_id)
    {
        if (!Services::identity()->isAdmin())
            return Services::identity()->getResponse();
        $back_url = GALLERY_ADMIN_URL."galleries";
        $current_data = Models::gallery()->getOne($gallery_id);
        if(!is_array($current_data) || count($current_data)==0){
            return $this->errorMessage("Gallery not found.", $back_url);
        }

        $type_dir = "gallery-$current_data[gallery_id]";
        $dir = FCPATH."upload_file/$type_dir/";
        // Make directory
        if(!file_exists($dir)){
            mkdir($dir);
        }
        // Create index file
        $file = $dir.'index.php';
        if(!file_exists($file)){
            $myfile = fopen($file, "w") or die("Unable to open file!");
            $txt = "<?php\n http_response_code(404); ";
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        $current_file_name = basename($_FILES["file"]["name"]);
        $config = array(
            'upload_path'=>"upload_file/$type_dir/",
            'allowed_types'=>"gif|jpg|png",
            'encrypt_name'=> true,
        );
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload("file"))
        {
            return  json_encode(array("status"=>"error","errors"=>$this->upload->display_errors('<p>', '</p>')));
        }

        $data = $this->upload->data();
        $data_image = array(
            "image_url"=>$config['upload_path'].$data["file_name"],
            "image_name"=>$current_file_name,
            "gallery_id"=>$gallery_id,
        );
        $image_id = Models::galleryImages()->add($data_image);
        if($image_id!=0) {
            return json_encode(array(
                "status" => "success",
                "file_patch" => $config['upload_path'] . $data["file_name"],
                "file_url" => base_url() . $config['upload_path'] . $data["file_name"],
                "width" => $data["image_width"],
                "height" => $data["image_height"],
                "image_id" => $image_id,
                "image_name" => $current_file_name,
                "submit_url" => GALLERY_ADMIN_URL."imageSubmit/$image_id",
                "size" => $data["file_size"]));
        }
        unlink(FCPATH.$data_image["image"]);
        echo json_encode(array("status"=>"error","errors"=>_l("Could not save images data in database.",$this)));
    }

    /**
     * Add/Edit form of an image
     *
     * @param null $id
     */
    function imageSubmit($id = null)
    {
        $back_url = GALLERY_ADMIN_URL."galleries";
        $self_url = GALLERY_ADMIN_URL."imageSubmit/$id";

        if($id!=null){
            $data = Models::galleryImages()->getOne($id);
            if(count($data)==0){
                return $this->errorMessage("Couldn't find the image.", $back_url);
            }
            $this->data['sub_title'] = _l("Edit", $this);
            $form_attr = array();
        }else{
            $this->data['sub_title'] = _l("Add", $this);
            $form_attr = array('data-reset'=>1);
        }

        $config = array();

//        if(isset($data)){
//            $config[] = array(
//                'field' => 'image_name',
//                'label' => _l("File Name", $this),
//                'type' => "static",
//                'value' => $data['image_name'],
//            );
//        }

        $languages = Models::languages()->getAll();
        foreach ($languages as $language){
            $translate = Models::galleryImages()->getTranslations($id, $language['language_id']);
            $prefix = "translate[$language[language_id]]";
            array_push($config, array(
                'prefix_language'=>$language,
                'field'=>$prefix."[title]",
                'label'=>_l('Title',$this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['title'])?$translate['title']:'',
            ));
        }

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!Services::identity()->isAdmin(true))
                return Services::identity()->getResponse();
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if(key_exists('translate',$post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if($id!=null){
//                Models::self::galleryImages()->edit($id, $post_data);
                if(isset($translates)){
                    Models::galleryImages()->updateTranslations($id,$translates,$languages);
                }
                return $this->successMessage("The image has been edited successfully.", $back_url);
            }
            else{
                $new_id = Models::galleryImages()->add($post_data);
                if(isset($translates)){
                    Models::galleryImages()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("The image has been sent successfully.", $back_url);
            }
        }

        $myform->setSubmitLabel(_l("Save", $this));
        $myform->setStyle("bootstrap-inline");
        $myform->setFormTheme("form_only");

        if($this->input->is_ajax_request()){
//            $myform->data['title'] = $this->data['title'];
            echo $myform->fetch(null, $form_attr);
            return;
        }
        $this->data['title'] = _l("Gallery's image",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Galleries", $this), 'url'=>$back_url),
            array('title'=>$this->data['sub_title']));
        $this->data['page'] = "gallery_image_submit";
        return $this->viewRenderString($myform->fetch(null,$form_attr));
    }

    /**
     * Delete an image
     *
     * @param $id
     * @param int $confirm
     */
    function imageDelete($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin(true))
            return Services::identity()->getResponse();

        $back_url = GALLERY_ADMIN_URL."galleries";
        $self_url = GALLERY_ADMIN_URL."imageDelete/$id";
        $data = Models::galleryImages()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("Couldn't find the image.", $back_url);
        }

        if($confirm!=1){
            return  json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the image from database.", $this).
                    '<br>'._l("After this, you will not to able to restore it.", $this).'</p>'.
                    '<p class="text-center font-lg bold">'._l("Are you sure to delete this?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>"$self_url/1",
                'redirect'=>1,
            ));
        }

        Models::galleryImages()->remove($id);
        return $this->successMessage("The image has been deleted successfully.", $back_url);
    }
}