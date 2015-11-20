<?php if(!defined('INST_BASEDIR')) die('Direct access is not allowed!');

    /* ====================================================================
    *
    *                            PHP Setup Wizard
    *
    *                        -= DATABASE INTERACTION =-
    *
    *  ================================================================= */
    

    /** 
	 * Connects to database and executes queries 
	 */
    class Inst_Databases
    {
        private $connection, $dbselected, $dbname;

        /** 
		 *  Connects to database and executes queries 
		 */
        function Inst_Databases()
        {
            $this->connection = false; # Default connection
            $this->dbselected = false; # Database link
			$this->dbname = ''; # Selected database name
        }        

		/*==============================================[ DATABASE CONNECTION ]==============================================*/

        /** 
		 *  Connect to database server 
		 */
        function Connect($host='', $user='', $pass='', $port='')
        {
			// This method accepts $keywords['connection'] array to be passed 
			// for $host, othervice the other two will be used instead
			if(is_array($host))
			{
				$hostport = $host['hostname'];
				if(strlen($host['dbport']) > 0)
					$hostport = $hostport.':'.$host['dbport'];

				$this->connection = mysql_connect($hostport, $host['username'], $host['password']);            
			}
			else 
			{
				if(strlen($port) > 0)
					$host = $host.':'.$port;

				$this->connection = mysql_connect($host, $user, $pass);
			}
        }

		/**
		 *  Disconnect from the database server
		 */
		function Disconnect() 
		{
			if($this->IsConnected())
			{
				mysql_close($this->connection);
				$this->dbselected = false;
				$this->dbname = false;
			}
		}
        
        /** 
		 *  If the resource is connected to the database 
		 */
        function IsConnected()
		{
			return ($this->connection) ? true : false;
		}

		/** 
		 *  Get an error message from database 
		 */
        function GetDatabaseError()
        {
            return mysql_error();
        } 

		/** 
		 *  Run a query to the selected database, or run
		 *  multiple queries (if parameter is an array)
		 */
        function RunQuery($query)
        {
			if(is_array($query))
			{
				$results = array();
				foreach($query as $q)
				{
					// Do not process empty queries
					$q = trim($q);
					if(strlen($q) == 0)
						continue;
	
					// Execute the query and return the results with
					// success state and error message if set
					$q = explode(";\r\n",$q);
					foreach($q as $query)
					{
						$result = mysql_query( $query, $this->connection);
					}
					//$result = mysqli_multi_query($this->connection,$q);
					if($result)
						 $results[] = array('success'=>true,  'data'=>$result, 'error'=>'');
					else $results[] = array('success'=>false, 'data'=>$result, 'error'=>$this->GetDatabaseError());
				}
				return $results;
			}
			else
			{
				return mysql_query( $query, $this->connection);
			}
        }

		 	

		/*==============================================[ DATABASES ]==============================================*/
              
        /** 
		 *  Select a database to run queries in 
		 */
        function SelectDatabase($database)
        {
            $this->dbselected = mysql_select_db($database,$this->connection);  
			
			if($this->IsDatabaseSelected())
				 $this->dbname = $database;
			else $this->dbname = false;
        }
        
        /** 
		 *  Has a database been selected 
		 */
        function IsDatabaseSelected()
        {
            return ($this->dbselected) ? true : false;
        }

		/**
		 *  Get the name of the selected database. NOTE: If no database
		 *  has been selected then false is returned.
		 */ 
		function GetDatabaseName() 
		{
			 return $this->dbname;
		}
        
        /** 
		 *  If you want to create a database, this function must approve
         *  the name of the new database in order for it to be created!
         *  The string must be on the form:  a-z,  0-9  or  _  
		 */
        function IsDatabaseFriendly($database)
        {
            return preg_match("/^[a-z0-9_]*$/i", strtolower($database));
        }
        
        /** 
		 *  Create new database whitin the resource connected to 
		 */
        function CreateNewDatabase($database)
        {
            // The database name must be approved first!
            if(!$this->IsDatabaseFriendly($database))
                return false;            
            
            // The $database contains legal values 
            $query = "CREATE DATABASE ".$database." DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";
            $result = $this->RunQuery($query);
            if(mysql_affected_rows($this->connection) > 0)
                 return true;
            else return false;
        }   
        
        /** 
		 *  Does a database exist or not 
		 */
        function DoesDatabaseExist($database)
        {
            $database = strtolower($database);
            $dblist = $this->GetDatabaseList();
            foreach($dblist as $idx=>$db)
            {
                if($db['name'] == $database)
                    return true;
            }
            return false;
        }
        
        /** 
		 *  Get a list of databases and their table count
		 */
        function GetDatabaseList($includeSelectedDatabase=true)
        {
			/*
			*   NOTE: In some cases the query "SHOW DATABASES;" will be denied on the target
			*         webserver. If that happens, the returned list here will always be empty.
			*
			*         All functionality that relays on the fact that this list WILL give the
			*         list of "available databases" will most likely expect the selected database
			*         to be in it!
			*
			*         However, if this workaround [$includeSelectedDatabase] is not set to true,
			*         the list will be empty if denied. So, if a database has been selected and
			*         that selected database is NOT in this list here - add it anyway!
			*/

			// Only return array if connected to MySQL server
            if($this->IsConnected())
            {
                $dblist = array();
				$result = $this->RunQuery('SHOW DATABASES;');
				
				// If the result has data - then user 
				// has SHOW DATABASE privilege
				if($result)
				{
					while ($row = mysql_fetch_object($result)) 
					{
						$tbresult = $this->RunQuery("SHOW TABLES FROM ".$row->Database);                    
						$dblist[] = array(
							'name'  => $row->Database, 
							'tbcount' => mysql_num_rows($tbresult)
							);
					}

					// If to make sure the selected database is in the list
					if($includeSelectedDatabase && $this->IsDatabaseSelected())
					{
						$notInList = true;
						foreach($dblist as $db)
						{
							if($db['name'] == $this->GetDatabaseName())
							{
								$notInList = false;
								break;
							}
						}						
						if($notInList)
						{
							$tbresult = $this->RunQuery("SHOW TABLES FROM ".$this->GetDatabaseName());                    
							$dblist[] = array(
								'name'  => $this->GetDatabaseName(), 
								'tbcount' => mysql_num_rows($tbresult)
								);
						}
					}

					return $dblist;
				}

				// SHOW DATABASE; query does not return anything or user does not
				// have permission to run this query
				else
				{
					// Making sure the selected database will be in the list
					$dblist = array();
					if($includeSelectedDatabase && $this->IsDatabaseSelected())
					{
						// Get table count from the selected database (if that is allowed =))
						$tbresult = $this->RunQuery("SHOW TABLES FROM ".$this->GetDatabaseName());                    
						$dblist[] = array(
							'name'  => $this->GetDatabaseName(), 
							'tbcount' => mysql_num_rows($tbresult)
							);
					}

					// Return empty array, or an array with selected database only
					return $dblist;
				}
            }
			
			return false;
        } 

		/**
		 *  Get a list of database names only
		 */
		function GetDatabaseNameList()
		{
			$dbList = $this->GetDatabaseList();
			$output = array();
			foreach($dbList as $idx=>$db)
			{
				$output[] = $db['name'];
			}
			return $output;
		}

		/**
		 *  Get a list of tables that are within the selected database. NOTE: If
		 *  there is no database selected, an empty array will be returned. 
		 */ 
		function GetTableListFromDatabase() 
		{
			if($this->IsConnected() && $this->IsDatabaseSelected() && strlen($this->GetDatabaseName()) > 0)
			{
				$list = array();
				$result = $this->RunQuery("SHOW TABLES FROM ".$this->GetDatabaseName());
				if($result && mysql_num_rows($result) > 0)
				{
					while ($row = mysql_fetch_row($result))
						$list[] = $row[0];
				}
				return $list;
			}
			else
				return array();
		}
        
        

		/*==============================================[ TESTING PRIVILEGES ]==============================================*/


		/**
		 *  Run few queries to see if the user has the access needed to create the tables for
		 *  the system being installed, and the privileges to manipulate data. If any of these
		 *  will fail the system being installed might not work properly due to limited privileges
		 */
		function TestUserPrivileges() 
		{
			$outcome = array();
			$totalSuccess = true;
			$testTable = 'installer_query_testing_table';

			// The testing queries
			$testing = array
			(
				'create' =>  "CREATE TABLE `".$testTable."` ( ".
							 "`id` INT NOT NULL AUTO_INCREMENT , ".
							 "`text` VARCHAR( 255 ) NOT NULL , ".
							 "PRIMARY KEY ( `id` ))",

				'insert' =>  "INSERT INTO `".$testTable."` (`id`, `text`) VALUES ".
							 "(NULL , 'this is the first row'), (NULL , 'this is the second row');",

				'select' =>  "SELECT * FROM `".$testTable."`;",

				'update' =>  "UPDATE `".$testTable."` SET `text` = 'text updated' ".
					         "WHERE `".$testTable."`.`id` = '2';",

				'delete' =>  "DELETE FROM `".$testTable."` WHERE `id` = '1';",				

				'alter'  =>  "ALTER TABLE `".$testTable."` ADD UNIQUE (`text`)",

				'drop'   =>  "DROP TABLE `".$testTable."`"
			);		

			// First, check if the testing table exists, if it does, try
			// to drop it. If "drop" or "show tables" are denied then the 
			// testing queries will as well.This process will not be shown
			// in the outcome of the tests - just tries to be sure that
			// CREATE TABLE command will be executed when the table does not exist!
			if($this->RunQuery("SHOW TABLES LIKE '".$testTable."'"))
				$this->RunQuery("DROP TABLE `".$testTable."`");

			// Do all the testing queries
			foreach($testing as $test=>$query)
			{
				$result = $this->RunQuery($query);
				if($result)
					$outcome[$test] = array('success'=>true,  'query'=>$query, 'error'=>'');
				else
				{
					$totalSuccess = false;
					$outcome[$test] = array('success'=>false, 'query'=>$query, 'error'=>$this->GetDatabaseError());
				}
			}

			// Add more info to the $outcome before returning
			$outcome['totalsuccess'] = $totalSuccess;
			return $outcome;
		}
    }
