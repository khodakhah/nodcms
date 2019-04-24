<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class NodCMS_Loader extends CI_Loader {
    // Package list
    public $packages;
    /*
     * Load hooks classes form package folder
     */
    function __construct()
    {
        parent::__construct();
        // Set package patch
        $package_dir = APPPATH."third_party";
        // Check exists package patch
        if(!is_dir($package_dir)){
            return;
        }
        // Include Hooks parent file
        require_once $package_dir."/NodcmsHooks.php";
        // Scan all directories in package folder
        $dir = scandir($package_dir);
        // Remove ".."
        unset($dir[0]);
        // Remove "."
        unset($dir[1]);
        foreach ($dir as $item) {
            if(preg_match('~([^/]+)\.[A-Za-z0-9]+~', $item)){
                continue;
            }
            // Make a package item
            $this->packages[$item] = array();
            // Load a package hooks
            if(file_exists($package_dir."/".$item."/".$item."Hooks.php")){
                require_once $package_dir."/".$item."/".$item."Hooks.php";
                $class = $item."Hooks";
                $this->packages[$item]['hooks'] = new $class();
            }
            // Load a package shortcut
            if(file_exists($package_dir."/".$item."/".$item."Shortcut.php")){
                require_once $package_dir."/".$item."/".$item."Shortcut.php";
                $class = $item."Shortcut";
                $this->packages[$item]['shortcut'] = new $class();
            }
            $this->packages[$item]['dir'] = $package_dir."/".$item."/";
            // Add config path before autoload
            $config =& $this->_ci_get_component('config');
            $config->_config_paths[] = $this->packages[$item]['dir'];
        }
    }

    /*
     * Run a method from all packages hooks classes
     */
    function packageHooks()
    {
        if(!is_array($this->packages))
            return;

        $i = func_num_args();
        if($i < 1 )
            return;

        $params = func_get_args();
        $method = $params[0];
        unset($params[0]);

        foreach ($this->packages as $item){
            if(!isset($item['hooks']) || !method_exists($item['hooks'], $method))
                continue;
//            if($method == "setAppointment")
//            echo array($item['hooks'], $method).' - ['.implode(",",$params).']';
//            else
            // Call the method with an array of parameters
            call_user_func_array(array($item['hooks'], $method), $params);
        }
//        if($method=="setAppointment")
//        exit;
    }


    /*
     * Run a method from a shortcut class in a package
     */
    function packageShortcut($package_name, $method, $params = NULL, $show_error = FALSE)
    {
        // Check package exists
        if(!isset($this->packages[$package_name]['hooks']))
            if($show_error !== TRUE) return; else show_error("Package $package_name isn't found!");
        // Check method exists
        if(!method_exists($this->packages[$package_name]['hooks'], $method))
            if($show_error !== TRUE) return; else show_error("The method $method isn't exists in $package_name!");
        // Add package path
        $this->add_package_path($package_name);
        // Run method
        if($params!=NULL)
            $this->packages[$package_name]['hooks']->$method($params);
        else
            $this->packages[$package_name]['hooks']->$method();
        // Remove package path
        $this->remove_package_path($package_name);
    }

    /**
     * Run a method from a hook class in a package
     *
     * @param $package_name
     * @param $method
     * @param null $params
     * @param bool $show_error
     */
    function packageLoad($package_name, $method, $params = NULL, $show_error = FALSE)
    {
        // Check package exists
        if(!isset($this->packages[$package_name]['hooks']))
            if($show_error !== TRUE) return; else show_error("Package $package_name isn't found!");
        // Check method exists
        if(!method_exists($this->packages[$package_name]['hooks'], $method))
            if($show_error !== TRUE) return; else show_error("The method $method isn't exists in $package_name!");
        // Add package path
        $this->add_package_path($package_name);
        // Run method
        if($params!=NULL)
            $this->packages[$package_name]['hooks']->$method($params);
        else
            $this->packages[$package_name]['hooks']->$method();
        // Remove package path
        $this->remove_package_path($package_name);
    }

    /**
     * Run a method from a hook class in a package
     *
     * @param $package_name
     * @param $method
     * @param null $params
     * @param bool $show_error
     * @return array
     */
    function packageReturn($package_name, $method, $params = NULL, $show_error = FALSE)
    {
        // Check package exists
        if(!isset($this->packages[$package_name]['hooks']))
            if($show_error == TRUE) return array('status'=>"error", 'error'=>"Package '$package_name' isn't found!");
        // Check method exists
        if(!method_exists($this->packages[$package_name]['hooks'], $method))
            if($show_error == TRUE) return array('status'=>"error", 'error'=>"The method $method isn't exists in $package_name!");
        // Add package path
        $this->add_package_path($package_name);
        // Run method
        if($params!=NULL)
            $result = $this->packages[$package_name]['hooks']->$method($params);
        else
            $result = $this->packages[$package_name]['hooks']->$method();
        // Remove package path
        $this->remove_package_path($package_name);

        return $result;
    }

    /**
     * Check a package existence
     *
     * @param $package_name
     * @return bool
     */
    function packageExists($package_name)
    {
        if(!is_array($this->packages))
            return FALSE;

        if(!isset($this->packages[$package_name]))
            return FALSE;

        return TRUE;
    }

    /**
     * Return directory of a package
     *
     * @param $package_name
     * @return mixed
     */
    function packageDir($package_name)
    {
        if( ! isset($this->packages[$package_name]))
            show_error("The package '$package_name' isn't exists.");
        return $this->packages[$package_name]['dir'];
    }

    /**
     * Return a list of packages names
     *
     * @return array
     */
    function packageList()
    {
        if( ! is_array($this->packages))
            return array();

        return array_keys($this->packages);
    }

    /**
     * Add a css file at your at the end of css pools
     *
     */
    function addCssFile()
    {
        $i = func_num_args();
        $params = func_get_args();
        if($i < 1 )
            return;
        elseif($i==1){
            if(!in_array(base_url().$params[0],$this->css_files))
                array_push($this->css_files, base_url().$params[0]);
        }
        elseif($i==2){
            if(!isset($this->language) || $this->language == null)
                return;

            if($this->language["rtl"]){
                if(!in_array(base_url().$params[1],$this->css_files))
                    array_push($this->css_files, base_url().$params[1]);
            }
            else
                if(!in_array(base_url().$params[0],$this->css_files))
                    array_push($this->css_files, base_url().$params[0]);
        }
    }

    /**
     * Add a js file at your at the end of js pools
     *
     * @param $uri
     */
    function addJsFile()
    {
        $i = func_num_args();
        $params = func_get_args();
        if($i < 1 )
            return;
        elseif($i==1){
            if(!in_array(base_url().$params[0],$this->js_files))
                array_push($this->js_files, base_url().$params[0]);
        }
        elseif($i==2){
            if(!isset($this->language) || $this->language == null)
                return;

            if($this->language["rtl"]){
                if(!in_array(base_url().$params[1],$this->js_files))
                    array_push($this->js_files, base_url().$params[1]);
            }
            else
                if(!in_array(base_url().$params[0],$this->js_files))
                    array_push($this->js_files, base_url().$params[0]);
        }
    }

    /**
     * Add all css files to your view files.
     * It will use on your main template frame file.
     */
    function fetchAllCSS()
    {
        foreach ($this->css_files as $item){
            echo '<link href="'.$item.'.css" rel="stylesheet" type="text/css"/>';
        }
    }

    /**
     * Add all js files to your view files.
     * It will use on your main template frame file.
     */
    function fetchAllJS()
    {
        foreach ($this->js_files as $item){
            echo '<script src="'.$item.'.js" type="text/javascript"></script>';
        }
    }
}