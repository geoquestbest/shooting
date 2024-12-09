<?php

// Create a image from url
//$im = imagecreatefromjpeg('1.jpg');


$frame = imagecreatefrompng("frame.png");
imagesavealpha($frame, true);

// Affine the image
//$newimage = imageaffine($im, [0.9022, 0, 0, 0.9022, -1.33, -306.67]);
//imagejpeg($newimage, '11.jpg');


$im2 = imagecreatefromjpeg('11.jpg');
list($width, $height, $type, $attr) = getimagesize('11.jpg');

$newImage2 = imagecreatetruecolor(2320, 3088);
imagealphablending($newImage2, false);
imagesavealpha($newImag2e, true);
$transparency = imagecolorallocatealpha($newImage2, 255, 255, 255, 127);
imagefilledrectangle($newImage2, 0, 0, 2320, 3088, $transparency);
imagecopyresized($newImage2, $im2, (2320 - 2094) / 2 - 1.33, -306.67, 0, 0, 2094, 2786, 2320, 3088);

//header('Content-Type: image/jpeg');
imagejpeg($newImage2, '22.jpg');


$im3 = imagecreatefromjpeg('22.jpg');

imagecopy($im3, $frame, 0, 0, 0, 0, 2320, 3088);


header('Content-Type: image/jpeg');
imagejpeg($im3);

?>