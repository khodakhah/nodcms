<div class="mt-checkbox-list">
    <?php if(count($sub_items)!=0){ ?>
        <?php foreach ($sub_items as $item){ ?>
            <label class="mt-checkbox mt-checkbox-outline <?php echo $item['class']; ?>">
                <input value="1" type="checkbox" name="<?php echo $item['field']; ?>" <?php foreach ($item['attr'] as $key=>$value){ echo $key.' = "'.$value.'"'; } ?> <?php echo (isset($item['default'])&&$item['default']==1)?'checked':''; ?>>
                <?php echo $item['label']; ?>
                <span></span>
            </label>
        <?php } ?>
    <?php }else{ ?>
        <label class="mt-checkbox mt-checkbox-outline <?php echo $class; ?>">
            <input value="1" type="checkbox" id="<?php echo $field_id; ?>" name="<?php echo $name; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?> <?php echo (isset($default)&&$default==1)?'checked':''; ?>>
            <?php echo $description; ?>
            <span></span>
        </label>
    <?php } ?>
</div>