
<div class="row">
    <div class="col-md-2">
        <ul class="nav nav-sidebar drop-down-nav" style="padding: 0 !important;">
            <li>
                <a href="#"><?=_l("All Extensions",$this)?></a>
                <ul <?=(isset($filter_license) || !isset($sub_category_id))?"class='openNow'":""?>>
                    <li><a <?=(!isset($filter_license) && !isset($sub_category_id) && !isset($extension_sort))?"class='active'":""?> href="<?=base_url()?>category"><?=_l("New Extensions",$this)?></a></li>
                    <li><a <?=(isset($extension_sort) && $extension_sort=="download")?"class='active'":""?> href="<?=base_url()?>category?type=thumb&amp;sort=download&amp;order=DESC"><?=_l("Best Downloads",$this)?></a></li>
                    <?php if(isset($all_license) && count($all_license)!=0){ ?>
                    <?php foreach($all_license as $item){ ?>
                        <li><a <?=(isset($filter_license) && $filter_license==$item["license_id"])?"class='active'":""?> href="<?=base_url()?>category?type=thumb&amp;filter_license=<?=$item["license_id"]?>"><?=$item["license_name"]?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </li>
            <?php if(isset($category_list) && $category_list){ ?>
            <?php foreach($category_list as $item){ ?>
                <li>
                    <a href="#"><?=$item["category_name"]?></a>
                    <?php if(isset($item["sub_cat"])){ ?>
                    <ul <?=(isset($sub_category_id) && $item["category_id"]==$sub_category_id)?"class='openNow'":""?>>
                        <?php foreach($item["sub_cat"] as $sValue){ ?>
                        <li><a <?=(isset($category_id) && $sValue["category_id"]==$category_id)?'class="active"':''?> href="<?=base_url()?>category/<?=$sValue["category_id"]?>"><?=$sValue["category_name"]?></a></li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <div class="row placeholders">
                    <?php if(isset($extension_data) && count($extension_data) > 0) { $rating=array(); $i=0; ?>
                    <?php foreach($extension_data as $data) { $i++; if(!isset($rating["rate_".$data['extension_id']])) $rating["rate_".$data['extension_id']]=$data['count_rate']!=0?round($data['sum_rate']/$data['count_rate']):0; ?>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="thumbnail <?=$i==6?"last":""?><?=($i==1?"first":"")?>">
                                <a href="<?=base_url()?>extension/<?=$data['extension_id']?>">
                                    <article class="media">
                                        <div class="img"><img src="<?=base_url()?><?=image($data['image'],$settings['default_image'],200,200)?>"></div>
                                        <div class="divider"></div>
                                        <div class="caption text-center">
                                            <ul>
                                                <li><?=isset($data['enname'])?$data['enname']:""?></li>
                                                <li><?=$data['name']?></li>
                                                <li class="row"><?=isset($data['license_name'])?$data['license_name']:"";?></li>
                                                <li class="text-center"><div style="margin: 0 auto;" class="rate_<?=$data['extension_id']?>"></div></li>
                                                <li><?=$data['price']==0?_l("Free",$this):$this->currency->format($data['price'])?></li>
                                            </ul>
                                        </div>
                                    </article>
                                </a>
                            </div>
                        </div>
                        <?php if($i==6){ $i=0; ?>
                                </div><div class="row placeholders">
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="text-center"><?php echo $pagination;?></div>
    </div>
</div>
<script src="<?=base_url()?>assets/raty/jquery.raty.js"></script>
<script type="text/javascript">
    $(function(){
        <?php if(isset($rating)){ ?>
        <?php foreach($rating as $key=>$item){ ?>
            $(".<?=$key?>").raty({score:"<?=$item?>",path:"<?=base_url()?>assets/raty/img/",readOnly:true});
            <?php } ?>
        <?php } ?>

        $(".drop-down-nav li>ul").hide();
        $(".drop-down-nav li>ul.openNow").show();
        $(".drop-down-nav li:has('ul')>a").click(function(){
            if($(this).next().length){
                var thisElement = $(this).next();
                if(thisElement.hasClass("openNow")){
                    thisElement.slideUp().removeClass("openNow");
                }else{
                    $(".drop-down-nav li .openNow").slideUp().removeClass("openNow");
                    $(".drop-down-nav li .openNow").promise().done(function(){
                        thisElement.slideDown().addClass("openNow");
                    });
                }
            }
        });
        $("ul.drop-down-nav>li>ul").css({"max-height":"350px"})
        $("ul.drop-down-nav>li>ul").niceScroll({cursorcolor:"#000000"});
    });
</script>