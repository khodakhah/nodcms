<div class="card bg-dark font-white">
    <img alt="<?php echo $data['name']; ?>" title="<?php echo $data['name']; ?>" class="card-img" src="<?php echo base_url($data['profile_image']); ?>">
    <div class="card-img-overlay text-center bg-dark-fade">
        <h2 class="card-title"><?php echo $data['name']; ?></h2>
        <p class="card-text"><?php echo $data['name_title']; ?></p>
        <div class="font-lg card-text"><?php echo $data['description']?></div>
    </div>
</div>
<div class="container margin-top-20 margin-bottom-20">
    <?php echo $data['content']; ?>
</div>