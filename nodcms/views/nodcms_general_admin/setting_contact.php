<ul class="nav nav-tabs">
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings"><?php echo _l('General settings',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/seo"><?php echo _l('SEO optimise',$this)?></a></li>
    <li role="presentation" class="active"><a href="javascript:;"><?php echo _l('Contact settings',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/mail"><?php echo _l('Send mail settings',$this)?></a></li>
</ul>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform();
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
</div>
