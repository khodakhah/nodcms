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


    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?=base_url()?>assets/startuply/css/bootstrap.min.css" type="text/css" media="all" />
    <?php if($_SESSION["language"]["rtl"]==1){ ?>
        <link href="<?=base_url()?>assets/startuply/css/rtl.bootstrap.min.css" rel="stylesheet">
    <?php } ?>
    <link rel="stylesheet" href="<?=base_url()?>assets/startuply/css/font-awesome.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?=base_url()?>assets/startuply/css/font-lineicons.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?=base_url()?>assets/startuply/css/animate.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?=base_url()?>assets/startuply/css/toastr.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="<?=base_url()?>assets/startuply/css/style.css" type="text/css" media="all" />
    <?php if($_SESSION["language"]["rtl"]==1){ ?>
        <link href="<?=base_url()?>assets/startuply/css/rtl.css" rel="stylesheet">
    <?php } ?>

    <!--[if lt IE 9]>
    <script src="<?=base_url()?>assets/startuply/js/html5.js"></script>
    <script src="<?=base_url()?>assets/startuply/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
            $('img').error(function(){
               <?php if(isset($settings['default_image']) && $settings['default_image']!="") {?>
               $(this).attr('src','<?php echo base_url(); ?><?php echo image($settings['default_image'],$settings['default_image'],220,120);?>');
              <?php } else {?>
              $(this).attr('src','<?php echo base_url(); ?><?php echo image("assets/frontend/img/noimage.jpg","assets/frontend/img/noimage.jpg",220,120);?>');
              <?php }?>
            });
    </script>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery-1.11.0.min.js?ver=1"></script>
    <![endif]-->
    <!--[if (gte IE 9) | (!IE)]><!-->
    <script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery-2.1.0.min.js?ver=1"></script>
    <!--<![endif]-->
</head>
<body <?=isset($body_id)?"id='$body_id'":""?>>
    <div id="mask">
        <div id="loader"></div>
    </div>
    <header>
        <div class="header-holder">
        <nav class="navigation navigation-header">
            <div class="container">
                <div class="navigation-brand">
                    <div class="brand-logo">
                        <a href="<?=base_url()?>" class="logo"><img src="<?=base_url().image($settings["logo"],"",200,50)?>"></a>
                        <span class="sr-only">startup.ly</span>
                    </div>
                    <button class="navigation-toggle visible-xs" type="button" data-toggle="dropdown" data-target=".navigation-navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navigation-navbar">
                    <ul class="navigation-bar navigation-bar-left">
                        <?php foreach($data_menu as $key=>$menu) { ?>
                        <li class="<?=$menu["active_class"]?>"><a href="<?=$menu["url"]?>"><?=$menu['name']?></a></li>
                        <?php } ?>
                        <li><a href="<?=base_url().$lang?>/contact"><?=_l("Contact us",$this)?></a></li>
                    </ul>
                    <ul class="navigation-bar navigation-bar-right">
                        <?php if(!isset($_SESSION['user'])){ ?>
                        <li><a href="<?=base_url().$lang."/"?>login"><?=_l('Login',$this);?></a></li>
                        <li class="featured"><a href="<?=base_url().$lang."/"?>register"><?=_l('Sign Up',$this);?></a></li>
                        <?php } else {?>
                        <li class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" href="#">
                                <span class="username"><?=isset($_SESSION["user"]["username"])?$_SESSION["user"]["username"]:"Unknown"?></span>
                                <b class="caret"></b>
                            </button>
                            <ul class="dropdown-menu">
                                <div class="log-arrow-up"></div>
                                <li><a href="<?=base_url().$lang."/"?>profile-password" class="btn-block"><i class=" fa fa-lock"></i> <?=_l('Change pass',$this);?></a></li>
                                <li><a href="<?php echo base_url().$lang."/"; ?>login/true" class="btn-block"><i class="fa fa-key"></i> <?=_l('Log Out',$this);?></a></li>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        </div>
    </header>
    <?php require_once ("startuply/" . $content . ".php"); ?>


    <section id="guarantee" class="long-block light">
        <div class="container">
            <div class="col-md-12 col-lg-9">
                <i class="icon icon-seo-icons-24 pull-left"></i>
                <article class="pull-left">
                    <h2><strong><?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?></strong> <?=_l("You can see live demo here!",$this)?></h2>
                    <p class="thin"><?=_l("This link is a live demo for NodCMS. You can see user side and admin side there.",$this)?></p>
                </article>
            </div>

            <div class="col-md-12 col-lg-3">
                <a href="http://demo.nodcms.com" class="btn btn-default" target="_blank"><?=_l("Live Demo",$this)?></a>
            </div>
        </div>
    </section>

    <footer id="footer" class="footer light">
        <div class="container">
            <div class="footer-content row">
                <div class="col-sm-4">
                    <div class="logo-wrapper">
                        <img src="<?=base_url().image($settings["logo"],"",250,50)?>" alt="<?=isset($settings["options"]["company"])?$settings["options"]["company"]:""?>">
                    </div>
                    <p><?=isset($settings["options"]["site_description"])?$settings["options"]["site_description"]:""?></p>
                    <p><strong><?=isset($settings["options"]["company"])?$settings["options"]["company"]:""?></strong></p>
                </div>
                <div class="col-sm-5 social-wrap">
                    <div class="footer-title"><?=_l("Social Networks",$this)?></div>
                    <ul class="list-inline socials">
                        <li><a href="#"><span class="icon icon-socialmedia-08"></span></a></li>
                        <li><a href="#"><span class="icon icon-socialmedia-09"></span></a></li>
                        <li><a href="#"><span class="icon icon-socialmedia-16"></span></a></li>
                        <li><a href="#"><span class="icon icon-socialmedia-04"></span></a></li>
                    </ul>
                    <ul class="list-inline socials">
                        <li><a href="#"><span class="icon icon-socialmedia-07"></span></a></li>
                        <li><a href="#"><span class="icon icon-socialmedia-16"></span></a></li>
                        <li><a href="#"><span class="icon icon-socialmedia-09"></span></a></li>
                        <li><a href="#"><span class="icon icon-socialmedia-08"></span></a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <div class="footer-title"><?=_l("Our Contacts",$this)?></div>
                    <ul class="list-unstyled">
                        <li>
                            <span class="icon icon-chat-messages-14"></span>
                            <a href="mailto:<?=$settings["email"]?>"><?=$settings["email"]?></a>
                        </li>
                        <li>
                            <span class="icon icon-seo-icons-34"></span>
                            <?=$settings["address"]?>
                        </li>
                        <li>
                            <span class="icon icon-seo-icons-17"></span>
                            <?=$settings["phone"]?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyright"><?=isset($settings["options"]["company"])?$settings["options"]["company"]:""?> <?=date("Y",time())?>. <?=_l("All rights reserved",$this)?>.</div>
    </footer>

<div class="back-to-top"><i class="fa fa-angle-up fa-3x"></i></div>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery.nav.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery.appear.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery.plugin.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/jquery.countdown.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/waypoints.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/waypoints-sticky.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/toastr.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/headhesive.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/mailchimp/js/mailing-list.js"></script>
<script type="text/javascript" src="<?=base_url()?>/assets/startuply/js/scripts.js"></script>
<script>
    <?php if($this->session->flashdata("message_error")){ ?>
        $(function(){
            $(window).load(function(){
                toastr.error('<?=$this->session->flashdata("message_error")?>');
            });
        });
    <?php } ?>
</script>
</body>
</html>




