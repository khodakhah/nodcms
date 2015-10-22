<?php if(isset($data) && $data!=0){ ?>
    <?php foreach($data["body"] as $item){ ?>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <article class="panel">
            <div class="pro-img-box">
                <a title="<?=$item['name']?>" href="<?=base_url().$lang?>/extension/<?=$item['extension_id']?>"><img src="<?=base_url()?><?=image($item['image'],$settings['default_image'],220,149)?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" style="width:100%;"/></a>
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
<?php } ?>