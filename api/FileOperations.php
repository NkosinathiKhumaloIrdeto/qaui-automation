<?php
header("Access-Control-Allow-Origin: *");
$from_path =  $_GET['from_path'];
$to_path = $_GET['to_path'];

header("HTTP/1.1 200 OK");

$files = array(
    "from"=>$from_path,
        "to"=>$to_path);

copy($from_path, $to_path);

echo json_encode(
    array(
        "status"=>"File copied successfully",
        "Files"=>$files
        ));