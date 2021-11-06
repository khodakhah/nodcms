<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

/**
 * @param $source_dir
 * @param int $directory_depth
 * @param bool $hidden
 * @return array|bool
 */
function get_all_php_files($source_dir, $directory_depth = 0, $hidden = FALSE) {
    if ($fp = @opendir($source_dir))
    {
        $filedata	= array();
        $new_depth	= $directory_depth - 1;
        $source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        while (FALSE !== ($file = readdir($fp)))
        {
            // Remove '.', '..', and hidden files [optional]
            if ($file === '.' OR $file === '..' OR ($hidden === FALSE && $file[0] === '.'))
            {
                continue;
            }

            is_dir($source_dir.$file) && $file .= DIRECTORY_SEPARATOR;

            if (($directory_depth < 1 OR $new_depth > 0) && is_dir($source_dir.$file))
            {
                $filedata = array_merge($filedata, get_all_php_files($source_dir.$file, $new_depth, $hidden));
            }
            else
            {
                $check_match = preg_match('/^([A-Za-z\_\-]+\.php)$/', $file);
                if($check_match==false || $check_match==0){
                    continue;
                }
                $filedata[] = $source_dir.$file;
            }
        }

        closedir($fp);
        return $filedata;
    }

    return FALSE;
}

/**
 * @return array|bool
 */
function get_all_resources_dir() {
    $source_dir = APPPATH."third_party/";
    if ($fp = @opendir($source_dir))
    {
        $filedata	= array();
        $source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        while (FALSE !== ($file = readdir($fp)))
        {
            // Remove '.', '..', and hidden files [optional]
            if ($file === '.' OR $file === '..')
            {
                continue;
            }

            is_dir($source_dir.$file) && $filedata[] = $file;
        }

        closedir($fp);
        return $filedata;
    }

    return FALSE;
}

function autoload_resources($class_name) {
    $paths = array();
    $_paths = get_all_php_files(APPPATH.RESOURCES_DIR_NAME);

    if($_paths != false)
        $paths = array_merge($paths, $_paths);

    $third_parties = get_all_resources_dir();

    if(is_array($third_parties)){
        foreach($third_parties as $item) {
            $_paths = get_all_php_files(APPPATH."third_party/$item".DIRECTORY_SEPARATOR.RESOURCES_DIR_NAME.DIRECTORY_SEPARATOR);
            if(!is_array($_paths) || count($_paths) == 0) {
                continue;
            }
            $paths = array_merge($paths, $_paths);
        }
    }

    if(count($paths) == 0)
        return;

    foreach($paths as $path) {
        if(strpos($path, $class_name.".php") === false)
            continue;
        require_once $path;
    }
}

spl_autoload_register('autoload_resources');
