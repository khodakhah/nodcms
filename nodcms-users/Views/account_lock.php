<p class="text-center"><?php echo _l("Unfortunately, your account is deactivated by admin!", $this); ?></p>
<div class="row">
    <div class="col">
        <?php if($this->userdata["avatar"]!=""){ ?>
            <img src="<?php echo base_url($this->userdata["avatar"]); ?>" class="img-fluid">
        <?php }else{ ?>
            <img src="<?php echo USER_UNDEFINED_AVATAR; ?>" class="img-fluid">
        <?php } ?>
    </div>
    <div class="col">
        <h4><?php echo $this->userdata["username"]; ?></h4>
        <a href="<?php echo base_url("{$this->lang}/logout"); ?>" class="btn btn-success uppercase"><?php echo _l("Logout", $this); ?></a>
    </div>
</div>
