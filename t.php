<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
//$result = json_decode(file_get_contents("http://141.94.254.113:9000/make_boom?link=https://shootnbox.fr/uploads/DE2222/9a663435486719a801f884f61d1f1209.mp4"), true);
/*include("ResizeImage.php");
$image = new ResizeImage();
$image->load("uploads/images/2f495f08992aac66e9369ddfcb666842.png");
$image->resize(1080, 1950);
$image->save("uploads/images/frame.png");
$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".str_replace("==", "", base64_encode('-i https://shootnbox.fr/uploads/images/frame.png -filter_complex "overlay=0:0"'))."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/DE2222/9a663435486719a801f884f61d1f1209.mp4"), true);
var_dump($result);
var_dump(copy($result['path'], "uploads/images/video.mp4"));*/
//$result = json_decode(file_get_contents("http://141.94.254.113:9000/make_boom?token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/images/video.mp4&speed=1"), true);
//$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff2?param=&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/FA7428/1aac18ce2ea48422dae86d0e33f9e452.mov&pts=0.25"), true);
//echo trim(base64_encode('-filter_complex "[0:v]colorchannelmixer=.393:.769:.189:0:.349:.686:.168:0:.272:.534:.131[colorchannelmixed];[colorchannelmixed]eq=1.0:0:1.3:2.4:1.0:1.0:1.0:1.0[color_effect]" -map [color_effect] -c:v libx264 -c:a'), "=");

//$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf scale=1440:1920'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/7eed05216b96e3b374438dec58ef05ba.mp4"), true);
//var_dump($result);
//var_dump(copy($result['path'], "video_nn.mp4"));
//var_dump(copy($result['Path:'], "new_video.mp4"));
//var_dump(file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']));

@require_once("inc/mainfile.php");
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = 294");
$row_orders = mysqli_fetch_assoc($result_orders);
$data = json_decode(base64_decode($row_orders['data']), true);
echo"<pre>";
print_r($data);
echo"</pre>";
echo $data['objects'][0]['width'];

/*$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf hflip'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/input.mov"), true);
copy($result['path'], "uploads/".$row_orders['num_id']."/input.mov");
var_dump(file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']));*/


$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf scale=1440:1920'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/input.mov"), true);
copy($result['path'], "uploads/".$row_orders['num_id']."/input1.mov");
var_dump(file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']));

$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf "hflip, scale='.$data['objects'][0]['width'].':1920"'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/input.mov"), true);
copy($result['path'], "uploads/".$row_orders['num_id']."/input2.mov");
var_dump(file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']));

$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-i https://shootnbox.fr/uploads/'.$row_orders['num_id'].'/input2.mov  -i https://shootnbox.fr/uploads/images/frame.png -filter_complex "overlay = '.((1440 - $data['objects'][0]['width']) / 2).':0, overlay = 0:0"'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/input1.mov"), true);
var_dump($result);
var_dump(copy($result['path'], "video_nn2.mp4"));
var_dump(file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']));

$result = json_decode(file_get_contents("http://141.94.254.113:9000/make_boom?token=ksadjaslkdjqww871&link=https://shootnbox.fr/video_nn2.mp4&speed=1"), true);
var_dump($result);
copy($result['Path:'],  "video_nn2.mp4");
file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['Path:']);

/*$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".str_replace("==", "", base64_encode('-i https://shootnbox.fr/uploads/images/frame.png -filter_complex "overlay=0:0"'))."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/video_nn2.mp4"), true);
copy($result['path'],  "video_nn2.mp4");
file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);*/
?>