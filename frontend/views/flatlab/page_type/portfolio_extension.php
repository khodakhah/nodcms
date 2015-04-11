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
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <article class="panel">
                    <h1 class="panel-heading"><?=$data['name']?></h1>
                    <div class="panel-body">
                        <img class="img-rounded" src="<?=base_url()?><?=(isset($data['image']) && $data['image']!="")?$data['image']:$settings['default_image']?>" alt="<?=$data['name']?>" title="<?=$data['name']?>" style="width:100%;"/>
                        <hr>
                        <div class="text-info"><?=date("Y-m-d | l H:i",$data['created_date'])?></div>
                        <?=$data['description']?>
                    </div>
                </article>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <?php if(isset($relations) && count($relations)!=0){ ?>
                    <div class="row">
                        <?php foreach($relations as $item){ ?>
                        <article class="panel portfolio-effect-item">
                            <div class="pro-img-box">
                                <a title="<?=$item['name']?>" href="<?=base_url().$lang?>/extension/<?=$item['extension_id']?>"><img src="<?=base_url()?><?=image($item['image'],$settings['default_image'],300,200)?>" alt="<?=$item['name']?>" title="<?=$item['name']?>" style="width:100%;"/></a>
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
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
