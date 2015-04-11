<link href="<?=base_url()?>assets//smoothDivScroll/css/smoothDivScroll.css" rel="stylesheet">
<style type="text/css">

    #makeMeScrollable
    {
        width:100%;
        /*height: 330px;*/
        position: relative;
    }
        /* Replace the last selector for the type of element you have in
           your scroller. If you have div's use #makeMeScrollable div.scrollableArea div,
           if you have links use #makeMeScrollable div.scrollableArea a and so on. */
    #makeMeScrollable div.scrollableArea img
    {
        border: 1px solid #030303;background: #282828;
        position: relative;
        float: left;
        margin: 5px;
        padding: 5px;
        /* If you don't want the images in the scroller to be selectable, try the following
           block of code. It's just a nice feature that prevent the images from
           accidentally becoming selected/inverted when the user interacts with the scroller. */
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
</style>
<div class="row">
    <div class="col-md-2 col-sm-12 col-xs-12">
        <ul class="nav nav-sidebar drop-down-nav">
            <?php if(isset($category_list) && $category_list){ ?>
            <?php foreach($category_list as $item){ ?>
                <li>
                    <a href="#"><?=$item["category_name"]?></a>
                    <?php if(isset($item["sub_cat"])){ ?>
                    <ul>
                        <?php foreach($item["sub_cat"] as $sValue){ ?>
                        <li><a href="<?=base_url()?>category/<?=$sValue["category_id"]?>"><?=$sValue["category_name"]?></a></li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
    <div class="col-md-10 col-sm-12 col-xs-12 main" id="main-content">
    <div class="row placeholders">
        <div class="col-md-9 col-sm-6 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div style="direction: ltr;">
                        <div id="makeMeScrollable">
                            <?php if(isset($extension_image) && count($extension_image)!=0){ ?>
                            <?php foreach($extension_image as $item){ ?>
<!--                                <img src="--><?//=base_url().image($item["image"],$settings['default_image'],200,400)?><!--" href="--><?//=$item["image"]?><!--" alt="--><?//=$item["name"]?><!--"/>-->
                                    <img href="<?=base_url().$item["image"]?>" class="app-screenshot" src="<?=base_url().$item["image"]?>" href="<?=$item["image"]?>" alt="<?=$item["name"]?>" style="height: 300px"/>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div><?=isset($extension['description'])?$extension['description']:"";?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 sidebar-app">
            <div class="thumbnail description">
                <div class="img"><span><img src="<?=base_url().image($extension["image"],$settings['default_image'],300,300)?>" alt="<?php echo $extension["name"]; ?>" class="img-responsive"></span></div>
                <div class="divider2"></div>
                <div class="caption">
                    <ul>
                        <?=($extension['group_id']==1)?"<li class='text-center'>AppExtreme</li>":"";?>
                        <li class="text-center"><?=isset($extension['enname'])?$extension['enname']:"";?></li>
                        <li class="text-center"><?=isset($extension['name'])?$extension['name']:"";?></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Application Maker",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['app_maker'])?$extension['app_maker']:"";?></div></li>
                        <li class="row"><div class="col-md-4 nopadd"><?=_l("Category",$this)?></div><div class="col-md-8 nopadd"><?=isset($extension['category_name'])?$extension['category_name']:"";?></div></li>
                        <li class="row"><div class="col-md-4 nopadd"><?=_l("License",$this)?></div><div class="col-md-8 nopadd"><?=isset($extension['license_name'])?$extension['license_name']:"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("File Volume",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['file_volume'])?Convertnumber2farsi($extension['file_volume']):"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Application Patch",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['patch2'])?Convertnumber2farsi($extension['patch2']):"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Android Patch",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['patch1'])?Convertnumber2farsi($extension['patch1']):"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Views",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['views'])?Convertnumber2farsi($extension['views']):"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Download Count",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['download'])?Convertnumber2farsi($extension['download']):"0"?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Comment Count",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['comment'])?Convertnumber2farsi($extension['comment']):"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Date added",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['created_date'])?my_int_date($extension['created_date']):"";?></div></li>
                        <li class="row"><div class="col-md-6 nopadd"><?=_l("Date Modified",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['updated_date'])?my_int_date($extension['updated_date']):"";?></div></li>
                        
                        <?php if($extension['price']!=0){ ?><li class="row"><div class="col-md-6"><?=_l("Price",$this)?></div><div class="col-md-6 nopadd"><?=isset($extension['price'])?$this->currency->format($extension['price']):$this->currency->format(0)?></div></li><?php } ?>
                        <li class="text-center">
                            <div id="rate"></div>
                        </li>
                        <li class="buttonlink">
                            <?php if(isset($_SESSION["user"]["user_id"])){ ?>
                            <?php if(isset($extension['price']) && ($extension['price']==0 || ($extension['price']!=0 && isset($user_buy) && $user_buy))){ ?>
                            <a href="<?=base_url();?>extension-download?extension_download_id=<?php echo $extension['extension_id'];?>" class="btn btn-w32"><?=_l("Download Now",$this)?></a>
                            <?php }else{ ?>
                            <a href="<?=base_url();?>checkout/<?=$extension['extension_id'];?>" class="btn btn-w32"><?=_l("Buy Now",$this)?></a>
                            <?php } ?>
                            <a href="#myModal" class="btn btn-w3" data-toggle="modal" href="#myModal"><?=_l("User Comments",$this)?></a>
                            <?php }else{ ?>
                            <div class="alert alert-danger "><?=_l("For free download of our website, you should make a free account and login.",$this)?> <a style="color: #698019" href="<?=base_url()?>register"><?=_l("Sign Up",$this)?></a> </div>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=_l("User Comments",$this)?></h4>
            </div>
            <div class="modal-body">
                <?php if(isset($extension_comment) && count($extension_comment) >0) { ?>
                <?php foreach($extension_comment as $comment) { ?>
                    <article class="media">
                        <a class="pull-left thumb p-thumb floatenfa">
                            <img src="<?=base_url()?><?=$comment["avatar"]?>">
                        </a>
                        <div class="media-body">
                            <span class="cmt-head"><?php echo $comment['content'];?></span>
                            <p> <i class="fa fa-user"></i> <?php echo $comment['comment_by'];?></p>
                        </div>
                    </article>
                    <?php } ?>
                <?php } else {?>
                <article class="media" id="no-comment">
                    <div class="media-body">
                        <h3 class="cmt-head"><?=_l("No Comments Yet",$this)?></h3>
                        <p> <i class="fa fa-time"></i> <?=_l("Please Send Comments For Products",$this)?></p>
                    </div>
                </article>
                <?php }?>

                <?php if(isset($user_download) && $user_download){ ?>
                <div id="success" class="alert alert-success" style="display: none;"></div>
                <div id="error" class="alert alert-danger" style="display: none;"></div>
                <div class="form">
                    <form class="cmxform form-horizontal tasi-form" method="post" name="comment" id="commentform" action="">
                        <?php if(!isset($_SESSION['user'])) { ?>
                        <div class="form-group ">
                            <label for="cname" class="control-label col-lg-2"><?=_l("Name",$this)?> </label>
                            <div class="col-lg-10">
                                <input class=" form-control" id="cname" name="comment_by" minlength="3" type="text" required />
                            </div>
                        </div>
                        <?php }else{ ?>
                        <input value="<?=$_SESSION['user']["username"]?>" id="cname" name="comment_by" type="hidden" />
                        <?php } ?>
                        <div class="form-group ">
                            <label for="ccomment" class="control-label col-lg-2"><?=_l("Your Comment",$this)?> </label>
                            <div class="col-lg-10">
                                <textarea class="form-control " id="ccomment" name="comment" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <input type="hidden" name="id" class="button" value="<?php echo $current_id;?>">
                                <?php if (isset($_SESSION['user']['user_id'])) { ?>
                                <input type="hidden" name="uid" class="button" value="<?php echo $_SESSION['user']['user_id'];?>">
                                <?php }?>

                                <!-- Alert After Send Comments -->
                                <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"><?=_l("Thank You For Send Comment",$this)?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <br>
                                                <?=_l("Your comment was sent successfully, you have to register to view the site, you need to wait for approval. Your comment will be displayed after administrator approval.",$this)?>
                                                <br><br>
                                                <?=_l("Thank you",$this)?>
                                                <br><br>
                                                <?=_l("Administrator",$this)?>

                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" class="close" data-dismiss="modal" aria-hidden="true" type="button"> <?=_l("Submit",$this)?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Alert After Send Comments -->

                                <button class="btn btn-primary" type="submit" id="button-comment" name="send_comment"><?php echo _l("Send Comment",$this); ?></button>
                                <button class="btn btn-default" type="reset"><?=_l('Reset Form',$this);?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $(".drop-down-nav li>ul").hide();
        $(".drop-down-nav li:has('ul')>a").click(function(){
            if($(this).next().length){
                var thisElement = $(this).next();
                if(thisElement.hasClass("openNow")){
                    thisElement.slideUp().removeClass("openNow");
                }else{
                    $(".drop-down-nav li .openNow").slideUp().removeClass("openNow");
                    $(".drop-down-nav li .openNow").promise().done(function(){
                        thisElement.slideDown().addClass("openNow");
                    });
                }
            }
        });
    });
</script>

    <script src="<?=base_url()?>assets/raty/jquery.raty.js"></script>
<script type="text/javascript">
     $(document).ready(function(){
        <?php if(isset($user_download) && $user_download && isset($user_rate) && !$user_rate){ ?>
        $("#rate").raty({
             path:"<?=base_url()?>assets/raty/img/",
             click: function(data){
                 $.post('<?=base_url()?>ajax/rate/<?=$current_id?>/' + data ,function(msg){
                     if(msg.status==1){
                         $("#rate").raty({score:data,path:"<?=base_url()?>assets/raty/img/",readOnly:true})
                     }else {
                         alert(msg.errors);
                     }
                 },'json');
             }
         });
    <?php }else{ ?>
         $("#rate").raty({score:"<?=isset($extension_rate)?round($extension_rate):0?>",path:"<?=base_url()?>assets/raty/img/",readOnly:true});
    <?php } ?>
    <?php if(isset($user_download) && $user_download){ ?>
        var working = false;
        // submit form
        $('#commentform').submit(function(e){
            $('#success').html('').hide();
            $('#error').html('').hide();
            if($("#commentform input[type=text].require").val() == ""){
                alert("<?=_l("Please enter your name",$this)?>");
                return  false;
            }
            if($("#commentform textarea.require").val() == ""){
                alert("<?=_l("Please enter your comment",$this)?>");
                return  false;
            }
            $(this).find("input[type=submit]").attr("disabled","disabled");
            e.preventDefault();
            working = true;
            // form bang post
            $.post('<?=base_url()?>ajax/addcomment',{"comment":$("#ccomment").val(),"comment_by":$("#cname").val(),"id":"<?=$current_id?>"},function(msg){
                if(msg.status==1){
                    $("#no-comment").remove();
                    // them thanh cong
                    //reset lai khung comment
                    $('#success').html(msg.success);
                    $('#success').show();
                    $("#ccomment").val("");
                }
                else {
                    // co loi xay ra
                    $('#error').html('');
                    $.each(msg.errors,function(k,v){
                        $('#error').append(v);
                    });
                    $("#error").show();
                }
//                alert(msg.status);
                working = false;
            },'json');
            $("#commentform .require").val("");
            $(this).find("input[type=submit]").removeAttr("disabled");
        });
     <?php } ?>
     });
    $(document).ready(function() {
        $(function(){
            $('select.styled').customSelect();
        });
    });

</script>
<script src="<?=base_url()?>assets//smoothDivScroll/js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>

<!-- Latest version (3.1.4) of jQuery Mouse Wheel by Brandon Aaron
     You will find it here: https://github.com/brandonaaron/jquery-mousewheel -->
<script src="<?=base_url()?>assets//smoothDivScroll/js/jquery.mousewheel.min.js" type="text/javascript"></script>

<!-- jQuery Kinectic (1.8.2) used for touch scrolling -->
<!-- https://github.com/davetayls/jquery.kinetic/ -->
<script src="<?=base_url()?>assets//smoothDivScroll/js/jquery.kinetic.min.js" type="text/javascript"></script>

<!-- Smooth Div Scroll 1.3 minified-->
<!--<script src="--><?//=base_url()?><!--assets//smoothDivScroll/js/jquery.smoothdivscroll-1.3-min.js" type="text/javascript"></script>-->
<script src="<?=base_url()?>assets/smoothDivScroll/js/jquery.smoothdivscroll-1.3-min.js" type="text/javascript"></script>

<link href="<?=base_url()?>assets/colorbox/colorbox.css" rel="stylesheet">
<script src="<?=base_url()?>assets/colorbox/jquery.colorbox-min.js"></script>

<script type="text/javascript">
    $(function(){
        $("div#makeMeScrollable").smoothDivScroll({
            visibleHotSpotBackgrounds: "always",
            touchScrolling: true
        });
        $(".app-screenshot").colorbox({
            rel: 'app-screenshot',
            maxWidth: '96%',
            maxHeight: '96%'
        });
        $("ul.drop-down-nav>li>ul").css({"max-height":"350px"})
        $("ul.drop-down-nav>li>ul").niceScroll({cursorcolor:"#000000"});
    });
</script>
