<div class="row-color">
    <?php if(isset($data) && $data!=0){ ?>
        <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=base_url().$lang?>"><i class="fa fa-home"></i> <?=_l("Home",$this)?></a></li>
            <?php if(isset($search_result) && $search_result!=""){ ?>
            <li><a href="<?=base_url().$lang?>/search?filter=<?=$search_result?>"><?=_l("Search result",$this)?>: "<?=str_replace("_"," ",$search_result)?>"</a></li>
            <li class="active"><a href="<?=base_url().$lang?>/page/<?=$data["page_id"]?>"><?=$data["title_caption"]?></a>: <?=$data['name']?></li>
            <?php }else{ ?>
            <li><a href="<?=base_url().$lang?>/page/<?=$data["page_id"]?>"><?=$data["title_caption"]?></a></li>
            <li class="active"><?=$data['name']?></li>
            <?php } ?>
        </ul>
        <article class="panel">
            <header class="panel-heading"><?=$data['name']?></header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                        <img src="<?=base_url()?><?=(isset($data['image']) && $data['image']!="")?$data['image']:$settings['default_image']?>" alt="<?=$data['name']?>" title="<?=$data['name']?>" style="width:100%;"/>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="text-info"><?=date("Y-m-d | l H:i",$data['created_date'])?></div>
                        <hr>
                        <?=$data['description']?>
                    </div>
                </div>
            </div>
        </article>
        <article class="panel">
            <div class="panel-body">
                <div class="text-center mbot30">
                    <h3 class="timeline-title"><?=_l("Comments",$this)?></h3>
                    <p class="t-info"><?=_l("Please send us your feedback",$this)?></p>
                </div>
                <?php if(isset($comments) && count($comments)!=0){ $i=0; ?>
                <div class="timeline">
                    <?php foreach($comments as $item){ $i++; ?>
                    <article class="timeline-item <?=($i%2)==0?"":"alt"?>">
                        <div class="timeline-desk">
                            <div class="panel">
                                <div class="panel-body">
                                    <span class="arrow<?=($i%2)==0?"":"-alt"?>"></span>
                                    <span class="timeline-icon <?=($i%2)==0?"green":"purple"?>"></span>
                                    <span class="timeline-date"><?=mkh_int_fullDate($item["created_date"])?></span>
                                    <h1 class="<?=($i%2)==0?"green":"purple"?>"><i class="fa fa-comment-o"></i> <?=$item["username"]?></h1>
                                    <p><?=$item["content"]?></p>
                                    <?php if(isset($item["sub_comments"]) && count($item["sub_comments"]) !=0 ){ ?>
                                    <?php foreach($item["sub_comments"] as $sub_item){ ?>
                                        <div class="notification">
                                            <h1 class="red"><i class="fa fa-comments-o"></i> <?=$sub_item["username"]?></h1>
                                            <?=$sub_item["content"]?>
                                        </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if(isset($_SESSION["user"])){ ?>
                <div class="chat-form">
                    <label for="comment_text" class="control-label"> <i class="fa fa-user"></i> <?=$_SESSION["user"]["username"]?></label>
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-8">
                            <div class="form-group">
                                <textarea id="comment_text" data-ext-id="<?=$data["extension_id"]?>" type="text" class="form-control col-lg-12" placeholder="<?=_l("Type a message here",$this)?>..." rows="1"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                            <a class="btn btn-success btn-block" href="javascript:;" id="comment_send"><?=_l("Send",$this)?></a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </article>

    </div>
    <?php if(isset($_SESSION["user"])){ ?>
        <script>
            $("#comment_text").keyup(function(){
                var thistext = $(this).val(); if(thistext.match(/\n/g)==null){ $(this).attr('rows',1); }else{ $(this).attr('rows',parseInt(thistext.match(/\n/g).length)+1); }
            });
            $("#comment_send").click(function(){
                $("#comment_text").parent().removeClass("has-error").find(".help-block").remove();
                $.post("<?=base_url().$lang?>/ajax/addcomment",{"comment":$("#comment_text").val(),"ext_id":$("#comment_text").attr("data-ext-id")},function(msg){
                    eval("var data = " + msg);
                    if(data.status == 1){
                        $("#comment_text").val("");
                        $("#comment_text").parent().addClass("has-success").append("<p class='help-block'>" + data.success +"</p>");
                    }else{
                        $("#comment_text").parent().addClass("has-error").append("<p class='help-block'>" + data.errors +"</p>");
                    }
                });
            });
        </script>
        <?php } ?>
    <?php } ?>
<div class="container">
    <?php if(isset($relations) && count($relations)!=0){ ?>
        <div class="row">
            <?php foreach($relations as $item){ ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <article class="panel">
                    <div class="pro-img-box">
                        <a title="<?=$item['name']?>" href="<?=base_url().$lang?>/extension/<?=$item['extension_id']?>"><img src="<?=base_url()?><?=image($item['image'],$settings['default_image'],300,200)?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" style="width:100%;"/></a>
                    </div>
                    <div class="panel-body">
                        <h3><?=$item['name']?></h3>
                        <div class="date-description"><?=date("Y-m-d | l H:i",$item['created_date'])?></div>
                        <hr>
                        <?=substr_string($item['description'])?>
                        <a href="<?=base_url().$lang?>/extension/<?=$item["extension_id"]?>" class="btn btn-link"><?=_l("Read More",$this)?></a>
                    </div>
                </article>
            </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
</div>
