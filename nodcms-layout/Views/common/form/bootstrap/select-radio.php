<div id="<?php echo $field_id; ?>" class="<?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <div class="">
        <?php foreach($options as $item){ ?>
            <div class="form-check">
                <input <?php echo ($item[$option_value]==$default)?'checked=""':''; ?> type="radio" name="<?php echo $name; ?>" class="form-check-input bd-highlight" value="<?php echo $item[$option_value]; ?>" id="<?php echo $name.'_'.$item[$option_value]; ?>" <?php if(isset($item['attr'])) foreach ($item['attr'] as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
                <label class="form-check-label" for="<?php echo $name.'_'.$item[$option_value]; ?>">
                    <?php if(is_array($option_name)){ ?>
                        <?php foreach($option_name as $opt_name) echo "$item[$opt_name]<br>"; ?>
                    <?php }else{ ?>
                        <?php echo $item[$option_name]; ?>
                    <?php } ?>
                </label>
            </div>
        <?php } ?>
    </div>
</div>