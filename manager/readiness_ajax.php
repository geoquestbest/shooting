<?php
@session_start();
@header('Content-Type: application/json; charset=utf-8');

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400'); // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	exit(0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && json_decode(file_get_contents('php://input'), true) != "") $_POST = json_decode(file_get_contents('php://input'), true);

@require_once("../inc/mainfile.php");

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
//$columnIndex = $_POST['order'][0]['column']; // Column index
//$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

if ($_POST['order'][0]['column'] == 0) {
  $order = "UNIX_TIMESTAMP(STR_TO_DATE(`event_date`, '%d.%m.%Y')) ".$_POST['order'][0]['dir'];
} else {
  if ($_POST['order'][0]['column'] == 7) {
    $order = "`image` ".$_POST['order'][0]['dir'].", UNIX_TIMESTAMP(STR_TO_DATE(`event_date`, '%d.%m.%Y')) ASC";
  } else {
    $order = "`agency_id` ".$_POST['order'][0]['dir'].", UNIX_TIMESTAMP(STR_TO_DATE(`event_date`, '%d.%m.%Y')) ASC";
  }
}

$searchValue = trim($_POST['search']['value']); // Search value


if ($searchValue != "") {
	$rq = " AND (`id` LIKE '%".$searchValue."%' OR `societe` LIKE '%".$searchValue."%' OR `first_name` LIKE '%".$searchValue."%' OR `last_name` LIKE '%".$searchValue."%' OR `email` LIKE '%".$searchValue."%' OR `phone` LIKE '%".$searchValue."%' OR `num_id` LIKE '%".$searchValue."%' OR `devis` LIKE '%".$searchValue."%')";
}

$totalRecords = 0;
$totalRecordwithFilter = 0;

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `status` = 2 AND `off` = ".(isset($_GET['arch']) ? 1 : 0).$rq);
$totalRecords = mysqli_num_rows($result_orders);

