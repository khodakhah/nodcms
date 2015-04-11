<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency
	{
		private $code;
  		private $currencies = array();
  
  	public function __construct() {
		
	require('backend/config/database.php');
	 $con = mysql_connect($db['default']['hostname'], $db['default']['username'] , $db['default']['password']);
     mysql_select_db($db['default']['database'], $con);
	 mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $con);
     $SQL = 'SELECT * FROM currency';
     $data =  mysql_query($SQL, $con);
     while ($result = mysql_fetch_assoc($data)) {
	
      		$this->currencies[$result['code']] = array(
        		'currency_id'   => $result['currency_id'],
        		'title'         => $result['title'],
        		'symbol_left'   => $result['symbol_left'],
        		'symbol_right'  => ($result['symbol_right']),
        		'decimal_place' => $result['decimal_place'],
        		'value'         => $result['value']
      		); 
    	} 
		
		if (isset($_GET['currency']) && (array_key_exists($_GET['currency'], $this->currencies))) {
			$this->set($_GET['currency']);
    	} elseif ((isset($_SESSION['currency'])) && (array_key_exists($_SESSION['currency'], $this->currencies))) {
      		$this->set($_SESSION['currency']);
    	} elseif ((isset($_COOKIE['currency'])) && (array_key_exists($_COOKIE['currency'], $this->currencies))) {
      		$this->set($_COOKIE['currency']);
    	} else {
      		$this->set('RLS');
    	}
		
  	}
	
  	public function set($currency) {
    	$this->code = $currency;

    	if (!isset($_SESSION['currency']) || ($_SESSION['currency'] != $currency)) {
      		$_SESSION['currency'] = $currency;
    	}

    	if (!isset($_COOKIE['currency']) || ($_COOKIE['currency'] != $currency)) {
	  		setcookie('currency', $currency, time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
    	}
  	}

  	public function format($number, $currency = '', $value = '', $format = true) {

		if ($currency && $this->has($currency)) {
      		$symbol_left   = $this->currencies[$currency]['symbol_left'];
      		$symbol_right  = $this->currencies[$currency]['symbol_right'];
      		$decimal_place = $this->currencies[$currency]['decimal_place'];
    	} else {
      		$symbol_left   = $this->currencies[$this->code]['symbol_left'];
      		$symbol_right  = $this->currencies[$this->code]['symbol_right'];
      		$decimal_place = $this->currencies[$this->code]['decimal_place'];
			
			$currency = $this->code;
    	}
		
    	if ($value) {
      		$value = $value;
    	} else {
      		$value = $this->currencies[$currency]['value'];
    	}

        if ($value) {
      		$value = (float)$number * $value;
    	} else {
      		$value = $number;
    	}

    	$string = '';

    	if (($symbol_left) && ($format)) {
      		$string .= $symbol_left;
    	}
		$decimal_point = '.';
//		if ($format) {
//			$decimal_point = $this->language->get('decimal_point');
//		} else {
//			$decimal_point = '.';
//		}
		$thousand_point = ',';
//		if ($format) {
//			$thousand_point = $this->language->get('thousand_point');
//		} else {
//			$thousand_point = '';
//		}
			
    	$string .= number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);

    	if (($symbol_right) && ($format)) {
      		$string .= $symbol_right;
    	}
	
    	return $string;
  	}
	
  	public function convert($value, $from, $to) {
		if (isset($this->currencies[$from])) {
			$from = $this->currencies[$from]['value'];
		} else {
			$from = 0;
		}
		
		if (isset($this->currencies[$to])) {
			$to = $this->currencies[$to]['value'];
		} else {
			$to = 0;
		}		
		
		return $value * ($to / $from);
  	}
	
  	public function getId($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['currency_id'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
  	}
	
	public function getSymbolLeft($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['symbol_left'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
  	}
	
	public function getSymbolRight($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['symbol_right'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
  	}
	
	public function getDecimalPlace($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['decimal_place'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
  	}
	
  	public function getCode() {
    	return $this->code;
  	}
  
  	public function getValue($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['value'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
  	}
    
  	public function has($currency) {
    	return isset($this->currencies[$currency]);
  	}
	// ================ /CURRENCY DISPLAY =========================
	// ============================================================
} // end class
