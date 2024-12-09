<?php
@require_once("inc/mainfile.php");

$worker = explode("::", file_get_contents("worker.ini"));

  $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$worker[0]);
  $row_orders = mysqli_fetch_assoc($result_orders);
  $data = json_decode(base64_decode($row_orders['data']), true);
  print_r($data);

  if (strpos(mb_strtolower($row_orders['box_type']), 'vegas') !== false && $worker[2] == "ipad") {
    $result_orders2 = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = 4182");
    $row_orders2 = mysqli_fetch_assoc($result_orders2);
    $data = json_decode(base64_decode($row_orders2['data']), true);
    $template = $row_orders2['image'];
    $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182 ORDER BY `id` DESC");
    $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
  } else {
    $template = $row_orders['image'];
    $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
    $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
  }


  $file_name = $worker[1];

  $hash = $worker[3];
  $email = $worker[4];
  $sms = $worker[5];

  $matrix = explode(",", $data['objects'][0]['matrix']);
  $delta = trim($matrix[5], ")") * $matrix[3];

  /*$result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf hflip'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/".$file_name), true);
  copy($result['path'], "uploads/".$row_orders['num_id']."/".$file_name);
  file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);*/


  $result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf scale=1320:1920'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/".$file_name), true);
  copy($result['path'], "uploads/".$row_orders['num_id']."/input1.mov");
  file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);

  $result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf "hflip, scale='.$data['objects'][0]['width'].':1920"'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/".$file_name), true);
  copy($result['path'], "uploads/".$row_orders['num_id']."/input2.mov");
  file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);

  if ($template != "") {
    include("ResizeImage.php");
    $image = new ResizeImage();
    $image->load("uploads/images/".$template);
    $image->resize(1320, 1920, "y");
    $image->save("uploads/images/frame.png");
    $result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-i https://shootnbox.fr/uploads/'.$row_orders['num_id'].'/input2.mov -i https://shootnbox.fr/uploads/images/frame.png -filter_complex "overlay = '.((1320 - $data['objects'][0]['width']) / 2).':'.$delta.', overlay = 0:0"'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/input1.mov"), true);
    copy($result['path'], "uploads/".$row_orders['num_id']."/".$file_name);
    file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);
  } else {
    $result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-i https://shootnbox.fr/uploads/'.$row_orders['num_id'].'/input2.mov -filter_complex "overlay = '.((1320 - $data['objects'][0]['width']) / 2).':0"'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/input1.mov"), true);
    copy($result['path'], "uploads/".$row_orders['num_id']."/".$file_name);
    file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);
  }



  if ($row_configure_orders['boomerang_switch'] == 1) {
    if ($row_configure_orders['boomerang_speed'] > 0) {
    $speed = $row_configure_orders['boomerang_speed'];
  } else {
    $speed = 1;
  }

  $result = json_decode(file_get_contents("http://141.94.254.113:9000/make_boom?token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/".$file_name."&speed=".$speed), true);
    copy($result['Path:'],  "uploads/".$row_orders['num_id']."/".$file_name);
    file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['Path:']);
  }


  /*if (isset($filter) && $filter == "sepia") {
    $result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-filter_complex "[0:v]colorchannelmixer=.393:.769:.189:0:.349:.686:.168:0:.272:.534:.131[colorchannelmixed];[colorchannelmixed]eq=1.0:0:1.3:2.4:1.0:1.0:1.0:1.0[color_effect]" -map "[color_effect] -c:v libx264 -c:a"'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/".$file_name), true);
    copy($result['path'], "uploads/".$row_orders['num_id']."/".$file_name);
    file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);
  }

  if (isset($filter) && $filter == "monochrome") {
    $result = json_decode(file_get_contents("http://141.94.254.113:9000/ff?param=".trim(base64_encode('-vf format=gray'), "=")."&token=ksadjaslkdjqww871&link=https://shootnbox.fr/uploads/".$row_orders['num_id']."/".$file_name), true);
    copy($result['path'], "uploads/".$row_orders['num_id']."/".$file_name);
    file_get_contents("http://141.94.254.113:9000/clean_boom?token=ksadjaslkdjqww871&id=".$result['path']);
  }*/
  @unlink("uploads/".$row_orders['num_id']."/input1.mov");
  @unlink("uploads/".$row_orders['num_id']."/input2.mov");

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
            '.nl2br(str_replace('[medialink]', '<a href="https://shootnbox.fr/view/?hash='.$hash.'" taget="blank">https://shootnbox.fr/view/?hash='.$hash.'</a>', $row_configure_orders['email_text'])).'
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
            <text>'.str_replace('[medialink]', '%0Ahttps://shootnbox.fr/view/?hash='.$hash.'%0A', $row_configure_orders['sms_text']).'</text>
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

  echo"done";
?>