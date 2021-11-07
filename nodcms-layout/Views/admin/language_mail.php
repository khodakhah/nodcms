<?php $this->addJsFile("assets/ckeditor/ckeditor"); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="portlet">
            <div class="portlet-body">
                <div class=" form">
                    <?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

mk_hpostform();
                    ?>
                    <?php
                    if(isset($auto_emails) && count($auto_emails)!=0){
                        foreach($auto_emails as $key=>$val){
                            ?>
                            <div class="portlet box blue">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase"><?php echo _l($val['label'],$this)." (".'<img src="'.base_url().$data["image"].'" style="width:18px;"> '.$data["language_name"].")"; ?></span>
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title="">
                                        </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="padding: 20px 40px;">
                                    <?php
                                    mk_vtext("data[auto_messages][".$data["language_id"]."][$key][subject]",_l('Mail Subject',$this),isset($auto_messages_data[$data["language_id"]][$key]['subject'])?$auto_messages_data[$data["language_id"]][$key]['subject']:"");
                                    mk_vtexteditor_shortkeys($key.$data["language_id"],"data[auto_messages][".$data["language_id"]."][$key][content]",_l('Mail Content',$this),isset($auto_messages_data[$data["language_id"]][$key]['content'])?$auto_messages_data[$data["language_id"]][$key]['content']:"",'rows="12"',$val['keys']);
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <?php
                    mk_hsubmit(_l('Save',$this));
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
</script>
