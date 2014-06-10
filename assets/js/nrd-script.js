(function (window, Stripe, $){

	"use strict";

	Stripe.setPublishableKey(FormProcessAJAX.pkey); 


	var NRDForm = (function (){

		var $formContainer = $('#nrd-form-wrapper'),
			$form = $formContainer.find('form'), 
			$errorsContainer = $formContainer.find('.errors'),
			emailRex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
			strRex = /\S/;


		function bindEvents() {
			$form.on('submit', checkForErrors);
			$form.on('keydown', 'input.error', removeErrors);
		}

		function removeErrors() {
			var $input = $(this);

			$input.removeClass('error');

			if($form.find('input.error').length < 1) $errorsContainer.html('');
			
		}
		function checkForErrors(e) {
			var nameVal = $form.find('input[name="name"]').val(),
				emailVal = $form.find('input[name="email"]').val(),
				amountVal = $form.find('input[name="amount"]').val(),
				cvcVal = $form.find('input[name="cvc"]').val(),
				cardVal = $form.find('input[name="card-number"]').val(),
				yearVal = $form.find('input[name="exp-year"]').val(),
				monthVal = $form.find('input[name="exp-month"]').val(),
				errors = [];

			if( !nameVal || !strRex.test(nameVal)) {
				errors.push({
					message : 'Please enter your name.',
					el : $form.find('input[name="name"]')
				});
			}

			if( !amountVal || !strRex.test(amountVal) || parseInt( amountVal ) < 5 ) {
				errors.push({
					message : 'Please enter a valid donation amount. $5 minimum.',
					el : $form.find('input[name="amount"]')
				});
			}

			if( !emailVal || emailRex.test( emailVal ) ) {
				errors.push({
					message : 'Please enter a valid email address.',
					el : $form.find('input[name="email"]')
				});
			}

			if( !cardVal || !Stripe.card.validateCardNumber(cardVal) ) {
				errors.push({
					message : 'Please enter a valid card number.',
					el : $form.find('input[name="card-number"]')
				});
			}

			if( !yearVal || !monthVal || !Stripe.card.validateExpiry(monthVal, yearVal) ) {
				errors.push({
					message : 'Provided expiration date is invalid or missing.',
					el : $form.find('input[name^="exp-"]')
				});
			}

			if( !cvcVal || !strRex.test(cvcVal)) {
				errors.push({
					message : 'Security code for your card is missing.',
					el : $form.find('input[name="cvc"]')
				});
			}

			if(errors.length > 0) {
				var i = 0,
					l = errors.length;
				for(i; i<l; i++) {
					displayError(errors[i]);
				}
			} else {
				submitForm();
			}

			e.preventDefault();
		}
		function displayError (err) {
			err.el.addClass('error');
			$errorsContainer.append('<span>'+err.message+'</span>');
		}

		function submitForm(e) {

			Stripe.card.createToken($form, stripeResponseHeader);
			$form.find('input[type="submit"]').prop('disabled', true);

		}

		function stripeResponseHeader(status, response) {
			
			if(response.error) {
				var error = {
					message : response.error.message,
					el : $form.find('input[data-stripe="'+response.error.param+'"]')
				};

				displayError(error);
				
				$form.find('input[type="submit"]').prop('disabled', false);

			} else {
    
    			$form.append($('<input type="hidden" name="stripeToken" />').val(response.id));

    			sendToTheServer();

			}
		}

		function sendToTheServer() {
			var call = {};

			call.url = FormProcessAJAX.url;
			call.type = 'POST';
			call.data = {
				formData : $form.serialize(),
				action : 'submit_form',
				 _ajax_nonce : FormProcessAJAX.nonce
			};
			
			call.success = showThanks;

			$.ajax(call);
		}

		function showThanks(r,a) {
			console.log(r,a);
		}

		function showForm() {

		}

		function hideForm() {


		}


		return {
			init : bindEvents,
			show : showForm, 
			hide : hideForm
		};

	}) ();

	
	var buttons = $('nr-donate-button');

	if(buttons.length > -1 ) {

		NRDForm.init();

		buttons.on('click', NRDForm.show);
	}

})(window, Stripe, jQuery);