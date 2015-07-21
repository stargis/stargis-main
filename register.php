<!DOCTYPE HTML>
<!--
	Landed by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php

require_once '/etc/php5/passwordLib.php';
// PROCESSING REGISTRATION
$reg = false;
if (isset($_POST['email'])){
	$err = false;

	$required = array('email', 'affe1', 'affe2', 'vname', 'nname', 'try');

	foreach($required as $field) {
	  if (empty($_POST[$field])) {
	    $err = true;
	    $errmsg = "<li>Nicht alle Felder ausgefuellt</li>";
	  }
	}

	if(!$err){
		$email = strtolower($_POST['email']);
		$vname = $_POST['vname'];
		$nname = $_POST['nname'];
		$passw = $_POST['affe1'];
		$pass2 = $_POST['affe2'];
		$try = $_POST['try'];
		$user_type = ($try=="true"?4:2);
		include('db.php');
		$stmt = $mysqli->prepare('SELECT USER FROM `users` WHERE USER = ? AND TYPE != 4');
		$stmt->bind_param('s', $email);
		$stmt->bind_result($existinguser);
		$res = $stmt->execute();
		while($stmt->fetch()){
			$err = true;
			$errmsg.= "<li>Unter dieser eMail-Adresse ist bereits ein Benutzer registriert!</li>";
		}

		if (!$err && $passw == $pass2){

			$reg = true;

			$header = 'From: noreply@stargis.tk' . "\r\n" .
			    'Reply-To: noreply@stargis.tk' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

			$msg="Hallo ".$vname."!\n
			Du bekommst diese eMail, weil du dich mit der Adresse $email auf stargis.tk registriert hast.
			Die eMail-Adresse ist gleichzeitig dein Login!
			Liebe Gruesse, das StarGIS-Team";

			mail($email, "Registrierung StarGIS", $msg, $header);
			

			$stmt = $mysqli->prepare('INSERT INTO `users` (USER, HASH, NAME, VNAME, TYPE) VALUES (?, ?, ?, ?, ?)');
			$stmt->bind_param("ssssd", 
				$email, 
				password_hash($passw, PASSWORD_DEFAULT), 
				$nname, 
				$vname,
				$user_type );
			$res = $stmt->execute();
		} else {
			$errmsg .= "<li>Die Passw&ouml;rter stimmen nicht &uuml;berein!</li>";
			$err = true;
		}

	}
}

?>


<html>
	<head>
		<title>StarGIS - Registrieren</title>
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
					<?php
					if($reg){
						if(!$err){
							echo '<h2>Registrierung erfolgreich!</h2>
							<p>Bitte schau in Deiner Inbox der Adresse '.$_POST['email'].' nach,
							Du solltest eine Nachricht von uns bekommen haben!';
						}else{
							echo '<h2>Registrierung fehlgeschlagen!</h2>
							<p>Bitte &uuml;berpr&uuml;fe Deine Angaben!</p><ul>'.$errmsg.'</ul>';
						}
					}

					?>
						<h2>Registrieren</h2>
					</header>
					<div class="row 150%">
						<div class="8u 12u$(medium)" style="margin-left:-10px;">

							<!-- Content -->
							<?php
								if(!isset($_GET['try'])){
									$_GET['try'] = "false";
								}
								if($_GET['try'] == "false"){
									echo '
								<section id="content">
									<h3>Registrieren</h3>
									<form method="post" action="register.php">
									<div class="row uniform 50%">
									<input type="hidden" name="try" value="false" />
									<div class="6u$ 12u$(xsmall)">
										<input type="text" name="vname" id="vname" value="" placeholder="Vorname" />
									</div>
									<div class="6u$ 12u$(xsmall)">
										<input type="text" name="nname" id="nname" value="" placeholder="Nachname" />
									</div>
									<div class="6u$ 12u$(xsmall)">
										<input type="email" name="email" id="email" value="" placeholder="eMail-Adresse (=Login)" />
									</div>
									<div class="6u$ 12u$(xsmall)">
										<input type="password" name="affe1" id="pass" value="" placeholder="Passwort" />
									</div>
									<div class="6u$ 12u$(xsmall)">
										<input type="password" name="affe2" id="pass" value="" placeholder="Passwort wiederholen" />
										
									</div>
									<div style="float:right;clear:none;width:50%;">
									<ul class="actions"><li>
									<input type="submit" value="Registrieren" class="special" style="background-color:blue;float:left;" />
									</li></ul>									
									</div>

									<p>Doch zuerst nur testen? Klicke <a href="register.php?try=true">hier</a>!</p>

									</div>
									</form>
									
									
								</section>
								
								';
								} else {
									echo '
								<section id="content">
									<h3>Testaccount starten</h3>
									<form method="post" action="register.php">
									<div class="row uniform 50%">
									<input type="hidden" name="try" value="true" />
									<div class="6u$ 12u$(xsmall)">
										<input type="email" name="email" id="email" value="" placeholder="eMail-Adresse (=Login)" />
									</div>
									<ul class="actions"><li>
									<input type="submit" value="Testaccount jetzt starten" class="special" style="background-color:blue;float:left;" />
									</li></ul>
									
									<p>Mit dem Testaccount kannst Du eine Planung unter Berücksichtugung der bekanntesten Sterne und Planeten 
									vornehmen. Wenn du auch Deep Sky Objekte sehen willst und Nebel und Galaxien in deinen Pl&auml;nen haben willst,
									registriere Dich bitte voll!</p>
									</form>
									<p>Doch gleich registrieren? Klicke <a href="register.php?try=false">hier</a>!</p>
									
									</div>
								</section>
								';

								}
							?>

						</div>
						<div class="4u$ 12u$(medium)">

							<!-- Sidebar -->

									<section>
										<h3>Bereits registriert?</h3>
										<p>Gleich hier einloggen:</p>
										<footer>
									<section id="content">
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