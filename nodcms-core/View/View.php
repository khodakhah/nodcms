<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2020.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2020 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\View;

use CodeIgniter\Config\Services;
use Psr\Log\LoggerInterface;

class View extends \CodeIgniter\View\View
{
    /**
     * Reset Config default
     *
     * @var \NodCMS\Core\Config\View
     */
    public $config;

    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    public $request;

    // All add-ons css files in header tag
    public $css_files = array();

    // Add add-ons js files in header tag
    public $header_js_files = array();

    // All add-ons js files in footer content (append to body tag)
    public $footer_js_files = array();

    /**
     * @var View
     */
    private $_common_view;

    /**
     * View constructor.
     * @param $config
     * @param string|null $viewPath
     * @param null $loader
     * @param bool|null $debug
     * @param LoggerInterface|null $logger
     */
    public function __construct($config = null, string $viewPath = null, $loader = null, bool $debug = null, LoggerInterface $logger = null)
    {
        $config == null && $config = new \NodCMS\Core\Config\View();
        parent::__construct($config, $viewPath, $loader, $debug, $logger);
        $this->request = Services::request();
    }

    /**
     * The common view uses layout view files
     *
     * @return View
     */
    public function common()
    {
        if(empty($this->_common_view))
            $this->_common_view = new self();
        return $this->_common_view;
    }

    /**
     * Reset the config file
     *
     * @param $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * Add path to the view file
     *
     * @param string $view
     * @param array|null $options
     * @param bool|null $saveData
     * @return string
     */
    public function render(string $view, array $options = null, bool $saveData = null): string
    {
        $view = $this->config->viewPath . "/" . $view;
        return parent::render($view, $options, $saveData);
    }

    /**
     * Render the frame
     *
     * @param array|null $options
     * @param bool|null $saveData
     * @return string
     */
    public function renderFrame(array $options = null, bool $saveData = null): string
    {
        return parent::render($this->config->viewPath . "/" . $this->config->frameFile, $options, $saveData);
    }

    /**
     * Define controller public variables as view variables to use in the view files.
     * For example: $this->language, $this->settings, etc.
     * TODO: Change this routine.
     *
     * @param $controller
     */
    public function loadControllerVars($controller)
    {
        foreach($controller as $key=>$item) {
            $this->$key = $item;
        }
    }

    /**
     * Keep the old flashdata() usage in view files
     *
     * @param string $key
     * @return array|false|null
     */
    public function flashdata(string $key) {
        if(!session()->has($key))
            return false;

        return session()->getFlashdata($key);
    }

    /**
     * Add a css file at your at the end of css pools
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    function addCssFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->css_files, ".css", $path, $ltr_path);
    }

    /**
     * Add a js file at your at the end of js pools
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    function addJsFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->footer_js_files, ".js", $path, $ltr_path);
    }

    /**
     * Add a js file at your in the header tag
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    function addHeaderJsFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->header_js_files, ".js", $path, $ltr_path);
    }

    /**
     * Add all css files to your view files.
     * It will use on your main template frame file.
     */
    function fetchAllCSS()
    {
        echo "<style>".$this->assetContents($this->css_files)."</style>\n";
    }

    /**
     * Add all js files to your view files at the end of body tag.
     * It will use on your main template frame file.
     */
    function fetchAllJS()
    {
        echo "<script type='text/javascript'>".$this->assetContents($this->footer_js_files)."</script>\n";
    }

    /**
     * Add all js files to your view files at the end of head tag.
     * It will use on your main template frame file.
     */
    function fetchAllHeaderJS()
    {
        echo "<script type='text/javascript'>".$this->assetContents($this->header_js_files)."</script>\n";
    }

    /**
     * Add an asset file to an assets array
     *
     * @param $variable
     * @param string $file_type
     * @param string $path
     * @param string|null $ltr_path
     */
    private function addAsset(&$variable, string $file_type, string $path, string $ltr_path = null) {
        if($ltr_path != null) {
            if(!isset($this->language) || $this->language == null)
                return;
            if($this->language["rtl"]){
                if(!in_array($ltr_path . $file_type, $variable))
                    array_push($variable, $ltr_path . $file_type);
                return;
            }
        }

        if(!in_array($path . $file_type, $variable))
            array_push($variable, $path . $file_type);
    }

    /**
     * Convert assets array to string
     *
     * @param array $variable
     * @return string
     */
    private function assetContents(array $variable): string {
        $result = array();
        foreach ($variable as $item){
            $file = ROOTPATH . $item;
            if(!file_exists($file)) continue;
            $result[] = file_get_contents($file);
        }
        return join("\n", $result);
    }
}