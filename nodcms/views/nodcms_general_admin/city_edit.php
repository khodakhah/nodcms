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
                <?=$title?> <?=isset($data['title'])?_l("Edit",$this)." ".$data['title']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['city_id'])?"/".$data['city_id']:""));
                    mk_hurl_upload("data[avatar]",_l('avatar',$this),isset($data['avatar'])?$data['avatar']:'',"avatar");
                    mk_htext("data[city_name]",_l('city Name',$this),isset($data['city_name'])?$data['city_name']:'');
                    foreach ($languages as $item) {
                        mk_htext("data[titles][".$item["language_id"]."]",_l('city name',$this)." (".$item["language_name"].")",isset($titles[$item["language_id"]])?$titles[$item["language_id"]]["title_caption"]:"");
                    }
                    mk_htext("data[location]",_l('city location',$this),isset($data['location'])?$data['location']:'');
                    mk_hselect("data[country_id]",_l('currency',$this),$countries,"country_id","country_name",isset($data['country_id'])?$data['country_id']:null);
                    mk_hcheckbox("data[public]",_l('public',$this),(isset($data['public']) && $data['public']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
mk_popup_uploadfile(_l('Upload Avatar',$this),"avatar",$base_url."upload_image/20/");
?>
