<?php
declare(strict_types=1);

/*
 * Creates and modificate the event calendar
 *
 * @copyright 2017 Marek Dzilne
 */
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
