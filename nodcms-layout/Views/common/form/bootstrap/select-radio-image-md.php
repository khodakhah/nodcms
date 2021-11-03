<div class="md-radio-list <?php echo $class; ?>" id="<?php echo $field_id; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
    <?php foreach($options as $item){ ?>
        <div class="md-radio">
            <input type="radio" id="<?php echo $field_id.'_'.$item[$option_value]; ?>" <?php echo ($item[$option_value]==$default)?'checked=""':''; ?> class="md-radiobtn" name="<?php echo $name; ?>" value="<?php echo $item[$option_value]; ?>">
            <label for="<?php echo $field_id.'_'.$item[$option_value]; ?>">
                <span></span>
                <span class="check"></span>
                <span class="box"></span>
                <?php echo $item[$option_name]; ?>
                <img src="<?php echo base_url($item[$option_image]); ?>" alt="<?php echo $item[$option_name]; ?>" title="<?php echo $item[$option_name]; ?>">
            </label>
        </div>
    <?php } ?>
</div>