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

    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <?php if($this->language["rtl"]!=1){ ?>
        <link href="<?php echo base_url("assets/nodcms/bootstrap-4.1.3/css/nodcms-admin.min.css"); ?>" rel="stylesheet" type="text/css"/>
   <?php }else{ ?>
        <link href="<?php echo base_url("assets/nodcms/bootstrap-4.1.3/css/nodcms-admin-rtl.min.css"); ?>" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <link href="<?php echo base_url("assets/plugins/jquery-ui/jquery-ui.min.css"); ?>" rel="stylesheet" type="text/css"/>
    <?php $this->fetchAllCSS(); ?>

    <script src="<?php echo base_url("assets/jquery-3.4.0.min.js"); ?>" type="text/javascript"></script>
</head>
<body data-base-url="<?php echo CONFIG_BASE_URL; ?>">
<?php echo $this->render($this->mainTemplate."/top-menu"); ?>
<!-- END HEADER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="row no-gutters">
    <?php if($this->hasSidebar()){ ?>
        <div class="sidebar-col d-print-none">
            <ul class="nav flex-column nodcms-sidebar">
                <li class="nav-item sidebar-minimizer">
                    <a class="nav-link text-right" href="javascript:;"><i class="fas fa-chevron-left"></i></a>
                </li>
                <li class="sidebar-close">
                    <a class="btn btn-outline-secondary btn-sm margin-10" href="javascript:;"><i class="fas fa-list"></i></a>
                </li>
                <?php echo $this->sidebar(); ?>
            </ul>
        </div>
    <?php } ?>

    <!-- BEGIN CONTENT -->
    <div class="col">
        <div class="container-fluid margin-top-10 margin-bottom-10">
            <h1 class="page-title">
                <?php echo $title; ?>
                <small><?php echo isset($sub_title)?$sub_title:''; ?></small>
            </h1>
            <?php if(isset($breadcrumb) && count($breadcrumb)!=0){ ?>
                <div class="page-bar">
                    <div class="row no-gutters">
                        <div class="col-md">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <i class="icon-grid"></i>
                                        <a href="<?php echo ADMIN_URL; ?>"><?php echo _l('Control Panel', $this); ?></a>
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

            <?php if($this->flashdata('static_error')){ ?>
                <div class="alert alert-block alert-danger">
                    <button data-dismiss="alert" class="close" type="button"></button>
                    <h4 class="alert-heading"><?php echo _l('Error',$this); ?>!</h4>
                    <?php echo $this->flashdata('static_error'); ?>
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
    <div class="page-footer-inner margin-bottom-20 ">
        <?php echo $this->common()->render("copyright"); ?>
    </div>
</div>
<!-- END FOOTER -->

<script src="<?php echo base_url("assets/plugins/popper/popper.min.js"); ?>" type="text/javascript"></script>
<script src="<?php echo base_url("assets/nodcms/bootstrap-4.1.3/js/bootstrap.min.js"); ?>" type="text/javascript"></script>
<script src="<?php echo base_url("assets/plugins/jquery-ui/jquery-ui.min.js"); ?>" type="text/javascript"></script>

<script src="<?php echo base_url("assets/nodcms/js/common.js"); ?>" type="text/javascript"></script>
<?php echo $this->common()->render("common/bootstrap-toastr"); ?>
<?php echo $this->common()->render("common/bootstrap-confirmation"); ?>
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
