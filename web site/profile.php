<?php
	include 'dbconnect.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Silent FMI</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<script>
			function changing(who) {
				switch(who.value) {
				
					case "DAILY":
						document.getElementById("weekly").style.display="none";
						document.getElementById("dayy").style.display="none";
						document.getElementById("monthly").style.display="none";
						document.getElementById("onetime").style.display="none";
						document.getElementById("onetimedayy").style.display="none";
						document.getElementById("onetimetekst").style.display = "none";
					break;
					
					case "WEEKLY":
						document.getElementById("monthly").style.display="none";
						document.getElementById("onetime").style.display="none";
						document.getElementById("onetimedayy").style.display="none";
						document.getElementById("weekly").style.display = "block";
						document.getElementById("dayy").style.display="block";
						document.getElementById("onetimetekst").style.display = "none";
					break;
					
					case "MONTHLY":
						document.getElementById("monthly").style.display="block";
						document.getElementById("dayy").style.display="block";
						document.getElementById("onetime").style.display="none";
						document.getElementById("onetimedayy").style.display="none";
						document.getElementById("weekly").style.display = "none";
						document.getElementById("onetimetekst").style.display = "none";
					break;
					
					case "ONETIME":
						document.getElementById("monthly").style.display="none";
						document.getElementById("dayy").style.display="none";
						document.getElementById("onetime").style.display="block";
						document.getElementById("onetimedayy").style.display="block";
						document.getElementById("weekly").style.display = "none";
						document.getElementById("onetimetekst").style.display = "block";
						
					break;
					
				}
			}
		</script>
		<div id="header">
			<a href="index.php" title="SilentFmi" id="logo">
				<img src="images/logo.png" alt=""/>
			</a>
		</div>
		<div id="main_content">
		<?php
			if(isset($_SESSION['vleznal'])) {
				?>
			<p class='message'><?php echo $_SESSION['SESSION_USERNAME'] ?></p>
				<p class='message'>See all events <a href='list.php'>here</a></p>
				<form class="user_form" method="post" action="add.php">
					<fieldset>
						<legend>Event Handler</legend>
						<label for="eventtitle">Event title</label> <input type="text" name="rulename" id="eventtitle"/><br/>
						
						<span class="dropdown_label">Repetition</span>
						<div class="select_big">
							<select name="period" id="period" onchange="changing(this)">
								<option value="DAILY">Daily</option>
								<option value="WEEKLY">Weekly</option>
								<option value="MONTHLY">Monthly</option>
								<option value="ONETIME">Onetime</option>
							</select>
						</div>
						
						<span class="dropdown_label" id="dayy" style="display:none">Day</span>
						<div class="select_big" id="weekly" style="display:none">						
							<select name="weekly">
								<option value="1">Monday</option>
								<option value="2">Tuesday</option>
								<option value="3">Wednesday</option>
								<option value="4">Thursday</option>
								<option value="5">Friday</option>
								<option value="6">Saturday</option>
								<option value="7">Sundey</option>
							</select>
						</div>
						
						
						<div class="select_small" id="monthly" style="display:none">
							<select name="monthly">
								<?php
									for($i=1;$i<=31;$i++)
										echo "<option value=$i>$i</option>";
								?>
							</select>
						</div>
					
					<span class="dropdown_label" id="onetimetekst" style="display:none">Date:</span>
					<div class="select_small" id="onetime" style="display:none">
						<select name="onetimeM">
							<option value="January">January</option>
							<option value="February">February</option>
							<option value="March">March</option>
							<option value="April">April</option>
							<option value="May">May</option>
							<option value="June">June</option>
							<option value="July">July</option>
							<option value="August">August</option>
							<option value="September">September</option>
							<option value="October">October</option>
							<option value="November">November</option>
							<option value="December">December</option>
						</select>
					</div>
					
					<div class="select_small" id="onetimedayy" style="display:none">
						<select name="onetimeD">
							<?php
								for($i=1;$i<=31;$i++)
									echo "<option value=$i>$i</option>";
							?>
						</select>
					</div>

					
					<span class="dropdown_label">Start time:</span>
					<div class="select_small">
						<select name="fromtimehour">
							<?php
								for($i=0;$i<=23;$i++) {
									if($i<10)
										echo "<option value=0$i>0$i</option>";
									else
										echo "<option value=$i>$i</option>";
								}
							?>
						</select>
					</div>
					<div class="select_small">
						<select name="fromtimeminute">
							<option value="00">00</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
							<option value="50">50</option>
						</select>
					</div>
					
					<span class="dropdown_label">End time:</span>
					<div class="select_small">
						<select name="endtimehour">
							<?php
								for($i=0;$i<=23;$i++) {
									if($i<10)
										echo "<option value=0$i>0$i</option>";
									else
										echo "<option value=$i>$i</option>";
								}
							?>
						</select>
					</div>
					<div class="select_small">
						<select name="endtimeminute">
							<option value="00">00</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="30">30</option>
							<option value="40">40</option>
							<option value="50">50</option>
						</select>
					</div>
					<?php if($_GET['error'] == "1") : ?>
						<p class="message">Event title is empty!</p>
					<?php elseif($_GET['error'] == "2") : ?>
						<p class="message">Wrong day of month!</p>
					<?php elseif($_GET['error'] == "3") : ?>
						<p class="message">End time is before start time!</p>
						<?php elseif($_GET['error'] == "0") : ?>
						<p class="message">Succesfully added event!</p>
					<?php endif; ?>
					
					<button type="submit" name="submit">Add event</button>
				</form>
				<?php
			}
			else {
				echo "<p class='message'>You are currently not logged in!</p>";
			}
		?>
		</div>
		
		<div id="footer">
			<?php if(isset($_SESSION['vleznal'])) : ?>
				<p class="message"><a href="logout.php">Logout</a>|</p>
			<?php endif; ?>
			<p class="message"><a href="about.php">About</a></p>
			<span>powered by</span>
			<a href="#" id="hackfmi"><img src="images/hackfmi.png" alt="HackFMI"/></a>
		</div>
	</body>
</html>