<div class="row margin-top-10">
    <div class="col-md-6">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail" style="width: 100%;">
                <img src="<?php echo $img_src; ?>" alt="<?php echo $img_src; ?>"/>
            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style="width: 100%;">
            </div>
            <div>
                <span class="btn default btn-file">
                <span class="fileinput-new">
                <?php echo _l("Select image", $this); ?> </span>
                <span class="fileinput-exists">
                <?php echo _l("Change", $this); ?> </span>
                <input type="file" class="<?php echo $class; ?>" name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
                </span>
                <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput">
                    <?php echo _l("Cancel", $this); ?>
                </a>
<!--                <button type="submit" class="btn btn-primary fileinput-exists">-->
<!--                    --><?php //echo _l("Save changes", $this)?>
<!--                </button>-->
                <?php if(isset($default)&&$default!=""){ ?>
                    <a href="<?php echo $remove_url; ?>" class="btn btn-danger fileinput-new btn-ask">
                        <?php echo _l("Remove", $this)?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>