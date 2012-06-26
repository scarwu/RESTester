<?php
print_r($_SERVER);

echo "\n-- Header --\n";
print_r(getallheaders());

foreach($_SERVER as $key => $value)
	if(preg_match('/^HTTP_(.+)/', $key, $match)) {
		$names = explode('_', $match[1]);
		$index = ucfirst(strtolower(array_shift($names)));
		if(count($names))
			foreach($names as $segments)
				$index .= '-' . ucfirst(strtolower($segments));
		$_header[$index] = $value;
	}
$_header['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
$_header['Content-Type'] = $_SERVER['CONTENT_TYPE'];

print_r($_header);

if(NULL !== ($handle = fopen("php://input", "r"))) {
	echo "\n-- Stand I/O --\n";
	while($data = fread($handle, 1024))
		echo $data;
	fclose($handle);
}

if(0 !== count($_GET)) {
	echo "\n-- GET --\n";
	print_r($_GET);
}

if(0 !== count($_POST)) {
	echo "\n-- POST --\n";
	print_r($_POST);
}

if(0 !== count($_FILES)) {
	echo "\n-- FILES --\n";
	print_r($_FILES);
}
