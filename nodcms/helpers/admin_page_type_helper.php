<?php
/**
 * Load TinyMCE editor
 */
function get_page_dynamic($key,$main_array){
    return (isset($main_array[$key]["dynamic"]) && $main_array[$key]["dynamic"]==1)?1:0;
}
function allowed_page_fields($key,$field_needle,$main_array){
    return (isset($main_array[$key]["fields"]) && array_key_exists($field_needle,$main_array[$key]["fields"]))?1:0;
}
function allowed_extension_fields($field_needle,$main_array){
    return (isset($main_array["fields"]) && array_key_exists("extension",$main_array["fields"]) && in_array($field_needle,$main_array["fields"]["extension"]))?1:0;
}
function allowed_extension_more($main_array){
    return (isset($main_array['fields']["extension"]["more"]) && is_array($main_array['fields']["extension"]["more"]))?1:0;
}
function get_extension_more($main_array){
    return $main_array['fields']["extension"]["more"];
}
function allowed_page_field($field_needle,$main_array){
    return (isset($main_array["fields"]["page"]) && in_array($field_needle,$main_array["fields"]["page"]))?1:0;
}
function allowed_page_more($main_array){
    return (isset($main_array['fields']["page"]["more"]) && is_array($main_array['fields']["page"]["more"]))?1:0;
}
function get_page_more($main_array){
    return $main_array['fields']["page"]["more"];
}