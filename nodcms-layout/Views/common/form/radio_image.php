<div class="md-radio <?php echo $input_class; ?>">
    <?php foreach($options as $item){ ?>
        <div class="md-radio">
            <input type="radio" <?php echo ($item[$option_value]==$default)?'checked=""':''; ?> class="md-radiobtn" name="<?php echo $input_name; ?>" value="<?php echo $item[$option_value]; ?>" <?php echo $item[$option_id]!=''?'id="'.$item[$option_id].'"':''; ?>>
            <label <?php echo $item[$option_id]!=''?'for="'.$item[$option_id].'"':''; ?>>
                <span></span>
                <span class="check"></span>
                <span class="box"></span>
                <img src="<?php echo base_url($item[$option_image]); ?>" alt="<?php echo $item[$option_name]; ?>" title="<?php echo $item[$option_name]; ?>">
            </label>
        </div>
    <?php } ?>
</div>