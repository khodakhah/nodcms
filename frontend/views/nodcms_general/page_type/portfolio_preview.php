<?php if(isset($data) && count($data)!=0){ ?>
    <div class="container">
        <div class="headline">
            <h2><?=$page_data["title_caption"]?></h2>
        </div>
        <div class="row portfolio-effect">
        <?php foreach($data as $item){ ?>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <article class="panel portfolio-effect-item">
                    <div class="pro-img-box">
                        <a class="fancybox" rel="group<?=$page_data["page_id"]?>" title="<?=$item['name']?>" href="<?=base_url().$item['image']?>"><img src="<?=base_url()?><?=image($item['image'],$settings['default_image'],300,200)?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" style="width:100%;"/></a>
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
<?php } ?>
