<?php

function mk_use_uploadbox($this){
    ?>
    <script src="<?=base_url()?>assets/mini-upload-image/js/jquery.knob.js"></script>
    <script src="<?=base_url()?>assets/mini-upload-image/js/jquery.ui.widget.js"></script>
    <script src="<?=base_url()?>assets/mini-upload-image/js/jquery.iframe-transport.js"></script>
    <script src="<?=base_url()?>assets/mini-upload-image/js/jquery.fileupload.js"></script>
    <script src="<?=base_url()?>assets/mini-upload-image/js/script.js"></script>
    <script src="<?=base_url()?>assets/mini-upload-form/js/script.js"></script>
    <link href="<?=base_url()?>assets/mini-upload-image/css/style.css" rel="stylesheet" >
    <script>
        $(function(){
            $.loadUploadedImages = function(thiselement){
                $.ajax({url:"<?=base_url()?>admin/uploaded_images",success:function(result){
                    thiselement.find(".image_list").html(result).attr("insert-to",thiselement.attr("insert-to"));
                }});
            }
        });
    </script>
    <div class="modal fade" id="choose_uploaded_image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" onshow="$.loadUploadedImages($(this))">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?=_l("Choose uploaded image",$this)?></h4>
                </div>
                <div class="modal-body">
                    <div class="image_list"></div>
                </div>
                <div class="modal-footer text-center">
                    <button data-dismiss="modal" class="btn btn-danger btn-lg" type="button"><?=_l("Close",$this)?></button>
                </div>
            </div>
        </div>
    </div>

    <?php
}
function mk_popup_uploadfile($title,$insert_to,$postURL,$submitName='file'){
    ?>
    <div class="modal fade" id="<?=$insert_to?>_uploader" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?=$title?></h4>
                </div>
                <div class="modal-body">
                    <div style="width:100%;" id="<?=$insert_to?>_uploader_thum"></div>
                    <form class="upload_form" method="post" action="<?=$postURL?>" enctype="multipart/form-data" for="<?=$insert_to?>" uploadtype="image">
                        <div class="drop">
                            Drop Here
                            <a>Browse</a>
                            <input type="file" name="<?=$submitName?>" multiple />
                        </div>
                        <ul>
                            <!-- The file uploads will be shown here -->
                        </ul>
                    </form>

                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
        <script type="text/javascript"> $(function(){ $("#<?=$insert_to?>_btn").text("<?=$title?>") }); </script>
    <?php
}
function mk_hurl_upload($name,$caption="",$value="",$id=null,$options=''){
    ?>
<div class="form-group ">
    <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
    <div class="col-lg-7">
        <input class=" form-control" id="<?=$id!=null?$id:$name?>" name="<?=$name?>" type="text" value="<?=$value?>" <?=$options?>/>
    </div>
    <div class="col-lg-3">
        <a id="<?=$id!=null?$id:$name?>_btn" href="#<?=$id!=null?$id:$name?>_uploader" class="btn btn-primary" data-toggle="modal">Upload</a>
        <a id="choose_<?=$id!=null?$id:$name?>_btn" href="#choose_uploaded_image" onclick="$($(this).attr('href')).attr('insert-to','#<?=$id!=null?$id:$name?>');" class="btn btn-primary" data-toggle="modal">Choose</a>
    </div>
</div>
<?php
}
function mk_hpostform($action="",$name=null){
    ?>
    <form class="cmxform form-horizontal tasi-form" <?=$name!=null?'name="'.$name.'" id="'.$name.'"':''?> method="post" action="<?=$action?>">
    <?php
}
function mk_closeform(){
    ?>
    </form>
    <?php
}
function mk_htext($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <input class=" form-control" id="<?=$name?>" name="<?=$name?>" type="text" value="<?=$value?>" <?=$options?>/>
        </div>
    </div>
    <?php
}
function mk_hidden($name,$value="",$options=''){
    ?>
    <input id="<?=$name?>" name="<?=$name?>" type="hidden" value="<?=$value?>" <?=$options?>/>
    <?php
}
function mk_hpassword($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <input class=" form-control" id="<?=$name?>" name="<?=$name?>" type="password" value="<?=$value?>" <?=$options?>/>
        </div>
    </div>
    <?php
}
function mk_hemail($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <input class=" form-control" id="<?=$name?>" name="<?=$name?>" type="email" value="<?=$value?>" <?=$options?>/>
        </div>
    </div>
    <?php
}
function mk_hurl($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <input class=" form-control" id="<?=$name?>" name="<?=$name?>" type="url" value="<?=$value?>" placeholder="http://" <?=$options?>/>
        </div>
    </div>
    <?php
}
function mk_hnumber($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <input class=" form-control" id="<?=$name?>" name="<?=$name?>" type="text" value="<?=$value?>" <?=$options?>/>
        </div>
    </div>
    <?php
}
function mk_hmasked($name,$caption="",$value="",$masked="",$masked_help="",$options=""){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <input class=" form-control" data-mask="<?=$masked?>" id="<?=$name?>" name="<?=$name?>" type="text" value="<?=$value?>" <?=$options?>/>
            <?php if($masked_help!=null){ ?><span class="help-inline"><?=$masked_help==""?$masked:$masked_help?></span><?php } ?>
        </div>
    </div>
    <?php
}
function mk_htextarea($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <textarea class=" form-control" id="<?=$name?>" name="<?=$name?>" <?=$options?>><?=$value?></textarea>
            <?php if(isset($help)){ ?><p><?php echo $help; ?></p><?php } ?>
        </div>
    </div>
    <?php
}
function mk_vtextarea_shortkeys($id,$name,$caption="",$value="",$options='',$shortkeys=array()){
    ?>
    <div class="form-group ">
        <label for="<?=$id?>" class="control-label"><?=$caption?></label>
        <?php if(count($shortkeys)!=0){ ?>
        <p>
            <?php foreach($shortkeys as $item){ ?>
                <button type="button" class="btn btn-default btn-sm" onclick="insertAtCaret('<?=$id?>','<?php echo $item['value']; ?>');"><?php echo $item['label']; ?></button>
            <?php } ?>
        </p>
        <?php } ?>
        <textarea class="form-control" id="<?=$id?>" name="<?=$name?>" <?=$options?>><?=$value?></textarea>
        <?php if(isset($help)){ ?><p><?php echo $help; ?></p><?php } ?>
    </div>
    <?php
}
function mk_htexteditor($name,$caption="",$value="",$options=''){
    ?>
    <div class="form-group">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10">
            <textarea class="ckeditor form-control" id="<?=$name?>" name="<?=$name?>" <?=$options?>><?=$value?></textarea>
        </div>
    </div>
    <?php
}
function mk_hsubmit($submitCaption=null,$backURL=null,$backCaption=null){
    ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <input class="btn btn-danger" type="submit" value="<?=$submitCaption!=null?$submitCaption:''?>" >
            <a href="<?=$backURL!=null?$backURL:''?>" class="btn btn-default" type="button"><?=$backCaption!=null?$backCaption:''?></a>
        </div>
    </div>
    <?php
}
function mk_hcheckbox($name,$caption="",$checked=null,$value="1",$options=''){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10 col-sm-10">
            <input value="<?=$value?>" type="checkbox" onclick="if($(this).attr('checked')=='checked'){ $(this).next().removeAttr('checked'); }else{ $(this).next().attr('checked','checked'); }" style="width: 20px" class="checkbox form-control" id="<?=$name?>" name="<?=$name?>" <?=$checked!=null?"checked":""?> <?=$options?>>
            <input value="0" type="checkbox" style="display: none;" name="<?=$name?>" <?=$checked==null?"checked":""?>>
        </div>
    </div>
    <?php
}
function mk_hselect($name,$caption="",$options_data=null,$field_id,$field_name,$value=null,$null_select=null,$options='',$onselect=null,$translate=null){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10 col-sm-10">
            <select class="form-control" id="<?=$name?>" name="<?=$name?>" <?=$options?> <?=$onselect!=null?"onchange='".$onselect."'":""?>>
                <?php if($null_select!=null){ ?>
                <option value="0"><?=$null_select?></option>
                <?php } ?>
                <?php if($options_data!=null){ ?>
                    <?php foreach($options_data as $data){ ?>
                    <option value="<?=$data[$field_id]?>" <?=($value!=null && $data[$field_id]==$value)?"selected":""?>><?=$translate!=null?_l($data[$field_name],$translate):$data[$field_name]?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <?php
}
function mk_hselect_faicon($name,$caption="",$options_data=null,$value=null){
    ?>
    <div class="form-group ">
        <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
        <div class="col-lg-10 col-sm-10">
            <div class="btn-group btn-block">
                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" >
                    <b class="caret"></b>
                    <i id="btn_icon_<?=str_replace(array("[","]"),"",$name)?>" class="fa fa-2x <?=$value?>"></i>
                </button>
                <div class="dropdown-menu" style="width: 100%;">
                    <div class="select_icon scroll-box" style="overflow: hidden;max-height: 300px;">
                        <label <?=($value==null)?'class="checked"':''?>><input type="radio" name="<?=$name?>" value="" onclick="$('input[type=radio]:not(:checked)').parent().removeClass('checked'); $(this).parent().addClass('checked'); $('#btn_icon_<?=str_replace(array("[","]"),"",$name)?>').attr('class',$(this).next().attr('class'))" <?=($value==null)?"checked":""?>> <i class="fa fa-stop fa-2x text-danger"></i></label>
                        <?php if($options_data!=null){ ?>
                        <?php foreach($options_data as $data){ ?>
                            <label <?=($value!=null && $value==$data)?'class="checked"':''?>><input type="radio" name="<?=$name?>" value="<?=$data?>" onclick="$('input[type=radio]:not(:checked)').parent().removeClass('checked'); $(this).parent().addClass('checked'); $('#btn_icon_<?=str_replace(array("[","]"),"",$name)?>').attr('class',$(this).next().attr('class'))" <?=($value!=null && $value==$data)?"checked":""?>> <i class="fa <?=$data?> fa-2x"></i></label>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
function mk_hgoogle_location($name,$caption="",$value=""){
    $val = array("","","","");
    $data = explode(", ",$value);
    $data[0]=isset($data[0])?substr($data[0],0,1):"";
    $data[1]=isset($data[1])?substr($data[1],0,1):"";
    if($data[0]=="+" && $data[1]=="+"){ $val[0] = $value; }
    elseif($data[0]=="-" && $data[1]=="+"){ $val[1] = $value; }
    elseif($data[0]=="+" && $data[1]=="-"){ $val[2] = $value; }
    elseif($data[0]=="-" && $data[1]=="-"){ $val[3] = $value; }
    ?>
<div class="form-group">
    <label for="<?=$name?>" class="control-label col-lg-2"><?=$caption?></label>
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="direction: ltr">
        <input class="masked form-control" data-mask="+99.999999, +99.999999" type="text" onchange="var thiselement = $(this);  $(this).parent().parent().find('input.masked').each(function(){ if($(this).val()!=thiselement.val()){ $(this).val(''); } thiselement.parent().parent().find('input.main_value').val(thiselement.val()); });" value="<?=$val[0]?>" />
        <span class="help-inline">Plus (+), Plus (+)</span>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="direction: ltr">
        <input class="masked form-control" data-mask="-99.999999, +99.999999" type="text" onchange="var thiselement = $(this);  $(this).parent().parent().find('input.masked').each(function(){ if($(this).val()!=thiselement.val()){ $(this).val(''); } thiselement.parent().parent().find('input.main_value').val(thiselement.val()); });" value="<?=$val[1]?>" />
        <span class="help-inline">Minus (-), Plus (+)</span>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="direction: ltr">
        <input class="masked form-control" data-mask="+99.999999, -99.999999" type="text" onchange="var thiselement = $(this);  $(this).parent().parent().find('input.masked').each(function(){ if($(this).val()!=thiselement.val()){ $(this).val(''); } thiselement.parent().parent().find('input.main_value').val(thiselement.val()); });" value="<?=$val[2]?>" />
        <span class="help-inline">Plus (+), Minus (-)</span>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="direction: ltr">
        <input class="masked form-control" data-mask="-99.999999, -99.999999" type="text" onchange="var thiselement = $(this);  $(this).parent().parent().find('input.masked').each(function(){ if($(this).val()!=thiselement.val()){ $(this).val(''); } thiselement.parent().parent().find('input.main_value').val(thiselement.val()); });" value="<?=$val[3]?>" />
        <span class="help-inline">Minus (-), Minus (-)</span>
    </div>
    <input class="main_value" id="<?=$name?>" name="<?=$name?>" type="hidden" value="<?=$value?>"/>
</div>
<?php
}