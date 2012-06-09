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
		preg_match('/({(?:.|\n)+})(?:(?:.|\n)+)?/', $query_string, $match);
		$info = json_decode($match[1], TRUE);

		$host = isset($info['host']) ? $info['host'] : 'http://localhost';
		$uri = isset($info['uri']) ? $info['uri'] : '/';
		$this->_url = $host . '/' . trim($uri, '/');
		$this->_file = isset($info['file']) ? $info['file'] : NULL;
		$this->_params = isset($info['params']) ? json_encode($info['params']) : NULL;
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
	
	private function fliter($response) {
		$regex = '/((?:(?:(?:.|\w)+\r\n)+\r\n)?)((?:.|\n)+)/';
		
		preg_match($regex, $response, $match);
		
		if(NULL == ($json = json_decode(trim($match[2]), TRUE)))
			$json = trim($match[2]);

		return json_encode(array(
			'header' => trim($match[1]),
			'json' => $json
		));
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
			CURLOPT_CUSTOMREQUEST => 'GET',
			
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
		
		echo $this->fliter($output);
	}
	
	/**
	 * Send post method
	 */
	private function post() {
		$this->_header[] = 'Content-Type: application/json; charset=utf-8';
		$this->_header[] = 'Content-Length: ' . strlen($this->_params);
		
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_CUSTOMREQUEST => 'POST',
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			// Post Data
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
		
		//echo $output;
		echo $this->fliter($output);
	}
	
	/**
	 * Send put method
	 */
	private function put() {
		$this->_header[] = 'Content-Type: application/json; charset=utf-8';
		$this->_header[] = 'Content-Length: ' . strlen($this->_params);
		
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_CUSTOMREQUEST => 'PUT',
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			// Post Data
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
		
		echo $this->fliter($output);
	}
	
	/**
	 * Send delete method
	 */
	private function delete() {
		$this->_header[] = 'Content-Type: application/json; charset=utf-8';
		$this->_header[] = 'Content-Length: ' . strlen($this->_params);
		
		// Set option
		curl_setopt_array($this->_client, array(
			// Host + Uri + Query String
			CURLOPT_URL => $this->_url,
			
			// HTTP Method
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			
			// User agent
			CURLOPT_USERAGENT => $this->_user_agent,
			
			// Post Data
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
		
		echo $this->fliter($output);
	}
}
