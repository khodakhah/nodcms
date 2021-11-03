<div class="input-group input-small rangedatepicker input-datepicker" data-id="<?php echo $field_id; ?>" <?php foreach ($attr as $key=>$value){ echo $key.' = "'.$value.'"'; } ?> <?php foreach ($datepicker as $key=>$value){ echo "data-".strtolower(preg_replace('/([A-Z]+)/', "-$1", $key))." = \"$value\""; } ?>>
    <input type="text" id="<?php echo $field_id; ?>_from" value="<?php echo isset($default['from'])?$default['from']:""; ?>" class="form-control rangedatepicker-inputs input-small"/>
    <div class="input-group-addon"><i class="fa fa-angle-right"></i></div>
    <input type="text" id="<?php echo $field_id; ?>_to" value="<?php echo isset($default['to'])?$default['to']:""; ?>" class="form-control rangedatepicker-inputs input-small"/>
</div>
<div class="hidden" id="calendarfilter_<?php echo $field_id; ?>"><?php echo $calendarfilter; ?></div>
<input name="<?php echo $name; ?>" id="<?php echo $field_id; ?>" value="<?php echo $default_microtime; ?>" type="hidden"/>
