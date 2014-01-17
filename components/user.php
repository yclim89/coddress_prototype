<?php 

// Simple user CRUD functions
// In a later version this will be completely Object Oriented, using all the classes in the (improved) dotcore, when I get around to it

function validate_user($last_name, $first_name, $email, $password, $password_confirm)
{
	$err = array();
	// Allow for empty last_names and first names
	// if(empty($last_name)) { $err["last_name"] = "The last name cannot be empty"; }
	// if(empty($first_name)) { $err["first_name"] = "The first name cannot be empty"; }
	if(empty($email)) { $err["email"] = "The email cannot be empty"; }
	// Check for duplicate emails
	else if(user_email_registered($email)) { $err["email"] = "This email has already been registered"; }
	else if (!is_valid_email($email)) { $err["email"] = "The email is invalid"; }
	if(empty($password)) { $err["register_password"] = "The password cannot be empty"; }
	else if ($password != $password_confirm) { $err["register_password"] = "The submitted passwords do not match"; }
	return $err;
}

// Returns user_id (todo - proper comments hopefully with pdv)
function insert_user($last_name,$first_name,$email,$password) // $created, $principal_addr
{
	global $mysqli;

	$query = 'INSERT INTO users (last_name,first_name,email,password) VALUES (
		"'.escape($last_name).'",
		"'.escape($first_name).'",
		"'.escape($email).'",
		"'.escape(encrypt_password($password)).'"
	)';

	// TODO: Check for failures
	$mysqli->query($query);
	return $mysqli->insert_id;
}

function update_user_principal_addr($user_id, $principal_addr)
{
	global $mysqli;
	$query = 'UPDATE users SET principal_addr='.$principal_addr.' WHERE user_id='.$user_id; 

	// TODO: Check for failures
	$mysqli->query($query);
	return true; // Assume success (todo: check for failure)
}

function get_user_id($email)
{
	global $mysqli;
	$query = 'SELECT user_id FROM users WHERE email = "'.escape($email).'"';

	// TODO: Check for failures
	$result = $mysqli->query($query);
	$row = $result->fetch_array();
	$user_id = $row[0];
	$result->close();
	return $user_id;
}

function user_email_registered($email)
{
	global $mysqli;
	$query = 'SELECT COUNT(*) FROM users WHERE email = "'.escape($email).'"';

	// TODO: Check for failures
	$result = $mysqli->query($query);
	$row = $result->fetch_array();
	$count = $row[0];
	$result->close();
	return $count > 0;
}

function valid_credentials($email, $password)
{
	global $mysqli;
	$query = 'SELECT COUNT(*) FROM users WHERE email = "'.escape($email).'" AND password="'.escape(encrypt_password($password)).'"';

	// TODO: Check for failures
	$result = $mysqli->query($query);
	$row = $result->fetch_array();
	$count = $row[0];
	$result->close();
	return $count == 1;
}

function valid_credentials_hashed($email, $password)
{
	global $mysqli;
	$query = 'SELECT COUNT(*) FROM users WHERE email = "'.escape($email).'" AND password="'.$password.'"';

	// TODO: Check for failures
	$result = $mysqli->query($query);
	$row = $result->fetch_array();
	$count = $row[0];
	$result->close();
	return $count == 1;
}

function encrypt_password($password)
{
	return hash("sha256",$password.SALT);
}
?>
