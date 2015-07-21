<!DOCTYPE HTML>
<!--
	Landed by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
// check if user is logged in and/or his rights and/or log him in first
require_once '/etc/php5/passwordLib.php';
session_start();
include('db.php');

$err = false;
if(isset($_POST['email'])){ //log him in
	$email = $_POST['email'];
	$pass = $_POST['affe'];

	$stmt = $mysqli->prepare('SELECT HASH, TYPE, VNAME, ID FROM `users` WHERE USER = ?');
	$stmt->bind_param('s', $email);
	$res = $stmt->execute();
	$stmt->bind_result($hash, $rights, $vname, $uid);
	
	if($stmt->fetch()){
		if(password_verify($pass, $hash)){
			$_SESSION['login'] = true;
			$_SESSION['user'] = $email;
			$_SESSION['rights'] = $rights;
			$_SESSION['vname'] = $vname;
			$_SESSION['uid'] = $uid;
		}else{
			$err = true;
			$errmsg = "Passwort leider nicht korrekt.";
		}
	}else{
		$err = true;
		$errmsg = "eMail-Adresse ist unbekannt.".$email;
	}
	$stmt->close();

}

?>


<html>
	<head>
		<title>StarGIS - Planung</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrollex.min.js"></script>
		<script type="text/javascript" src="./js/jquery.datetimepicker.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
			<link rel="stylesheet" type="text/css" href="js/jquery.datetimepicker.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<!-- LEAFLET -->

		<link rel="stylesheet" href="css/leaflet_var.css" />
		<script src="js/leaflet.js"></script>
		<script type="text/javascript" src="js/tile.stamen.js"></script>

		<script>
    jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
        return this.each(function() {
            var select = this;
            var options = [];
            $(select).find('option').each(function() {
                options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data('options', options);
            $(textbox).bind('change keyup', function() {
                var options = $(select).empty().data('options');
                var search = $.trim($(this).val());
                var regex = new RegExp(search,"gi");
              
                $.each(options, function(i) {
                    var option = options[i];
                    if(option.text.match(regex) !== null) {
                        $(select).append(
                           $('<option>').text(option.text).val(option.value)
                        );
                    }
                });
                if (selectSingleMatch === true && $(select).children().length === 1) {
                    $(select).children().get(0).selected = true;
                }
            });            
        });
    };

    $(function() {
        $('#liststar').filterByText($('#filterit'), true);
    });  

    function selectAll() 
    { 
        selectBox = document.getElementById("filterstar");

        for (var i = 0; i < selectBox.options.length; i++) 
        { 
             selectBox.options[i].selected = true; 
        } 
    }
</script>


	</head>
	<body>

		<!-- Header -->
			<header id="header" class="skel-layers-fixed">
				<?php
					include('header.php');
				?>
			</header>

<?php
if(isset($_SESSION['login'])){
	$vname = $_SESSION['vname'];
	echo '
		<!-- Main -->
			<div id="main" class="wrapper style1">
				<div class="container">
					<!--<header class="major">
						<h2></h2>
					</header>-->
					<div class="row 150%">
						<div class="4u 12u$(medium)">

							<!-- Sidebar -->
								<section id="sidebar">
									<section>
										<h3>Letztgeplante Beobachtungen:</h3>';
										
	$stmt2 = $mysqli->prepare("SELECT obs.ID, loc.NAME, obs.CREATED FROM `observations` AS obs 
		LEFT JOIN `observation_locations` AS loc ON loc.ID = obs.LOCATION_ID 
		WHERE USER_ID = ? ORDER BY obs.CREATED DESC LIMIT 5;");
	$uid = $_SESSION['uid'];
	$stmt2->bind_param('s', $uid);
	$res = $stmt2->execute();
	$stmt2->bind_result($obs_id, $loc_NAME, $date_created);
	$cnt = 0;
	while($stmt2->fetch()){
			echo '<p><b><a href="obs_details.php?id='.$obs_id."\">".$loc_NAME."</a></b><br / >Geplant am ".$date_created."</p>\n";
			$cnt ++;
	}
	if($cnt == 0){
			echo '<p>Keine letzen Planungen</p>';
	}

	


										echo '
									</section>
									<hr />
									<section>
									<h3>Aktuelle Himmelsereignisse</h3>
									<ul>
										<li>Geminiden: Meteorschauer</li>
										<li>Komet Lovejoy: Immer noch sichtbar</li>

									</ul>
									</section>
								</section>

						</div>
						<div class="8u$ 12u$(medium) important(medium)">
								<h3>Neue Beobachtung planen:</h3>
								<form action="obs_details.php?planit=true" method="post">
								<section id="content">
									<div style="width:45%; float:left">
									<h4>(Zwerg-)Planeten</h4>

											<input type="checkbox" name="planet[]" value="mercury" id="mercury">
											 <label for="mercury">Merkur</label> 
											<input type="checkbox" name="planet[]" value="venus" id="venus">
											 <label for="venus">Venus</label> 
											<input type="checkbox" name="planet[]" value="mars" id="mars">
											 <label for="mars">Mars</label> 
											<input type="checkbox" name="planet[]" value="jupiter" id="jupiter">
											 <label for="jupiter">Jupiter</label> 
											<input type="checkbox" name="planet[]" value="saturn" id="saturn">
											 <label for="saturn">Saturn</label> 
											<input type="checkbox" name="planet[]" value="uranus" id="uranus">
											 <label for="uranus">Uranus</label> 
											<input type="checkbox" name="planet[]" value="neptune" id="neptune">
											 <label for="neptune">Neptun</label> 
											<input type="checkbox" name="planet[]" value="pluto" id="pluto">
											 <label for="pluto">Pluto</label> 
											 <hr style="margin-top:10px;margin-bottom:10px;"/>
											<input type="checkbox" name="planet[]" value="moon" id="moon">
											 <label for="moon">Mond</label> 
											 <hr style="margin-top:10px;margin-bottom:10px;"/>
											 Datum/Uhrzeit (UTC):
											 <input type="text" value="'.date('Y-m-d H:i').'" name="date" />

									</div>

											<div style="width:53%; float:right;height:293px;margin-top:-40px;">
											<div id="leafmap" style="height:100%; width:100%"></div>
											<script type="text/javascript">
											var layer = new L.StamenTileLayer("toner");
											var leafmap = new L.Map("leafmap", {
											    center: new L.LatLng(48.21887796010553, 16.403320133686066),
											    zoom: 7
											});
											leafmap.addLayer(layer);
											var fipoIcon = L.icon({
											    iconUrl: \'http://maps.google.com/mapfiles/kml/shapes/triangle.png\',

											    iconSize:     [20, 20], // size of the icon
											    iconAnchor:   [10, 10], // point of the icon which will correspond to marker s location
											});


											L.marker([48.24266494390566, 16.25668144086376], {icon: fipoIcon}).addTo(leafmap).bindPopup("<h3 style=\"color:#101010;\">Sofienalpe</h3>");
											L.marker([48.54575284393436, 16.25738524831835], {icon: fipoIcon}).addTo(leafmap).bindPopup("<h3 style=\"color:#101010;\">Oberleis</h3>");
											L.marker([47.77564158773253, 16.02657508570700], {icon: fipoIcon}).addTo(leafmap).bindPopup("<h3 style=\"color:#101010;\">Hohe Wand: Gasthof Postl</h3>");
											L.marker([47.76114955361699, 15.98755931714550], {icon: fipoIcon}).addTo(leafmap).bindPopup("<h3 style=\"color:#101010;\">Hohe Wand: Wildgehege</h3>");

											leafmap.on(\'click\', function(e) {
												NS = (e.latlng.lat>0)?" N , ":" S, ";
												WE = (e.latlng.lng>0)?" E":" W";
												degNS = String(Math.abs(e.latlng.lat));
												degWE = String(Math.abs(e.latlng.lng));
											    $("#position").val( degNS + NS + degWE + WE);
											});

											</script>

									&nbsp;Koordinaten per Klick in die Karte setzen.
									<input type="text" value="48.12345 N, 16.98765 E" name="position" id="position" />
										</div>
									</section>
							<br style="clear:both;">
							<section>
							<hr /> <div>
							<div style="float:left;width:40%;">
						Sterne im FK6 (FK6-Nummer, Name):<br /><br />
						<select id="liststar" multiple="multiple" style="width:100%;height:250px;">
						';
						
						//Prepare a statement
						$stmt = $mysqli->prepare("SELECT ID, NAME FROM `stellar_object`");
						$stmt->bind_result($D_ID, $D_NAME);
						//Get results
						$stmt->execute();
						
						while ($stmt->fetch()){
						echo '<option value="'.$D_ID.'">'.$D_NAME.'</option>';
						}
						
						echo '
						</select>	
						Filter: <input type="text" id="filterit" />				
						</div>
						
						<div style="float:left;width:16%;margin-left:2%;margin-right:2%;">
						<br/><br/><br/>
						<input style="width: 100%;" id="moveright" type="button" value="&gt;&gt;" onClick=\'$("#liststar"+"  option:selected").appendTo("#filterstar");\' /> <br/>
						<input style="width: 100%;" id="moveleft" type="button" value="&lt;&lt;" onClick=\'$("#filterstar"+"  option:selected").appendTo("#liststar");\' />
						</div>
						
						<div style="float:left;width:40%;">
						Diese Sterne/Objekte in die Suche miteinbeziehen:<br />
						<select name="filterstar[]" id="filterstar" multiple="multiple" style="width:100%;height:250px;">

						</select>	
						<br />
						<input style="width:100%;" class="special" type="submit" value="Beobachtung planen!" onClick="selectAll()" />
						</div>				
						</div>

									

								</section>
								</section>

						</div>
					</div>
				</div>
			</div>
';
}else{
	if($err){
		echo '<div id="main" class="wrapper style1">
				<div class="container">
					<header class="major">
					<h2>Fehler</h2>
	<p>'.$errmsg.'<br /><a href="login.php" style="color:#F0F0F0;">Hier gehts zum Login!</a></p>
	</header></div></div>';
	}else{

	echo '<div id="main" class="wrapper style1">
				<div class="container">
					<header class="major">
					<h2>Sorry, Du bist nicht eingeloggt!</h2>
	<p><a href="login.php" style="color:#F0F0F0;">Hier gehts zum Login!</a></p>
	</header></div></div>';
}
}
?>
		<!-- Footer -->
			<footer id="footer">
				<?php
					include('footer.php');
				?>
			</footer>


	</body>
</html>
