<?php
@require_once("../inc/mainfile.php");

$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new`");
while($row_orders = mysqli_fetch_assoc($result_orders)) {
  if ($row_orders['selected_options'] == "")
    mysqli_query($conn, "UPDATE `orders_new` SET `selected_options` = '".str_replace("Je choisis sur catalogue:1:,", "Je choisis sur catalogue:1:0,", $row_orders['selected_options'])."' WHERE `id` = ".$row_orders['id']) or die(mysqli_error($conn));
  }
}
echo "done";
mysqli_close($conn);
?>
