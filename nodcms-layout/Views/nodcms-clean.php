<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language["code"]; ?>" <?php echo $this->language["rtl"]!=1?'':'dir="rtl"'; ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($description)?$description:""; ?>">
    <meta name="author" content="<?php echo isset($author)?$author:""; ?>">
    <meta name="keyword" content="<?php echo isset($keyword)?$keyword:""; ?>">
    <?php if(isset($settings["fav_icon"]) && $settings["fav_icon"]!=''){ ?>
    <link rel="shortcut icon" href="<?php echo base_url($settings["fav_icon"]); ?>">
    <?php } ?>
    <title><?php echo $title; ?> <?php echo isset($sub_title)?$sub_title:""; ?></title>

    <?php echo csrf_meta("csrf_meta"); ?>
    <?php echo $this->settings['add_on_header']; ?>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/v4-shims.css">
    <link href="<?php echo base_url("assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/metronic/global/plugins/bootstrap-select/css/bootstrap-select.min.css"); ?>"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <?php if($this->language["rtl"]!=1){ ?>
        <link href="<?php echo base_url("assets/nodcms/bootstrap-4.1.3/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
   <?php }else{ ?>
        <link href="<?php echo base_url("assets/nodcms/bootstrap-4.1.3/css/bootstrap-rtl.min.css"); ?>" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <link href="<?php echo base_url("assets/metronic/global/css/custom.css"); ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/metronic/global/plugins/bootstrap-toastr/toastr.min.css"); ?>"/>
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link rel="stylesheet" href="<?php echo base_url("assets/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css"); ?>">
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <?php $this->fetchAllCSS(); ?>
    <script src="<?php echo base_url("assets/metronic/global/plugins/jquery.min.js"); ?>" type="text/javascript"></script>
</head>
<body data-base-url="<?php echo base_url(); ?>" class="page-container-bg-solid page-sidebar-closed-hide-logo <?php echo (count($this->page_sidebar_items)==0 || $this->page_sidebar_closed == true)?' page-full-width page-sidebar-closed':''; ?>">
<div class="page-container">
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <?php if($this->session->flashdata('static_error')){ ?>
                        <div class="alert alert-block alert-danger fade in">
                            <button data-dismiss="alert" class="close" type="button"></button>
                            <h4 class="alert-heading"><?php echo _l('Error',$this); ?>!</h4>
                            <?php echo $this->session->flashdata('static_error'); ?>
                        </div>
                    <?php } ?>
                    <?php echo isset($content)?$content:''; ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<![endif]-->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>-->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>-->
<script src="<?php echo base_url("assets/popper/popper.min.js"); ?>" type="text/javascript"></script>
<script src="<?php echo base_url("assets/nodcms/bootstrap-4.1.3/js/bootstrap.min.js"); ?>" type="text/javascript"></script>
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>-->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>-->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>-->
<script src="<?php echo base_url("assets/metronic/global/plugins/jquery.blockui.min.js"); ?>" type="text/javascript"></script>
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/js.cookie.min.js" type="text/javascript"></script>-->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>-->
<script src="<?php echo base_url("assets/metronic/global/plugins/select2/js/select2.min.js"); ?>" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="<?php echo base_url("assets/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"); ?>"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/global/scripts/app.min.js" type="text/javascript"></script>-->
<!--<script src="--><?php //echo base_url(); ?><!--assets/metronic/layouts/layout4/scripts/layout.js" type="text/javascript"></script>-->
<script src="<?php echo base_url("assets/nodcms/js/common.js"); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- END JAVASCRIPTS -->
<script type="text/javascript" src="<?php echo base_url("assets/metronic/global/plugins/bootstrap-inputmask.min.js"); ?>"></script>
<!--script for this page-->

<script src="<?php echo base_url("assets/metronic/global/plugins/bootstrap-toastr/toastr.min.js"); ?>"></script>
<script>
    $(function(){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        <?php if($this->session->flashdata('success')){ ?>
        toastr['success']("<?php echo $this->session->flashdata('success'); ?>", "<?php echo _l("Success",$this); ?>");
        <?php } ?>
        <?php if($this->session->flashdata('error')){ ?>
        toastr['error']("<?php echo $this->session->flashdata('error'); ?>", "<?php echo _l("Error",$this); ?>");
        <?php } ?>
        <?php if($this->session->flashdata('message')){ ?>
        toastr['info']("<?php echo $this->session->flashdata('message'); ?>", "<?php echo _l("Info",$this); ?>");
        <?php } ?>
    });
</script>
<script src="<?php echo base_url("assets/metronic/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"); ?>"></script>
<script>
    $(function(){
        $.fn.makeConfirmationBtn = function () {
            if(typeof $(this).attr('onclick')!="undefined"){
                var the_action = $(this).attr('onclick');
                $(this).on('confirmed.bs.confirmation', function () {
                    eval(the_action);
                });
                $(this).removeAttr('onclick');
            }
            $(this).confirmation({
                container: 'body',
                btnOkClass: 'btn-xs btn-success',
                btnCancelClass: 'btn-xs btn-danger',
                singleton: true,
                popout: true,
                btnOkIcon: 'fa fa-check',
                btnCancelIcon: 'fa fa-times',
                placement: 'left',
                title:$(this).attr('data-msg'),
                btnOkLabel: '<?php echo _l("Yes please!",$this); ?>',
                btnCancelLabel: '<?php echo _l("No Stop!",$this); ?>'
            });
        };

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

<?php $this->fetchAllJS(); ?>
<?php echo $this->settings['add_on_script']; ?>
</body>
</html>
