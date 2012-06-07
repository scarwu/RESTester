<?php
/**
 * RESTester Service Caller
 * 
 * @package		RESTful Server API Tester
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/RESTester
 */

class ServiceCaller {
	private $_host;
	private $_uri;
	private $_method;
	private $_header;
	private $_params;
	private $_file;
	private $_user_agent;
	
	public function __construct($query_string) {
		$info = json_decode($query_string, TRUE);

		$host = isset($info['host']) ? $info['host'] : 'http://localhost';
		$uri = isset($info['uri']) ? $info['uri'] : '/';
		
		$this->_url = $host . '/' . trim($uri, '/');
		$this->_file = isset($info['file']) ? $info['file'] : NULL;
		$this->_params = isset($info['params']) ? $info['params'] : NULL;
		$this->_header = isset($info['header']) ? $info['header'] : array();
		$this->_method = isset($info['method']) ? $info['method'] : 'get';
		
		$this->_user_agent = 'RESTester/0.1';
		$this->_client = curl_init();
	}
	
	/**
	 * Run
	 */
	public function run() {
		switch(strtolower($this->_method)) {
			case 'get':
				$this->get();
				break;
			case 'post':
				$this->post();
				break;
			case 'put':
				$this->put();
				break;
			case 'delete':
				$this->delete();
				break;
			default:
				header('HTTP/1.1 404 Not Found');
		}
	}
	
	/**
	 * Send get method
	 */
	private function get() {
		$this->_url .= NULL !== $this->_params ? '?' . $this->_params : '';
		
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_CUSTOMREQUEST => strtoupper($this->_method),
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			// HTTP Header
			CURLOPT_HTTPHEADER => $this->_header,
			
			// Result include header
			CURLOPT_HEADER => 1,
			
			CURLOPT_RETURNTRANSFER => true,
			
			CURLOPT_FOLLOWLOCATION => true
		));
		
		$output = curl_exec($this->_client);
		curl_close($this->_client);
		echo $output;
	}
	
	/**
	 * Send post method
	 */
	private function post() {
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_POST => 1,
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			CURLOPT_POSTFIELDS => $this->_params,
			
			// HTTP Header
			CURLOPT_HTTPHEADER => $this->_header,
			
			// Result include header
			CURLOPT_HEADER => 1,
			
			CURLOPT_RETURNTRANSFER => true,
			
			CURLOPT_FOLLOWLOCATION => true
		));
		
		$output = curl_exec($this->_client);
		curl_close($this->_client);
		echo $output;
	}
	
	/**
	 * Send put method
	 */
	private function put() {
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_PUT => 1,
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			// HTTP Header
			CURLOPT_HTTPHEADER => $this->_header,
			
			// Result include header
			CURLOPT_HEADER => 1,
			
			CURLOPT_RETURNTRANSFER => true,
			
			CURLOPT_FOLLOWLOCATION => true
		));
		
		$output = curl_exec($this->_client);
		curl_close($this->_client);
		echo $output;
	}
	
	/**
	 * Send delete method
	 */
	private function delete() {
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			// HTTP Header
			CURLOPT_HTTPHEADER => $this->_header,
			
			// Result include header
			CURLOPT_HEADER => 1,
			
			CURLOPT_RETURNTRANSFER => true,
			
			CURLOPT_FOLLOWLOCATION => true
		));
		
		$output = curl_exec($this->_client);
		curl_close($this->_client);
		echo $output;
	}
}
