<?php
/**
 * Created by Mojtaba Khodakhah
 * YahooID: khodakhah.mojtaba
 * GoogleID: khodakhah.mojtaba
 * Date: 8/5/14
 * Time: 1:32 AM
 * Mobile: +43 688 6011 4434.
 */
function mkh_int_fullDate($time){
    return date("j F Y | g:i A",$time);
}
function mkh_int_justDate($time){
    return date("j F Y",$time);
}
function mkh_int_justTime($time){
    return date("g:i A",$time);
}
function mkh_int_formalDate($time){
    return date("d-m-Y",$time);
}