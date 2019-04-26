<?php if($input_prefix!=""){ ?>
    <div class="input-group">
        <span class="input-group-addon"><?php echo $input_prefix; ?></span>
        <input name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" value="<?php echo $default; ?>" type="<?php echo $type; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    </div>
<?php }else{ ?>
    <input name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" value="<?php echo $default; ?>" type="<?php echo $type; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
<?php } ?>
