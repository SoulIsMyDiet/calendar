<?php

declare(strict_types=1);
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
		else
		{
			$dbs ="pgsql:host=".DB_HOST.";dbname=".DB_NAME;
			try
			{
				//const are definded in /sys/config/db-cred.inc.php
				$this->db = new PDO($dbs, DB_USER, DB_PASS);
			}
			catch (Exception $e)
			{
				die($e->getMessage());
			}
		}
	}
}
