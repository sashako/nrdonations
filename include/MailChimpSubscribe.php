<?php 
	/**
	* Simple MailChimp API wrapper for subscribing to a list.
	*
	* @author Sasha Konovalova <sasha.konovalova@gmail.com>
	*/

	class MailChimpSubscribe {

		private $api_key;
		private $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0';

		function __construct($api_key) {

			$this->api_key = $api_key;
			$this->method = 'lists/subscribe';
			list(, $datacentre) = explode('-', $this->api_key);
			$this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);

		}

		public function add($listId, $email, $name=null) {

			$vars = array();

			if($name!==null) {
				list($firstName, $lastName) = explode(' ', $name, 2);
				$vars['FNAME'] = $firstName;
				$vars['LNAME'] = $lastName;
			}

			$args = array(
				'apikey' => $this->api_key,
				'id' => $listId,
				'email' => array('email' => $email),
				'merge_vars' => $vars
			);

			return $this->make_request($args);
		}

		public function make_request($args=array(), $timeout=10) {

			$url = $this->api_endpoint.'/'.$this->method.'.json';

			if (function_exists('curl_init') && function_exists('curl_setopt')){

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
				$result = curl_exec($ch);
				curl_close($ch);

			} else {

				$json_data = json_encode($args);
				$result    = file_get_contents($url, null, stream_context_create(array(
					'http' => array(
						'protocol_version' => 1.1,
						'method'           => 'POST',
						'header'           => "Content-type: application/json\r\n".
											  "Connection: close\r\n" .
											  "Content-length: " . strlen($json_data) . "\r\n",
						'content'          => $json_data,
					),
				)));
			}

			return $result ? json_decode($result, true) : false;
		}
	}
?>