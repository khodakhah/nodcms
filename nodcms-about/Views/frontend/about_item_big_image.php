<?php if(isset($item) && count($item)!=0){ ?>
    <div class="bg-grey-cararra padding-top-20 padding-bottom-20">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="margin-40">
                        <img alt="<?php echo $item['name']; ?>" title="<?php echo $item['name']; ?>" class="img-fluid" src="<?php echo base_url($item['profile_image']); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <h2 class="margin-top-40"><?php echo $item['name']; ?></h2>
                    <p><?php echo $item['name_title']; ?></p>
                    <div class="font-lg font-grey-mint"><?php echo $item['preview_description']?></div>
                    <?php if($item['profile_uri']!=""){ ?>
                        <a href="<?php echo base_url("{$this->lang}/about-$item[profile_uri]"); ?>">
                            <?php echo _l("Learn more", $this); ?>
                        </a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>