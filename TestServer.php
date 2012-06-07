<?php
echo "Server -      ";
print_r($_SERVER);
echo "\nGET -       ";
print_r($_GET);
echo "\nPOST -      ";
print_r($_POST);
echo "\nFILES -     ";
print_r($_FILES);
echo "\nStand I/O - ";
$input = file_get_contents('php://input');
print_r($input);
