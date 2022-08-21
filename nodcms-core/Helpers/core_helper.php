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

if ( ! function_exists('_l')){
    function _l($label, $obj = null)
    {
        $label = str_replace('"','&quot;',$label);
        return lang($label);
    }
}

if ( ! function_exists('substr_string')){
    function substr_string($text,$start = 0,$count = 10,$end_text=" ..."){
        $explode = explode(" ",strip_tags($text));
        return implode(" ",array_splice($explode,$start,$count)).(count($explode)>$count?$end_text:"");
    }
}

if ( ! function_exists('json_range_date_to_array')){
    function json_range_date_to_array($date, $delimiter = ',')
    {
        $dates = explode($delimiter, $date);
        foreach ($dates as &$item){
            $item /= 1000;
        }
        return $dates;
    }
}

if ( ! function_exists('my_int_date')){
    function my_int_date($date)
    {
        if($date==0) return "";
        return date(\Config\Services::settings()->get()["date_format"] ,$date);
    }
}

if ( ! function_exists('my_int_fullDate')){
    function my_int_fullDate($time){
        if($time==0) return "";
        return date_translate(date("j F Y | ".\Config\Services::settings()->get()["time_format"],$time));
    }
}

if ( ! function_exists('my_int_justDate')){
    function my_int_justDate($time){
        if($time==0) return "";
        return date_translate(date("j F Y",$time));
    }
}

if ( ! function_exists('my_int_justTime')){
    function my_int_justTime($time){
        if($time==0) return "";
        return date_translate(date(\Config\Services::settings()->get()["time_format"], $time));
    }
}

if( !function_exists('date_translate')){
    function date_translate($text){
        $translation = array(
            "January" => _l("January"),
            "February" => _l("February"),
            "March" => _l("March"),
            "April" => _l("April"),
            "May" => _l("May"),
            "June" => _l("June"),
            "July" => _l("July"),
            "August" => _l("August"),
            "September" => _l("September"),
            "October" => _l("October"),
            "November" => _l("November"),
            "December" => _l("December"),
            "Jan" => _l("Jan"),
            "Feb" => _l("Feb"),
            "Mar" => _l("Mar"),
            "Apr" => _l("Apr"),
            "Jun" => _l("Jun"),
            "Jul" => _l("Jul"),
            "Aug" => _l("Aug"),
            "Sep" => _l("Sep"),
            "Oct" => _l("Oct"),
            "Nov" => _l("Nov"),
            "Dec" => _l("Dec"),
            "Sunday" => _l("Sunday"),
            "Monday" => _l("Monday"),
            "Tuesday" => _l("Tuesday"),
            "Wednesday" => _l("Wednesday"),
            "Thursday" => _l("Thursday"),
            "Friday" => _l("Friday"),
            "Saturday" => _l("Saturday"),
            "Sun" => _l("Sun"),
            "Mon" => _l("Mon"),
            "Tue" => _l("Tue"),
            "Wed" => _l("Wed"),
            "Thu" => _l("Thu"),
            "Fri" => _l("Fri"),
            "Sat" => _l("Sat"),
            "Su" => _l("Su"),
            "Mo" => _l("Mo"),
            "Tu" => _l("Tu"),
            "We" => _l("We"),
            "Th" => _l("Th"),
            "Fr" => _l("Fr"),
            "Sa" => _l("Sa"),
            "AM" => _l("AM"),
            "am" => _l("am"),
            "PM" => _l("PM"),
            "pm" => _l("pm"),
        );
        $search = array_keys($translation);
        $replace = array_values($translation);

        return str_replace($search, $replace, $text);
    }
}

if ( ! function_exists('email_key_replace')){
    function email_key_replace($text, $array){
        $search = array();
        $replace = array();
        foreach($array as $key=>$value){
            array_push($search, '[--$'.$key.'--]');
            array_push($replace, $value);
        }
        return str_replace($search, $replace, $text);
    }
}

// TODO: check the function (! email not found!)
if ( ! function_exists('send_notification_email')){
    function send_notification_email($key, $to, $data, $language_id = NULL, $from = NULL){
        \Config\Services::notification($key, $language_id)->sendEmail($data, $to, null, $from);
    }
}
if ( ! function_exists('send_notification_sms')){
    function send_notification_sms($key, $to, $data, $language_id = NULL){
        $CI = &get_instance();

        if(!$CI->load->packageExists("Sms"))
            return;

        $CI->load->add_package_path(APPPATH."third_party/Sms");
        $smsContent = $CI->Sms_model->getAutoMessages($key, $language_id);
        if (!isset($smsContent['content'])){
            log_message("error", "Couldn't send notification sms to '$to', because there isn't any sms content for '$key'");
            return;
        }
        $search = array();
        $replace = array();
        foreach($data as $key=>$value){
            array_push($search, '[--$'.$key.'--]');
            array_push($replace, $value);
        }
        $content = str_replace($search, $replace, $smsContent['content']);
        $CI->load->packageShortcut("Sms", "sendMessage", array($to, $content));
    }
}

