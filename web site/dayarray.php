<?php
	include 'dbconnect.php';
	function getDaysArray($startDate, $period, $id)
	{
			$dbConnect = new DBConnect();
			$query = $dbConnect->createQuery("SELECT", "*", "rules", "userid = '$id' AND expiredate>$startDate AND active='ACTIVE'");
			//echo $query;
			$sql = $dbConnect->sendQuery($query);
			$days = array(); //days[0]=startDate
			while($row = mysql_fetch_array($sql))
			{
				$starttimeH=date('H',$row[fromtime]);
				$starttimeM=date('i',$row[fromtime]);
				$starttime=$starttimeH*6+(int)($starttimeM/10);
				$endtimeH=date('H',$row[endtime]);
				$endtimeM=date('i',$row[endtime]);
				$endtime=$endtimeH*6+(int)($endtimeM/10);
				//echo $starttimeH;
				//echo print_r($row);



				if($row["periodtype"]=="DAILY"){
					for ($d=0; $d < $period; $d++) { 
						for ($time=$starttime+1; $time <= $endtime; $time++) { 
							if ($days[$d*144+$time]==1) {
							}
							$days[$d*144+$time]=1;
						}
					}
				}

				if($row["periodtype"]=="WEEKLY"){
					$weekdayToday=date('N',$startDate);
					$d=$row[perioddata]-$weekdayToday;
					if ($d<0) {
						$d=$d+7;
					}
					for ($i=$d; $i < $period; $i=$i+7) { 
						for ($time=$starttime+1; $time <= $endtime; $time++) { 
							$days[$i*144+$time]=1;

						}
					}
				}

				$time=$startDate;

				if($row[periodtype]=='MONTHLY'){
					$today=date('j',$startDate);
					$d=$row[perioddata]-$today;
					if($d<0)
					{
						$d=$d+date('t',$startDate);
					}
					if($d<$period){
					for ($time=$starttime; $time <= $endtime; $time++) { 
							$days[$d*144+$time]=1;
						}
					}
				}

				if($row[periodtype]=='ONETIME'){
					if($startDate<$row[perioddata]&&$row[perioddata]<$startDate+3600*24*$period)
					{

						if(date('n',$startDate)==date('n',$row[perioddata]))
						{
							$d=date('j',$row[perioddata])-date('j',$startDate);
						}
						}
						else
						{
							$d=date('j',$row[perioddata])+date('t',$startDate)-date('j',$startDate);
						}
						if($d<$period){
						for ($time=$starttime; $time <= $endtime; $time++) { 
							$days[$d*144+$time]=1;
					}
				}
				}

				

				//echo print_r($days);
				//echo $starttimeH;
				
				//echo "<br/>";
				//echo $endtimeM;
			}
			return $days;
	}
?>