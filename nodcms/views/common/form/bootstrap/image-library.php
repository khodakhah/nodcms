<div>
    <img id="<?php echo $field_id; ?>_image" data-preview="<?php echo $not_set_preview; ?>" src="<?php echo $img_src; ?>" alt="<?php echo _l("Image Preview", $this); ?>" class="input-preview img-responsive" style="max-height: 300px;">
</div>
<div class="row">
    <div class="col-9">
        <input role="image-library" data-target="<?php echo $field_id; ?>_image" name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" type="text" value="<?php echo $default; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    </div>
    <div class="col-3">
        <button onclick="$.loadInModal('<?php echo $image_library_url; ?>', 'modal-lg');" type="button" class="btn default"><?php echo _l("Brows", $this); ?></button>
    </div>
</div>
