<?php 
	$nrd_options = get_option('nrd_settings');

?>

<div class="wrap">
		<h2>Donations Settings</h2>
		<form method="post" action="options.php">
 
			<?php settings_fields('nrd_settings_group'); ?>
 
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">Test Mode</th>
						<td>
							<input id="nrd_settings[test_mode]" name="nrd_settings[test_mode]" type="checkbox" value="1" <?php checked(1, $nrd_options['test_mode']); ?> />
							<label class="description" for="nrd_settings[test_mode]">Check this to use the plugin in test mode.</label>
						</td>
					</tr>
				</tbody>
			</table>	
 
			<h3 class="title">Stripe API Keys</h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">Live Secret Key</th>
						<td>
							<input id="nrd_settings[live_secret_key]" name="nrd_settings[live_secret_key]" type="text" class="regular-text" value="<?php echo isset($nrd_options['live_secret_key']) ? $nrd_options['live_secret_key'] : ''; ?>"/>
							
						</td>
					</tr>
					<tr valign="top">	
						<th scope="row" valign="top">Live Publishable Key</th>
						<td>
							<input id="nrd_settings[live_publishable_key]" name="nrd_settings[live_publishable_key]" type="text" class="regular-text" value="<?php echo isset($nrd_options['live_publishable_key']) ? $nrd_options['live_publishable_key'] : ''; ?>"/>
							
						</td>
					</tr>
					<tr valign="top">	
						<th scope="row" valign="top">Test Secret Key</th>
						<td>
							<input id="nrd_settings[test_secret_key]" name="nrd_settings[test_secret_key]" type="text" class="regular-text" value="<?php echo isset($nrd_options['test_secret_key']) ? $nrd_options['test_secret_key'] :''; ?>"/>
							
						</td>
					</tr>
					<tr valign="top">	
						<th scope="row" valign="top">Test Publishable Key</th>
						<td>
							<input id="nrd_settings[test_publishable_key]" name="nrd_settings[test_publishable_key]" class="regular-text" type="text" value="<?php echo isset($nrd_options['test_publishable_key']) ? $nrd_options['test_publishable_key'] : ''; ?>"/>
							
						</td>
					</tr>
				</tbody>
			</table>	
 			<h3 class="title">Mailchimp Settings</h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">Mailchimp API Key</th>
						<td>
							<input id="nrd_settings[mailchimp_key]" name="nrd_settings[mailchimp_key]" class="regular-text" type="text" value="<?php echo isset($nrd_options['mailchimp_key']) ? $nrd_options['mailchimp_key'] : ''; ?>"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">List ID</th>
						<td>
							<input id="nrd_settings[list_id]" name="nrd_settings[list_id]" class="regular-text" type="text" value="<?php echo isset($nrd_options['list_id']) ? $nrd_options['list_id'] : '' ; ?>"/>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
 
		</form>
	</div>