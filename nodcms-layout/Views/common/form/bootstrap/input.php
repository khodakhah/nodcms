<?php if($input_prefix!=""){ ?>
    <div class="input-group mb-2">
        <div class="input-group-prepend"><div class="input-group-text"><?php echo $input_prefix; ?></div></div>
        <input name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" value="<?php echo $default; ?>" type="<?php echo $type; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    </div>
<?php }else{ ?>
    <input name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" value="<?php echo $default; ?>" type="<?php echo $type; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
<?php } ?>
