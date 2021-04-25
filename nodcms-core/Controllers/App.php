<?php
/**
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

namespace NodCMS\Core\Controllers;


use Config\Services;

abstract class App extends Base
{
    // !Important: undefined $lang keys put in this array to add to language files
    public $langArray = array();

    // Class type: backend or frontend
    public $controllerType;

    // Admin sidebar menus
    public $admin_panel_items = array();

    public $captcha_session_name = 'nodcms_captcha';

    // Keep the form data for form helpers
    public $html_form_data = array('form_group'=>"inline_form_group", 'label_col'=>"col-md-2", 'input_col'=>"col-md-10");

    // Settings default values
    public $settings_default;

    /**
     * User data from DB
     *
     * @var array
     */
    public $userdata;

    /**
     * @var \NodCMS\Core\Modules\Modules
     */
    protected $modules;

    public function __construct()
    {
        parent::__construct();
        $this->modules = Services::modules();

        define('USER_UNDEFINED_AVATAR',base_url().'upload_file/images/user.png');
        define('ADMIN_URL',base_url("admin")."/");
    }

    /**
     * Create a pagination
     *
     * @param array $config
     */
    protected function mkPagination(array $config)
    {
        $pagination = Services::pager();
        $pagination->setSegment($config['uri_segment']);
        if($pagination->getPageCount() > 1)
            $this->data['pagination'] = $pagination->makeLinks($pagination->getCurrentPage(), $config['per_page'], $config['total_rows']);
    }

    /**
     * Display a general error page
     * TODO: Rename this
     *
     * @param null $message
     * @param int $status_code
     * @param null $heading
     * @param array $buttons
     * @return string
     */
    protected function showError($message = null, $status_code = 404, $heading = null, $buttons = array()): string
    {
        http_response_code($status_code);
        $this->data["title"] = _l("Error", $this)." $status_code";
        $this->data["status_code"] = $status_code;
        $this->data["heading"] = $heading!=null?$heading:_l("Page not found!", $this);
        $this->data["message"] = $message!=null?$message:_l("Your requested page not found.", $this);
        $this->data["buttons"] = $buttons;
        return $this->viewRenderString($this->viewCommon("common/show_error", $this->data));
    }
}