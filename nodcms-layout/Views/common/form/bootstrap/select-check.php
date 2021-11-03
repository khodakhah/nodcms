<div id="<?php echo $field_id; ?>_group" class="input-group <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <div class="mt-checkbox-inline">
        <?php foreach($options as $item){ ?>
            <label class="mt-checkbox">
                <input data-group="#<?php echo $field_id; ?>" <?php echo (is_array($default)&&in_array($item[$option_value],$default))?'checked=""':''; ?> type="checkbox" class="checkbox-multiselect" value="<?php echo $item[$option_value]; ?>" id="<?php echo $field_id.'_'.$item[$option_value]; ?>"> <?php echo $item[$option_name]; ?>
                <span></span>
            </label>
        <?php } ?>
    </div>
    <input type="hidden" name="<?php echo $name; ?>" class="" value="<?php echo is_array($default)?join(',',$default):''; ?>" id="<?php echo $field_id; ?>">
</div>