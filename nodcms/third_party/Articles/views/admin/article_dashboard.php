<div class="row">
    <div class="col-md-3 col-sm-4 col-xs">
        <a href="<?php echo ARTICLES_ADMIN_URL."articleForm"; ?>" class="btn grey-mint btn-block btn-outline">
            <i class="fas fa-plus"></i>
            <div> <?php echo _l("Add Article", $this); ?> </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-4 col-xs">
        <a href="<?php echo ARTICLES_ADMIN_URL."article"; ?>" class="btn grey-mint btn-block btn-outline">
            <i class="fas fa-list"></i>
            <div>
                <?php echo _l("Articles", $this); ?>
                <?php if($data_count>0){ ?><span class="badge badge-primary"> <?php echo $data_count; ?> </span><?php } ?>
            </div>
        </a>
    </div>
</div>