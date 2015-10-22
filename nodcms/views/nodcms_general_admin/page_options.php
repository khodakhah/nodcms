<?php mk_use_uploadbox($this); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=_l("Options",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['page_id'])?"/".$data['page_id']:""));
                    if(isset($page_type[$data["page_type"]]) && allowed_page_field("image",$page_type[$data["page_type"]]))
                        mk_hurl_upload("data[avatar]",_l('order',$this),isset($data['avatar'])?$data['avatar']:'',"avatar");
                    if(isset($page_type[$data["page_type"]]) && allowed_page_field("order",$page_type[$data["page_type"]]))
                        mk_hnumber("data[page_order]",_l('order',$this),isset($data['page_order'])?$data['page_order']:'',"style='max-width:300px;'");
                    if(isset($page_type[$data["page_type"]]) && allowed_page_field("preview",$page_type[$data["page_type"]]))
                        mk_hcheckbox("data[preview]",_l('preview',$this),(isset($data['preview']) && $data['preview']==1)?1:null);
                    // Make more Options
                    if(allowed_page_more($page_type[$data["page_type"]])){
                        foreach (get_page_more($page_type[$data["page_type"]]) as $key=>$value) {
                            if(is_array($value)){
                                $caption = isset($value["caption"])?$value["caption"]:"";
                                $type = isset($value["type"])?$value["type"]:"";
                            }else{
                                $caption = $key;
                                $type = $value;
                            }
                            if($type == "text"){
                                mk_htext("data[page_more][$key]",_l($caption,$this),isset($data['page_more'][$key])?$data['page_more'][$key]:'');
                            }elseif($type == "num"){
                                mk_hnumber("data[page_more][$key]",_l($caption,$this),isset($data['page_more'][$key])?$data['page_more'][$key]:'');
                            }elseif($type == "url"){
                                mk_hurl("data[page_more][$key]",_l($caption,$this),isset($data['page_more'][$key])?$data['page_more'][$key]:'');
                            }elseif($type == "textarea"){
                                mk_htextarea("data[page_more][$key]",_l($caption,$this),isset($data['page_more'][$key])?$data['page_more'][$key]:'');
                            }elseif($type == "check"){
                                mk_hcheckbox("data[page_more][$key]",_l($caption,$this),isset($data['page_more'][$key])?1:0);
                            }
                        }
                    }
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
if(isset($page_type[$data["page_type"]]) && allowed_page_field("image",$page_type[$data["page_type"]]))
    mk_popup_uploadfile(_l('Upload Avatar',$this),"avatar",$base_url."upload_image/20/");
?>