<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Dec-19
 * Time: 12:53 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

/*
 * Form Validation functions
 */
trait NodCMSValidators
{
    // Phone number format function
    public function validPhone($text)
    {
        if($text=='' || preg_match('/^(([\+]|0|00)[1-9][0-9][\s\/\-]?)?[0-9]{1,12}?$/',$text)==TRUE){
            return TRUE;
        }else{
            $this->form_validation->set_message('validPhone', _l("The {field} field must be a valid phone number such as the bellow examples.",  $this).' (+12 1234567, 012 1234567, +123456789, 0123456789, +12-1234567, +12/1234567)');
            return false;
        }
    }

    // Validation time format function
    public function regexMatch24Hours($text)
    {
        if(preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/',$text)==TRUE){
            return TRUE;
        }else{
            $this->form_validation->set_message('regexMatch24Hours', _l("The {field} field is not in the correct time format.",  $this));
            return false;
        }
    }

    // Validation multi time format function
    public function formRulesMultiTime($text)
    {
        if(preg_match('/^([0-1][0-9]|2[0-4]):[0-5][0-9](-([0-1][0-9]|2[0-4]):[0-5][0-9])*$/',$text)==TRUE){
            return TRUE;
        }else{
            $this->form_validation->set_message('formRulesMultiTime', _l("The {field} field is not in the correct time format.",  $this));
            return false;
        }
    }

    // Validation multi date format function
    public function formRulesMultiDate($text)
    {
        if(preg_match('/^([0-9]{13})(\,[0-9]{13})*$/',$text)==TRUE){
            return TRUE;
        }else{
            $this->form_validation->set_message('formRulesMultiTime', _l("The {field} field is not in the correct time format.",  $this));
            return false;
        }
    }

    // Validation multi date&time format function
    public function formRulesMultiDateTime($text)
    {
        if($text=='')
            return true;
        if(preg_match('/^([0-9]{13}\-((([0-1][0-9]|2[0-4])\:[0-5][0-9])+|0))(\,[0-9]{13}\-((([0-1][0-9]|2[0-4])\:[0-5][0-9])+|0))*$/',$text)){
            return true;
        }else{
            $this->form_validation->set_message('formRulesMultiTime', _l("The {field} field is not in the correct time format.",  $this));
            return false;
        }
    }

    // Validation time format function
    public function formRulesTimeRange($text)
    {
        if(preg_match('/^([0-1][0-9]|2[0-4]):[0-5][0-9]-([0-1][0-9]|2[0-4]):[0-5][0-9]$/',$text)==TRUE){
            return TRUE;
        }else{
            $this->form_validation->set_message('formRulesTimeRange', _l("The {field} field is not in the correct time format.",  $this));
            return false;
        }
    }

    // Validation password format function
    public function formRulesPassword($value)
    {
        if ($value=='' || preg_match('/^.{6,18}$/', $value) == TRUE) {
            return TRUE;
        }else{
            $this->form_validation->set_message('formRulesPassword', _l("The {field} field must be at least 6 and cannot exceed 18 characters in length.", $this));
            return FALSE;
        }
    }

    // Validation date format function
    public function validDate($value)
    {
        if($value=='')
            return true;
//        $match1 = preg_match('/^([0-9]{4})[\/|\.](0[1-9]|1[0-2])[\/|\.](0[1-9]|[1-2][0-9]|3[0-1])$/',$value);
//        if($match1 == TRUE ||
//            checkdate(substr($value, 3, 2), substr($value, 0, 2), substr($value, 6, 4)) ||
//            checkdate(substr($value, 0, 2), substr($value, 3, 2), substr($value, 6, 4)))
        $d1 = DateTime::createFromFormat("d.m.Y", $value);
        $d2 = DateTime::createFromFormat("m/d/Y", $value);
        $d3 = DateTime::createFromFormat("Y-m-d", $value);
        if($d1 && $d1->format("d.m.Y") == $value
            || $d2 && $d2->format("m/d/Y") == $value
            || $d3 && $d3->format("Y-m-d") == $value)
            return true;
        else{
            $this->form_validation->set_message('validDate', _l("The {field} field is not in the correct date format.", $this));
            return false;
        }
    }

    // Validation name format function
    public function formRulesName($value)
    {
        if (preg_match('/[\'\/~`\!@#\$£%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\0-9]/', $value) == true) {
            $this->form_validation->set_message('formRulesName', _l("The {field} field must contain letters and spaces only.", $this));
            $errors[] = 'Name must contain letters and spaces only';
            return false;
        }else{
            return true;
        }
    }

