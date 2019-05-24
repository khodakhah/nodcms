<div class="input-group input-large">
    <?php if(isset($before_sign) && $before_sign!=""){ ?>
        <span class="input-group-addon"><?php echo $before_sign; ?></span>
    <?php } ?>
    <input data-divider="<?php echo $divider?>" data-target="#<?php echo $field_id; ?>" id="<?php echo $field_id; ?>-formatted" class="form-control currency-format <?php echo $class; ?>" type="text" value="<?php echo $default; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <?php if(isset($after_sign) && $after_sign!=""){ ?>
        <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $after_sign; ?></div>
        </div>
    <?php } ?>
</div>
<input name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" type="hidden" value="<?php echo $default; ?>">
