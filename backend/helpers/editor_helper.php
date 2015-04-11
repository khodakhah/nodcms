<?php
/**
 * Load TinyMCE editor
 */
function load_editor($elements,$type=null){
  
  $controller = &get_instance();
  $view_data['elements'] = $elements;
  $view_data['type'] = $type;
  return $controller->load->view('editor', $view_data, true);
}