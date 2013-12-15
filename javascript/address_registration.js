// Handle the selection of the registration process

$(document).ready( function() {

	prevRegistration = $('input[name=registration][checked=checked]').attr("value");
	// alert(prevRegistration);

	$('input[name=registration]').change(function() {
	if (prevRegistration == "login") { 
		// hide the login form
		$("#login").hide("slow");
	}
	
	if (prevRegistration == "new") { 
		// hide the login form
		$("#user_registration").hide("slow");
	}
        if (this.value == 'login') {
		$("#login").show("slow");
		prevRegistration = 'login';
        }
        else if (this.value == 'new') {
		$("#user_registration").show("slow");
		prevRegistration = 'new';
        }
    });
});

var prevRegistration = "none"; // By default there's no registration
