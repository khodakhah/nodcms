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

namespace NodCMS\Core\View;

use CodeIgniter\Config\Services;
use Psr\Log\LoggerInterface;

class View extends \CodeIgniter\View\View
{
    /**
     * @var string
     */
    protected $viewPrefix;

    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    public $request;

    /**
     * All add-ons css files in header tag
     *
     * @var array
     */
    public $css_files = array();

    /**
     * Add add-ons js files in header tag
     *
     * @var array
     */
    public $header_js_files = array();

    /**
     * All add-ons js files in footer content (append to body tag)
     *
     * @var array
     */
    public $footer_js_files = array();

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
        $config == null && $config = new \Config\View();
        parent::__construct($config, $viewPath, $loader, $debug, $logger);
        $this->request = Services::request();
        $this->viewPrefix = $this->config->namespacePieces;
    }

    /**
     * Define controller public variables as view variables to use in the view files.
     * TODO: Change this routine.
     *
     * @param $controller
     */
    public function loadControllerVars($controller)
    {
        foreach ($controller as $key=>$item) {
            $this->$key = $item;
        }
    }

    /**
     * Add a css file at your at the end of css pools
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    public function addCssFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->css_files, ".css", $path, $ltr_path);
    }

    /**
     * Add a js file at your at the end of js pools
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    public function addJsFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->footer_js_files, ".js", $path, $ltr_path);
    }

    /**
     * Add a js file at your in the header tag
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    public function addHeaderJsFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->header_js_files, ".js", $path, $ltr_path);
    }

    /**
     * Add all css files to your view files.
     * It will use on your main template frame file.
     */
    public function fetchAllCSS()
    {
        foreach ($this->css_files as $item) {
            $file = ROOTPATH . "public/" . $item;
            if (!file_exists($file)) {
                continue;
            }
            echo "<link rel='stylesheet' href='".base_url($item)."'>\n";
        }
    }

    /**
     * Add all js files to your view files at the end of body tag.
     * It will use on your main template frame file.
     */
    public function fetchAllJS()
    {
        foreach ($this->footer_js_files as $item) {
            $file = ROOTPATH . "public/" . $item;
            if (!file_exists($file)) {
                continue;
            }
            echo "<script type='text/javascript' src='".base_url($item)."'></script>\n";
        }
    }

    /**
     * Add all js files to your view files at the end of head tag.
     * It will use on your main template frame file.
     */
    public function fetchAllHeaderJS()
    {
        foreach ($this->header_js_files as $item) {
            $file = ROOTPATH . "public/" . $item;
            if (!file_exists($file)) {
                continue;
            }
            echo "<script type='text/javascript' src='".base_url($item)."'></script>\n";
        }
    }

    /**
     * Returns all attached css assets
     *
     * @return array
     */
    public function getAllCssAssets(): array
    {
        return $this->css_files;
    }

    /**
     * Returns all attached js assets
     *
     * @return array
     */
    public function getAllJsAssets(): array
    {
        return array_merge($this->header_js_files, $this->footer_js_files);
    }

    /**
     * Add an asset file to an assets array
     *
     * @param $variable
     * @param string $file_type
     * @param string $path
     * @param string|null $ltr_path
     */
    private function addAsset(&$variable, string $file_type, string $path, string $ltr_path = null)
    {
        if ($ltr_path != null) {
            if (\Config\Services::language()->hasBeenSet() && \Config\Services::language()->get()["rtl"]) {
                if (!in_array($ltr_path . $file_type, $variable)) {
                    array_push($variable, $ltr_path . $file_type);
                }
                return;
            }
        }

        if (!in_array($path . $file_type, $variable)) {
            array_push($variable, $path . $file_type);
        }
    }

    /**
     * Reset the config file
     *
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
        $this->viewPrefix = $this->config->namespacePieces;
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
        $view = $this->viewPrefix . "/" . $view;
        if (empty($this->tempData['settings'])) {
            $this->setData(['settings'=>\Config\Services::settings()->get()]);
        }
        return parent::render($view, $options, $saveData);
    }

    /**
     * Check view file exists
     *
     * @param string $view
     * @return bool
     */
    public function fileExists(string $view): bool
    {
        $view = $this->viewPrefix . "/" . $view;
        $fileExt = pathinfo($view, PATHINFO_EXTENSION);
        $realPath = empty($fileExt) ? $view . '.php' : $view; // allow Views as .html, .tpl, etc (from CI3)
        return file_exists($realPath);
    }

    /**
     * Keep the old flashdata() usage in view files
     *
     * @param string $key
     * @return array|false|null
     */
    public function flashdata(string $key)
    {
        if (!session()->has($key)) {
            return false;
        }

        return session()->getFlashdata($key);
    }
}
