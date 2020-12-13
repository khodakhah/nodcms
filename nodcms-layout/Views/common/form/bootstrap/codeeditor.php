<textarea name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="form-control <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>><?php echo $default; ?></textarea>
<script>
    $(window).load(function () {
        var myTextArea = document.getElementById('<?php echo $field_id; ?>');
        var myCodeMirror = CodeMirror.fromTextArea(myTextArea, {
            lineNumbers: true,
            mode: 'htmlmixed'
        });
    });
</script>