<?php if(count($html_keys)!=0){ ?>
    <p>
        <?php foreach($html_keys as $item){ ?>
            <button type="button" class="btn grey-mint btn-outline btn-sm" onclick="$(this).insertHTMLAtTexteditor('<?php echo $field_id; ?>');"><textarea class="hidden"><?php echo $item['value']; ?></textarea>
                <i class="fa fa-code"></i>
                <?php echo $item['label']; ?></button>
        <?php } ?>
    </p>
<?php } ?>

<?php if(count($shortkeys)!=0){ ?>
    <p>
        <?php foreach($shortkeys as $item){ ?>
            <button type="button" class="btn btn-default btn-sm" onclick="insertAtTexteditor('<?php echo $field_id; ?>', '<?php echo $item['value']; ?>');"><?php echo $item['label']; ?></button>
        <?php } ?>
    </p>
<?php } ?>
<textarea name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" data-loading="#<?php echo $field_id; ?>-loading" class="form-control ckeditor-quick <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>><?php echo $default; ?></textarea>
<div id="<?php echo $field_id; ?>-loading"><i class="fa fa-spinner fa-pulse"></i></div>