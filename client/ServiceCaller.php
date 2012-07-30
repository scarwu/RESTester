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
	private $_raw;
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
		$this->_raw = isset($info['raw']) ? $info['raw'] : FALSE;
		
		$this->_user_agent = 'RESTester/Rev.4';
		$this->_client = curl_init();
		
		if(count($this->_header) > 0) {
			foreach($this->_header as $key => $value)
				$header[] = $key . ': ' . $value;
			$this->_header = $header;
		}
		
		// User agent
		curl_setopt($this->_client, CURLOPT_USERAGENT, $this->_user_agent);
		
		// HTTP Header
		curl_setopt($this->_client, CURLOPT_HTTPHEADER, $this->_header);
		
		// Result include header
		curl_setopt($this->_client, CURLOPT_HEADER, 1);
		
		curl_setopt($this->_client, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_client, CURLOPT_FOLLOWLOCATION, true);
	}
	
	/**
	 * Run
	 */
	public function run() {
		$output = '';
		
		switch(strtolower($this->_method)) {
			case 'get':
				$output = $this->get();
				break;
			case 'post':
				$output = $this->post();
				break;
			case 'put':
				$output = $this->put();
				break;
			case 'delete':
				$output = $this->delete();
				break;
			default:
				header('HTTP/1.1 404 Not Found');
		}
		
		curl_close($this->_client);
		
		if(TRUE == $this->_raw)
			echo $output;
		else
			echo $this->fliter($output);
	}
	
	private function fliter($response) {
		$regex = '/((?:.|\n)+)\r\n\r\n({.+})?$/';
		
		if(preg_match($regex, $response, $match)) {
			if(isset($match[2])) {
				if(NULL == ($json = json_decode(trim($match[2]), TRUE)))
					$json = trim($match[2]);
	
				return json_encode(array(
					'header' => trim($match[1]),
					'json' => $json
				));
			}
			else
				return json_encode(array('header' => trim($match[1])));
		}
		else
			return json_encode(array('header' => trim($response)));
	}
	
	/**
	 * Send get method
	 */
	private function get() {
		$this->_url .= NULL !== $this->_params ? '?' . $this->_params : '';
		
		// Host + Uri + Query String
		curl_setopt($this->_client, CURLOPT_URL, $this->_url);
		
		// HTTP Method
		curl_setopt($this->_client, CURLOPT_CUSTOMREQUEST, 'GET');
		
		return curl_exec($this->_client);
	}
	
	/**
	 * Send post method
	 */
	private function post() {
		// Host + Uri + Query String
		curl_setopt($this->_client, CURLOPT_URL, $this->_url);
		
		// HTTP Method
		curl_setopt($this->_client, CURLOPT_CUSTOMREQUEST, 'POST');

		if(NULL === $this->_file) {
			$this->_header[] = 'Content-Type: application/json; charset=utf-8';
			$this->_header[] = 'Content-Length: ' . strlen($this->_params);
			
			// Post Data
			curl_setopt($this->_client, CURLOPT_POSTFIELDS, $this->_params);
		}
		else {
			// Post Data
			if(NULL != $this->_params)
				curl_setopt($this->_client, CURLOPT_POSTFIELDS, array('json' => $this->_params, 'file' => '@'.$this->_file));
			else
				curl_setopt($this->_client, CURLOPT_POSTFIELDS, array('file' => '@'.$this->_file));
				
			// File path
			// curl_setopt($this->_client, CURLOPT_INFILE, fopen($this->_file, 'r'));
				
			// File size
			// curl_setopt($this->_client, CURLOPT_INFILESIZE, filesize($this->_file));
			
			curl_setopt($this->_client, CURLOPT_BINARYTRANSFER, true);
		}
		
		return curl_exec($this->_client);
	}
	
	/**
	 * Send put method
	 */
	private function put() {
		// Host + Uri + Query String
		curl_setopt($this->_client, CURLOPT_URL, $this->_url);
		
		// HTTP Method
		curl_setopt($this->_client, CURLOPT_CUSTOMREQUEST, 'PUT');
		
		if(NULL === $this->_file) {
			$this->_header[] = 'Content-Type: application/json; charset=utf-8';
			$this->_header[] = 'Content-Length: ' . strlen($this->_params);

			// Post Data
			curl_setopt($this->_client, CURLOPT_POSTFIELDS, $this->_params);
		}
		else {
			// Post Data
			if(NULL != $this->_params)
				curl_setopt($this->_client, CURLOPT_POSTFIELDS, array('json' => $this->_params, 'file' => '@'.$this->_file));
			else
				curl_setopt($this->_client, CURLOPT_POSTFIELDS, array('file' => '@'.$this->_file));
			
			// File path
			// curl_setopt($this->_client, CURLOPT_INFILE, fopen($this->_file, 'r'));
				
			// File size
			// curl_setopt($this->_client, CURLOPT_INFILESIZE, filesize($this->_file));
			
			curl_setopt($this->_client, CURLOPT_BINARYTRANSFER, true);
		}
		
		return curl_exec($this->_client);
	}
	
	/**
	 * Send delete method
	 */
	private function delete() {
		$this->_header[] = 'Content-Type: application/json; charset=utf-8';
		$this->_header[] = 'Content-Length: ' . strlen($this->_params);
		
		// Host + Uri + Query String
		curl_setopt($this->_client, CURLOPT_URL, $this->_url);
		
		// HTTP Method
		curl_setopt($this->_client, CURLOPT_CUSTOMREQUEST, 'DELETE');
		
		// Post Data
		curl_setopt($this->_client, CURLOPT_POSTFIELDS, $this->_params);
		
		return curl_exec($this->_client);
	}
}
