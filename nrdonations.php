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

	 	public function __construct() {

	   		add_action('enqueue_scripts', 'register_user_assets');

	 	}

	 	public function register_user_assets() {

	 		wp_register_script('nr-donations-js', plugins_url('js/nrd-script.js', __FILE__));
	 		wp_register_style('nr-donations-styles', plugins_url('css/nrd-styles.css', __FILE__))

	 	}

  	}
}

$GLOBALS['NRDonations'] = new NRDonations();

?>