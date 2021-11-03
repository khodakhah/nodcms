<div class="bg-grey-steel">
    <div class="row no-gutters">
        <div class="col-md-6 order-md-2">
            <img alt="<?php echo $data['name']; ?>" title="<?php echo $data['name']; ?>" class="img-fluid" src="<?php echo base_url($data['profile_image']); ?>">
        </div>
        <div class="col-md-6 order-md-1">
            <div class="padding-40">
                <h2><?php echo $data['name']; ?></h2>
                <p><?php echo $data['name_title']; ?></p>
                <div class="font-lg font-grey-mint"><?php echo $data['description']?></div>
            </div>
        </div>
    </div>
</div>
<div class="container margin-top-20 margin-bottom-20">
    <?php echo $data['content']; ?>
</div>