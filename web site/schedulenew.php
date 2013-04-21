<?php
	include 'dayarray.php';
?>
<!DOCTYPE html>
<html>
	<head></head>
	<body><?php
			if(isset($_GET['user']))
			{
			$user = mysql_real_escape_string($_GET['user']);
			$dbConnect = new dbConnect();
			$queryUser = $dbConnect->createQuery("SELECT", "id", "users", "username = '$user'");
			$sql = $dbConnect->sendQuery($queryUser);
			if(!mysql_num_rows($sql)) {
				echo "nouser";
				exit;
			}
			$sql = $dbConnect->sendQuery($queryUser);
			$user=mysql_fetch_array($sql);
			$id=$user[id];

			$time=time()+3600*24*10;

			$days=getDaysArray($time, 7, $id);

			}
				$dateToday=(int)date('d',$time);
				$monthToday=(int)date('m',$time);
				$startTime=0;
				$endTime=0;

				for ($i=0; $i < 31; $i++) { 
					$day=$dateToday.".".$monthToday." ";
					echo $day;
					for ($h=0; $h < 24; $h++) { 
						for ($m=0; $m < 6; $m++) { 
							//echo $days[$i*144+$h*6+$m];
							if(isset($days[$i*144+$h*6+$m])==false&&$days[$i*144+$h*6+$m+1]==1)
								{
									if($m==5)
										{
											$h+=1;
											$m=0;
										}
									$startTime=$h.":".$m."0";
									echo $startTime;
									echo "-";
								}
							if (isset($days[$i*144+$h*6+$m])&&$days[$i*144+$h*6+$m]==1&&isset($days[$i*144+$h*6+$m+1])==false) 
							{
								if($m==5)
										{
											$h+=1;
											$m=0;
										}
								$endTime=$h.":".$m."0";
								echo $endTime;
								echo "|";
								}
						}
					}
					
					
					$dateToday+=1;
					if($dateToday>(int)date('t',$time))
					{
						$dateToday=1;
						$monthToday+=1;
					}
					echo "\n";
				}
			//$endtime=$endtimeH*24+(int)($endtimeM/10);
			?>
			</body>
			</html>