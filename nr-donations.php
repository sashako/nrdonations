<?php 

/*

Plugin Name: NR Donations
Description: Donations plugin for Newtown Radio, New York
Version: 0.9
Author: Sasha Konovalova
Author URI: http://www.sashakonovalova.com
License: GPL2

*/

class NR_Donations {

	public function __construct() {

		if(is_admin()) {

			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_init', array(&$this, 'register_nrd_settings'));
		}

		add_shortcode( 'nrdonate', array(&$this, 'create_donate_button'));
		
		add_action('init', array(&$this, 'create_post_type'));

		add_action('wp_footer', array(&$this, 'register_user_assets'));

		add_action('wp_ajax_submit_form', array(&$this, 'process_form'));
		add_action('wp_ajax_nopriv_submit_form', array(&$this, 'process_form'));

		require_once(dirname(__FILE__).'/include/Stripe.php');
		
	
	}

	function create_post_type() {
		$labels = array(
			'singular_name' =>'donation',
			'name' => 'donations',
			'not_found' => 'no donations found'
		);
		register_post_type('donation', array(
			'label' => 'Donations',
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => false, 
			'exclude_from_search' => true,
			'supports' => false,
			
			'show_in_menu' => false
		));
		
	}

	function create_donate_button($atts) {
		echo 'test';
		$a = shortcode_atts( array(
			'text' => 'Donate'
		), $atts );
		return '<span class="nr-donate-button inline">'.$a['text'].'</span>';
	}

	function register_nrd_settings() {
		register_setting('nrd_settings_group', 'nrd_settings');
	}

	function admin_menu() {
		add_menu_page('Donations', 'Donations', 'administrator', 'nr-donations/nrd-admin-list.php', '', 'dashicons-marker', 25);
		add_options_page('Donations Settings', 'Donations Settings', 'administrator', 'nr-donations/nrd-admin-settings.php');
	}

	function register_user_assets() {

		echo file_get_contents(plugins_url('assets/templates/nrd-form.php', __FILE__ ));

		wp_register_script('stripe-js', 'https://js.stripe.com/v2/');
		wp_register_script('nr-donations-js', plugins_url('assets/js/nrd.min.js', __FILE__));
		wp_register_style('nr-donations-styles', plugins_url('assets/css/nrd-styles.css', __FILE__));

		wp_enqueue_script('stripe-js');
		wp_enqueue_script('nr-donations-js', array('jquery', 'stripe-js'));
		wp_enqueue_style('nr-donations-styles');

		wp_localize_script('nr-donations-js', 'FormProcessAJAX', array(
			'url' => admin_url('admin-ajax.php'),
			'pkey' => get_option('nrd_settings')['test_mode'] ? get_option('nrd_settings')['test_publishable_key'] : get_option('nrd_settings')['live_publishable_key']
		));

	}

	function process_form() {

		$nrd_opt = get_option('nrd_settings');
		$post_data = array();

		if(isset($nrd_opt['test_mode']) && $nrd_opt['test_mode']) {

			$secret_key = $nrd_opt['test_secret_key'];

		} else {	

			$secret_key = $nrd_opt['live_secret_key'];

		}

		$token = $_POST['token'];
		$amount = $_POST['amount'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$subscribe = $_POST['subscribe'];

		try {

			Stripe::setApiKey($secret_key);

			$customer = Stripe_Customer::create(array(
				'card' => $token,
				'email' => $email,
				'description' => $name)
			);

			$charge = Stripe_Charge::create(array(
				'amount' => intval($amount)*100, 
				'currency' => 'usd',
				'customer' => $customer->id)
			);

		} catch (Exception $e) {

			die('Stripe request failed');
		}

		if($subscribe == true) {

			require_once(dirname(__FILE__).'/include/MailChimpSubscribe.php');

			$list_id = $nrd_opt['list_id'];
			$mc_api_key = $nrd_opt['mailchimp_key'];
			$subscriber_name = strlen(trim($name)) == 0 ? null : $name;

			$mc = new MailChimpSubscribe($mc_api_key);

			$sub = $mc->add($list_id, $email, $subscriber_name);

		}
		$post_data['post_title'] = 'Donation by '.$name.' <'.$email.'>';
		$post_data['post_type'] = 'donation';
		$post_data['post_status'] = 'publish';
		$post_id = wp_insert_post($post_data);

		add_post_meta($post_id, 'donator', $name, true);
		add_post_meta($post_id, 'email', $email, true);
		add_post_meta($post_id, 'amount', intval($amount), true);
		add_post_meta($post_id, 'subscribed', $subscribe, true);

		die();
		
	}

};

new NR_Donations;




