<?php echo $the_form; ?>
<p>
    <a href="<?php echo base_url().$this->language["code"]; ?>/login" id="register-btn" class="btn btn-block blue-soft btn-outline">
        <?php echo _l("Sign In", $this); ?>
    </a>
</p>
<?php if($this->settings['registration']==1){ ?>
    <p>
        <a href="<?php echo base_url().$this->language["code"]; ?>/user-registration" id="register-btn" class="btn btn-block blue-soft btn-outline">
            <?php echo _l("Create an account", $this); ?>
        </a>
    </p>
<?php } ?>