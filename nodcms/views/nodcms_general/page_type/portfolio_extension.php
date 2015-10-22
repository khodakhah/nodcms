<div class="page-title">
    <div class="container">
        <h1><?php echo isset($title)?$title:""; ?></h1>
        <form class="search-form" role="search" onsubmit="return check_search();">
            <input type="text" placeholder="<?=_l("Search",$this)?>..." name="filter_search" class="form-control" value="<?=isset($search_word)?$search_word:""?>">
            <button class="btn btn-primary" id="doSearch" type="submit"><i class="fa fa-search"></i>&nbsp;</button>
        </form>
    </div>
</div>
<div class="page-sub-title">
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
    </div>
</div>
<?php if(isset($data) && $data!=0){ ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                <img src="<?=base_url()?><?=(isset($data['image']) && $data['image']!="")?$data['image']:$settings['default_image']?>" alt="<?=$data['name']?>" title="<?=$data['name']?>" style="width:100%;"/>
                <hr>
                <p class="text-info"><?=date("Y-m-d | l H:i",$data['created_date'])?></p>
                <?=$data['description']?>
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