<?php

require_once ("paypalfunctions.php");

$_SESSION['checkout']['payment_method'] = $PaymentOption = isset($_POST['payment_method']) && $_POST['payment_method']==1?"PayPal":"PayPal";

if ( $PaymentOption == "PayPal")
{
        // ==================================
        // PayPal Express Checkout Module
        // ==================================

        //'------------------------------------
        //' The paymentAmount is the total value of 
        //' the shopping cart, that was set 
        //' earlier in a session variable 
        //' by the shopping cart page
        //'------------------------------------
		$EXname =  $_SESSION['orders'];
        $paymentAmount = $_SESSION['total_money'];
        $shipping = isset($_SESSION['shipping'])?$_SESSION['shipping']:"";
        $shipping_status = isset($_SESSION['shipping_status'])?$_SESSION['shipping_status']:0; 

        //'------------------------------------
        //' When you integrate this code 
        //' set the variables below with 
        //' shipping address details 
        //' entered by the user on the 
        //' Shipping page.
        //'------------------------------------

        $shipToName = "";
        $shipToStreet = "250 West 34th Street";
        $shipToStreet2 = ""; //Leave it blank if there is no value
        $shipToCity = "New York";
        $shipToState = "PA";
        $shipToCountryCode = "US"; // Please refer to the PayPal country codes in the API documentation
        $shipToZip = "NY 10119";
        $phoneNum = "1 646 745 9000";

        //'------------------------------------
        //' The currencyCodeType and paymentType 
        //' are set to the selections made on the Integration Assistant 
        //'------------------------------------
        $currencyCodeType = "USD";
        $paymentType = "Sale";

        //'------------------------------------
        //' The returnURL is the location where buyers return to when a
        //' payment has been succesfully authorized.
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $returnURL = $_SESSION['checkout']['return_url'];

        //'------------------------------------
        //' The cancelURL is the location buyers are sent to when they hit the
        //' cancel button during authorization of payment during the PayPal flow
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $cancelURL = $_SESSION['checkout']['cancel_url'];

        //'------------------------------------
        //' Calls the SetExpressCheckout API call
        //'
        //' The CallMarkExpressCheckout function is defined in the file PayPalFunctions.php,
        //' it is included at the top of this file.
        //'-------------------------------------------------
        
     	  $resArray = CallShortcutExpressCheckout($paymentAmount,$EXname,$shipping,$shipping_status, $currencyCodeType, $paymentType, $returnURL, $cancelURL);
        /*$resArray = CallMarkExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL,
                                                                                  $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState,
                                                                                  $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum
        );
		*/
        $ack = strtoupper($resArray["ACK"]);
        if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
        {
                $token = urldecode($resArray["TOKEN"]);
                $_SESSION['reshash']=$token;
                RedirectToPayPal ( $token );
        } 
        else  
        {
                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
                
                echo "SetExpressCheckout API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;
        }
}
else
{
	//Moneybookers
}

?>
