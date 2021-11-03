<div class="card">
    <?php if($item['post_image']!=""){ ?>
        <a href="<?php echo $item['post_url']; ?>">
            <img alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>" class="card-img-top" src="<?php echo base_url($item['post_image']); ?>">
        </a>
    <?php } ?>
    <div class="card-body">
        <h2 class="card-title"><?php echo $item['title']; ?></h2>
        <div class="card-text font-grey-mint"><?php echo $item['description']?></div>
        <div class="text-center margin-top-20">
            <a class="btn blue" href="<?php echo $item['post_url']; ?>">
                <?php echo _l("Learn more", $this); ?>
            </a>
        </div>
    </div>
</div>