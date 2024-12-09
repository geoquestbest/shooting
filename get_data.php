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

if (isset($parametrs)) {
  $parametrs_arr = explode("&", $parametrs);
  foreach($parametrs_arr as $parametr) {
    $parametr_arr = explode("=", $parametr);
    ${$parametr_arr['0']} = $parametr_arr[1];
  }
}

ob_start();

print_r($_GET);
print_r($_POST);
print_r($_FILES);

 //echo file_get_contents('php://input');

file_put_contents(time().'video.txt', ob_get_clean());


/********** Аккаунт **********/
if ($method == 'data') {

  if ($action == "qr_code") {
    if (isset($order_id)) {
      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$order_id);
      if (mysqli_num_rows($result_orders) == 0) {
        header("HTTP/1.0 404 Bad Request");
        $result['error'] = array('title' => 'Order not found!', 'err_code' => 1);
      } else {
        $row_orders = mysqli_fetch_assoc($result_orders);
        $result['result'] = array("qr_code" => $row_orders['qr']);
      }
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }

  }

  if ($action == "set_personal_data") {
    if (isset($order_id)) {
      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$order_id);
      if (mysqli_num_rows($result_orders) == 0) {
        header("HTTP/1.0 404 Bad Request");
        $result['error'] = array('title' => 'Order not found!', 'err_code' => 1);
      } else {
        mysqli_query($conn, "UPDATE `orders_new` SET `personal_data` = '$personal_data' WHERE `id` =".$order_id);
        $result['result'] = array("status" => 'updated');
      }
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }

  }

  if ($action == "send") {
    //if (isset($order_id) && ((isset($_FILES['image1']) && isset($_FILES['image2']) && isset($_FILES['image3']) && isset($_FILES['image4'])) || isset($_FILES['image']) || isset($_FILES['video']))) {

    if (isset($order_id)) {

      if (isset($hash) && $hash != "") {
        $exist = true;
        while ($exist) {
          $short = preg_replace( "/[^a-zA-Z\s]/", '', base64_encode(random_bytes(4)));
          $result_short_links = mysqli_query($conn, "SELECT `id` FROM `short_links` WHERE `short` = '$short'");
          $exist = mysqli_num_rows($result_short_links) != 0;
        }
        mysqli_query($conn, "INSERT INTO `short_links`(`id`, `order_id`, `hash`, `short`, `url`, `status`) VALUES (NULL, '$order_id', '$hash', '$short', '', '0')");
      }

      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$order_id);
      $row_orders = mysqli_fetch_assoc($result_orders);

      $data = json_decode(base64_decode($row_orders['data']), true);

      $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
      if (mysqli_num_rows($result_configure_orders) == 0) {
        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182");
      }
      $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);

      if (!file_exists("uploads/".$row_orders['num_id'])) {
        mkdir("uploads/".$row_orders['num_id'], 0777, true);
      }

      $frames = array();

      if (isset($image_type) && $image_type == "base64_image") {
        $file_name = md5(time()).".jpg";
        if (base64_decode(file_get_contents('php://input')) != "") {
          file_put_contents("image64.txt", file_get_contents('php://input'));
          file_put_contents("uploads/".$row_orders['num_id']."/".$file_name, base64_decode(file_get_contents('php://input')));
        }
      }

      if (strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false) {
        $result_orders2 = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = 4182");
        $row_orders2 = mysqli_fetch_assoc($result_orders2);
        $data = json_decode(base64_decode($row_orders2['data']), true);
        $template = $row_orders['image_vegas'] != "" ? $row_orders['image_vegas'] : $row_orders2['image'];
        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182 ORDER BY `id` DESC");
        $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
      } else {
        $template = $row_orders['image'];
      }

      if(isset($_FILES['image1']) && $_FILES['image1']['error'] == 0){
        $file = pathinfo($_FILES['image1']['name']);
        $file_name1 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image1']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name1);

        //list($width, $height) = getimagesize("uploads/images/".$row_orders['image']);

        include("ResizeImage.php");
        $image = new ResizeImage();
        $image->load("uploads/images/".$template);
        $image->resize(1080, 1620, "y");
        $image->save("uploads/images/frame.png");

        $dig_arr = explode(",", $data['objects'][0]['matrix']);
        $cc = 1080 / $data['objects'][0]['height'];

        ob_start();

        $frame = imagecreatefrompng("uploads/images/frame.png");
        var_dump($frame);
        imagesavealpha($frame, true);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name1);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);


        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name1);
        imagedestroy($img);
        imagedestroy($im);

        file_put_contents('gif.txt', ob_get_clean());

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name1;
      }
      if(isset($_FILES['image2']) && $_FILES['image2']['error'] == 0){
        $file = pathinfo($_FILES['image2']['name']);
        $file_name2 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image2']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name2);

        //$image->load("uploads/".$row_orders['num_id']."/".$file_name2);
        //$image->resize($width, $height, "y");
        //$image->save("uploads/".$row_orders['num_id']."/".$file_name2);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name2);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);

        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name2);
        imagedestroy($img);
        imagedestroy($im);

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name2;
      }
      if(isset($_FILES['image3']) && $_FILES['image3']['error'] == 0){
        $file = pathinfo($_FILES['image3']['name']);
        $file_name3 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image3']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name3);

        //$image->load("uploads/".$row_orders['num_id']."/".$file_name3);
        //$image->resize($width, $height, "y");
        //$image->save("uploads/".$row_orders['num_id']."/".$file_name3);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name3);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);

        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name3);
        imagedestroy($img);
        imagedestroy($im);

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name3;
      }
      if(isset($_FILES['image4']) && $_FILES['image4']['error'] == 0){
        $file = pathinfo($_FILES['image4']['name']);
        $file_name4 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image4']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name4);

        //$image->load("uploads/".$row_orders['num_id']."/".$file_name4);
        //$image->resize($width, $height, "y");
        //$image->save("uploads/".$row_orders['num_id']."/".$file_name4);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name4);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);

        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name4);
        imagedestroy($img);
        imagedestroy($im);

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name4;

        imagedestroy($frame);
      }

      if (count($frames) == 4) {
        require "AnimGif.php";
        //$durations = array(20, 30, 10, 10);
        $durations = array(number_format($row_configure_orders['gif_speed']/11, 2, '.', ''), number_format($row_configure_orders['gif_speed']/11, 2, '.', ''), number_format($row_configure_orders['gif_speed']/11, 2, '.', ''), number_format($row_configure_orders['gif_speed']/11, 2, '.', ''));
        $anim = new GifCreator\AnimGif();
        $anim->create($frames, $durations);
        $file_name = md5(time()."gif").".gif";
        $anim->save("uploads/".$row_orders['num_id']."/".$file_name);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name1);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name2);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name3);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name4);
      }

      if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = pathinfo($_FILES['image']['name']);
        $file_name = md5(time()).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name);
      }

      if(isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $file = pathinfo($_FILES['video']['name']);
        $file_name = md5(time()).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['video']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name);

        file_put_contents('worker.ini', $order_id."::".$file_name."::ipad::".(isset($hash) ? $hash : '')."::".(isset($email) ? $email : '')."::".(isset($sms) ? $sms : ''));
        exec("php /var/www/ftp.shootnbox.fr/data/www/ftp.shootnbox.fr/worker.php > /dev/null &");

      } else {
        if (isset($_FILES['video']) && $_FILES['video']['error'] != 0) {
          ob_start();
          echo"Error: ".$_FILES['video']['error'];
          file_put_contents('video2.txt', ob_get_clean());
        }
      }

      /*if (!isset($hash) || $hash == "") {
        $hash = randomPassword();
        $result_short_links = mysqli_query($conn, "SELECT `url` FROM `short_links` WHERE `hash` = '$hash'");
        while(mysqli_num_rows($result_short_links) > 0) {
          $hash = randomPassword();
          $result_short_links = mysqli_query($conn, "SELECT `url` FROM `short_links` WHERE `hash` = '$hash'");
        }
        $url = $row_orders['num_id'].'/'.$file_name;
        mysqli_query($conn, "INSERT INTO `short_links`(`id`, `hash`, `url`, `status`) VALUES (NULL, '$hash', '$url', '0')");
      } else {
        $result_short_links = mysqli_query($conn, "SELECT `url` FROM `short_links` WHERE `hash` = '$hash'");
        if (mysqli_num_rows($result_short_links) != 0) {
          $row_short_links = mysqli_fetch_assoc($result_short_links);
          $file_name = str_replace($row_orders['num_id']."/", "", $row_short_links['url']);
        } else {
          header("HTTP/1.0 400 Bad Request");
          $result['error'] = array('title' => 'Hash not found!', 'err_code' => 3);
        }
      }*/

      if (isset($hash) && $hash != "") {
        $url = $row_orders['num_id'].'/'.$file_name;
        mysqli_query($conn, "UPDATE `short_links` SET `url` = '$url', `status` = 1 WHERE `hash` LIKE '$hash'");
      }

      if (isset($qr)) {
        $qr_code = 'https://ftp.shootnbox.fr/qr.php?url=https://ftp.shootnbox.fr/view/?hash='.$hash;
      } else {
        $qr_code = "";
      }

      /*if (isset($email) && $email != "") {
        $subject = $row_configure_orders['email_subject']." ".date("d-m-Y H:i", time());
        $mailheaders = "MIME-Version: 1.0\r\n";
        $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
        $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <". $row_configure_orders['email_from'].">\r\n";
        $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
        $msg = '<!DOCTYPE html>
          <head>
            <meta charset="UTF-8">
            <meta content="width=device-width, initial-scale=1" name="viewport">
            <meta name="x-apple-disable-message-reformatting">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="telephone=no" name="format-detection">
            <title>'.$row_configure_orders['email_subject'].'</title>
            <style type="text/css">
              font-family: \'Arial\';
              font-size: 11px;
            </style>
          </head>
          <body>
            '.nl2br(str_replace('[medialink]', '<a href="https://ftp.shootnbox.fr/view/?hash='.$hash.'" taget="blank">https://ftp.shootnbox.fr/view/?hash='.$hash.'</a>', $row_configure_orders['email_text'])).'
          </body>
        </html>';
        mail($email, "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);

        mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '$order_id', '1', '$email')");

      }

      if (isset($sms) && $sms != "") {

        ob_start();
        // CapitoleMobile POST URL
        $postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";
        //Structure de Données XML
        $xmlString = '<SMS>
          <authentification>
            <username>Shootnbox</username>
            <password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
          </authentification>
          <message>
            <text>'.str_replace('[medialink]', '%0Ahttps://ftp.shootnbox.fr/view/?hash='.$hash.'%0A', $row_configure_orders['sms_text']).'</text>
            <long>yes</long>
            <sender>ShootnBox</sender>
          </message>
          <recipients>
            <gsm>'.preg_replace("/[^0-9\s]/", '', $sms).'</gsm>
          </recipients>
        </SMS>';
        // insertion du nom de la variable POST "XML" avant les données au format XML
        $fields = "XML=" . urlencode($xmlString);
        // dans cet exemple, la requête POST est realisée grâce à la librairie Curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        // Réponse de la requête POST
        $response = curl_exec($ch);
        curl_close($ch);
        ob_get_clean();

        mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '$order_id', '2', '$sms')");

      }*/

      $result['result'] = array("status" => "done", "qr_code" => $qr_code, "hash" => $hash);

    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }

  if ($action == "send_desktop") {
    //if (isset($order_id) && ((isset($_FILES['image1']) && isset($_FILES['image2']) && isset($_FILES['image3']) && isset($_FILES['image4'])) || isset($_FILES['image']) || isset($_FILES['video']))) {

    if (isset($order_id)) {

      if (isset($hash) && $hash != "") {
        $exist = true;
        while ($exist) {
          $short = preg_replace( "/[^a-zA-Z\s]/", '', base64_encode(random_bytes(4)));
          $result_short_links = mysqli_query($conn, "SELECT `id` FROM `short_links` WHERE `short` = '$short'");
          $exist = mysqli_num_rows($result_short_links) != 0;
        }
        mysqli_query($conn, "INSERT INTO `short_links`(`id`, `order_id`, `hash`, `short`, `url`, `status`) VALUES (NULL, '$order_id', '$hash', '$short', '', '0')");
      }

      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$order_id);
      $row_orders = mysqli_fetch_assoc($result_orders);

      $data = json_decode(base64_decode($row_orders['data']), true);

      $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
      if (mysqli_num_rows($result_configure_orders) == 0) {
        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182");
      }
      $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);

      if (!file_exists("uploads/".$row_orders['num_id'])) {
        mkdir("uploads/".$row_orders['num_id'], 0777, true);
      }

      $frames = array();

      if (isset($image_type) && $image_type == "base64_image") {
        $file_name = md5(time()).".jpg";
        if (base64_decode(file_get_contents('php://input')) != "") {
          file_put_contents("uploads/".$row_orders['num_id']."/".$file_name, base64_decode(file_get_contents('php://input')));
        }
      }

      if(isset($_FILES['image1']) && $_FILES['image1']['error'] == 0){
        $file = pathinfo($_FILES['image1']['name']);
        $file_name1 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image1']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name1);

        //list($width, $height) = getimagesize("uploads/images/".$row_orders['image']);

        include("ResizeImage.php");
        $image = new ResizeImage();
        $image->load("uploads/images/".$row_orders['image']);
        $image->resize(1080, 1620, "y");
        $image->save("uploads/images/frame.png");

        $dig_arr = explode(",", $data['objects'][0]['matrix']);
        $cc = 1080 / $data['objects'][0]['height'];

        ob_start();

        $frame = imagecreatefrompng("uploads/images/frame.png");
        var_dump($frame);
        imagesavealpha($frame, true);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name1);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);
        var_dump($img);



        echo imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        echo imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name1);
        imagedestroy($img);
        imagedestroy($im);

        file_put_contents('gif.txt', ob_get_clean());

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name1;
      }
      if(isset($_FILES['image2']) && $_FILES['image2']['error'] == 0){
        $file = pathinfo($_FILES['image2']['name']);
        $file_name2 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image2']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name2);

        //$image->load("uploads/".$row_orders['num_id']."/".$file_name2);
        //$image->resize($width, $height, "y");
        //$image->save("uploads/".$row_orders['num_id']."/".$file_name2);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name2);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);

        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name2);
        imagedestroy($img);
        imagedestroy($im);

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name2;
      }
      if(isset($_FILES['image3']) && $_FILES['image3']['error'] == 0){
        $file = pathinfo($_FILES['image3']['name']);
        $file_name3 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image3']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name3);

        //$image->load("uploads/".$row_orders['num_id']."/".$file_name3);
        //$image->resize($width, $height, "y");
        //$image->save("uploads/".$row_orders['num_id']."/".$file_name3);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name3);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);

        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name3);
        imagedestroy($img);
        imagedestroy($im);

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name3;
      }
      if(isset($_FILES['image4']) && $_FILES['image4']['error'] == 0){
        $file = pathinfo($_FILES['image4']['name']);
        $file_name4 = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image4']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name4);

        //$image->load("uploads/".$row_orders['num_id']."/".$file_name4);
        //$image->resize($width, $height, "y");
        //$image->save("uploads/".$row_orders['num_id']."/".$file_name4);

        $img = $im = imagecreatefromjpeg("uploads/".$row_orders['num_id']."/".$file_name4);
        imagecopy($img, $im, 0, $dig_arr[5]*$cc, 0, 0, 1080, 1620);

        imagecopy($img, $frame, 0, 0, 0, 0, 1080, 1620);
        imagejpeg($img, "uploads/".$row_orders['num_id']."/".$file_name4);
        imagedestroy($img);
        imagedestroy($im);

        $frames[] = "uploads/".$row_orders['num_id']."/".$file_name4;

        imagedestroy($frame);
      }

      if (count($frames) == 4) {
        require "AnimGif.php";
        //$durations = array(20, 30, 10, 10);
        $durations = array(number_format($row_configure_orders['gif_speed']/11, 2, '.', ''), number_format($row_configure_orders['gif_speed']/11, 2, '.', ''), number_format($row_configure_orders['gif_speed']/11, 2, '.', ''), number_format($row_configure_orders['gif_speed']/11, 2, '.', ''));
        $anim = new GifCreator\AnimGif();
        $anim->create($frames, $durations);
        $file_name = md5(time()."gif").".gif";
        $anim->save("uploads/".$row_orders['num_id']."/".$file_name);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name1);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name2);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name3);
        @unlink("uploads/".$row_orders['num_id']."/".$file_name4);
      }

      if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = pathinfo($_FILES['image']['name']);
        $file_name = md5(time()).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name);
      }

      if(isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $file = pathinfo($_FILES['video']['name']);
        $file_name = md5(time()).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['video']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name);

        file_put_contents('worker.ini', $order_id."::".$file_name."::desktop");
        exec("php /var/www/ftp.shootnbox.fr/data/www/ftp.shootnbox.fr/worker.php > /dev/null &");

      } else {
        if (isset($_FILES['video']) && $_FILES['video']['error'] != 0) {
          ob_start();
          echo"Error: ".$_FILES['video']['error'];
          file_put_contents('video2.txt', ob_get_clean());
        }
      }

      if (!isset($hash) || $hash == "") {
        $hash = randomPassword();
        $result_short_links = mysqli_query($conn, "SELECT `url` FROM `short_links` WHERE `hash` = '$hash'");
        while(mysqli_num_rows($result_short_links) > 0) {
          $hash = randomPassword();
          $result_short_links = mysqli_query($conn, "SELECT `url` FROM `short_links` WHERE `hash` = '$hash'");
        }
        $url = $row_orders['num_id'].'/'.$file_name;
        //mysqli_query($conn, "INSERT INTO `short_links`(`id`, `hash`, `url`, `status`) VALUES (NULL, '$hash', '$url', '0')");
        mysqli_query($conn, "INSERT INTO `short_links`(`id`, `order_id`, `hash`, `short`, `url`, `status`) VALUES (NULL, '$order_id', '$hash', '$hash', '$url', '0')");
      } else {
        $result_short_links = mysqli_query($conn, "SELECT `url` FROM `short_links` WHERE `hash` = '$hash'");
        if (mysqli_num_rows($result_short_links) != 0) {
          $row_short_links = mysqli_fetch_assoc($result_short_links);
          $file_name = str_replace($row_orders['num_id']."/", "", $row_short_links['url']);
        } else {
          header("HTTP/1.0 400 Bad Request");
          $result['error'] = array('title' => 'Hash not found!', 'err_code' => 3);
        }
      }

      if (isset($hash) && $hash != "") {
        $url = $row_orders['num_id'].'/'.$file_name;
        mysqli_query($conn, "UPDATE `short_links` SET `url` = '$url', `status` = 1 WHERE `hash` LIKE '$hash'");
      }

      if (isset($qr)) {
        $qr_code = 'https://ftp.shootnbox.fr/qr.php?url=https://ftp.shootnbox.fr/view/?hash='.$hash;
      } else {
        $qr_code = "";
      }


      if (isset($email) && $email != "") {
        $subject = $row_configure_orders['email_subject']." ".date("d-m-Y H:i", time());
        $mailheaders = "MIME-Version: 1.0\r\n";
        $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
        $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <". $row_configure_orders['email_from'].">\r\n";
        $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
        $msg = '<!DOCTYPE html>
          <head>
            <meta charset="UTF-8">
            <meta content="width=device-width, initial-scale=1" name="viewport">
            <meta name="x-apple-disable-message-reformatting">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="telephone=no" name="format-detection">
            <title>'.$row_configure_orders['email_subject'].'</title>
            <style type="text/css">
              font-family: \'Arial\';
              font-size: 11px;
            </style>
          </head>
          <body>
            '.nl2br(str_replace('[medialink]', '<a href="https://ftp.shootnbox.fr/view/?hash='.$hash.'" taget="blank">https://ftp.shootnbox.fr/view/?hash='.$hash.'</a>', $row_configure_orders['email_text'])).'
          </body>
        </html>';
        mail($email, "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);

        mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '$order_id', '1', '$email')");

      }

      if (isset($sms) && $sms != "") {

        ob_start();
        // CapitoleMobile POST URL
        $postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";
        //Structure de Données XML
        $xmlString = '<SMS>
          <authentification>
            <username>Shootnbox</username>
            <password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
          </authentification>
          <message>
            <text>'.str_replace('[medialink]', '%0Ahttps://ftp.shootnbox.fr/view/?hash='.$hash.'%0A', $row_configure_orders['sms_text']).'</text>
            <long>yes</long>
            <sender>ShootnBox</sender>
          </message>
          <recipients>
            <gsm>'.preg_replace("/[^0-9\s]/", '', $sms).'</gsm>
          </recipients>
        </SMS>';
        // insertion du nom de la variable POST "XML" avant les données au format XML
        $fields = "XML=" . urlencode($xmlString);
        // dans cet exemple, la requête POST est realisée grâce à la librairie Curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        // Réponse de la requête POST
        $response = curl_exec($ch);
        curl_close($ch);
        ob_get_clean();

        mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '$order_id', '2', '$sms')");

      }

      $result['result'] = array("status" => "done", "qr_code" => $qr_code, "hash" => $hash);

    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }

  if ($action == "arch") {

    if (isset($order_id)) {

      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$order_id);
      $row_orders = mysqli_fetch_assoc($result_orders);

      if (!file_exists("uploads/".$row_orders['num_id'])) {
        mkdir("uploads/".$row_orders['num_id'], 0777, true);
      }

      if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
        $file = pathinfo($_FILES['file']['name']);
        $file_name = md5(time().$file['basename']).".".strtolower($file['extension']);
        move_uploaded_file($_FILES['file']['tmp_name'], "uploads/".$row_orders['num_id']."/".$file_name);
        include_once("manager/pclzip.lib.php");
        $archive = new PclZip("uploads/".$row_orders['num_id']."/".$file_name);
        $result['result'] = array("status" => "done", "result" => $archive->extract(PCLZIP_OPT_PATH, "uploads/".$row_orders['num_id']));
        $archive->delete();
      } else {
        header("HTTP/1.0 400 Bad Request");
        $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
      }
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }

  if ($action == "extract") {
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
      $file = pathinfo($_FILES['file']['name']);
      $file_name = md5(time().$file['basename']).".".strtolower($file['extension']);
      move_uploaded_file($_FILES['file']['tmp_name'], "uploads/".$file_name);
      include_once("manager/pclzip.lib.php");
      $archive = new PclZip("uploads/".$file_name);
      $result['result'] = array("status" => "done", "files" => $archive->extract(PCLZIP_OPT_PATH, "uploads/"));
      $archive->delete();
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }

  if ($action == "retry") {
    if (isset($hash)) {
      $result_short_links = mysqli_query($conn, "SELECT * FROM `short_links` WHERE `hash` LIKE '$hash'");
      if (mysqli_num_rows($result_short_links) != 0) {
        /*$row_short_links = mysqli_fetch_assoc($result_short_links);

        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$row_short_links['order_id']);
        $row_orders = mysqli_fetch_assoc($result_orders);

        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
        if (mysqli_num_rows($result_configure_orders) == 0) {
          $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182");
        }
        $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);*/

        if (isset($qr)) {
          $qr_code = 'https://ftp.shootnbox.fr/qr.php?url=https://ftp.shootnbox.fr/view/?hash='.$hash;
        } else {
          $qr_code = "";
        }

        //sleep(45);

       /* if (isset($email) && $email != "") {
          $subject = $row_configure_orders['email_subject']." ".date("d-m-Y H:i", time());
          $mailheaders = "MIME-Version: 1.0\r\n";
          $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
          $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <". $row_configure_orders['email_from'].">\r\n";
          $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
          $msg = '<!DOCTYPE html>
            <head>
              <meta charset="UTF-8">
              <meta content="width=device-width, initial-scale=1" name="viewport">
              <meta name="x-apple-disable-message-reformatting">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta content="telephone=no" name="format-detection">
              <title>'.$row_configure_orders['email_subject'].'</title>
              <style type="text/css">
                font-family: \'Arial\';
                font-size: 11px;
              </style>
            </head>
            <body>
              '.nl2br(str_replace('[medialink]', '<a href="https://ftp.shootnbox.fr/view/?hash='.$hash.'" taget="blank">https://ftp.shootnbox.fr/view/?hash='.$hash.'</a>', $row_configure_orders['email_text'])).'
            </body>
          </html>';
          mail($email, "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);

          mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '".$row_orders['id']."', '1', '$email')");
        }

        if (isset($sms) && $sms != "") {

          ob_start();
          // CapitoleMobile POST URL
          $postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";
          //Structure de Données XML
          $xmlString = '<SMS>
            <authentification>
              <username>Shootnbox</username>
              <password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
            </authentification>
            <message>
              <text>'.str_replace('[medialink]', '%0Ahttps://ftp.shootnbox.fr/view/?hash='.$hash.'%0A', $row_configure_orders['sms_text']).'</text>
              <long>yes</long>
              <sender>ShootnBox</sender>
            </message>
            <recipients>
              <gsm>'.preg_replace("/[^0-9\s]/", '', $sms).'</gsm>
            </recipients>
          </SMS>';
          // insertion du nom de la variable POST "XML" avant les données au format XML
          $fields = "XML=" . urlencode($xmlString);
          // dans cet exemple, la requête POST est realisée grâce à la librairie Curl
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $postUrl);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
          // Réponse de la requête POST
          $response = curl_exec($ch);
          curl_close($ch);
          echo $response;
          file_put_contents('sms.txt', ob_get_clean());

          mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '".$row_orders['id']."', '2', '$sms')");

        }*/

        file_put_contents('msg_worker.ini', (isset($hash) ? $hash : '')."::".(isset($email) ? $email : '')."::".(isset($sms) ? $sms : ''));
        exec("php /var/www/ftp.shootnbox.fr/data/www/ftp.shootnbox.fr/msg_worker.php > /dev/null &");

        $result['result'] = array("status" => "done", "qr_code" => $qr_code, "hash" => $hash);
      } else {
        header("HTTP/1.0 400 Bad Request");
        $result['error'] = array('title' => 'Hash not found!', 'err_code' => 3);
      }
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }
}

