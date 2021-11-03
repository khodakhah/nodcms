<div class="margin-bottom-40">
    <div class="row">
        <div class="col-md">
            <a href="<?php echo ADMIN_URL."menu"; ?>" class="btn grey-mint btn-outline btn-block">
                <i class="fas fa-sitemap"></i>
                <?php echo _l("Site Menu", $this); ?>
            </a>
        </div>
        <div class="col-md">
            <a href="<?php echo ADMIN_URL."settings/socialLinks"; ?>" class="btn grey-mint btn-outline btn-block">
                <i class="fas fa-share-alt"></i>
                <?php echo _l("Social Links", $this); ?>
            </a>
        </div>
        <div class="col-md">
            <a href="<?php echo ADMIN_URL."settings/contact"; ?>" class="btn grey-mint btn-outline btn-block">
                <i class="fas fa-phone"></i>
                <?php echo _l("Contact Info", $this); ?>
            </a>
        </div>
        <div class="col-md">
            <a href="<?php echo ADMIN_URL."settings/tcpp"; ?>" class="btn grey-mint btn-outline btn-block">
                <i class="fas fa-gavel"></i>
                <?php echo _l("T&C, PP", $this); ?>
            </a>
        </div>
        <div class="col-md">
            <a href="<?php echo ADMIN_URL."automaticEmailTexts"; ?>" class="btn grey-mint btn-outline btn-block">
                <i class="far fa-envelope"></i>
                <?php echo _l("Email Texts", $this); ?>
                <?php if(isset($auto_email_messages_badge)){ ?>
                    <span class="badge badge-danger"><?php echo $auto_email_messages_badge; ?></span>
                <?php } ?>
            </a>
        </div>
    </div>
</div>