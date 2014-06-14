(function (window, Stripe, $){

	"use strict";

	Stripe.setPublishableKey(FormProcessAJAX.pkey); 


	var NRDForm = (function (){

		var $formContainer = $('#nrd-form-wrapper'),
			$form = $formContainer.find('form'), 
			$errorsContainer = $formContainer.find('.errors'),
			$closeButton = $formContainer.find('.close-form'),
			emailRex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
			strRex = /\S/;


		function bindEvents() {
			$closeButton.on('click', hideForm);
			$formContainer.on('click', hideForm);
			$('.nrd-form').on('click', function(e){ e.stopPropagation();});
			$form.on('submit', checkForErrors);
			$form.on('keydown', 'input.error', removeErrors);
		}

		function removeErrors() {
			var $input = $(this);

			$input.removeClass('error');

			if($form.find('input.error').length < 1) $errorsContainer.removeClass('show').html('');
			
		}
		function checkForErrors(e) {
			var emailVal = $form.find('input[name="email"]').val(),
				amountVal = $form.find('input[name="amount"]').val(),
				cvcVal = $form.find('input[name="cvc"]').val(),
				cardVal = $form.find('input[name="card-number"]').val(),
				yearVal = $form.find('input[name="exp-year"]').val(),
				monthVal = $form.find('input[name="exp-month"]').val(),
				errors = [];

			if( !amountVal || !strRex.test(amountVal) || parseInt( amountVal ) < 5 ) {
				errors.push({
					message : 'Please enter a valid donation amount. $5 minimum.',
					el : $form.find('input[name="amount"]')
				});
			}

			if( !emailVal || !emailRex.test( emailVal ) ) {
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
					displayError(errors[i], i==l-1);
				}
			} else {
				submitForm();
			}

			e.preventDefault();
		}
		function displayError (err, last) {
			var isLast = last || false;
			if(err.el) err.el.addClass('error');
			$errorsContainer.append('<span>'+err.message+'</span>');
			if(isLast) $errorsContainer.addClass('show');
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
				action : 'submit_form',
				token : $form.find('input[name="stripeToken"]').val(),
				amount : $form.find('input[name="amount"]').val(),
				name : $form.find('input[name="name"]').val(),
				email : $form.find('input[name="email"]').val(),
				subscribe : $form.find('input[name="subscribe"]').is(':checked')
			};
			call.success = successfullySubmitted;
			call.error = function() {
				displayError({message: 'Oops, something went wrong. Please try submitting the form again.', el: false}, true);
			}

			$.ajax(call);
		}

		function successfullySubmitted(data, status, xhr) {
			if(status === 'success') {
				localStorage.setItem('donated', true);
				$formContainer.addClass('thank-you');
			} else {
				displayError({message: 'Oops, something went wrong. Please try submitting the form again.', el: false}, true);
			}
		}

		function showForm() {
			$formContainer.addClass('show');
		}

		function hideForm() {
			$formContainer.removeClass('show thank-you');
			$form[0].reset();

		}

		return {
			init : bindEvents,
			show : showForm, 
			hide : hideForm
		};

	}) ();

	
	var buttons = $('.nr-donate-button'),
		supportsStorage = testStorage(),
		currentMonth = new Date().getMonth() + 1;

	if(buttons.length > 0 ) {

		NRDForm.init();
		
		buttons.on('click', NRDForm.show);

		if(supportsStorage) controlAutoPopup();
		
	}

	function testStorage() {
		try {
			localStorage.setItem('test',true);
			localStorage.removeItem('test');
			sessionStorage.setItem('test', true);
			sessionStorage.removeItem('test');
			return true;
		} catch (e) {
			return false;
		} 
	}

	function controlAutoPopup() {
		var month = localStorage.getItem('month'),
			visits = localStorage.getItem('visitCount');

		if(!sessionStorage.getItem('inSession')) {

			if(visits&&month) {

				if(+month === +currentMonth) {
					var c = +localStorage.getItem('visitCount');
					if(c === 9 )  {
						if(!localStorage.getItem('donated')) setTimeout(NRDForm.show, 1000);
					} 
					localStorage.setItem('visitCount', c+1);
					
				} else {
					localStorage.setItem('month', currentMonth);
					localStorage.setItem('visitCount', 0);
					if(localStorage.getItem('donated')) localStorage.removeItem('donated');
				}
			} else {
				localStorage.setItem('visitCount', 0);
				localStorage.setItem('month', currentMonth);
			}
			sessionStorage.setItem('inSession', true);
		}
		
	}

})(window, Stripe, jQuery);