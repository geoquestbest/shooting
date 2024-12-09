<?php
@require_once("inc/mainfile.php");
$hash = mysqli_real_escape_string($conn, $_GET['hash']);
$result_short_links = mysqli_query($conn, "SELECT `hash` FROM `short_links` WHERE `short` = '$hash'");
if (mysqli_num_rows($result_short_links) > 0) {
  $row_short_links = mysqli_fetch_assoc($result_short_links);
  mysqli_close($conn);
  @header("Location: https://shootnbox.fr/view/?hash=".$row_short_links['hash']);
} else {
  mysqli_close($conn);
  @header("Location: https://shootnbox.fr/");
}

?>