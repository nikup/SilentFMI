<?php
	include 'dbconnect.php';
	$dbConnect = new DBConnect();
	if(isset($_SESSION['SESSION_ID'])) {
		$userid = $_SESSION['SESSION_ID'];
		$query = $dbConnect->createQuery("SELECT", "*", "rules", "userid = '$userid'");
		$sql = $dbConnect->sendQuery($query);
		echo "<table border=1>";
		echo "<th>Title</th>
			<th>Repetition</th>
			<th>Day</th>
			<th>Start time</th>
			<th>End time</th>
			<th>Delete</th>
			";
		while($row = mysql_fetch_array($sql)) {
			echo "<tr><td>";
			echo $row['name'];
			echo "</td><td>";
			echo $row['periodtype'];
			echo "</td><td>";

			if($row["periodtype"]=="DAILY")
			{
				echo "Every";
			}
			if($row["periodtype"]=="WEEKLY")
			{
				if($row['perioddata']==1) echo "Mon";
				if($row['perioddata']==2) echo "Tue";
				if($row['perioddata']==3) echo "Wed";
				if($row['perioddata']==4) echo "Thu";
				if($row['perioddata']==5) echo "Fri";
				if($row['perioddata']==6) echo "Sat";
				if($row['perioddata']==7) echo "Sun";
			}
			if($row["periodtype"]=="MONTHLY")
			{
				echo $row['perioddata'];
			}
			if($row[periodtype]=='ONETIME')
			{
				echo date("d.m",$row['perioddata']);
			}
			echo "</td><td>";
			echo date("H:i",$row['fromtime']);
			echo "</td><td>";
			echo date("H:i",$row['endtime']);
			echo "</td><td>";
			$ruleid = $row['ruleid'];
			echo "<a href=delete.php?ruleid=$ruleid>DELETE</a>";
			echo "</td></tr>";
		}
		
		echo "</table>";
	}
?>