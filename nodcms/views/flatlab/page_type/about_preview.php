<?php if(isset($data) && count($data)!=0){ ?>
    <div class="row row-color bg-default text-lg">
        <div class="container">
            <h1 class="text-center"><?=isset($settings["options"]["company"])?$settings["options"]["company"]:$settings["company"]?></h1>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <?php if(isset($page_data["avatar"]) && $page_data["avatar"]!=""){ ?><div class="xs-text-center"><img style="width: 100%;" src="<?=base_url().$page_data["avatar"]?>"></div><?php } ?>
                </div>
                <?php foreach($data as $item){ ?>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <h2 class="xs-text-center"><?=$item['name']?></h2>
                    <div class="xs-text-center">
                        <?=$item['description']?>
                    </div>
                    <br>
                    <p class="xs-text-center"><a class="btn btn-info btn-shadow btn-lg" href="<?=base_url().$lang?>/page/<?=$page_data["page_id"]?>"><i class="fa fa-info-circle"></i> <?=_l("Get More Information",$this)?></a></p>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>