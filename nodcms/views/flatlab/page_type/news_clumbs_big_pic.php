<?php if(isset($data) && $data!=0){ ?>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?=base_url().$lang?>"><i class="fa fa-home"></i> <?=_l("Home",$this)?></a></li>
            <li class="active"><?=$data['title_caption']?></li>
        </ul>
        <div class="row">
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
            <div id="ajax_load"></div>
        </div>
        <div class="text-center"><img src="<?=base_url()?>/upload_file/loading.gif" id="loading" style="display: none;" alt="<?=_l("loading...",$this)?>" title="<?=_l("loading...",$this)?>"></div>
    </div>
<?php } ?>
<script>
    $(function(){
        var displayAll = 0;
        var lastofset = 0;
        $(window).scroll(function(){
            if ($(document).height() <= $(window).scrollTop() + $(window).height() && displayAll==0) {
                $("#loading").show();
                lastofset+=10;
                $.ajax({
                    url: "<?=base_url().$lang?>/page/<?=$data['article_id']?>?offset=" + lastofset + "&ajax"
                }).done(function(data) {
                            if(data!=""){
                                $("#ajax_load").before(data);
                            }else{
                                displayAll = 1;
                            }
                            $("#loading").hide();
                        });
            }
        });
    });
</script>