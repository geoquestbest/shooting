<?php
@header('Content-Type: text/html; charset=utf-8');

@require_once("inc/mainfile.php");


$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `data` != ''");
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  echo "<b>№".$row_orders['id']." - ".$row_orders['event_date']."</b><br/>";
  echo base64_decode($row_orders['data']);
  echo"<br/><br/>";
}



?>