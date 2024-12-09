<?php
$cfg = array();
$cfg['xmlclient'] = FALSE;
$cfg['doctypeid'] = "<!DOCTYPE html>";
$contenttype = ($cfg['doctypeid']>2 && $cfg['xmlclient']) ? 'application/xhtml+xml' : 'text/html; charset=utf-8';
@header('Content-Type: '.$contenttype);


@require_once("../inc/mainfile.php");
/*$result_orders = mysqli_query($conn, "SELECT DISTINCT `email` FROM `orders_new`");
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  if (strpos(mb_strtolower($row_orders['email']), "@gmail.") === false && strpos(mb_strtolower($row_orders['email']), "@hotmail.") === false && strpos(mb_strtolower($row_orders['email']), "@yahoo.") === false && strpos(mb_strtolower($row_orders['email']), "@outlook.") === false && strpos(mb_strtolower($row_orders['email']), "@icloud.") === false && strpos(mb_strtolower($row_orders['email']), "@msn.") === false && strpos(mb_strtolower($row_orders['email']), "@free.") === false && strpos(mb_strtolower($row_orders['email']), "@live.") === false && strpos(mb_strtolower($row_orders['email']), "@orange.") === false && strpos(mb_strtolower($row_orders['email']), "@wanadoo.") === false && strpos(mb_strtolower($row_orders['email']), "@sfr.") === false && strpos(mb_strtolower($row_orders['email']), "@laposte.") === false) {
    echo $row_orders['email']."<br />";
  }
}*/

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `template_id` != 0 AND `data` = ''");
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  $result_orders2 = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `template_id` = ".$row_orders['template_id']." AND `data` != ''");
  if (mysqli_num_rows($result_orders2) != 0) {
    $row_orders2 = mysqli_fetch_assoc($result_orders2);
    mysqli_query($conn, "UPDATE `orders_new` SET `data` = '".$row_orders2['data']."' WHERE `template_id` = ".$row_orders['template_id']." AND `data` = ''") or die(mysqli_error($conn));
    echo $row_orders['id']."<br />";
  }
}
@mysqli_close();
echo"done";
?>
