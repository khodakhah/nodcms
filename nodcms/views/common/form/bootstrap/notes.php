<?php foreach ($notes as $item){ ?>
    <div class="note note-<?php echo $item['type']; ?>">
        <h4 class="block"><?php echo $item['title']; ?></h4>
        <p><?php echo $item['content']; ?></p>
    </div>
<?php } ?>
