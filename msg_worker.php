<?php
@require_once("inc/mainfile.php");

$worker = explode("::", file_get_contents("msg_worker.ini"));

$hash = $worker[0];
$email = $worker[1];
$sms = $worker[2];

  $result_short_links = mysqli_query($conn, "SELECT * FROM `short_links` WHERE `hash` LIKE '$hash'");

        $row_short_links = mysqli_fetch_assoc($result_short_links);

        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` =".$row_short_links['order_id']);
        $row_orders = mysqli_fetch_assoc($result_orders);

        $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
        if (mysqli_num_rows($result_configure_orders) == 0) {
          $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182");
        }
        $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);

        sleep(45);

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

          mysqli_query($conn, "INSERT INTO `orders_data`(`id`, `order_id`, `type_id`, `data`) VALUES (NULL, '".$row_orders['id']."', '1', '$email')");
        }

        if (isset($sms) && $sms != "") {

          ob_start();

          $result_short_links = mysqli_query($conn, "SELECT `short` FROM `short_links` WHERE `hash` LIKE '$hash'");
          $row_short_links = mysqli_fetch_assoc($result_short_links);
          // CapitoleMobile POST URL
          $postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";
          //Structure de Données XML
          $xmlString = '<SMS>
            <authentification>
              <username>Shootnbox</username>
              <password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
            </authentification>
            <message>
              <text>'.str_replace('[medialink]', '%0Ahttps://shootnbox.fr/l/'.$row_short_links['short'].'%0A', $row_configure_orders['sms_text']).'</text>
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

        }

  echo"done";
?>