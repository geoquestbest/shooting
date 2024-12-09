<?php
@session_start();

if (!isset($_SESSION['user']) && $_SESSION['user']['role'] != 1) {
  header("Location: /");
  exit;
}

@require_once("../inc/mainfile.php");

set_include_path( get_include_path().PATH_SEPARATOR."..");
include_once("xlsxwriter.class.php");
$writer = new XLSXWriter();

$header = array(
  'ID'=>'string',
	'Data'=>'string',
);

$writer->writeSheetHeader('Sheet1', $header);

$rq = "WHERE `status` = 2";

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
  $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
  $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
} else {
  $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
  $end_date = date("d.m.Y", strtotime("31.12.".(date("Y", time()) + 2)));
}

if (isset($_GET['type_id'])) {
  $rq2 = " AND `type_id` = ".mysqli_real_escape_string($conn, $_GET['type_id']);
} else {
  $rq2 = "";
}

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` $rq ORDER BY `id` DESC");
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  $result_orders_data = mysqli_query($conn, "SELECT DISTINCT(data) FROM `orders_data` WHERE `order_id` = ".$row_orders['id'].$rq2);
  if (mysqli_num_rows($result_orders_data) > 0 && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0 && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) {
    while($row_orders_data = mysqli_fetch_assoc($result_orders_data)) {
    	$writer->writeSheetRow('Sheet1',
    		array(
          $row_orders['id'],
    			$row_orders_data['data'],
    		)
    	);
    }
  }
}



@mysqli_close($conn);

$writer->writeToFile("tmp.xlsx");

header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=list_".$start_date."-".$end_date.".xlsx");
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

echo file_get_contents("tmp.xlsx");
unlink("tmp.xlsx");


?>