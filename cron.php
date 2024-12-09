<?php
@require_once("inc/mainfile.php");
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `status` = 2");
//ob_start();
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  $days = floor((strtotime($row_orders['event_date']) - time()) / (60 * 60 * 24));
  $message = "";

  if ($days == 15 && $row_orders['image'] == "" && mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je choisis sur catalogue') !== false) {
     //echo $days."<br>";
     $message = htmlspecialchars_decode($row_settings['sms2'], ENT_QUOTES);
     $long = $row_settings['long2'];

  }

  if ($days == 1) {
     //echo $days."<br>";
     $message = htmlspecialchars_decode($row_settings['sms3'], ENT_QUOTES);
     $long = $row_settings['long3'];
  }

  if ($message != "") {

    // CapitoleMobile POST URL
    $postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";

    //Structure de Données XML
    $xmlString = '<SMS>
      <authentification>
        <username>Shootnbox</username>
        <password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
      </authentification>
      <message>
        <text>'.$message.'</text>
        <sender>ShootnBox</sender>
        <long>'.$long.'</long>
      </message>
      <recipients>
        <gsm>'.preg_replace("/[^0-9\s]/", '', $row_orders['phone']).'</gsm>
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


  }
}
//ob_get_clean();
echo"done";
?>