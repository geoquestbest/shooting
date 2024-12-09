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
$searchValue = trim($_POST['search']['value']); // Search value


if (isset($_GET['status'])) {
	$rq = "WHERE `status` = ".mysqli_real_escape_string($conn, $_GET['status']);
} else {
	$rq = "";
}

if (isset($_GET['ids'])) {
  if ($rq == "") {
    $rq = "WHERE `id` IN (".mysqli_real_escape_string($conn, $_GET['ids']).")";
  } else {
    $rq .= " AND `id` IN (".mysqli_real_escape_string($conn, $_GET['ids']).")";
  }
}

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
  $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
  $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
} else {
  $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
  $end_date = date("d.m.Y", strtotime("31.12.".(date("Y", time()) + 2)));
}

if (isset($_GET['user_id']) && $_GET['user_id'] != 0) {
  $rq .= ($rq != "" ? " AND `user_id` = ".mysqli_real_escape_string($conn, $_GET['user_id']) : "WHERE `user_id` = ".mysqli_real_escape_string($conn, $_GET['user_id']));
}

if (isset($_GET['long_duration']) && $_GET['long_duration'] != 0) {
  $rq .= ($rq != "" ? " AND `long_duration` = ".mysqli_real_escape_string($conn, $_GET['long_duration']) : "WHERE `long_duration` = ".mysqli_real_escape_string($conn, $_GET['long_duration']));
}

if (isset($_GET['search_type']) && $_GET['search_type'] == 1) {
  $rq .= ($rq != "" ? " AND `created_at` >= ".strtotime($start_date." 00:00:00")." AND `created_at` <= ".strtotime($end_date." 23:59:59") : "WHERE `created_at` >= ".strtotime($start_date." 00:00:00")." AND `created_at` <= ".strtotime($end_date." 23:59:59"));
}

if (isset($_GET['facteur']) && $_GET['facteur'] == 1) {
  $rq .= ($rq != "" ? " AND `facteur` = 1" : "WHERE `facteur` = 1");
}

if ($searchValue != "") {
	$rq .= " AND (`id` LIKE '%".$searchValue."%' OR `societe` LIKE '%".$searchValue."%' OR `first_name` LIKE '%".$searchValue."%' OR `last_name` LIKE '%".$searchValue."%' OR `email` LIKE '%".$searchValue."%' OR `phone` LIKE '%".$searchValue."%' OR `num_id` LIKE '%".$searchValue."%' OR `devis` LIKE '%".$searchValue."%')";
}

