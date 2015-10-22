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
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['article_id'])?"/".$data['article_id']:""));
                    mk_hselect("data[article_type]",_l('page type',$this),$page_type,"id","name",isset($data['article_type'])?$data['article_type']:null,null,'style="width:400px"');
                    mk_hurl_upload("data[avatar]",_l('avatar',$this),isset($data['avatar'])?$data['avatar']:'',"avatar");
                    mk_htext("data[article_name]",_l('article Name',$this),isset($data['article_name'])?$data['article_name']:'');
                    foreach ($languages as $item) {
                        mk_htext("data[titles][".$item["language_id"]."]",_l('article name',$this)." (".$item["language_name"].")",isset($titles[$item["language_id"]])?$titles[$item["language_id"]]["title_caption"]:"");
                    }
                    mk_hnumber("data[article_order]",_l('article order',$this),isset($data['article_order'])?$data['article_order']:'');
                    mk_hcheckbox("data[article_dynamic]",_l('Active extensions search',$this),(isset($data['article_dynamic']) && $data['article_dynamic']==1)?1:null);
                    mk_hcheckbox("data[preview]",_l('preview',$this),(isset($data['preview']) && $data['preview']==1)?1:null);
                    mk_hcheckbox("data[public]",_l('public',$this),(isset($data['public']) && $data['public']==1)?1:null);
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
mk_popup_uploadfile(_l('Upload Avatar',$this),"avatar",$base_url."upload_image/20/");
?>