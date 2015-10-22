<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.knob.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.ui.widget.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.fileupload.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/script.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-form/js/script.js"></script>
<link href="<?=base_url()?>assets/mini-upload-image/css/style.css" rel="stylesheet" >
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=isset($data['language_name'])?_l("Edit",$this)." ".$data['language_name']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['language_id'])?"/".$data['language_id']:""));
                    mk_hurl_upload("data[image]",_l('image',$this),isset($data['image'])?$data['image']:'',"image");
                    mk_htext("data[language_name]",_l('Language Name',$this),isset($data['language_name'])?$data['language_name']:'');
                    mk_htext("data[code]",_l('Language Code',$this),isset($data['code'])?$data['code']:'');
                    mk_hnumber("data[sort_order]",_l('Order',$this),isset($data['sort_order'])?$data['sort_order']:'');
                    mk_hcheckbox("data[rtl]",_l('Direction RTL',$this),(isset($data['rtl']) && $data['rtl']==1)?1:null);
                    mk_hcheckbox("data[public]",_l('Public',$this),(isset($data['public']) && $data['public']==1)?1:null);
                    mk_hcheckbox("data[default]",_l('default',$this),(isset($data['default']) && $data['default']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
mk_popup_uploadfile(_l('Upload Avatar',$this),"image",$base_url."upload_image/2/");
?>