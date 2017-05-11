<?php
//declare(strict_types=1);

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
		if(isset($useDate))
		{
			$this->_useDate = $useDate;
		}
		else
		{
			$this->_useDate = date('Y-m-d H:i:s');//in case of no date as arg we use todays date
		}
		//we grab single data from date we use and write it as a class property
		$ts = strtotime($this->_useDate);
		$this->_m = (int)date('m', $ts);
		$this->_y = (int)date('Y', $ts);
		$this->_daysInMonth = cal_days_in_month(
			CAL_GREGORIAN,
			$this->_m,
			$this->_y
		);
		$ts = mktime(0,0,0, $this->_m, 1, $this->_y);
		$this->_startDay = (int)date('w', $ts);
	}
	public function _loadEventData($id =null){
		$sql = "SELECT event_id, event_title, event_desc, event_start, event_end FROM events";
		if (!empty($id))//is not empty
		{
			$sql.=" WHERE event_id =:id";
		}
		else
		{
			//we will select all events that happen in the month we ask for
			$start_ts = mktime(0,0,0, $this->_m, 1,$this->_y);
			$end_ts = mktime(23,59,59, $this->_m+1, 0,$this->_y);
			$start_date = date('Y-m-d H:i:s', $start_ts);
			$end_date = date('Y-m-d H:i:s', $end_ts);

			$sql .=" WHERE event_start BETWEEN '$start_date' AND '$end_date' ORDER BY event_start";
		}
			try
			{
				$stmt = $this->db->prepare($sql);
				if(!empty($id))
				{
					$stmt->bindParam(":id",$id, PDO::PARAM_INT); // this :id is now var $id we gave
				}
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				/*PROBLEM WITH CONST
				echo "</br>";
				echo DB_NAME."</br>";
				echo DB_USER."</br>";
				echo DB_PASS."</br>";
				echo DB_HOST."</br>";
				 */
				return $results;
			}
			catch(Exception $e)
			{
				die($e->getMesage());
			}
	}
	// we are building the html file dependly on month
	public function buildCalendar(){

		$cal_month = date('F Y', strtotime($this->_useDate));
		//define(WEEKDAYS, ['Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So', 'Nd']);//uncoment in php7
$weekdays = ['Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So', 'Nd'];
		$html = "\n\t<h2>$cal_month</h2>";//heading of callendar
		for($d=0, $labels=NULL; $d<7; ++$d )
		{
			$labels .= "\n\t\t<li>$weekdays[$d]</li>";//in php7 change var into const WEEKDAYS
		}
		$html .="\n\t<ul class= \"weekdays\">".$labels."\n\t</ul>";
		$events = $this->_createEventObj();
		$html.= "\n\t<ul>";
		for ( $i = 1, $c =1, $t=date('j'), $m=date('m'), $y=date('Y'); $c<=$this->_daysInMonth;++$i)
		{
			$class = $i<$this->_startDay ? "fill" : NULL;	//if month starts after monday the earliest day will be grey(class = "fill")

			if($c==$t && $m==$this->_m && $y==$this->_y )
			{
				$class = "today";//if the calendar shows today it have special class
			}
			$ls = sprintf("\n\t\t<li class= \"%s\">", $class);
			$le = "\n\t\t</li>";
			$event_info = NULL;

			if ($this->_startDay<=$i && $this->_daysInMonth>=$c)
			{
							if (isset($events[$c]) )
				{
					foreach( $events[$c] as $event)
					{
						//if there is an event in the databse  on this day it will expose it
						$link = '<a href="view.php?event_id='.$event->id.'">'.$event->title.'</a>';
						$event_info .="\n\t\t\t$link";
					}
				}
				$date = sprintf("\n\t\t\t<strong>%02d</strong>",$c++);//we are changing the language to c++// ah just laughing :P (putting incrementing numbers on ecah block**)
			}
			else {$date="&nbsp;";} //** or space if needed

			$wrap = $i!=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>" : NULL;//after sunday we need to end the unorderd list an start another one

			$html .= $ls . $date . $event_info . $le . $wrap; //the summary of all above
		}
			while($i%7!=1)
			{
				$html .= "\n\t\t<li class= \"fill\">&nbsp;</li>"; ++$i; //even if month has come to end, show masut go on and we need to "fill" the rest of block
			}

		$html .="\n\t</ul>\n\n";

		return $html;
	}
