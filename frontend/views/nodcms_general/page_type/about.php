<div class="page-title">
    <div class="container">
        <h1><?php echo isset($title)?$title:""; ?></h1>
    </div>
</div>
<div class="page-sub-title">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=base_url().$lang?>"><i class="fa fa-home"></i> <?=_l("Home",$this)?></a></li>
            <li class="active"><?=$data['title_caption']?></li>
        </ul>
    </div>
</div>
<?php if(isset($data) && count($data)!=0){ ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <?php foreach($data["body"] as $item){ ?>
                    <h4>
                        <?php if($item['extension_icon']!=""){ ?><i class="fa <?=$item['extension_icon']?>"></i><?php } ?>
                        <?=$item['name']?>
                    </h4>
                    <div>
                        <?=$item['description']?>
                    </div>
                    <br>
                <?php } ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?php if($data["avatar"]!=""){ ?><img style="width:100%;" src="<?=base_url().$data["avatar"]?>"><?php } ?>
            </div>
        </div>
    </div>
<?php } ?>