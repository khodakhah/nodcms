<?php $this->addCSSFile("assets/plugins/cubeportfolio/css/cubeportfolio"); ?>
<?php $this->addJsFile("assets/plugins/cubeportfolio/js/jquery.cubeportfolio.min"); ?>
<?php $this->addJsFile("assets/nodcms/Packages/Gallery/gallery-images"); ?>
<div class="bg-grey-cararra padding-top-20 padding-bottom-20">
    <div class="container">
        <div class="">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <img src="<?php echo base_url($data['gallery_image']); ?>" alt="<?php echo $data['title']; ?>" title="<?php echo $data['title']; ?>" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-6">
                    <h1 class="border-bottom border-grey font-size-4 padding-bottom-40 font-weight-normal"><?php echo $data['title']; ?></h1>
                    <div class="margin-top-10 font-grey-mint margin-bottom-10"><?php echo $data['description']; ?></div>
                    <div class=""><?php echo $data['details']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="margin-top-20 margin-bottom-20">
        <div class="clearfix"></div>
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
            <div data-role="gallery-images" class="cbp cbp-l-grid-mosaic">
                <?php foreach($data_list as $item){ ?>
                    <div class="cbp-item">
                        <a href="<?php echo base_url($item['image_url']); ?>" class="cbp-caption cbp-lightbox" data-title="<?php echo $item['title']; ?>">
                            <div class="cbp-caption-defaultWrap">
                                <img src="<?php echo base_url(image($item['image_url'],"",400,400)); ?>" alt="<?php echo $item['title']; ?>" title="<?php echo $item['title']; ?>"> </div>
                            <div class="cbp-caption-activeWrap">
                                <div class="cbp-l-caption-alignCenter">
                                    <div class="cbp-l-caption-body">
                                        <div class="cbp-l-caption-title"><?php echo $item['title']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
