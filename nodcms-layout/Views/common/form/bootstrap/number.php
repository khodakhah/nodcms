<div class="input-group national-number-format">
    <input data-min="<?php echo $min; ?>" data-max="<?php echo $max; ?>" name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control input-value <?php echo $class; ?>" value="<?php echo $default; ?>" type="text" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <span class="input-group-btn"><button class="btn default font-red input-minus-btn" type="button"><i class="fas fa-minus"></i></button></span>
    <span class="input-group-btn"><button class="btn default font-blue input-plus-btn" type="button"><i class="fas fa-plus"></i></button></span>
</div>