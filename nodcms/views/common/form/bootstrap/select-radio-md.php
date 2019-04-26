<div class="md-radio-inline <?php echo $class; ?>" id="<?php echo $field_id; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <?php foreach($options as $item){ ?>
        <div class="md-radio">
            <input <?php echo ($item[$option_value]==$default)?'checked=""':''; ?> type="radio" name="<?php echo $name; ?>" value="<?php echo $item[$option_value]; ?>" id="<?php echo $name.'_'.$item[$option_value]; ?>" class="md-radiobtn">
            <label for="<?php echo $name.'_'.$item[$option_value]; ?>">
                <span></span>
                <span class="check"></span>
                <span class="box"></span>
                <?php echo $item[$option_name]; ?>
            </label>
        </div>
    <?php } ?>
</div>