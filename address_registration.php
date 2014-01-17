<?php

ini_set('memory_limit', '512M');
include ("./config.php");

// Handle the form if submitted
$err = array();
if(!empty($_REQUEST["address_submit"])) {

	$err  = array();
	// Validate the address
	$err  = array_merge($err,validate_coddress($_REQUEST["country"], $_REQUEST["zip_code"], $_REQUEST["prefecture"], $_REQUEST["city"], $_REQUEST["addr_l1"], $_REQUEST["addr_l2"]));

	if($_REQUEST["registration"] == "new") 
	{
		// Validate registration information
		$err = array_merge($err,validate_user($_REQUEST["last_name"],$_REQUEST["first_name"],$_REQUEST["email"],$_REQUEST["register_password"],$_REQUEST["password_confirm"]));
	}

	if($_REQUEST["registration"] == "login") {
		// Validate the login credentials
		if(!valid_credentials($_REQUEST["login_email"],$_REQUEST["login_password"])) { $err["login"] = "The username password are incorrect"; }
		login($_REQUEST["login_email"],$_REQUEST["login_password"]);
	}

	if(count($err) == 0) {
		// echo "Success! Try to insert into database";
		$user_id = NULL; // The ID of the user registering this address, will stay null if no registration nor login information were submitted
		if($_REQUEST["registration"] == "new") {
			// Insert the new user
			if(!($user_id=insert_user($_REQUEST["last_name"],$_REQUEST["first_name"],$_REQUEST["email"],$_REQUEST["register_password"])))
			{
				$err["process"] = "User registration failed, please try again";
			}
			else login($_REQUEST["email"],$_REQUEST["register_password"]);
		}

		if($_REQUEST["registration"] == "login") 
		{
			$user_id = get_user_id(get_logged_in_email());
		}

		if(count($err)==0)
		{
			// Calculate the expiration date of the address
			$expiration = def_expiration_userless_addr();
			if($user_id) $expiration = NULL; // No expiration date if the user registered
			// At this point, we have the user we want to associate with the address (or none). Let's try inserting the address
			if($addr_id = insert_address($_REQUEST["country"],$_REQUEST["prefecture"],$_REQUEST["city"], $_REQUEST["zip_code"],$_REQUEST["addr_l1"],$_REQUEST["addr_l2"],$expiration,$user_id)) {
				// Try updating the primary address of the new user
				if($_REQUEST["registration"]=="new") {
					update_user_principal_addr($user_id, $addr_id);
				}
			}
			else {
				$err["process"] = "Error inserting address, please try again";
			}
		}
	}
}

$include_js = "javascript/address_registration.js";
$include_css = "stylesheets/address_registration.css";
include 'header.php';


?>
	<div id="main_content">
		<h2>Address Registration</h2>
		
		<!-- todo: add captcha, support multiple countries, radio box with 3 options: login, register, don't register (code will be available for 2 weeks) -->

		<form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" accept-charset="utf-8">

