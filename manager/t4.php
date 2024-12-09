<?php
@require_once("../inc/mainfile.php");

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `selected_options` != ''");
while($row_orders = mysqli_fetch_assoc($result_orders)) {



if (strpos($row_orders['select_type'], 'entreprise') !== false) {
  $data = json_decode(file_get_contents("../enterprise.ini"), true);
  $prices_arr = json_decode(file_get_contents("../enterprise_price.ini"), true);
  switch($row_orders['box_type']) {
    case "Ring":
      $options = $data['ring2']['options'];
      $deliverys = $data['ring2']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_ring'");
      $price = $prices_arr['ring_price'];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_ring2_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_ring2_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_ring2_options_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_ring2_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_ring2_delivery_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_ring2_delivery_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Vegas":
    case "Vegas_800":
    case "Vegas_1200":
      $options = $data['vegas']['options'];
      $deliverys = $data['vegas']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vegas".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")."'");
      $price = $prices_arr['vegas_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_options_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_options_3_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_options_4_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_options_5_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_delivery_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vegas_delivery_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Miroir":
    case "Miroir_800":
    case "Miroir_1200":
      $options = $data['miroir']['options']; $deliverys = $data['miroir']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_miroir".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")."'");
      $price = $prices_arr['miroir_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_options_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_options_3_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_options_4_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_options_5_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_miroir_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Spinner_360":
      $options = $data['spinner']['options'];
      $deliverys = $data['spinner']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_spinner'");
      $price = $prices_arr['spinner_price'];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_spinner_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_spinner_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_spinner_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Réalité_Virtuelle":
      $options = $data['vr2']['options'];
      $deliverys = $data['vr2']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vr'");
      $price = $prices_arr['vr_price'];

      $options_description = array();

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'enterprise_addit_vr2_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
  }
} else {
  $data = json_decode(file_get_contents("../particulier.ini"), true);
  $prices_arr = json_decode(file_get_contents("../particulier_price.ini"), true);
  switch($row_orders['box_type']) {
    case "Ring":
      $options = $data['ring']['options'];
      $deliverys = $data['ring']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_ring'");
      $price = $prices_arr['ring_price'];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_options_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_options_3_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_delivery_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_ring_delivery_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Vegas":
    case "Vegas_800":
    case "Vegas_1200":
      $options = $data['vegas']['options'];
      $deliverys = $data['vegas']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vegas".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")."'");
      $price = $prices_arr['vegas_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_3_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_4_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_5_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_options_6_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_delivery_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vegas_delivery_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Miroir":
    case "Miroir_800":
    case "Miroir_1200":
      $options = $data['miroir']['options']; $deliverys = $data['miroir']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_miroir".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")."'");
      $price = $prices_arr['miroir_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200")];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_options_1_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_options_2_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_options_3_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_options_4_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_options_5_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_miroir_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Spinner_360":
      $options = $data['spinner']['options'];
      $deliverys = $data['spinner']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_spinner'");
      $price = $prices_arr['spinner_price'];

      $options_description = array();
      $result_options_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_spinner_options_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $options_description[] = $row_options_description['meta_value'];

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_spinner_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
    case "Réalité_Virtuelle":
      $options = $data['vr2']['options'];
      $deliverys = $data['vr2']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vr'");
      $price = $prices_arr['vr_price'];

      $options_description = array();

      $delivery_description = array();
      $result_delivery_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `meta_key` = 'particular_addit_vr2_delivery_0_article' AND `post_id` = 989862");
      $row_options_description = mysqli_fetch_assoc($result_options_description);
      $delivery_description[] = $row_delivery_description['meta_value'];
    break;
  }
}


$deliverys[] = array('name' => 'Kilométriques supplémentaires', 'price' => 49);
$row_description = mysqli_fetch_assoc($result_description);

$selected_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))))));
$selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))));

$delivery_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', $row_orders['selected_options'])));
$delivery_txt = $delivery_options_arr[count($delivery_options_arr) - 1];
$delivery_options_value_arr = explode(",", $row_orders['delivery_options']);


$total_ht = 0;
$total_tva = 0;



if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
  if ($price != 0) {
    $total_ht = $total_ht + $price*$row_orders['amount'];
    $total_tva = $total_tva + $price*$row_orders['amount']*0.2;
  }
} else {
  if ($price != 0) {
    $total_ht = $total_ht + ($price*$row_orders['amount'] - number_format($price*$row_orders['amount']/120*20, 2, '.', ''));
    $total_tva = $total_tva + number_format($price*$row_orders['amount']/120*20, 2, '.', '');
  }
}

$i = 0;
$assurance = false;

$options_txt = "";
foreach ($options as $key => $value) {
  if (in_array($value['name'], $selected_options_arr)) {
    for($i = 0; $i < count($selected_options_arr); $i++) {
      if (trim($selected_options_arr[$i]) == $value['name']) {
        if ($options_txt != "") {
          $options_txt .= ",";
        }
        $options_txt .=  $value['name'].":1:".$value['price'];
      }
    }
  }
}





print_r($row_orders['selected_options']);
  $tmp_arr = explode(",", $row_orders['selected_options']);
  $tmp_arr2 = explode(":", $tmp_arr[0]);
  if (!isset($tmp_arr2[2])) {
    echo "<br />".$row_orders['id']." - ".trim($options_txt)."<br />";
    mysqli_query($conn, "UPDATE `orders_new` SET `selected_options` = '".trim($options_txt)."' WHERE `id` = ".$row_orders['id']);
  }

  if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
    //echo $row_orders['id']." - ".($total_ht - $total_ht/100*$row_orders['transportation_time'])."€ HT<br />";
    //mysqli_query($conn, "UPDATE `orders_new` SET `total` = '".($total_ht - $total_ht/100*$row_orders['transportation_time'])."' WHERE `id` = ".$row_orders['id']);
  } else {
    //echo $row_orders['id']." - ".(($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']))."€ TTC<br />";
    //mysqli_query($conn, "UPDATE `orders_new` SET `total` = '".(($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']))."' WHERE `id` = ".$row_orders['id']);
  }

}

mysqli_close($conn);
?>
