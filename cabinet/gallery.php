<?php
  @session_start();
  if (!isset($_SESSION['order'])) { header("Location: login.php"); exit; }
  @require_once("../inc/mainfile.php");
  $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  $row_orders = mysqli_fetch_assoc($result_orders);
  $_SESSION['order']['album'] = $row_orders['num_id'];
  $delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Votre galerie photo</title>
    <link rel="stylesheet" type="text/css" href="./css/index.min.css" />
  </head>
  <body>
    <header class="header header--auth">
      <div class="container">
        <a href="#" class="header-logo">
          <img src="./img/header/logo.svg" alt="header logo" />
        </a>

        <a href="./?event=logout" class="header-logout">
          Déconnexion
          <img src="./img/header/logout.svg" alt="header logout" />
        </a>
      </div>
    </header>

    <div class="container">
      <div class="wraper">
        <?php include('sidebar.php'); ?>
        <main class="main">
          <section class="gallery">
            <div class="gallery-container">
              <h1 class="gallery-title">Votre galerie photo</h1>

              <img
                class="gallery-svg"
                src="./img/gallery/trait.svg"
                alt="trait"
              />

              <?php
                $files = scandir("../uploads/".$row_orders['num_id']."/");
                if (count($files) > 2 && $row_orders['gallery_access'] == 1) {
                  echo'<br /><p class="gallery-description">
                    <span class="description-bold description-br">
                      Votre lien vers <a href="../album/templates.php" target="_blank" style="border-bottom: 1px dotted;"> l\'album photo</a>.
                    </span>
                  </p>';
                } else {
              ?>

              <p class="gallery-description">
                <span class="description-bold description-br">
                  Votre galerie photo sera bientôt disponible.
                </span>
                <span class="description-br">
                  Cela peut prendre jusqu’à 10 jours après votre évènement.
                </span>
                <span class="description-br">
                  Vous serez notifié par mail lorsque vos photos seront en
                  lignes.
                </span>
              </p>

              <img
                class="gallery-img"
                src="./img/gallery/time.svg"
                alt="time"
              />

            <?php } ?>

              <!--p class="gallery-warning">
                Camille et moi ne savons pas à quoi va ressembler cette page une
                fois la galerie disponible. Les photos seront directement dessus
                ? Ça apparaitra sous forme de lien ?
              </p-->
            </div>
          </section>
        </main>
      </div>
    </div>

    <script type="text/javascript" src="./js/app.js"></script>
  </body>
</html>
