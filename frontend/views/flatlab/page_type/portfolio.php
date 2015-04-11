<?php if(isset($data) && $data!=0){ ?>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=base_url().$lang?>"><i class="fa fa-home"></i> <?=_l("Home",$this)?></a></li>
            <li class="active"><?=$data['title_caption']?></li>
        </ul>
        <div class="row">
            <?php foreach($data["body"] as $item){ ?>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <article class="panel portfolio-effect-item">
                    <div class="pro-img-box">
                        <a class="fancybox" rel="group<?=$data["page_id"]?>" title="<?=$item['name']?>" href="<?=base_url().$item['image']?>"><img class="img-rounded" src="<?=base_url()?><?=image($item['image'],$settings['default_image'],300,200)?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" style="width:100%;"/></a>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-8 co-md-8 col-sm-8 col-xs-8">
                                <p><?=$item['name']?></p>
                            </div>
                            <div class="col-lg-4 co-md-4 col-sm-4 col-xs-4">
                                <a href="<?=base_url().$lang?>/extension/<?=$item["extension_id"]?>" class="btn btn-danger btn-shadow pull-right"><?=_l("Read Info",$this)?></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <?php } ?>
            <div id="ajax_load"></div>
        </div>
        <div class="text-center"><img src="<?=base_url()?>/upload_file/loading.gif" id="loading" style="display: none;" alt="<?=_l("loading...",$this)?>" title="<?=_l("loading...",$this)?>"></div>
    </div>
<script src="<?=base_url()?>assets/flatlab/assets/fancybox/source/jquery.fancybox.js"></script>
<script>
    $(window).load(function() {
        'use strict';
        //    fancybox
        jQuery(".fancybox").fancybox();
    });

    $(function(){
        var displayAll = 0;
        var lastofset = 0;
        $(window).scroll(function(){
            if ($(document).height() <= $(window).scrollTop() + $(window).height() && displayAll==0) {
                $("#loading").show();
                lastofset+=10;
                $.ajax({
                    url: "<?=base_url().$lang?>/page/<?=$data['article_id']?>?offset=" + lastofset + "&ajax"
                }).done(function(data) {
                            if(data!=""){
                                $("#ajax_load").before(data);
                            }else{
                                displayAll = 1;
                            }
                            $("#loading").hide();
                        });
            }
        });
    });
</script>
<?php } ?>