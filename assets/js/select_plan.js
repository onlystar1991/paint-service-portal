$(function() {

	var public_key = "pk_test_yyRJBpIuegu4K0G1PJgdZTZQ";
	var payment_plan = "";
	
	var handler = StripeCheckout.configure({
		key: public_key,
		image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		name: 'Videre Project',
		locale: 'auto',
		token: function(token) {
			handler.close();
			
			var data = {
				token_id: token.id,
				plan_id: payment_plan
			};

			console.log(data);
			$.ajax({
				url: base_url + 'user/subscribe',
				type: 'post',
				data: data,
				dataType: 'json',
				success: function(response) {
					console.log(response);
					if (response.status == 'ok') {
						// window.location.href = base_url + 'home/dashboard';
					}
				}
			})
		}
	});

	$(".plan-picker").click(function() {
		$("#select_monthly_or_yearly").modal();
		payment_plan = $(this).data('plan');
	})

	$("#pay-monthly").click(function() {
		payment_plan = payment_plan + "-monthly";
		console.log(payment_plan);

		$("#select_monthly_or_yearly").modal('hide');
		handler.open({
			name: 'Your Payment Info',
			zipCode: false,
			panelLabel: "Add payment",
			allowRememberMe: false,
			email: email
		});
	});

	$("#pay-yearly").click(function() {
		payment_plan = payment_plan + "-yearly";

		console.log(payment_plan);
		$("#select_monthly_or_yearly").modal('hide');

		handler.open({
			name: 'Your Payment Info',
			zipCode: false,
			panelLabel: "Add payment",
			allowRememberMe: false,
			email: email
		});
	});

})