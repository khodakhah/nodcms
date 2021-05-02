<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <?php $this->addCSSFile("assets/plugins/cubeportfolio/css/cubeportfolio"); ?>
    <?php $this->addJsFile("assets/plugins/cubeportfolio/js/jquery.cubeportfolio.min"); ?>
    <?php $this->addJsFile("assets/nodcms/Packages/Gallery/gallery-list.min"); ?>
    <div class="container-fluid">
        <div data-role="gallery" class="cbp margin-top-20 margin-bottom-20">
            <?php foreach($data_list as $item){ ?>
                <div class="cbp-item print motion">
                    <a href="<?php echo base_url($this->language['code']."/album-$item[gallery_id]"); ?>" class="cbp-caption cbp-singlePageInline" data-title="<?php echo $item['title']; ?>" rel="nofollow">
                        <div class="cbp-caption-defaultWrap">
                            <img src="<?php echo base_url(image($item['gallery_image'],"",400,300)); ?>" class="img-responsive" alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>">
                        </div>
                        <div class="cbp-caption-activeWrap">
                            <div class="cbp-l-caption-alignLeft">
                                <div class="cbp-l-caption-body">
                                    <div class="cbp-l-caption-title"><?php echo $item['title']; ?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
