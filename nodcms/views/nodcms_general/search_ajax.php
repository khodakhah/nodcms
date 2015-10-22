<?php if(isset($data) && count($data)!=0){ ?>
    <?php foreach($data as $item){ ?>
    <div class="classic-search">
        <h4><a href="<?=base_url().$lang?>/extension/<?=$item["extension_id"]?>"><?=str_replace($text_search,$text_replace,$item["name"])?></a></h4>
        <a href="<?=base_url().$lang?>/extension/<?=$item["extension_id"]?>"><?=base_url().$lang?>/extension/<?=$item["extension_id"]?></a>
        <p><?=str_replace($text_search,$text_replace,substr_string($item['description'],0,30))?></p>
    </div>
    <?php } ?>
<?php } ?>
