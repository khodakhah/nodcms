<?php
/**
 * Created by Mojtaba Khodakhah
 * Subject: Homework
 * Date: 11/4/13
 * Connecet: khodakhah.mojtaba@yahoo.com
 */
function substr_string($text,$start = 0,$count = 10,$end_text=" ..."){
    $explode = explode(" ",strip_tags($text));
    return implode(" ",array_splice($explode,$start,$count)).(count($explode)>$count?$end_text:"");
}