<?php 
if(!empty($err["process"])) echo '<div class="error">'.$err["process"].'</div>';
// echo $mysqli->error;
else if(empty($_REQUEST["address_submit"]) || count($err) > 0) { // If the form was not submitted or if there were errors, show the form again
?>
			<section id="address">
			<div>
				<label for="country">Country<span class="required">*</span>:</label><select name="country" id="country">
				<option value="107">Japan</option>
			</select>
			</div>

			<div><label for="zip_code">Zip Code (As in 1111111)<span class="required">*</span>:</label><input type="text" name="zip_code"<?php print_submitted_value("zip_code"); ?> id="zip_code"> <span class="error"><?php if(!empty($err["zip_code"])) echo $err["zip_code"]; ?></span></div>

			<div>
				<label for="prefecture">Prefecture<span class="required">*</span></label><input type="text" name="prefecture"<?php print_submitted_value("prefecture"); ?> id="prefecture"><span class="error"><?php if(!empty($err["prefecture"])) echo $err["prefecture"]; ?></span>
			</div>

			<div>
				<label for="city">City:<span class="required">*</span></label><input type="text" name="city"<?php print_submitted_value("city"); ?> id="city"><span class="error"><?php if(!empty($err["city"])) echo $err["city"]; ?></span>
			</div>

			<div><label for="addr_l1">Address Line 1<span class="required">*</span>:</label><input type="text" name="addr_l1"<?php print_submitted_value("addr_l1"); ?> id="addr_l1"><span class="error"><?php if(!empty($err["addr_l1"])) echo $err["addr_l1"]; ?></span>
			</div>

			<div>
			<label for="addr_l2">Address Line 2:</label><input type="text" name="addr_l2"<?php print_submitted_value("addr_l2"); ?> id="addr_l2">
			</div>
			</section>

			<div>
			<label for="registration">Registration:</label>
			<input type="radio" name="registration"<?php print_checked_radio("registration", "none", "none"); ?> value="none">Don't register
			<input type="radio" name="registration"<?php print_checked_radio("registration", "new", "none"); ?> value="new">New user
			<input type="radio" name="registration"<?php print_checked_radio("registration", "login", "none"); ?> value="login">Login
			</div>

			<section id="user_registration"<?php if(!empty($_REQUEST["registration"]) && $_REQUEST["registration"] == "new") echo ' class="open"';?>>
			<h3>User Registration</h3>
			<div>
				<label for="email">E-Mail<span class="required">*</span>:</label><input type="email" name="email"<?php print_submitted_value("email"); ?> id="email"><span class="error"><?php if(!empty($err["email"])) echo $err["email"]; ?></span>
			</div>
			<div>
				<label for="register_password">Password<span class="required">*</span>:</label><input type="password" name="register_password" id="password"><span class="error"><?php if(!empty($err["register_password"])) echo $err["register_password"]; ?></span>
			</div>
			<div>
				<label for="password_confirm">Password confirmation<span class="required">*</span>:</label><input type="password" name="password_confirm" id="password_confirm">
			</div>
			<div><label for="last_name">Last Name:</label><input type="text" name="last_name"<?php print_submitted_value("last_name"); ?> id="last_name"><span class="error"><?php if(!empty($err["last_name"])) echo $err["last_name"]; ?></span>
			</div>
			<div>
				<label for="first_name">First Name:</label><input type="text" name="first_name"<?php print_submitted_value("first_name"); ?> id="first_name"><span class="error"><?php if(!empty($err["first_name"])) echo $err["first_name"]; ?></span>
			</div>
			</section>

			<section id="login"<?php if(!empty($_REQUEST["registration"]) && $_REQUEST["registration"] == "login") echo ' class="open"';?>>
			<h3>Login</h3>
			
			<?php 
			if(!empty($err["login"])) {
			?> 

			<div class="error"><?php echo $err["login"]; ?></div>

			<?php } ?>

			<div>
				<div><label for="login_email">E-Mail:</label><input type="text" name="login_email"<?php print_submitted_value("login_email"); ?> id="login_email"></div>
				<div><label for="login_password">Password:</label><input type="password" name="login_password" id="login_password"></div>
			</div>
			</section>

		
			<p><input type="submit" name="address_submit" value="Register Address"></p>

<?php
}
else {
	// else, show a successful message
	if($_REQUEST["registration"] == "none") {
		// Address available for 2 weeks message
		echo "The address was successfully registered! The code will be available for another 2 weeks from now. The coddress is: " . get_code($addr_id);
	}
	else if($_REQUEST["registration"] == "login") {
		// The address was registered and added to your account successfully.
		echo "Your address was successfully registered! You can manage your addresses in your control panel. Your coddress is: " . get_code($addr_id);
	}
	else {
		// The address and user registration were completed successfully.
		echo "Your address was successfully registered! You can manage your addresses in your control panel. Your coddress is: " . get_code($addr_id);
	}
}
?>
		</form>
	</div>
<?php
include 'footer.php';
?>
