<div id="<?php echo $field_id; ?>" class="input-group <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
        <div class="mt-radio-inline">
                <?php foreach($options as $item){ ?>
                <label class="mt-radio">
                    <input <?php echo ($item[$option_value]==$default)?'checked=""':''; ?> type="radio" name="<?php echo $name; ?>" class="" value="<?php echo $item[$option_value]; ?>" id="<?php echo $name.'_'.$item[$option_value]; ?>" data-radio="iradio_square-blue"> <?php echo $item[$option_name]; ?>
                    <span></span>
                </label>
                <?php } ?>
        </div>
</div>