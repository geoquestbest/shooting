<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
list($width, $height) = getimagesize("uploads/FA8001/f2e8e0191cacc257961116b8259819f9.jpg");
include("ResizeImage.php");
$image = new ResizeImage();
$image->load("https://shootnbox.fr/uploads/images/d3f7ccf2e08d0d90cab784fa3f32e5b0.png");
var_dump($image->resize($width, $height, "y"));
$image->save("uploads/images/frame.png");
?>