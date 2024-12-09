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
  'NUMERO DE COMPTE'=>'string',
	'NUMERO FACTURE'=>'string',
  'MONTANT FACTURE'=>'string',
  'TYPE DOC'=>'string',
  'DATE DOCUMENT'=>'string',
  'ECHEANCE'=>'string',
  'MODE DE PAIEMENT'=>'string',
);

$writer->writeSheetHeader('Sheet1', $header);

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` IN (".mysqli_real_escape_string($conn, $_GET['orders_ids']).") ORDER BY `id` DESC");
while($row_orders = mysqli_fetch_assoc($result_orders)) {
 	$writer->writeSheetRow('Sheet1',
 		array(
      $row_orders['id'],
 			$row_orders['num_id'],
      ($row_orders['select_type'] == 'Une entreprise' ? number_format(str_replace(",", ".", ($row_orders['total'] + $row_orders['total']*0.2)), 2, '.', '') : number_format(str_replace(",", ".", ($row_orders['total'])), 2, ',', '')),
      "FA",
      $row_orders['event_date'],
      date("d.m.Y", strtotime($row_orders['event_date']) + 30*24*3600),
      "VR"
 		)
 	);
}



@mysqli_close($conn);

$writer->writeToFile("tmp.xlsx");

header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=factor_".date("dmY_Hi").".xlsx");
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

echo file_get_contents("tmp.xlsx");
unlink("tmp.xlsx");


?>