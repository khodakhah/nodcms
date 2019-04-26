<div class="survey-points-radio radio-list">
    <?php for($i=1;$i<=$count;$i++){ ?>
        <label class="radio-inline"><div class="radio"><span><input class="hidden" type="radio" id="<?php echo "$field_id-$i"; ?>" name="<?php echo $name; ?>" value="<?php echo $i; ?>"></span></div> <i class="<?php echo $item_icon_off; ?>" data-icon-off="<?php echo $item_icon_off; ?>" data-icon-on="<?php echo $item_icon_on; ?>"></i></label>
    <?php } ?>
    <input type="hidden">
</div>