$totalRecords = 0;
$totalRecordwithFilter = 0;

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` ".$rq);
$total = 0; $ids = "";
while($row_orders = mysqli_fetch_assoc($result_orders)) {
	if (((!isset($_GET['arch']) && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0 && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) && (!isset($_GET['search_type']) || $_GET['search_type'] == 0) || (isset($_GET['arch']) && floor((strtotime($row_orders['event_date']) + 24*3600 - time())/(3600*24)) < 0) && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date) && (!isset($_GET['search_type']) || $_GET['search_type'] == 0)) || ($_GET['search_type'] == 1 && !isset($_GET['arch']) && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0) || ($_GET['search_type'] == 1 && isset($_GET['arch']) && floor((strtotime($row_orders['event_date']) + 24*3600 - time())/(3600*24)) < 0)) {
		$totalRecords++;
		if (strpos(strtolower($row_orders['select_type']), 'entreprise') === false) {
			 $total = $total + str_replace(",", ".", $row_orders['total']);
    } else {
    	 $total = $total + str_replace(",", ".", $row_orders['total'])*1.2;
    }
		$ids .= $row_orders['id'].",";
	}
}

$data = array();
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` $rq ORDER BY `id` $columnSortOrder LIMIT ".$row.", ".$rowperpage);
while($row_orders = mysqli_fetch_assoc($result_orders)) {
	if (((!isset($_GET['arch']) && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0 && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) && (!isset($_GET['search_type']) || $_GET['search_type'] == 0) || (isset($_GET['arch']) && floor((strtotime($row_orders['event_date']) + 24*3600 - time())/(3600*24)) < 0) && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date) && (!isset($_GET['search_type']) || $_GET['search_type'] == 0)) || ($_GET['search_type'] == 1 && !isset($_GET['arch']) && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0) || ($_GET['search_type'] == 1 && isset($_GET['arch']) && floor((strtotime($row_orders['event_date']) + 24*3600 - time())/(3600*24)) < 0)) {

		if ($row_orders['user_id'] != 0) {
      $result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = ".$row_orders['user_id']);
      $row_users = mysqli_fetch_assoc($result_users);
    	$commercial = $row_users['first_name'];
    } else {
      $commercial = "-";
    }

    switch($row_orders['agency_id']) {
    	case 1: $agency = "Paris"; break;
      case 2: $agency = "Bordeaux"; break;
      default: $agency = "-"; break;
    }

    $box_type = $row_orders['box_type'];
    $result_bornes = mysqli_query($conn, "SELECT * FROM `bornes` WHERE `order_id` = ".$row_orders['id']);
    while($row_bornes = mysqli_fetch_assoc($result_bornes)) {
      $box_typ .= ', '.$row_bornes['box_type'];
    }

    $templates = "";
    $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']);
    while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
      $templates .= '<br /><a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120').'" alt="" /></a><a href="#" class="btn btn-danger btn-icon btn-circle btn-sm delete-image" data-id="'.$row_orders_images['id'].'" title="Supprimer le template"><i class="fa fa-close"></i></a>';
    }
    $result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `order_id` = ".$row_orders['id']);
    if (mysqli_num_rows($result_template_images) > 0) {
      $templates .= "<br /><small>";
    	$j = 1;
      while($row_template_images = mysqli_fetch_assoc($result_template_images)) {
        $templates .= '<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_template_images['image'].'">'.$j.'</a>';
        if($j < mysqli_num_rows($result_template_images)) {
          $templates .= ", ";
        }
        $j++;
      }
      $templates .= "</small>";
    }
    if ($row_orders['without_photo_frame'] == 1) {
      $templates .= "<br />Sans cadre photo";
    }

    $refuse = "";
    if ($_GET['status'] == 0) {
      $refuse .= 'a href="javascript:void()" class="btn btn-warning btn-icon btn-circle btn-sm" title="Refusé" onClick="refuseOrder('.$row_orders['id'].');"><i class="fa fa-close"></i></a>';
    }
    if ($_GET['status'] == -1) {
      $refuse = $row_orders['refuse_title'];
    }


  	$actions = '<a href="#" class="btn '.($row_orders['info'] == "" ? "btn-default" : "btn-confirm").' btn-icon btn-circle btn-sm info" data-id="'.$row_orders['id'].'" data-value="'.$row_orders['info'].'" title="Info"><i class="fa fa-info"></i></a>';
		if ($_GET['status'] == 2) {
			$actions .= '<a href="#" class="btn '.($row_orders['send_mail'] == 0 ? 'btn-primary' : 'btn-success').' btn-icon btn-circle btn-sm send-sms" data-id="'.$row_orders['id'].'" title="SMS photo ftp"><i class="fa fa-paper-plane-o"></i></a>&nbsp;';
 		}
		if ($_GET['status'] == 2) {
			$actions .= '<a href="#" class="btn '.($row_orders['send_mail'] == 0 ? 'btn-primary' : 'btn-success').' btn-icon btn-circle btn-sm send-mail" data-id="'.$row_orders['id'].'" title="Mail photo ftp"><i class="fa fa-envelope-o"></i></a>&nbsp;';
		}
		if ($row_orders['image'] != "") {
			$actions .= '<a href="#" class="btn btn-success btn-icon btn-circle btn-sm eraser" data-id="'.$row_orders['id'].'" title="Supprimer le template"><i class="fa fa-eraser"></i></a>&nbsp;';
		}
		$actions .= '<a href="mail.php?order_id='.$row_orders['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Mail HTML" target="_blank"><i class="fa fa-envelope-o"></i></a>&nbsp;<a href="add_order.php?order_id='.$row_orders['id'].'" class="btn btn-info btn-icon btn-circle btn-sm" title="Copie"><i class="fa fa-copy"></i></a><a href="edit_order.php?order_id='.$row_orders['id'].'&status='.mysqli_real_escape_string($conn, $_GET['status']).(isset($_GET['arch']) ? '&arch=true' : '').'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>&nbsp;<a class="btn btn-inverse btn-icon btn-circle btn-sm" title="Erreur" onClick="errorOrder('.$row_orders['id'].');"><i class="fa fa-flash"></i></a>&nbsp;<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Supprimer" onClick="deleteOrder('.$row_orders['id'].');"><i class="fa fa-close"></i></a>';


		$data[] = array(
			'id' => $row_orders['id'],
			'facteur' => ($row_orders['status'] == 2 ? '<a href="javascript:void(0)" class="btn '.($row_orders['facteur'] == 0 ? 'btn-default' : 'btn-danger').' btn-icon btn-circle facteur'.$row_orders['id'].'" title="Facteur" onClick="Facteur('.$row_orders['id'].')""><i class="fa fa-check-square"></i></a>' : ''),
			'invite' => ($row_orders['invite'] == 1 ? 'CLIENT' : (($row_orders['invite'] != 1 && trim($row_orders['gclid']) != "") ? 'ADS' : 'SEO')),
			'commercial' => $commercial,
			'agency' => $agency,
			'date' => date("d.m.Y", $row_orders['created_at']),
			'customer' => ($row_orders['societe'] ? '<b>'. $row_orders['societe']. '</b><br />' : '' ).'<p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>'.($row_orders['event_place'] != '' ? 'Lieu de l’évènement : <b>'.$row_orders['event_place'].'</b><br />' : '').($row_orders['event_type'] != '' ? 'Type d’événement : <b>'.$row_orders['event_type'].'</b>' : ''),
			'email' => '<a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a>',
			'phone' => '<a href="tel:'.$row_orders['phone'].'" title="Envoyer un e-mail">'.$row_orders['phone'].'</a>',
			'box_type' => $box_type,
			'select_type' => $row_orders['select_type'],
			'event_date' => $row_orders['event_date'],
			'return_date' => $row_orders['return_date'],
			'relaunch' => '<a href="#" class="btn '.($row_orders['relaunch'] == 0 ? 'btn-default' : 'btn-success').' btn-icon btn-circle relaunch" data-id="'.$row_orders['id'].'" title="Relance"><i class="fa fa-refresh"></i></a>',
			'jx' => (floor((strtotime($row_orders['event_date']) - time())/(3600*24)) > 0 ? floor((strtotime($row_orders['event_date']) - time())/(3600*24)) : "-"),
			'delivery' => ((mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison'),
			'selected_options' => str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", Livraison", "", str_replace(", Retrait boutique", "", $row_orders['selected_options'])))),
			'tarif_ht' => ($row_orders['select_type'] == 'Une entreprise' ? number_format(str_replace(",", ".", $row_orders['total']), 2, '.', '') : number_format(str_replace(",", ".", ($row_orders['total']/1.2)), 2, ',', '')).'€',
			'tarif_ttc' => ($row_orders['select_type'] == 'Une entreprise' ? number_format(str_replace(",", ".", ($row_orders['total'] + $row_orders['total']*0.2)), 2, '.', '') : number_format(str_replace(",", ".", ($row_orders['total'])), 2, ',', '')).'€',
			'discount' => ($row_orders['discount'] != 0 ? $row_orders['discount'].'€' : ''),
			'code_promo' => $row_orders['promocode'],
			'devis' => ($row_orders['status'] == 2 ? ($row_orders['devis'] != 0 ? '<a href="to_pdf.php?order_id='.$row_orders['id'].'&devis='.$row_orders['devis'].'" title="Devis" target="_blank">'."DE".$row_orders['devis'].'</a>' : '').'
                <a href="#" class="btn '.($row_orders['to_mail'] == 0 ? 'btn-default' : 'btn-success').' btn-icon btn-circle to-mail" data-id="'.$row_orders['id'].'" title="Mail"><i class="fa fa-envelope-o"></i></a>' : ''),
			'templates' => ($row_orders['image'] != "" ? '<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120').'" alt="" /></a>
                  <a href="be/?image='.$row_orders['image'].'&order_id='.$row_orders['id'].'&data='.$row_orders['data'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier Template"><i class="fa fa-edit"></i></a>' : '').$templates,
			'facture' => '<a href="to_pdf.php?order_id='.$row_orders['id'].'" title="Facture" target="_blank">'.$row_orders['num_id'].'</a>'.($row_orders['status'] == 2 ? '<br /><a href="to_pdf.php?order_id='.$row_orders['id'].'&refund=true" class="btn btn-danger btn-icon btn-circle btn-sm" title="Remboursement" target="_blank"><i class="fa fa-close"></i></a>' : ''),
			'refuse' => $refuse,
			'actions' => $actions
		);
		$totalRecordwithFilter++;
	}
}

$response = array(
	"draw" => intval($draw),
	"iTotalRecords" => $totalRecordwithFilter,
	"iTotalDisplayRecords" => $totalRecords,
	"aaData" => $data,
	"total" => number_format($total, 2, '.', '')."€",
	"ids" => trim($ids, ",")
);

echo json_encode($response);

@mysqli_close($conn);

?>