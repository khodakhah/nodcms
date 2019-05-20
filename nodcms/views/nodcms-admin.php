<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language["code"]; ?>" <?php echo $this->language["rtl"]!=1?'':'dir="rtl"'; ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($description)?$description:""; ?>">
    <meta name="author" content="<?php echo isset($author)?$author:""; ?>">
    <meta name="keyword" content="<?php echo isset($keyword)?$keyword:""; ?>">
    <?php if(isset($settings["fav_icon"]) && $settings["fav_icon"]!=''){ ?>
    <link rel="shortcut icon" href="<?php echo base_url().$settings["fav_icon"]; ?>">
    <?php } ?>
    <title><?php echo $title; ?> <?php echo isset($sub_title)?$sub_title:""; ?></title>

    <?php echo $this->settings['add_on_header']; ?>

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
<body data-base-url="<?php echo base_url(); ?>">

<?php $this->load->view("nodcms-top-menu"); ?>
<?php echo isset($cart)?$cart:""; ?>
<!-- END HEADER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="row no-gutters">
    <?php if(count($this->admin_panel_items)!=0){ ?>
        <div class="sidebar-col d-print-none">
            <ul class="nav flex-column nodcms-sidebar">
                <li class="nav-item sidebar-minimizer">
                    <a class="nav-link text-right" href="javascript:;"><i class="fas fa-chevron-left"></i></a>
                </li>
                <li class="sidebar-close">
                    <a class="btn btn-outline-secondary btn-sm margin-10" href="javascript:;"><i class="fas fa-list"></i></a>
                </li>
                <?php $this->load->view($this->page_sidebar); ?>
            </ul>
        </div>
    <?php } ?>

    <!-- BEGIN CONTENT -->
    <div class="col">
        <div class="container-fluid margin-top-10 margin-bottom-10">
            <!-- BEGIN DASHBOARD STATS -->
            <?php if($this->session->flashdata('static_error')){ ?>
                <div class="alert alert-block alert-danger fade in">
                    <button data-dismiss="alert" class="close" type="button"></button>
                    <h4 class="alert-heading"><?php echo _l('Error',$this); ?>!</h4>
                    <?php echo $this->session->flashdata('static_error'); ?>
                </div>
            <?php } ?>
            <div class="page-content">
                <?php echo isset($content)?$content:''; ?>
            </div>
            <!-- END DASHBOARD STATS -->
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>

<div class="page-footer d-print-none">
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
    <div class="page-footer-inner text-center margin-bottom-20 ">
        <?php $this->load->view("copyright"); ?>
    </div>
</div>
<!-- END FOOTER -->

<script src="<?php echo base_url(); ?>assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/popper/popper.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/nodcms/bootstrap-4.1.3/js/bootstrap.min.js" type="text/javascript"></script>
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>-->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/select2/js/select2.min.js" type="text/javascript"></script>-->
<!--<script src="--><?php //echo base_url()?><!--assets/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>-->

<script src="<?php echo base_url(); ?>assets/nodcms/js/common.js" type="text/javascript"></script>
<?php $this->load->view("common/bootstrap-toastr"); ?>
<?php $this->load->view("common/bootstrap-confirmation"); ?>
<script>
    $(function(){
        $.setCurrencyFormatSettings({
            before_sign: '<?php echo $this->settings["currency_sign_before"] == 1?$this->settings["currency_sign"]:''; ?>',
            after_sign: '<?php echo $this->settings["currency_sign_before"] == 1?'':$this->settings["currency_sign"]; ?>',
            currency_code: '<?php echo $this->settings["currency_code"]; ?>',
            // Only able to be "1.234,56", "1,234.56", "1.234", and "1,234"
            number_format: '<?php echo $this->settings["currency_format"]; ?>'
        });

        $('.btn-ask').each(function(){
            $(this).makeConfirmationBtn();
        });

        <?php if($page!=''){ ?>
            $('#<?php echo $page; ?>').highlight_selected_menu();
        <?php } ?>
    });
</script>
<script>
    $(function () {
        $('.nodcms-sidebar').each(function () {
            var $this = $(this);
            var $parent = $this.parent();
            $this.find('.sidebar-minimizer').click(function () {
                $parent.toggleClass("minimized");
                $(this).find("i").toggleClass("fa-chevron-left fa-chevron-right");

            });

            $this.find('.sidebar-close a').click(function () {
                $parent.toggleClass("sidebar-open");
                $(this).find("i").toggleClass("fa-times fa-list");
            });

            $this.find(".nav-toggle").each(function () {
                var $navToggle = $(this);
                $navToggle.click(function () {
                    $(this).parent().toggleClass("open");
                });
            });
        });
    });
</script>

<?php $this->fetchAllJS(); ?>
<?php echo $this->settings['add_on_script']; ?>
</body>
</html>
