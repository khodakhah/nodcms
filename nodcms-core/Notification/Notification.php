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
