<?php

include ("./config.php");

$err = array();
if(isset($_REQUEST["decode"]))
{
	if($addr = get_coded_address($_REQUEST["code1"],$_REQUEST["code2"]))
	{
		$addr_str = print_addr($addr);
	}
	else
	{
		$err["process"] = "The code submitted does not exist in the database";
	}
}
// $include_js = "javascript/address_registration.js";
// $include_css = "stylesheets/address_registration.css";
include 'header.php';
?>
<div id="main_content">
	<h2>Decode an address</h2>
	<form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" accept-charset="utf-8">

<?php 
if(!empty($err["process"])) echo '<div class="error">'.$err["process"].'</div>';
else if(isset($addr_str)) echo 'The coddress '.$_REQUEST["code1"]."-".$_REQUEST["code2"]." points towards the following address: ".$addr_str;
?>

		<div><label for="code1">Code:</label><input type="text" name="code1"<?php print_submitted_value("code1"); ?> id="code1">-<input type="text" name="code2"<?php print_submitted_value("code2"); ?> value="" id="code2"></div>

		<p><input type="submit" name="decode" value="Decode"></p>

	</form>
</div>
<?php
include 'footer.php';
?>
