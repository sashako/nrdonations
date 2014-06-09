<?php 

if( !class_exists( 'NRDonations' ) ) {

	class NRDonations {

	 	function __construct() {

	 		header('Content-type: application/json');

	   		add_action('wp_footer', array($this, 'register_user_assets'));
	   		
	   		add_action('wp_ajax_submit_form', array($this, 'process_form'));
	   		add_action('wp_ajax_nopriv_submit_form', array($this, 'process_form'));
    		
	 	}

	 	function register_user_assets() {
	 		echo file_get_contents(plugins_url('nrd-form.php', __FILE__ ));

	 		wp_register_script('stripe-js', 'https://js.stripe.com/v2/');
	 		wp_register_script('nr-donations-js', plugins_url('js/nrd-script.js', __FILE__));
	 		wp_register_style('nr-donations-styles', plugins_url('css/nrd-styles.css', __FILE__));

	 		wp_enqueue_script('stripe-js');
	 		
	 		wp_enqueue_script('nr-donations-js', array('jquery', 'stripe-js'));
	 		wp_enqueue_style('nr-donations-styles');

			wp_localize_script('nr-donations-js', 'FormProcessAJAX', array(
				'url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('nrd-submit-form')
			));

	 	}

	 	function process_form() {

	 		$nonce = $_POST['_ajax_nonce'];


	 		if (! wp_verify_nonce($nonce, 'nrd-submit-form')) {
     			die('Error');
    		}


	 	}

  	}
}

new NRDonations();

?>