public function displayEvent($id){
		if (empty($id)) {return NULL;}

		$id = preg_replace('/[^0-9]/', '', $id);

		$event = $this->_loadEventById($id);
		$ts = strtotime($event->start);
		$date = date('F d, Y', $ts);
		$start = date('g:ia', $ts);
		$end = date('g:ia', $ts);
		$end = date('g:ia', strtotime($event->end));

		return "<h2>$event->title</h2>"."\n\t<p class=\"dates\">$date, $start&mdash;$end</p>"."\n\t<p>$event->description</p>";

public function displayForm(){
	if(isset($_POST['event_id']))
{
	$id = (int) $_POST['event_id'];
}
else
{
$id = NULL;
}
$submit = "utwórz nowe wydarzenie";

$event = new Event();

if (!empty($id))
{
$event = $this->_loadEventById($id);

if(!is_object($event)){return NULL;}
$submit = "Edytuj to wydarzenie"
}
return <<<FORM_MARKUP
<form action="assets/inc/process.inc.php" method="POST">
<fieldset>
	<legend>$submit</legend>
	<label for="event_title"> Nazwa wydarzenia </label>
	<input type="text" name="event_title" id="event_title" value="$event->title" />
	<label for="event_start"> Czas rozpoczęcia </label>
	<input type="text" name="event_start" id="event_start" value="$event->start" />
	<label for="event_end"> Czas zakończenia </label>
	<input type="text" name="event_end" id="event_end" value="$event->end" />
	<label for="event_description"> Opis wydarzenia </label>
	<textarea name="event_description" id="event_description">$event->description</textarea>
	<input type="hidden" name="event_id" value="$event->id" />
	<input type="hidden" name="token" value="$_SESSION[token]" />
	<input type="hidden" name="action" value="event_edit" />
	<input type="submit" name="event_submit" value="$submit" />
lub <a href="./">anuluj</a>
</fieldset>
</form>
FORM_MARKUP;
}

public function processForm(){
	if ($_POST['action']!='event_edit')
	{
		return "Nieprawidłwe użycie metdody processForm";
	}
	
	$title = htmlentities($_POST['event_title'], ENT_QUOTES);
	$desc = htmlentities($_POST['event_description'], ENT_QUOTES);
	$start = htmlentities($_POST['event_start'], ENT_QUOTES);
	$end = htmlentities($_POST['event_end'], ENT_QUOTES);
	if (empty($_POST['event_id']))
	{
		$sql = "INSERT INTO events
		(event_title, event_desc, event_start, event_end)
		VALUES
		(:title, :description, :start, :end)";
	}
	else
	{
		$id = (int) $_POST['event_id'];
		$sql = "UPDATE events
		SET
		event_title = :title,
		event_desc = :description,
		event_start= :start,
		event_end= :end
		WHERE event_id = $id";
	}
	try
	{
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":title", $title, PDO::PARAM_STR);
		$stmt->bindParam(":description", $desc, PDO::PARAM_STR);
		$stmt->bindParam(":start", $start, PDO::PARAM_STR);
		$stmt->bindParam(":end", $end, PDO::PARAM_STR); 
		$stmt->execute();
		$stmt->closeCursor();
		return TRUE;
	}
	catch ( Exception $e )
	{
		return $e-> getMessage();
	}
		
}
	//in this method we make a table of tables and we add a 'key' to each table which is the day of month
			private function _createEventObj(){
				$arr = $this->_loadEventData();
				$events = [];//making empty array at the begining just to avoid problems
				foreach($arr as $event)
				{
					$day =date('j', strtotime($event['event_start']));
					try
					{
						$events[$day][] =new Event ($event);
					}
					catch(Exception $e)
					{
						die($e->getMessage());
					}
				}
				return $events;
			}
		private function _loadEventById($id){
		
		if (empty($id) )
		{
			return NUll;
		}
		$event = $this->_loadEventData($id);

		if ( isset($event[0]))
		{
			return new Event($event[0]);
			//$event is a table with one table $event[0]
		}
		else
		{
			return NULL;
		}
	}
	
}
