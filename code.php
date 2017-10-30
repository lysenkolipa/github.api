<?php
require_once 'api.php';


$post = $_POST;
$data = new API($post);
$url = $data->getURL($post);
$json=$data->getJSON($url);

//$json = json_decode( $json);

print_r( $json );






