<?php if(!defined('INST_BASEDIR')) die('Direct access is not allowed!');

    /* ====================================================================
    *
    *                             PHP Setup Wizard
	*
    *                            -= CONFIGURATION =-
    *
    *  ================================================================= */
    


    /*=====================================================================================*\
	|*                                                                                     *|
    |*                                 INSTALLATION STEPS                                  *|
	|*                                                                                     *|
    \*=====================================================================================*/
	define('STEP_MODULECHECK',	'phpmodules');
	define('STEP_IOFILES',		'iopermission');
	define('STEP_WELCOME',		'welcomemsg');
	define('STEP_TERMSOFUSE',	'termsofuse');
	define('STEP_TIMEZONE',		'timezones');
	define('STEP_LANGUAGE',		'languages');
	define('STEP_DBCONNECT',	'dbserverlogon');
	define('STEP_DBSELECT',		'dbselection');
	define('STEP_DBACCESS',		'dbaccesstest');
	define('STEP_DBPREFIX',		'dbprefix');
	define('STEP_RUNSQL',		'installsql');
	define('STEP_ROOTUSER',		'rootuser');
	define('STEP_WRITECONFIG',	'writeconfig');
	define('STEP_FINISHED',		'finishedmsg');

	// This constant is used in overview.php as a "special" step to show the contents
	// of this file! This step IS NOT IMPLEMENTED in the installer.php - only overview.php
	define('STEP_SETTOVERVIEW',	'settoverview');


	$steps = array(

	/*--------------------------------------------------------------------|
	|  PHP Module check                                                   |
	|---------------------------------------------------------------------|
	|  Does the hosting service support the necessary PHP modules for this
	|  installer to function or not
	|  
	|  > title    : The title of this step in the process box
	|  > enabled  : Should this step be included or not
	|  > autoskip : If moduels are installed, this step will be skipped
	|  > modules  : What PHP modules to check
	\---------------------------------------------------------------------*/
	STEP_MODULECHECK => array(
		'title'    =>  'PHP Extension Test',
		'enabled'  =>  false,
		'autoskip' =>  true,
		'modules'  =>  array('mysql','mcrypt'),
		),


	/*---------------------------------------------------------------------|
	|  I/O File Permissions                                                |
	|----------------------------------------------------------------------|
	|  Checks wherther if the installer can in fact create and write to 
	|  files on the server, which is the core purpose of the installer
	|
	|  !!NOTE!!:  This step will verify the "Create Configuration File" 
	|             step configuration. So IF this step has been passed the
	|             output config will be able to be created/updated
	|  
	|  > title     : The title of this step in the process box
	|
	|  > enabled  : Should this step be included or not
	|
	|  > autoskip  : If permission is granted, this step will be skipped
	|                whitout notifying the user at all. Othervice the user
	|                will be prompted "can create files" success message
	|
	|  > enabled   : If disabled, the installer continues without the 
	|                certany that files can be created and written to
	\---------------------------------------------------------------------*/
	STEP_IOFILES => array(
		'title'        =>  'File Permissions',
		'enabled'      =>  true,
		'autoskip'     =>  true,
		),


	/*--------------------------------------------------------------------|
	|  Welcome Message                                                    |
	|---------------------------------------------------------------------|
	|  Show welcome message or short introduction to what is being 
	|  installed
	|  
	|  > title    : The title of this step in the process box
	|  > enabled  : Should this step be included or not
	|  > maskname : The mask filename - HTML is supported   
	\---------------------------------------------------------------------*/
	STEP_WELCOME => array(
		'title'        =>  'Introduction',
		'enabled'      =>  true,
		'maskname'     =>  'welcome_message.html',
		),


	/*---------------------------------------------------------------------|
	|  Terms Of Use Agreement                                              |
	|----------------------------------------------------------------------|
	|  If enabled the user MUST approve the "terms of use" agreement before
	|  continuing with the install
	|  
	|  > title    : The title of this step in the process box
	|  > enabled  : Should this step be included or not
	|  > maskname : The mask filename - plain-text only!   
	\---------------------------------------------------------------------*/
	STEP_TERMSOFUSE => array(
		'title'        =>  'Terms Of Use Agreement',
		'enabled'      =>  false,
		'maskname'     =>  'terms_of_use.txt',
		),


	/*---------------------------------------------------------------------|
	|  Language Selection                                                  |
	|----------------------------------------------------------------------|
	|  Does the system you are installing require a language selection?
	|
	|  > title        : The title of this step in the process box
	|  > enabled      : Should this step be included or not
	|  > supported    : Array of supported languages, with country code and name
	\---------------------------------------------------------------------*/
	STEP_LANGUAGE => array(
		'title'        =>  'Select Language',
		'enabled'      =>  false,
		'supported'    =>  array(
							'gb'=>'English (UK)',
							'us'=>'English (USA)',
							'de'=>'German',
							'fr'=>'French'),
		),


	/*---------------------------------------------------------------------|
	|  Timezone Selection                                                  |
	|----------------------------------------------------------------------|
	|  Does the system you are installing require a timezone selection?
	|
	|  > title        : The title of this step in the process box
	|  > enabled      : Should this step be included or not
	\---------------------------------------------------------------------*/
	STEP_TIMEZONE => array(
		'title'        =>  'Select Timezone',
		'enabled'      =>  false,
		),


	/*=====================================================================|
	|  Database Server Connection                        [ REQUIRED STEP ] |
	|======================================================================|
	|  Prompts username, password and host input fieldss that the user 
	|  fills out to logon to the server.
	|  
	|  > title        : The title of this step in the process box
	|
	|  > portoptional : If enabled, the user has the option to fill in port
	|                   value. If keept empty it will be ignored when 
	|                   connection is made
	|
	|  > encryptlogin : When login is successful, this flag will make sure
	|                   the username, password, host and database name will 
	|                   be encrypted in session using the Blowfish encryption. 
	|                   It is highly recommended that you enable this feature 
	|                   to enhance security during the installation. 
	\---------------------------------------------------------------------*/
	STEP_DBCONNECT => array(
		'title'        =>  'Server Connection',
		'portoptional' =>  false,
		'encryptlogin' =>  false, 
		),


	/*=====================================================================|
	|  Database Selection / Creation                     [ REQUIRED STEP ] |
	|======================================================================|
	|  Lists all databases available and total number of tables in each 
	|  database. Offers the user to choose one database for the installation. 
	|  New databasew can also be created in this step
	|  
	|  > title        : The title of this step in the process box
	|  > allowcreate  : Can the user create new databases as well
	\---------------------------------------------------------------------*/
	STEP_DBSELECT => array(
		'title'        =>  'Database Selection',
		'allowcreate'  =>  true,
		),


	/*---------------------------------------------------------------------|
	|  Database Access Test                                                | 
	|----------------------------------------------------------------------|
	|  A test is made to see if the selected user has the permit to create
	|  tables, insert, update, delete etc. If this step is set to autoskip,
	|  then it will only be shown if something in the test will render fail
	|  
	|  > title    : The title of this step in the process box
	|  > autoskip : Possible to skip this step if all tests are successful
	|  > enabled  : Should this step be included or not
	\---------------------------------------------------------------------*/
	STEP_DBACCESS => array(
		'title'        =>  'Database Access Test',
		'autoskip'     =>  true,
		'enabled'      =>  false,
		),


	/*---------------------------------------------------------------------|
	|  Database Table Prefix                                               |
	|----------------------------------------------------------------------|
	|  A very usefull option when you want to make sure that the table names 
	|  will be unique and not collide with other table names. Either from 
	|  other systems or another version of this same system 
	|
	|  > title      : The title of this step in the process box
	|
	|  > enabled    : Should this step be included or not
	|
	|  > optional   : The table prefix is not always wanted, though it is
	|                 a nice feature to offer. If you want the table prefix
	|                 to be enabled but not forced - set optional to true
	|                 so the user can self deside if he wants a prefix. Set
	|                 it to false and the prefix must have a value, thus
	|                 forced to have some value
	|
	|  > separator  : This string value is added at the end of the prefix.
	|                 If it is already at the end (the user added it) then
	|                 it will not be repeated. Keep the box empty if you
	|                 wish not to use a separator
	\---------------------------------------------------------------------*/
	STEP_DBPREFIX => array(
		'title'        =>  'Table Prefix',
		'enabled'      =>  false,
		'optional'     =>  true,
		'separator'    =>  '',  
		),


	/*---------------------------------------------------------------------|
	|  Execute SQL Queries (Install Tables)                                |
	|----------------------------------------------------------------------|
	|  A block of SQL well be executed, assuming the system needs to run 
	|  SQL commands to create tables and perhaps insert few rows as well. 
	|
	|  > title        : The title of this step in the process box
	|  > enabled      : Should this step be included or not
	|  > viewsql      : Should the install script be visible to the user,
	|                   if true then showin in a box with scrollbars
	|  > maskname     : The mask filename - plain-text only!
	\---------------------------------------------------------------------*/
	STEP_RUNSQL => array(
		'title'        =>  'Install Database Tables',
		'enabled'      =>  true,
		'viewsql'      =>  true,
		'maskname'     =>  'nodcms.sql',
		),


	/*---------------------------------------------------------------------|
	|  Create Administrator Account                                        |
	|----------------------------------------------------------------------|
	|  Should the installer include "Create Administrator Account" for the 
	|  system that is beeing installed? 
	|
	|  !!NOTE!!:  This step has to be customized to fit the needs of the
	|             system being installed. It is nearly impossible to count 
	|             for all possibilities when it comes to install users to 
	|             various systems. 
	|
	|  > title        : The title of this step in the process box
	|  > enabled      : Should this step be included or not
	|  > encryptdata  : The administrator data will be encrypted in sessions
	|  > maskname     : Name of the mask file containing the "insert" query
	\---------------------------------------------------------------------*/
	STEP_ROOTUSER => array(
		'title'        =>  'Create Administrator Account',
		'enabled'      =>  false,
		'encryptdata'  =>  true,
		'maskname'     =>  'insert_root_access.sql',
		),


	/*---------------------------------------------------------------------|
	|  Create Configuration File                                           |
	|----------------------------------------------------------------------|
	|  Create a config file for the system being installed, and write all 
	|  the information the whole setup has been collecting.
	|
	|  > title        : The title of this step in the process box
	|
	|  > enabled      : Should this step be included or not
	|
	|  > autoskip     : If creation is successful, this step will be skipped
	|                   whitout notifying the user at all. Othervice the user
	|                   will be prompted "config created" success message
	|
	|  > maskname     : The mask filename. Note that the name of the mask
	|                   file WILL be the name of the output config. So 
	|                   rename the config file accordingly.
	|
	|  > savetofolder : Where will the config file be saved. This setting will 
	|                   create these folders from where the installer was launched 
	|                   from, which usually is index.php. Some tips:
	|
	|                     "folder/" : Create new folder and put the config
	|                                 inside that folder
	|
	|                  "../folder/" : Go back one folder, create the new folder
	|                                 there and put the config in it
	|
	|                            "" : No folder should be created, the config
	|                                 will be created at the same place as the
	|                                 file that launched the installer (index.php)
	|
	|                   NOTE: If some string is presented, that string will 
	|                         be put in the PHP function "mkdir()". To read
	|                         more about creating folders - check PHP documentation	                       
	|
	|  > updateonzero : In some cases, the config file has to be created 
	|                   manually due to security reasons. When that happens
	|                   the installer cannot rely on the fact that if the
	|                   config exists, we are done. So, the work around is
	|                   that you create the file manually and make it totally
	|                   empty! If the installer detects the output file, and
	|                   this setting is set to true - then if the file has 
	|                   zero bytes, we are not done and will keep installing!
	\---------------------------------------------------------------------*/
	STEP_WRITECONFIG => array(
		'title'        =>  'Create Configuration File',
		'enabled'      =>  true,
		'autoskip'     =>  true,
		'updateonzero' =>  true,
		'maskname'     =>  'database.php',
		'savetofolder' =>  '../nodcms/config/',
		),
		
//	STEP_WRITECONFIG_ADMIN => array(
//		'title'        =>  'Create Admin Configuration File',
//		'enabled'      =>  true,
//		'autoskip'     =>  true,
//		'updateonzero' =>  true,
//		'maskname'     =>  'database.php',
//		'savetofolder' =>  '../../backend/config/', 
//		),


	/*---------------------------------------------------------------------|
	|  Finished Message                                                    |
	|----------------------------------------------------------------------|
	|  Show finished message or short outro to what has been installed
	|  
	|  > title    : The title of this step in the process box
	|  > enabled  : Should this step be included or not
	|  > maskname : The mask filename - HTML is supported   
	\---------------------------------------------------------------------*/
	STEP_FINISHED => array(
		'title'        =>  'All done!',
		'enabled'      =>  true,
		'maskname'     =>  'finished_message.html',
		),
	);




    
    
    
    /*=====================================================================================*\
	|*                                                                                     *|
    |*                                 ADDITIONAL SETTINGS                                 *|
	|*                                                                                     *|
    \*=====================================================================================*/
    $config = array(


	/*--------------------------------------------------------------------|
	|  Installer Title                                                    |
	|---------------------------------------------------------------------|
	|  What should the name of the installer be
	\--------------------------------------------------------------------*/
	'installer_title_name' => 'NodCMS - Market Place - INSTALLER',

	
    /*--------------------------------------------------------------------|
	|  PHP Error Messages                                                 |
	|---------------------------------------------------------------------|
	|  Should the installer show PHP error messages or not. The installer
	|  will display errors in a mush more friendly fashion. Set to TRUE if 
	|  you are creating custom steps and need some debugging. 
	\--------------------------------------------------------------------*/
	'show_php_error_messages' => false,

     
    /*--------------------------------------------------------------------|
	|  Show Database Error Messages                                       |
	|---------------------------------------------------------------------|
	|  Error messages generated by the database server when SQL queries
	|  are unsuccessful. Useful when users are at "advanced" level, but 
	|  not considered needed when users are at "novice" level.
	\--------------------------------------------------------------------*/
    'show_database_error_messages' => false,


    /*--------------------------------------------------------------------|
	|  Masks Folder name                                                  |
	|---------------------------------------------------------------------|
	|  What is the name of the masks folder. Default: "masks"
	\--------------------------------------------------------------------*/
    'mask_folder_name' => 'masks',


	/*--------------------------------------------------------------------|
	|  Ignore Installer When Process Is Done                              |
	|---------------------------------------------------------------------|
	|  When the installer detects that the output config file exists and 
	|  it contains some data, it can simply "ignore itself" and continue
	|  running the website. Else it will notify the user to remove the
	|  installer folder
	\--------------------------------------------------------------------*/
    'ignore_installer_when_done' => true,


	/*--------------------------------------------------------------------|
	|  Allow Overriding Current Config                                    |
	|---------------------------------------------------------------------|
	|  When the installer detects that the output config file exists and 
	|  it contains some data, and "ignore" setting is set to false, should 
	|  the installer offer a "start all over" button. If pressed, the 
	|  current config will be deleted from the server as the installer 
	|  reloads and then acknowledges that there is a need for config creation
	|
	|  NOTE: 'ignore_installer_when_done' must be FALSE for this to work!
	\--------------------------------------------------------------------*/
    'allow_overriding_oldconfig' => false,
    

	/*--------------------------------------------------------------------|
	|  Allow Complete Self-Destruction                                    |
	|  Automatically launch Self-Destruction                              |
	|---------------------------------------------------------------------|
	|  When the installer detects that the output config file exists and 
	|  it contains some data, and "ignore" setting is set to false, should 
	|  the installer offer a "remove files" button. If pressed, all files
	|  in the Installer folder will be deleted from the server, or at least 
	|  an attempt will be made to do so
	|
	|  If the setting 'allow_self_destruction' is set to true, this setting
	|  can enforce that mechanism to execute automatically when installer 
	|  is done, and the user simply presses "Done" or "Finished" button and
	|  the installer files will be removed as the installed system is 
	|  starting up for the first time
	\--------------------------------------------------------------------*/
    'allow_self_destruction'       => false,    
	'automatically_self_destruct'  => false,


	/*--------------------------------------------------------------------|
	|  Self-Destruction Filter / Folder Removal                           |
	|---------------------------------------------------------------------|
	|  If for some reason, you do not want the installer to remove some
	|  spesific extensions from the Installer folder, specify the ones that
	|  you want to remove, but keep the array empty to remove everything
	|
	|  - Example:  array('php', 'css');  = Removes only PHP and CSS files
	|  - Example:  array();              = Removes ALL files!
	\--------------------------------------------------------------------*/
	'self_destruct_filter'          => array(),
	'self_destruct_removes_folders' => true,


	/*--------------------------------------------------------------------|
	|  Session Prefix                                                     |
	|---------------------------------------------------------------------|
	|  Uniquely identifies the sessions for the installer, important! 
	|  Default value is 'INST_' but change it if you are using it yourself
	\--------------------------------------------------------------------*/
    'session_prefix' => 'INST_',


	/*--------------------------------------------------------------------|
	|  Session Encryption Hash                                            |
	|---------------------------------------------------------------------|
	|  The sever connection credentials will be encrypted using a blowfish
	|  encryption. It will need a key that is used to encrypt/decrypt the
	|  the login info. To keep the encryption safe and unique for your 
	|  installer - replace the key below and try to obscure it as much as
	|  you can for added security.
	\--------------------------------------------------------------------*/
    'encryption_key' => '*r3p14ce_tHiz-w1Th>y0uR<paS5phr4ze!*',


	/*--------------------------------------------------------------------|
	|  Debug Sessions / Posts / Gets                                      |
	|---------------------------------------------------------------------|
	|  Do you want to know the values of Sessions, Posts and Gets at the
	|  start of each reload? This is very helpful when adding custom steps
	\--------------------------------------------------------------------*/
    'debug_sessions' => false,
	'debug_posts'    => false,
	'debug_gets'     => false,
    );	



    /*=====================================================================================*\
    |*                                                                                     *|
    |*                                    MASK KEYWORDS                                    *|
	|*                                                                                     *|
    \*=====================================================================================*/
    $keywords = array(
    
    /*--------------------------------------------------------------------|
	|  Opening-Closing Brackets                                           |
	|---------------------------------------------------------------------|
	|  These are the symbols that represents starting and closing of 
	|  keywords, curly braces are default values. Example: {username}
	\--------------------------------------------------------------------*/
    'open_bracket'  => '{',
    'close_bracket' => '}',


	/*--------------------------------------------------------------------|
	|  [!!IMPORTANT!!] Next Query Separator                               |
	|---------------------------------------------------------------------|
	|  The function "mysql_query()" does not support multi-query execution
	|  and we cannot be certain that the "mysqli" extension is installed at
	|  every webserver. So, we have to somehow be able to run multiple
	|  queries using a function that does not support such thing.
	|
	|  The simplest way is to solve this is to add some kind of separator 
	|  into the mask file, indicating where an query ends and new one begins.
	|  This might be a tedious work-around, but we can be certain that
	|  multi-queries will be supported in at least 99.99% of times :)
	|
	|  Keep in mind that this string is an SQL comment, so it will be 
	|  ignored by the MySQL database if not removed.
	\--------------------------------------------------------------------*/
    'next_query'  => '-- NEXT_QUERY --',
    

	/*--------------------------------------------------------------------|
	|  [RESERVED] Installer Keywords : Connection                        |
	|---------------------------------------------------------------------|
	|  These keywords in this list MUST be here in order for the installer 
	|  to function properly! Fill in the empty brackets if you want to set 
	|  some default values. If default values are "correct" or accepted by 
	|  some of the steps, the user will be promted a success message when
	|  that step is entered, like it was posted by the user himself.
	\--------------------------------------------------------------------*/
    'connection' => array(
        'hostname' => 'localhost',  # default: localhost
        'username' => '',
        'password' => '',
        'database' => '',
		'dbport'   => '', # optional: enable it in STEP_DBCONNECT in $steps
        'dbprefix' => '', # optional: enable it in STEP_DBPREFIX in $steps
		),
    

	/*--------------------------------------------------------------------|
	|  Installer Keywords : Administrator Account                         |
	|---------------------------------------------------------------------|
	|  If the step STEP_ROOTUSER is enabled, these are the keywords that 
	|  will be used with that step. Here you can specify the default values
	|  and add more keywords that you might need
	|
	|  NOTE: The current keys and values are only for demonstration on how
	|        to use the installer mechanics to your advantage. Change these
	|        keys to what ever you like and add more if you need. Remember
	|        to keep your mask file updated with the keywords here
	|
	|  NOTE: The prefix "admin_" is added here to prevent the 'connection'
	|        keywords overriding these. In other worder - if a keyword here
	|        is "username" then the "username" in 'connection' will override
	|        the value here and the installer would be broken!
	\--------------------------------------------------------------------*/
    'admin' => array(
        'admin_username'  => '', 
        'admin_password'  => '', 
		'admin_passagain' => '', 
		'admin_realname'  => '', 
        'admin_email'     => '',
		'admin_phonenr'   => '',
		
		// This value is not set by the "installer user" but should be specified
		// by the system's developer of the system being installed. So, 
		// this key is kept here to be available to the mask files instead 
		// of putting this value directly into the mask file.
		'admin_level'     => 10,

		// In some systems there are "hashkeys" to protect the data from
		// being updated outside the system itself. So, this is generated
		// when all the "admindata" is valid and ready to be inserted. This
		// key is set by the step when process is done. 
		'admin_hashkey'   => '',
		),


	/*--------------------------------------------------------------------|
	|  Installer Keywords : Special / Custom                              |
	|---------------------------------------------------------------------|
	|  These keywords are custom to your installation. Any keyword can be
	|  added as long as it does not collide with reserved keywords. If 
	|  collision occurs, the reserved keyword will override the special one. 
	|  You can either use these keywords for some custom steps  or just to have
	|  them available to use in the mask files (like welcome message etc.)
	\--------------------------------------------------------------------*/
    'special' => array(    

		// These three keywords are used in welcome and finished
		// messages, just a demonstration on special keywords
        'company' => 'Basic Company', 
        'product' => 'Basic Product',
        'version' => '4.5',

		// These keywords are used with Timezone and Language steps
		'timezone' => '0',
		'language' => 'gb',

		// Want to show the todays date
		// in welcome/outro message?
		'datenow'  => date('H:m:s, F j, Y'),
		), 
    );
   