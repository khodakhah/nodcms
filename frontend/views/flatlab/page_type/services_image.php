<?php if(isset($data) && $data!=0){ ?>
    <div class="row">
        <div class="container">
            <h1 class="text-center"><?=$data["title_caption"]?></h1>
            <div class="row">
                <?php foreach($data["body"] as $item){ ?>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body" style="height: 300px;">
                            <div class="text-center"><img src="<?=base_url().$item['image']?>"></div>
                            <h2 class="text-center"><?=$item['name']?></h2>
                            <div class="text-center">
                                <?=$item['description']?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>