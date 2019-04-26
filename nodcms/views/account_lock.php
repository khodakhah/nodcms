<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $settings["company"]; ?> - <?php echo _l('Lock',$this); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>

    <?php if($_SESSION["language"]["rtl"]!=1){ ?>
        <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/pages/css/lock.css" rel="stylesheet" type="text/css"/>
        <!-- BEGIN THEME STYLES -->
        <link href="<?php echo base_url(); ?>assets/metronic/global/css/components.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
    <?php }else{ ?>
        <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/pages/css/lock-rtl.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/global/css/components-rtl.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/layout-rtl.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/themes/darkblue-rtl.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/custom-rtl.css" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="<?php echo base_url().$settings["fav_icon"]?>">
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body>
<div class="page-lock">
    <div class="page-logo">
        <a class="brand" href="<?php echo base_url(); ?>">
            <img style="max-height: 80px;" src="<?php echo base_url().$settings['logo']; ?>" alt="LOGO"/>
        </a>
    </div>
    <div class="page-body">
        <div class="lock-head">
            <?php echo _l("Locked", $this); ?>
        </div>
        <div class="lock-body">
            <p class="text-center font-grey-cascade"><?php echo _l("Unfortunately, your account is deactivated by admin!", $this); ?></p>
            <div class="pull-left lock-avatar-block">
                <?php if($this->userdata["avatar"]!=""){ ?>
                    <img src="<?php echo base_url().$this->userdata["avatar"]; ?>" class="lock-avatar">
                <?php }else{ ?>
                    <img src="<?php echo base_url(); ?>upload_file/images/user.png" class="lock-avatar">
                <?php } ?>
            </div>
            <div class="lock-form pull-left margin-bottom-20">
                <h4><?php echo $this->userdata["username"]; ?></h4>
                <div class="form-actions">
                    <a href="<?php echo base_url(); ?>admin-sign/logout" class="btn btn-success uppercase"><?php echo _l("Logout", $this); ?></a>
                </div>
            </div>
        </div>
        <div class="lock-bottom">
        </div>
    </div>
    <div class="page-footer-custom">
        <?php echo $settings["company"]?> <i class="fa fa-copyright"></i> <?php echo date("Y")?>
        <a href="http://chictheme.com/nodaps" target="_blank" title="Purchase NodAPS just $24">NodAPS</a>
    </div>
</div>
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url(); ?>assets/metronic/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/layouts/layout/scripts/demo.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/layouts/layout/scripts/layout.js" type="text/javascript"></script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>