<div class="portlet light">
    <?php if(isset($message) && $message!=''){ ?>
        <p class="text-center font-lg"><?php echo $message; ?></p>
    <?php } ?>
    <div class="text-center">
        <?php if($this->settings['registration']==1){ ?>
        <a class="btn btn-lg green" href="<?php echo base_url($this->language['code']."/user-registration") ?>"><?php echo _l("Sign Up", $this); ?></a>
        <?php } ?>
        <a class="btn btn-lg blue" href="<?php echo base_url($this->language['code']."/login") ?>"><?php echo _l("Sign In", $this); ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php // echo $sign_form; ?>
    </div>
    <div class="col-md-6">
        <?php // echo $registration_form; ?>
    </div>
</div>