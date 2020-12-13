<select multiple name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control input-medium <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <?php foreach ($options as $item){ ?>
        <option value="<?php echo $item[$option_value]; ?>"><?php echo $item[$option_name]; ?></option>
    <?php } ?>
</select>
<script>
        $(function(){ $("#<?php echo $field_id; ?>").val('<?php echo $default; ?>'); });
</script>