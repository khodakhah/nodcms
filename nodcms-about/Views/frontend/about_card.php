<div class="bg-grey-cararra padding-top-20 padding-bottom-20">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img alt="<?php echo $data['name']; ?>" title="<?php echo $data['name']; ?>" class="img-fluid" src="<?php echo base_url($data['profile_image']); ?>">
            </div>
            <div class="col-md-8">
                <h1 class="margin-top-40"><?php echo $data['name']; ?></h1>
                <p><?php echo $data['name_title']; ?></p>
                <div class="font-lg font-grey-mint"><?php echo $data['preview_description']?></div>
            </div>
        </div>
    </div>
</div>
<div class="container margin-top-20 margin-bottom-20">
    <?php echo $data['content']; ?>
</div>