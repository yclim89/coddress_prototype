<?php 

include ("./components/helper_functions.php");
include ("./components/login.php");
include ("./components/user.php");
include ("./components/coddress.php");
include ("./components/countries.php");

// Database config
define("DB_HOST", "localhost");
define("DB_USER", "perrin4869");
define("DB_PASS", "conan");
define("DB_NAME", "coddress");

date_default_timezone_set("UTC");

define("SALT", "VeRY_RANdomSTRINGforSALTINGAAA");

// Not the best practice, but for this prototype, use a global mysql connection
$mysqli  = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->query("SET NAMES utf8");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
