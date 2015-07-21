<?php
//header.php
session_start();

echo '

<h1 id="logo">
	<a href="index.php"><img src="logo.png" style="max-height:3.4em;" /></a>
</h1>
<nav id="nav">
	<ul>
		<li><a href=".">Home</a></li>
		<li><a href="details.php">Details</a></li>
		<li><a href="impressum.php">Impressum</a></li>';
		if(isset($_SESSION['login'])){
			if ($_SESSION['login'] == true ){

				echo '
				<li><a href="planning.php">Planung</a></li>
				<li><a href="login.php?logout" class="button special" style="background-color:blue;">Logout</a></li>';
			}
		}else{
			echo '
		<li><a href="login.php" class="button special" style="background-color:blue;">Login</a></li>
		<li><a href="register.php?try=false" class="button special">Registrieren</a></li>
		';
	}

echo '
	</ul>
</nav>

';
?>
