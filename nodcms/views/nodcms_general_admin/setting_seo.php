<ul class="nav nav-tabs">
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/general"><?php echo _l('General settings',$this)?></a></li>
    <li role="presentation" class="active"><a href="javascript:;"><?php echo _l('SEO optimise',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/contact"><?php echo _l('Contact settings',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/mail"><?php echo _l('Send mail settings',$this)?></a></li>
</ul>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform();
                    $option = "style='max-width:600px;'";
                    foreach ($languages as $item) {
                        mk_htext("data[options][".$item["language_id"]."][site_title]",_l('site title',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["site_title"]:"",$option);
                        mk_htextarea("data[options][".$item["language_id"]."][site_description]",_l('Site Description',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["site_description"]:"",$option);
                        mk_htextarea("data[options][".$item["language_id"]."][site_keyword]",_l('site keywords',$this)." (".$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["site_keyword"]:"",$option);
                    }
                    mk_hsubmit(_l('Submit',$this),$base_url,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
