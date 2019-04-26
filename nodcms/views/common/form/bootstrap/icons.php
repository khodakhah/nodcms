<div class="input-group input-large">
    <div class="input-icon">
        <i id="btn_icon_<?php echo $field_id; ?>" class="<?echo $default?>"></i>
        <input onkeyup="$('#btn_icon_<?php echo $field_id; ?>').attr('class',$(this).val());" name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" type="text" value="<?php echo $default; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    </div>
    <span class="input-group-btn">
        <button type="button" class="btn default input-icons-list" data-icon-element="#btn_icon_<?php echo $field_id; ?>" data-input="#<?php echo $field_id; ?>" data-modal-title="<?php echo $modal_title; ?>"><i class="fa fa-search"></i></button>
    </span>
</div>