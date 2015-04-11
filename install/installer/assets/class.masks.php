<?php if(!defined('INST_BASEDIR')) die('Direct access is not allowed!');

    /* ====================================================================
    *
    *                            PHP Setup Wizard
    *
    *                         -= MASK READ/WRITER =-
    *
    *  ================================================================= */
    

    /** 
	 *  Manipulates mask files 
	 */
    class Inst_Masks
    {
        private $words;
        
        /** 
		 *  Manipulates mask files 
		 */
        function Inst_Masks()
        {
            $this->SetKeywords();
        }
        
        
        /********************************[ CORE FUNCTIONS ]********************************/
        
        /** 
		 *  Set the keywords that will be replaced in the masks 
		 */
        function SetKeywords()
        {
			global $keywords;

			// Merge the keyword arrays together, if there is collision in the arrays the
			// connection array will be dominant - meaning that it will override the
			// keys in special and admin arrays
			$this->words = array();
			$this->words = array_merge($keywords['special'], $keywords['connection']);
			$this->words = array_merge($keywords['admin'], $this->words);
        }
            
        /** 
		 *  Replace keywords in a mask content 
		 */
        function ReplaceKeywords($maskContent)
        {
			global $keywords;

            // Skip replace if no string to work with
            if(!$maskContent || strlen($maskContent) == 0)
                return $maskContent;
            
            // Do the replacement
            foreach($this->words as $keyword=>$value)
            {
				$keyword = $keywords['open_bracket'].$keyword.$keywords['close_bracket'];
                $maskContent = str_replace($keyword, $value, $maskContent);
            }
            return $maskContent;
        }

		/**
		 *  Get how many keywords are replaced in some mask content
		 */
		function GetReplaceKeywordCount($maskContent)
		{
			global $keywords;

            // Skip replace if no string to work with
            if(!$maskContent || strlen($maskContent) == 0)
                return $maskContent;
            
            // Do the replacement
			$count = array();
            foreach($this->words as $keyword=>$value)
            {
				$searchWord = $keywords['open_bracket'].$keyword.$keywords['close_bracket'];
				$wordCount = substr_count($maskContent, $searchWord);
				
				if($wordCount > 0)
					$count[$keyword] = $wordCount;
            }
            return $count;
		}

		/**
		 *  Check if the mask file exists and is readable
		 */
		function DoesMaskExistAndIsReadable($maskname)
		{
			global $config;
			$file = INST_RUNFOLDER.$config['mask_folder_name'].DIRECTORY_SEPARATOR.$maskname;
			return (is_file($file) && is_readable($file)) ? true : false;
		}

		/** 
		 *  Get a mask content with the keywords replaced
		 */
		function GetMask($maskname, $replaceKeywords=true)
		{
			global $config;
			$file = INST_RUNFOLDER.$config['mask_folder_name'].DIRECTORY_SEPARATOR.$maskname;

			if(is_file($file) && is_readable($file))
			{
				$content = file_get_contents($file);

				if($replaceKeywords)
					 return $this->ReplaceKeywords($content);
				else return $content;
			}
			else
				return false;
		}

		function GetMaskExtension($maskname, $getInLowercase=false)
		{
			// Get the file extension (+1 to skip the dot)
			if(strrpos($maskname, '.') != false)
			{
				$ext = substr($maskname, (strrpos($maskname, '.') + 1 ));
				if($getInLowercase)
					 return strtolower($ext);
				else return $ext;
			}
			else 
				return false;
		}

		/**
		 *  How many times does the SQL separator occur in some mask content
		 */
		function GetSqlSeparatorCount($maskContent)
		{
			global $keywords;
			return substr_count($maskContent, $keywords['next_query']);
		}
        
		/**
		 *  Should the "next query" separator be filtered out of
		 *  the SQL query block (or some string for that matter)
		 */
		function FilterSqlSeparator($maskContent, $reduceNewlines=true)
		{
			global $keywords;
			$maskContent = str_replace($keywords['next_query'], "", $maskContent);

			if($reduceNewlines)
			{
				$maskContent = str_replace("\r", "", $maskContent);
				$maskContent = str_replace("\n\n", "\n", $maskContent);
			}

			return $maskContent;
		}

        /********************************[ GET SPESIFIC MASKS ]********************************/
        
        /** 
		 *  Get the welcome message with keywords replaced 
		 */
        function GetWelcomeMessage()
        {
			global $steps;
			return $this->GetMask($steps[STEP_WELCOME]['maskname']);
        }       
        
        /** 
		 *  Get the Terms-Of-Agreement with keywords replaced 
		 */
        function GetTermsOfAgreement()
        {
			global $steps;
			return $this->GetMask($steps[STEP_TERMSOFUSE]['maskname']);
        } 

		/** 
		 *  Get the SQL queries with keywords replaced 
		 */
        function GetConfigFile()
        {
			global $steps;
			return $this->GetMask($steps[STEP_WRITECONFIG]['maskname']);
        } 

		/** 
		 *  Get the finished message with keywords replaced 
		 */
        function GetFinishedMessage()
        {
			global $steps;
			return $this->GetMask($steps[STEP_FINISHED]['maskname']);
        }  

		/** 
		 *  Get the SQL queries that will create tables for the system
		 */
        function GetSqlInstallQueries($splitBySeperator=false)
        {
			global $steps;
			global $keywords;
			$maskContent = $this->GetMask($steps[STEP_RUNSQL]['maskname']);

			// If the SQL mask should be splitted by the seperator
			// string or not - return either one string or array of strings
			if($splitBySeperator)
				 return explode($keywords['next_query'], $maskContent);
			else return $maskContent;
        } 

		/** 
		 *  Get the SQL queries that will be used to insert root access
		 */
        function GetSqlRootAccessQueries($splitBySeperator=false)
        {
			global $steps;
			global $keywords;
			$maskContent = $this->GetMask($steps[STEP_ROOTUSER]['maskname']);

			// If the SQL mask should be splitted by the seperator
			// string or not - return either one string or array of strings
			if($splitBySeperator)
				 return explode($keywords['next_query'], $maskContent);
			else return $maskContent;
        } 
    }
