<?php if(!defined('INST_BASEDIR')) die('Direct access is not allowed!');

    /* ====================================================================
    *
    *                            PHP Setup Wizard
    *
    *                         -= SELF-DESTRUCTION =-
    *
    *  This file contains functions that are used to delete ALL the files
	*  inside the Installer folder. Be VERY careful with this script and
	*  only modify it if you are absolutly certain of what you are doing.
    *
    *  ================================================================= */

	/**
	 *  You got to admit, that's pretty funny for a function name :)
	 *  What it does is simply trying to delete all the installer files
	 *  including this one!
	 */ 
	function DeleteYourself()
	{
		/*
		*   >>> IMPORTANT! <<<< 
		*
		*   NOTE: This code only provides you with the ability to completly make the Installer
		*         remove itself from the webserver (if it can). HOWEVER - you can modify this 
		*         function to only delete SOME files or some spesific files rather than deleting
		*         everything.
		*
		*         Example: You have made a very descriptive "step-by-step" guide to how to get
		*                  this installer going. The file "installer.php" is only allowed to be
		*                  executed directly so this method could only delete that file. Then,
		*                  in your guide you could say "Delete the script file installer.php if
		*                  the Installer itself is unable to" - rather then saying delete the 
		*                  Installer folder, which contains 55+ files including images etc.
		*                  Just a suggestion :)
		*/

		global $config;

		// Read the base directory for files to delete and sort the array so that the longest 
		// scopes are deleted first - example: base/aaa.txt  base/folder/bbb.txt - then bbb.txt
		// will be the first because it has scope of 2, and aaa.txt has the scope 1
		$files = ReadFiles(INST_BASEDIR.INST_RUNFOLDER, $config['self_destruct_filter']);
		rsort($files);

		// Get all unique directories into one array and sort by scope as well
		$dirs = array();
		foreach($files as $file)
		{
			if(!isset($dirs[$file['dir']]))
				$dirs[$file['dir']] = $file['scope'];
		}		
		arsort($dirs);

		// Success rate is stored
		$fileDelete = array('success'=>0, 'failed'=>0, 'total'=>count($files));
		$dirDelete = array('success'=>0, 'failed'=>0, 'total'=>count($dirs));

		// Begin with deleting the files
		foreach($files as $file)
		{
			#if(unlink($file['dir'].$file['name']))
				 $fileDelete['success']++;
			#else $fileDelete['failed']++;
		}

		if($config['self_destruct_removes_folders'])
		{
			// And then delete the directories
			foreach($dirs as $dir=>$scope)
			{
				#if(rmdir($dir))
					 $dirDelete['success']++;
				#else $dirDelete['failed']++;
			}
		}
		else
		{
			// Total reset to zero, so no folder should be
			// deleted, no success and no failure!
			$dirDelete['total'] = 0;
		}

		// Return back the success of deletion
		return array('dirs'=>$dirDelete, 'files'=>$fileDelete);
	}


	/**
	 *  Read everything in a folder, and recursivly go through all
	 *  subfolders to scan for more files. Define the files to include
	 *  in the read
	 */
	function ReadFiles($dir, $filterExt=array())
	{
		$files = array();
		$checkFile = "";
		$extension = "";

		// Fix the separators in the path 
		$dir = FixPath($dir);
		if(is_dir($dir))
		{
			if($opendir = opendir($dir))
			{
				// Read everything in the folder, $checkFile can be both
				// folder, file and for some reason '.' and '..'
				while(($checkFile = readdir($opendir)))
				{
					// Construct a subfolder variable and if that is
					// valid directory - scan those files first
					$subFolder = FixPath($dir.DIRECTORY_SEPARATOR.$checkFile);

					// If this is a directory - scan that folder
					// first before reading the files in here
					$fromFolder = array();
					if(is_dir($subFolder) && $checkFile != '.' && $checkFile != '..')
						$fromFolder = ReadFiles($subFolder, $filterExt);

					// Put all "from folder" files into the $files array
					foreach($fromFolder as $folderFile)
						$files[] = $folderFile;

					// Get file extension
					if(strrpos($checkFile, '.') != false)
						 $extension = strtolower(substr($checkFile, (strrpos($checkFile, '.') + 1 )));
					else $extension = false;
					
					// If file has extension and either set to delete ALL extensions (empty array)
					// or this extension is in the array of "extensions to delete" - add to 
					if($extension !== false && (count($filterExt) == 0 || in_array($extension, $filterExt)))
					{
						// To be sure its a file...
						if(filetype($dir.'/'.$checkFile) == "file")
						{
							$scope = count(explode(DIRECTORY_SEPARATOR, $dir));
							$files[] = array('scope'=>$scope, 'dir'=>$dir, 'name'=>$checkFile, 'ext'=>$extension);
						}						
					}
				}
				closedir( $opendir );
			}
		}

		return $files;
	}
