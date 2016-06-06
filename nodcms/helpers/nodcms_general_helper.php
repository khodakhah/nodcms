<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/15/2015
 * Time: 8:00 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
if ( ! function_exists('_l')){
    function _l($label, $obj)
    {
        $return = $obj->lang->line($label);
        if ($return){
            return $return;
        }else{
            if($obj->router->fetch_class()=="Nodcms_general"){
                if(!in_array($label,$obj->langArray)){
                    $file = getcwd()."/nodcms/language/".$_SESSION["language"]["language_name"].'/'.$_SESSION["language"]["code"]."_lang.php";
                    if(file_exists($file)){
                        $current = file_get_contents($file);
                        $current .= "\n";
                        $current .= '$lang["'.$label.'"] = "'.$label.'";';
                        file_put_contents($file, $current);
                        $obj->langArray[$label] = $label;
                    }
                }
            }elseif($obj->router->fetch_class()=="Nodcms_general_admin"){
                if(!in_array($label,$obj->langArray)){
                    $file = getcwd()."/nodcms/language/".$_SESSION["language"]["language_name"]."/backend_lang.php";
                    if(file_exists($file)){
                        $current = file_get_contents($file);
                        $current .= "\n";
                        $current .= '$lang["'.$label.'"] = "'.$label.'";';
                        file_put_contents($file, $current);
                        $obj->langArray[$label] = $label;
                    }
                }
            }
            return $label;
        }
    }
}
if ( ! function_exists('substr_string')){
    function substr_string($text,$start = 0,$count = 10,$end_text=" ..."){
        $explode = explode(" ",strip_tags($text));
        return implode(" ",array_splice($explode,$start,$count)).(count($explode)>$count?$end_text:"");
    }
}
if ( ! function_exists('my_int_date')){
    function my_int_date($date)
    {
        return date("d.m.Y",$date);
    }
}
if ( ! function_exists('my_int_fullDate')){
    function my_int_fullDate($time){
        return date("j F Y | g:i A",$time);
    }
}
if ( ! function_exists('my_int_justDate')){
    function my_int_justDate($time){
        return date("j F Y",$time);
    }
}
if ( ! function_exists('my_int_justTime')){
    function my_int_justTime($time){
        return date("g:i A",$time);
    }
}

if ( ! function_exists('frontendStatisticCalc')){
    function frontendStatisticCalc($obj,$language){
        //        Statistic
        $visitorMinDate = $obj->NodCMS_general_model->getMinDateVisitor();
        if($visitorMinDate!=0){
            $visitorMaxDate = $obj->NodCMS_general_model->getMaxDateVisitor();
            $times = $visitorMaxDate - $visitorMinDate;
            if($times >= 86400){
                $days = round($times / 86400);
                $visitorMinDate = (mktime(0,0,0,date("m",$visitorMinDate),(date("j",$visitorMinDate)),date("Y",$visitorMinDate)));
                for($d=0;$d<$days;$d++){
                    $maxTime = (mktime(0,0,0,date("m",$visitorMinDate),(date("j",$visitorMinDate)),date("Y",$visitorMinDate))) + 86400;
                    $obj->NodCMS_general_model->updateStatistic(strtotime(date("d.m.Y",$visitorMinDate)),$maxTime);
                    $visitorMinDate = $maxTime;
                }
            }
        }
        $visitor = $obj->NodCMS_general_model->getDuplicateVisitor(session_id(),$_SERVER["REQUEST_URI"]);
        if(count($visitor) == 0){
            // Get IP address
            if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            }

            $ip = filter_var($ip, FILTER_VALIDATE_IP);
            $ip = ($ip === false) ? '0.0.0.0' : $ip;
            $obj->load->library('spyc');
            $visitor_data = array(
                "session_id"=>session_id(),
                "user_id"=>isset($_SESSION["user"]["user_id"])?$_SESSION["user"]["user_id"]:0,
                "created_date"=>time(),
                "updated_date"=>time(),
                "user_agent"=>Spyc::YAMLDump($_SERVER["HTTP_USER_AGENT"]),
                "user_ip"=>$ip,
                "language_id"=>$language["language_id"],
                "language_code"=>$language["code"],
                "referrer"=>isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"",
                "request_url"=>$_SERVER['REQUEST_URI'],
                "count_view"=>1
            );
            $obj->NodCMS_general_model->insertVisitors($visitor_data);
        }else{
            $visitor = @reset($visitor);
            $visitor_data = array(
                "user_id"=>isset($_SESSION["user"]["user_id"])?$_SESSION["user"]["user_id"]:0,
                "updated_date"=>time(),
                "count_view"=>$visitor["count_view"]+1
            );
            $obj->NodCMS_general_model->updateDuplicateVisitor(session_id(),$_SERVER["REQUEST_URI"],$visitor_data);
        }
    }
}