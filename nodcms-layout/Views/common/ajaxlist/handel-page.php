<?php $this->addJsFile("assets/nodcms/js/ajaxlist"); ?>
<div id="<?php echo $options['listID']; ?>" class="ajaxlist"></div>
<script>
    $(function () {
        $('#<?php echo $options['listID']; ?>.ajaxlist').ajaxList(<?php echo json_encode($options); ?>);
    });
</script>
