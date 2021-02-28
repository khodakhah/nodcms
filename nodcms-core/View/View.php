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
     * @var \Config\View
     */
    public $config;

    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    public $request;

    /**
     * @var self
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
        $config == null && $config = new \Config\View();
        parent::__construct($config, $viewPath, $loader, $debug, $logger);
        $this->request = Services::request();
    }

    /**
     * The common view uses layout view files
     *
     * @return self
     */
    public function common(): self
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
     * Reset viewPath
     *
     * @param string $viewPath
     */
    public function setViewPath(string $viewPath)
    {
        $this->viewPath = $viewPath;
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
}