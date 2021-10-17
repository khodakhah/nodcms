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