if ( ! function_exists('get_all_php_files')){
    function get_all_php_files($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata	= array();
            $new_depth	= $directory_depth - 1;
            $source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.' OR $file === '..' OR ($hidden === FALSE && $file[0] === '.'))
                {
                    continue;
                }

                is_dir($source_dir.$file) && $file .= DIRECTORY_SEPARATOR;

                if (($directory_depth < 1 OR $new_depth > 0) && is_dir($source_dir.$file))
                {
                    $filedata = array_merge($filedata, get_all_php_files($source_dir.$file, $new_depth, $hidden));
                }
                else
                {
                    $check_match = preg_match('/^([A-Za-z\_\-]+\.php)$/', $file);
                    if($check_match==false || $check_match==0){
                        continue;
                    }
                    $filedata[] = $source_dir.$file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }
}

if( ! function_exists('print_captcha')){
    function print_captcha(){
        $CI =& get_instance();
        $random_word = substr(md5(rand(10000,99999)),-6,6);
        $_SESSION[$CI->captcha_session_name] = $random_word;
        $CI->load->helper('captcha');
        $vals = array(
            'word'=>$random_word,
            'img_path'=> FCPATH.'upload_file/captcha/',
            'img_url'=> base_url('upload_file/captcha/'),
            'img_width'     => 100,
            'img_height'    => 40,
            'word_length'     => 6,
            'font_size'     => 40,
        );

        $cap = create_captcha($vals);
        echo $cap['image'];
    }
}
if( ! function_exists('active_captcha')){
    function active_captcha(){
        $CI =& get_instance();
        if(!isset($CI->settings['captcha']))
            return 0;
        if($CI->settings['captcha']==2){
            if(!isset($CI->provider["settings"]["captcha"]))
                return 0;
            return $CI->provider["settings"]["captcha"];
        }
        return $CI->settings['captcha'];
    }
}

if( ! function_exists('adminSidebarMap')){
    function adminSidebarMap($sidebar_array){
        if(is_array($sidebar_array) && count($sidebar_array) != 0){
            foreach($sidebar_array as $key=>$item){
                if(isset($item["sub_menu"]) && is_array($item["sub_menu"]) && count($item["sub_menu"])!=0){
                    $sub_menu = TRUE;
                }else{
                    $sub_menu = FALSE;
                }
                ?>
                <li class="nav-item" id="<?php echo $key; ?>">
                    <a class="nav-link nav-toggle" href="<?php echo isset($item["url"])?$item["url"]:""; ?>">
                        <i class="<?php echo isset($item["icon"])?$item["icon"]:''; ?>"></i>
                        <span class="title"><?php echo isset($item["title"])?$item["title"]:"Undefined title"; ?></span>
                        <?php if($sub_menu){ ?>
                            <span class="arrow"></span>
                        <?php } ?>
                    </a>
                    <?php if($sub_menu){ ?>
                        <ul class="sub-menu">
                            <?php adminSidebarMap($item["sub_menu"]); ?>
                        </ul>
                    <?php } ?>
                </li>
                <?php
            }
        }
    }
}

if(!function_exists('array_remove_diff')){
    /**
     * Merge and remove the difference of two input array
     *
     * @param $arr1 array
     * @param $arr2 array
     * @return array
     */
    function array_remove_diff($arr1, $arr2){
        $arr = array_merge($arr1, $arr2);
        $diff = array_diff_key($arr, $arr1);
        foreach ($diff as $key=>$item) {
            unset($arr[$key]);
        }
        return $arr;
    }
}

if(!function_exists('get_user_avatar_url')){
    /**
     * Return the full url of a user
     *
     * @param $user
     * @return string
     */
    function get_user_avatar_url($user){
        if($user['avatar']=="" || !file_exists(FCPATH.$user['avatar'])){
            return base_url("upload_file/images/user.png");
        }
        return base_url($user['avatar']);
    }
}

if(!function_exists('split_hex_color')){
    /**
     * Automatic split color code and return the color code of the index
     *
     * @param $index
     * @param $count
     * @return string
     */
    function split_hex_color($index, $count){
        // Max number(white)
        $col = hexdec("FFFFFF");
        $col_part = round($col/$count);
        // Put a way form black & white
        while($col_part%hexdec("111111")==0 || $col_part%16==0 || $count%17==0){
            $count++;
            $col_part = round($col/$count);
        }
        // Put a way from to begin with black
        $index++;
        $color_code=($index*$col_part);
        // Set the 0 before to make it 6 char
        $color=substr("000000".dechex($color_code),-6);
        return "#$color";
    }
}

if(!function_exists('human_file_size')){
    /**
     * @param $value
     * @return string
     */
    function human_file_size($value){
//        var_dump(require_once )
        if($value < 1024){
            return "$value KB";
        }
        $value = round($value/1024);
        if($value < 1024){
            return "$value MB";
        }
        $value = round($value/1024);
        if($value < 1024){
            return "$value GB";
        }
        $value = round($value/1024);
        return "$value TB";
    }
}

if(!function_exists('countries_iso_codes')){
    function countries_iso_codes(){
        return array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BQ' => 'Bonaire, Saint Eustatius and Saba',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CW' => 'Curacao',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'CD' => 'Democratic Republic of the Congo',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'TL' => 'East Timor',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'CI' => 'Ivory Coast',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'XK' => 'Kosovo',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'KP' => 'North Korea',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'CG' => 'Republic of the Congo',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SX' => 'Sint Maarten',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'KR' => 'South Korea',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'VI' => 'U.S. Virgin Islands',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
    }
}

if(!function_exists('countries_iso')){
    function countries_iso(){
        return array(
            'AF'=>array(
                'name'=>'Afghanistan',
                'char-2'=>"AF",
                'char-3'=>"AFG",
                'code'=>"004"
            ),

            'AX'=>array(
                'name'=>'Åland Islands',
                'char-2'=>"AX",
                'char-3'=>"ALA",
                'code'=>"248"
            ),

            'AL'=>array(
                'name'=>'Albania',
                'char-2'=>"AL",
                'char-3'=>"ALB",
                'code'=>"008"
            ),

            'DZ'=>array(
                'name'=>'Algeria',
                'char-2'=>"DZ",
                'char-3'=>"DZA",
                'code'=>"012"
            ),

            'AS'=>array(
                'name'=>'American Samoa',
                'char-2'=>"AS",
                'char-3'=>"ASM",
                'code'=>"016"
            ),

            'AD'=>array(
                'name'=>'Andorra',
                'char-2'=>"AD",
                'char-3'=>"AND",
                'code'=>"020"
            ),

            'AO'=>array(
                'name'=>'Angola',
                'char-2'=>"AO",
                'char-3'=>"AGO",
                'code'=>"024"
            ),

            'AI'=>array(
                'name'=>'Anguilla',
                'char-2'=>"AI",
                'char-3'=>"AIA",
                'code'=>"660"
            ),

            'AQ'=>array(
                'name'=>'Antarctica',
                'char-2'=>"AQ",
                'char-3'=>"ATA",
                'code'=>"010"
            ),

            'AG'=>array(
                'name'=>'Antigua and Barbuda',
                'char-2'=>"AG",
                'char-3'=>"ATG",
                'code'=>"028"
            ),

            'AR'=>array(
                'name'=>'Argentina',
                'char-2'=>"AR",
                'char-3'=>"ARG",
                'code'=>"032"
            ),

            'AM'=>array(
                'name'=>'Armenia',
                'char-2'=>"AM",
                'char-3'=>"ARM",
                'code'=>"051"
            ),

            'AW'=>array(
                'name'=>'Aruba',
                'char-2'=>"AW",
                'char-3'=>"ABW",
                'code'=>"533"
            ),

            'AU'=>array(
                'name'=>'Australia',
                'char-2'=>"AU",
                'char-3'=>"AUS",
                'code'=>"036"
            ),

            'AT'=>array(
                'name'=>'Austria',
                'char-2'=>"AT",
                'char-3'=>"AUT",
                'code'=>"040"
            ),

            'AZ'=>array(
                'name'=>'Azerbaijan',
                'char-2'=>"AZ",
                'char-3'=>"AZE",
                'code'=>"031"
            ),

            'BS'=>array(
                'name'=>'Bahamas',
                'char-2'=>"BS",
                'char-3'=>"BHS",
                'code'=>"044"
            ),

            'BH'=>array(
                'name'=>'Bahrain',
                'char-2'=>"BH",
                'char-3'=>"BHR",
                'code'=>"048"
            ),

            'BD'=>array(
                'name'=>'Bangladesh',
                'char-2'=>"BD",
                'char-3'=>"BGD",
                'code'=>"050"
            ),

            'BB'=>array(
                'name'=>'Barbados',
                'char-2'=>"BB",
                'char-3'=>"BRB",
                'code'=>"052"
            ),

            'BY'=>array(
                'name'=>'Belarus',
                'char-2'=>"BY",
                'char-3'=>"BLR",
                'code'=>"112"
            ),

            'BE'=>array(
                'name'=>'Belgium',
                'char-2'=>"BE",
                'char-3'=>"BEL",
                'code'=>"056"
            ),

            'BZ'=>array(
                'name'=>'Belize',
                'char-2'=>"BZ",
                'char-3'=>"BLZ",
                'code'=>"084"
            ),

            'BJ'=>array(
                'name'=>'Benin',
                'char-2'=>"BJ",
                'char-3'=>"BEN",
                'code'=>"204"
            ),

            'BM'=>array(
                'name'=>'Bermuda',
                'char-2'=>"BM",
                'char-3'=>"BMU",
                'code'=>"060"
            ),

            'BT'=>array(
                'name'=>'Bhutan',
                'char-2'=>"BT",
                'char-3'=>"BTN",
                'code'=>"064"
            ),

            'BO'=>array(
                'name'=>'Bolivia,
             Plurinational State of',
                'char-2'=>"BO",
                'char-3'=>"BOL",
                'code'=>"068"
            ),

            'BQ'=>array(
                'name'=>'Bonaire,
             Sint Eustatius and Saba',
                'char-2'=>"BQ",
                'char-3'=>"BES",
                'code'=>"535"
            ),

            'BA'=>array(
                'name'=>'Bosnia and Herzegovina',
                'char-2'=>"BA",
                'char-3'=>"BIH",
                'code'=>"070"
            ),

            'BW'=>array(
                'name'=>'Botswana',
                'char-2'=>"BW",
                'char-3'=>"BWA",
                'code'=>"072"
            ),

            'BV'=>array(
                'name'=>'Bouvet Island',
                'char-2'=>"BV",
                'char-3'=>"BVT",
                'code'=>"074"
            ),

            'BR'=>array(
                'name'=>'Brazil',
                'char-2'=>"BR",
                'char-3'=>"BRA",
                'code'=>"076"
            ),

            'IO'=>array(
                'name'=>'British Indian Ocean Territory',
                'char-2'=>"IO",
                'char-3'=>"IOT",
                'code'=>"086"
            ),

            'BN'=>array(
                'name'=>'Brunei Darussalam',
                'char-2'=>"BN",
                'char-3'=>"BRN",
                'code'=>"096"
            ),

            'BG'=>array(
                'name'=>'Bulgaria',
                'char-2'=>"BG",
                'char-3'=>"BGR",
                'code'=>"100"
            ),

            'BF'=>array(
                'name'=>'Burkina Faso',
                'char-2'=>"BF",
                'char-3'=>"BFA",
                'code'=>"854"
            ),

            'BI'=>array(
                'name'=>'Burundi',
                'char-2'=>"BI",
                'char-3'=>"BDI",
                'code'=>"108"
            ),

            'KH'=>array(
                'name'=>'Cambodia',
                'char-2'=>"KH",
                'char-3'=>"KHM",
                'code'=>"116"
            ),

            'CM'=>array(
                'name'=>'Cameroon',
                'char-2'=>"CM",
                'char-3'=>"CMR",
                'code'=>"120"
            ),

            'CA'=>array(
                'name'=>'Canada',
                'char-2'=>"CA",
                'char-3'=>"CAN",
                'code'=>"124"
            ),

            'CV'=>array(
                'name'=>'Cape Verde',
                'char-2'=>"CV",
                'char-3'=>"CPV",
                'code'=>"132"
            ),

            'KY'=>array(
                'name'=>'Cayman Islands',
                'char-2'=>"KY",
                'char-3'=>"CYM",
                'code'=>"136"
            ),

            'CF'=>array(
                'name'=>'Central African Republic',
                'char-2'=>"CF",
                'char-3'=>"CAF",
                'code'=>"140"
            ),

            'TD'=>array(
                'name'=>'Chad',
                'char-2'=>"TD",
                'char-3'=>"TCD",
                'code'=>"148"
            ),

            'CL'=>array(
                'name'=>'Chile',
                'char-2'=>"CL",
                'char-3'=>"CHL",
                'code'=>"152"
            ),

            'CN'=>array(
                'name'=>'China',
                'char-2'=>"CN",
                'char-3'=>"CHN",
                'code'=>"156"
            ),

            'CX'=>array(
                'name'=>'Christmas Island',
                'char-2'=>"CX",
                'char-3'=>"CXR",
                'code'=>"162"
            ),

            'CC'=>array(
                'name'=>'Cocos (Keeling) Islands',
                'char-2'=>"CC",
                'char-3'=>"CCK",
                'code'=>"166"
            ),

            'CO'=>array(
                'name'=>'Colombia',
                'char-2'=>"CO",
                'char-3'=>"COL",
                'code'=>"170"
            ),

            'KM'=>array(
                'name'=>'Comoros',
                'char-2'=>"KM",
                'char-3'=>"COM",
                'code'=>"174"
            ),

            'CG'=>array(
                'name'=>'Congo',
                'char-2'=>"CG",
                'char-3'=>"COG",
                'code'=>"178"
            ),

            'CD'=>array(
                'name'=>'Congo,
             the Democratic Republic of the',
                'char-2'=>"CD",
                'char-3'=>"COD",
                'code'=>"180"
            ),

            'CK'=>array(
                'name'=>'Cook Islands',
                'char-2'=>"CK",
                'char-3'=>"COK",
                'code'=>"184"
            ),

            'CR'=>array(
                'name'=>'Costa Rica',
                'char-2'=>"CR",
                'char-3'=>"CRI",
                'code'=>"188"
            ),

            'CI'=>array(
                'name'=>'Côte d\'Ivoire',
                'char-2'=>"CI",
                'char-3'=>"CIV",
                'code'=>"384"
            ),

            'HR'=>array(
                'name'=>'Croatia',
                'char-2'=>"HR",
                'char-3'=>"HRV",
                'code'=>"191"
            ),

            'CU'=>array(
                'name'=>'Cuba',
                'char-2'=>"CU",
                'char-3'=>"CUB",
                'code'=>"192"
            ),

            'CW'=>array(
                'name'=>'Curaçao',
                'char-2'=>"CW",
                'char-3'=>"CUW",
                'code'=>"531"
            ),

            'CY'=>array(
                'name'=>'Cyprus',
                'char-2'=>"CY",
                'char-3'=>"CYP",
                'code'=>"196"
            ),

            'CZ'=>array(
                'name'=>'Czech Republic',
                'char-2'=>"CZ",
                'char-3'=>"CZE",
                'code'=>"203"
            ),

            'DK'=>array(
                'name'=>'Denmark',
                'char-2'=>"DK",
                'char-3'=>"DNK",
                'code'=>"208"
            ),

            'DJ'=>array(
                'name'=>'Djibouti',
                'char-2'=>"DJ",
                'char-3'=>"DJI",
                'code'=>"262"
            ),

            'DM'=>array(
                'name'=>'Dominica',
                'char-2'=>"DM",
                'char-3'=>"DMA",
                'code'=>"212"
            ),

            'DO'=>array(
                'name'=>'Dominican Republic',
                'char-2'=>"DO",
                'char-3'=>"DOM",
                'code'=>"214"
            ),

            'EC'=>array(
                'name'=>'Ecuador',
                'char-2'=>"EC",
                'char-3'=>"ECU",
                'code'=>"218"
            ),

            'EG'=>array(
                'name'=>'Egypt',
                'char-2'=>"EG",
                'char-3'=>"EGY",
                'code'=>"818"
            ),

            'SV'=>array(
                'name'=>'El Salvador',
                'char-2'=>"SV",
                'char-3'=>"SLV",
                'code'=>"222"
            ),

            'GQ'=>array(
                'name'=>'Equatorial Guinea',
                'char-2'=>"GQ",
                'char-3'=>"GNQ",
                'code'=>"226"
            ),

            'ER'=>array(
                'name'=>'Eritrea',
                'char-2'=>"ER",
                'char-3'=>"ERI",
                'code'=>"232"
            ),

            'EE'=>array(
                'name'=>'Estonia',
                'char-2'=>"EE",
                'char-3'=>"EST",
                'code'=>"233"
            ),

            'ET'=>array(
                'name'=>'Ethiopia',
                'char-2'=>"ET",
                'char-3'=>"ETH",
                'code'=>"231"
            ),

            'FK'=>array(
                'name'=>'Falkland Islands (Malvinas)',
                'char-2'=>"FK",
                'char-3'=>"FLK",
                'code'=>"238"
            ),

            'FO'=>array(
                'name'=>'Faroe Islands',
                'char-2'=>"FO",
                'char-3'=>"FRO",
                'code'=>"234"
            ),

            'FJ'=>array(
                'name'=>'Fiji',
                'char-2'=>"FJ",
                'char-3'=>"FJI",
                'code'=>"242"
            ),

            'FI'=>array(
                'name'=>'Finland',
                'char-2'=>"FI",
                'char-3'=>"FIN",
                'code'=>"246"
            ),

            'FR'=>array(
                'name'=>'France',
                'char-2'=>"FR",
                'char-3'=>"FRA",
                'code'=>"250"
            ),

            'GF'=>array(
                'name'=>'French Guiana',
                'char-2'=>"GF",
                'char-3'=>"GUF",
                'code'=>"254"
            ),

            'PF'=>array(
                'name'=>'French Polynesia',
                'char-2'=>"PF",
                'char-3'=>"PYF",
                'code'=>"258"
            ),

            'TF'=>array(
                'name'=>'French Southern Territories',
                'char-2'=>"TF",
                'char-3'=>"ATF",
                'code'=>"260"
            ),

            'GA'=>array(
                'name'=>'Gabon',
                'char-2'=>"GA",
                'char-3'=>"GAB",
                'code'=>"266"
            ),

            'GM'=>array(
                'name'=>'Gambia',
                'char-2'=>"GM",
                'char-3'=>"GMB",
                'code'=>"270"
            ),

            'GE'=>array(
                'name'=>'Georgia',
                'char-2'=>"GE",
                'char-3'=>"GEO",
                'code'=>"268"
            ),

            'DE'=>array(
                'name'=>'Germany',
                'char-2'=>"DE",
                'char-3'=>"DEU",
                'code'=>"276"
            ),

            'GH'=>array(
                'name'=>'Ghana',
                'char-2'=>"GH",
                'char-3'=>"GHA",
                'code'=>"288"
            ),

            'GI'=>array(
                'name'=>'Gibraltar',
                'char-2'=>"GI",
                'char-3'=>"GIB",
                'code'=>"292"
            ),

            'GR'=>array(
                'name'=>'Greece',
                'char-2'=>"GR",
                'char-3'=>"GRC",
                'code'=>"300"
            ),

            'GL'=>array(
                'name'=>'Greenland',
                'char-2'=>"GL",
                'char-3'=>"GRL",
                'code'=>"304"
            ),

            'GD'=>array(
                'name'=>'Grenada',
                'char-2'=>"GD",
                'char-3'=>"GRD",
                'code'=>"308"
            ),

            'GP'=>array(
                'name'=>'Guadeloupe',
                'char-2'=>"GP",
                'char-3'=>"GLP",
                'code'=>"312"
            ),

            'GU'=>array(
                'name'=>'Guam',
                'char-2'=>"GU",
                'char-3'=>"GUM",
                'code'=>"316"
            ),

            'GT'=>array(
                'name'=>'Guatemala',
                'char-2'=>"GT",
                'char-3'=>"GTM",
                'code'=>"320"
            ),

            'GG'=>array(
                'name'=>'Guernsey',
                'char-2'=>"GG",
                'char-3'=>"GGY",
                'code'=>"831"
            ),

            'GN'=>array(
                'name'=>'Guinea',
                'char-2'=>"GN",
                'char-3'=>"GIN",
                'code'=>"324"
            ),

            'GW'=>array(
                'name'=>'Guinea-Bissau',
                'char-2'=>"GW",
                'char-3'=>"GNB",
                'code'=>"624"
            ),

            'GY'=>array(
                'name'=>'Guyana',
                'char-2'=>"GY",
                'char-3'=>"GUY",
                'code'=>"328"
            ),

            'HT'=>array(
                'name'=>'Haiti',
                'char-2'=>"HT",
                'char-3'=>"HTI",
                'code'=>"332"
            ),

            'HM'=>array(
                'name'=>'Heard Island and McDonald Islands',
                'char-2'=>"HM",
                'char-3'=>"HMD",
                'code'=>"334"
            ),

            'VA'=>array(
                'name'=>'Holy See (Vatican City State)',
                'char-2'=>"VA",
                'char-3'=>"VAT",
                'code'=>"336"
            ),

            'HN'=>array(
                'name'=>'Honduras',
                'char-2'=>"HN",
                'char-3'=>"HND",
                'code'=>"340"
            ),

            'HK'=>array(
                'name'=>'Hong Kong',
                'char-2'=>"HK",
                'char-3'=>"HKG",
                'code'=>"344"
            ),

            'HU'=>array(
                'name'=>'Hungary',
                'char-2'=>"HU",
                'char-3'=>"HUN",
                'code'=>"348"
            ),

            'IS'=>array(
                'name'=>'Iceland',
                'char-2'=>"IS",
                'char-3'=>"ISL",
                'code'=>"352"
            ),

            'IN'=>array(
                'name'=>'India',
                'char-2'=>"IN",
                'char-3'=>"IND",
                'code'=>"356"
            ),

            'ID'=>array(
                'name'=>'Indonesia',
                'char-2'=>"ID",
                'char-3'=>"IDN",
                'code'=>"360"
            ),

            'IR'=>array(
                'name'=>'Iran,
             Islamic Republic of',
                'char-2'=>"IR",
                'char-3'=>"IRN",
                'code'=>"364"
            ),

            'IQ'=>array(
                'name'=>'Iraq',
                'char-2'=>"IQ",
                'char-3'=>"IRQ",
                'code'=>"368"
            ),

            'IE'=>array(
                'name'=>'Ireland',
                'char-2'=>"IE",
                'char-3'=>"IRL",
                'code'=>"372"
            ),

            'IM'=>array(
                'name'=>'Isle of Man',
                'char-2'=>"IM",
                'char-3'=>"IMN",
                'code'=>"833"
            ),

            'IL'=>array(
                'name'=>'Israel',
                'char-2'=>"IL",
                'char-3'=>"ISR",
                'code'=>"376"
            ),

            'IT'=>array(
                'name'=>'Italy',
                'char-2'=>"IT",
                'char-3'=>"ITA",
                'code'=>"380"
            ),

            'JM'=>array(
                'name'=>'Jamaica',
                'char-2'=>"JM",
                'char-3'=>"JAM",
                'code'=>"388"
            ),

            'JP'=>array(
                'name'=>'Japan',
                'char-2'=>"JP",
                'char-3'=>"JPN",
                'code'=>"392"
            ),

            'JE'=>array(
                'name'=>'Jersey',
                'char-2'=>"JE",
                'char-3'=>"JEY",
                'code'=>"832"
            ),

            'JO'=>array(
                'name'=>'Jordan',
                'char-2'=>"JO",
                'char-3'=>"JOR",
                'code'=>"400"
            ),

            'KZ'=>array(
                'name'=>'Kazakhstan',
                'char-2'=>"KZ",
                'char-3'=>"KAZ",
                'code'=>"398"
            ),

            'KE'=>array(
                'name'=>'Kenya',
                'char-2'=>"KE",
                'char-3'=>"KEN",
                'code'=>"404"
            ),

            'KI'=>array(
                'name'=>'Kiribati',
                'char-2'=>"KI",
                'char-3'=>"KIR",
                'code'=>"296"
            ),

            'KP'=>array(
                'name'=>'Korea,
             Democratic People\'s Republic of',
                'char-2'=>"KP",
                'char-3'=>"PRK",
                'code'=>"408"
            ),

            'KR'=>array(
                'name'=>'Korea,
             Republic of',
                'char-2'=>"KR",
                'char-3'=>"KOR",
                'code'=>"410"
            ),

            'KW'=>array(
                'name'=>'Kuwait',
                'char-2'=>"KW",
                'char-3'=>"KWT",
                'code'=>"414"
            ),

            'KG'=>array(
                'name'=>'Kyrgyzstan',
                'char-2'=>"KG",
                'char-3'=>"KGZ",
                'code'=>"417"
            ),

            'LA'=>array(
                'name'=>'Lao People\'s Democratic Republic',
                'char-2'=>"LA",
                'char-3'=>"LAO",
                'code'=>"418"
            ),

            'LV'=>array(
                'name'=>'Latvia',
                'char-2'=>"LV",
                'char-3'=>"LVA",
                'code'=>"428"
            ),

            'LB'=>array(
                'name'=>'Lebanon',
                'char-2'=>"LB",
                'char-3'=>"LBN",
                'code'=>"422"
            ),

            'LS'=>array(
                'name'=>'Lesotho',
                'char-2'=>"LS",
                'char-3'=>"LSO",
                'code'=>"426"
            ),

            'LR'=>array(
                'name'=>'Liberia',
                'char-2'=>"LR",
                'char-3'=>"LBR",
                'code'=>"430"
            ),

            'LY'=>array(
                'name'=>'Libya',
                'char-2'=>"LY",
                'char-3'=>"LBY",
                'code'=>"434"
            ),

            'LI'=>array(
                'name'=>'Liechtenstein',
                'char-2'=>"LI",
                'char-3'=>"LIE",
                'code'=>"438"
            ),

            'LT'=>array(
                'name'=>'Lithuania',
                'char-2'=>"LT",
                'char-3'=>"LTU",
                'code'=>"440"
            ),

            'LU'=>array(
                'name'=>'Luxembourg',
                'char-2'=>"LU",
                'char-3'=>"LUX",
                'code'=>"442"
            ),

            'MO'=>array(
                'name'=>'Macao',
                'char-2'=>"MO",
                'char-3'=>"MAC",
                'code'=>"446"
            ),

            'MK'=>array(
                'name'=>'Macedonia,
             the former Yugoslav Republic of',
                'char-2'=>"MK",
                'char-3'=>"MKD",
                'code'=>"807"
            ),

            'MG'=>array(
                'name'=>'Madagascar',
                'char-2'=>"MG",
                'char-3'=>"MDG",
                'code'=>"450"
            ),

            'MW'=>array(
                'name'=>'Malawi',
                'char-2'=>"MW",
                'char-3'=>"MWI",
                'code'=>"454"
            ),

            'MY'=>array(
                'name'=>'Malaysia',
                'char-2'=>"MY",
                'char-3'=>"MYS",
                'code'=>"458"
            ),

            'MV'=>array(
                'name'=>'Maldives',
                'char-2'=>"MV",
                'char-3'=>"MDV",
                'code'=>"462"
            ),

            'ML'=>array(
                'name'=>'Mali',
                'char-2'=>"ML",
                'char-3'=>"MLI",
                'code'=>"466"
            ),

            'MT'=>array(
                'name'=>'Malta',
                'char-2'=>"MT",
                'char-3'=>"MLT",
                'code'=>"470"
            ),

            'MH'=>array(
                'name'=>'Marshall Islands',
                'char-2'=>"MH",
                'char-3'=>"MHL",
                'code'=>"584"
            ),

            'MQ'=>array(
                'name'=>'Martinique',
                'char-2'=>"MQ",
                'char-3'=>"MTQ",
                'code'=>"474"
            ),

            'MR'=>array(
                'name'=>'Mauritania',
                'char-2'=>"MR",
                'char-3'=>"MRT",
                'code'=>"478"
            ),

            'MU'=>array(
                'name'=>'Mauritius',
                'char-2'=>"MU",
                'char-3'=>"MUS",
                'code'=>"480"
            ),

            'YT'=>array(
                'name'=>'Mayotte',
                'char-2'=>"YT",
                'char-3'=>"MYT",
                'code'=>"175"
            ),

            'MX'=>array(
                'name'=>'Mexico',
                'char-2'=>"MX",
                'char-3'=>"MEX",
                'code'=>"484"
            ),

            'FM'=>array(
                'name'=>'Micronesia,
             Federated States of',
                'char-2'=>"FM",
                'char-3'=>"FSM",
                'code'=>"583"
            ),

            'MD'=>array(
                'name'=>'Moldova,
             Republic of',
                'char-2'=>"MD",
                'char-3'=>"MDA",
                'code'=>"498"
            ),

            'MC'=>array(
                'name'=>'Monaco',
                'char-2'=>"MC",
                'char-3'=>"MCO",
                'code'=>"492"
            ),

            'MN'=>array(
                'name'=>'Mongolia',
                'char-2'=>"MN",
                'char-3'=>"MNG",
                'code'=>"496"
            ),

            'ME'=>array(
                'name'=>'Montenegro',
                'char-2'=>"ME",
                'char-3'=>"MNE",
                'code'=>"499"
            ),

            'MS'=>array(
                'name'=>'Montserrat',
                'char-2'=>"MS",
                'char-3'=>"MSR",
                'code'=>"500"
            ),

            'MA'=>array(
                'name'=>'Morocco',
                'char-2'=>"MA",
                'char-3'=>"MAR",
                'code'=>"504"
            ),

            'MZ'=>array(
                'name'=>'Mozambique',
                'char-2'=>"MZ",
                'char-3'=>"MOZ",
                'code'=>"508"
            ),

            'MM'=>array(
                'name'=>'Myanmar',
                'char-2'=>"MM",
                'char-3'=>"MMR",
                'code'=>"104"
            ),

            'NA'=>array(
                'name'=>'Namibia',
                'char-2'=>"NA",
                'char-3'=>"NAM",
                'code'=>"516"
            ),

            'NR'=>array(
                'name'=>'Nauru',
                'char-2'=>"NR",
                'char-3'=>"NRU",
                'code'=>"520"
            ),

            'NP'=>array(
                'name'=>'Nepal',
                'char-2'=>"NP",
                'char-3'=>"NPL",
                'code'=>"524"
            ),

            'NL'=>array(
                'name'=>'Netherlands',
                'char-2'=>"NL",
                'char-3'=>"NLD",
                'code'=>"528"
            ),

            'NC'=>array(
                'name'=>'New Caledonia',
                'char-2'=>"NC",
                'char-3'=>"NCL",
                'code'=>"540"
            ),

            'NZ'=>array(
                'name'=>'New Zealand',
                'char-2'=>"NZ",
                'char-3'=>"NZL",
                'code'=>"554"
            ),

            'NI'=>array(
                'name'=>'Nicaragua',
                'char-2'=>"NI",
                'char-3'=>"NIC",
                'code'=>"558"
            ),

            'NE'=>array(
                'name'=>'Niger',
                'char-2'=>"NE",
                'char-3'=>"NER",
                'code'=>"562"
            ),

            'NG'=>array(
                'name'=>'Nigeria',
                'char-2'=>"NG",
                'char-3'=>"NGA",
                'code'=>"566"
            ),

            'NU'=>array(
                'name'=>'Niue',
                'char-2'=>"NU",
                'char-3'=>"NIU",
                'code'=>"570"
            ),

            'NF'=>array(
                'name'=>'Norfolk Island',
                'char-2'=>"NF",
                'char-3'=>"NFK",
                'code'=>"574"
            ),

            'MP'=>array(
                'name'=>'Northern Mariana Islands',
                'char-2'=>"MP",
                'char-3'=>"MNP",
                'code'=>"580"
            ),

            'NO'=>array(
                'name'=>'Norway',
                'char-2'=>"NO",
                'char-3'=>"NOR",
                'code'=>"578"
            ),

            'OM'=>array(
                'name'=>'Oman',
                'char-2'=>"OM",
                'char-3'=>"OMN",
                'code'=>"512"
            ),

            'PK'=>array(
                'name'=>'Pakistan',
                'char-2'=>"PK",
                'char-3'=>"PAK",
                'code'=>"586"
            ),

            'PW'=>array(
                'name'=>'Palau',
                'char-2'=>"PW",
                'char-3'=>"PLW",
                'code'=>"585"
            ),

            'PS'=>array(
                'name'=>'Palestine,
             State of',
                'char-2'=>"PS",
                'char-3'=>"PSE",
                'code'=>"275"
            ),

            'PA'=>array(
                'name'=>'Panama',
                'char-2'=>"PA",
                'char-3'=>"PAN",
                'code'=>"591"
            ),

            'PG'=>array(
                'name'=>'Papua New Guinea',
                'char-2'=>"PG",
                'char-3'=>"PNG",
                'code'=>"598"
            ),

            'PY'=>array(
                'name'=>'Paraguay',
                'char-2'=>"PY",
                'char-3'=>"PRY",
                'code'=>"600"
            ),

            'PE'=>array(
                'name'=>'Peru',
                'char-2'=>"PE",
                'char-3'=>"PER",
                'code'=>"604"
            ),

            'PH'=>array(
                'name'=>'Philippines',
                'char-2'=>"PH",
                'char-3'=>"PHL",
                'code'=>"608"
            ),

            'PN'=>array(
                'name'=>'Pitcairn',
                'char-2'=>"PN",
                'char-3'=>"PCN",
                'code'=>"612"
            ),

            'PL'=>array(
                'name'=>'Poland',
                'char-2'=>"PL",
                'char-3'=>"POL",
                'code'=>"616"
            ),

            'PT'=>array(
                'name'=>'Portugal',
                'char-2'=>"PT",
                'char-3'=>"PRT",
                'code'=>"620"
            ),

            'PR'=>array(
                'name'=>'Puerto Rico',
                'char-2'=>"PR",
                'char-3'=>"PRI",
                'code'=>"630"
            ),

            'QA'=>array(
                'name'=>'Qatar',
                'char-2'=>"QA",
                'char-3'=>"QAT",
                'code'=>"634"
            ),

            'RE'=>array(
                'name'=>'Réunion',
                'char-2'=>"RE",
                'char-3'=>"REU",
                'code'=>"638"
            ),

            'RO'=>array(
                'name'=>'Romania',
                'char-2'=>"RO",
                'char-3'=>"ROU",
                'code'=>"642"
            ),

            'RU'=>array(
                'name'=>'Russian Federation',
                'char-2'=>"RU",
                'char-3'=>"RUS",
                'code'=>"643"
            ),

            'RW'=>array(
                'name'=>'Rwanda',
                'char-2'=>"RW",
                'char-3'=>"RWA",
                'code'=>"646"
            ),

            'BL'=>array(
                'name'=>'Saint Barthélemy',
                'char-2'=>"BL",
                'char-3'=>"BLM",
                'code'=>"652"
            ),

            'SH'=>array(
                'name'=>'Saint Helena,
             Ascension and Tristan da Cunha',
                'char-2'=>"SH",
                'char-3'=>"SHN",
                'code'=>"654"
            ),

            'KN'=>array(
                'name'=>'Saint Kitts and Nevis',
                'char-2'=>"KN",
                'char-3'=>"KNA",
                'code'=>"659"
            ),

            'LC'=>array(
                'name'=>'Saint Lucia',
                'char-2'=>"LC",
                'char-3'=>"LCA",
                'code'=>"662"
            ),

            'MF'=>array(
                'name'=>'Saint Martin (French part)',
                'char-2'=>"MF",
                'char-3'=>"MAF",
                'code'=>"663"
            ),

            'PM'=>array(
                'name'=>'Saint Pierre and Miquelon',
                'char-2'=>"PM",
                'char-3'=>"SPM",
                'code'=>"666"
            ),

            'VC'=>array(
                'name'=>'Saint Vincent and the Grenadines',
                'char-2'=>"VC",
                'char-3'=>"VCT",
                'code'=>"670"
            ),

            'WS'=>array(
                'name'=>'Samoa',
                'char-2'=>"WS",
                'char-3'=>"WSM",
                'code'=>"882"
            ),

            'SM'=>array(
                'name'=>'San Marino',
                'char-2'=>"SM",
                'char-3'=>"SMR",
                'code'=>"674"
            ),

            'ST'=>array(
                'name'=>'Sao Tome and Principe',
                'char-2'=>"ST",
                'char-3'=>"STP",
                'code'=>"678"
            ),

            'SA'=>array(
                'name'=>'Saudi Arabia',
                'char-2'=>"SA",
                'char-3'=>"SAU",
                'code'=>"682"
            ),

            'SN'=>array(
                'name'=>'Senegal',
                'char-2'=>"SN",
                'char-3'=>"SEN",
                'code'=>"686"
            ),

            'RS'=>array(
                'name'=>'Serbia',
                'char-2'=>"RS",
                'char-3'=>"SRB",
                'code'=>"688"
            ),

            'SC'=>array(
                'name'=>'Seychelles',
                'char-2'=>"SC",
                'char-3'=>"SYC",
                'code'=>"690"
            ),

            'SL'=>array(
                'name'=>'Sierra Leone',
                'char-2'=>"SL",
                'char-3'=>"SLE",
                'code'=>"694"
            ),

            'SG'=>array(
                'name'=>'Singapore',
                'char-2'=>"SG",
                'char-3'=>"SGP",
                'code'=>"702"
            ),

            'SX'=>array(
                'name'=>'Sint Maarten (Dutch part)',
                'char-2'=>"SX",
                'char-3'=>"SXM",
                'code'=>"534"
            ),

            'SK'=>array(
                'name'=>'Slovakia',
                'char-2'=>"SK",
                'char-3'=>"SVK",
                'code'=>"703"
            ),

            'SI'=>array(
                'name'=>'Slovenia',
                'char-2'=>"SI",
                'char-3'=>"SVN",
                'code'=>"705"
            ),

            'SB'=>array(
                'name'=>'Solomon Islands',
                'char-2'=>"SB",
                'char-3'=>"SLB",
                'code'=>"090"
            ),

            'SO'=>array(
                'name'=>'Somalia',
                'char-2'=>"SO",
                'char-3'=>"SOM",
                'code'=>"706"
            ),

            'ZA'=>array(
                'name'=>'South Africa',
                'char-2'=>"ZA",
                'char-3'=>"ZAF",
                'code'=>"710"
            ),

            'GS'=>array(
                'name'=>'South Georgia and the South Sandwich Islands',
                'char-2'=>"GS",
                'char-3'=>"SGS",
                'code'=>"239"
            ),

            'SS'=>array(
                'name'=>'South Sudan',
                'char-2'=>"SS",
                'char-3'=>"SSD",
                'code'=>"728"
            ),

            'ES'=>array(
                'name'=>'Spain',
                'char-2'=>"ES",
                'char-3'=>"ESP",
                'code'=>"724"
            ),

            'LK'=>array(
                'name'=>'Sri Lanka',
                'char-2'=>"LK",
                'char-3'=>"LKA",
                'code'=>"144"
            ),

            'SD'=>array(
                'name'=>'Sudan',
                'char-2'=>"SD",
                'char-3'=>"SDN",
                'code'=>"729"
            ),

            'SR'=>array(
                'name'=>'Suriname',
                'char-2'=>"SR",
                'char-3'=>"SUR",
                'code'=>"740"
            ),

            'SJ'=>array(
                'name'=>'Svalbard and Jan Mayen',
                'char-2'=>"SJ",
                'char-3'=>"SJM",
                'code'=>"744"
            ),

            'SZ'=>array(
                'name'=>'Swaziland',
                'char-2'=>"SZ",
                'char-3'=>"SWZ",
                'code'=>"748"
            ),

            'SE'=>array(
                'name'=>'Sweden',
                'char-2'=>"SE",
                'char-3'=>"SWE",
                'code'=>"752"
            ),

            'CH'=>array(
                'name'=>'Switzerland',
                'char-2'=>"CH",
                'char-3'=>"CHE",
                'code'=>"756"
            ),

            'SY'=>array(
                'name'=>'Syrian Arab Republic',
                'char-2'=>"SY",
                'char-3'=>"SYR",
                'code'=>"760"
            ),

            'TW'=>array(
                'name'=>'Taiwan,
             Province of China',
                'char-2'=>"TW",
                'char-3'=>"TWN",
                'code'=>"158"
            ),

            'TJ'=>array(
                'name'=>'Tajikistan',
                'char-2'=>"TJ",
                'char-3'=>"TJK",
                'code'=>"762"
            ),

            'TZ'=>array(
                'name'=>'Tanzania,
             United Republic of',
                'char-2'=>"TZ",
                'char-3'=>"TZA",
                'code'=>"834"
            ),

            'TH'=>array(
                'name'=>'Thailand',
                'char-2'=>"TH",
                'char-3'=>"THA",
                'code'=>"764"
            ),

            'TL'=>array(
                'name'=>'Timor-Leste',
                'char-2'=>"TL",
                'char-3'=>"TLS",
                'code'=>"626"
            ),

            'TG'=>array(
                'name'=>'Togo',
                'char-2'=>"TG",
                'char-3'=>"TGO",
                'code'=>"768"
            ),

            'TK'=>array(
                'name'=>'Tokelau',
                'char-2'=>"TK",
                'char-3'=>"TKL",
                'code'=>"772"
            ),

            'TO'=>array(
                'name'=>'Tonga',
                'char-2'=>"TO",
                'char-3'=>"TON",
                'code'=>"776"
            ),

            'TT'=>array(
                'name'=>'Trinidad and Tobago',
                'char-2'=>"TT",
                'char-3'=>"TTO",
                'code'=>"780"
            ),

            'TN'=>array(
                'name'=>'Tunisia',
                'char-2'=>"TN",
                'char-3'=>"TUN",
                'code'=>"788"
            ),

            'TR'=>array(
                'name'=>'Turkey',
                'char-2'=>"TR",
                'char-3'=>"TUR",
                'code'=>"792"
            ),

            'TM'=>array(
                'name'=>'Turkmenistan',
                'char-2'=>"TM",
                'char-3'=>"TKM",
                'code'=>"795"
            ),

            'TC'=>array(
                'name'=>'Turks and Caicos Islands',
                'char-2'=>"TC",
                'char-3'=>"TCA",
                'code'=>"796"
            ),

            'TV'=>array(
                'name'=>'Tuvalu',
                'char-2'=>"TV",
                'char-3'=>"TUV",
                'code'=>"798"
            ),

            'UG'=>array(
                'name'=>'Uganda',
                'char-2'=>"UG",
                'char-3'=>"UGA",
                'code'=>"800"
            ),

            'UA'=>array(
                'name'=>'Ukraine',
                'char-2'=>"UA",
                'char-3'=>"UKR",
                'code'=>"804"
            ),

            'AE'=>array(
                'name'=>'United Arab Emirates',
                'char-2'=>"AE",
                'char-3'=>"ARE",
                'code'=>"784"
            ),

            'GB'=>array(
                'name'=>'United Kingdom',
                'char-2'=>"GB",
                'char-3'=>"GBR",
                'code'=>"826"
            ),

            'US'=>array(
                'name'=>'United States',
                'char-2'=>"US",
                'char-3'=>"USA",
                'code'=>"840"
            ),

            'UM'=>array(
                'name'=>'United States Minor Outlying Islands',
                'char-2'=>"UM",
                'char-3'=>"UMI",
                'code'=>"581"
            ),

            'UY'=>array(
                'name'=>'Uruguay',
                'char-2'=>"UY",
                'char-3'=>"URY",
                'code'=>"858"
            ),

            'UZ'=>array(
                'name'=>'Uzbekistan',
                'char-2'=>"UZ",
                'char-3'=>"UZB",
                'code'=>"860"
            ),

            'VU'=>array(
                'name'=>'Vanuatu',
                'char-2'=>"VU",
                'char-3'=>"VUT",
                'code'=>"548"
            ),

            'VE'=>array(
                'name'=>'Venezuela,
             Bolivarian Republic of',
                'char-2'=>"VE",
                'char-3'=>"VEN",
                'code'=>"862"
            ),

            'VN'=>array(
                'name'=>'Viet Nam',
                'char-2'=>"VN",
                'char-3'=>"VNM",
                'code'=>"704"
            ),

            'VG'=>array(
                'name'=>'Virgin Islands,
             British',
                'char-2'=>"VG",
                'char-3'=>"VGB",
                'code'=>"092"
            ),

            'VI'=>array(
                'name'=>'Virgin Islands,
             U.S.',
                'char-2'=>"VI",
                'char-3'=>"VIR",
                'code'=>"850"
            ),

            'WF'=>array(
                'name'=>'Wallis and Futuna',
                'char-2'=>"WF",
                'char-3'=>"WLF",
                'code'=>"876"
            ),

            'EH'=>array(
                'name'=>'Western Sahara',
                'char-2'=>"EH",
                'char-3'=>"ESH",
                'code'=>"732"
            ),

            'YE'=>array(
                'name'=>'Yemen',
                'char-2'=>"YE",
                'char-3'=>"YEM",
                'code'=>"887"
            ),

            'ZM'=>array(
                'name'=>'Zambia',
                'char-2'=>"ZM",
                'char-3'=>"ZMB",
                'code'=>"894"
            ),

            'ZW'=>array(
                'name'=>'Zimbabwe',
                'char-2'=>"ZW",
                'char-3'=>"ZWE",
                'code'=>"716"
            ),

        );
    }
}
if(!function_exists('country_iso_code')){
    function country_name_to_iso($name, $code_type = "char-2"){
        $countries = countries_iso();
        $_countries = array_combine(array_column($countries,"name"), array_column($countries, $code_type));
        if(!key_exists($name,$_countries))
            return "";
        return $_countries[$name];
    }
}

if( ! function_exists("timespan")) {
    /**
     * Carry an old function!
     *
     * @param int $seconds
     * @param string $nowTime
     * @param int $units
     * @return string
     * @throws Exception
     */
    function timespan(int $seconds = 1, string $nowTime = 'now', int $units = 1) {
        $time = new \CodeIgniter\I18n\Time();
        $now = new \CodeIgniter\I18n\Time(!empty($nowTime) ? $nowTime : "now");
        $time->setTimestamp($seconds);
        $diff = $time->difference($now);
        return $diff->humanize();
    }
}

if( ! function_exists("image")) {
    function image($image_path,$default_image, $width = 0, $height = 0) {
        if(!file_exists($image_path)) {
            return $default_image;
        }

        //Alternative image if file was not found
        if($image_path=="")
            $image_path = $default_image;

        //The new generated filename we want
        $fileinfo = pathinfo($image_path);
        $new_image_path = $fileinfo['dirname'] . '/' . $fileinfo['filename'] . '_' . $width . 'x' . $height . '.' . $fileinfo['extension'];

        if ((! file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
            \Config\Services::image()
                ->withFile($image_path)->fit($width, $height)
                ->save($new_image_path);
        }

        return $new_image_path;
    }
}
