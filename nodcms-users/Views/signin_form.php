<?php echo $the_form; ?>
<div class="row margin-top-20">
    <?php if($this->settings['registration']==1){ ?>
        <div class="col">
            <a href="<?php echo base_url("{$this->lang}/user-registration"); ?>" id="register-btn" class="btn btn-block blue-soft btn-outline">
                <?php echo _l("Create an account", $this); ?>
            </a>
        </div>
    <?php } ?>
    <div class="col">
        <a href="<?php echo base_url("{$this->lang}/return-password"); ?>" id="register-btn" class="btn btn-block blue-soft btn-outline">
            <?php echo _l("Forgot password?", $this); ?>
        </a>
    </div>
</div>
