<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-May-19
 * Time: 3:14 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Blog_category_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "blog_categories";
        $primary_key = "category_id";
        $fields = array(
            'category_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'category_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'category_image'=>"VARCHAR(255) NULL DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    function idExists($values)
    {
        $this->db->select('category_id');
        $this->db->from("blog_categories");
        $this->db->where("category_id IN ($values)");
        $query = $this->db->get();
        $result = $query->result_array();
        return $result!=null?array_column($result, "category_id"):array();
    }
}