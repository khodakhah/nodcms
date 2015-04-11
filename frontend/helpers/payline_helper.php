<?php
/**
 * Created by Mojtaba Khodakhah
 * Subject: Homework
 * Date: 11/4/13
 * Connecet: khodakhah.mojtaba@yahoo.com
 */
function get_payline_api(){
    return 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567';
}
function get_payline_url_send(){
    return 'http://payline.ir/payment-test/gateway-send';
}
function get_payline_url_second(){
    return 'http://payline.ir/payment-test/gateway-result-second';
}
function get_payline_url_gateway(){
    return 'http://payline.ir/payment-test/gateway-';
}

function send($amount,$redirect,$api){
//    $api = get_payline_api();
    $url_send = get_payline_url_send();
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url_send);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}
function get($trans_id,$id_get,$api){
//    $api = get_payline_api();
    $url_second = get_payline_url_second();
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url_second);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&id_get=$id_get&trans_id=$trans_id");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}