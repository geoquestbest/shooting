<?php
$ids = [727, 1091, 667, 669, 665, 715, 681, 695, 689, 2274, 1991, 2561, 666, 711];
foreach ($ids as $key => $value) {
  echo $value;
  $data = file_get_contents("https://www.owayo.fr/konfigurator_php/stocklogos/getLogoByID.php?id=".$value);
  file_put_contents($value.'.zip', $data);
}

?>