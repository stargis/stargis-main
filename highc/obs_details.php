<!DOCTYPE HTML>
<!--
	Landed by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>StarGIS - Beobachtungsdetails</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrollex.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<body>
<?php
session_start();

	if(isset($_GET['planit'])){
		$date = str_replace(" ", ",", str_replace(":", ",", str_replace("-", ",",$_POST['date'])));
		$planets = implode(",", $_POST['planet']);
		$stars = implode(",", $_POST['filterstar']);
		$user = $_SESSION['uid'];
		//echo $user." ".$date." ".$planets." ".$stars;
		$_GET['id'] = shell_exec("/usr/bin/python2.7 /home/stargis/bestoptions.py ".$user." ".$date." ".$planets." ".$stars);

	}
?>
		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
				<?php
			include('header.php');
				?>
			</header>

		<!-- Main -->
			<div id="main" class="wrapper style1">
				<div class="container">
						<?php
if(isset($_SESSION['login'])){
		include('db.php');
		$stmt = $mysqli->prepare('SELECT loc.`NAME` , loc.`POSITION_LAT` , loc.`POSITION_LON` , loc.`ADDITIONAL_INFORMATION` , loc.`WEATHER_CODE_ZAMG` , obs.`PLANNED_FOR` , obs.`HTML`
								FROM `observations` AS obs
								LEFT JOIN `observation_locations` AS loc ON loc.`ID` = obs.`LOCATION_ID`
								WHERE obs.`ID` = ?');
		$stmt->bind_param('s', $_GET['id']);
		$res = $stmt->execute();
		$stmt->bind_result($L_NAME, $L_LAT, $L_LON, $L_INFO, $L_ZAMG, $O_DATE, $O_HTML);

		if($stmt->fetch()){
				echo '<h3 style="color:#e01010;">Beobachtung am '.$O_DATE.'  -  '.$L_NAME.'</h3>';
				echo $O_HTML;
				echo '<hr /><p style="color:#e01010;"><b style="color:#e01010;">Anfahrtsdetails:</b><br />'.$L_INFO."</p>";
			}else{
				echo "<h3>Diese Beobachtung konnte leider nicht gefunden werden...</h3>";
			}
} else {
echo '<div id="main" class="wrapper style1">
				<div class="container">
					<header class="major">
					<h2>Sorry, Du bist nicht eingeloggt!</h2>
	<p><a href="login.php" style="color:#F0F0F0;">Hier gehts zum Login!</a></p>
	</header></div></div>';

}
?>
				</div>
			</div>

		<!-- Footer -->
			<footer id="footer">
				<?php
					include('footer.php');
				?>
			</footer>

	</body>
</html>
