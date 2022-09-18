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

use Config\Autoload;
use Config\Models;
use Config\Services;
use NodCMS\Core\Libraries;

class General extends Frontend
{
    /**
     * Homepage
     *
     */
    public function index()
    {
        // Redirect to a URL
        if ($this->settings['homepage_type'] == "redirect") {
            header("location: ".$this->settings['homepage_redirect']);
            return "";
        }

        // Open and return a file content
        if ($this->settings['homepage_type'] == "display_file") {
            $myfile = fopen(SELF_PATH.$this->settings['homepage_display_file'], "r") or die("Unable to open file!");
            $result = fread($myfile, filesize($this->settings['homepage_display_file']));
            fclose($myfile);
            return $result;
        }

        // Display/curl a web page
        if ($this->settings['homepage_type'] == "display_page") {
            return $this->curlWebPage($this->settings['homepage_display_page']);
        }

        $this->data['packages'] = Services::modules()->getHomePreviews();

        // System homepage default
        $index_content = $this->viewCommon("home", $this->data);

        // Contact page
//        $data = $this->curlWebPage(base_url("$lang/contact-home"));
//        if(!preg_match("/\<title\>[\s]?Error 404[\s]?\<\/title>/",$data))
//            $index_content .= $data;

        $this->display_page_title = $this->settings['home_page_title_box'];
        if (!empty($this->settings['index_logo'])) {
            $this->data['title_logo'] = base_url($this->settings['index_logo']);
        }
        if (isset($this->settings['home_page_title_bg']) && !empty($this->settings['home_page_title_bg'])) {
            $this->data['title_bg'] = base_url($this->settings['home_page_title_bg']);
        }
        $this->data['title_bg_blur'] = $this->settings['home_page_title_bg_blur'];
        $this->data['title'] = $this->settings['company'];
        $this->data['sub_title'] = $this->settings["site_title"];
        $this->data['keyword'] = $this->settings["site_keyword"];
        $this->data['description'] = $this->settings["site_description"];
        $this->data['author'] = $this->settings["site_author"];
        return $this->viewRenderString($index_content);
    }

