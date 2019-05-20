<div class="text-center alert alert-danger">
    <h1 class=""><?php echo $heading; ?></h1>
    <p class="text-lg"><?php echo $message; ?></p>
    <?php if(isset($buttons) && count($buttons)!=0){ ?>
        <?php foreach ($buttons as $item){ ?>
            <a class="btn default" href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a>
        <?php } ?>
    <?php } ?>
</div>