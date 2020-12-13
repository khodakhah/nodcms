<?php if(count($shortkeys)!=0){ ?>
    <p>
        <?php foreach($shortkeys as $item){ ?>
            <button type="button" class="btn btn-default btn-sm" onclick="insertAtCaret('<?php echo $field_id; ?>','<?php echo $item['value']; ?>');"><?php echo $item['label']; ?></button>
        <?php } ?>
    </p>
<?php } ?>
<textarea name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>><?php echo $default; ?></textarea>