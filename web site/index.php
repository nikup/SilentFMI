<?php
	include 'dbconnect.php';
	$dbConnect = new DBConnect();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Silent FMI</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div id="header">
			<a href="index.php" title="SilentFmi" id="logo">
				<img src="images/logo.png" alt=""/>
			</a>
		</div>
		<div id="main_content">
				
			<?php
			 if(isset($_GET['logout']))
				echo "<p class='message'>You have successfully logged out!</p>";
				
			if(isset($_SESSION['vleznal'])) {
				echo "<p class='message'>You are already logged in! Go to <a href=profile.php>profile</a></p>";
			}
			?>
		<?php
			if(isset($_POST['submit']) && !isset($_SESSION['vleznal'])) {
				$username = mysql_real_escape_string($_POST['username']);
				$password = sha1((get_magic_quotes_gpc() ? stripslashes($_POST['password']) : $_POST['password']));
				$query = $dbConnect->createQuery("SELECT","id,username","users","username = '$username' AND pwdhash = '$password'");
				$sql = $dbConnect->sendQuery($query);
				echo mysql_error();
				if($result = mysql_num_rows($sql) > 0) {
					?>
					<div id="success">
						<h1>Congratulations!</h1>
						<p>Go to your <a href="profile.php">profile</a></p>
					</div>
					<?php
					$_SESSION['vleznal'] = true;
					$query = $dbConnect->createQuery("SELECT","id,username","users","username = '$username' AND pwdhash = '$password'");
					$sql = $dbConnect->sendQuery($query);
					$result = mysql_fetch_assoc($sql);
					$_SESSION['SESSION_ID'] = $result['id'];
					$_SESSION['SESSION_USERNAME'] = $result['username'];
				}
				else
				{
					$invalid = true;
				}
			}
		?>
		<?php if(!isset($_SESSION['vleznal'])) : ?>
		<br/>
		<br/>
		<br/>
		<form class="user_form" method="post" action="index.php">
			<fieldset>
				<legend>Login</legend>
				<label for="username">username</label> <input <?php if(isset($invalid)) echo "class='error'"; ?> type="text" id="username" name="username"/><br/>
				<label for="pass">password</label> <input <?php if(isset($invalid)) echo "class='error'"; ?> type="password" name="password" id="pass"/><br/>
				<?php if(isset($invalid)) : ?>
					<p class="message">Invalid username or password !</p>
				<?php endif;?>
				<button type="submit" name="submit">Login</button>
				<a href="register.php">Not registered?</a>
			</fieldset>
		</form>
		<?php endif; ?>
		</div>
		
		<div id="footer">
			<?php if(isset($_SESSION['vleznal'])) : ?>
				<p class="message"><a href="logout.php">Logout</a>|</p>
			<?php endif; ?>
			<p class="message"><a href="about.php">About</a></p>
			<span>powered by</span>
			<a href="#" id="hackfmi"><img src="images/hackfmi.png" alt="HackFMI"/></a>
		</div>
		<?php
		?>
	</body>
</html>