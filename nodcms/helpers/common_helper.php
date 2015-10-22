<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @access	public
 * @param	string
 * @return	string
 */	

date_default_timezone_set('Asia/Ho_Chi_Minh'); // SET TIMEZONE

function notAdminRedirect($userdata)
{
	if ($userdata['group']!=1) {
		redirect("/cpanel/index/");
	}		
}
function seo_url($str) 
{
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	
	$str = preg_replace("/(đ)/", 'd', $str);
	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	
	return url_title($str, 'dash', TRUE); // CI replace &,/ ... 
	
	// usage: echo seo_url("đây là hàm dùng để... &--/\|") ;
}

function split_words($string, $nb_caracs, $separator)
{
	    $string = strip_tags(html_entity_decode($string));
	    if( strlen($string) <= $nb_caracs ){
	        $final_string = $string;
	    } else {
	        $final_string = "";
	        $words = explode(" ", $string);
	        foreach( $words as $value ){
	            if( strlen($final_string . " " . $value) < $nb_caracs ){
	                if( !empty($final_string) ) $final_string .= " ";
	                $final_string .= $value;
	            } else {
	                break;
	            }
	        }
	        $final_string .= $separator;
	    }
	    return $final_string;
}

/*function VN2none($value)
{
    #---------------------------------a^
    $value = str_replace("ấ", "a", $value);
    $value = str_replace("ầ", "a", $value);
    $value = str_replace("ẩ", "a", $value);
    $value = str_replace("ẫ", "a", $value);
    $value = str_replace("ậ", "a", $value);
    #---------------------------------A^
    $value = str_replace("Ấ", "A", $value);
    $value = str_replace("Ầ", "A", $value);
    $value = str_replace("Ẩ", "A", $value);
    $value = str_replace("Ẫ", "A", $value);
    $value = str_replace("Ậ", "A", $value);
    #---------------------------------a(
    $value = str_replace("ắ", "a", $value);
    $value = str_replace("ằ", "a", $value);
    $value = str_replace("ẳ", "a", $value);
    $value = str_replace("ẵ", "a", $value);
    $value = str_replace("ặ", "a", $value);
    #---------------------------------A(
    $value = str_replace("Ắ", "A", $value);
    $value = str_replace("Ằ", "A", $value);
    $value = str_replace("Ẳ", "A", $value);
    $value = str_replace("Ẵ", "A", $value);
    $value = str_replace("Ặ", "A", $value);
    #---------------------------------a
    $value = str_replace("á", "a", $value);
    $value = str_replace("à", "a", $value);
    $value = str_replace("ả", "a", $value);
    $value = str_replace("ã", "a", $value);
    $value = str_replace("ạ", "a", $value);
    $value = str_replace("â", "a", $value);
    $value = str_replace("ă", "a", $value);
    #---------------------------------A
    $value = str_replace("Á", "A", $value);
    $value = str_replace("À", "A", $value);
    $value = str_replace("Ả", "A", $value);
    $value = str_replace("Ã", "A", $value);
    $value = str_replace("Ạ", "A", $value);
    $value = str_replace("Â", "A", $value);
    $value = str_replace("Ă", "A", $value);
    #---------------------------------e^
    $value = str_replace("ế", "e", $value);
    $value = str_replace("ề", "e", $value);
    $value = str_replace("ể", "e", $value);
    $value = str_replace("ễ", "e", $value);
    $value = str_replace("ệ", "e", $value);
    #---------------------------------E^
    $value = str_replace("Ế", "E", $value);
    $value = str_replace("Ề", "E", $value);
    $value = str_replace("Ể", "E", $value);
    $value = str_replace("Ễ", "E", $value);
    $value = str_replace("Ệ", "E", $value);
    #---------------------------------e
    $value = str_replace("é", "e", $value);
    $value = str_replace("è", "e", $value);
    $value = str_replace("ẻ", "e", $value);
    $value = str_replace("ẽ", "e", $value);
    $value = str_replace("ẹ", "e", $value);
    $value = str_replace("ê", "e", $value);
    #---------------------------------E
    $value = str_replace("É", "E", $value);
    $value = str_replace("È", "E", $value);
    $value = str_replace("Ẻ", "E", $value);
    $value = str_replace("Ẽ", "E", $value);
    $value = str_replace("Ẹ", "E", $value);
    $value = str_replace("Ê", "E", $value);
    #---------------------------------i
    $value = str_replace("í", "i", $value);
    $value = str_replace("ì", "i", $value);
    $value = str_replace("ỉ", "i", $value);
    $value = str_replace("ĩ", "i", $value);
    $value = str_replace("ị", "i", $value);
    #---------------------------------I
    $value = str_replace("Í", "I", $value);
    $value = str_replace("Ì", "I", $value);
    $value = str_replace("Ỉ", "I", $value);
    $value = str_replace("Ĩ", "I", $value);
    $value = str_replace("Ị", "I", $value);
    #---------------------------------o^
    $value = str_replace("ố", "o", $value);
    $value = str_replace("ồ", "o", $value);
    $value = str_replace("ổ", "o", $value);
    $value = str_replace("ỗ", "o", $value);
    $value = str_replace("ộ", "o", $value);
    #---------------------------------O^
    $value = str_replace("Ố", "O", $value);
    $value = str_replace("Ồ", "O", $value);
    $value = str_replace("Ổ", "O", $value);
    $value = str_replace("Ô", "O", $value);
    $value = str_replace("Ộ", "O", $value);
    #---------------------------------o*
    $value = str_replace("ớ", "o", $value);
    $value = str_replace("ờ", "o", $value);
    $value = str_replace("ở", "o", $value);
    $value = str_replace("ỡ", "o", $value);
    $value = str_replace("ợ", "o", $value);
    #---------------------------------O*
    $value = str_replace("Ớ", "O", $value);
    $value = str_replace("Ờ", "O", $value);
    $value = str_replace("Ở", "O", $value);
    $value = str_replace("Ỡ", "O", $value);
    $value = str_replace("Ợ", "O", $value);
    #---------------------------------u*
    $value = str_replace("ứ", "u", $value);
    $value = str_replace("ừ", "u", $value);
    $value = str_replace("ử", "u", $value);
    $value = str_replace("ữ", "u", $value);
    $value = str_replace("ự", "u", $value);
    #---------------------------------U*
    $value = str_replace("Ứ", "U", $value);
    $value = str_replace("Ừ", "U", $value);
    $value = str_replace("Ử", "U", $value);
    $value = str_replace("Ữ", "U", $value);
    $value = str_replace("Ự", "U", $value);
    #---------------------------------y
    $value = str_replace("ý", "y", $value);
    $value = str_replace("ỳ", "y", $value);
    $value = str_replace("ỷ", "y", $value);
    $value = str_replace("ỹ", "y", $value);
    $value = str_replace("ỵ", "y", $value);
    #---------------------------------Y
    $value = str_replace("Ý", "Y", $value);
    $value = str_replace("Ỳ", "Y", $value);
    $value = str_replace("Ỷ", "Y", $value);
    $value = str_replace("Ỹ", "Y", $value);
    $value = str_replace("Ỵ", "Y", $value);
    #---------------------------------DD
    $value = str_replace("Đ", "D", $value);
    $value = str_replace("Đ", "D", $value);
    $value = str_replace("đ", "d", $value);
    #---------------------------------o
    $value = str_replace("ó", "o", $value);
    $value = str_replace("ò", "o", $value);
    $value = str_replace("ỏ", "o", $value);
    $value = str_replace("õ", "o", $value);
    $value = str_replace("ọ", "o", $value);
    $value = str_replace("ô", "o", $value);
    $value = str_replace("ơ", "o", $value);
    #---------------------------------O
    $value = str_replace("Ó", "O", $value);
    $value = str_replace("Ò", "O", $value);
    $value = str_replace("Ỏ", "O", $value);
    $value = str_replace("Õ", "O", $value);
    $value = str_replace("Ọ", "O", $value);
    $value = str_replace("Ô", "O", $value);
    $value = str_replace("Ơ", "O", $value);
    #---------------------------------u
    $value = str_replace("ú", "u", $value);
    $value = str_replace("ù", "u", $value);
    $value = str_replace("ủ", "u", $value);
    $value = str_replace("ũ", "u", $value);
    $value = str_replace("ụ", "u", $value);
    $value = str_replace("ư", "u", $value);
    #---------------------------------U
    $value = str_replace("Ú", "U", $value);
    $value = str_replace("Ù", "U", $value);
    $value = str_replace("Ủ", "U", $value);
    $value = str_replace("Ũ", "U", $value);
    $value = str_replace("Ụ", "U", $value);
    $value = str_replace("Ư", "U", $value);
    #---------------------------------
   	
    return url_title($value, 'dash', TRUE);
}
*/

