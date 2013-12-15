<?php

include ("./components/helper_functions.php");
include ("./components/login.php");

// Handle the form if submitted
$err = array();
if(!empty($_REQUEST["address_submit"])) {

	if(!is_numeric($_REQUEST["zip_code"]) || strlen($_REQUEST["zip_code"]) != 7) {
		$err["zip_code"] = 'Invalid zip code';
	}

	if(empty($_REQUEST["prefecture"])) {
		$err["prefecture"] = "The prefecture cannot be empty";
	}

	if(empty($_REQUEST["city"])) {
		$err["city"] = "The city cannot be empty";
	}

	if(empty($_REQUEST["addr_l1"])) {
		$err["addr_l1"] = "The address cannot be empty";
	}

	if($_REQUEST["registration"] == "new") {
		// Validate registration information
		// last name, first name, email, passwords
		if(empty($_REQUEST["last_name"])) { $err["last_name"] = "The last name cannot be empty"; }
		if(empty($_REQUEST["first_name"])) { $err["first_name"] = "The first name cannot be empty"; }
		if(empty($_REQUEST["email"])) { $err["email"] = "The email cannot be empty"; }
		else if (!is_valid_email($_REQUEST["email"])) { $err["email"] = "The email is invalid"; }
		if(empty($_REQUEST["register_password"])) { $err["register_password"] = "The password cannot be empty"; }
		else if ($_REQUEST["register_password"] != $_REQUEST["password_confirm"]) { $err["register_password"] = "The submitted passwords do not match"; }
	}

	if($_REQUEST["registration"] == "login") {
		// Validate the login credentials
		if(!valid_credentials($_REQUEST["login_email"],$_REQUEST["login_password"])) { $err["login"] = "The username password are incorrect"; }
	}

	if(count($err) == 0) {
		echo "Success! Try to insert into database";
	}
}

$include_js = "javascript/address_registration.js";
$include_css = "stylesheets/address_registration.css";
include 'header.php';
?>
	<div id="main_content">
		<h2>Address Registration</h2>
		
		todo: add captcha, support multiple countries, radio box with 3 options: login, register, don't register (code will be available for 2 weeks)

		<form action="address_registration.php" method="post" accept-charset="utf-8">
			<section id="address">
			<div>
			<label for="country">Country:</label><select name="country" disabled="disabled" id="country">
				<option value="107">Japan</option>
			</select>
			</div>

			<div><label for="zip_code">Zip Code (As in 1111111):</label><input type="text" name="zip_code"<?php print_submitted_value("zip_code"); ?> id="zip_code"> <span class="error"><?php if(!empty($err["zip_code"])) echo $err["zip_code"]; ?></span></div>

			<div>
				<label for="prefecture">Prefecture</label><input type="text" name="prefecture"<?php print_submitted_value("prefecture"); ?> id="prefecture"><span class="error"><?php if(!empty($err["prefecture"])) echo $err["prefecture"]; ?></span>
			</div>

			<div>
			<label for="city">City</label><input type="text" name="city"<?php print_submitted_value("city"); ?> id="city"><span class="error"><?php if(!empty($err["city"])) echo $err["city"]; ?></span>
			</div>

			<div><label for="addr_l1">Address Line 1:</label><input type="text" name="addr_l1"<?php print_submitted_value("addr_l1"); ?> id="addr_l1"><span class="error"><?php if(!empty($err["addr_l1"])) echo $err["addr_l1"]; ?></span>
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
				<label for="email">E-Mail</label><input type="email" name="email"<?php print_submitted_value("email"); ?> id="email"><span class="error"><?php if(!empty($err["email"])) echo $err["email"]; ?></span>
			</div>
			<div>
				<label for="register_password">Password</label><input type="password" name="register_password" id="password"><span class="error"><?php if(!empty($err["register_password"])) echo $err["register_password"]; ?></span>
			</div>
			<div>
				<label for="password_confirm">Password confirmation</label><input type="password" name="password_confirm" id="password_confirm">
			</div>
			<div><label for="last_name">Last Name</label><input type="text" name="last_name"<?php print_submitted_value("last_name"); ?> id="last_name"><span class="error"><?php if(!empty($err["last_name"])) echo $err["last_name"]; ?></span>
			</div>
			<div>
				<label for="first_name">First Name</label><input type="text" name="first_name"<?php print_submitted_value("first_name"); ?> id="first_name"><span class="error"><?php if(!empty($err["first_name"])) echo $err["first_name"]; ?></span>
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
				<label for="login_email">E-Mail</label><input type="text" name="login_email"<?php print_submitted_value("login_email"); ?> id="login_email">
				<label for="login_password">Password</label><input type="password" name="login_password" id="login_password">
			</div>
			</section>

		
			<p><input type="submit" name="address_submit" value="Register Address"></p>
		</form>
	</div>
<?php
include 'footer.php';
?>
