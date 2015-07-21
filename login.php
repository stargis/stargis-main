<!DOCTYPE HTML>
<!--
	Landed by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
require_once '/etc/php5/passwordLib.php';

$text = "";
if(isset($_GET['logout'])){
		session_start();
		session_destroy();
		$text = "<h2>Erfolgreich ausgeloggt.</h2>";
	
}


?>
<html>
	<head>
		<title>StarGIS - Login</title>
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

		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
				<?php
					include('header.php');
				?>
			</header>

		<!-- Main -->
			<div id="main" class="wrapper style1">
				<div class="container">
					<header class="major">
					<?php echo $text; ?>
						<h2>Login f&uuml;r registrierte Benutzer</h2>
						<p>Herzlich Willkommen! Wohin darf es heute Abend gehen?</p>
					</header>
					<div class="row 150%">
						<div class="8u 12u$(medium)">

							<!-- Content -->
								<section id="content">
									<h3>Login</h3>
									<form method="post" action="planning.php">
									<div class="row uniform 50%">
									<div class="6u$ 12u$(xsmall)">
										<input type="email" name="email" id="email" value="" placeholder="eMail-Adresse" />
									</div>
									<div class="6u$ 12u$(xsmall)">
										<input type="password" name="affe" id="pass" value="" placeholder="Passwort" />
										
									</div>
									<div style="float:right;clear:none;width:50%;">
									<ul class="actions"><li>
									<input type="submit" value="Login" class="special" style="background-color:blue;float:left;" />
									</li></ul>
									</div>
									</div>
									
									</form>
									<p>Dein Benutzername ist die eMail-Adresse, mit der Du dich bei uns registriert hast.</p>
									<p>Wenn Du dein Passwort vergessen hast, kannst Du <a href="forgotpassword.php">hier</a> ein neues anfordern.</p>
									
								</section>

						</div>
						<div class="4u$ 12u$(medium)">

							<!-- Sidebar -->
								<section id="sidebar">
									<section>
										<h3>Noch nicht registriert?</h3>
										<p>Du kannst dich entweder zu einem Testaccount anmelden oder dich gleich registrieren:</p>
										<footer>
											<ul class="actions">
												<li><a href="#" class="button" style="padding-bottom:1em;">Testaccount starten</a></li>
											</ul>
											<ul class="actions">
												<li><a href="#" class="button special">Jetzt Registrieren</a></li>
											</ul>
										</footer>
									</section>
								</section>

						</div>
					</div>
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