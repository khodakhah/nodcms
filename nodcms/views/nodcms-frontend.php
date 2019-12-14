<!DOCTYPE html>
<?php $this->load->addCssFile("assets/nodcms/bootstrap-4.1.3/css/bootstrap.min"); ?>
<?php $this->load->addCssFile("assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min"); ?>

<?php $this->load->addCssFile("assets/nodcms/Packages/Support/icon-collection/css/nodcms-support.min"); ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->language["code"]; ?>" <?php echo $this->language["rtl"]!=1?'':'dir="rtl"'; ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/v4-shims.css">
    <?php $this->fetchAllCSS(); ?>
    <script src="<?php echo base_url(); ?>assets/jquery-3.4.0.min.js" type="text/javascript"></script>
</head>
<body data-base-url="<?php echo base_url(); ?>" class="page-container-bg-solid page-sidebar-closed-hide-logo <?php echo (count($this->page_sidebar_items)==0 || $this->page_sidebar_closed == true)?' page-full-width page-sidebar-closed':''; ?>">
<?php $this->load->view("nodcms-top-menu"); ?>
<?php echo isset($cart)?$cart:""; ?>
<?php $this->load->view('nodcms-page-head')?>

<div class="row no-gutters">
    <?php if(count($this->page_sidebar_items)!=0){ ?>
        <div class="sidebar-col d-print-none">
            <ul class="nav flex-column nodcms-sidebar h-100">
                <li class="nav-item sidebar-minimizer">
                    <a class="nav-link text-right" href="javascript:;"><i class="fas fa-chevron-left"></i></a>
                </li>
                <?php $this->load->view($this->page_sidebar); ?>
            </ul>
        </div>
    <?php } ?>
    <!-- BEGIN CONTENT -->
    <div class="col">
        <div class="page-content">
            <!-- BEGIN DASHBOARD STATS -->
            <?php if($this->session->flashdata('static_error')){ ?>
                <div class="alert alert-block alert-danger fade in">
                    <button data-dismiss="alert" class="close" type="button"></button>
                    <h4 class="alert-heading"><?php echo _l('Error',$this); ?>!</h4>
                    <?php echo $this->session->flashdata('static_error'); ?>
                </div>
            <?php } ?>
            <?php if(!$this->display_page_title && isset($breadcrumb) && count($breadcrumb)!=0){ ?>
                <div class="">
                    <div class="container">
                        <div class="page-bar">
                            <div class="row no-gutters">
                                <div class="col-md">
                                    <nav aria-label="breadcrumb">
                                        <ul class="breadcrumb margin-0 bg-transparent">
                                            <li class="breadcrumb-item">
                                                <i class="icon-grid"></i>
                                                <a href="<?php echo base_url().$this->language['code']; ?>"><?php echo _l("Home", $this); ?></a>
                                            </li>
                                            <?php foreach($breadcrumb as $item){ ?>
                                                <li class="breadcrumb-item">
                                                    <?php if(isset($item['url'])){ ?>
                                                        <a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
                                                    <?php }else{ ?>
                                                        <span><?php echo $item['title']; ?></span>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                                <?php if(isset($breadcrumb_options) && count($breadcrumb_options)!=0){ ?>
                                    <div class="col-md">
                                        <div class="page-toolbar text-right">
                                            <div class="btn-group">
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
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php echo isset($content)?$content:''; ?>
            <!-- END DASHBOARD STATS -->
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- END CONTENT -->
</div>

<?php if(isset($social_links) && count($social_links)!=0) { ?>
    <div class="container">
        <div class="padding-top-10 padding-bottom-10">
            <?php foreach($social_links as $item) { ?>
                <a class="btn default" href="<?php echo $item['url']; ?>" target="_blank" title="<?php echo $item['title']; ?>"><i class="fab fa-<?php echo $item['class']; ?>"></i></a>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div class="page-footer d-print-none">
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
    <div class="container">
        <div class="page-footer-inner text-center">
            <?php $this->load->view("copyright"); ?>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/nodcms/js/common.min.js"></script>
<script src="<?php echo base_url(); ?>assets/nodcms/bootstrap-4.1.3/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/toastr/toastr.min.js"></script>
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
<script src="<?php echo base_url(); ?>assets/plugins/popper/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
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
            var title;
            switch(typeof $(this).attr('data-msg')){
                case "undefined":
                    title = "<?php echo _l("Are you sure?", $this); ?>";
                    break;
                case "":
                    title = "<?php echo _l("Are you sure?", $this); ?>";
                    break;
                default:
                    title = $(this).attr('data-msg');
            }
            $(this).confirmation({
                rootSelector: 'body',
                btnOkClass: 'btn-sm green-soft',
                btnCancelClass: 'btn-sm red-soft',
                popout: true,
                singleton: true,
                btnOkIconClass: 'fa fa-check margin-right-10 margin-left-10',
                btnCancelIconClass: 'fa fa-times margin-right-10 margin-left-10',
                placement: 'left',
                title: title,
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
<script>
    $(function () {
        $('.nodcms-sidebar').each(function () {
            var $this = $(this);
            var $parent = $this.parent();
            $this.find('.sidebar-minimizer').click(function () {
                $parent.toggleClass("minimized");
                $(this).find("i").toggleClass("fa-chevron-left fa-chevron-right");

            });
            $this.find(".nav-toggle").each(function () {
                var $navToggle = $(this);
                $(this).parent().addClass("open");
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
