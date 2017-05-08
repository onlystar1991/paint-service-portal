$(function() {

	//Sign in part
	$("#login-errors").hide();
	function login_callback(login_response) {
		var response = JSON.parse(login_response);
		if (response.status == 'success') {
			window.location.assign(base_url);
		}
		$("#login-errors").html(response.message);
		$("#login-errors").show();
		console.log(response.message);
	}
	$("#btn-login").click(function() {
		$("#login-form").ajaxSubmit(login_callback);
	})

	$('#login').on('hidden.bs.modal', function () {
		$("#login-errors").hide();
		$("#login-errors").html('');
	})

	//Sign up part
	$("#signup-errors").hide();
	function signup_callback(signup_response) {
		var response = JSON.parse(signup_response);
		if (response.status == 'success') {
			window.location.assign(base_url);
		}
		$("#signup-errors").html(response.message);
		$("#signup-errors").show();
		console.log(response.message);
	}
	$("#signup-button").click(function() {
		$("#signup-form").ajaxSubmit(signup_callback);
	})
	$('#signup').on('hidden.bs.modal', function () {
		$("#signup-errors").hide();
		$("#signup-errors").html('');
	})
})