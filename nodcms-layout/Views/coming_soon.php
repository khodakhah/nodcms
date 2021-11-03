<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <?php
        $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        ?>
    <title><?php echo $title; ?></title>
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
    <meta name="keywords" content="<?php if(isset($keyword)) echo $keyword; ?>" />
    <meta name="description" content="<?php if(isset($description)) echo substr_string(strip_tags($description),0,50); ?>" />

    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?php echo base_url($lang); ?>/sitemap.xml" /> <!-- No www -->
    <link rel="shortcut icon" href="<?=base_url($settings["fav_icon"]) ?>">

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css"); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css"); ?>"/>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/metronic/pages/css/profile-old.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/metronic/global/plugins/jquery-ui/jquery-ui-datepicker-custom.css"); ?>"/>
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo base_url(); ?><!--assets/metronic/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css"/>-->
    <!-- END GLOBAL MANDATORY STYLES -->
    <?php if($_SESSION["language"]["rtl"]!=1){ ?>
        <link href="<?php echo base_url("assets/metronic/global/plugins/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/global/plugins/uniform/css/uniform.default.css"); ?>" rel="stylesheet" type="text/css"/>
        <!-- BEGIN THEME STYLES -->
        <link href="<?php echo base_url("assets/metronic/global/css/components.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/layouts/layout3/css/layout.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/layouts/layout3/css/themes/green-haze.css.css"); ?>" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo base_url("assets/metronic/layouts/layout3/css/custom.css"); ?>" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
    <?php }else{ ?>
        <link href="<?php echo base_url("assets/metronic/rtl/global/plugins/bootstrap/css/bootstrap-rtl.min.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/rtl/global/plugins/uniform/css/uniform.default-rtl.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/rtl/global/css/components-rtl.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/rtl/global/css/plugins-rtl.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/rtl/admin/layout3/css/layout-rtl.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("assets/metronic/rtl/admin/layout3/css/themes/default-rtl.css"); ?>" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo base_url("assets/metronic/rtl/admin/layout3/css/custom-rtl.css"); ?>" rel="stylesheet" type="text/css"/>
    <?php } ?>
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="<?php echo base_url("assets/metronic/global/plugins/jqvmap/jqvmap/jqvmap.css"); ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url("assets/metronic/pages/css/coming-soon.css"); ?>" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGIN STYLES -->

    <script src="<?php echo base_url("assets/metronic/global/plugins/jquery.min.js"); ?>" type="text/javascript"></script>

    <script type="text/javascript">
            $('img').error(function(){
               <?php if(isset($settings['default_image']) && $settings['default_image']!="") {?>
               $(this).attr('src','<?php echo base_url(image($settings['default_image'],$settings['default_image'],220,120)); ?>');
              <?php } else {?>
              $(this).attr('src','<?php echo base_url(image("assets/frontend/img/noimage.jpg","assets/frontend/img/noimage.jpg",220,120)); ?>');
              <?php }?>
            });
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 coming-soon-header">
                <a class="brand" href="index.html">
                    <img style="max-height: 30px;" src="<?=base_url($settings["logo"])?>" alt="logo"/>
                </a>
            </div>
        </div>
        <div class="coming-soon-content">
            <h1><?php echo $heading; ?></h1>
            <p>
                <?php echo $message; ?>
            </p>
        </div>
        <!--/end row-->
        <div class="row">
            <div class="col-md-12 coming-soon-footer">
                <?php echo $settings["company"]; ?> <i class="fa fa-copyright"></i> <?php echo date("Y")?>
                <?php if(!isset($production_copyright) || $production_copyright == TRUE){ ?>
                    <a href="http://chictheme.com/nodaps" target="_blank" title="Purchase NodAPS just $24">NodAPS</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--[if lt IE 9]>
    <script src="<?php echo base_url("assets/metronic/global/plugins/respond.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/excanvas.min.js"); ?>"></script>
    <![endif]-->
    <script src="<?php echo base_url("assets/metronic/global/plugins/jquery-migrate.min.js"); ?>" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="<?php echo base_url("assets/metronic/global/plugins/jquery-ui/jquery-ui.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/jquery.blockui.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/js.cookie.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"); ?>" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?php echo base_url("assets/metronic/global/plugins/backstretch/jquery.backstretch.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/global/scripts/app.min.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/layouts/layout3/scripts/layout.js"); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url("assets/metronic/pages/scripts/tasks.js"); ?>" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <?php if(isset($calendar_i18n) && in_array($lang, $calendar_i18n)){ ?>
        <script src="<?php echo base_url("assets/metronic/global/plugins/jquery-ui/ui/i18n/datepicker-"); ?><?php echo $lang; ?>.js" type="text/javascript"></script>
        <script>
            $(function(){
                $.datepicker.setDefaults( $.datepicker.regional[ "<?php echo $lang; ?>" ] );
            });
        </script>
    <?php } ?>
    <script>
        $.backstretch([
            "<?php echo base_url("assets/metronic/pages/media/bg/1.jpg"); ?>",
            "<?php echo base_url("assets/metronic/pages/media/bg/2.jpg"); ?>",
            "<?php echo base_url("assets/metronic/pages/media/bg/3.jpg"); ?>",
            "<?php echo base_url("assets/metronic/pages/media/bg/4.jpg"); ?>"
        ], {
            fade: 1000,
            duration: 10000
        });
    </script>
    <!-- END JAVASCRIPTS -->
</body>
</html>