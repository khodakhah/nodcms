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


class EmailNotification
{
    public function getEmptyMessages(): int
    {
//        $auto_emails = $this->config->item('autoEmailMessages');
//        // Load auto messages from packages directories
//        $packages = $this->load->packageList();
//        foreach ($packages as $item){
//            $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
//            if(is_array($package_auto_emails))
//                $auto_emails = array_merge($auto_emails, $package_auto_emails);
//        }
//        $missed = 0;
//        $languages = $this->model->languages()->getAll();
//        foreach ($auto_emails as $key=>$item){
//            foreach($languages as $language){
//                $filled = $this->Email_messages_model->getCount(array('code_key'=>$key, 'language_id'=>$language['language_id']));
//                if($filled==0){
//                    $missed++;
//                }
//            }
//        }
        return 0;
    }
}
