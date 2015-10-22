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

        $(".top-nav-toggle-btn").click(function(){
//            alert('1');
            $(".top-nav-toggle").toggleClass("top-nav-toggle-show");
        });
    });
</script>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid row">
        <div class="custom-menu-bar">
            <div class="container">
                <button class="btn btn-link top-nav-toggle-btn"><i class="fa fa-bars fa-3x"></i> </button>
                <div class="top-nav-toggle">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="icon"><a href="<?=base_url().$lang."/"?>"><img alt="logo" src="<?=base_url().$settings["logo"]?>" style="height: 50px;"> <?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <!-- user login dropdown start-->
                        <?php if(isset($data_menu) && count($data_menu)!=0) {?>
                        <?php foreach($data_menu as $menu) {?>
                        <li>
                            <a href="<?=$menu["url"]?>"><?=$menu['name']?></a>
                            <?php if(isset($menu['child']) && $menu['child']!=null) {?>
                            <ul class="dropdown-menu">
                                <?php foreach($menu['child'] as $me) {?>
                                <li><a href="<?php echo base_url().$lang."/"; ?>article/<?php echo $me['article_id']; ?>/<?php echo url_title($me['url_title']); ?>.html"><?php echo _l($me['name'],$this);?></a></li>
                                <?php } ?>
                            </ul>
                            <?php }?>
                        </li>
                        <?php } ?>
                        <?php } ?>
                        <li>
                            <a href="<?=base_url().$lang?>/contact"><?=_l("Contact us",$this)?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="search-bar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-shadow btn-default dropdown-toggle" type="button"> <?php if(isset($_SESSION["language"]['image'])){ ?><img src="<?=base_url().$_SESSION["language"]["image"]?>" style="width: 18px"><?php } ?> <?=$_SESSION["language"]['language_name']?> <span class="caret"></span> </button>
                            <ul class="dropdown-menu extended">
                                <div class="log-arrow-up"></div>
                                <?php foreach($languages as $item) {?>
                                <?php if($item["language_id"]!=$_SESSION["language"]["language_id"]){ ?>
                                    <li><a href="<?=$item["lang_url"]?>"> <?php if(isset($item['image'])){ ?><img src="<?=base_url().$item["image"]?>" style="width: 48px"><?php } ?> <?=$item['language_name']?></a></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php if(!isset($_SESSION['user'])){ ?>
                            <a href="<?=base_url().$lang."/"?>login" class="btn btn-link">
                                <span class="fa fa-key"></span>
                                <span class="username"><?=_l('Login',$this);?></span>
                            </a>
                            <a href="<?=base_url().$lang."/"?>register" class="btn btn-link">
                                <span class="fa fa-user"></span>
                                <span class="username"><?=_l('Sign Up',$this);?></span>
                            </a>
                        <?php } else {?>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-shadow btn-success dropdown-toggle" href="#">
                                    <span class="username"><?=isset($_SESSION["user"]["username"])?$_SESSION["user"]["username"]:"Unknown"?></span>
                                    <b class="caret"></b>
                                </button>
                                <ul class="dropdown-menu extended logout">
                                    <div class="log-arrow-up"></div>
                                    <li><a href="<?=base_url().$lang."/"?>profile-password"><i class=" fa fa-lock"></i><?=_l('Change pass',$this);?></a></li>
                                    <li><a href="<?php echo base_url().$lang."/"; ?>login/true"><i class="fa fa-key"></i><?=_l('Log Out',$this);?></a></li>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <form class="pull-right" onsubmit="return check_search();">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <input type="text" placeholder="<?=_l("Search",$this)?>..." name="filter_search" class="form-control" value="<?=isset($search_word)?$search_word:""?>">
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <button class="btn btn-success btn-block" id="doSearch" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
