<?php
/**
 * Load TinyMCE editor
 */
function check_is_gallery($key,$main_array){
    return (isset($main_array[$key]["fields"]["gallery"]))?1:0;
}
function check_extension_order_preview($key,$main_array){
    return (isset($main_array[$key]["fields"]["extension"]) && in_array("order",$main_array[$key]["fields"]["extension"]))?1:0;
}
function get_extension_limit_preview($key,$main_array){
    return (isset($main_array[$key]["preview"]["limit"]))?$main_array[$key]["preview"]["limit"]:0;
}
function allowed_theme_extension($key,$main_array){
    return isset($main_array[$key]["theme_extension"])?1:0;
}
function get_theme_extension($key,$main_array){
    return $main_array[$key]["theme_extension"];
}
function allowed_theme_page($key,$main_array){
    return isset($main_array[$key]["theme"])?1:0;
}
function get_theme_page($key,$main_array){
    return $main_array[$key]["theme"];
}
function allowed_theme_page_ajax($key,$main_array){
    return isset($main_array[$key]["theme_ajax"])?1:0;
}
function get_theme_page_ajax($key,$main_array){
    return $main_array[$key]["theme_ajax"];
}
function get_in_extension_related_limit($key,$main_array){
    return (isset($main_array[$key]["in_extension"]["related_limit"]) && is_numeric($main_array[$key]["in_extension"]["related_limit"]) && $main_array[$key]["in_extension"]["related_limit"]>0)?$main_array[$key]["in_extension"]["related_limit"]:null;
}