    // Validation username type function
    public function validateUsernameType($value)
    {
        if (preg_match('/^[A-Za-z0-9_]*$/', $value) == FALSE) {
            $this->form_validation->set_message('validateUsernameType', _l("The {field} field must contain just English letters and underline only.", $this));
            return FALSE;
        }
        if ($value=='' || preg_match('/^.{3,18}$/', $value) == FALSE) {
            $this->form_validation->set_message('validateUsernameType', _l("The {field} field must be between 3 und 18 characters in length.", $this));
            return FALSE;
        }
        return TRUE;
    }

    // Validation username function (check unique with DB)
    public function validateUsername($value, $except_user_id = 0)
    {
        if (preg_match('/^[A-Za-z0-9_]*$/', $value) == FALSE) {
            $this->form_validation->set_message('validateUsername', _l("The {field} field must contain just English letters and underline only.", $this));
            return FALSE;
        }
        if ($value=='' || preg_match('/^.{3,18}$/', $value) == FALSE) {
            $this->form_validation->set_message('validateUsername', _l("The {field} field must be between 3 und 18 characters in length.", $this));
            return FALSE;
        }
        if($this->Nodcms_admin_model->checkUserUnique(array('username'=>$value), $except_user_id)){
            $this->form_validation->set_message('validateUsername', _l("The {field} field must be unique in the system.", $this));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Form validation
     *  - unique email
     *
     * @param $value
     * @param int $except_user_id
     * @return bool
     */
    public function emailUnique($value, $except_user_id = 0)
    {
        if($this->Public_model->isUnique($value, "users", "email", "user_id", $except_user_id)!=0){
            $this->form_validation->set_message('emailUnique', _l("The {field} field must be unique in the system.", $this));
            return false;
        }else{
            return true;
        }
    }

    // Validation email unique function
    public function validCaptcha($value)
    {
        if(!isset($_SESSION[$this->captcha_session_name])){
            $this->form_validation->set_message('validCaptcha', _l("Did't set find captcha session.", $this));
            return false;
        }
        if($_SESSION[$this->captcha_session_name]!=$value){
            $this->form_validation->set_message('validCaptcha', _l("The {field} field wasn't correct.", $this));
            return false;
        }
        return true;
    }

    // Validation field an a table unique function
    public function isUnique($value,$args)
    {
        $args = explode(',', $args);
        $args_count = count($args);
        if($args_count!=2 && $args_count!=4 && $args_count!=5){
            $this->form_validation->set_message('isUnique', _l("Missing some arguments for validation rules.", $this));
            return false;
        }
        $table = $args[0];
        $filed = $args[1];

        $except_field = isset($args[2])?$args[2]:null;
        $except_value = isset($args[3])?$args[3]:null;

        $conditions = isset($args[4])?$args[4]:null;

        if($value=="")
            return true;
        $count = $this->Public_model->isUnique($value,$table,$filed,$except_field, $except_value,$conditions);
        if($count==0){
            return true;
        }
        $this->form_validation->set_message('isUnique', _l("This {field} is already exists.", $this));
        return false;
    }

    public function validURI($value)
    {
        if($value=="")
            return true;
        if(preg_match('/^[a-z]+[a-z0-9\-\_]*$/', $value) == FALSE){
            $this->form_validation->set_message('validURI', _l("The {field} field must contain just English letters, dash and underline only. The first character must be English letter only.", $this));
            return false;
        }
        return true;
    }

    /**
     * Form validation callback
     *  - Input mask
     *
     * @param $value
     * @param $mask
     * @return bool
     */
    public function validMask($value, $mask)
    {
        if($value=="")
            return true;
        $replacement = array(
            '!'=>'\!',
            '@'=>'\@',
            '#'=>'\#',
            '-'=>'\-',
            '_'=>'\_',
            '$'=>'\$',
            '€'=>'\€',
            '%'=>'\%',
            '^'=>'\^',
            '&'=>'\&',
            '*'=>'\*',
            '('=>'\(',
            ')'=>'\)',
            '/'=>'\/',
            '['=>'\[',
            ']'=>'\]',
            ','=>'\,',
            '.'=>'\.',
            ';'=>'\;',
            ':'=>'\:',
            '9'=>'[0-9]',
        );
        $patter = str_replace(array_keys($replacement),$replacement,$mask);
        if(!preg_match('/^'.$patter.'$/', $value)){
            $this->form_validation->set_message('validMask', _l("The {field} has not contain correct value.", $this));
            return false;
        }
        return true;
    }

    /**
     * Validate a value with multi line email addresses
     *
     * @param $value
     * @return bool
     */
    public function validEmails($value)
    {
        if($value=="")
            return true;
        if(!preg_match('/^([A-Za-z0-9]+([\_\.\-][A-Za-z0-9]+)*[\@][A-Za-z0-9]+([\_\.\-][A-Za-z0-9]+)*\.[A-Za-z0-9]+)(\n[A-Za-z0-9]+([\_\.\-][A-Za-z0-9]+)*[\@][A-Za-z0-9]+([\_\.\-][A-Za-z0-9]+)*\.[A-Za-z0-9]+)*\n*/', $value)){
            $this->form_validation->set_message('validEmails', _l("The {field} has not contain valid emails.", $this));
            return false;
        }
        return true;
    }

    /**
     * Form validation of currency format (float with the 2 fix)
     *
     * @param $value
     * @return bool
     */
    public function validCurrency($value)
    {
        if($value=="")
            return true;
        if(!preg_match('/^[0-9]+(\.[0-9]{2})?$/', $value)){
            $this->form_validation->set_message('validCurrency', _l("The {field} has not contain valid currency.", $this));
            return false;
        }
        return true;
    }

    /**
     * Form validations of range of number
     *
     * @param $value
     * @param $args
     * @return bool
     */
    public function validRange($value,$args)
    {
        if($value=="")
            return true;

        $args = explode(',', $args);
        $args_count = count($args);
        if($args_count!=2 ){
            $this->form_validation->set_message('validRange', _l("Missing some arguments for validation rules.", $this));
            return false;
        }
        $min = $args[0];
        $max = $args[1];

        if(!preg_match('/^[0-9]+\-[0-9]+$/', $value)){
            $this->form_validation->set_message('validRange', _l("The {field} has not contain valid number range.", $this));
            return false;
        }

        $numbers = explode('-', $value);
        if($numbers[0]<$min || $numbers[0]>$max){
            $this->form_validation->set_message('validRange', _l("The minimum selected of {field} is out of range.", $this));
            return false;
        }
        if($numbers[1]<$min || $numbers[1]>$max){
            $this->form_validation->set_message('validRange', _l("The maximum selected of {field} is out of range.", $this));
            return false;
        }
        if($numbers[0]>$numbers[1]){
            $this->form_validation->set_message('validRange', _l("The minimum selected of {field} is bigger than minimum.", $this));
            return false;
        }

        return true;
    }

    /**
     * Form validation of a range date
     *
     * @param $value
     * @return bool
     */
    public function validRangeDate($value)
    {
        if($value=="")
            return true;

        if(!preg_match('/^[0-9]{10}000\,[0-9]{10}000$/', $value)){
            $this->form_validation->set_message('validRangeDate', _l("The {field} has not contain valid date range.", $this));
            return false;
        }

        return true;
    }

    /**
     * Form validations of a valid currency 3-digit code
     *
     * @param $value
     * @return bool
     */
    public function validCurrencyCode($value)
    {
        if($value=="")
            return true;

        if(!preg_match('/^[A-Z]{3}$/', $value)){
            $this->form_validation->set_message('validCurrencyCode', _l("The {field} has not contain valid currency code.", $this));
            return false;
        }

        return true;
    }

    /**
     * Form validation of a list of numbers
     *
     * @param $value
     * @return bool
     */
    public function validNumberList($value)
    {
        if($value=="")
            return true;
        // Format validation
        if(!preg_match('/^[1-9][0-9]*(\,[1-9][0-9]*)*$/', $value)){
            $this->form_validation->set_message('validNumberList', _l("The {field} has not contain valid values.", $this));
            return false;
        }

        return true;
    }

    /**
     * Form validation of google map iframe
     *
     * @param $value
     * @return bool
     */
    public function validGoogleMapIframe($value)
    {
        if($value=="")
            return true;
        // Format validation
        if(!preg_match('/^\<iframe\s([\w]+\=\"[^\s]+\"[\s])*src\=\"[^\s]+\"([\s][\w]+\=\"[^\s]+\")*([\s][\w]+)*\>\<\/iframe\>$/', $value)){
            $this->form_validation->set_message('validGoogleMapIframe', _l("The {field} has not contain valid values.", $this));
            return false;
        }

        return true;
    }

    /**
     * Form validation of existence a list of numbers in a table of database
     *  - This method is using a Model class to check the values existence
     *  - You should pass two argument in the form validation callback function:
     *      1. Model class: The name of a model class that already loaded
     *      2. Model method: A method of the class that will accept a the $value as text and return a list if finds numbers
     *          just like $value but as an array
     *
     * @param $value
     * @param $args
     * @return bool
     */
    public function validNumberListExists($value, $args)
    {
        if($value=="")
            return true;

        $args = explode(',', $args);
        $args_count = count($args);
        if($args_count!=2 ){
            $this->form_validation->set_message('validNumberListExists', _l("Missing some arguments for 'validNumberListExists' validation rules.", $this));
            return false;
        }
        $model_class = $args[0];
        $model_method = $args[1];

        // Format validation
        if(!preg_match('/^[1-9][0-9]*(\,[1-9][0-9]*)*$/', $value)){
            $this->form_validation->set_message('validNumberListExists', _l("The {field} has not contain valid values.", $this));
            return false;
        }

        $values = explode(',', $value);
        $result = call_user_func_array(array($this->$model_class, $model_method),array($value));
        $array_diff = array_diff($values,$result);
        if(count($array_diff)!=0){
            $this->form_validation->set_message('validNumberListExists', str_replace("{diff}", join(',',$array_diff),_l("The values '{diff}' of {field} are not exists.", $this)));
            return false;
        }

        return true;
    }

    /**
     * Form validation of the Terms & Conditions accept
     *
     * @param $value
     * @return bool
     */
    public function acceptTermsAndConditions($value)
    {
        if($value!=1){
            $this->form_validation->set_message('acceptTermsAndConditions', _l("Accept the {field} is required.", $this));
            return false;
        }

        return true;
    }

    /**
     * Form validation of making required field if other field is filled
     *
     * @param $value
     * @param $args
     * @return bool
     */
    public function validateRequiredIf($value, $args)
    {
        $args = explode(',', $args);
        $args_count = count($args);
        if($args_count!=2){
            $this->form_validation->set_message('validateRequiredIf', "Missing some arguments for 'validateRequiredIf' validation rules.");
            return false;
        }

        $field = $args[0];
        $field_value = $args[1];

        if(!isset($_POST[$field]) || $_POST[$field] != $field_value || $value!="" || $value!=NULL){
            return true;
        }
        $this->form_validation->set_message('validateRequiredIf', _l("The {field} is required.", $this));
        return false;
    }

    /**
     * Form validations of google invisible reCaptcha
     *
     * @param $value
     * @return bool
     */
    public function validGoogleInvisibleReCaptcha($value)
    {
        if($value=="")
            return true;

        if(!isset($this->settings["google_captcha_secret_key"]) || $this->settings["google_captcha_secret_key"]==""){
            $this->form_validation->set_message('validGoogleInvisibleReCaptcha', _l("Google captcha secret key has not set.", $this));
            return false;
        }
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $post_data = array(
            'secret'=>$this->settings["google_captcha_secret_key"],
            'response'=>$value,
        );

        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0' );

        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        $data = curl_exec( $ch );
        curl_getinfo( $ch,CURLINFO_HTTP_CODE );
        curl_close( $ch );

        $response = json_decode( $data, true );

        // * Check response format
        if(!isset($response['success'])){
            $this->form_validation->set_message('validGoogleInvisibleReCaptcha', _l("Invalid response form google.", $this));
            return false;
        }

        // * Check success status
        if($response['success']==false){
            $message = str_replace("{error_code}",$response['error-codes'],_l("The google response for the reCaptcha was false with the error code: {error_code}.", $this));
            $this->form_validation->set_message('validGoogleInvisibleReCaptcha', $message);
            return false;
        }
        // * Check hostname
        if($response['hostname']!=CONFIG_BASE_URL){
            $search = array('{response_hostname}','{current_hostname}');
            $replace = array($response['hostname'],CONFIG_BASE_URL);
            $message = str_replace($search, $replace,_l("The solve hostname({response_hostname}) shall be equal to {current_hostname}.", $this));
            $this->form_validation->set_message('validGoogleInvisibleReCaptcha', $message);
            return false;
        }

        return true;
    }

    /**
     * Form validation not equal to the value or list
     *
     * @param $value
     * @param $param
     * @return bool
     */
    public function validateNotEqual($value, $param)
    {
        if($value=="" || !in_array($value, explode(',', $param)))
            return true;

        $this->form_validation->set_message('validateNotEqual', _l("The content of {field} is not allowed.", $this));
        return false;
    }

    /**
     * Form validation: check a file path
     *
     * @param $value
     * @return bool
     */
    public function validateFileExists($value)
    {
        if($value=="")
            return true;

        if(file_exists(getcwd()."\\$value"))
            return true;

        $this->form_validation->set_message('validateFileExists', _l("The entered path in {field} is not exists.", $this));
        return false;
    }

    /**
     * Form validation: validate a database name
     *
     * @param $value
     * @return bool
     */
    public function validDatabaseName($value)
    {
        if (preg_match('/^[A-Za-z0-9_]*$/', $value) == FALSE) {
            $this->form_validation->set_message('validDatabaseName', _l("The {field} field must contain just English letters and underline only.", $this));
            return false;
        }
        return true;
    }

    public function validHostName($value)
    {
        if (preg_match('/^[A-Za-z0-9_]*$/', $value) == FALSE) {
            $this->form_validation->set_message('validHostName', _l("The {field} field must contain just English letters and underline only.", $this));
            return false;
        }
        return true;
    }
}