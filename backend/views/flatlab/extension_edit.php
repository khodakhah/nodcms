<?php mk_use_uploadbox($this); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=isset($data['title'])?_l("Edit",$this)." ".$data['title']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['extension_id'])?"/".$data['extension_id']:""));
                    mk_hselect("data[language_id]",_l('language',$this),$languages,"language_id","language_name",isset($data['language_id'])?$data['language_id']:null,null,'style="width:200px"');
                    if(isset($page_type) && allowed_extension_fields("icon",$page_type))
                        mk_hselect_faicon("data[extension_icon]",_l('Icon',$this),$faicons,isset($data['extension_icon'])?$data['extension_icon']:null,null,'style="width:200px"');
                    mk_htext("data[name]",_l('extension Name',$this),isset($data['name'])?$data['name']:'');
                    if(isset($page_type) && allowed_extension_fields("description",$page_type))
                        mk_htexteditor("data[description]",_l('extension description',$this),isset($data['description'])?$data['description']:'');
                    if(isset($page_type) && allowed_extension_fields("full_description",$page_type))
                        mk_htexteditor("data[full_description]",_l('extension full description',$this),isset($data['full_description'])?$data['full_description']:'');
                    if(isset($page_type) && allowed_extension_fields("image",$page_type))
                        mk_hurl_upload("data[image]",_l('image',$this),isset($data['image'])?$data['image']:'',"image");
                    if(isset($page_type) && allowed_extension_fields("order",$page_type))
                        mk_hnumber("data[extension_order]",_l('order',$this),isset($data['extension_order'])?$data['extension_order']:'');
                    // Make more Options
                    if(allowed_extension_more($page_type)){
                        foreach (get_extension_more($page_type) as $key=>$value) {
                            if(is_array($value)){
                                $caption = isset($value["caption"])?$value["caption"]:"";
                                $type = isset($value["type"])?$value["type"]:"";
                            }else{
                                $caption = $key;
                                $type = $value;
                            }
                            if($type == "text"){
                                mk_htext("data[extension_more][$key]",_l($caption,$this),isset($data['extension_more'][$key])?$data['extension_more'][$key]:'');
                            }elseif($type == "num"){
                                mk_hnumber("data[extension_more][$key]",_l($caption,$this),isset($data['extension_more'][$key])?$data['extension_more'][$key]:'');
                            }elseif($type == "url"){
                                mk_hurl("data[extension_more][$key]",_l($caption,$this),isset($data['extension_more'][$key])?$data['extension_more'][$key]:'');
                            }elseif($type == "textarea"){
                                mk_htextarea("data[extension_more][$key]",_l($caption,$this),isset($data['extension_more'][$key])?$data['extension_more'][$key]:'');
                            }elseif($type == "check"){
                                mk_hcheckbox("data[extension_more][$key]",_l($caption,$this),(isset($data['extension_more'][$key]) && $data['extension_more'][$key]==1)?1:0);
                            }
                        }
                    }
                    mk_hcheckbox("data[public]",_l('public',$this),(isset($data['public']) && $data['public']==1)?1:null);
                    mk_hcheckbox("data[status]",_l('status',$this),(isset($data['status']) && $data['status']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page."s".(isset($data_type)?"/".$data_type:'').(isset($relation_id)?"/".$relation_id:""),_l('Cancel',$this));
                    mk_hidden("data[relation_id]",isset($relation_id)?$relation_id:0);
                    mk_hidden("data[data_type]",isset($data_type)?$data_type:'');
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
mk_popup_uploadfile(_l('Upload Image',$this),"image",$base_url."upload_image/20/");
?>
