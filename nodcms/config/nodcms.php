<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/15/2015
 * Time: 8:35 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
$config['NodCMS_general_templateFolderName'] = 'nodcms_general';
$config['NodCMS_general_admin_templateFolderName'] = 'nodcms_general_admin';
$config['max_upload_size'] = 20000; // KG
$config['backend_models'] = array('NodCMS_general_admin_model');
$config['backend_helpers'] = array('admin_page_type','nodcms_form');
$config['frontend_models'] = array('NodCMS_general_model');
$config['frontend_helpers'] = array();
