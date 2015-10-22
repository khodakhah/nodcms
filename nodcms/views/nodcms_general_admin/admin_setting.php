<?php mk_use_uploadbox($this); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=_l('Site Settings',$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform();
                    mk_hselect("data[language_id]",_l('Admin language',$this),$languages,"language_id","language_name",isset($settings['language_id'])?$settings['language_id']:null,null,'style="width:200px"');
                    $option = "style='max-width:600px;'";
                    mk_htext("data[company]",_l('Company Name',$this),isset($settings['company'])?$settings['company']:'',$option);
                    foreach ($languages as $item) {
                        mk_htext("data[options][".$item["language_id"]."][company]",_l('company name',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["company"]:"",$option);
                        mk_htext("data[options][".$item["language_id"]."][site_title]",_l('site title',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["site_title"]:"",$option);
                        mk_htextarea("data[options][".$item["language_id"]."][site_description]",_l('Site Description',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["site_description"]:"",$option);
                        mk_htextarea("data[options][".$item["language_id"]."][site_keyword]",_l('site keywords',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["site_keyword"]:"",$option);
                    }
                    mk_hurl_upload("data[logo]",_l('Logo',$this),isset($settings['logo'])?$settings['logo']:'',"logo");
                    mk_hurl_upload("data[fav_icon]",_l('Fav Icon',$this),isset($settings['fav_icon'])?$settings['fav_icon']:'',"fav_icon");
                    mk_hemail("data[email]",_l('Email Address',$this),isset($settings['email'])?$settings['email']:'');
                    mk_hnumber("data[phone]",_l('Phone Number',$this),isset($settings['phone'])?$settings['phone']:'');
                    mk_hnumber("data[zip_code]",_l('Zip Code',$this),isset($settings['zip_code'])?$settings['zip_code']:'');
                    mk_hgoogle_location("data[location]",_l('Location',$this),isset($settings['location'])?$settings['location']:'');
                    mk_htextarea("data[address]",_l('Address',$this),isset($settings['address'])?$settings['address']:'');
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
