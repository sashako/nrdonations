<div id="nrd-form-wrapper">
	<div class="nrd-form">
		<header>
			<h3>Donate</h3>
			<span class="close-form">close</span>
		</header>

		<form>
			<fieldset>
				<label>Amount<span>$5 minimum</span></label>
				<input type="text" class="currency" name="amount" />
			</fieldset>
			<fieldset>
				<label>Name</label>
				<input type="text" name="name" data-stripe="name" />
			</fieldset>
			<fieldset>
				<label>Email</label>
				<input type="email" name="email" />
			</fieldset>
			<fieldset>
				<label>Card Number</label>
				<input type="text" name="card-number" data-stripe="number" />
			</fieldset>
			<fieldset>
				<label>Expiration Date <span>(mm/yyyy)</span></label>
				<input type="text" name="exp-month" data-stripe="exp-month" size="2" />
				<input type="text" name="exp-year" data-stripe="exp-year" size="4" />
			</fieldset>
			<fieldset>
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
	</div>
</div>