if ($method == 'ipad') {

  if ($action == "list") {

    //$rq = " WHERE `box_type` LIKE '%ring%'";
    $rq = " WHERE (`box_type` LIKE '%ring%' OR `box_type` LIKE '%vegas%') AND `status` = 2";

    $orders = array();
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` ".$rq);
    while($row_orders = mysqli_fetch_assoc($result_orders)) {
      $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
      if (((strpos(mb_strtolower($row_orders['box_type']), 'ring') !== false && mysqli_num_rows($result_configure_orders) > 0 && $row_orders['image'] != '' && $row_orders['data'] != '') || strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false) && strtotime($row_orders['event_date']) >= strtotime(date("d.m.Y", time())."00:00:00") && strtotime($row_orders['event_date']) <= strtotime(date("d.m.Y", (time() + 3600*24*10))."00:00:00")) { //&& strtotime($row_orders['event_date']) >= time()

        if (mysqli_num_rows($result_configure_orders) > 0) {
          $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
        } else {
          $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182 ORDER BY `id` DESC");
          $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
        }
        $result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `gallery_id` = ".$row_configure_orders['gallery_id']." ORDER BY `id`");
        $video = $video1 = $video2 = $video3 = $gallery = array();
        while($row_gallery_images = mysqli_fetch_assoc($result_gallery_images)) {
          $gallery[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_images['image'];
        }
        $result_configure_orders_video = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
        if (mysqli_num_rows($result_configure_orders_video) > 0) {
          $row_configure_orders_video = mysqli_fetch_assoc($result_configure_orders_video);
          if ($row_configure_orders_video['video'] != "") {
            $video_arr = explode(";", $row_configure_orders_video['video']);
            foreach ($video_arr as $key => $value) {
              $video[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$value;
            }
          } else {
            $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".(strpos(mb_strtolower($row_orders['box_type']), 'ring') !== false ? 1 : 2)." AND `type_id` = 1");
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
              $video[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
            }
          }
        } else {
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".(strpos(mb_strtolower($row_orders['box_type']), 'ring') !== false ? 1 : 2)." AND `type_id` = 1");
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 1");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video1[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 2");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video2[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 3");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video3[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }

        $add = true;
        if ($row_orders['image'] != "") {
          if (strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false) {
            $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `type_id` = 4");
            $row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
            $template = $row_orders['image_vegas'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image_vegas'] : "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
            //$template = "";
          } else {
            $template = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image'];
          }
        } else {
          if (strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false) {
            $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `type_id` = 4");
            $row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
            $template = $row_orders['image_vegas'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image_vegas'] : "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
            //$template = "";
            list($width, $height) = getimagesize($template);
              if ($width > $height) {
                $add = false;
              }
          } else {
            $template = "";
            $add = false;
          }
        }

        if ($row_orders['data'] != "") {
          $data = json_decode(base64_decode($row_orders['data']), true);
        } else {
          $result_orders2 = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = 4182");
          $row_orders2 = mysqli_fetch_assoc($result_orders2);
          $data = json_decode(base64_decode($row_orders2['data']), true);
        }
        if ($template != "") {
          list($width, $height) = getimagesize($template);
          if ($width > $height) {
            //$add = false;
            $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `type_id` = 4");
            $row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
            $template = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
            $data = json_decode(base64_decode("eyJvYmplY3RzIjpbeyJ0eXBlIjoiem9uZSIsIndpZHRoIjoxMjAwLCJoZWlnaHQiOjE4MDAsInRyYW5zZm9ybSI6InQxNDg3LjcyNzMsMjIxNi4zNjM3czIuMjUyMywyLjI1MjMsMCwwIiwibWF0cml4IjoibWF0cml4KDIuMjUyMywwLDAsMi4yNTIzLC0xMi4yNywtMzMuNjQpIn1dfQ=="), true);
          }
        }


        if ($add) {

          if (strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false) {
            $result_orders2 = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = 4182");
            $row_orders2 = mysqli_fetch_assoc($result_orders2);
            $template = $row_orders['image_vegas'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image_vegas'] : "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders2['image'];
            //$result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182 ORDER BY `id` DESC");
            //$row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
          }

          $long_duration = array();

          $orders[] = array(
            'order_id' => $row_orders['id'],
            'num_id' => $row_orders['num_id'],
            'event_date' => $row_orders['event_date'],
            'password' => $row_orders['password'],
            'personal_data' => ($row_orders['personal_data'] == 1),
            'name' => $row_orders['societe'] != '' ? $row_orders['societe'] : $row_orders['last_name']." ".$row_orders['first_name'],
            'video' => $video,
            'video1' => $video1,
            'video2' => $video2,
            'video3' => $video3,
            'background_image' => ($row_configure_orders['image'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_configure_orders['image'] : ""),
            'background_color' => $row_configure_orders['background_color'],
            'text_color' => $row_configure_orders['text_color'],
            'photo_enable' => ($row_configure_orders['photo_switch'] == 1),
            'photo_delay_1' => $row_configure_orders['photo_delay_1'],
            'photo_delay_2' => $row_configure_orders['photo_delay_2'],
            'gif_enable' => ($row_configure_orders['gif_switch'] == 1),
            'gif_delay_1' => $row_configure_orders['gif_delay_1'],
            'gif_delay_2' => $row_configure_orders['gif_delay_2'],
            'gif_speed' => $row_configure_orders['gif_speed'],
            'boomerang_enable' => ($row_configure_orders['boomerang_switch'] == 1),
            'boomerang_delay' => $row_configure_orders['boomerang_delay'],
            'boomerang_duration' => $row_configure_orders['boomerang_duration'],
            'boomerang_speed' => $row_configure_orders['boomerang_speed'],
            'prop_enable' => ($row_configure_orders['prop_switch'] == 1),
            'gallery' => $gallery,
            'sms_enable' => ($row_configure_orders['sms_switch'] == 1),
            'sms_text' => $row_configure_orders['sms_text'],
            'sms_popup' => $row_configure_orders['sms_popup'],
            'sms_button' => $row_configure_orders['sms_button'],
            'email_enable' => ($row_configure_orders['email_switch'] == 1),
            'email_from' => $row_configure_orders['email_from'],
            'email_subject' => $row_configure_orders['email_subject'],
            'email_text' => $row_configure_orders['email_text'],
            'email_popup' => $row_configure_orders['email_popup'],
            'email_button' => $row_configure_orders['email_button'],
            'qr_code_enable' => ($row_configure_orders['qr_code_switch'] == 1),
            'rgpd_enable' => ($row_configure_orders['rgpd_switch'] == 1),
            'rgpd_text' => $row_configure_orders['rgpd_text'],
            'rgpd_yes' => $row_configure_orders['rgpd_yes'],
            'rgpd_no' => $row_configure_orders['rgpd_no'],
            'photo_amount' => $row_configure_orders['photo_amount'],
            'photo_max' => $row_configure_orders['photo_max'],
            'template' => $template,
            'template_positions' => $data,
            'long_duration' => $long_duration
          );
        }
      }
    }
    $result['result'] = array('orders' => $orders);
  }

  if ($action == "get") {
    if (isset($password)) {
      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `password` LIKE '$password'");
      if(mysqli_num_rows($result_orders)) {
        $row_orders = mysqli_fetch_assoc($result_orders);
        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
        $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
        $result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `gallery_id` = ".$row_configure_orders['gallery_id']." ORDER BY `id`");
        $video = $video1 = $video2 = $video3 = $gallery = array();
        while($row_gallery_images = mysqli_fetch_assoc($result_gallery_images)) {
          $gallery[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_images['image'];
        }
        $video_arr = explode(";", $row_configure_orders['video']);
        foreach ($video_arr as $key => $value) {
          $video[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$value;
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 1");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video1[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 2");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video2[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 3");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video3[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }

        if ($row_orders['image'] != "") {
          $template = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image'];
        } else {
          if (strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false) {
            $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `type_id` = 4");
            $row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
            $template = $row_orders['image_vegas'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image_vegas'] : "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          } else {
            $template = "";
          }
        }

        $long_duration = array();

        $result['result'] = array(
          'order_id' => $row_orders['id'],
          'num_id' => $row_orders['num_id'],
          'name' => $row_orders['societe'] != '' ? $row_orders['societe'] : $row_orders['last_name']." ".$row_orders['first_name'],
          'video' => $video,
          'video1' => $video1,
          'video2' => $video2,
          'video3' => $video3,
          'background_image' => ($row_configure_orders['image'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_configure_orders['image'] : ""),
          'background_color' => $row_configure_orders['background_color'],
          'text_color' => $row_configure_orders['text_color'],
          'photo_enable' => ($row_configure_orders['photo_switch'] == 1),
          'photo_delay_1' => $row_configure_orders['photo_delay_1'],
          'photo_delay_2' => $row_configure_orders['photo_delay_2'],
          'gif_enable' => ($row_configure_orders['gif_switch'] == 1),
          'gif_delay_1' => $row_configure_orders['gif_delay_1'],
          'gif_delay_2' => $row_configure_orders['gif_delay_2'],
          'gif_speed' => $row_configure_orders['gif_speed'],
          'boomerang_enable' => ($row_configure_orders['boomerang_switch'] == 1),
          'boomerang_delay' => $row_configure_orders['boomerang_delay'],
          'boomerang_duration' => $row_configure_orders['boomerang_duration'],
          'boomerang_speed' => $row_configure_orders['boomerang_speed'],
          'prop_enable' => ($row_configure_orders['prop_switch'] == 1),
          'gallery' => $gallery,
          'sms_enable' => ($row_configure_orders['sms_switch'] == 1),
          'sms_text' => $row_configure_orders['sms_text'],
          'sms_popup' => $row_configure_orders['sms_popup'],
          'sms_button' => $row_configure_orders['sms_button'],
          'email_enable' => ($row_configure_orders['email_switch'] == 1),
          'email_from' => $row_configure_orders['email_from'],
          'email_subject' => $row_configure_orders['email_subject'],
          'email_text' => $row_configure_orders['email_text'],
          'email_popup' => $row_configure_orders['email_popup'],
          'email_button' => $row_configure_orders['email_button'],
          'qr_code_enable' => ($row_configure_orders['qr_code_switch'] == 1),
          'rgpd_enable' => ($row_configure_orders['rgpd_switch'] == 1),
          'rgpd_text' => $row_configure_orders['rgpd_text'],
          'rgpd_yes' => $row_configure_orders['rgpd_yes'],
          'rgpd_no' => $row_configure_orders['rgpd_no'],
          'photo_amount' => $row_configure_orders['photo_amount'],
          'photo_max' => $row_configure_orders['photo_max'],
          'template' => $template,
          'template_positions' => json_decode(base64_decode($row_orders['data']), true),
          'long_duration' => $long_duration
        );
      } else {
        header("HTTP/1.0 400 Bad Request");
        $result['error'] = array('title' => 'Order with password not found!', 'err_code' => 2);
      }
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }
}

if ($method == 'desktop') {

  if ($action == "list") {

    $rq = " WHERE `box_type` LIKE '%vegas%' AND `image` != ''";

    $orders = array();
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` ".$rq);
    while($row_orders = mysqli_fetch_assoc($result_orders)) {
      $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
      if (mysqli_num_rows($result_configure_orders) > 0 && $row_orders['image'] != '' && $row_orders['data'] != '') { //&& strtotime($row_orders['event_date']) >= time()
        $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
        $result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `gallery_id` = ".$row_configure_orders['gallery_id']." ORDER BY `id`");
        $video1 = $video2 = $video3 = $gallery = array();
        while($row_gallery_images = mysqli_fetch_assoc($result_gallery_images)) {
          $gallery[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_images['image'];
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 1");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video1[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        } else {
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = 3 AND `type_id` = 1");
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video1[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 2");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video2[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        } else {
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = 3  AND `type_id` = 2");
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video2[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 3");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video3[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        } else {
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = 3 AND `type_id` = 3");
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video3[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }

        $add = true;
        $template = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image'];
        /*list($width, $height) = getimagesize($template);
        if ($width < $height) {
          $add = false;
        }*/

        $long_duration = array();

        if ($add) {

          $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$row_orders['template_id']);
          $row_templates = mysqli_fetch_assoc($result_templates);

          $orders[] = array(
            'order_id' => $row_orders['id'],
            'num_id' => $row_orders['num_id'],
            'name' => $row_orders['societe'] != '' ? $row_orders['societe'] : $row_orders['last_name']." ".$row_orders['first_name'],
            'password' => $row_orders['password'],
            'personal_data' => ($row_orders['personal_data'] == 1),
            'video' => ($row_configure_orders['video'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_configure_orders['video'] : ""),
            'video1' => $video1,
            'video2' => $video2,
            'video3' => $video3,
            'background_image' => ($row_configure_orders['image'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_configure_orders['image'] : ""),
            'background_color' => $row_configure_orders['background_color'],
            'text_color' => $row_configure_orders['text_color'],
            'photo_enable' => ($row_configure_orders['photo_switch'] == 1),
            'photo_delay_1' => $row_configure_orders['photo_delay_1'],
            'photo_delay_2' => $row_configure_orders['photo_delay_2'],
            'gif_enable' => ($row_configure_orders['gif_switch'] == 1),
            'gif_delay_1' => $row_configure_orders['gif_delay_1'],
            'gif_delay_2' => $row_configure_orders['gif_delay_2'],
            'gif_speed' => $row_configure_orders['gif_speed'],
            'boomerang_enable' => ($row_configure_orders['boomerang_switch'] == 1),
            'boomerang_delay' => $row_configure_orders['boomerang_delay'],
            'boomerang_duration' => $row_configure_orders['boomerang_duration'],
            'boomerang_speed' => $row_configure_orders['boomerang_speed'],
            'prop_enable' => ($row_configure_orders['prop_switch'] == 1),
            'gallery' => $gallery,
            'sms_enable' => ($row_configure_orders['sms_switch'] == 1),
            'sms_text' => $row_configure_orders['sms_text'],
            'sms_popup' => $row_configure_orders['sms_popup'],
            'sms_button' => $row_configure_orders['sms_button'],
            'email_enable' => ($row_configure_orders['email_switch'] == 1),
            'email_from' => $row_configure_orders['email_from'],
            'email_subject' => $row_configure_orders['email_subject'],
            'email_text' => $row_configure_orders['email_text'],
            'email_popup' => $row_configure_orders['email_popup'],
            'email_button' => $row_configure_orders['email_button'],
            'qr_code_enable' => ($row_configure_orders['qr_code_switch'] == 1),
            'rgpd_enable' => ($row_configure_orders['rgpd_switch'] == 1),
            'rgpd_text' => $row_configure_orders['rgpd_text'],
            'rgpd_yes' => $row_configure_orders['rgpd_yes'],
            'rgpd_no' => $row_configure_orders['rgpd_no'],
            'photo_amount' => $row_configure_orders['photo_amount'],
            'photo_max' => $row_configure_orders['photo_max'],
            'template' => $template,
            'template_positions' => json_decode(base64_decode($row_orders['data']), true),
            'long_duration' => $long_duration,
            'format_id' => $row_orders['format_id'] > 0 ? intval($row_orders['format_id']) : intval($row_templates['format_id'])
          );
        }
      }
    }
    $result['result'] = array('orders' => $orders);
  }

  if ($action == "get") {
    if (isset($password)) {
      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `password` LIKE '$password'");
      if(mysqli_num_rows($result_orders)) {
        $row_orders = mysqli_fetch_assoc($result_orders);
        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
        $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
        $result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `gallery_id` = ".$row_configure_orders['gallery_id']." ORDER BY `id`");
        $video1 = $video2 = $video3 = $gallery = array();
        while($row_gallery_images = mysqli_fetch_assoc($result_gallery_images)) {
          $gallery[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_images['image'];
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 1");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video1[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 2");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video2[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }
        $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 3");
        if (mysqli_num_rows($result_gallery_videos) > 0) {
          while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
            $video3[] = "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_gallery_videos['video'];
          }
        }

        $long_duration = array();

        $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$row_orders['template_id']);
        $row_templates = mysqli_fetch_assoc($result_templates);

        $result['result'] = array(
          'order_id' => $row_orders['id'],
          'num_id' => $row_orders['num_id'],
          'name' => $row_orders['societe'] != '' ? $row_orders['societe'] : $row_orders['last_name']." ".$row_orders['first_name'],
          'video' => ($row_configure_orders['video'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_configure_orders['video'] : ""),
          'video1' => $video1,
          'video2' => $video2,
          'video3' => $video3,
          'background_image' => ($row_configure_orders['image'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_configure_orders['image'] : ""),
          'background_color' => $row_configure_orders['background_color'],
          'text_color' => $row_configure_orders['text_color'],
          'photo_enable' => ($row_configure_orders['photo_switch'] == 1),
          'photo_delay_1' => $row_configure_orders['photo_delay_1'],
          'photo_delay_2' => $row_configure_orders['photo_delay_2'],
          'gif_enable' => ($row_configure_orders['gif_switch'] == 1),
          'gif_delay_1' => $row_configure_orders['gif_delay_1'],
          'gif_delay_2' => $row_configure_orders['gif_delay_2'],
          'gif_speed' => $row_configure_orders['gif_speed'],
          'boomerang_enable' => ($row_configure_orders['boomerang_switch'] == 1),
          'boomerang_delay' => $row_configure_orders['boomerang_delay'],
          'boomerang_duration' => $row_configure_orders['boomerang_duration'],
          'boomerang_speed' => $row_configure_orders['boomerang_speed'],
          'prop_enable' => ($row_configure_orders['prop_switch'] == 1),
          'gallery' => $gallery,
          'sms_enable' => ($row_configure_orders['sms_switch'] == 1),
          'sms_text' => $row_configure_orders['sms_text'],
          'sms_popup' => $row_configure_orders['sms_popup'],
          'sms_button' => $row_configure_orders['sms_button'],
          'email_enable' => ($row_configure_orders['email_switch'] == 1),
          'email_from' => $row_configure_orders['email_from'],
          'email_subject' => $row_configure_orders['email_subject'],
          'email_text' => $row_configure_orders['email_text'],
          'email_popup' => $row_configure_orders['email_popup'],
          'email_button' => $row_configure_orders['email_button'],
          'qr_code_enable' => ($row_configure_orders['qr_code_switch'] == 1),
          'rgpd_enable' => ($row_configure_orders['rgpd_switch'] == 1),
          'rgpd_text' => $row_configure_orders['rgpd_text'],
          'rgpd_yes' => $row_configure_orders['rgpd_yes'],
          'rgpd_no' => $row_configure_orders['rgpd_no'],
          'photo_amount' => $row_configure_orders['photo_amount'],
          'photo_max' => $row_configure_orders['photo_max'],
          'template' => ($row_orders['image'] != "" ? "https://ftp.shootnbox.fr/".UPLOAD_IMAGES_DIR.$row_orders['image'] : ""),
          'template_positions' => json_decode(base64_decode($row_orders['data']), true),
          'long_duration' => $long_duration,
          'format_id' => $row_orders['format_id'] > 0 ? intval($row_orders['format_id']) : intval($row_templates['format_id'])
        );
      } else {
        header("HTTP/1.0 400 Bad Request");
        $result['error'] = array('title' => 'Order with password not found!', 'err_code' => 2);
      }
    } else {
      header("HTTP/1.0 400 Bad Request");
      $result['error'] = array('title' => 'Transferred not all parameters!', 'err_code' => 1);
    }
  }
}

if ($method == 'timelines') {

  if ($action == "boxes") {
    $boxes = array();
    $boxes[] = array('title' => 'Ring', 'color' => fromRGB(252, 102, 32));
    $boxes[] = array('title' => 'Vegas', 'color' => fromRGB(236, 0, 139));
    $boxes[] = array('title' => 'Vegas Slim', 'color' => fromRGB(236, 0, 139));
    $boxes[] = array('title' => 'Miroir', 'color' => fromRGB(0, 174, 239));
    $boxes[] = array('title' => 'Spinner', 'color' => fromRGB(0, 201, 83));
    $result = $boxes;
  }

  if ($action == "box_ids") {
    $box = strtolower($box);
    $names = array();
    switch($box) {
      case 'ring':
        for ($i = 1; $i <= $row_settings['ring']; $i++) {
          $result_timeline = mysqli_query($conn, "SELECT * FROM `timeline` WHERE `name` LIKE 'R$i'");
          if (mysqli_num_rows($result_timeline) > 0) {
            $row_timeline = mysqli_fetch_assoc($result_timeline);
            $intervals = json_decode(base64_decode($row_timeline['intervals']));
          } else {
            $intervals = array();
          }
          $names[] = array('name' => "R$i", 'color' => fromRGB(252 - ($i-1)*8, 102 + ($i-1)*5, 32 + ($i-1)*5), 'intervals' => $intervals);
        }
      break;

      case 'vegas':
        for ($i = 1; $i <= $row_settings['vegas']; $i++) {
          $result_timeline = mysqli_query($conn, "SELECT * FROM `timeline` WHERE `name` LIKE 'V$i'");
          if (mysqli_num_rows($result_timeline) > 0) {
            $row_timeline = mysqli_fetch_assoc($result_timeline);
            $intervals = json_decode(base64_decode($row_timeline['intervals']));
          } else {
            $intervals = array();
          }
          $names[] = array('name' => "V$i", 'color' => fromRGB(236 - ($i-1)*8, 0 + ($i-1)*5, 139 + ($i-1)*3), 'intervals' => $intervals);
        }
      break;

      case 'vegas slim':
        for ($i = 1; $i <= $row_settings['vegas_slim']; $i++) {
          $result_timeline = mysqli_query($conn, "SELECT * FROM `timeline` WHERE `name` LIKE 'VS$i'");
          if (mysqli_num_rows($result_timeline) > 0) {
            $row_timeline = mysqli_fetch_assoc($result_timeline);
            $intervals = json_decode(base64_decode($row_timeline['intervals']));
          } else {
            $intervals = array();
          }
          $names[] = array('name' => "VS$i", 'color' => fromRGB(236 - ($i-1)*8, 0 + ($i-1)*8, 139 + ($i-1)*5), 'intervals' => $intervals);
        }
      break;

      case 'miroir':
        for ($i = 1; $i <= $row_settings['miroir']; $i++) {
          $result_timeline = mysqli_query($conn, "SELECT * FROM `timeline` WHERE `name` LIKE 'M$i'");
          if (mysqli_num_rows($result_timeline) > 0) {
            $row_timeline = mysqli_fetch_assoc($result_timeline);
            $intervals = json_decode(base64_decode($row_timeline['intervals']));
          } else {
            $intervals = array();
          }
          $names[] = array('name' => "M$i", 'color' => fromRGB(0 + ($i-1)*8, 174 + ($i-1)*8, 239 - ($i-1)*5), 'intervals' => $intervals);
        }
      break;

      case 'spinner':
        for ($i = 1; $i <= $row_settings['spinner']; $i++) {
          $result_timeline = mysqli_query($conn, "SELECT * FROM `timeline` WHERE `name` LIKE 'S$i'");
          if (mysqli_num_rows($result_timeline) > 0) {
            $row_timeline = mysqli_fetch_assoc($result_timeline);
            $intervals = json_decode(base64_decode($row_timeline['intervals']));
          } else {
            $intervals = array();
          }
          $names[] = array('name' => "S$i", 'color' => fromRGB(0 + ($i-1)*8, 201 + ($i-1)*5, 83 + ($i-1)*8), 'intervals' => $intervals);
        }
      break;
    }

    $result = $names;
  }

  if ($action == "box_update") {
    if (isset($name) && isset($intervals)) {
      $intervals = str_replace('--', '==', str_replace(' ', '+', $intervals));
      $result_timeline = mysqli_query($conn, "SELECT * FROM `timeline` WHERE `name` LIKE '$name'");
      if (mysqli_num_rows($result_timeline) > 0) {
        mysqli_query($conn, "UPDATE `timeline` SET `intervals` = '$intervals'  WHERE `name` LIKE '$name'");
      } else {
        mysqli_query($conn, "INSERT INTO `timeline`(`id`, `name`, `intervals`) VALUES (NULL, '$name', '$intervals')");
      }
      $result = array('status' => 'done');
    } else {
      $result = array('status' => 'error');
    }
  }


  if ($action == "search") {
    $orders = array();
    if (strlen($text) > 0) {
      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `societe` LIKE '%$text%' OR `first_name` LIKE '%$text%' OR `last_name` LIKE '%$text%'");
      while($row_orders = mysqli_fetch_assoc($result_orders)) {
        $orders[] = array('order_id' => $row_orders['id'], 'company' => $row_orders['societe'], 'first_name' => $row_orders['first_name'], 'last_name' => $row_orders['last_name']);
      }

    }

    $result = $orders;
  }
}


echo json_encode($result);

@mysqli_close($conn);

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 5; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function fromRGB($R, $G, $B)
{

    $R = dechex($R);
    if (strlen($R)<2)
    $R = '0'.$R;

    $G = dechex($G);
    if (strlen($G)<2)
    $G = '0'.$G;

    $B = dechex($B);
    if (strlen($B)<2)
    $B = '0'.$B;

    return '#' . $R . $G . $B;
}

?>