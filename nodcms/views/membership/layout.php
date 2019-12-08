<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?php $this->language['code']; ?>">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $title; ?></title>

    <?php echo $this->settings['add_on_header']; ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link rel="shortcut icon" href="<?=base_url().$this->settings["fav_icon"]?>">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <?php if($this->language["rtl"]!=1){ ?>
        <link href="<?php echo base_url(); ?>assets/nodcms/bootstrap-4.1.3/css/nodcms-admin.min.css" rel="stylesheet" type="text/css"/>
    <?php }else{ ?>
        <link href="<?php echo base_url(); ?>assets/nodcms/bootstrap-4.1.3/css/nodcms-admin-rtl.min.css" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <?php $this->fetchAllCSS(); ?>
    <script src="<?php echo base_url(); ?>assets/jquery-3.4.0.min.js" type="text/javascript"></script>
</head>
<body data-base-url="<?php echo base_url(); ?>" class="bg-grey-mint">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 margin-top-40">
            <div class="text-center margin-bottom-40">
                <a href="<?php echo base_url(); ?>">
                    <img class="img-fluid" src="<?php echo base_url().$this->settings['logo_light']; ?>" alt=""/>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php echo $content; ?>
                </div>
            </div>
            <div class="font-grey margin-top-20 text-center">
                <?php $this->load->view("copyright"); ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/popper/popper.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/nodcms/bootstrap-4.1.3/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/nodcms/js/common.js" type="text/javascript"></script>

<?php $this->load->view("common/bootstrap-confirmation"); ?>
<?php $this->load->view("common/bootstrap-toastr"); ?>
<?php $this->fetchAllJS(); ?>

</body>
</html>