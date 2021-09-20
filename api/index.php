<?php
//User will POST data to this location, which will then trigger some command(s)
include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "includes.php";
include_once(__DIR__ . DIRECTORY_SEPARATOR . "API.php");

$api = new API;

echo $api->request();