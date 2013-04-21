<?php
	include 'dbconnect.php';
	$dbConnect = new DBConnect();
	
	if(isset($_SESSION['vleznal']) && isset($_GET['ruleid'])) {
		$userid = $_SESSION['SESSION_ID'];
		$ruleid = $_GET['ruleid'];
		$query = $dbConnect->createQuery("SELECT", "userid,ruleid", "rules", "userid = '$userid' AND ruleid = '$ruleid'");
		$sql = $dbConnect->sendQuery($query);
		$result = mysql_fetch_assoc($sql);
		
		if($result['userid'] != $userid || $result['ruleid'] != $ruleid)
			exit;
		
		$query = "DELETE FROM rules WHERE ruleid = '$ruleid' AND userid = '$userid'";
		$sql = mysql_query($query);
		if($sql)
			header("Location:list.php?success=1");
		else
			header("Location:list.php?error=1");

	}
?>