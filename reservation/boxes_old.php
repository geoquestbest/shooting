<?php
@session_start();
  if (isset($_GET['type']) && strtolower($_GET['type']) == 'entreprise') {
    $price_prefix = 'e';
    $price_sufix = 'HT';
  } else {
    $price_prefix = '';
    $price_sufix = 'TTC';
  }
  @require_once("../inc/mainfile.php");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css?v=1.1.7">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <title>shootnbox</title>

    <style>
        :root{
            --gradient: linear-gradient(#da3a8d 0%, #fff 100%);
        }

        <?php
            $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types`");
            while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
        ?>

        :root:has(body.box<?php echo $row_bornes_types['id']; ?>) {
            --color-accent: <?php echo $row_bornes_types['color']; ?>;
            --gradient: linear-gradient(<?php echo $row_bornes_types['color']; ?> 0%, #fff 100%);
        }

        <?php
            }
        ?>
    </style>
</head>
<body class="accent-green">
    <div class="wrapper">
        <header class="header">
            <img src="img/logo.svg" alt="shootnbox logo">
        </header>
        <main class="main">
            <section class="main-section">
                <div class="container form-page__wrapper">
                    <div class="form-page__content form-page__confirm js-form-tab active">
                        <div class="form-page__header-wrapper">
                            <h2 class="title bg-title">Sélectionnez le photobooth de votre évènement</h2>
                        </div>
                        <div class="flex-wrapper">
                            <?php
                            $find = 0;
                              $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types`");
                              while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
                                $department_arr = explode(" ", $row_bornes_types['department']);
                                if (in_array(substr($_COOKIE['postcode'], 0, 2), $department_arr)) {
                            ?>
                            <div class="confirm__content-wrapper" style="--color-accent: <?php echo $row_bornes_types['color']; ?>; --gradient: linear-gradient(<?php echo $row_bornes_types['color']; ?> 0%, #fff 100%);">
                                <div class="border-item">
                                    <div class="confirm__content-inner">
                                        <p class="item-title"><?php echo $row_bornes_types['title']; ?></p>
                                        <img class="img-inner" src="<?php echo ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image']; ?>" alt="">
                                    </div>
                                    <!--p class="desc">Inclus dans la formule :</p-->
                                    <div class="flex-col">
                                      <?php
                                        $descriptions_arr = explode("|||", $row_bornes_types['description']);
                                        for ($j = 0; $j < count($descriptions_arr) - 1; $j++){
                                            for ($i = 0; $i < count($descriptions_arr) - $j - 1; $i++){
                                                // если текущий элемент больше следующего
                                                $description_arr = explode("||", $descriptions_arr[$i]);
                                                $description_arr2 = explode("||", $descriptions_arr[$i + 1]);
                                                if ($description_arr[2] > $description_arr2[2]){
                                                    // меняем местами элементы
                                                    $tmp_var = $descriptions_arr[$i + 1];
                                                    $descriptions_arr[$i + 1] = $descriptions_arr[$i];
                                                    $descriptions_arr[$i] = $tmp_var;
                                                }
                                            }
                                        }
                                        foreach ($descriptions_arr as $key => $value) {
                                          $description_arr = explode("||", $value);
                                          echo'<div class="flex-col-item">
                                            <img src="'.$description_arr[0].'" alt="">
                                            <p>'.$description_arr[1].'</p>
                                          </div>';
                                        }
                                      ?>
                                    </div>
                                </div>
                                <div class="confirm__button-wrapper">
                                    <?php
                                        $prices_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
                                    ?>
                                    <h2 class="title"><?php echo $prices_arr[0].'€ '.$price_sufix; ?></h2>
                                    <a href="reservation.php?type=<?php echo $_GET['type']; ?>&box_type=<?php echo $row_bornes_types['id']; ?>" class="button text-large">SÉLECTIONNER</a>
                                </div>
                            </div>

                            <?php
                                    $find = 1;
                                }
                              }

                              if ($find == 0) {

                                $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `department` LIKE '%00%'");
                                while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {

                            ?>
                                <div class="confirm__content-wrapper" style="--color-accent: <?php echo $row_bornes_types['color']; ?>; --gradient: linear-gradient(<?php echo $row_bornes_types['color']; ?> 0%, #fff 100%);">
                                <div class="confirm__content-inner">
                                    <p class="item-title"><?php echo $row_bornes_types['title']; ?></p>
                                    <img class="img-inner" src="<?php echo ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image']; ?>" alt="">
                                    <svg class="bg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="297.913" height="382.964" viewBox="0 0 297.913 382.964">
                                        <defs>
                                          <linearGradient id="linear-gradient-box<?php echo $row_bornes_types['id']; ?>" y1="0.5" x2="1" y2="0.5" gradientUnits="objectBoundingBox">
                                            <stop offset="0" stop-color="<?php echo $row_bornes_types['color']; ?>"/>
                                            <stop offset="1" stop-color="<?php echo adjustBrightness($row_bornes_types['color'], 0.5); ?>" />
                                          </linearGradient>
                                        </defs>
                                        <path id="Контур_292" data-name="Контур 292" d="M7.889,34.1H292.114c3.78,0,6.844,4.233,6.844,9.454V399.528c0,1-.588,1.815-1.314,1.815,0,0-29.617,15.721-65.611,15.721S156.4,401.343,156.4,401.343s-45.7-8.858-69.965-9.417S1.8,405.494,1.8,405.494c0,1.061.091-8.25.091-13.471L1.045,43.554C1.045,38.333,4.109,34.1,7.889,34.1Z" transform="translate(-1.045 -34.1)" fill="url(#linear-gradient-box<?php echo $row_bornes_types['id']; ?>)"/>
                                    </svg>
                                </div>
                                <p class="desc">Inclus dans la formule :</p>
                                <div class="flex-col">
                                  <?php
                                    $descriptions_arr = explode("|||", $row_bornes_types['description']);
                                    foreach ($descriptions_arr as $key => $value) {
                                      $description_arr = explode("||", $value);
                                      echo'<div class="flex-col-item">
                                        <img src="'.$description_arr[0].'" alt="">
                                        <p>'.$description_arr[1].'</p>
                                      </div>';
                                    }
                                  ?>
                                </div>
                                <div class="confirm__button-wrapper">
                                    <?php
                                        $prices_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
                                    ?>
                                    <h2 class="title"><?php echo $prices_arr[0].'€ '.$price_sufix; ?></h2>
                                    <a href="reservation.php?type=<?php echo $_GET['type']; ?>&box_type=<?php echo $row_bornes_types['id']; ?>" class="button text-large">SÉLECTIONNER</a>
                                </div>
                            </div>

                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>              
        </main>
    </div>
    
    <script src="js/main.js?v=1.1.5"></script>
    <script src="js/phone-mask.js"></script>
</body>
</html>
<?php
  mysqli_close($conn);
  function adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}
?>