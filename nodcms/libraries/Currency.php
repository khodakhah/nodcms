<?php

/**
 * Created by PhpStorm.
 * User: Shohre
 * Date: 9/7/2016
 * Time: 4:47 PM
 * Project: NodCMS
 */
class Currency
{
    // Acceptable formats: 1.234,56 - 1,234.56 - 1.234 - 1,234
    private $_currencyFormat = "1.234,56";
    private $options = array(
        "code"=>"EUR",
        "sign"=>"€",
        "html_sign"=>"&euro;",
        "add_before"=>"",
        "add_after"=>" EUR",
    );

    private $_smallPart = "";
    private $_smallPartDivider = ",";
    private $_bigPart = "";
    private $_bigPartDivider = ".";

    public function setFormat($value){
        if($value != "1.234,56" && $value != "1,234.56" && $value != "1.234" && $value != "1,234"){
            $value = $this->_currencyFormat;
        }
        if ($value == "1.234,56") {
            $this->_smallPartDivider = ",";
            $this->_bigPartDivider = ".";
        } elseif ($value == "1,234.56") {
            $this->_smallPartDivider = ".";
            $this->_bigPartDivider = ",";
        } elseif ($value == "1.234") {
            $this->_bigPartDivider = ".";
            $this->_smallPartDivider = "";
        } elseif ($value == "1,234") {
            $this->_bigPartDivider = ",";
            $this->_smallPartDivider = "";
        }
        $this->_currencyFormat = $value;
    }

    public function setOptions($value){
        if(!isset($value["code"]))
            $value["code"]=$this->options["code"];

        if(!isset($value["sign"]))
            $value["code"]=$this->options["sign"];

        if(!isset($value["html_sign"]))
            $value["code"]=$this->options["html_sign"];

        if(!isset($value["add_before"]))
            $value["code"]=$this->options["add_before"];

        if(!isset($value["add_after"]))
            $value["code"]=$this->options["add_after"];

        $this->options = $value;
    }

    // Convert number to currency string
    function format($value){
        if(!is_int($value) && !is_float($value))
            $value = floatval($value);
        $this->setParts($value);

        $format = $this->options["add_before"];
        $format .= $this->_bigPart;
        if($this->_smallPartDivider != "")
            $format .= $this->_smallPartDivider.$this->_smallPart;
        $format .= $this->options["add_after"];
        return $format;
    }

    // Convert number to currency string without sign
    function noSignFormat($value){
        if(!is_int($value) && !is_float($value))
            $value = floatval($value);
        $this->setParts($value);

        $format = $this->_bigPart;
        if($this->_smallPartDivider != "")
            $format .= $this->_smallPartDivider.$this->_smallPart;
        return $format;
    }

    private function setParts($value){
        $str_value = strval($value);
        $array_value = explode(".", $str_value);
        if(is_float($value) && isset($array_value[1])){
            $this->_smallPart = substr($array_value[1], 0, 2);
            $this->_bigPart = $array_value[0];
        }else{
            $this->_bigPart = $str_value;
            $this->_smallPart = "00";
        }
        $value_len = strlen($this->_bigPart);
        if($value_len > 3){
            $mod = $value_len%3;
            $bigPart = substr($this->_bigPart, 0, $mod);
            for ($i=$mod;$i<$value_len;$i+=3){
                $bigPart .= $this->_bigPartDivider;
                $bigPart .= substr($this->_bigPart, $i, 3);
            }
            $this->_bigPart = $bigPart;
        }
    }
}