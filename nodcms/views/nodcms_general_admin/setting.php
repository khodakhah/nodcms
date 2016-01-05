<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="javascript:;"><?php echo _l('General settings',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/seo"><?php echo _l('SEO optimise',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/contact"><?php echo _l('Contact settings',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/mail"><?php echo _l('Send mail settings',$this)?></a></li>
</ul>
<?php mk_use_uploadbox($this); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform();
                    mk_hselect("data[language_id]",_l('Admin language',$this),$languages,"language_id","language_name",isset($settings['language_id'])?$settings['language_id']:null,null,'style="width:200px"');
                    $option = "style='max-width:600px;'";
                    mk_htext("data[company]",_l('Company Name',$this),isset($settings['company'])?$settings['company']:'',$option);
                    foreach ($languages as $item) {
                        mk_htext("data[options][".$item["language_id"]."][company]",_l('company name',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["company"]:"",$option);
                    }
                    mk_hurl_upload("data[logo]",_l('Logo',$this),isset($settings['logo'])?$settings['logo']:'',"logo");
                    mk_hurl_upload("data[fav_icon]",_l('Fav Icon',$this),isset($settings['fav_icon'])?$settings['fav_icon']:'',"fav_icon");
                    mk_hsubmit(_l('Submit',$this),$base_url,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
    <?php
    mk_popup_uploadfile(_l('Upload Logo',$this),"logo",$base_url."upload_image/1/");
    mk_popup_uploadfile(_l('Upload Fav',$this),"fav_icon",$base_url."upload_image/1/");
    ?>
</div>
