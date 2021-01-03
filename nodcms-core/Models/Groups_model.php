<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Groups_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "groups";
        $primary_key = "group_id";
        $fields = array(
            'group_id'=>"int(10) NOT NULL AUTO_INCREMENT",
            'group_name'=>"varchar(50) DEFAULT NULL",
            'backend_login'=>"int(1) DEFAULT '0'",
        );
        $foreign_tables = array("gallery_image");
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Insert first data
     */
    function defaultData()
    {
        $data = array(
            array('group_id'=>1,'group_name'=>"Admin", 'backend_login'=>1),
            array('group_id'=>20,'group_name'=>"Users", 'backend_login'=>0),
        );
        foreach($data as $item) {
            $this->add($item);
        }
    }
}