$data = array();
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `status` = 2 AND `off` = ".(isset($_GET['arch']) ? 1 : 0)." $rq ORDER BY $order LIMIT ".$row.", ".$rowperpage);
$totalRecordwithFilte = mysqli_num_rows($result_orders);
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  switch($row_orders['agency_id']) {
    case 1: $agency = "Paris"; break;
    case 2: $agency = "Bordeaux"; break;
    default: $agency = "-"; break;
  }
  switch(strtolower(trim($row_orders['box_type']))) {
    case "ring": $bg_color = "#ea672a"; break;
    case "vegas": $bg_color = "#e41082"; break;
    case "miroir": $bg_color = "#08a6dd"; break;
    case "spinner_360": $bg_color = "#20a33e"; break;
    case "réalité_virtuelle": $bg_color = "#5d4e98"; break;
  }
  $images = "";

  if ($row_orders['image'] != "") {
    $images .= '<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'">OK</a>';
  }
  $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']);
  while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
     $images .= '<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image'].'">ОК</a>';
  }
  $result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `order_id` = ".$row_orders['id']);
  if (mysqli_num_rows($result_template_images) > 0) {
    $images .= "<br /><small>";
    $j = 1;
    while($row_template_images = mysqli_fetch_assoc($result_template_images)) {
      $images .= '<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_template_images['image'].'">'.$j.'</a>';
      if($j < mysqli_num_rows($result_template_images)) {
        $images .= ", ";
      }
      $j++;
    }
    $images .= "</small>";
  }

  if ($row_orders['without_photo_frame'] == 1) {
    $images .= "<br />Sans cadre photo";
  }

  $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
  $delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
  switch($row_orders['event_time']) {
    case 7: $event_time = "7h à 19h"; break;
    case 8: $event_time = "13h à 19h"; break;
    default: $event_time = "-"; break;
  }
  if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'kilomètres supplémentaires') !== false) {
    foreach (explode(",", $row_orders['selected_options']) as $key => $value) {
      $options_arr = explode(":", $value);
      if (mb_strpos(strtolower(trim($options_arr[0])), 'kilomètres supplémentaires') !== false) {
        $data_km = $options_arr[1] * $options_arr[2];
      }
    }
  } else {
    $data_km = 0;
  }
  $select1 = '<select class="courier form-control" data-id="'.$row_orders['id'].'" data-km="'.($data_km/2).'" style="width: 120px;">
    <option value=""'.($row_orders['courier'] == "" ? ' selected' : '').'>Choisir...</option>';
    $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery`");
    while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
      $select1 .= '<option value="'.$row_delivery['title'].'"'.($row_orders['courier'] == $row_delivery['title'] ? ' selected' : '').'>'.$row_delivery['title'].'</option>';
    }
  $select1 .= '</select>';

  $select2 = '<select class="courier_r form-control" data-id="'.$row_orders['id'].'" data-km="'.($data_km/2).'" style="width: 120px;">
    <option value=""'.($row_orders['courier_r'] == "" ? ' selected' : '').'>Choisir...</option>';
    $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery`");
    while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
      $select2 .= '<option value="'.$row_delivery['title'].'"'.($row_orders['courier_r'] == $row_delivery['title'] ? ' selected' : '').'>'.$row_delivery['title'].'</option>';
    }
  $select2 .= '</select>';


	$data[] = array(
		'id' => $row_orders['id'],
    'date' => $row_orders['event_date'].'<br />ID '.$row_orders['id'],
    'agency' => $agency,
    'societe' => $row_orders['societe'],
    'name' => $row_orders['last_name'].' '.$row_orders['first_name'],
    'acompte' => ((($row_orders['deposit'] > 0 && $row_orders['payment_status'] > 0)) ? "OK" : "").(($row_orders['select_type'] == "Une entreprise") ? "S" : ""),
    'borne' => '<span style="padding: 5px 10px; background: '.$bg_color.'; color: #fff;">'.$row_orders['box_type'].'</span>',
    'box_id' => ($row_orders['box_id'] != "null" ? $row_orders['box_id'] : ""),
    'images' => $images,
    'event' => (((strtolower(trim($row_orders['event_type'])) == "ring" && mysqli_num_rows($result_configure_orders) > 0 && $row_orders['image'] != '' && $row_orders['data'] != '') || $row_orders['event_ready'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="event_ready text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="event_ready text-danger">NON</a>'),
    'box_ready' => (($row_orders['box_ready'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="box_ready text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="box_ready text-danger">NON</a>'),
    'data_ready' => (($row_orders['data_ready'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="data_ready text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="data_ready text-danger">NON</a>'),
    'delivery' => '<center style="padding: 5px; color: '.(($delivery == "Livraison") ? "#c00" : "#20a33e").'; font-weight: bold;">'.$delivery.($row_orders['description'] != "" ? '<br /><a class="btn btn-default btn-icon btn-circle btn-sm" title="Info" onClick="infoOrder(\''.str_replace("\n", "<br />", $row_orders['description']).'\');"><i class="fa fa-info"></i></a>' : '').'</center>',
    'delivery_valid' => ($delivery == 'Retrait boutique' ? 'OK' : ($row_orders['delivery_valid'] == 1 ? 'OK' : '-')),
    'configure' => '<a href="configure.php?order_id='.$row_orders['id'].'&back_url='.$_SERVER['REQUEST_URI'].'" class="btn btn-'.((mysqli_num_rows($result_configure_orders) == 0) ? 'primary' : 'warning').' btn-icon btn-circle btn-lg" title="Configuration 1"><i class="fa fa-edit"></i></a>&nbsp;'.($row_orders['image'] != "" ? '<a href="be/?image='.$row_orders['image'].'&order_id='.$row_orders['id'].'" class="btn btn-'.(($row_orders['data'] == "") ? 'success' : 'danger').' btn-icon btn-circle btn-lg" title="Configuration 2"><i class="fa fa-edit"></i></a>' : ''),
    'password' => $row_orders['password'],
    'login' => '<a class="btn btn-danger btn-icon btn-circle btn-lg" href="../album/?login='.$row_orders['num_id'].'&password='.$row_orders['password'].'" target="_blank" title="Connexion"><i class="fa fa-key"></i></a>',
    'event_time' => $event_time,
    'selected_options' => str_replace(", Livraison", "", str_replace(", Retrait boutique", "", $row_orders['selected_options'])),
    'select1' => $select1,
    'select2' => $select2,
    'delivery_price' => '<input type="text" value="'.$row_orders['delivery_price'].'" class="delivery_price delivery_price'.$row_orders['id'].' form-control" data-id="'.$row_orders['id'].'" style="width: 75px;" />',
    'comment' => '<input type="text" value="'.$row_orders['comment'].'" class="comment form-control" data-id="'.$row_orders['id'].'" style="width: 240px;" />',
    'on_off' => (!isset($_GET['arch']) ? '<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Fermer la commande" onClick="offOrder('.$row_orders['id'].', this);"><i class="fa fa-close"></i></a>' : '<a class="btn btn-primary btn-icon btn-circle btn-sm" title="Rétablir la commande" onClick="onOrder('.$row_orders['id'].', this);"><i class="fa fa-external-link"></i></a>'),
  );
}

$response = array(
	"draw" => intval($draw),
	"iTotalRecords" => $totalRecordwithFilter,
	"iTotalDisplayRecords" => $totalRecords,
	"aaData" => $data,
  "order" => $_POST['order'],
	"ids" => trim($ids, ",")
);

echo json_encode($response);

@mysqli_close($conn);

?>