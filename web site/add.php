<?php
	include 'dbconnect.php';
	$dbConnect = new DBConnect();
	if(isset($_SESSION['vleznal'])) {
		if(isset($_POST['submit'])) {
			$userid = $_SESSION['SESSION_ID'];
			$title = mysql_real_escape_string($_POST['rulename']);
			if($title == "")
			{
				header("Location:profile.php?error=1");
				exit;
			}
			$periodType = mysql_real_escape_string($_POST['period']);
			$type = 'RULE';
			switch($periodType) {
				case "DAILY":
					$periodData = null;
				break;
				
				case "WEEKLY":
					$periodData = mysql_real_escape_string($_POST['weekly']);
				break;
				
				case "MONTHLY":
					$periodData = mysql_real_escape_string($_POST['monthly']);
				break;
				
				case "ONETIME":
					if($_POST['onetimeD'] > 31 || $_POST['onetimeD'] < 1)
					{
						header("Location:profile.php?error=2");
						exit;
					}
					
					$time = strtotime($_POST['onetimeD']." ".$_POST['onetimeM']);
					$t = strtotime($_POST['onetimeM']);
					if((int)date("t",$t) < (int)$_POST['onetimeD'])
					{
						header("Location:profile.php?error=2");
						exit;
					}
					$periodData = $time;
				break;
				
				default:
					header("Location:profile.php");
					exit;
				break;
			}
			$expireDate = time() + 3600*24*30*6;
			$priority = 1;
			$fromtimehour = mysql_real_escape_string($_POST['fromtimehour']);
			$fromtimeminute = mysql_real_escape_string($_POST['fromtimeminute']);
			$endtimehour = mysql_real_escape_string($_POST['endtimehour']);
			$endtimeminute = mysql_real_escape_string($_POST['endtimeminute']);
			
			if($fromtimehour == "" || $fromtimeminute == "" || $endtimehour == "" || $endtimeminute == "")
			{
				header("Location:profile.php");
				exit;
			}
			
			$startTime = strtotime($_POST['fromtimehour'].":".$_POST['fromtimeminute']);
			$endTime = strtotime($_POST['endtimehour'].":".$_POST['endtimeminute']);
			
			if($endTime <= $startTime)
			{
				header("Location:profile.php?error=3");
				exit;
			}
				
			$query = $dbConnect->insertQuery("userid,type,name,periodtype,perioddata,expiredate,priority,fromtime,endtime",
								"'$userid', '$type', '$title', '$periodType', '$periodData', '$expireDate', '$priority', '$startTime', '$endTime'",
								"rules");
			$sql = $dbConnect->sendQuery($query);
			if($sql)
			{
				header("Location:profile.php?error=0");
			}
			
		}
	
	}
?>