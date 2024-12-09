<?php
@header('Content-Type: application/json; charset=utf-8');

if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) $_POST = json_decode(file_get_contents('php://input'), true);

@require_once("inc/mainfile.php");


foreach ($_POST as $key => $value){${$key} = mysqli_real_escape_string($conn, $value);}
foreach ($_GET as $key => $value){${$key} = mysqli_real_escape_string($conn, $value);}

$parametrs_arr = explode("&", $parametrs);
foreach($parametrs_arr as $parametr) {
  $parametr_arr = explode("=", $parametr);
  ${$parametr_arr['0']} = $parametr_arr[1];
}


  $frame = imagecreatefrompng("uploads/images/frame.png");
  var_dump($frame);
  imagesavealpha($frame, true);

   list($width, $height) = getimagesize("uploads/FA7930/877e924828842b0f653cc9be1f5d5a80.jpg");

  $img = imagecreatefromjpeg("uploads/FA7930/877e924828842b0f653cc9be1f5d5a80.jpg");
  imagecopy($img, $frame, 0, 0, 0, 0, $width, $height);
  imagejpeg($img, "uploads/FA7930/1.jpg");
  imagedestroy($img);
?>