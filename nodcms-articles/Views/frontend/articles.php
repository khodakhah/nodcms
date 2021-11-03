<div class="row articles">
    <?php if(isset($single_image) && count($single_image)!=0){ ?>
        <div class="col-md-2">
            <?php foreach($single_image as $item){ ?>
                    <div class="portlet solid grey">
                        <div class="portlet-body">
                                <a title="<?php echo $item["name"]; ?>" href="<?php echo base_url($lang."/pa-".$item["article_id"]); ?>">
                                    <img alt="image-<?php echo $item["name"]; ?>" title="<?php echo $item["name"]; ?>" src="<?php echo base_url($item["image"]); ?>" class="img-responsive">
                                </a>
                                <a title="<?php echo $item["name"]; ?>" href="<?php echo base_url($lang."/pa-".$item["article_id"]); ?>">
                                    <h2><?php echo $item["name"]; ?></h2>
                                </a>
                        </div>
                    </div>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if(isset($single_text) && count($single_text)!=0){ ?>
        <div class="col-md-2">
            <?php foreach($single_text as $item){ ?>
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <h2>
                                <a title="<?php echo $item["name"]; ?>" href="<?php echo base_url($lang."/pa-".$item["article_id"]); ?>">
                                    <?php echo $item["name"]; ?>
                                </a>
                            </h2>
                        </div>
                    </div>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if(isset($group_image) && count($group_image)!=0){ ?>
        <div class="col-md-4">
            <?php foreach($group_image as $item){ ?>
                <img alt="image-<?php echo $item["name"]; ?>" title="<?php echo $item["name"]; ?>" src="<?php echo base_url($item["image"]); ?>" class="img-responsive">
                <div class="portlet solid grey-cararra">
                    <div class="portlet-body">
                        <h2>
                            <i class="icon-layers font-green"></i>
                            <?php echo $item["name"]; ?>
                        </h2>
                        <?php foreach($item["sub_articles"] as $sub_item){ ?>
                            <a title="<?php echo $sub_item["name"]; ?>" href="<?php echo base_url($lang."/pa-".$sub_item["article_id"]); ?>">
                                <h3><?php echo $sub_item["name"]; ?></h3>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <?php if(isset($group_text) && count($group_text)!=0){ ?>
        <div class="col-md-4">
            <?php foreach($group_text as $item){ ?>
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <h2 class="caption">
                            <?php echo $item["name"]; ?>
                        </h2>
                    </div>
                    <div class="portlet-body">
                        <?php foreach($item["sub_articles"] as $sub_item){ ?>
                            <a title="<?php echo $sub_item["name"]; ?>" href="<?php echo base_url($lang."/pa-".$sub_item["article_id"]); ?>">
                                <h3><?php echo $sub_item["name"]; ?></h3>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
