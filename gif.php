<?php
@require_once("inc/mainfile.php");

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = 4001");
$row_orders = mysqli_fetch_assoc($result_orders);
$data = json_decode(base64_decode($row_orders['data']), true);

$dig_arr = explode(",", $data['objects'][0]['matrix']);
$cc = 1080 / $data['objects'][0]['height'];

$frame = imagecreatefrompng("uploads/images/frame.png");
imagesavealpha($frame, true);

$img = imagecreatefromjpeg("uploads/FA2000/1.jpg");

$im = imagecreatefromjpeg("uploads/FA2000/1.jpg");
imagecolorallocate($im, 255, 255, 255);

imagecopy($im, $img, 0, $dig_arr[5]*$cc, 0, 0, 812, 1080);

imagecopy($im, $frame, 0, 0, 0, 0, 812, 1080);

imagejpeg($im, "gif.jpg");

imagedestroy($img);

echo "done";
?>