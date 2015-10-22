<?php if(isset($data) && count($data)!=0){ ?>
<div class="row-color">
    <div class="container">
        <div class="row fade-hover">
            <?php foreach($data as $item){ ?>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="pricing-table <?=(isset($item['extension_more']["special"]) && $item['extension_more']["special"]==1)?'most-popular':''?>">
                    <div class="pricing-head">
                        <h1> <?=$item['name']?> </h1>
                        <h2>
                            <span class="note">$</span><?=$item['extension_more']["price"]?> </h2>
                    </div>
                    <ul class="list-unstyled">
                        <li><?=isset($item['extension_more']["row1"])?$item['extension_more']["row1"]:"-"?></li>
                        <li><?=isset($item['extension_more']["row2"])?$item['extension_more']["row2"]:"-"?></li>
                        <li><?=isset($item['extension_more']["row3"])?$item['extension_more']["row3"]:"-"?></li>
                        <li><?=isset($item['extension_more']["row4"])?$item['extension_more']["row4"]:"-"?></li>
                    </ul>
                    <div class="price-actions">
                        <a class="btn" href="<?=isset($item['extension_more']["button_link"])?$item['extension_more']["button_link"]:"#"?>"><?=isset($item['extension_more']["button_name"])?$item['extension_more']["button_name"]:"-"?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>