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
	'Data'=>'string'
);

$writer->writeSheetHeader('Sheet1', $header);

if (isset($_GET['type_id'])) {
  $rq = " AND `type_id` = ".mysqli_real_escape_string($conn, $_GET['type_id']);
} else {
  $rq = "";
}

$result_orders_data = mysqli_query($conn, "SELECT DISTINCT(data) FROM `orders_data` WHERE `order_id` = ".mysqli_real_escape_string($conn, $_GET['order_id']).$rq);
while($row_orders_data = mysqli_fetch_assoc($result_orders_data)) {
	$writer->writeSheetRow('Sheet1',
		array(
			$row_orders_data['data'],
		)
	);
}



@mysqli_close($conn);

$writer->writeToFile("tmp.xlsx");

header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=list_".$_GET['order_id'].".xlsx");
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

echo file_get_contents("tmp.xlsx");
unlink("tmp.xlsx");


?>