(function (window, Stripe, $){

	"use strict";

	Stripe.setPublishableKey('pk_test_26cmfa6BTw7EZO4HnnMmGqot'); //move to php


	var NRDForm = (function (){
		var $errorsContainer,
			$formContainer, 
			$form;

		function buildForm() {
			var el = document.createElement('div'),
				html = '';

			el.setAttribute('id', 'nrd-form-wrapper');

			html += '<div class="form"><header><h3>Donate</h3><span class="close-form">close</span></header>';

			html += '<form><fieldset><label>Amount</label><input type="text" class="currency" name="donation-ammount"/></fieldset>';
			html += '<fieldset><label>Name</label><input type="text" data-stripe="name" /></fieldset>';
			html += '<fieldset><label>Email</label><input type="email" name="email" /></fieldset>';
			html += '<fieldset><label>Card Number</label><input type="text" data-stripe="number"/></fieldset>';
			html += '<fieldset><label>Expiration Date</label><input type="text" data-stripe="exp-month" size="2"/><input type="text" data-stripe="exp-year" size="4" /></fieldset>';
			html += '<fieldset><label>Security code (CVC)</label><input type="text" size="4" data-stripe="cvc"/></fieldset>';
			html += '<div class="errors"></div>';
			html += '<input type="submit" />';
			html += '</form></div>';	

			el.innerHTML = html; 
			document.body.appendChild(el);

			$formContainer = $('#nrd-form-wrapper');
			$form = $formContainer.find('form');
			$errorsContainer = $formContainer.find('.errors');
		
			bindEvents();

		}

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


			}
		}

		function showForm() {

		}

		function hideForm() {


		}


		return {
			init : buildForm,
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