<?php if(isset($data) && count($data)!=0){ ?>
    <div class="container">
        <div class="headline">
            <h3><?=$page_data["title_caption"]?></h3>
        </div>
        <div class="row">
            <?php foreach($data as $item){ ?>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="group-effect-item">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div><img src="<?=base_url()?><?=image($item['image'],$settings['default_image'],200,200)?>" style="width: 100%;"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h3><?=$item['name']?></h3>
                                <p><?=$item['extension_more']["job"]?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if(isset($preview_limit) && count($data)>$preview_limit){ ?>
            <a href="<?=base_url().$lang?>/page/<?=$page_data["page_id"]?>" class="btn btn-primary btn-shadow"><?=_l("More",$this)?></a>
        <?php } ?>
    </div>
<?php } ?>