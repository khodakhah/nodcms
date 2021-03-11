<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\View;


use Config\Services;

class Layout extends View
{
    /**
     * Attach form assets on the layout
     */
    public function fetchAllCSS()
    {
        parent::fetchAllCSS();
        Services::formLayout()->fetchAllCSS();
    }

    /**
     * Attach form assets on the layout
     */
    public function fetchAllJS()
    {
        parent::fetchAllJS();
        Services::formLayout()->fetchAllJS();
    }

    /**
     * Attach form assets on the layout
     */
    public function fetchAllHeaderJS()
    {
        parent::fetchAllHeaderJS();
        Services::formLayout()->fetchAllHeaderJS();
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
        return $this->render($this->config->frameFile, $options, $saveData);
    }

    /**
     * Render sidebar
     *
     * @param string $view_file
     * @return string
     */
    public function sidebar(string $view_file = "sidebar"): string
    {
        return Services::sidebar()->render($view_file);
    }

    public function hasSidebar(): bool
    {
        return Services::sidebar()->hasLinks();
    }
}