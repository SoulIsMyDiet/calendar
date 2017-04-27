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
		if(isset($useDate))
		{
			$this->_useDate = $useDate;
		}
		else
		{
			$this->_useDate = date('Y-m-d H:i:s');
		}
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
		$test = "SELECT * FROM events";
		if (!empty($id))
		{
			$sql.=" WHERE event_id =:id";
		}
		else
		{
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
					$stmt->bindParam(":id",$id, PDO::PARAM_INT);
			
				}
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$stmt->closeCursor();
				echo "</br>";
				//echo DB_NAME."</br>";
				//echo DB_USER."</br>";
				//echo DB_PASS."</br>";
				//echo DB_HOST."</br>";
				return $results;
			}
			catch(Exception $e)
			{
				die($e->getMesage());
			}
		}
	
			private function _createEventObj(){
				$arr = $this->_loadEventData();
				$events = [];
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
		
	public function buildCalendar(){

		$cal_month = date('F Y', strtotime($this->_useDate));
		define('WEEKDAYS', ['Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So', 'Nd']);
		$html = "\n\t<h2>$cal_month</h2>";
		for($d=0, $labels=NULL; $d<7; ++$d )
		{
			$labels .= "\n\t\t<li>".WEEKDAYS[$d]."</li>";
		}
		$html .="\n\t<ul class= \"weekdays\">".$labels."\n\t</ul>";
		$events = $this->_createEventObj();
		$html.= "\n\t<ul>";
		for ( $i = 1, $c =1, $t=date('j'), $m=date('m'), $y=date('Y'); $c<=$this->_daysInMonth;++$i)
		{
			$class = $i<$this->_startDay ? "fill" : NULL;	

			if($c==$t && $m==$this->_m && $y==$this->_y )
			{
				$class = "today";
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
						$link = '<a href="view.php?event_id='.$event->id.'">'.$event->title.'</a>';
						$event_info .="\n\t\t\t$link";
					}
				}
				$date = sprintf("\n\t\t\t<strong>%02d</strong>",$c++);
			}
			else {$date="&nbsp;";}

			$wrap = $i!=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>" : NULL;

			$html .= $ls . $date . $event_info . $le . $wrap;
		}
			while($i%7!=1)
			{
				$html .= "\n\t\t<li class= \"fill\">&nbsp;</li>"; ++$i;
			}

		$html .="\n\t</ul>\n\n";

		return $html;
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
	}
}
