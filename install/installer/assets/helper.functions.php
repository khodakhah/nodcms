<?php if(!defined('INST_BASEDIR')) die('Direct access is not allowed!');

    /* ====================================================================
    *
    *                            PHP Setup Wizard
    *
    *                         -= HELPER FUNCTIONS =-
    *
    *  Do not change anything in this file unless you are certain what
    *  you are doing. Any modifycations here might break the installer!
    *
    *  ================================================================= */


	
	
	/* =========================[ SESSION FUNCTIONS ]========================= */

	/* --------------------( Database Installation Status )------------------- */

	/** 
	 *  The progress of executing SQL queries in a database are set outside this 
	 *  helper, and the installer should not handle any sessions. So, this method 
	 *  is a work-around to allow the installer to update a session without direct 
	 *  manipulation. A database status is set by status message and a prefix
	 */
	function SetDatabaseInstallStatus($database, $state, $prefix='', $successCount=0, $failedCount=0) 
	{
		global $config;
		$ID = $config['session_prefix'];
		$sql = 'dbinstall';

		// Set the session to empty array if not set
		if(!isset($_SESSION[$ID][$sql])) 
			$_SESSION[$ID][$sql] = array();

		// Set a status for a given database (name)
		$_SESSION[$ID][$sql][$database] = array('state'=>$state, 'prefix'=>$prefix, 'okaycount'=>$successCount, 'failcount'=>$failedCount);
	}

	/**
	 *  Get the list of databases or some spesific database only, and their
	 *  installation status. This is a work-around function to allow installer
	 *  to access data directly from sessions
	 */
	function GetDatabaseInstallStatus($database='') 
	{
		global $config;
		$ID = $config['session_prefix'];
		$sql = 'dbinstall';

		// Set the session to empty array if not set
		if(!isset($_SESSION[$ID][$sql])) 
			$_SESSION[$ID][$sql] = array();

		// If get all database statuses
		if($database == '')
			return $_SESSION[$ID][$sql];

		// If the database has been spesified - return only its status
		else if(array_key_exists($database, $_SESSION[$ID][$sql]))
			return $_SESSION[$ID][$sql][$database];

		// The database does not have a status yet
		else
			return false;
	}

	/**
	 *  Remove some spesific database from sessions, rendering it
	 *  "unknown" or available for installation again. Used when a
	 *  database has a successful install but does not exist, thus
	 *  needs to be removed from sessions
	 */ 
	function RemoveDatabaseInstallStatus($database)
	{
		global $config;
		$ID = $config['session_prefix'];
		$sql = 'dbinstall';

		if(array_key_exists($database, $_SESSION[$ID][$sql]))
			unset($_SESSION[$ID][$sql][$database]);
	}

	/**
	 *  Reset installation session
	 */
	function ResetDatabaseInstallStatus()
	{
		global $config;
		$ID = $config['session_prefix'];
		$sql = 'dbinstall';

		unset($_SESSION[$ID][$sql]);
	}

	
	/* --------------------( Table Prefix )------------------- */
	
	/**
	 *  Set the prefix value to sessions. The installer might need to update
	 *  the session outside the prefix step.
	 */ 
	function SetSessionPrefix($prefix='')
	{
		global $steps;
		global $config;
		$ID = $config['session_prefix'];
		$connect = 'connection'; 
		$key = 'dbprefix';

		// If a separator must be added at the end of the prefix
		if(strlen($steps[STEP_DBPREFIX]['separator']) > 0)
		{
			// If the prefix is empty, and the separator is not
			// optional - the prefix becomes the separator
			if(strlen($prefix) == 0)
			{
				if(!$steps[STEP_DBPREFIX]['optional'])
					$prefix = $steps[STEP_DBPREFIX]['separator'];
			}

			// Prefix has value, make sure the separator is not 
			// added multiple times at the end of it
			else
			{
				$serp = $steps[STEP_DBPREFIX]['separator'];
				$start = strlen($prefix) - strlen($serp); # where does the $serp string start in $prefix 
				$start = ($start < 0) ? 0 : $start; # make sure $start does not go below zero

				// If the last part of $prefix does not match the separator then add
				// the separator at the end of $prefix before storing it to session
				if(substr($prefix, $start, strlen($serp)) != $steps[STEP_DBPREFIX]['separator'])
					$prefix = $prefix.$steps[STEP_DBPREFIX]['separator'];
			}
		}

		// Finally set the prefix to session
		$_SESSION[$ID][$connect][$key] = $prefix;
	}


	/* --------------------( Administrator Account )------------------- */

	/**
	 *  Set if a administrator account exists, or has been approved
	 */ 
	function SetAdminAccountStatus($status='none') # success , failed
	{
		global $config;
		$ID = $config['session_prefix'];
		$admin = 'adminaccount';

		// Set the session to false if not set
		if(!isset($_SESSION[$ID][$admin])) 
			$_SESSION[$ID][$admin] = 'none';

		// Set a status for administrator creation
		$_SESSION[$ID][$admin] = $status;
	}

	/**
	 *  Get a administrator account status
	 */ 
	function GetAdminAccountStatus()
	{
		global $config;
		$ID = $config['session_prefix'];
		$admin = 'adminaccount';

		// Set the session to false if not set
		if(!isset($_SESSION[$ID][$admin])) 
			$_SESSION[$ID][$admin] = 'none';

		// Get the administrator creation status
		return $_SESSION[$ID][$admin];
	}

	/**
	 *  Get if an administrator account has been created or not
	 */
	function AdminAccountExists()
	{
		return (GetAdminAccountStatus() != 'none') ? true : false;
	}

	/**
	 *  Get if an administrator account has been created successfully
	 */
	function AdminAccountSuccess()
	{
		return (GetAdminAccountStatus() == 'success') ? true : false;
	}

	/**
	 *  Get if an administrator account failed to insert 
	 */
	function AdminAccountFailed()
	{
		return (GetAdminAccountStatus() == 'failed') ? true : false;
	}



	/* =========================[ $STEPS FUNCTIONS ]========================= */

	/**
	 *  Get the next step index key, taking configuration into account
	 *  NOTE: false is returned if there is none
	 */
	function GetNextStep($stepKey)
	{
		global $steps;

		$next = false;
		$breakNext = false;
		foreach($steps as $key=>$step)
		{
			$next = $key;

			if($breakNext)
			{
				if( (isset($steps[$next]['enabled']) && !$steps[$next]['enabled']) || 
					(isset($steps[$next]['autoskip']) && $steps[$next]['autoskip']) )
					 continue;
				else break;
			}

			if($stepKey == $key)
				$breakNext = true;
		}
		return $next;
	}

	/**
	 *  Get the previous step index key, taking configuration into account.
	 *  NOTE: false is returned if there is none
	 */
	function GetPrevStep($stepKey)
	{
		global $steps;

		$prev = false;
		$breakNext = false;

		end($steps);
		while($current = prev($steps))
		{
			$prev = key($steps);

			if($breakNext)
			{
				if( (isset($steps[$prev]['enabled']) && !$steps[$prev]['enabled']) || 
					(isset($steps[$prev]['autoskip']) && $steps[$prev]['autoskip']) )
					 continue;
				else break;
			}

			if($stepKey == key($steps))
				$breakNext = true;
		}

		// Return false if the same as current
		if($prev == $stepKey) 
			 return false;

		// If the while ended and the $prev value
		// is set to disabled or autoskip step, then
		// return false!
		if( (isset($steps[$prev]['enabled']) && !$steps[$prev]['enabled']) || 
			(isset($steps[$prev]['autoskip']) && $steps[$prev]['autoskip']) )
			return false;
		
		// The step must be valid then :)
		else 
			return $prev;
	}


	/* =========================[ ENCRYPTION / DECRYPTION ]========================= */

	/**
	 *  Encrypt string
	 */
	function Encrypt($string) 
	{
		if(IsModuleInstalled('mcrypt'))
		{
			global $config;
			return mcrypt_ecb(MCRYPT_BLOWFISH, $config['encryption_key'], $string, MCRYPT_ENCRYPT);
		}
		return $string;
	}

	/**
	 *  Decrypt string
	 */
	function Decrypt($string) 
	{
		if(IsModuleInstalled('mcrypt'))
		{
			global $config;
			return mcrypt_ecb(MCRYPT_BLOWFISH, $config['encryption_key'], $string, MCRYPT_DECRYPT);
		}
		return $string;
	}


	/* =========================[ FINAL CONFIG FUNCTIONS ]========================= */

	/** 
	 *  Combine the final output path and the config filename. This is important
	 *  method because if broken - the installer will not function
	 */
	function FixPath($configSavePath='')
	{
		// Do not check slashes if value is empty
		$configSavePath = trim($configSavePath);
		if(strlen($configSavePath) == 0)
			return '';

		// If for some reason the Php does not support both types of slashes,
		// this small clean-up script is to fix all slashes such that they
		// are all facing the same way - and the same way as DIRECTORY_SEPARATOR,
		// and if there are two slashes together, replace them with a single one
		$slashes = array("\\","/","\\\\","//");
		foreach($slashes as $slash)
			$configSavePath = str_replace($slash, DIRECTORY_SEPARATOR, $configSavePath);

		// If the 'savetofolder' does not end with a slash, add it!
		if(substr($configSavePath, -1, 1) != DIRECTORY_SEPARATOR)
			$configSavePath .= DIRECTORY_SEPARATOR;

		return $configSavePath;
	}
	
	/** 
	 *  Does the final output config file exist or not. If in $steps, the setting 
	 *  'updateonzero' is enabled - this method will check if the config has 
	 *  zero bytes or not as well.
	 */
	function IsInstallerDone()
	{
		//  Access config and step to get the path and filename
		global $config;
		global $steps;

		$savepath = FixPath($steps[STEP_WRITECONFIG]['savetofolder']);

		// Directory exists - check the output config file
		if(is_dir($savepath) || strlen($savepath) == 0)
		{
			// Update savepath to include the filename and get 
			// "onzero" setting to variable for simpler code
			$savepath = $savepath.$steps['writeconfig']['maskname'];
			$updateOnZero = $steps['writeconfig']['updateonzero'];				
		
			// The config does not exist, we continue with installing
			if(!is_file($savepath))
				return false;
			
			// If the config does exists, and file should NOT be
			// updated if it contains zero bytes - we are done!
			else if(is_file($savepath) && !$updateOnZero)
				return true;

			// The output config file does exist, but we only continue
			// if the file does only contain zero bytes
			else
			{
				$bytes = filesize($savepath);
				return ($bytes == 0) ? false : true;
			}
		}

		return false;
	}

	/**
	 *  Delete the final output config and return a boolean
	 *  notifing if it was successful or not
	 */
	function DeleteFinalOutputConfig()
	{
		global $steps;

		// Get the name of final output name and path folder
		$folder = FixPath($steps[STEP_WRITECONFIG]['savetofolder']);
		$file = trim($steps[STEP_WRITECONFIG]['maskname']);

		// If deletion was successful
		if(is_file($folder.$file) && unlink($folder.$file))
			 return true;
		else return false;
	}


	/* =========================[ MISC FUNCTIONS ]========================= */


	/** 
	 *  Does the given input only contain numeric digits from 0 to 9  
	 */
	function IsNumericOnly($input)
	{
		/*  NOTE: The PHP function "is_numeric()" evaluates "1e4" to true
		 *        and "is_int()" only evaluates actual integers, not 
		 *        numeric strings. */

		return preg_match("/^[0-9]*$/", $input);
	}

	/**
	 *  Does some module exist or not
	 */
	function IsModuleInstalled($moduleName)
	{
		// The faster "less-reliable" alternative which is not used because
		// a module (or extension) names could be in different casing, so
		// 'Mysql' should be approved even though only 'mysql' is loaded		
		## return extension_loaded($moduleName);

		// Set the module name to lower case and get all loaded extensions 
		$moduleName = strtolower($moduleName);
		$extensions = get_loaded_extensions();
		foreach($extensions as $ext)
		{
			if($moduleName == strtolower($ext))
				return true;
		}

		return false;
	}