    /**
     * Remove a file
     *
     * @param $id
     * @param $key
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string|void
     */
    public function removeMyFile($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'remove_key'=>$key,
        );
        $row = Models::uploadFiles()->getOne(null, $conditions);
        if (count($row)==0) {
            return $this->errorMessage("The file couldn't find.", base_url());
        }
        $file = $row['file_path'];
        if (preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/', $row['file_path'])!=1) {
            $file = SELF_PATH . $file;
        }
        $myForm = new Libraries\Form($this);
        $unique_cookie = $myForm->getFileUniqueCookie();
        if ($row['unique_cookie']!=$unique_cookie) {
            return $this->errorMessage("You don't have access to remove this file.", base_url());
        }
        if (file_exists($file)) {
            unlink($file);
        }
        Models::uploadFiles()->remove($id);
        return $this->successMessage("The file has been removed successfully.", base_url());
    }

    /**
     * Download a file
     *
     * @param $id
     * @param $key
     * @throws \Exception
     */
    public function file($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'file_key'=>$key,
        );
        $row = $this->model->uploadFiles()->getOne(null, $conditions);
        if (empty($row)) {
            return $this->viewRenderString("The file couldn't find.");
        }
        $file = $row['file_path'];
        if (preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/', $row['file_path'])!=1) {
            $file = ROOTPATH . $file;
            if (!file_exists($file)) {
                throw new \Exception("The file doesn't exists.");
            }
        }
        return $this->response->download($file)->setFileName($row['name'])->setContentType($row['file_type']);
    }

    /**
     * Display an undefined image
     *
     * @param $width
     * @param $height
     * @param string $text
     */
    public function noimage($width, $height, $text = "No Image")
    {
        $font_size = 20;
        Services::response()->setHeader("Content-Type", "image/png");
        $im = @imagecreate($width, $height)
        or die("Cannot Initialize new GD image stream");
        imagecolorallocate($im, 240, 240, 240);
        $text_color = imagecolorallocate($im, 3, 3, 3);
        $black = imagecolorallocate($im, 0, 0, 0);
        $x = ($width/2)-(strlen($text)*($font_size/3));
        $y = ($height/2)+($font_size/2);
        $text = str_replace("_", " ", $text);
        $font = ROOTPATH.'public/assets/OpenSans-Regular.ttf';
        imagettftext($im, $font_size, 0, $x, $y, $text_color, $font, $text);
//        imagestring($im, 3, $x, $y, $text, $text_color);
        imagepng($im);
        imagedestroy($im);
    }

    /**
     * Display an image
     *
     * @param $id
     * @param $key
     */
    public function image($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'file_key'=>$key,
        );
        $row = $this->model->uploadFiles()->getOne(null, $conditions);
        if (empty($row)) {
            $this->noimage(400, 400, "The file couldn't find");
            return;
        }
        $file = $row['file_path'];
        if (preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/', $row['file_path'])!=1) {
            $file = SELF_PATH . $file;
            if (!file_exists($file)) {
                $this->noimage(400, 400, "The file doesn't exists");
                return;
            }
        }
        header('Content-Type: '.$row['file_type']);
        header('Content-Length: ' . filesize($file));
        echo file_get_contents(base_url($row['file_path']));
    }

    /**
     * Display the fixed pages on the software
     *  - There is some fixed page on the software:
     *      * Terms & Conditions (Route: /[LanguagePrefix]/terms-and-conditions)
     *      * Privacy Policy (Route: /[LanguagePrefix]/privacy-policy)
     *
     * @param $page_name
     */
    public function staticSettingsPages($page_name)
    {
        $page_contents = array(
            'terms-and-conditions'=>array(
                'title'=>$this->settings['terms_and_conditions_title'],
                'content'=>$this->settings['terms_and_conditions_content']
            ),
            'privacy-policy'=>array(
                'title'=>$this->settings['privacy_policy_title'],
                'content'=>$this->settings['privacy_policy_content']
            )
        );
        $this->data['title'] = $page_contents[$page_name]['title'];
        $this->data['keyword'] = "";
        $this->data['data'] = $page_contents[$page_name]['content'];
        echo $this->viewRender("static_settings_pages");
    }

    /**
     * Contact form page and google map
     *
     * @param $home
     */
    public function contact($home = null)
    {
        if ($this->settings['contact_form']==1) {
            $self_url = base_url("{$this->lang}/contact");
            $config = array(
                array(
                    'field'=>"email",
                    'rules'=>"required|valid_email",
                    'label'=>_l("Email", $this),
                    'type'=>"text",
                ),
                array(
                    'field'=>"name",
                    'rules'=>"required",
                    'label'=>_l("Your name", $this),
                    'type'=>"text",
                ),
                array(
                    'field'=>"message",
                    'rules'=>"required",
                    'label'=>_l("Message", $this),
                    'type'=>"textarea"
                ),
            );
            if ($this->settings['terms_accept_required']==1) {
                $config[] = array(
                    'field'=>"terms_and_conditions",
                    'type'=>"accept-terms",
                    'rules'=>"acceptTermsAndConditions",
                    'label'=>_l("Terms & Conditions", $this),
                );
            }
            if ($this->userdata!=null) {
                $config[0]['type'] = "static";
                $config[0]['class'] = "bold";
                $config[0]['value'] = $this->userdata['email'];
                $config[1]['type'] = "static";
                $config[1]['class'] = "bold";
                $config[1]['value'] = $this->userdata['fullname'];
            }
            $myForm = new Libraries\Form($this);
            $myForm->config($config, $self_url, 'post', 'ajax');
            if ($myForm->ispost()) {
                $data = $myForm->getPost();
                if (!is_array($data) || count($data)==0) {
                    return $myForm->getResponse();
                }
                if ($this->userdata!=null) {
                    $data['username'] = $this->userdata['username'];
                    $data['email'] = $this->userdata['email'];
                    $data['name'] = $this->userdata['fullname'];
                } else {
                    $data['username'] = "";
                }
                $data['date'] = my_int_date(time());

                // Send Notification Emial
                send_notification_email("contact_form", $this->settings['email'], $data, $this->language['language_id'], $data['email']);

                return $this->successMessage("The message has been successfully sent.", $self_url);
            }
            $myForm->setFormTheme('form_only');
            $myForm->setStyle('bootstrap-vertical');

            $form_attr = array('data-reset'=>1,'data-message'=>1);
            if ($this->request->isAJAX()) {
                return $myForm->fetch('', $form_attr, false);
            }
            $this->data['contact_form'] = $myForm->fetch('', $form_attr, false);
        }

        $this->data['title'] = _l("Contact us", $this);
        if ($home!=null) {
            return $this->viewCommon("contact_home", $this->data);
        }

        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = isset($this->settings["site_description"]) ? $this->settings["site_description"] : "";
        $this->data['keyword'] = isset($this->settings["site_keyword"]) ? $this->settings["site_keyword"] : "";
        return $this->viewRender("contact");
    }

    public function resetCaptcha()
    {
        print_captcha();
    }
}
