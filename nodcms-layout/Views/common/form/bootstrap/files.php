<div data-max-files="<?php echo $max_files; ?>" data-max-file-size="<?php echo $max_file_size; ?>" data-accept-files="<?php echo $accept_types; ?>" data-list-name="<?php echo $name; ?>" data-remove-url="<?php echo base_url(\Config\Services::language()->getLocale()."/remove-my-file/"); ?>/" action="<?php echo $upload_url; ?>" class="dropzone dropzone-file-area" id="<?php echo $field_id; ?>">
    <h3 class="sbold"><?php echo _l("Upload files", $this); ?></h3>
    <p><?php echo _l("You can drop your files here, or click to browse your files.", $this); ?></p>
    <?php if(isset($default) && is_array($default) && count($default)!=0){ ?>
        <textarea class="d-none dropzone-default"><?php echo json_encode($default); ?></textarea>
    <?php } ?>
</div>