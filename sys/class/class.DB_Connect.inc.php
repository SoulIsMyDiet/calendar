<?php

//declare(strict_types=1);//uncomment in php7
/*
 * Operations with database
 *
 * @copyright 2017 Marek Dzilne
 */
class DB_Connect{
	//Contains database object
	protected $db;
	protected function __construct($db=NULL){
		if( is_object($db) )
		{
			$this->db = $db;
		}
		else //just in case if something weird happen we try again to make conection with database*
		{
			/*PROBLEM WITH CONST
			 *$dbs = "pgsql:host=".DB_HOST.";dbname=".DB_NAME;
			 */
			$dbs ="pgsql:host=localhost;port=5432;dbname=calendar";
			try
			{

				//const are definded in /sys/config/dbcred.inc.php

				/*PROBLEM WITH CONST
				 *$dbo = new PDO($dbs, DB_USER, DB_PASS);
				 */

				$this->db = new PDO($dbs, 'ziom', 'ziomek');
			}
			catch (Exception $e)//*but this time we gonna know about the errors 
			{
				die($e->getMessage());
			}
		}
	}
}
