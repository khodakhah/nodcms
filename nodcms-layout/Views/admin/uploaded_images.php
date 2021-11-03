<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="row">
        <?php foreach($data_list as $data){ ?>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
            <p><img class="img-rounded" src="<?=base_url().image($data['image'],$settings['default_image'],300,200)?>" style="width: 100%" alt="Image"></p>
            <p class="text-center"><?=$data["width"]?> <?=_l("px",$this)?> X <?=$data["height"]?> <?=_l("px",$this)?></p>
            <p class="text-center"><?=$data["size"]?> <?=_l("MG",$this)?></p>
            <p class="text-center">
                <a href="javascript:;" class="btn btn-info btn-shadow" onclick="$($(this).parent().parent().parent().parent().attr('insert-to')).attr('value','<?=$data['image']?>');"><i class="fa fa-copy"></i> <?=_l("URL Copy",$this)?></a>
            </p>
        </div>
        <?php } ?>
    </div>
<?php } ?>
