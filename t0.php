<?php
$exist = true;
while ($exist) {
  $short = preg_replace( "/[^a-zA-Z\s]/", '', base64_encode(random_bytes(4)));
  $result_short_links = mysqli_query($conn, "SELECT `id` FROM `short_links` WHERE `short` = '$short'");
  $exist = mysqli_num_rows($result_short_links) != 0;
}
echo $short;
?>