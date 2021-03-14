<?php echo $the_form; ?>
<p>
    <a href="<?php echo base_url("{$this->lang}/login"); ?>" id="register-btn" class="btn btn-block blue-soft btn-outline">
        <?php echo _l("Sign In", $this); ?>
    </a>
</p>
<p>
    <a href="<?php echo base_url("{$this->lang}/return-password"); ?>" id="register-btn" class="btn btn-block blue-soft btn-outline">
        <?php echo _l("Forgot password?", $this); ?>
    </a>
</p>