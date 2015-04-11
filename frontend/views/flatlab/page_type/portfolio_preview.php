<?php if(isset($data) && count($data)!=0){ ?>
<div class="row-color">
    <div class="container">
        <h2 class="header-border"><?=$page_data["title_caption"]?></h2>
        <div class="row portfolio-effect">
        <?php foreach($data as $item){ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <article class="panel portfolio-effect-item">
                    <div class="pro-img-box">
                        <a class="fancybox" rel="group<?=$page_data["page_id"]?>" title="<?=$item['name']?>" href="<?=base_url().$item['image']?>"><img class="img-rounded" src="<?=base_url()?><?=image($item['image'],$settings['default_image'],300,200)?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" style="width:100%;"/></a>
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
        </div>
    </div>
</div>
<script src="<?=base_url()?>assets/flatlab/assets/fancybox/source/jquery.fancybox.js"></script>
<script>
    $(window).load(function() {
        'use strict';
        //    fancybox
        jQuery(".fancybox").fancybox();
    });
</script>
<?php } ?>
