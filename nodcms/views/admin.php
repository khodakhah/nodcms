<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=$_SESSION["language"]["code"]?>" <?php echo $this->language["rtl"]!=1?'':'dir="rtl"'; ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($settings["fav_icon"]) && $settings["fav_icon"]!=''){ ?>
    <link rel="shortcut icon" href="<?php echo base_url().$settings["fav_icon"]; ?>">
    <?php } ?>
    <title><?=_l('Administration',$this)?> <?=isset($settings["company"])?$settings["company"]:""?></title>

    <?php echo $this->settings['add_on_header']; ?>

    <script src="<?php echo base_url(); ?>assets/metronic/global/plugins/pace/pace.min.js"></script>
    <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/v4-shims.css">
    <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-select/css/bootstrap-select.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/select2/css/select2.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/select2/css/select2-bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-summernote/summernote.css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <?php if($_SESSION["language"]["rtl"]!=1){ ?>
    <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- BEGIN THEME STYLES -->
    <link href="<?php echo base_url(); ?>assets/metronic/global/css/components.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
   <?php }else{ ?>
    <link href="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/global/css/components-rtl.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/global/css/plugins-rtl.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/layout-rtl.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/themes/darkblue-rtl.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="<?php echo base_url(); ?>assets/metronic/layouts/layout/css/custom-rtl.css" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/metronic/global/plugins/bootstrap-toastr/toastr.min.css"/>
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css">
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <?php $this->fetchAllCSS(); ?>
    <script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery.min.js" type="text/javascript"></script>
</head>
<body data-base-url="<?php echo base_url(); ?>" class="page-header-fixed page-sidebar-closed-hide-logo page-quick-sidebar-over-content <?php echo (isset($this->page_sidebar_menu_closed) && $this->page_sidebar_menu_closed == true)?'page-sidebar-closed':'' ?>">
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo base_url(); ?>" target="_blank">
                <img style="height: 20px;" src="<?php echo isset($settings["logo"])?base_url().$settings["logo"]:""; ?>" alt="<?=isset($settings["company"])?$settings["company"]:""?>" title="<?=isset($settings["company"])?$settings["company"]:""?>" class="logo-default">
            </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <a href="<?php echo base_url();?>" target="_blank" class="btn btn-link font-yellow navbar-btn"><i class="icon-screen-desktop"></i> <?php echo _l('View site',$this); ?></a>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <?php $this->load->view("user_menu", $this->data); ?>
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu <?php echo (isset($this->page_sidebar_menu_closed) && $this->page_sidebar_menu_closed == true)?'page-sidebar-menu-closed':''; ?>" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper hide">
                    <div class="sidebar-toggler">
                        <span></span>
                    </div>
                </li>
                <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                <li class="sidebar-search-wrapper">
                    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                    <form id="" class="sidebar-search">

                    </form>
                    <!-- END RESPONSIVE QUICK SEARCH FORM -->
                </li>

                <?php $this->load->view('admin_sidebar'); ?>

            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <h3 class="page-title">
                <?php echo $title; ?>
                <small><?php echo isset($sub_title)?$sub_title:''; ?></small>
            </h3>
            <?php if(isset($breadcrumb) && count($breadcrumb)!=0){ ?>
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        <li>
                            <i class="icon-grid"></i>
                            <a href="<?php echo ADMIN_URL; ?>"><?php echo _l('Control Panel', $this); ?></a>
                        </li>
                        <?php foreach($breadcrumb as $item){ ?>
                        <li>
                            <?php if(isset($_SESSION['language']['rtl']) && $_SESSION['language']['rtl']==1){ ?>
                                <i class="fa fa-angle-left"></i>
                            <?php }else{ ?>
                                <i class="fa fa-angle-right"></i>
                            <?php } ?>
                            <?php if(isset($item['url'])){ ?>
                                <a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
                            <?php }else{ ?>
                                <span><?php echo $item['title']; ?></span>
                            <?php } ?>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php if(isset($breadcrumb_options) && count($breadcrumb_options)!=0){ ?>
                    <div class="page-toolbar">
                        <div class="pull-right">
                            <?php foreach($breadcrumb_options as $item){ ?>
                                <?php if(!isset($item['sub_links'])){ ?>
                                    <a href="<?php echo $item['url']; ?>" class="btn btn-fit-height grey-salt <?php echo (isset($item['active']) && $item['active']==1)?"active":""; ?>" <?php echo isset($item['target'])?'target="'.$item['target'].'"':''; ?>>
                                        <?php if(isset($item['icon'])){ ?>
                                            <i class="<?php echo $item['icon']; ?>"></i>
                                        <?php } ?>
                                        <?php echo $item['title']; ?>
                                    </a>
                                <?php }else{ ?>
                                    <div class="btn-group">
                                        <button class="btn btn-fit-height grey-salt dropdown-toggle <?php echo (isset($item['active']) && $item['active']==1)?"active":""; ?>" type="button" data-toggle="dropdown" data-close-others="true" data-hover="dropdown" data-delay="1000">
                                            <?php if(isset($item['icon'])){ ?>
                                                <i class="<?php echo $item['icon']; ?>"></i>
                                            <?php } ?>
                                            <?php echo $item['title']; ?> <i class="fa fa-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <?php foreach($item['sub_links'] as $sub_item){ ?>
                                                <li>
                                                    <a href="<?php echo $sub_item['url']; ?>">
                                                        <?php if(isset($sub_item['icon'])){ ?>
                                                            <i class="<?php echo $sub_item['icon']; ?>"></i>
                                                        <?php } ?>
                                                        <?php echo $sub_item['title']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?php if(isset($page_tabs) && count($page_tabs)!=0){ ?>
                <div class="tabbable-line">
                    <ul class="nav nav-tabs nav-justified">
                        <?php foreach($page_tabs as $item){ ?>
                            <li <?php echo (isset($item['active']) && $item['active']==1)?'class="active"':""; ?>>
                                <a href="<?php echo isset($item['url'])?$item['url']:'#'; ?>">
                                    <?php if(isset($item['icon'])){ ?>
                                        <i class="<?php echo $item['icon']; ?>"></i>
                                    <?php } ?>
                                    <?php echo $item['title']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <br>
            <?php } ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN DASHBOARD STATS -->
            <?php if($this->session->flashdata('static_error')){ ?>
                <div class="alert alert-block alert-danger fade in">
                    <button data-dismiss="alert" class="close" type="button"></button>
                    <h4 class="alert-heading"><?php echo _l('Error',$this); ?>!</h4>
                    <?php echo $this->session->flashdata('static_error'); ?>
                </div>
            <?php } ?>
            <?php echo isset($content)?$content:''; ?>
            <!-- END DASHBOARD STATS -->
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        <?php $this->load->view("copyright"); ?>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<div class="modal fade" id="askStaticModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-warning">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title text-warning"><i class="fa fa-warning"></i> <?php echo _l("Confirmation!",$this); ?></div>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l("No",$this); ?></button>
                <a href="#" class="btn btn-warning btn-accept"><?php echo _l("Yes",$this); ?></a>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/select2/js/select2.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="<?php echo base_url()?>assets/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/metronic/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/metronic/layouts/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/nodcms/js/common.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- END JAVASCRIPTS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-inputmask.min.js"></script>
<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/ckeditor/ckeditor.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/ckeditor/ckeditor.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/bootstrap-summernote/summernote.min.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/bootstrap-summernote/plugins/template/summernote-ext-template.js"></script>-->
<!--script for this page-->

<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-toastr/toastr.min.js"></script>
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
<script src="<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
<!--<script src="--><?php //echo base_url(); ?><!--assets/nodaps/admin/common.js"></script>-->
<script>
    $(function(){
        $.getSiteStyles = function(){
            return [
                '<?php echo base_url(); ?>assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet',
                '<?php echo base_url(); ?>assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet',
                '<?php echo base_url(); ?>assets/metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet',
                '<?php echo base_url(); ?>assets/metronic/global/css/components.css" rel="stylesheet',
            ]
        };

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

        $('.btn-ask').each(function(){
            $(this).makeConfirmationBtn();
        });

        function highlight_selected_menu(item){
            var myParent = item.parent('ul');
            if(myParent.hasClass('sub-menu')){
                item.addClass("active");
                if(item.find('sub-menu').length > 0){
                    $("#"+item.attr("id")+" > a:first-child .arrow").addClass('open');
                }
                highlight_selected_menu(myParent.parent('li'));
            }else{
                item.addClass("active star");
                $("#"+item.attr("id")+" > a:first-child")
                    .append($('<span class="selected"></span>'))
                    .find('.arrow').addClass('open');

            }
        }
        highlight_selected_menu($('#<?php echo $page; ?>'));
    });
</script>
<?php $this->fetchAllJS(); ?>
<?php echo $this->settings['add_on_script']; ?>
</body>
</html>
