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
    <title><?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?><?php echo isset($title)?" - ".$title:""; ?></title>
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
    <meta name="keywords" content="<?php if(isset($keyword)) echo $keyword; ?>" />
    <meta name="description" content="<?php if(isset($description)) echo substr_string(strip_tags($description),0,50); ?>" />

    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?php echo base_url().$lang; ?>/sitemap.xml" /> <!-- No www -->
    <link rel="shortcut icon" href="<?=base_url().$settings["fav_icon"]?>">

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>assets/nodcms_general/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/newsticker/ticker-style.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/nodcms_general/css/style.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/nodcms_general/css/frontend-style.css" rel="stylesheet">
<!--    <link href="--><?//=base_url()?><!--assets/nodcms_general/css/news-style-blue.css" rel="stylesheet">-->
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
</head>
<body>
    <div class="container main-container">
        <header>
            <div class="row">
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                    <div class="pull-left">
                        <h1 class="main-header"><span class="icon-box"><i class="fa fa-info"></i></span> <?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?><?php echo isset($title)?" - ".$title:""; ?></h1>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                    <div class="btn-group pull-right btn-link">
                        <button data-toggle="dropdown" class="btn btn-link dropdown-toggle" type="button"> <?php if(isset($_SESSION["language"]['image'])){ ?><img src="<?=base_url().$_SESSION["language"]["image"]?>" style="width: 18px"><?php } ?> <?=$_SESSION["language"]['language_name']?> <span class="caret"></span> </button>
                        <ul class="dropdown-menu extended">
                            <div class="log-arrow-up"></div>
                            <?php foreach($languages as $item) {?>
                                <?php if($item["language_id"]!=$_SESSION["language"]["language_id"]){ ?>
                                    <li><a href="<?=$item["lang_url"]?>"> <?php if(isset($item['image'])){ ?><img src="<?=base_url().$item["image"]?>" style="width: 24px"><?php } ?> <?=$item['language_name']?></a></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-default navbar-mega" role="navigation">
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
                            <?php foreach($data_menu as $menu) {?>
                                <li><a href="<?=$menu["url"]?>"><?=$menu['name']?></a></li>
                            <?php } ?>
                            <?php } ?>
                            <li><a href="<?=base_url().$lang?>/contact"><?=_l("Contact us",$this)?></a></li>
                        </ul>
                        <form class="navbar-form navbar-right" role="search" onsubmit="return check_search();">
                            <div class="form-group">
                                <input type="text" placeholder="<?=_l("Search",$this)?>..." name="filter_search" class="form-control" value="<?=isset($search_word)?$search_word:""?>">
                            </div>
                            <button class="btn btn-primary" id="doSearch" type="submit"><i class="fa fa-search"></i>&nbsp;</button>
                        </form>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </header>
        <?php require_once ("flatlab/" . $content . ".php"); ?>
        <div class="modal-footer">
            <?=date("Y")?>
            <span class="icon-box"><i class="fa fa-copyright"></i></span>
            <a href="<?=base_url()?>"><?=str_replace(array("http://","/"),"",base_url())?></a>
        </div>
    </div>
    <script src="<?=base_url()?>assets/nodcms_general/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function check_search() {
            url = '<?=base_url().$lang?>/search?';
            var filter_search = $('input[name=\'filter_search\']').attr('value');
            if (filter_search) {
//            url += encodeURIComponent(filter_search);
                url += "filter=" + filter_search.replace(/ /g,"_");
            }
            if (filter_search) {
                window.location = url;
            }
            return false;
        }
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




