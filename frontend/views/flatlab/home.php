<?php if(isset($pages) && count($pages)!=0){ ?>
    <?php foreach($pages as $item){ ?>
        <?=$item['body']?>
    <?php } ?>
<?php } ?>