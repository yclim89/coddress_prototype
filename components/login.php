<?php

// Login related functions

$logged_in = FALSE;

function login($email, $password)
{
	global $logged_in;

	/* Set cookie to last 1 year */
	setcookie('username', $email, time()+60*60*24*365);
	setcookie('password', escape(encrypt_password($password)), time()+60*60*24*365);
	$logged_in = true;
}

function is_logged_in()
{
	global $logged_in;
	if($logged_in) return $logged_in;
	if(!isset($_COOKIE["username"]) || !isset($_COOKIE["password"])) return false;
	return valid_credentials_hashed($_COOKIE["username"],$_COOKIE["password"]);
}

function logoff()
{
	setcookie('username', "", time()-3600);
	setcookie('password', "", time()-3600);
	unset($_COOKIE["username"]);
	unset($_COOKIE["password"]);
}

function get_logged_in_email()
{
	return isset($_COOKIE["username"]) ? $_COOKIE["username"] : NULL;
}

function get_logged_in_uid()
{
	return get_user_id(get_logged_in_email());
}
?>
