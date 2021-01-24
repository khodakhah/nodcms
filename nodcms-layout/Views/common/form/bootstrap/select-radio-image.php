<div class="input-group <?php echo $class; ?>">
    <?php foreach($options as $item){ ?>
        <div class="icheck-list">
            <label>
                <input type="radio" <?php echo ($item[$option_value]==$default)?'checked=""':''; ?> class="" name="<?php echo $name; ?>" value="<?php echo $item[$option_value]; ?>">
                <?php echo $item[$option_name]; ?>
                <img src="<?php echo base_url($item[$option_image]); ?>" alt="<?php echo $item[$option_name]; ?>" title="<?php echo $item[$option_name]; ?>">
            </label>
        </div>
    <?php } ?>
</div>