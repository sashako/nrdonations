<div id="nrd-form-wrapper">
	<div class="nrd-form">
		<header>
			<h3>Donate</h3>
			<span class="close-form">&nbsp;</span>
		</header>

		<form>
			<fieldset class="inline">
				<label>Amount <span>( $5 minimum )</span></label>
				&#36; <input type="text" name="amount" />
			</fieldset>
			<fieldset class="full">
				<label>Name</label>
				<input type="text" name="name" data-stripe="name" />
			</fieldset>
			<fieldset class="full">
				<label>Email</label>
				<input type="text" name="email" />
			</fieldset>
			<fieldset class="full">
				<label>Card Number</label>
				<input type="text" name="card-number" data-stripe="number" />
			</fieldset>
			<fieldset class="inline">
				<label>Expiration Date <span>( mm / yyyy )</span></label>
				<input type="text" name="exp-month" data-stripe="exp-month" size="2" />
				<input type="text" name="exp-year" data-stripe="exp-year" size="4" />
			</fieldset>
			<fieldset class="inline">
				<label>Security code (CVC)</label>
				<input type="text" name="cvc" size="4" data-stripe="cvc"/>
			</fieldset>
			<fieldset>
				<input type="checkbox" name="subscribe" id="subscribe-to-nrn"/>
				<label for="subscribe-to-nrn">Subscribe to Newtown Radio newsletter.</label>
			</fieldset>
			<div class="errors"></div>
			<input type="submit" />
		</form>
		<div class="thank-you-message">
			<h4>Thank You</h4>
			<p>We appreciate your donation!</p>
		</div>
	</div>
</div>