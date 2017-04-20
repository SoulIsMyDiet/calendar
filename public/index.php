<?php
declare(strict_types=1);

include_once __DIR__.'/../sys/core/init.inc.php';
/*
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

class Calendar extends DB_Connect{
        private $_useDate;
        private $_m; //month;
        private $_y; //year;
        private $_daysInMonth;
        private $_startDay;

        public function __construct($dbo=NULL, $useDate=NULL){
                parent::__construct($dbo);
        }
}
*/
$cal = new Calendar($dbo, "20	16-01-01 12:00:00");

if (is_object ($cal) )
{
	echo "<pre>", var_dump($cal), "</pre>";
}
