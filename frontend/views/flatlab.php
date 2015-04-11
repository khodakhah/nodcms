<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?$lang?>">
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
    <title><?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?><?php echo isset($title)?" | ".$title:""; ?></title>
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
    <meta name="keywords" content="<?php if(isset($keyword)) echo $keyword; ?>" />
    <meta name="description" content="<?php if(isset($description)) echo sort_words(strip_tags($description),0,50); ?>" />

    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?php echo base_url().$lang; ?>/sitemap.xml" /> <!-- No www -->
    <link rel="shortcut icon" href="<?=base_url().$settings["fav_icon"]?>">


    <link href="<?=base_url()?>assets/flatlab/fonts_fa/ed-fonts.css" rel="stylesheet" />
    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>assets/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?=base_url()?>assets/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/flatlab/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/flatlab/css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="<?=base_url()?>assets/flatlab/css/style.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/flatlab/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?=base_url()?>assets/flatlab/js/html5shiv.js"></script>
    <script src="<?=base_url()?>assets/flatlab/js/respond.min.js"></script>
    <![endif]-->

    <?php if($_SESSION["language"]["rtl"]==1){ ?>
    <link href="<?=base_url()?>assets/flatlab/css/rtl.css" rel="stylesheet">
    <?php } ?>
    <link href="<?=base_url()?>assets/flatlab/css/custom.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/frontend_custom/css/style.css" rel="stylesheet">



    <script src="<?=base_url()?>assets/flatlab/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript">
            $('img').error(function(){
               <?php if(isset($settings['default_image']) && $settings['default_image']!="") {?>
               $(this).attr('src','<?php echo base_url(); ?><?php echo image($settings['default_image'],$settings['default_image'],220,120);?>');
              <?php } else {?>
              $(this).attr('src','<?php echo base_url(); ?><?php echo image("assets/frontend/img/noimage.jpg","assets/frontend/img/noimage.jpg",220,120);?>');
              <?php }?>
            });
    </script>
</head>
<body class="full-width">
<section id="container">
  	<!--start header-->
    <?php require 'common/flatlab_header.php'; ?>
    <!--start content-->
    <section id="main-content">
        <section class="wrapper">
            <?php require_once ("flatlab/" . $content . ".php"); ?>
        </section>
    </section>
    <!--start footer-->
    <?php require 'common/flatlab_footer.php'; ?>
</section>

<!-- js placed at the end of the document so the pages load faster -->
<!--<script src="--><?//=base_url()?><!--assets/flatlab/js/jquery.js"></script>-->

<script src="<?=base_url()?>assets/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?=base_url()?>assets/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/flatlab/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/flatlab/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/owl.carousel.js" ></script>
<script src="<?=base_url()?>assets/flatlab/js/jquery.customSelect.min.js" ></script>
<script src="<?=base_url()?>assets/flatlab/js/respond.min.js" ></script>


<!--common script for all pages-->
<script src="<?=base_url()?>assets/flatlab/js/common-scripts.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/rtl-common-scripts.js"></script>

<!--script for this page-->
<script src="<?=base_url()?>assets/flatlab/js/sparkline-chart.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/easy-pie-chart.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/count.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/jquery.pulsate.min.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/pulstate.js"></script>

<script>
    //owl carousel
    $(document).ready(function() {
        $("#owl-demo").owlCarousel({
            navigation : true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem : true,
            autoPlay:true

        });
    });

    //custom select box

    $(function(){
        $('select.styled').customSelect();
    });

</script>
</body>
</html>




