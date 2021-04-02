<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 3:35 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Portfolio_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "portfolio";
        $primary_key = "portfolio_id";
        $fields = array(
            'portfolio_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'portfolio_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'portfolio_image'=>"VARCHAR(255) NULL DEFAULT NULL",
            'portfolio_public'=>"INT(1) UNSIGNED NULL DEFAULT NULL",
            'portfolio_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'created_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title','details');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}