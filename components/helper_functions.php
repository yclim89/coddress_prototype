<?php
// Define helper functions
function print_submitted_value($key) {
	if(!empty($_REQUEST[$key])) {
		echo ' value="'.$_REQUEST[$key].'"';
	}
}

function print_checked_radio($key, $value, $default) {
	if(empty($_REQUEST[$key])) {
		if($value == $default) echo ' checked="checked"';
	}
	else {
		if($_REQUEST[$key] == $value) echo ' checked="checked"';
	}
}

function is_valid_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL );
}
?>
