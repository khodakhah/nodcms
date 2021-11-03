<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div class="bg-grey-cararra padding-top-20 padding-bottom-20">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <?php foreach($data_list as $item){ ?>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="row no-gutters">
                                <div class="col-4"><img alt="<?php echo $item['name']; ?>" title="<?php echo $item['name']; ?>" class="card-img" src="<?php echo base_url($item['profile_image']); ?>"></div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h2 class="card-title"><?php echo $item['name']; ?></h2>
                                        <p class="card-text"><?php echo $item['name_title']; ?></p>
                                        <div class="card-text font-grey-mint"><?php echo $item['preview_description']?></div>
                                        <?php if($item['profile_uri']!=""){ ?>
                                            <a href="<?php echo base_url("{$this->lang}/about-$item[profile_uri]"); ?>">
                                                <?php echo _l("Learn more", $this); ?>
                                            </a>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>