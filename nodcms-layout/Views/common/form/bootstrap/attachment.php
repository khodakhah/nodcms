<div id="<?php echo $field_id; ?>-parent" data-default="#<?php echo $field_id; ?>" class="border-1 attachment-box border-grey" style="height: auto;">
    <div class="row">
        <div class="col-md-9">
            <input type="text" value="<?php echo $default; ?>" name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" class="attachment-value form-control border-0 <?php echo $class; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?>>
            <div class="attachment-display"></div></div>
        <div class="col-md-3 text-right">
            <div class="btn-group">
                <button type="button" class="btn clear-btn hidden red"><?php echo _l("Clear", $this); ?></button>
                <button type="button" data-url="<?php echo $url; ?>" class="btn brows-btn blue-steel"><?php echo _l("Brows", $this); ?></button>
            </div>
        </div>
    </div>
</div>
