<?php
// CapitoleMobile POST URL
$postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";
//Structure de Données XML
$xmlString = '<SMS>
<authentification>
<username>Shootnbox</username>
<password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
</authentification>
<message>
<text>Alexey, this is test Messsage !</text>
<sender>ShootnBox</sender>
</message>
<recipients>
<gsm>33678013767</gsm>
</recipients>
</SMS>';
// insertion du nom de la variable POST "XML" avant les données au format XML
$fields = "XML=" . urlencode(utf8_encode($xmlString));
// dans cet exemple, la requête POST est realisée grâce à la librairie Curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $postUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
// Réponse de la requête POST
$response = curl_exec($ch);
curl_close($ch);
// Ecriture de la réponse
echo $response;
?>