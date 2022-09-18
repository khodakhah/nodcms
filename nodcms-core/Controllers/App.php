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

    public function __construct()
    {
        parent::__construct();

        define('USER_UNDEFINED_AVATAR', base_url('upload_file/images/user.png'));
        define('ADMIN_URL', base_url("admin")."/");
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
        if ($pagination->getPageCount() > 1) {
            $this->data['pagination'] = $pagination->makeLinks($pagination->getCurrentPage(), $config['per_page'], $config['total_rows']);
        } else {
            $this->data['pagination'] = "";
        }
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
        $this->data["heading"] = $heading!=null ? $heading : _l("Page not found!", $this);
        $this->data["message"] = $message!=null ? $message : _l("Your requested page not found.", $this);
        $this->data["buttons"] = $buttons;
        return $this->viewRenderString($this->viewCommon("common/show_error", $this->data));
    }
}