//Substring utf-8 strings!
function substring($str,$from,$len)
{
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $from .'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'. $len .'}).*#s','$1', $str);
	// usage: echo substring("welcome to hell",5,5);
}

function utf8_str_replace($s, $r, $str)
{
	if(!is_array($s))
	{
		$s = '!'.preg_quote($s,'!').'!u';
	}
	else
	{
		foreach ($s as $k => $v) {
			$s[$k] = '!'.preg_quote($v).'!u';
		}
	}
	return preg_replace($s, $r, $str);
}

function cut_string($str, $len, $more)
{
	if ($str=="" || $str==NULL) return $str;
	if (is_array($str)) return $str;
	$str = trim($str);
	if (strlen($str) <= $len) return $str;
	$str = substr($str,0,$len);
	if ($str != "")
	{
		if (!substr_count($str," "))
		{
	    	if ($more) $str .= " ...";
	        return $str;
	    }
	    while(strlen($str) && ($str[strlen($str)-1] != " "))
		{
	    	$str = substr($str,0,-1);
	    }
	    $str = substr($str,0,-1);
	    if ($more) $str .= " ...";
	}
	return $str;
	// usage: echo cut_string("welcome to hell",10,1);    ---> output: welcome to ... 
}


// Date manipulation --------------------

function mydate($date) 
{
	return strftime("%m-%d-%Y", strtotime($date)); // input:  2009-12-01 19:31:38   --> output 01-12-2009
}
function myfull_date($date) 
{
	return strftime("%d-%m-%Y %H:%M", strtotime($date)); // output 01-12-2009 19:31
//	return strftime("%A, %d-%m-%Y %I:%M %p", strtotime($date)); // output Tuesday, 01-12-2009 9:31 AM
}
function my_int_date($date) 
{
	return date("Y-m-d",$date); // input:  548751255  --> output 01-12-2009
}
function myfull_int_date($date) 
{
	// Disable timezone in php.ini then set in code http://www.php.net/manual/en/timezones.europe.php
	//date_default_timezone_set('Europe/Copenhagen'); Danish
	//date_default_timezone_set('Asia/Ho_Chi_Minh');
	//return date("d-m-Y H:i",$date); // output 01-12-2009 19:31
	return date("l, F d, Y",$date); // output Saturday, November 6, 2010
}

