<?php
	include 'dbconnect.php';
	$dbConnect = new DBConnect();
	if(isset($_SESSION['vleznal']))
		header("Location:index.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Silent FMI</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
	<?php if(!isset($_SESSION['vleznal'])) : ?>
		<div id="header">
			<a href="index.php" title="SilentFmi" id="logo">
				<img src="images/logo.png" alt=""/>
			</a>
		</div>
		
		<div id="main_content">
		<?php if(isset($_POST['register'])) 
		{
			$username = mysql_real_escape_string($_POST['username']);
			if(strlen($username) < 3)
				$validateuser = true;
			
			$password = sha1((get_magic_quotes_gpc() ? stripslashes($_POST['password']) : $_POST['password']));
			$password2 = sha1((get_magic_quotes_gpc() ? stripslashes($_POST['password2']) : $_POST['password2']));
			
			if($password != $password2)
				$validatepass = true;
			
			
			if(strlen($username) > 2 && $password == $password2)
			{
				$query = $dbConnect->createQuery("SELECT", "username", "users", "username = '$username'");
				$result = $dbConnect->sendQuery($query);
				if(mysql_num_rows($result) > 0)
					$validate = true;
				else {
					$query = $dbConnect->insertQuery("username,pwdhash","'$username', '$password'", "users");
					$result = $dbConnect->sendQuery($query);
					if($result) {
						$success = true;
					}
				}
			}
		}
			?>			
			<form class="user_form" method="post" action="register.php">
				<fieldset>
					<legend>Register</legend>
					<label for="username">Username</label> <input <?php if(isset($validate) || isset($validateuser)) echo "class='error'" ?> type="text" name="username" id="username" /><br/>
					<label for="pass">Password</label> <input <?php if(isset($validatepass)) echo "class='error'" ?> type="password" name="password" id="pass"/><br/>
					<label for="repass">Repeat password</label> <input type="password" name="password2" id="repass"/><br/>
					<?php if(isset($validateuser)) : ?>
						<p class='message'>Too short username!</p>
					<?php endif; ?>
					<?php if(isset($validate)) : ?>
						<p class='message'>This username is already registered!</p>
					<?php endif; ?>
					
					<?php if(isset($validatepass)) : ?>
						<p class='message'>Password mismatch!</p>
					<?php endif; ?>
					
					<?php if(isset($success)) : ?>
						<p class='message'>You have successfully registered your name! Go to <a href=index.php>login page</a></p>
					<?php endif; ?>
					<button type="submit" name="register">Register me!</button>
				</fieldset>
			</form>
		</div>
		
		<div id="footer">
			<?php if(isset($_SESSION['vleznal'])) : ?>
				<p class="message"><a href="logout.php">Logout</a>|</p>
			<?php endif; ?>
			<p class="message"><a href="about.php">About</a></p>
			<span>powered by</span>
			<a href="#" id="hackfmi"><img src="images/hackfmi.png" alt="HackFMI"/></a>
		</div>
	<?php endif; ?>
	</body>
</html>
