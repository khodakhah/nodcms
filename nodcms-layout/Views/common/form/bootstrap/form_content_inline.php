<?php echo $form_content; ?>
<?php if(!$modal_format){ ?>
    <?php if(isset($this->back_url) && $this->back_url != ""){ ?>
        <a href="<?php echo $this->back_url; ?>" class="btn default"><?php echo _l("Back", $this); ?></a>
    <?php } ?>
    <button class="btn btn-primary mb-2 btn-submit <?php echo $submit_class; ?>" <?php foreach($submit_attr as $key=>$item){ echo " $key=\"$item\"";};?> type="submit"><?php echo _l("Submit", $this); ?></button>
<?php } ?>