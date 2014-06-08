<?php 

/*
Plugin Name: NRDonations
Description: Donations plugin for Newtown Radio, New York.
Author: Sasha Konovalova
Version: 0.9
Author URI: http://www.sashakonovalova.com/
*/

if( !class_exists( 'NRDonations' ) ) {

	class NRDonations {

	 	function __construct() {

	   		add_action('wp_footer', array($this, 'register_user_assets'));


	 	}

	 	function register_user_assets() {

	 		wp_register_script('stripe-js', 'https://js.stripe.com/v2/');
	 		wp_register_script('nr-donations-js', plugins_url('js/nrd-script.js', __FILE__));
	 		wp_register_style('nr-donations-styles', plugins_url('css/nrd-styles.css', __FILE__));

	 		wp_enqueue_script('stripe-js');
	 		
	 		wp_enqueue_script('nr-donations-js', array('jquery', 'stripe-js'));
	 		wp_enqueue_style('nr-donations-styles');

	 		

	 	}

  	}
}

$GLOBALS['NRDonations'] = new NRDonations();

?>