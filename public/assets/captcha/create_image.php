<?
/*
	This is PHP file that generates CAPTCHA image for the How to Create CAPTCHA Protection using PHP and AJAX Tutorial

	You may use this code in your own projects as long as this 
	copyright is left in place.  All code is provided AS-IS.
	This code is distributed in the hope that it will be useful,
 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	
	For the rest of the code visit http://www.WebCheatSheet.com
	
	Copyright 2006 WebCheatSheet.com	

*/

//Start the session so we can store what the security code actually is
session_start();

//Send a generated image to the browser 
create_image(); 
//echo $_SESSION["security_code"];
exit(); 

function create_image() 
{ 

	header("Content-type: image/png");
	$font = 'monofont.ttf';
	$width = 80; 
	$height = 20;  
	$font_size = $height * 0.75;
	$im = imagecreate($width, $height);
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 150, 150, 180);
	$black = imagecolorallocate($im, 0, 62, 62);
	$bg = imagecolorallocate($im, 215, 215, 215);
	
	imagefilledrectangle($im, 0, 0, 399, 29, $white);
	
	// The text to draw
	$md5_hash = md5(rand(0,999)); 
	//We don't need a 32 character long string so we trim it down to 6 
	$security_code1 = strtoupper(substr($md5_hash, 15, 3)); 
	$security_code2 = strtoupper(substr($md5_hash, 18, 3));
	
	//Set the session to store the security code
	$_SESSION["security_code"] = $security_code1.$security_code2;
	
	// Add background
	imagettftext($im, 20, 0, 1, 16, $bg, $font, "#######");
	// Add the first 3 letter
	imagettftext($im, 16, -10, 12, 13, $grey, $font, $security_code1);
	// Add the last 3 letter
	imagettftext($im, 16, 20, 45, 20, $black, $font, $security_code2); // rotate 20 degree
	//imagettftext  ( resource $image  , float $size  , float $angle  , int $x  , int $y  , int $color  , string $fontfile  , string $text  )
	
	// Using imagepng() results in clearer text compared with imagejpeg()
	imagepng($im);
	imagedestroy($im);
} 
?>