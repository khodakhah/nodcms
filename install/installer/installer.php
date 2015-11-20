<?php if(!defined('INST_BASEDIR')) die('Installer cannot be started directly!');

    /* ====================================================================
    *
    *                             PHP Setup Wizard
	*
    *                             -= INSTALLER =-
    *
    *  ================================================================= */   

    // Load installer configuration and file handling class
    include('configuration.php');
	require('assets'.DIRECTORY_SEPARATOR.'helper.functions.php');
            
    // If the output config exists and installer should be ignored 
    if(IsInstallerDone() && $config['ignore_installer_when_done'])
    {
        // Bye bye! :)
        unset($config);
        unset($keywords);
		unset($steps);
    }
    
    // If the installer WILL display something
    else
    {
        session_start();

		// Turns of PHP error messages - or embraze them
		if($config['show_php_error_messages'])
			 error_reporting(E_ALL);
		else error_reporting(0);
        
        // Include and create instanses of the other classes
		include('assets'.DIRECTORY_SEPARATOR.'class.masks.php');
        require('assets'.DIRECTORY_SEPARATOR.'class.htmlmaker.php');        
        require('assets'.DIRECTORY_SEPARATOR.'class.databases.php');
        $page = new Inst_HtmlMaker();
        $mask = new Inst_Masks();
        $dbase = new Inst_Databases();
        
        // Load session helper, which handles all POST and GET, and stores 
		// changes automatically to sessions, encrypts and decrypts etc.
        require('assets'.DIRECTORY_SEPARATOR.'helper.sessions.php');

		// Add to page maker that some data has to be shown on the top of the page
		if($config['debug_sessions']) $page->AddToDebug('Sessions:', $_SESSION[$config['session_prefix']]);
		if($config['debug_posts']) $page->AddToDebug('POST data:', $_POST);
		if($config['debug_gets']) $page->AddToDebug('GET data:', $_GET);
        
        // Update the mask class with updated keywords (with login, database etc.)
        $mask->SetKeywords();
            
		
        /* ===================================================[ INSTALLATION BEGINS! ]=================================================== */
        
        // If the output config does not exist, or is totally empty
        if(IsInstallerDone() == false)
        {   			
			/* ===================================================[ PHP MODULE CHECK ]=================================================== */

			$phpmodules = true;

			if($steps[STEP_MODULECHECK]['enabled'])
			{
				$page->MainTitle($steps[STEP_MODULECHECK]['title'], 'phpmodule'); 	
				$page->Paragraph('All PHP extensions must be installed in order for the Installer and/or the system to function properly.');

				// Do the module checking
				foreach($steps[STEP_MODULECHECK]['modules'] as $module)
				{
					if(IsModuleInstalled($module))
					{
						$page->SuccessBox('<b><tt>'.$module.'</tt></b> is installed!');
					}
					else
					{
						$page->ErrorBox('<b><tt>'.$module.'</tt></b> is <u>not installed!</u>');
						$phpmodules = false;
					}
				}

				// If all modules are installed
				if($phpmodules)
				{
					$page->FormStart(array('step'=>GetNextStep(STEP_MODULECHECK))); 
					$page->FormSubmit('Next');
					$page->FormClose();
				}

				// There where some errors or problems with the 
				// file IO - show "retry" button
				else
				{
					$page->Paragraph('Contact your webserver support (hosting service) to get the necessary PHP extensions loaded.');

					$page->FormStart(array('step'=>STEP_MODULECHECK));  
					$page->FormSubmit('Retry');
					$page->FormClose();
				}

				// If there are some modules not detected, or this step should not be
				// auto skipped and $step is currently set to view this step  
				if(!$phpmodules || (!$steps[STEP_MODULECHECK]['autoskip'] && $step == STEP_MODULECHECK))
					$page->ShowPage(STEP_MODULECHECK);

				// Page should not be shown, so the html queue is cleared
				// so the next step can start fresh
				else
					$page->ClearHtmlQueue();
			}

			/* ===================================================[ FILE I/O PERMISSIONS ]=================================================== */

			$ioable = true;	

			// Only continue if IO should be checked. The installer might
			// be configured to only install tables (though unlikely)
			if($steps[STEP_IOFILES]['enabled'])
			{
				// Errors along the way
				$status = array(
					'folder'  => array('state'=>'unknown', 'msg'=>'&nbsp;'),
					'read'	  => array('state'=>'unknown', 'msg'=>'&nbsp;'),
					'write'	  => array('state'=>'unknown', 'msg'=>'&nbsp;'),
					'delete'  => array('state'=>'unknown', 'msg'=>'&nbsp;'),
				);

				// Begin the page 
				$page->MainTitle($steps[STEP_IOFILES]['title'], 'filelocked'); 				

				// If the config should be in a folder, make sure it exists
				// or try to create the folder first
				$folder = trim($steps[STEP_WRITECONFIG]['savetofolder']);
				if(strlen($folder) > 0)
				{
					// Configure the new folder value if set to fit the webserver.
					// For instance "fld1/fld2" should be "fld1\fld2" on the server so
					// this method will convert slashes to make it right
					$folder = FixPath($folder);
					if(!is_dir($folder))
					{
						if(mkdir($folder))
						{
							$status['folder']['state'] = 'success';
							$status['folder']['msg'] = 'The config folder <b>'.$folder.'</b> has been created successfully';
						}
						else 
						{
							$status['folder']['state'] = 'failed';
							$status['folder']['msg'] = 'Unable to create the folder <b>'.$folder.'</b>';

							$page->ErrorBox('The Installer is unable to create the folder <b>'.$folder.'</b>, check your <tt>chmod</tt> '.
										'permissions or contact support to get this issue resolved.');
						}
					}
					else
					{
						$status['folder']['state'] = 'exists';
						$status['folder']['msg'] = 'Config folder <b>'.$folder.'</b> already exists';
					}
				}
				else
				{
					$status['folder']['msg'] = '';
				}


				// The folder either exists now or there is none to create, 
				// so continue with "write to file" check with the test file
				$file = "test_".$steps[STEP_WRITECONFIG]['maskname'];

				// Test the read ability (very likely to pass in most cases)
				$maskContent = $mask->GetConfigFile();
				if(strlen($maskContent) > 0)
				{
					$status['read']['state'] = 'success';
					$status['read']['msg'] = 'The mask file for <b>'.$file.'</b> is readable';
				}
				else
				{
					$status['read']['state'] = 'failed';
					$status['read']['msg'] = 'Unable to read the mask of <b>'.$file.'</b>';

					$page->ErrorBox('The Installer is unable to read the mask file <b>'.$config['mask_folder_name'].DIRECTORY_SEPARATOR.$file.
						            '</b>, check your <tt>chmod</tt> permissions or contact support to get this issue resolved.');
				}

				// If the path is valid
				if($status['folder']['state'] != 'failed')
				{
					// Write some string to the test file
					if(file_put_contents($folder.$file, "Installer: can I write to a file... "))
					{
						$status['write']['state'] = 'success';
						$status['write']['msg'] = 'The test-file <b>'.$file.'</b> was written too successfully';
					}
					else
					{
						$status['write']['state'] = 'failed';
						$status['write']['msg'] = 'Unable to create the test-file <b>'.$file.'</b>';

						$page->ErrorBox('The Installer is unable to create and write to <b>'.$folder.$file.'</b>, check your <tt>chmod</tt> '.
										'permissions or contact support to get this issue resolved.');
					}


					// Try to delete the file after test
					if($status['write']['state'] != 'failed')
					{
						if(unlink($folder.$file))
						{
							$status['delete']['state'] = 'success';
							$status['delete']['msg'] = 'The test-file <b>'.$file.'</b> has been removed';
						}
						else
						{
							$status['delete']['state'] = 'failed';
							$status['delete']['msg'] = 'Unable to delete the test-file <b>'.$file.'</b>';
							
							$page->ErrorBox('The Installer is unable to delete <b>'.$folder.$file.'</b>, check your <tt>chmod</tt> permissions or contact '.
								            'support to get this issue resolved.');
						}
					}
					else
					{
						$status['delete']['state'] = 'unknown';
						$status['delete']['msg'] = 'No file to delete';
					}
				}

				// Determine $ioable though it can easily be done in the foreach loop
				// BELOW this one. This is done here to be able to put "success" box
				// in the html queue BEFORE the actual test tables
				foreach($status as $test=>$result)
				{
					if($result['state'] == 'failed')
						$ioable = false;
				}

				if($ioable)
					$page->SuccessBox('The Installer has sufficient file permissions on this server.');


				// THIS TABLE USES THE SAME TABLE-CSS AS "USER ACCESS TEST" STEP!
				$page->StartTable(4, array('class'=>'dbtests', 'cellspacing'=>'0', 'cellpadding'=>'0'));
				foreach($status as $test=>$result)
				{
					if($result['state'] == 'success')
					{
						$page->AddTableData('', array('class'=>'okayico'));
						$page->AddTableData($test, array('class'=>'operation'));
						$page->AddTableData('Success!', array('class'=>'okay'));
						$page->AddTableData($result['msg'], array('class'=>'normalmsg'));
					}
					else if($result['state'] == 'failed')
					{
						$page->AddTableData('', array('class'=>'failico'));
						$page->AddTableData($test, array('class'=>'operation'));
						$page->AddTableData('Failed!', array('class'=>'fail'));
						$page->AddTableData($result['msg'], array('class'=>'errormsg'));
					}
					else if($result['state'] == 'exists')
					{
						$page->AddTableData('', array('class'=>'existico'));
						$page->AddTableData($test, array('class'=>'operation'));
						$page->AddTableData('Exists', array('class'=>'exist'));
						$page->AddTableData($result['msg'], array('class'=>'normalmsg'));
					}
					else 
					{
						$page->AddTableData('', array('class'=>'unknownico'));
						$page->AddTableData($test, array('class'=>'operation'));
						$page->AddTableData('Not tested', array('class'=>'unknown'));
						$page->AddTableData($result['msg'], array('class'=>'unknownmsg'));
					}
				}
				$page->EndTable();

				// If all test where successful (or not tested)
				// then show "next" button
				if($ioable)
				{
					$page->FormStart(array('step'=>GetNextStep(STEP_IOFILES)));

					$prev = GetPrevStep(STEP_IOFILES);
					if($prev) $page->FormButton('Back', array('step'=>$prev));   

					$page->FormSubmit('Next');
					$page->FormClose();
				}

				// There where some errors or problems with the 
				// file IO - show "retry" button
				else
				{
					$page->FormStart(array('step'=>STEP_IOFILES));

					$prev = GetPrevStep(STEP_IOFILES);
					if($prev) $page->FormButton('Back', array('step'=>$prev));   
 
					$page->FormSubmit('Retry');
					$page->FormClose();
				}
				

				// If there are some IO conficts, or this step should not be
				// auto skipped and $step is currently set to view this step  
				if(!$ioable || (!$steps[STEP_IOFILES]['autoskip'] && $step == STEP_IOFILES))
					$page->ShowPage(STEP_IOFILES);

				// Page should not be shown, so the html queue is cleared
				// so the next step can start fresh
				else
					$page->ClearHtmlQueue();
				
			}


            /* ===================================================[ WELCOME MESSAGE ]=================================================== */
            
            if($steps[STEP_WELCOME]['enabled'] && $step == STEP_WELCOME)
            {
                $page->MainTitle($steps[STEP_WELCOME]['title'], 'home');
                $page->Paragraph( $mask->GetWelcomeMessage() );
                                
                $page->FormStart(array('step'=>GetNextStep(STEP_WELCOME)));

				$prev = GetPrevStep(STEP_WELCOME);
				if($prev) $page->FormButton('Back', array('step'=>$prev));   

                $page->FormSubmit('Next');
                $page->FormClose();
                $page->ShowPage(STEP_WELCOME);  // <<<<<<<<<<<< PHP dies after the page has been shown!
            }
            
            
            /* ===================================================[ TERMS OF AGREEMENT ]=================================================== */
            
            if($steps[STEP_TERMSOFUSE]['enabled'] && (!$approved || $step == STEP_TERMSOFUSE)) 
            {
                $page->MainTitle($steps[STEP_TERMSOFUSE]['title'], 'agreement');                
                
                // Notify the user that he must approve the terms of agreement, and if he
                // has - notfify him that he has done so and make the box "checked" so user
                // can simply press "next to continue"
                $checked = array();
                if($approved)
                {
                    $page->SuccessBox('You have approved the terms of use agreement!');
                    $checked = array('checked');
                }
                else if($step != STEP_TERMSOFUSE)
                {
                    $page->InfoBox('You must approve the terms of use agreement if you want to continue!');
                }
                
                $page->Textarea( $mask->GetTermsOfAgreement() );
                $page->FormStart(array('step'=>GetNextStep(STEP_TERMSOFUSE)));
                $page->FormRadiobox('approved', 'agreement', 'I <b>accept</b> this terms of use', $checked);
                $page->FormRadiobox('denied', 'agreement', 'I <b>do not accept</b> this terms of use');
                
				$prev = GetPrevStep(STEP_TERMSOFUSE);
				if($prev) $page->FormButton('Back', array('step'=>$prev));   
                        
                $page->FormSubmit('Next');
                $page->FormClose();
                $page->ShowPage(STEP_TERMSOFUSE);
            }		


			/* ===================================================[ LANGUAGE SELECTION ]=================================================== */

			// If the language step is enabled and active, show "select language" form
			if($steps[STEP_LANGUAGE]['enabled'] && count($steps[STEP_LANGUAGE]['supported']) > 0 && $step == STEP_LANGUAGE)
			{
				$page->MainTitle($steps[STEP_LANGUAGE]['title'], 'language');
				$page->Paragraph('Select one of the following supported languages:');

				// Begin the language selection
				$page->FormStart(array('step'=>GetNextStep(STEP_LANGUAGE)));
				foreach($steps[STEP_LANGUAGE]['supported'] as $langCode=>$langName)
				{
					// If this language is the "default" one or the user
					// has selected this language before
					if($langCode == $keywords['special']['language'])
						 $checked = array('checked');
					else $checked = array();

					// Get a flag icon to go with the name
					$flag = $page->GetFlagHtml($langCode);
					$page->FormRadiobox($langCode, 'language', '&nbsp;'.$flag.'&nbsp;'.$langName, $checked);
				}

				$prev = GetPrevStep(STEP_LANGUAGE);
				if($prev) $page->FormButton('Back', array('step'=>$prev));   
                                     
                $page->FormSubmit('Next');
                $page->FormClose();
				$page->ShowPage(STEP_LANGUAGE);
			}
            

			/* ===================================================[ TMEZONE SELECTION ]=================================================== */

			/*
			*  The TimeZone step can check the selected language (if enabled)
			*  to see what TimeZone to select automatically
			*/
	
			// If the timezone step is enabled and active, show "select timezone" form
			if($steps[STEP_TIMEZONE]['enabled'] && $step == STEP_TIMEZONE)
			{
				$page->MainTitle($steps[STEP_TIMEZONE]['title'], 'timezone');

				$page->Paragraph('Assuming that you have a "timezone" feature in your system already, you might '.
					             'want to design this step yourself to make it fit correctly with your system.');

				$page->FormStart(array('step'=>GetNextStep(STEP_TIMEZONE)));
				$page->Label('Select timezone:');
				$page->AddTimezoneDropdown('timezone', $keywords['special']['timezone']);

				$prev = GetPrevStep(STEP_TIMEZONE);
				if($prev) $page->FormButton('Back', array('step'=>$prev));   
                   
                $page->FormSubmit('Next');
                $page->FormClose();
				$page->ShowPage(STEP_TIMEZONE);
			}	

			
			/* ===================================================[ DATABASE CONNECTION ]=================================================== */
			
			// Try to connect to database
			$login = $keywords['connection'];
			$dbase->Connect($login);

			// Clear the plain-text password if encryption is enabled
			if($steps[STEP_DBCONNECT]['encryptlogin'])
				$login['password'] = '';
		   
			// If connection cannot be made, force show "connection" step!
			if(!$dbase->IsConnected() || $step == STEP_DBCONNECT)
			{
				$page->MainTitle($steps[STEP_DBCONNECT]['title'], 'connection');
				
				// If the step is not STEP_DBCONNECT, then the installer was going 
				// somewhere else - so error message should be displayed
				if($step != STEP_DBCONNECT)
				{
					$page->WarningBox('Unable to establish a connection to <b>'.$login['hostname'].'</b>. '.
									  'Please fill in <i>hostname</i>, <i>username</i> and <i>password</i>.'); 
					
					if($config['show_database_error_messages'])
						$page->ErrorDatabaseBox($dbase->GetDatabaseError());
				}
				
				// If how ever the step IS set to 1 and the connection to database has been 
				// successful, let user know he does not have to set the values again or change them
				else if($step == STEP_DBCONNECT && $dbase->IsConnected())
				{
					$page->SuccessBox('Connection to database server is successful with login '. 
						'provided. Proceed to the next step');
				} 
				
				// If the port is optional and the port value contains 
				// non-digits then display warning message
				if($steps[STEP_DBCONNECT]['portoptional'] && !$dbase->IsConnected()
					&& strlen($login['dbport']) > 0 && !IsNumericOnly($login['dbport']))
				{
					$page->WarningBox('The port value <b>'.$login['dbport'].'</b> is not a valid numeric value'); 
				}

				// If the password is encrypted and connection has been made successfully - then pressing NEXT
				// in the 'else' clause below would send an empty string and reset the password to nothing. So,
				// either force the user to type in the password every time he visists this step (which sucks)
				// or just show success message and offer a "disconnect" button instead. Then the only info posted
				// on the "next" button here will be the step key
				if($steps[STEP_DBCONNECT]['encryptlogin'] && $dbase->IsConnected())
				{
					$page->FormStart(array('step'=>GetNextStep(STEP_DBCONNECT)));
					$prev = GetPrevStep(STEP_DBCONNECT);
					if($prev) $page->FormButton('Back', array('step'=>$prev));    
					$page->FormSubmit('Next');
					$page->FormClose();
					
					$page->SubTitle('Disconnect', 'disconnect');
					$page->Paragraph('The <i>username</i> and <i>password</i> provided to connect to <b>'.$login['hostname'].
									 '</b> are encrypted during the rest of this process. However, you can disconnect '.
									 'from current connection and enter new username and password if needed.');

					$page->FormStart(array('step'=>STEP_DBCONNECT, 'reset'=>'connection'));
					$page->FormSubmit('Disconnect');
					$page->FormClose();						
				}

				// Only shown when not connected to database server
				else
				{                    
					$page->FormStart(array('step'=>GetNextStep(STEP_DBCONNECT)));

					// If port is offered as option
					if($steps[STEP_DBCONNECT]['portoptional'])
					{
						$page->StartTable(2, array('class'=>'hostport', 'cellpadding'=>'0', 'cellspacing'=>'0'));

						// Insert the elements in wrong order, then when the
						// items are popped they are inserted in right order
						$page->FormInput($login['hostname'], 'hostname', array(), 'bigbox');
						$page->Label('Hostname:');
						$page->AddTableData($page->PopQueue().$page->PopQueue(), array('class'=>'port'));

						$page->FormInput($login['dbport'], 'dbport', array(), 'tinybox');
						$page->Label('Port:');
						$page->AddTableData($page->PopQueue().$page->PopQueue(), array('class'=>'host'));

						$page->EndTable();
					}

					// Display host normally
					else
					{
						$page->Label('Hostname:');
						$page->FormInput($login['hostname'], 'hostname');
					}

					$page->Label('Username:');
					$page->FormInput($login['username'], 'username');
					$page->Label('Password:');
					$page->FormPassword($login['password'], 'password');

					$prev = GetPrevStep(STEP_DBCONNECT);
					if($prev) $page->FormButton('Back', array('step'=>$prev));   
					$page->FormSubmit('Next');
					$page->FormClose();
				}
				
				$page->ShowPage(STEP_DBCONNECT); 
			}
			
			
			/* ===================================================[ DATABASE SELECTION/CREATION ]=================================================== */
			
			// Try to select a database if some value is provided
			if(strlen($login['database']) > 0)
				$dbase->SelectDatabase($login['database']);                
			
			// If unable to select database, force show "database" step!
			if(!$dbase->IsDatabaseSelected() || $step == STEP_DBSELECT)
			{   
				// Get the new database name if posted - or false if not. Then 
				// validate the name and try to insert this new database. Messages
				// are not added to queue right away
				$newdb = false;
				$msg = false;
				if(isset($_REQUEST['createdb']) && $steps[STEP_DBSELECT]['allowcreate'])
				{
					$newdb = trim($_REQUEST['createdb']);
					if(strlen($newdb) == 0)
					{
						$page->InfoBox('Please specify a name for the database');
						$msg[] = $page->PopQueue();
					}
					else if($dbase->DoesDatabaseExist($newdb))
					{
						$page->WarningBox('There exists a database with the name <b>'.$newdb.
							'</b> already, please choose another one.');
						$msg[] = $page->PopQueue();
					}
					else if(!$dbase->IsDatabaseFriendly($newdb))
					{
						$page->WarningBox('The database name <b>'.$newdb.
							'</b> is not valid, please choose another one.');
						$msg[] = $page->PopQueue();
					}
					else if($dbase->CreateNewDatabase($newdb))
					{
						$page->SuccessBox('The database <b>'.$newdb.'</b> has been created successfully!');
						$msg[] = $page->PopQueue();
					}
					else
					{
						$page->ErrorBox('Installer was unable to create the database <b>'.
							$newdb.'</b>, either select database from a list or contact support.');
						$msg[] = $page->PopQueue();
									
						if($config['show_database_error_messages'])
						{
							$page->ErrorDatabaseBox($dbase->GetDatabaseError());
							$msg[] = $page->PopQueue();
						}
					}
				}
				
				// Main title with an icon
				$page->MainTitle($steps[STEP_DBSELECT]['title'], 'dbselect');
				
				// -------------------( Option A: Select database from list )-------------------//
				
				// Do not show success box of selection IF creating new database!
				if($step != STEP_DBSELECT)
				{
					// If the database has a value and could not be selected, then either
					// the database no longer exists (or never did). 
					if(strlen($login['database']) > 0 && !$dbase->IsDatabaseSelected())
						$page->WarningBox('The database <b>'.$login['database'].'</b> cannot be selected');

					else if(!$dbase->IsDatabaseSelected())
						$page->InfoBox('You have to select a database or create a new one in order to continue');
				}
				else
				{
					if($dbase->IsDatabaseSelected())
						$page->SuccessBox('You have selected <b>'.$login['database'].'</b>, choose another '.
									  'database or proceed to the next step.');
				}

				// If the user has installed tables on some database, highlight those
				// databases as "success" to identify the ones the user should select,
				// but also show a success message with the others to show the user that
				// there have been successful installations					
				$successInstall = array();
				$successStr = '';
				foreach(GetDatabaseInstallStatus() as $database=>$status)
				{
					if($status['state'] == 'success')
					{
						$successInstall[] = $database;
					}
				}

				// Display the success databases in more "neat" fashion than
				// adding only commas in between. Make the last item be separated
				// with "and" which a human would do normally
				if(count($successInstall) > 0)
				{
					$str = '';
					if(count($successInstall) == 1)
						$str = '<b>'.$successInstall[0].'</b>';			
					else if(count($successInstall) > 1)
					{
						$serp = ' and ';
						foreach($successInstall as $database)
						{
							$str = $serp.'<b>'.$database.'</b>' . $str;
							$serp = ', ';
						}
						$str = substr($str, strlen($serp));
					}
					$page->SuccessBox('Database setup has been completed successfully on '.$str);
				}			

				// Display some text on what the user is supposed to do
				$page->Paragraph('Select a database from a list of databases whitin the server '.
					             'you have logged on (or type in the name manually).');
				

				// Go through the list of available databases
				$dblist = $dbase->GetDatabaseList();
				if(count($dblist) > 0)
				{
					$page->Label('Database list:');
					$page->FormStart(array('step'=>GetNextStep(STEP_DBSELECT)));
					$page->StartTable(2, array('class'=>'dblist'));

					reset($dblist);
					foreach($dblist as $idx=>$db)
					{
						// $optional : Additional attributes to the <input> tag
						// $divClass : Added class value to the <div> that contains the <input>
						$optional = array();
						$divClass = '';

						// If a database has been selected, make this radiobox checked
						if($db['name'] == $login['database'])
							$optional[0] = 'checked'; # numeric keys are ignored in HtmlMaker

						// If however, the $newdb is in the list - it was just inserted,
						// then highlight that database as newly created database
						if($newdb && $newdb == $db['name'])
							$divClass = 'newdb';

						// If this database has successful installation, highlight it
						// as successful or approved database
						if(in_array($db['name'], $successInstall))
						{
							// if this IF-statement validates to true, then the database was 
							// JUST created but still marked as "successful installation". That
							// can ONLY happen if the user installs tables on a database, then
							// drops it using another tool and creates it again here
							if(strlen($divClass) > 0)
								 $divClass .= ' installdone'; 
							else $divClass = 'installdone';

							// The current database is removed from $successInstall, which
							// indicates that at the end of this foreach, $successInstall 
							// should be empty - meaning all successfully installed databases
							// do in fact exist. If $successInstall has any elements left when
							// this foreach is done - then remove those databases from sessions!
							$key = array_search($db['name'], $successInstall);
							unset($successInstall[$key]);
						}


						// The name caption of the database is formed in html
						$caption = $db['name'].' '.$page->Discrete('('.$db['tbcount'].')');							
						
						// Add a radio box for this database
						$page->FormRadiobox($db['name'], 'database', $caption, $optional, $divClass);

						// Get the data from HTML queue back, and add the HTML 'values'
						// from the returned queue item array into a table
						$html = $page->PopQueue();
						$page->AddTableData($html);
					}    
					$page->EndTable();

					// If there are some elements left in $successInstall, then those databases
					// do not exist and there cannot be a successfull installation on a database
					// that does not exist - sessions need to be updated
					if(count($successInstall) > 0)
					{
						foreach($successInstall as $database)
							RemoveDatabaseInstallStatus($database);
					}					

					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_DBSELECT)));                        
					$page->FormSubmit('Next');  
					$page->FormClose();  
				}

				// There are no detected databases - getting the list of databases might
				// be denied so offer the user to type in the name of the database directly
				else
				{
					$page->Label('Database list:');

					// If database error messages should be displayed
					$error = $dbase->GetDatabaseError();
					#$error = "Access denied; you need the SHOW DATABASES privilege for this operation";
					if(strlen($error) > 0)
					{						
						$page->InfoBox('The user <b><tt>'.$login['username'].'</tt></b> is denied access to a list of databases');

						if($config['show_database_error_messages'])
							$page->ErrorDatabaseBox($error);
					}
					else
					{
						$page->Quotation('There are no databases available on '.$login['hostname']);
					}

				
					// Offer the user to type in the database name directly
					$page->FormStart(array('step'=>GetNextStep(STEP_DBSELECT)));

					$page->Label('Type in database name manually:');
					$page->FormInput($login['database'], 'database', array(), 'mediumbox');

					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_DBSELECT)));                        
					$page->FormSubmit('Next');  
					$page->FormClose();  
				}
				
				// -------------------( Option B: Create new database )-------------------//
				if($steps[STEP_DBSELECT]['allowcreate'])
				{
					$page->Paragraph('&nbsp;');
					$page->MainTitle('Create new database', 'dbnew');
					
					if(is_array($msg))
					{
						foreach($msg as $item)
							$page->AddToQueue($item);
					}
					
					$page->Bookmark('newdb');
					$page->Paragraph('Create new database which the system will be installed on.');
					$page->FormStart(array('step'=>STEP_DBSELECT), array('#'=>'newdb'));
					$page->Label('Database name:');
					$page->FormInput($newdb, 'createdb', array(), 'mediumbox');
					$page->FormSubmit('Create new');
					$page->FormClose();
				}
				$page->ShowPage(STEP_DBSELECT);                    
			} 


			/* ===================================================[ DATABASE ACCESS TESTING ]=================================================== */

			// Run multiple tests to see if user has the priviledge
			// to create tables and manipulate data
			$dbtest = $dbase->TestUserPrivileges();				

			// Only continue with this step if enabled. NOTE: $dbtest is kept
			// outside this 'if' statement because it could be used in 
			// below steps, so this is done to keep the installer stable!
			if($steps[STEP_DBACCESS]['enabled'])
			{
				// If some test failed, or autoskip is false and $step is set to this step
				if(!$dbtest['totalsuccess'] || (!$steps[STEP_DBACCESS]['autoskip'] && $step == STEP_DBACCESS))
				{
					$page->MainTitle($steps[STEP_DBACCESS]['title'], 'dbaccess'); 
					
					// Get the username to single variable
					$username = $keywords['connection']['username'];

					// If all the tests rendered successful, show success
					if($dbtest['totalsuccess'])
					{
						$page->SuccessBox('The user <b>'.$username.'</b> has sufficient database access. Proceed to the next step.');
					}

					// But, if the user was denied some of the commands, display warning
					else if(!$dbtest['totalsuccess'])
					{
						$page->WarningBox('The user <b>'.$username.'</b> must have permission to execute all of below commands '.
										  'in order to continue. Either <a href="?step='.STEP_DBCONNECT.'">try another login</a> '.
										  'or contact the support at your hosting service.');
					}

					
					// Explain litlebit what this is for
					$page->Paragraph('These commands are needed to install database tables, and then insert, update and delete data from those tables.');
					
					// Go through all the tests that where made
					$columnCount = ($config['show_database_error_messages']) ? 4 : 3;
					$page->StartTable($columnCount, array('class'=>'dbtests', 'cellspacing'=>'0', 'cellpadding'=>'0'));
					foreach($dbtest as $operation=>$result)
					{
						if($operation == 'totalsuccess')
							continue;

						if($result['success'])
						{
							$page->AddTableData('', array('class'=>'okayico'));
							$page->AddTableData($operation, array('class'=>'operation'));
							$page->AddTableData('Success!', array('class'=>'okay'));

							if($config['show_database_error_messages'])
								$page->AddTableData();
						}
						else
						{
							$page->AddTableData('', array('class'=>'failico'));
							$page->AddTableData($operation, array('class'=>'operation'));
							$page->AddTableData('Failed!', array('class'=>'fail'));

							if($config['show_database_error_messages'])
								$page->AddTableData($result['error'], array('class'=>'errormsg'));
						}
					}
					$page->EndTable();

					$page->FormStart(array('step'=>GetNextStep(STEP_DBACCESS)));
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_DBACCESS)));

					if($dbtest['totalsuccess'])
						 $page->FormSubmit('Next');
					else $page->FormSubmit('Retry');

					$page->FormClose();
					$page->ShowPage(STEP_DBACCESS);
				} 
			}
			
			
			/* ===================================================[ SQL TABLE PREFIX ]=================================================== */
			
			// Get the prefix value to variable
			$prefix = $keywords['connection']['dbprefix'];

			// The prefix checks are too much for one if statement,
			// so the truth value is stored in $validPrefix
			$validPrefix = false;

			// If the prefix is empty, it is only valid if set to optional in config
			// If the prefix has value, it is only valid if it is database friendly
			if(strlen($prefix) == 0)
				$validPrefix = ($steps[STEP_DBPREFIX]['optional']) ? true : false;
			else if(strlen($prefix) > 0)
				$validPrefix = $dbase->IsDatabaseFriendly($prefix);
			
			// The prefix must either contain valid "database friendly" value, or
			// the prefix is set to "optional" in $steps and it is clear
			if($steps[STEP_DBPREFIX]['enabled'] && ($validPrefix == false || $step == STEP_DBPREFIX))
			{
				$page->MainTitle($steps[STEP_DBPREFIX]['title'], 'prefix');
				
				// If installer intended to go somewhere else but was forced to show 
				// this step - the prefix is not valid! If the prefix is empty then
				// the prefix is not optional and MUST have a value
				if($step != STEP_DBPREFIX)
				{
					if(strlen($prefix) == 0)
						$page->WarningBox('Prefix cannot be empty, please enter a table prefix');                            
					else if($dbase->IsDatabaseFriendly($prefix) == false)
						$page->WarningBox('You cannot use <b>'.$prefix.'</b> as prefix value, choose another!');                        
				}
				
				// The user was supposed to go to this step, show success ONLY
				// if prefix is valid - othervice the user is seeing this for
				// the first time and no error should be shown to him!  
				else
				{
					// Prefix is specified and valid, user is jumping between steps
					if(strlen($prefix) > 0 && $dbase->IsDatabaseFriendly($prefix))
					{
						$page->SuccessBox('The prefix <b>'.$prefix.'</b> is accepted. Proceed to the next step.');
					}

					// No prefix is presented, only show notification if prefixes are optional
					else if(strlen($prefix) == 0 && $steps[STEP_DBPREFIX]['optional'])
					{
						$page->InfoBox('The table prefix <b>is optional</b>, but highly recommended. '.
									   'Keep the box empty if you wish not to set a table prefix.');
					}
				}
				
				$page->Paragraph('The prefix is used to prevent collision with other database tables, please enter '.
								 'few characters to uniquely identify your tables from the others.');

				// If a separator will be added at the end of the prefix, notify the user
				if(strlen($steps[STEP_DBPREFIX]['separator']) > 0)
				{
					// If length is 1, then "symbol" - if more than one then "string"
					$word = (strlen($steps[STEP_DBPREFIX]['separator']) == 1) ? 'symbol' : 'string';
					$page->Paragraph('Note that the '.$word.' <b style="color:#FF0000;"><tt>'.$steps[STEP_DBPREFIX]['separator'].
									 '</tt></b> will be added automatically at the end of the prefix. If added manually, '.
									 'it will not be repeated.');
				}
				
				$page->FormStart(array('step'=>GetNextStep(STEP_DBPREFIX)));
				$page->Label('Table prefix:');
				$page->FormInput($prefix, 'dbprefix'); ## <<<<< MUST MATCH THE SPECIAL KEYWORD INDEX TO WORK!!!!
				$page->FormButton('Back', array('step'=>GetPrevStep(STEP_DBPREFIX)));   
				$page->FormSubmit('Next');
				$page->FormClose();
				$page->ShowPage(STEP_DBPREFIX);                     
			}


			/* ===================================================[ INSTALL DATABASE TABLES ]=================================================== */

			// If a request has been made to run SQL installation script on the selected database,
			// run the script and set installation status to sessions
            if($steps[STEP_RUNSQL]['enabled'] && isset($_REQUEST['runsql']) && $dbase->GetDatabaseName() == $_REQUEST['runsql'])
            {
                // Get the installation queries and split them by the "next query" separator,
                // and then run each query and get an array of results. If there is no split
                // then the query will be an array with only one cell - still an array :)
                $results = $dbase->RunQuery( $mask->GetSqlInstallQueries(TRUE) );

                // Go through all the results, and every one of them must be 'true'
                // othervice some query failed and installation is incomplete!
                $databaseSuccessCount = 0;
                $databaseFailedCount = 0;
                foreach($results as $result)
                {
                    if($result['success'])
                        $databaseSuccessCount++;
                    else
                    {
                        $databaseFailedCount++;
                        $databaseErrorMessage .= $result['error']."<br />\n";
                    }
                }
                // If some query failed
                if($databaseFailedCount == 0)
                    $state = 'success';
                else $state = 'failed';

                // Save the installation status to sessions, with database name, the prefix
                // used to during installation and how many queries where successful and failed
                SetDatabaseInstallStatus($dbase->GetDatabaseName(), $state, $keywords['connection']['dbprefix'],
                    $databaseSuccessCount, $databaseFailedCount);
            }

            // Will contain the name of a valid database installations
            $successInstall = array();
            $failedInstall = array();
            $databaseErrorMessage = '';
			$databaseList = $dbase->GetDatabaseNameList();

			// Go through the list of databases to see if any database has 
			// gotten a "success" status, then installation is completed
			foreach(GetDatabaseInstallStatus() as $database=>$status)
			{
				// Only list a database "valid install" if it still exists :)
				if(in_array($database, $databaseList))
				{
					if($status['state'] == 'success')
						 $successInstall[] = $database;
					else $failedInstall[] = $database;
				}
			}
			unset($databaseList);

			// If there has not been any successful installation of database SQL queries yet, or this step is current
			if($steps[STEP_RUNSQL]['enabled'] && (count($successInstall) == 0 || $step == STEP_RUNSQL))
			{
				$page->MainTitle($steps[STEP_RUNSQL]['title'], 'sql');
				
				// If installer intended to go somewhere else but was 
				// forced to show this step - install not completed
				if($step != STEP_RUNSQL && count($successInstall) == 0)
				{
					$page->InfoBox('You must setup the database before going further.');
				}

				// There have been attempts to install, but failed!
				if(count($failedInstall) > 0)
				{
					if(count($failedInstall) == 1)
						$page->ErrorBox('There has been attempt to setup the database on <b>'.$failedInstall[0].'</b> but failed!');
					else
					{
						$str = '';
						$serp = ' and ';
						foreach($failedInstall as $database)
						{
							$str = $serp.'<b>'.$database.'</b>' . $str;
							$serp = ', ';
						}
						$str = substr($str, strlen($serp));
						$page->ErrorBox('There have been attempts to setup the database on '.$str.' but all failed!');
					}

					// If there are failed installs, and the database has some error message - show it
					if($config['show_database_error_messages'] && strlen($databaseErrorMessage) > 0)
						$page->ErrorDatabaseBox($databaseErrorMessage);
				}
				
				// If there are some successfull database 
				// installations, show success message
				if(count($successInstall) > 0)
				{
					// Notifycation of successful installations
					if(count($successInstall) == 1)
						$page->SuccessBox('Database setup has been completed successfully on <b>'.$successInstall[0].'</b>');
					else
					{
						$str = '';
						$serp = ' and ';
						foreach($successInstall as $database)
						{
							$str = $serp.'<b>'.$database.'</b>'.$str;
							$serp = ', ';
						}
						$str = substr($str, strlen($serp));
						$page->SuccessBox('Database setup has been completed successfully on '.$str);
					}


					// If there are successful installs and none of them matches the selected
					// database - then notify the user that he is about to install again on
					// another database and that he should rather just go back and select a 
					// database that has a valid install already
					if(!in_array($dbase->GetDatabaseName(), $successInstall))
					{
						$page->WarningBox('You are about to install tables on <b>'.$dbase->GetDatabaseName().'</b> '.
										  'though a valid install has been successfully done already.<br />&nbsp;<br />'.
										  'You can go back to <a href="?step='.STEP_DBSELECT.'">'.$steps[STEP_DBSELECT]['title'].'</a> '.
										  'and select a databases that has a valid installation.');
					}
				}

				/* 
				*  What happens if the user installs tables on database A, with prefix P1, and then
				*  after installation goes back to prefix step and changes P1 to P2. When the config
				*  is created the prefix value written to it will be P2 since the user changed it but
				*  the database will have prefix P1, causing failure in installation! 
				*
				*  The original implementation of the installer, the step STEP_DBPREFIX comes before 
				*  this step, or STEP_RUNSQL. Which means that if the prefix value is changed in 
				*  STEP_DBPREFIX step (and "next" is clicked in that step), this step is processed. 
				*  So, this prefix problem will be taken care of here.
				*
				*  This whole situation is still quite fragile because and change in the step order
				*  could prevent this issue to be fixed. To fully fix this problem - this fix has to be
				*  moved from here to the top (where queries are executed) so every time a step after
				*  this one is processed, the fix-check will modify the prefix value if needed.
				*
				*  In this version, however, the original implementation releys on the fact that the 
				*  step STEP_DBPREFIX is previous step from STEP_RUNSQL!  */					
				if(in_array($dbase->GetDatabaseName(), $successInstall))
				{
					// Get the status from sessions and check with the current set prefix
					$currentPrefix = $keywords['connection']['dbprefix'];
					$dbStatus = GetDatabaseInstallStatus($dbase->GetDatabaseName());
					if($currentPrefix != $dbStatus['prefix'])
					{
						// Change the prefix value to the one used during installation of SQL queries
						SetSessionPrefix($dbStatus['prefix']);
						$keywords['connection']['dbprefix'] = $dbStatus['prefix'];

						$text = ($dbStatus['prefix'] == '') ? '<i>empty</i>' : '<b>'.$dbStatus['prefix'].'</b>';

						// And then notify the user that the prefix has been changed!
						$page->WarningBox('During the setup of database <b>'.$dbase->GetDatabaseName().'</b>, the prefix '.
										  'was set to <tt><i>'.$dbStatus['prefix'].'</i></tt> but has been changed '.
										  'since then to <tt><i>'.$currentPrefix.'</i></tt><br />&nbsp;<br />'.
										  'The prefix has been reverted back to '.$text.' to prevent incorrect '.
										  'installation.');
					}
				}

				
				// Get the number of tables (and list of table names) from database to
				// know what to warn the user about and perhaps show a list of tables if needed
				$tableList = $dbase->GetTableListFromDatabase();
				if(count($tableList) > 0) 
				{
					if(strlen($prefix) == 0 && $steps[STEP_DBPREFIX]['enabled'])
					{
						// Only show a "no prefix set" warning if there has NOT been a successful
						// database installation on the selected database!!
						if(!in_array($dbase->GetDatabaseName(), $successInstall))
						{
							$page->WarningBox('You have not specified a table prefix, and the database <b>'.$dbase->GetDatabaseName().'</b> '.
								'contains <b>'.count($tableList).'</b> table(s).<br />&nbsp;<br />'.
								'It is highly recommended and there is a much lower risk of table collision if you '. 
								'<a href="?step='.STEP_DBPREFIX.'">specify a table prefix</a>.');
						}
					}

					$page->Paragraph('The database <b>'.$dbase->GetDatabaseName().'</b> contains the following tables:');
					$page->StartTable(3, array('class'=>'tablelist'));
					$page->AddTableData($tableList);
					$page->EndTable();

					// Show a small paragraph notifying the user that he must be certain that
					// he wants to install the tables on a non-empty database. BUT, if the
					// selected database does have "success" and there are some tables, then
					// this list is most likely the results of the installation
					$dbStatus = GetDatabaseInstallStatus($dbase->GetDatabaseName());
					if($dbStatus['state'] != 'success')
					{
						$page->Paragraph('Be completly sure that this is the database you want to use, even though '.
										 'it is not empty before continuing with installation.');
					}
				}
				else
				{
					$page->Paragraph('The database <b>'.$dbase->GetDatabaseName().'</b> is empty and ready for installation.');
				}
				
				// If the installation script should be displayed to the user
				// display it in textarea. BUT - this is readonly and cannot
				// be changed! 
				if($steps[STEP_RUNSQL]['viewsql'])
				{
					// In case the prefix was modified above, update $mask
					// with the updated state of all keywords
					$mask->SetKeywords();
					$page->Label('The SQL installation script:');

					$sqlScript = $mask->FilterSqlSeparator( $mask->GetSqlInstallQueries() );
					$page->Textarea($sqlScript, 'codeblock');
				}

				
				// If the selected database has successful installation - show "next" button
				$dbStatus = GetDatabaseInstallStatus($dbase->GetDatabaseName());
				if($dbStatus['state'] == 'success')
				{
					$page->FormStart(array('step'=>GetNextStep(STEP_RUNSQL)));
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_RUNSQL)));   
					$page->FormSubmit('Next');
					$page->FormClose();
				}
				
				// If the selected database has not yet been tried for installation
				else if($dbStatus == false)
				{
					$page->FormStart(array('step'=>STEP_RUNSQL, 'runsql'=>$dbase->GetDatabaseName()));
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_RUNSQL)));   
					$page->FormSubmit('Install Database Tables on ['.$dbase->GetDatabaseName().']');
					$page->FormClose();
				}

				// If the selected database does not have success, but something else
				// then there where some complications with the previous install
				else
				{
					$page->FormStart(array('step'=>STEP_RUNSQL, 'runsql'=>$dbase->GetDatabaseName()));
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_RUNSQL)));   
					$page->FormSubmit('Retry installation on '.$dbase->GetDatabaseName());
					$page->FormClose();
				}

				$page->ShowPage(STEP_RUNSQL);
			} 


			/* ===================================================[ CREATE ADMINISTRATOR ACCOUNT ]=================================================== */
			
			/*
			 *  ALL POSTED DATA FROM ADMIN FORMS MUST MATCH THE KEYWORDS 
			 *  IN $keywords['admin'] CONFIGURED IN configuration.php
			 */				

			// If enabled and the administrator account does not exist yet, or force showing this step
			if($steps[STEP_ROOTUSER]['enabled'] && (!AdminAccountSuccess() || $step == STEP_ROOTUSER))
			{
				$page->MainTitle($steps[STEP_ROOTUSER]['title'], 'rootuser');

				// Simplify the code litlebit with smaller variable
				$data = $keywords['admin'];

				// If the step is set to current, there is no administrator account 
				// and a request has been made to create administrator account...
				// - then validate the posted data (in $keywords['admin'], or $data)
				// - if data is valid, create the admin user and display success!
				if($step == STEP_ROOTUSER && !AdminAccountSuccess() && isset($_REQUEST['create']) 
					&& $_REQUEST['create'] == 'administrator')
				{

					// Include the methods used to validate the administrator account!
					require('assets'.DIRECTORY_SEPARATOR.'helper.adminaccount.php');
					
					/**************************************************************************************
					*                                                                                     *
					*            SPECIFY YOUR -RULES- OF CREATING ADMINISTRATOR ACCOUNTS HERE             *
					*         - these checks are simply a demonstration to help you get started -         *
					*                                                                                     *
					**************************************************************************************/

					// Assuming the data is valid
					$validData = true;

					// Usernames must have 4 characters or 
					if(strlen($data['admin_username']) < 4)
					{
						$need = 4 - strlen($data['admin_username']);
						$page->InfoBox('Usernames must be at least 4 characters long, you need <b>'.$need.'</b> more characters');
						$validData = false;
					}
					// Usernames cannot be constructed using special characters 
					// like # or %, or foreign special characters 
					if(!IsCommonCharacters($data['admin_username']))
					{
						$page->InfoBox('Usernames must only be constructed using common letters from <tt>A</tt> to <tt>Z</tt> and digits');
						$validData = false;
					}
					// If the two passwords do not match
					if($data['admin_password'] != $data['admin_passagain'])
					{
						$page->InfoBox('Passwords do not match, try again');
						$validData = false;
					}						
					// If the password must be more secure (since this is the root account)
					if(!IsValidPassword($data['admin_password']))
					{
						$page->InfoBox('Password is not secure enough, it must be 6 characters or longer and '.
									   'contain upper case character, digit or symbol as well.');
						$validData = false;
					}
					// If phone number set - it must be valid format
					if(strlen($data['admin_realname']) == 0)
					{
						$page->InfoBox('You must specify the display name');
						$validData = false;
					}						
					// If the password must be more secure (since this is the root account)
					if(!IsValidEmail($data['admin_email']))
					{
						$page->InfoBox('Email address <b>'.$data['admin_email'].'</b> is not valid email');
						$validData = false;
					}
					// If phone number set - it must be valid format
					if(strlen($data['admin_phonenr']) > 0)
					{
						$phonenr = StripCommonPhoneNumberSymbols($data['admin_phonenr']);
						if(!IsNumbersOnly($phonenr))
						{
							$page->InfoBox('Phone number <b>'.$data['admin_phonenr'].'</b> contain illegal characters');
							$validData = false;
						}
					}

					/////////// Root account data evaluation is done ///////////

					if($validData)
					{
						// Create an hashes for this valid data
						$keywords['admin']['admin_password'] = md5( $data['admin_password'] );
						$keywords['admin']['admin_hashkey'] = md5( $data['admin_username'].$keywords['admin']['admin_password'].$data['admin_email'] );

						// Update mask class with the changed keywords
						$mask->SetKeywords();

						// Get the installation queries and split them by the "next query" separator,
						// and then run each query and get an array of results. If there is no split
						// then the query will be an array with only one cell - still an array :)
						$results = $dbase->RunQuery( $mask->GetSqlRootAccessQueries(TRUE) );

						// Go through all the results, and every one of them must be 'true'
						// othervice some query failed and installation is incomplete!
						$databaseErrorMessage = '';
						$databaseSuccessCount = 0;
						$databaseFailedCount = 0;
						foreach($results as $result)
						{
							if($result['success'])
								$databaseSuccessCount++;
							else 
							{
								$databaseFailedCount++;
								$databaseErrorMessage .= $result['error']."<br />\n";
							}
						}

						// If some query failed					
						if($databaseFailedCount == 0)
						{
							SetAdminAccountStatus('success');
							$page->SuccessBox('Root user account has been created successfully. Proceed to the next step.');
						}
						else 
						{
							SetAdminAccountStatus('failed');
							$page->ErrorBox('Root user account could not be created!');

							if($config['show_database_error_messages'])
								$page->ErrorDatabaseBox($databaseErrorMessage);
						}
							
					}

				}
				

				// If selected step and admin account exists, show success message
				else if($step == STEP_ROOTUSER && AdminAccountSuccess())
				{
					$page->SuccessBox('Root user account has been created successfully. Proceed to the next step.');
				}

				// If the installer wanted to go further but could not,
				// the administrator account has not yet been created yet
				// or there was an error creating it
				else if($step != STEP_ROOTUSER)
				{
					if(AdminAccountFailed())
						$page->ErrorBox('Root user account failed to be created! Try again or contact support.');

					if(!AdminAccountExists())
						$page->InfoBox('Root user account does not exist yet! You must create one to continue!');
				}


				// Setup the administrator form if account does not exist
				// or it has failed before for some reason
				if(!AdminAccountSuccess())
				{
					// The form reloads the current step
					$page->FormStart(array('step'=>STEP_ROOTUSER, 'create'=>'administrator'));

					// Username, password and repeat password
					$page->Label($page->Must('* ').'Username:');
					$page->FormInput($data['admin_username'], 'admin_username');

					$page->Label($page->Must('* ').'Password:');
					$page->FormPassword('', 'admin_password');

					$page->Label($page->Must('* ').'Repeat password:');
					$page->FormPassword('', 'admin_passagain');

					// Subtitle to next section
					$page->SubTitle('Personal information:');

					// Display name and email
					$page->Label('Display name:');
					$page->FormInput($data['admin_realname'], 'admin_realname');

					$page->Label('Email address:');
					$page->FormInput($data['admin_email'], 'admin_email');

					$page->Label('Phone number:');
					$page->FormInput($data['admin_phonenr'], 'admin_phonenr');
					
					// Buttons to create the admin and go back
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_ROOTUSER)));   
					$page->FormSubmit('Create administrator account');
					$page->FormClose();
				}

				// User account exists, display some data
				else
				{
					// The form reloads the current step
					$page->FormStart(array('step'=>GetNextStep(STEP_ROOTUSER)));

					$page->StartTable(2, array('class'=>'administrator'));

					$page->AddTableData('Username:', array('class'=>'label'));
					$page->AddTableData($data['admin_username'], array('class'=>'data'));

					$page->AddTableData('Display name:', array('class'=>'label'));
					$page->AddTableData($data['admin_realname'], array('class'=>'data'));

					$page->AddTableData('Email address:', array('class'=>'label'));
					$page->AddTableData($data['admin_email'], array('class'=>'data'));

					$page->AddTableData('Phone number:', array('class'=>'label'));
					$page->AddTableData($data['admin_phonenr'], array('class'=>'data'));

					$page->EndTable();
					
					// Buttons to create the admin and go back
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_ROOTUSER)));   
					$page->FormSubmit('Next');
					$page->FormClose();
				}

				$page->ShowPage(STEP_ROOTUSER);
			}


			/* ===================================================[ CREATE FINAL-OUTPUT CONFIGURATION FILE ]=================================================== */

			// If writing config is enabled - the code here MUST be executed!
			// but the page might not be needed to be displayed
			if($steps[STEP_WRITECONFIG]['enabled'])
			{
				$page->MainTitle($steps[STEP_WRITECONFIG]['title'], 'filenew');

				// The creation state will be stored in this variable
				$configCreated = false;

				// Get the name of final output name and path folder
				$folder = FixPath($steps[STEP_WRITECONFIG]['savetofolder']);
				$file = trim($steps[STEP_WRITECONFIG]['maskname']);

				// Write the config contents to a file
				if(file_put_contents($folder.$file, $mask->GetConfigFile()))
				{
					$page->SuccessBox('Configuration has been created successfully!');
					$configCreated = true;
				}
				else
				{
					$page->ErrorBox('The Installer is unable to create configuration <b>'.$folder.$file.'</b>, check your <tt>chmod</tt> '.
									'permissions or contact support to get this issue resolved.');
					$configCreated = false;
				}
				$folder = FixPath($steps[STEP_WRITECONFIG]['savetofolder1']);
				if(file_put_contents($folder.$file, $mask->GetConfigFile()))
				{
					$page->SuccessBox('Admin Configuration has been created successfully!');
					$configCreated = true;
				}
				else
				{
					$page->ErrorBox('The Installer is unable to create configuration <b>'.$folder.$file.'</b>, check your <tt>chmod</tt> '.
									'permissions or contact support to get this issue resolved.');
					$configCreated = false;
				}

				// If the config was created - the installer is done!
				if($configCreated)
				{
					$page->FormStart(array('step'=>GetNextStep(STEP_WRITECONFIG)));
					$page->FormSubmit('Finished');
					$page->FormClose();
				}

				// Offer retry if creation failed
				else
				{
					$page->FormStart(array('step'=>STEP_WRITECONFIG));
					$page->FormButton('Back', array('step'=>GetPrevStep(STEP_WRITECONFIG)));   
					$page->FormSubmit('Retry');
					$page->FormClose();
				}


				// If there are some writing problems, or this step should not be
				// auto skipped and $step is currently set to view this step  
				if(!$configCreated || (!$steps[STEP_WRITECONFIG]['autoskip'] && $step == STEP_WRITECONFIG))
					$page->ShowPage(STEP_WRITECONFIG);

				// Page should not be shown, so the html queue is cleared
				// so the next step can start fresh
				else
					$page->ClearHtmlQueue();
			}

   

			/* ===================================================[ If there are no steps to process > DONE! ]=================================================== */

            TheLastInstallerStep();
            
        } // End if output config file does not exist or is empty
        
        
        /* ===================================================[ INSTALLER IS FINISHED ]=================================================== */
        
        else // the output config DOES exist!
        {
            TheLastInstallerStep();
        }
    }


	/**
	 *  The last installer step is shown:
	 *  - after STEP_WRITECONFIG if autoskip is enabled
	 *  - in the "else" statement above this function :)
	 */
	function TheLastInstallerStep()
	{
		global $page;
		global $mask;
		global $steps;
		global $config;

		// Only show "outro" message if enabled
		if($steps[STEP_FINISHED]['enabled'])
		{
			// Installation is done!
			$page->MainTitle($steps[STEP_FINISHED]['title'], 'cake');
			$page->Paragraph( $mask->GetFinishedMessage() . '<br />&nbsp;');
		}

		// If the installer will be ignored after installation
		// then do not offer the user to remove files or reset
		// the installation - Installer will be ignored either way
		if($config['ignore_installer_when_done'])
		{
			$page->FormStart();
			$page->FormSubmit('Finished!');
			$page->FormClose();
			$page->ShowPage(STEP_FINISHED, true, false);
		}

		// Only continue if self destruction is enabled
		if($config['allow_self_destruction'])  
		{
			// If the user requested all installer files should be deleted, 
			// or the installer should automatically self destruct
			if ($config['automatically_self_destruct'] || 
			   (isset($_REQUEST['doneaction']) && $_REQUEST['doneaction'] == 'selfdestruct') )
			{
				/* Since the PHP files have ALL been "included", their
				*  existance does not matter anymore. The rest of the 
				*  script will run normally even though all files have
				*  been deleted. */

				// Only include destroyer functions when they are needed
				// and the helpers are in the same folder - thus no "asset/"
				require('assets'.DIRECTORY_SEPARATOR.'helper.destroyer.php');

				// Delete all files configured by config and display results
				$deletionStatus = DeleteYourself();
				if(is_array($deletionStatus))
				{
					$dirs = $deletionStatus['dirs'];
					$files = $deletionStatus['files'];

					// Removing files
					if($files['success'] == $files['total'] && $files['failed'] == 0)
						$page->SuccessBox('All files where removed successfully!');

					else if($files['success'] == 0 && $files['total'] > 0)
						$page->WarningBox('Unable to remove files, you have to remove them manually');

					else 
						$page->ErrorBox('Error occur removing files, <b>'.$files['failed'].'</b> failed to be removed and need manual removal.');

					// Removing directories
					if($dirs['success'] == $dirs['total'] && $dirs['failed'] == 0)
						$page->SuccessBox('All directories where removed successfully!');

					else if($dirs['success'] == 0)
						$page->WarningBox('Unable to remove directories, you have to remove them manually');

					else
						$page->ErrorBox('Error occur removing directories, <b>'.$dirs['failed'].'</b> failed to be removed and need manual removal.');


					$page->FormStart();
					$page->FormSubmit('Finished!');
					$page->FormClose();
					$page->ShowPage(STEP_FINISHED, true, false);
				}
			}
		}	

		/*
		*  Deletion has not been initiated, so show options on what to do depending
		*  on what is configured in the config
		*/
		
		// Should the current config be deleted and installation reset?
		if($config['allow_overriding_oldconfig'])
		{
			$page->SubTitle('Start all over', 'remconf');
			$page->Paragraph('If you are not happy with the installation, you can make the Installer remove '.
							 'current configuration and start all over again. <b>WARNING:</b> Any database '.
							 'change cannot be undone! You have to undo the database installation manually.');

			# NOTE!
			# this done-action is processed in [helper.sessions.php]!
			$page->FormStart(array('doneaction'=>'removeold')); 
			$page->FormSubmit('Remove current configuration and start over');
			$page->FormClose();
		}
		
		// If self-destruction is enabled but does not start it automatically
		if($config['allow_self_destruction'])              
		{
			$page->SubTitle('Delete Installer', 'exit');		
			$page->Paragraph('Installation is completed but the Installer is preventing the installed system to '.
							 'launch. You can make the Installer remove itself from the webserver if you wish '.
							 'not to remove them yourself.');

			$page->FormStart(array('doneaction'=>'selfdestruct')); 
			$page->FormSubmit('Delete all Installer files from the Webserver');
			$page->FormClose();
		}

		// If the installer is unable to do anything after installation!
		if(!$config['allow_overriding_oldconfig'] && !$config['allow_self_destruction'])
		{
			$page->WarningBox('You have to manually remove the Installer folder: <b>'.
				FixPath(INST_BASEDIR.INST_RUNFOLDER).'</b> in order to view the installed system');
		}

		
		$page->ShowPage(STEP_FINISHED, true, false);
	}