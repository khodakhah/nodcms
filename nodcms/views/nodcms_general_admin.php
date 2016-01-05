<!DOCTYPE html>
<html lang="<?=$_SESSION["language"]["code"]?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="<?php echo base_url(); ?><?=isset($settings["fav_icon"])?$settings["fav_icon"]:""?>">

    <title><?=_l('Administration',$this)?> <?=isset($settings["company"])?$settings["company"]:""?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>assets/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/flatlab/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/flatlab/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/flatlab/assets/gritter/css/jquery.gritter.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/flatlab/css/style.css" rel="stylesheet">
    <?php if($_SESSION["language"]["rtl"]==1){ ?>
    <link href="<?php echo base_url(); ?>assets/flatlab/css/rtl.css" rel="stylesheet">
    <?php } ?>
    <link href="<?php echo base_url(); ?>assets/flatlab/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/backend_custom/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/flatlab/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>assets/flatlab/js/html5shiv.js"></script>
    <script src="<?php echo base_url(); ?>assets/flatlab/js/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(); ?>assets/flatlab/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/flatlab/js/jquery-1.8.3.min.js"></script>
</head>

<body>

<section id="container" >
<!--header start-->
    <header class="header blue-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
        <a href="<?php echo $base_url; ?>" class="logo"><?=isset($settings["company"])?$settings["company"]:""?></a>
    <div class="nav notify-row" id="top_menu"></div>
    <div class="container" style="padding-top:10px; ">
        <a target="_blank" href="<?php echo base_url(); ?>" class="btn btn-link" style="color: #333;text-decoration: none;font-weight: bold"><?=_l("View Website",$this)?></a>
        <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-link dropdown-toggle" href="#">
                <span class="username"><?=$this->session->userdata('username')?></span>
                <i class="fa fa-caret-down"></i>
            </button>
            <ul class="dropdown-menu extended logout">
                <div class="log-arrow-up"></div>
                <li><a href="<?php echo base_url(); ?>admin/admin_setting"><i class=" fa fa-cog"></i><?=_l('Settings',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>admin-sign/logout"><i class="fa fa-key"></i><?=_l('Log Out',$this);?></a></li>
            </ul>
        </div>
    </div>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">
                <li><a <?=($page == "home")?'class="active"':''?> href="<?php echo base_url(); ?>admin/"><i class="fa fa-dashboard"></i><span><?=_l("Dashboard",$this)?></span></a></li>
                <li class="sub-menu">
                    <a href="javascript:;" <?=(isset($data_type) && $data_type=="page")?'class="active"':''?>>
                        <i class="fa fa-copy"></i>
                        <span><?=_l("Content",$this)?></span>
                    </a>
                    <ul class="sub">
                        <?php if(isset($page_list) && count($page_list)!=0){ ?>
                        <?php foreach($page_list as $data){ ?>
                            <?php if(allowed_page_fields($data["page_type"],"extension",$all_page_type)){ ?>
                                <li <?=(isset($data_type) && isset($relation_id) && $data_type=="page" && $relation_id==$data["page_id"])?'class="active"':''?>><a  href="<?php echo $base_url; ?>extensions/page/<?=$data["page_id"]?>"><i class="fa fa-file-text-o"></i> <?=$data["page_name"]?></a></li>
                                <?php } ?>
                                <?php if(allowed_page_fields($data["page_type"],"gallery",$all_page_type)){ ?>
                                <li <?=(isset($data_type) && isset($relation_id) && $data_type=="page" && $relation_id==$data["page_id"])?'class="active"':''?>><a href="<?php echo $base_url; ?>gallery/page/<?=$data["page_id"]?>"><i class="fa fa-file-image-o"></i> <?=$data["page_name"]?></a></li>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </li>
                <li><a <?=($page == "comment")?'class="active"':''?> href="<?php echo base_url(); ?>admin/comment"><i class="fa fa-comment"></i><span><?=_l("Comments",$this)?></span></a></li>
                <li><a <?=($page == "page")?'class="active"':''?> href="<?php echo base_url(); ?>admin/page"><i class="fa fa-file"></i><span><?=_l("Pages",$this)?></span></a></li>
                <li><a <?=($page == "uploaded_images")?'class="active"':''?> href="<?php echo base_url(); ?>admin/uploaded_images_manager"><i class="fa fa-upload"></i><span><?=_l("Uploaded pictures",$this)?></span></a></li>
                <li><a <?=($page == "user")?'class="active"':''?> href="<?php echo base_url(); ?>admin/user"><i class="fa fa-users"></i><span><?=_l("Members",$this)?></span></a></li>
                <li><a <?=($page == "language")?'class="active"':''?> href="<?php echo base_url(); ?>admin/language"><i class="fa fa-globe"></i><span><?=_l("Languages",$this)?></span></a></li>
                <li><a <?=($page == "menu")?'class="active"':''?> href="<?php echo base_url(); ?>admin/editmenu"><i class="fa fa-sitemap"></i><span><?=_l("Menu manager",$this)?></span></a></li>
                <li><a <?=($page == "setting")?'class="active"':''?> href="<?php echo base_url(); ?>admin/settings"><i class="fa fa-gears"></i><span><?=_l("Settings",$this)?></span></a></li>
                <!--multi level menu end-->
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <?php echo $content; ?>
        </section>
    </section>
    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            2015 &copy; Design by Mojtaba.
            <a href="#" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="<?php echo base_url(); ?>assets/flatlab/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="<?php echo base_url(); ?>assets/flatlab/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/flatlab/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/owl.carousel.js" ></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/jquery.customSelect.min.js" ></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/respond.min.js" ></script>
<script src="<?php echo base_url(); ?>assets/flatlab/assets/gritter/js/jquery.gritter.js" ></script>

<!--common script for all pages-->
<script src="<?php echo base_url(); ?>assets/flatlab/js/common-scripts.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/flatlab/assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>

<!--script for this page-->
<!--<script src="--><?//=base_url()?><!--assets/flatlab/js/sparkline-chart.js"></script>-->
<script src="<?php echo base_url(); ?>assets/flatlab/js/easy-pie-chart.js"></script>
<script src="<?php echo base_url(); ?>assets/flatlab/js/count.js"></script>

<script>
    var Gritter = function () {
        $(window).load(function(){
            <?php if($this->session->flashdata('message')){ ?>
                $.gritter.add({
                        title: '<?=_l("Message",$this)?>',
                        text: '<?=$this->session->flashdata('message')?>'
                });
            <?php } ?>
            <?php if($this->session->flashdata('success')){ ?>
                $.gritter.add({
                        title: '<?=_l("Success",$this)?>',
                        class_name: 'gritter-success',
                        text: '<?=$this->session->flashdata('success')?>'
                });
            <?php } ?>
            <?php if($this->session->flashdata('error')){ ?>
                $.gritter.add({
                        title: '<?=_l("Error",$this)?>',
                        class_name: 'gritter-error',
                        text: '<?=$this->session->flashdata('error')?>'
                });
            <?php } ?>
        });
    }();
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
        $(".scroll-box").niceScroll({cursorcolor:"#555555"});
    });

</script>

</body>

<!-- Mirrored from thevectorlab.net/flatlab/index.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 09 Feb 2014 08:47:28 GMT -->
</html>
