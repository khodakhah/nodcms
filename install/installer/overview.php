<?php 

    /* ====================================================================
    *
    *                            PHP Setup Wizard
	*
    *                      -= FILE/SETTINGS OVERVIEW =-
	*
	*  This script is intended to give an overview of what has been configured,
	*  if all files exists, if mask files contain any of the keywords etc. In
	*  final "deployment" of your product, this file should not be included as
	*  this is only indented for those who are configuring the Installer
    *
    *  ================================================================= */   



	/********************************[ DO CONFIGURATION/INSTALLER EXIST ]********************************/

	if(!is_file('configuration.php') || !is_readable('configuration.php'))
		die('The configuration file <b>configuration.php</b> is either not found or is unreadable');

	if(!is_file('installer.php') || !is_readable('installer.php'))
		die('The main Installer file <b>installer.php</b> is either not found or is unreadable');


	/********************************[ DO CLASSES/HELPERS EXIST ]********************************/

	// Asset folder
	$assetFolder = 'assets';

	// Files that MUST be in Assets folder
	$includes = array(
		'class.databases.php', 
		'class.htmlmaker.php', 
		'class.masks.php', 
		'helper.functions.php', 
		'helper.sessions.php', 
		'helper.adminaccount.php'
		);


	// The folder "assets" contains classes and helpers
	if(!is_dir($assetFolder))
	{
		echo 'The folder <b>'.$assetFolder.'</b> is not found, it contains core Installer classes and helpers:';
		echo '<ul>';
		foreach($includes as $include)
			echo '<li>'.$include.'</li>';
		echo '</ul>';
		die();
	}	
	
	// Check availability of files
	foreach($includes as $include)
	{
		$file = $assetFolder.DIRECTORY_SEPARATOR.$include;
		if(!is_file($file) || !is_readable($file))
		{
			die('The file <b>'.$file.'</b> is either not found or is unreadable');
		}
	}



	/********************************[ ENABLED STEPS ]********************************/

	define('INST_RUNSCRIPT', pathinfo(__FILE__, PATHINFO_BASENAME));
    define('INST_BASEDIR',	 str_replace(INST_RUNSCRIPT, '', __FILE__));
    define('INST_RUNFOLDER', '');
	define('INST_RUNINSTALL', 'installer.php');

	include('configuration.php');
	include($assetFolder.DIRECTORY_SEPARATOR.'class.htmlmaker.php');
	include($assetFolder.DIRECTORY_SEPARATOR.'class.masks.php');

	$page = new Inst_HtmlMaker();
    $mask = new Inst_Masks();

	$page->HideDisabledSteps(false);
	$page->HideAutoskipSteps(false);
	$page->UseStepWait(false);
	
	// Get the settings for some requested step
	$step = (isset($_REQUEST['step'])) ? $_REQUEST['step'] : 'overview';
	$sett = false;
	if(isset($steps[$step]))
		$sett = $steps[$step];

	// If the settings are 'false', then the step is not
	// configured in the $steps variable - show $config overview!

	if($sett === false)
	{
		$page->MainTitle('Main Configuration', 'settings');
		$page->StartTable(2, array('class'=>'dbtests'));
		foreach($config as $key=>$value)
		{
			$show = GetSettingsValue($value);
			$page->AddTableData('<b>'.$key.'</b>', array('style'=>'text-align:right; padding-right:12px;'));
			$page->AddTableData('<tt>'.$show.'</tt>');
		}
		$page->EndTable();
		$page->Paragraph();

		$page->MainTitle('Installer Keywords', 'keywords');
		$page->StartTable(2, array('class'=>'dbtests'));
		foreach($keywords as $key=>$value)
		{
			if(is_array($value))
			{
				$page->AddTableData('<b>'.$key.'</b>', array('style'=>'text-align:right; padding-right:12px;'));
				$page->AddTableData('<span style="color:#BEBEBE">'.count($value).' keywords</span>');

				foreach($value as $wordkey=>$wordvalue)
				{
					$show = GetSettingsValue($wordvalue);
					$page->AddTableData('');
					
					if(strlen($show) > 0)
						 $page->AddTableData('<tt>'.$wordkey.' = <b>'.$show.'</b></tt>');
					else $page->AddTableData('<tt>'.$wordkey.'</tt>');
				}
			}
			else
			{
				$show = GetSettingsValue($value);
				$page->AddTableData('<b>'.$key.'</b>', array('style'=>'text-align:right; padding-right:12px;'));
				$page->AddTableData('<tt>'.$show.'</tt>');
			}
		}
		$page->EndTable();
	}

	// Do some step checking
	else
	{
		
		// Show the title of the step and a "for all" icon
		if(isset($steps[$step]['title']))
			 $title = $steps[$step]['title'];
		else $title = 'Installer step';
		$page->MainTitle($title, 'allsteps');

		// Display the keys and values
		$page->StartTable(2, array('class'=>'dbtests'));
		foreach($sett as $key=>$value)
		{
			$show = GetSettingsValue($value);
			$page->AddTableData('<b>'.$key.'</b>', array('style'=>'text-align:right; padding-right:12px;'));
			$page->AddTableData('<tt>'.$show.'</tt>');
		}
		$page->EndTable();

		// Make sure this mask file exists!
		if(isset($sett['maskname']))
		{
			if($mask->DoesMaskExistAndIsReadable($sett['maskname']))
			{
				$page->SuccessBox('The mask file <b>'.$sett['maskname'].'</b> exists and is readable');

				// The mask is checked for few things
				$maskContent = $mask->GetMask($sett['maskname'], false);

				// Get if ANY keyword is found in the mask
				$counts = $mask->GetReplaceKeywordCount($maskContent);
				if(is_array($counts) && count($counts) > 0)
				{
					$str = '';
					foreach($counts as $word=>$count)
						$str .= "\n".'<br /><tt><b>'.$keywords['open_bracket'].$word.$keywords['close_bracket'].'</b></tt> = <b>'.$count.'</b>';

					$page->SuccessBox('The mask file contains the following keywords:'.$str);
				}
				else
				{
					$page->InfoBox('This mask file does not contain any keywords to replace.');
				}

				// Check if there is SEPARATOR KEYWORD in the sql file
				$ext = $mask->GetMaskExtension($sett['maskname'], true);
				if($ext == 'sql')
				{
					$counts = $mask->GetSqlSeparatorCount($maskContent);
					if($counts > 0)
					{
						$page->SuccessBox('The SQL mask has <b>'.$counts.'</b> occurences of the <i>SQL Query Separator</i>');
					}
					else
					{
						$page->WarningBox('There is no occurence of the <i>SQL Query Separator</i>! If your "Installation SQL Script" contains '.
							              'more than one query, <b>the installation will fail!</b>  <br />&nbsp;<br />'.
							              'PHP does not support multiple queries, unless <tt>mysqli</tt> is used. Because we cannot be certain '.
							              'that <tt>mysqli</tt> will be installed, the Installer does not support that extension, but rather '.
							              'requires that the <tt>'.$keywords['next_query'].'</tt> separator is placed in between all queries.');
					}
				}
			}
			else
				$page->WarningBox('The mask file <b>'.$sett['maskname'].'</b> does not exists, make sure filenames are correct!');
		}
	}

	// Create a new array with the configuration overview as first element
	// and copy the rest of the $steps array into the new array
	$newSteps = array(STEP_SETTOVERVIEW => array('title'=>'Configuration Overview'));
	foreach($steps as $key=>$value)
		$newSteps[$key] = $value;

	// Clear the original steps and update with the modified version
	$steps = array();
	$steps = $newSteps;

	if(!isset($steps[$step]))
		$step = STEP_SETTOVERVIEW;

	// Show the page with the modified steps array
	$page->ShowPage($step, true, true, 'Installation Overview');



	//=================================[ Functions used in Overview Script ]=================================//

	function GetSettingsValue($value)
	{
		$show = '';
		if(is_array($value))
		{
			if(count($value) == 0)
				$show = '<i style="color:#9A9A9A;">*empty*</i>';
			else
			{
				foreach($value as $item)
					$show .= $item.', ';
				$show = substr($show, 0, strlen($show)-2);
			}
		}
		else
		{
			if($value === true)
				 $show = '<span style="color:#0000FF">true</span>';
			else if($value === false)
				$show = '<span style="color:#FF0000">false</span>';
			else
				$show = htmlentities($value);
		}

		return $show;
	}