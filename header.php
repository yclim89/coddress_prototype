<!DOCTYPE html>
<html>
<head>
    <title>Coddress</title>
    <meta charset="utf-8" />

    <script src="javascript/jquery-2.0.3.min.js" type="text/javascript" charset="utf-8"></script>

<?php

if($include_js !== NULL && file_exists($include_js)) {
	echo '<script src="'.$include_js.'" type="text/javascript" charset="utf-8"></script>';
}

if($include_css !== NULL && file_exists($include_css)) {
	echo '<link rel="stylesheet" href="'.$include_css.'" type="text/css" media="screen" title="address_registration" charset="utf-8">';
}
?>
</head>
<body>
    <header>
	<h1><a href="index.php">Coddress</a></h1>
	<nav>
		<ul>
			<li><a href="address_registration.php">Register a new address</a></li>
			<li><a href="decode.php">Decode a coddress</a></li>
		</ul>
	</nav>
    </header>
