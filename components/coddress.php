<?php 

// Simple user CRUD functions
// In a later version this will be completely Object Oriented, using all the classes in the (improved) dotcore, when I get around to it

// Set the maximum possible code (8 digits)
define ("MAX_CODE", 99999999);
// define ("MAX_CODE", 999999);

function validate_coddress($country,$zip_code,$prefecture,$city,$addr_line1,$addr_line2)
{
	$err = array();
	if(!is_numeric($zip_code) || strlen($zip_code) != 7) {
		$err["zip_code"] = 'Invalid zip code';
	}

	if(empty($prefecture)) {
		$err["prefecture"] = "The prefecture cannot be empty";
	}

	if(empty($city)) {
		$err["city"] = "The city cannot be empty";
	}

	if(empty($addr_line1)) {
		$err["addr_l1"] = "The address cannot be empty";
	}
	return $err;
}

// Returns address_id (todo - proper comments hopefully with pdv)
function insert_address($country,$prefecture,$city,$postal_code,$addr_line1,$addr_line2,$expiration=NULL,$user = NULL) // $created, $principal_addr
{
	global $mysqli;

	// Set up the codes to be inserted
	$code1 = substr($postal_code,0,3); // Take 3 characters starting from position 0
	$code2 = generate_random_code($code1);

	$query = 'INSERT INTO addresses (country,prefecture,city,postal_code,addr_line1,addr_line2,expiration,code1,code2,user) VALUES (
		'.$country.',
		"'.escape($prefecture).'",
		"'.escape($city).'",
		'.$postal_code.',
		"'.escape($addr_line1).'",
		"'.escape($addr_line2).'",
		'.timestamp2sql($expiration).',
		'.$code1.',
		'.$code2.',
		'.sql_value_or_null($user).'
	)';

	// echo $query;

	// TODO: Check for failures
	$mysqli->query($query);
	return $mysqli->insert_id;
}

function generate_random_code($code1)
{
	// $possible_codes = get_possible_codes($code1);
	// return $possible_codes[mt_rand(0,count($possible_codes))];
	$count = get_count_possible_codes($code1);
	return get_possible_code($code1, mt_rand(1,$count));
}

function get_count_possible_codes($code1)
{
	global $mysqli;
	$query = 'SELECT COUNT(*) FROM codes WHERE code NOT IN (SELECT code2 FROM addresses WHERE code1='.$code1.')';

	$result = $mysqli->query($query);
	$row = $result->fetch_array();
	$result->close();
	return $row[0];
}

function get_possible_code($code1,$rand)
{
	global $mysqli;
	$query = 'SELECT code FROM codes WHERE code NOT IN (SELECT code2 FROM addresses WHERE code1='.$code1.') ORDER BY code LIMIT '.$rand.',1';

	$result = $mysqli->query($query);
	$row = $result->fetch_array();
	$result->close();
	return $row[0];
}

// This solution uses up too much RAM, is too wasteful
// function get_possible_codes($code1)
// {
// 	global $mysqli;
//
// 	// Return an array with all the codes available with $code1
// 	$possible_codes = array();
// 	$prev_code = 1;
//
// 	// Get only those addresses that are not yet expired
// 	$query = "SELECT code2 FROM addresses WHERE code1='$code1' AND (UNIX_TIMESTAMP(expiration) > ".time()." OR expiration = NULL) ORDER BY code2";
// 	if($result = $mysqli->query($query))
// 	{
// 		while ($row = $result->fetch_assoc()) {
// 			// Add all the addresses from the previous one till the one in row
// 			for($i = $prev_code; $i < $row["code2"]; $i++)
// 			{
// 				array_push($possible_codes,$i);
// 			}
// 			$prev_code = $row["code2"]+1;
// 		}
// 		$result->close();
// 	}
//
// 	// Add remaining codes
// 	for($i = $prev_code; $i <= MAX_CODE; $i++)
// 	{
// 		array_push($possible_codes,$i);
// 	}
// 	return $possible_codes;
// }

function def_expiration_userless_addr() {
	return time() + 14*24*60*60; // two weeks by default
}

function get_code($addr_id) {
	global $mysqli;
	$query = 'SELECT code1,code2 FROM addresses WHERE address_id='.$addr_id;
	// TODO check for failures
	$results = $mysqli->query($query);
	$row = $results->fetch_assoc();
	$code = $row['code1'].'-'.$row['code2'];
	$results->close();
	return $code;
}

function &get_addresses_by_user($uid)
{
	global $mysqli;
	$query = 'SELECT * FROM addresses WHERE user='.escape($uid);
	$results = $mysqli->query($query);
	$addr_array = array();

	while($row = $results->fetch_assoc())
	{
		array_push($addr_array,$row);
	}
	$results->close();
	return $addr_array;
}

function get_coded_address($addr1,$addr2)
{
	global $mysqli;
	$query = 'SELECT * FROM addresses WHERE code1='.escape($addr1).' AND code2='.escape($addr2);
	$results = $mysqli->query($query);
	if(!$row = $results->fetch_assoc())
	{
		$results->close();
		return NULL;
	}
	$results->close();
	return $row;

}

function print_addr($addr)
{
	global $countries;
	return $addr["postal_code"] . " " . $countries[$addr["country"]] . " " . $addr["prefecture"] . " " . $addr["city"] . " " . $addr["addr_line1"] . " " . $addr["addr_line2"];
}
?>