// End date -----------------------------


function get_thumb_name($file)
{
	$parts 	= explode('.', $file);
	$ext 	= array_pop($parts);
	
	return implode('.', $parts).'_thumb.'.$ext;
}

function get_med_name($file)
{
	$parts 	= explode('.', $file);
	$ext 	= array_pop($parts);
	
	return implode('.', $parts).'_med.'.$ext;
}

function my_url_title($value)
{
	return url_title($value, 'dash', TRUE);
}

function clean_referrer($referrer)
{
    if ($referrer)
    {
	    preg_match("/[\&\?]q=([^&]*)/", $referrer, $matches);

        if (isset($matches[1]))
        {
            $search_query = rawurldecode($matches[1]);
            $search_query = str_replace("+", " ", $search_query);
            return "Search engine query for ".$search_query;

        }
        else
        {
            return $referrer;
        }
    }
    else
    {
        return $referrer;
    }

}
// CUT STRING with PLAIN FORMAT
function substr_plaintext($content, $limit)
{
    $contentTemp = explode(' ', $content);
    if (count($contentTemp) > $limit)
    {
        $content = implode(' ', array_slice($contentTemp, 0, $limit));
    }
    return $content;
}
// CUT STRING with HTML FORMAT
function substr_htmltext( $text, $len=200 ) 
{
          if( (mb_strlen($text) > $len) ) {
     
              $whitespaceposition = mb_strpos($text," ",$len)-1;
     
              if( $whitespaceposition > 0 ) {
                  $chars = count_chars(mb_substr($text, 0, ($whitespaceposition+1)), 1);
                  if ($chars[ord('<')] > $chars[ord('>')])
                      $whitespaceposition = mb_strpos($text,">",$whitespaceposition)-1;
                  $text = mb_substr($text, 0, ($whitespaceposition+1));
              }
     
              // close unclosed html tags
              if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) {
     
                  if( !empty($aBuffer[1]) ) {
     
                      preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
     
                      if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
     
                          foreach( $aBuffer[1] as $index => $tag ) {
     
                              if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
                                  $text .= '</'.$tag.'>';
                          }
                      }
                  }
              }
          }
          return $text;
}


// SHOW THUMB IMAGE WITH TIMTHUMB.PHP
function show_thumb_img($imgname, $location='/upload/product',$link_modal=false, $width='168', $height='99', $effect=1)
{
	$CI =& get_instance();
	$result="<img src='".base_url()."assets/auto_thumbnail/timthumb.php?src=".base_url().$location."/".$imgname."&h=".$height."&w=".$width."&zc=".$effect."' />";
	if($link_modal)
	{
		$result="<a href='".base_url().$location."/".$imgname."' rel='modal' title=\"".$CI->lang->line('tips_click_show_image')."\">".$result."</a>";
	}
	echo $result;
}