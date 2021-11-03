<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <?php \Config\Services::layout()->addCSSFile("assets/plugins/cubeportfolio/css/cubeportfolio"); ?>
    <?php \Config\Services::layout()->addJsFile("assets/plugins/cubeportfolio/js/jquery.cubeportfolio.min"); ?>
    <?php \Config\Services::layout()->addJsFile("assets/nodcms/Packages/Portfolio/portfolio-list.min"); ?>
    <div class="padding-top-20 padding-bottom-20">
        <div class="container-fluid">
            <h2 class="text-center margin-bottom-20"><?php echo $title; ?></h2>
            <div class="portfolio-content portfolio-3">
                <div class="clearfix"></div>
                <div data-role="portfolio" class="cbp">
                    <?php foreach($data_list as $item){ ?>
                        <div class="cbp-item print motion">
                            <a href="<?php echo base_url($this->language['code']."/portfolio-$item[portfolio_id]"); ?>" class="cbp-caption cbp-singlePageInline" data-title="<?php echo $item['title']; ?>" rel="nofollow">
                                <div class="cbp-caption-defaultWrap">
                                    <img src="<?php echo base_url(image($item['portfolio_image'],"",400,300)); ?>" class="img-responsive" alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>">
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
        </div>
    </div>
<?php } ?>
