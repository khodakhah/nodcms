<?php if(isset($data) && count($data)!=0){ ?>
    <?php foreach($data as $item){ ?>
    <article class="panel">
        <h2 class="panel-heading"><?=$item['name']?></h2>
        <div class="panel-body">
            <?=$item['description']?>
        </div>
    </article>
    <?php } ?>
<?php } ?>