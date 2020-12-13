<div class="form-body"><?php echo $form_content; ?></div>
<?php if(!$modal_format){ ?>
    <div class="form-actions">
        <div class="row">
            <div class="<?php echo !isset($columns)?"col-md-9 col-md-offset-3 offset-md-3":"col-xs-12 text-center"; ?>">
                <?php if(isset($this->back_url) && $this->back_url != ""){ ?>
                    <a href="<?php echo $this->back_url; ?>" class="btn default"><?php echo _l("Back", $this); ?></a>
                <?php } ?>
                <button class="btn btn-primary btn-submit <?php echo $submit_class; ?>" <?php foreach($submit_attr as $key=>$item){ echo " $key=\"$item\"";};?> type="submit"><?php echo isset($submit_label)?$submit_label:_l("Submit", $this); ?></button>
            </div>
        </div>
    </div>
<?php } ?>