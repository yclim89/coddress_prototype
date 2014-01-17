<?php

include ("./config.php");

$err = array();
if(isset($_REQUEST["login"]))
{
	if(valid_credentials($_REQUEST["login_email"],$_REQUEST["login_password"]))
	{
		login($_REQUEST["login_email"],$_REQUEST["login_password"]);
		header( 'Location: index.php' );
	}
	else
	{
		$err["process"] = "The username and password are invalid, please try again.";
	}
}

// $include_js = "javascript/address_registration.js";
// $include_css = "stylesheets/address_registration.css";
include 'header.php';
?>
<div id="main_content">
	<h2>Login</h2>


	<form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" accept-charset="utf-8">

<?php 
if(!empty($err["process"])) echo '<div class="error">'.$err["process"].'</div>';
?>

		<div><label for="login_email">E-Mail:</label><input type="text" name="login_email"<?php print_submitted_value("login_email"); ?> id="login_email"></div>
		<div><label for="login_password">Password:</label><input type="password" name="login_password" id="login_password"></div>

	
		<p><input type="submit" name="login" value="Login"></p>

	</form>
</div>
<?php
include 'footer.php';
?>
