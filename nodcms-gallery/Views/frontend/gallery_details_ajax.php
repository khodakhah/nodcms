<div class="cbp-l-inline">
    <div class="cbp-l-inline-left">
        <img src="<?php echo base_url($data['gallery_image']); ?>" alt="<?php echo $data['title']; ?>" title="<?php echo $data['title']; ?>" class="img-responsive">
    </div>
    <div class="cbp-l-inline-right">
        <div class="cbp-l-inline-title"><?php echo $data['title']; ?></div>
        <div class="cbp-l-inline-desc"><?php echo $data['details']; ?></div>
        <a href="<?php echo base_url($this->language['code']."/album-".$data['gallery_id']); ?>" target="_blank" class="cbp-l-inline-view btn blue btn-outline"><?php echo _l("View Album", $this); ?></a>
    </div>
</div>
