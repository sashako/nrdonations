<div id="nrd-form-wrapper">
	<div class="nrd-form">
		<header>
			<h3>Donate</h3>
			<span class="close-form">close</span>
		</header>

		<form>
			<fieldset>
				<label>Amount</label>
				<input type="text" class="currency" name="donation-ammount" />
			</fieldset>
			<fieldset>
				<label>Name</label>
				<input type="text" data-stripe="name" />
			</fieldset>
			<fieldset>
				<label>Email</label>
				<input type="email" name="email" />
			</fieldset>
			<fieldset>
				<label>Card Number</label>
				<input type="text" data-stripe="number" />
			</fieldset>
			<fieldset>
				<label>Expiration Date</label>
				<input type="text" data-stripe="exp-month" size="2" />
				<input type="text" data-stripe="exp-year" size="4" />
			</fieldset>
			<fieldset>
				<label>Security code (CVC)</label>
				<input type="text" size="4" data-stripe="cvc"/>
			</fieldset>
			<div class="errors"></div>
			<input type="submit" />
		</form>
	</div>
</div>