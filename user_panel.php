<?php

include ("./config.php");

// $include_js = "javascript/address_registration.js";
// $include_css = "stylesheets/address_registration.css";
include 'header.php';
?>
<div id="main_content">
	<h2>User Panel</h2>
	<p>The coddresses currently associated with your account are the following:</p>
	<ul>
<?php

// For now, just retrieve the list of coddresses belonging to the current user and display it on a table - in later versions, add editing capabilities
$rows = &get_addresses_by_user(get_logged_in_uid());
$count = count($rows);

for($i = 0; $i < $count; $i++)
{
	$row = $rows[$i];
	echo '<li>'.$row["code1"]."-".$row["code2"].' : '.print_addr($row).'</li>';
}
?>
	</ul>
</div>
<?php
include 'footer.php';
?>
