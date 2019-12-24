<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 16-Feb-19
 * Time: 03:53 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Languages_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "languages";
        $primary_key = "language_id";
        $fields = array(
            'language_id'=>"int(11) NOT NULL AUTO_INCREMENT",
            'language_name'=>"varchar(255) DEFAULT NULL",
            'language_title'=>"varchar(255) DEFAULT NULL",
            'code'=>"varchar(255) DEFAULT NULL",
            'public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'rtl'=>"int(1) unsigned DEFAULT '0'",
            'sort_order'=>"int(11) DEFAULT NULL",
            'created_date'=>"int(11) DEFAULT NULL",
            'default'=>"int(11) DEFAULT '0'",
            'image'=>"varchar(255) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $defaults = array(
            array(1, 'english', 'English', 'en', 1, 0, 1, 1369730191, 1, 'upload_file/lang/united_states_flag.png'),
            array(2, 'german', 'Deutsch', 'de', 1, 0, 2, 1518750675, 0, 'upload_file/lang/austria.png'),
        );
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Insert first data
     */
    function defaultData()
    {
        $data = array(
            array('language_id'=>1, 'language_name'=>'english', 'language_title'=>'English', 'code'=>'en', 'public'=>1, 'rtl'=>0, 'sort_order'=>1, 'created_date'=>time(), 'default'=>1, 'image'=>'upload_file/lang/en.png'),
        );
        foreach($data as $item) {
            $this->add($item);
        }
    }
}