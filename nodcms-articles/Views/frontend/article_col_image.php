<div class="row">
    <div class="col-md-3">
            <img src="<?php echo base_url($data["image"]); ?>" alt="image-<?php echo $data["name"]; ?>" class="img-fluid">
    </div>
    <div class="col-md-9">
        <h1 class="margin-top-40"><?php echo $title; ?></h1>
        <div class="font-grey-mint"><?php echo $data["description"]; ?></div>
    </div>
</div>
<div class="margin-top-20"><?php echo $data["content"]; ?></div>
