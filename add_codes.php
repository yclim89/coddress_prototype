<?php

include ("./config.php");

for($i = 0; $i <= MAX_CODE; $i++)
{
	$query = 'INSERT INTO codes (code) VALUES ('.$i.')';
	$mysqli->query($query);
	echo "Code number ".$i." inserted successfully\n";
}
$mysqli->close();
?>
