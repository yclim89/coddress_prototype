<!DOCTYPE html>
<html>
<head>
    <title>Coddress</title>
    <meta charset="utf-8" />

    <script src="javascript/jquery-2.0.3.min.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="./stylesheets/forms.css" type="text/css" media="screen" charset="utf-8">

<?php

if(!empty($include_js) && file_exists($include_js)) {
	echo '<script src="'.$include_js.'" type="text/javascript" charset="utf-8"></script>';
}

if(!empty($include_css) && file_exists($include_css)) {
	echo '<link rel="stylesheet" href="'.$include_css.'" type="text/css" media="screen" charset="utf-8">';
}
?>
</head>
<body>
    <header>
	<h1><a href="index.php">Coddress</a></h1>

<?php 
if(!is_logged_in()) { // show the nav for non-logged in users
?>
	<nav>
		<ul>
			<li><a href="address_registration.php">Register a new address</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="decode.php">Decode a coddress</a></li>
		</ul>
	</nav>
<?php 
} else {
?>
	<nav>
		<ul>
		<li><a href="user_panel.php">User Panel</a></li>
		<li><a href="logoff.php">Logoff</a></li>
		<li><a href="decode.php">Decode a coddress</a></li>
		</ul>
	</nav>
<?php
}
?>
    </header>
