<?php echo $this->load->addCssFile("assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput"); ?>
<?php echo $this->load->addJsFile("assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput"); ?>
<div class="row">
    <div class="col-md-8">
        <section class="panel">
            <div class="panel-body">
                <?php echo $submit_form; ?>
            </div>
        </section>
    </div>
    <div class="col-md-4">
        <div class="portlet">
            <div class="portlet-body">
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

mk_hpostform_multipart(base_url("user/account-avatar-change"));
                mk_hidden("image","1");
                ?>
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                        <img src="<?php echo (isset($data["avatar"])&&$data["avatar"]!="")?base_url($data["avatar"]):base_url("{$this->lang}/noimage-200-200-NO+IMAGE"); ?>" alt=""/>
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                    <div>
                <span class="btn default btn-file">
                <span class="fileinput-new">
                <?php echo _l("Select image", $this); ?> </span>
                <span class="fileinput-exists">
                <?php echo _l("Change", $this); ?> </span>
                <input type="file" name="file" id="file">
                </span>
                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                            <?php echo _l("Cancel", $this); ?>
                        </a>
                        <button type="submit" class="btn btn-primary fileinput-exists">
                            <?php echo _l("Save changes", $this)?>
                        </button>
                        <?php if(isset($data["avatar"])&&$data["avatar"]!=""){ ?>
                            <button type="submit" class="btn btn-danger fileinput-new btn-ask">
                                <?php echo _l("Remove", $this)?>
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix margin-top-10">
                    <span class="label label-warning"><?php echo _l("NOTE!", $this); ?></span>
                    <span> <?php echo _l("Best size:", $this); ?> 200x200</span>
                </div>
                <?php
                mk_closeform();
                ?>
            </div>
        </div>
    </div>
</div>
