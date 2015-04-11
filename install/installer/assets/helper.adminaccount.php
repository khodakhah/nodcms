<?php if(!defined('INST_BASEDIR')) die('Direct access is not allowed!');

    /* ====================================================================
    *
    *                            PHP Setup Wizard
    *
    *                   -= ADMINISTRATOR ACCOUNT FUNCTIONS =-
	*
	*  Place the method you need for the adminstrator account creation,
	*  any function that you have in your system or some filtering
	*  functions should be added here to ease with the implementation
	*  of the STEP_ROOTUSER step
	*
	*  Note: These method in here are only example of methods that your
	*        system would use to validate root user content
    *
    *  ================================================================= */


	/** 
	 *  Does the given input only contain integers from 0 to 9  
	 */
	function IsNumbersOnly($input)
	{
		return preg_match("/^[0-9]*$/", $input);
	}

	/** 
	 *  Strip common non-integer symbols from a phone number, such as: + - and white spaces 
	 *  so phone numbers like [+45 1234-2468] become [004512342468], so users can enter more
	 *  readable phone numbers, only if they use the correct symbols to reprent them
	 */
	function StripCommonPhoneNumberSymbols($phoneNumber)
	{
		// Regx expression anyone? ... 
		return str_replace("+", "00", str_replace(" ", "", str_replace("-", "", $phoneNumber)));
	}

	/** 
	 *  Does the given input contain only the common characters which 
	 *  are: A to Z (upper and lower), 0 to 9, underscore and hypen   
	 */
	function IsCommonCharacters($input)
	{
		return preg_match("/^[_a-zA-Z0-9]*$/", $input);
	}

	/** 
	 *  Is the email vaild by international standards or not  
	 */
	function IsValidEmail($emailAddress)
	{
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $emailAddress);
	}

	/** 
	 *  Is the password valid or not. To be valid it must:
	 *   - be longer than 5 characters
	 *   - contains a digit or symbol or upper case character
	 */
    function IsValidPassword($password)
    {
        if(strlen($password) <= 5)
            return false;
        
        // Get digits, lower case and upper case counts from password
    	$digits = strlen(preg_replace("/[^0-9]/", "", $password));
    	$lower = strlen(preg_replace("/[^a-z]/", "", $password));
    	$upper = strlen(preg_replace("/[^A-Z]/", "", $password));
    	
    	// Since digits, upper and lower characters have 
    	// been extracted, the rest must be symbols
    	$symbols = strlen($password) - ($digits + $lower + $upper);
    
        // At least ONE of these must be included in the password!
        if($digits == 0 && $upper == 0 && $symbols == 0)
            return false;
            
        // If the password contains ONLY digits - then not allowed!
        else if($digits > 0 && $lower == 0 && $upper == 0 && $symbols == 0)
            return false; 
            
        // It is 6 or longer, and has at least one symbol, digit
        // or upper case character - though weak, it is accepted!
        else
            return true;
    }
