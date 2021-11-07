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


use Config\Services;

class Layout extends View
{
    /**
     * Contains the self object but with default settings
     *
     * @var self
     */
    private $_common_view;

    /**
     * Contains the loaded site settings
     *
     * @var array
     */
    public $settings;

    /**
     * Contains the current loaded language
     *
     * @var array
     */
    public $language;

    /**
     * Contains teh current loaded language two char code.
     *
     * @var string
     */
    public $lang;

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
     * The common view uses layout view files
     *
     * @return self
     */
    public function common()
    {
        if(empty($this->_common_view)) {
            $this->_common_view = new self();
        }
        return $this->_common_view;
    }

    /**
     * Add most common variable before any render
     *
     * @param string $view
     * @param array|null $options
     * @param bool|null $saveData
     * @return string
     */
    public function render(string $view, array $options = null, bool $saveData = null): string
    {
        $this->language = Services::language()->hasBeenSet() ? Services::language()->get() : [];
        $this->lang = Services::language()->getLocale();
        $this->settings = Services::settings()->get();
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
        $this->viewPrefix = $this->config->namespaceLayout;
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

    /**
     * Check if layout has some links to display in sidebar
     *
     * @return bool
     */
    public function hasSidebar(): bool
    {
        return Services::sidebar()->hasLinks();
    }
}
