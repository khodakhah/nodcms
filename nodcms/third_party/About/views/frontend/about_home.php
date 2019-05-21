<?php if(isset($data_list) && count($data_list)!=0){ ?>
    <div id="about-me" class="bg-grey-cararra padding-top-20 padding-bottom-20 margin-top-40">
        <div class="container">
            <?php foreach($data_list as $item){ ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="margin-40">
                            <img alt="<?php echo $item['name']; ?>" title="<?php echo $item['name']; ?>" class="img-fluid rounded-circle" src="<?php echo base_url().$item['profile_image']; ?>">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h2 class="margin-top-40"><?php echo $item['name']; ?></h2>
                        <p><?php echo $item['name_title']; ?></p>
                        <div class="font-lg font-grey-mint"><?php echo $item['preview_description']?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>