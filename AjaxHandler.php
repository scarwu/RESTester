<?php
/**
 * RESTester Ajax Handler
 * 
 * @package		RESTful Server API Tester
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/RESTester
 */

define('DEBUG', TRUE);
 
if(DEBUG) {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}
else
	error_reporting(0);

// Load Service Caller
require_once 'ServiceCaller.php';

// Run Service Caller
$caller = new ServiceCaller(urldecode($_SERVER['QUERY_STRING']));
$caller->run();
