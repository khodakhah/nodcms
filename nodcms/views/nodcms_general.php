<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $lang?>">
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
    <title><?=isset($settings["company"])?$settings["company"]:''?><?php echo isset($title)?" - ".$title:""; ?></title>
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
    <meta name="keywords" content="<?php if(isset($keyword)) echo $keyword; ?>" />
    <meta name="description" content="<?php if(isset($description)) echo substr_string(strip_tags($description),0,50); ?>" />

    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?php echo base_url().$lang; ?>/sitemap.xml" /> <!-- No www -->
    <link rel="shortcut icon" href="<?=base_url().$settings["fav_icon"]?>">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="<?=base_url()?>assets/nodcms_general/css/frontend-style.css" rel="stylesheet">
    <!--external css-->
    <link href="<?=base_url()?>assets/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <?php if($_SESSION["language"]["rtl"]==1){ ?>
        <link href="<?=base_url()?>assets/nodcms_general/css/rtl.css" rel="stylesheet">
    <?php } ?>
    <?php if($_SESSION["language"]["code"]=="fa"){ ?>
        <link href="<?=base_url()?>assets/flatlab/fonts_fa/ed-fonts.css" rel="stylesheet">
    <?php } ?>

    <script src="<?=base_url()?>assets/nodcms_general/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript">
            $('img').error(function(){
               <?php if(isset($settings['default_image']) && $settings['default_image']!="") {?>
               $(this).attr('src','<?php echo base_url(); ?><?php echo image($settings['default_image'],$settings['default_image'],220,120);?>');
              <?php } else {?>
              $(this).attr('src','<?php echo base_url(); ?><?php echo image("assets/frontend/img/noimage.jpg","assets/frontend/img/noimage.jpg",220,120);?>');
              <?php }?>
            });
    </script>
    <script type="text/javascript">
        function check_search() {
            var url = '<?=base_url().$lang?>/search?';
            var filter_search = $('input[name=\'filter_search\']').val();
            if (filter_search) {
//            url += encodeURIComponent(filter_search);
                url += "filter=" + filter_search.replace(/ /g,"_");
                window.location = url;
            }
            return false;
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?=base_url().$lang?>"><i class="fa fa-home"></i></a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <?php if(isset($data_menu) && count($data_menu)!=0) {?>
                                <?php foreach($data_menu as $menu) { ?>
                                    <?php if(isset($menu["sub_menu_data"]) && count($menu["sub_menu_data"])!=0){ ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $menu['name']; ?> <i class="fa fa-caret-down"></i></a>
                                            <ul class="dropdown-menu">
                                                <?php foreach($menu["sub_menu_data"] as $suh_menu){ ?>
                                                    <li><a href="<?php echo $suh_menu['url']; ?>"><?php echo $suh_menu['name']; ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php }else{ ?>
                                        <li><a href="<?=$menu["url"]?>"><?=$menu['name']?></a></li>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <li><a href="<?=base_url().$lang?>/contact"><?=_l("Contact us",$this)?></a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <?php if(!isset($_SESSION['user'])){ ?>
                                <li>
                                    <a href="<?=base_url().$lang."/"?>login" class="btn btn-link">
                                        <span class="fa fa-key"></span>
                                        <span class="username"><?=_l('Login',$this);?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?=base_url().$lang."/"?>register" class="btn btn-link">
                                        <span class="fa fa-user"></span>
                                        <span class="username"><?=_l('Sign Up',$this);?></span>
                                    </a>
                                </li>
                            <?php } else {?>
                                <li class="dropdown">
                                    <a data-toggle="dropdown" class="btn btn-shadow btn-success dropdown-toggle" href="#">
                                        <span class="username"><?=isset($_SESSION["user"]["username"])?$_SESSION["user"]["username"]:"Unknown"?></span>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?=base_url().$lang."/"?>profile-password"><i class=" fa fa-lock"></i><?=_l('Change pass',$this);?></a></li>
                                        <li><a href="<?php echo base_url().$lang."/"; ?>login/true"><i class="fa fa-key"></i><?=_l('Log Out',$this);?></a></li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown" class="btn btn-link dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false"> <?php if(isset($_SESSION["language"]['image'])){ ?><img src="<?=base_url().$_SESSION["language"]["image"]?>" style="width: 18px"><?php } ?> <?=$_SESSION["language"]['language_name']?> <span class="caret"></span> </a>
                                <ul class="dropdown-menu">
                                    <?php foreach($languages as $item) {?>
                                        <?php if($item["language_id"]!=$_SESSION["language"]["language_id"]){ ?>
                                            <li><a href="<?=$item["lang_url"]?>"> <?php if(isset($item['image'])){ ?><img src="<?=base_url().$item["image"]?>" style="width: 24px"><?php } ?> <?=$item['language_name']?></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </div>
        </nav>
    </header>
    <?php echo isset($content)?$content:""; ?>
    <footer>
        <div class="modal-footer">
            <div class="container">
                <?=date("Y")?>
                <i class="fa fa-copyright"></i>
                <a href="<?=base_url()?>"><?=substr(str_replace(array("http://","https://"),"",base_url()),0,-1)?></a>
            </div>
        </div>
    </footer>
    <script src="<?=base_url()?>assets/nodcms_general/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $(function () {
                $('.carousel').carousel();
            });
            $(".top-nav-toggle-btn").click(function(){
                $(".top-nav-toggle").toggleClass("top-nav-toggle-show");
            });
        });
    </script>
</body>
</html>