<h4 class="title"><?php echo $title; ?></h4>
<p class="font-green-soft"><?php echo $message; ?></p>
<?php if(isset($action_buttons) && count($action_buttons)!=0){ ?>
    <?php foreach($action_buttons as $item){ ?>
        <a class="btn btn-default btn-lg" href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a>
    <?php } ?>
<?php } ?>
