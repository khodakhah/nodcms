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
 *  @since      Version 3.1.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Notification;


use Config\Services;

class Notification
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $emailSubject = "Automatic Email";

    /**
     * @var string
     */
    private $emailHtmlTemplate;

    /**
     * Define a notification and set templates.
     *
     * @param string $key
     * @param null $language_id
     */
    public function __construct(string $key, $language_id = NULL)
    {
        $this->key = $key;
        $autoEmailMSG = \Config\Services::model()->emailMessages()->getOneByKey($this->key, $language_id);
        if (empty($autoEmailMSG)){
            if(!\Config\Services::view()->fileExists("emails/{$this->key}")){
                log_message('error', "Couldn't find notification email content for $this->key.");
                return;
            }
            $this->emailHtmlTemplate = \Config\Services::view()->render("emails/{$this->key}");
            return;
        }

        $this->emailHtmlTemplate = $autoEmailMSG['content'];
    }

    /**
     * Prepare and send a notification mail
     *
     * @param array $data
     * @param string $to
     * @param string|null $subject
     * @param string|null $from
     */
    public function sendEmail(array $data, string $to, ?string $subject = null, ?string $from = null)
    {
        $search = array();
        $replace = array();
        foreach($data as $key=>$value){
            array_push($search, '[--$'.$key.'--]');
            array_push($replace, $value);
        }
        $subject = !empty($subject) ? $subject : $this->emailSubject;
        $content = str_replace($search, $replace, $this->emailHtmlTemplate);
        $this->_sendEmail($to, $subject, $content, $from);
    }

    /**
     * Send email
     *
     * @param string|array $emails
     * @param string $subject
     * @param string $content
     * @param string|array|null $from
     */
    private function _sendEmail($emails, string $subject, string $content, $from = null)
    {
        if ($_SERVER['SERVER_NAME'] != 'localhost') {
            $content = Services::view()->setData(['body'=>$content])->render('emails/general-frame');
            $content = str_replace(
                array('&nbsp;'),
                array(' '),
                $content
            );
            $setting =  Services::settings()->get();;
            if($from == null)
                $from = array($setting['send_email'], $setting['company']);

            if(isset($setting['use_smtp']) && $setting['use_smtp']==1){
                $config = array(
                    'protocol' => 'smtp',
                    'SMTPHost' => $setting['smtp_host'],
                    'SMTPPort' => $setting['smtp_port'],
                    'SMTPUser' => $setting['smtp_username'],
                    'SMTPPass' => $setting['smtp_password'],
                    'mailType'  => 'html',
                    'charset'   => 'iso-8859-1',
                    'SMTPCrypto'  => true,
                    'newline'   => "\r\n"
                );
            }else{
                $config = array(
                    'protocol' => 'mail',
                    'mailType'  => 'html',
                    'charset'   => 'utf8',
                    'SMTPCrypto'  => true,
                    'newline'   => "\r\n"
                );
            }
            $email = Services::email();
            $email->initialize($config);
            $email->clear();
            $email->setTo($emails);
            $email->setFrom($from[0],$from[1]);
            $email->setHeader('Subject', $subject);
            $email->setMessage($content);
            if(!$email->send()) {
                log_message('error', "The email \"{$subject}\" couldn't sent to \"{$emails}\"!");
            }
        }
    }
}