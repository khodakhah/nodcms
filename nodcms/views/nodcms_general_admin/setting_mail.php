<ul class="nav nav-tabs">
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings"><?php echo _l('General settings',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/seo"><?php echo _l('SEO optimise',$this)?></a></li>
    <li role="presentation"><a href="<?php echo base_url()?>admin/settings/contact"><?php echo _l('Contact settings',$this)?></a></li>
    <li role="presentation" class="active"><a href="javascript:;"><?php echo _l('Send mail settings',$this)?></a></li>
</ul>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform();
                    ?>
                    <div class="form-group ">
                        <label class="control-label col-lg-2"><?php echo _l('Email protocol',$this); ?></label>
                        <div class="col-lg-10 col-sm-10">
                            <div class="btn-group">
                                <label class="btn btn-default"><input onchange="if($(this).is(':checked')){ $('.smtp_options').slideDown(500); }" name="data[use_smtp]" type="radio" value="1" checked> <?php echo _l('SMTP-protocol',$this); ?></label>
                                <label class="btn btn-default"><input onchange="if($(this).is(':checked')){ $('.smtp_options').slideUp(500); }" name="data[use_smtp]" type="radio" value="0" <?php echo (isset($settings['use_smtp']) && $settings['use_smtp']==0)?'checked':''; ?>> <?php echo _l('mail-protocol',$this); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="smtp_options" <?php echo (isset($settings['use_smtp']) && $settings['use_smtp']==0)?'style="display:none;"':''; ?>>
                        <?php
                        $option = "style='max-width:600px;'";
                        mk_htext("data[smtp_host]",_l('SMTP host name',$this),isset($settings['smtp_host'])?$settings['smtp_host']:'',$option);
                        mk_hnumber("data[smtp_port]",_l('SMTP port',$this),isset($settings['smtp_port'])?$settings['smtp_port']:'',$option);
                        mk_htext("data[smtp_username]",_l('SMTP username',$this),isset($settings['smtp_username'])?$settings['smtp_username']:'',$option);
                        mk_htext("data[smtp_password]",_l('SMTP password',$this),isset($settings['smtp_password'])?$settings['smtp_password']:'',$option);
                        ?>
                    </div>
                    <div class="form-group ">
                        <label class="control-label col-lg-2"><?php echo _l('Auto messages',$this); ?></label>
                        <div class="col-lg-10">
                            <ul class="nav nav-tabs nav-languages" role="tablist">
                                <?php foreach ($languages as $item) { ?>
                                    <li role="presentation">
                                        <a href="#langtab<?php echo $item["language_id"]?>" aria-controls="langtab<?php echo $item["language_id"]?>" role="tab" data-toggle="tab">
                                            <img src="<?php echo base_url().$item["image"]; ?>" style="width:32px;">
                                            <?php echo $item["language_name"]; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content nav-languages">
                                <?php foreach ($languages as $item) { ?>
                                    <div role="tabpanel" class="tab-pane" id="langtab<?php echo $item["language_id"]?>">
                                        <?php
                                        mk_vtextarea_shortkeys("msg_header".$item["language_id"],"data[options][".$item["language_id"]."][msg_header]",_l('All auto message headers',$this)." (".'<img src="'.base_url().$item["image"].'" style="width:18px;"> '.$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["msg_header"]:"",'rows="6"',$public_keys);
                                        mk_vtextarea_shortkeys("msg_footer".$item["language_id"],"data[options][".$item["language_id"]."][msg_footer]",_l('All auto message footers',$this)." (".'<img src="'.base_url().$item["image"].'" style="width:18px;"> '.$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["msg_footer"]:"",'rows="6"',$public_keys);
                                        mk_vtextarea_shortkeys("msg_register".$item["language_id"],"data[options][".$item["language_id"]."][msg_register]",_l('Auto message after register',$this)." (".'<img src="'.base_url().$item["image"].'" style="width:18px;"> '.$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["msg_register"]:"",'rows="12"',$register_keys);
                                        mk_vtextarea_shortkeys("msg_active".$item["language_id"],"data[options][".$item["language_id"]."][msg_active]",_l('Auto message after users activate',$this)." (".'<img src="'.base_url().$item["image"].'" style="width:18px;"> '.$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["msg_active"]:"",'rows="12"',$activate_keys);
                                        mk_vtextarea_shortkeys("msg_reset_pass".$item["language_id"],"data[options][".$item["language_id"]."][msg_reset_pass]",_l('Auto message after users request to reset password',$this)." (".'<img src="'.base_url().$item["image"].'" style="width:18px;"> '.$item["language_name"].")",isset($options[$item["language_id"]])?$options[$item["language_id"]]["msg_reset_pass"]:"",'rows="12"',$reset_pass_keys);
                                        ?>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <?php
                    mk_hsubmit(_l('Submit',$this),$base_url,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $(function(){
        $('.nav.nav-tabs.nav-languages > li:first-child').addClass('active');
        $('.nav-languages .tab-pane:first-child').addClass('active');
    });
    function insertAtCaret(areaId,text) {
        var txtarea = document.getElementById(areaId);
        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = txtarea.selectionStart;

        var front = (txtarea.value).substring(0,strPos);
        var back = (txtarea.value).substring(strPos,txtarea.value.length);
        txtarea.value=front+text+back;
        strPos = strPos + text.length;
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            range.moveStart ('character', strPos);
            range.moveEnd ('character', 0);
            range.select();
        }
        else if (br == "ff") {
            txtarea.selectionStart = strPos;
            txtarea.selectionEnd = strPos;
            txtarea.focus();
        }
        txtarea.scrollTop = scrollPos;
    }
    function smtp_toggle(thisValue){
        alert('1');
    }
</script>
