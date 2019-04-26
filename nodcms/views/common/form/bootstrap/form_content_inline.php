<?php echo $form_content; ?>
<?php if(!$modal_format){ ?>
    <button class="btn btn-primary btn-submit <?php echo $submit_class; ?>" <?php foreach($submit_attr as $key=>$item){ echo " $key=\"$item\"";};?> type="submit"><?php echo _l("Submit", $this); ?></button>
<?php } ?>