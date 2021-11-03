<?php if(isset($item) && count($item)!=0){ ?>
    <div class="card bg-dark font-white">
        <img alt="<?php echo $item['name']; ?>" title="<?php echo $item['name']; ?>" class="card-img" src="<?php echo base_url($item['profile_image']); ?>">
        <div class="card-img-overlay text-center bg-dark-fade">
            <h2 class="card-title"><?php echo $item['name']; ?></h2>
            <p class="card-text"><?php echo $item['name_title']; ?></p>
            <div class="font-lg card-text"><?php echo $item['preview_description']?></div>
            <?php if($item['profile_uri']!=""){ ?>
                <a class="btn white btn-outline" href="<?php echo base_url("{$this->lang}/about-$item[profile_uri]"); ?>">
                    <?php echo _l("Learn more", $this); ?>
                </a>
            <?php }?>
        </div>
    </div>
<?php } ?>