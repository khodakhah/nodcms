<?php
    function _l($label, $obj)
    {
        $return = $obj->lang->line($label);
        if($return)
            return $return;
        else
            return $label;
    }