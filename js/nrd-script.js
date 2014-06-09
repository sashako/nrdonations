(function (window, Stripe, $){

	"use strict";

	Stripe.setPublishableKey('pk_test_26cmfa6BTw7EZO4HnnMmGqot'); //move to php


	var NRDForm = (function (){

		var $formContainer = $('#nrd-form-wrapper'),
			$form = $formContainer.find('form'), 
			$errorsContainer = $formContainer.find('.errors');


		function bindEvents() {
			$form.on('submit', submitForm);
			$form.on('keydown', 'input.error', removeErrors);
		}

		function removeErrors() {
			var input = $(this);

			input.removeClass('error');
			$errorsContainer.html('');
		}

		function submitForm(e) {
			

			Stripe.card.createToken($form, stripeResponseHeader);
			$form.find('input[type="submit"]').prop('disabled', true);

			e.preventDefault();
		}

		function stripeResponseHeader(status, response) {
			
			if(response.error) {

				$form.find('input[data-stripe="'+response.error.param+'"]').addClass('error');
				$errorsContainer.html('<span>'+response.error.message+'</span>');
				$form.find('input[type="submit"]').prop('disabled', false);

			} else {
					
				var token = response.id;
    
    			$form.append($('<input type="hidden" name="stripeToken" />').val(token));

    			sendToTheServer();

			}
		}

		function sendToTheServer() {
			var call = {},
				data;

			call.url = FormProcessAJAX.url;
			call.type = 'POST';
			call.data = {
				formContent : data,
				action: 'submit_form',
				 _ajax_nonce: FormProcessAJAX.nonce
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