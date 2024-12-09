<?php
  @session_start();
  if (isset($_GET['event']) && $_GET['event'] == "logout") { unset($_SESSION['order']); }
  if (!isset($_SESSION['order'])) { header("Location: login.php"); exit; }
  @require_once("../inc/mainfile.php");
  $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  $row_orders = mysqli_fetch_assoc($result_orders);
  $delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bienvenue</title>
    <link rel="stylesheet" type="text/css" href="./css/index.min.css?v=1.0" />
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
          <section class="bienvenue">
            <div class="bienvenue-container">
              <h1 class="bienvenue-title">Bienvenue</h1>
              <p class="bienvenue-text">
                sur votre espace client <span></span>
              </p>
              <img
                class="bienvenue-img"
                src="./img/bienvenue/trait.svg"
                alt="trait"
              />
              <p class="bienvenue-description">
                Cet espace vous permet de remplir toutes les informations
                nécessaires au bon déroulement de votre évènement mais également
                d’accéder à tous les éléments dont vous auriez besoin.
              </p>

              <div class="bienvenue-items">
                <div class="bienvenue-item">
                  <p class="item-text">Nom de la réservation</p>
                  <h2 class="item-title"><?php echo ($row_orders['societe'] != "" ? $row_orders['societe'] : $row_orders['last_name']." ".$row_orders['first_name']) ?></h2>
                </div>
                <div class="bienvenue-item">
                  <p class="item-text">Votre borne</p>
                  <h2 class="item-title">
                    <?php
                      echo ($row_orders['amount'] > 1 ? $row_orders['amount']." " : "");
                      switch($row_orders['box_type']) {
                        case "Ring":
                        case "Ring_promotionnel":
                          echo "Ring";
                        break;
                        case "Vegas":
                        case "Vegas_800":
                        case "Vegas_1200":
                        case "Vegas_1600":
                        case "Vegas_2000":
                          echo "Vegas";
                        break;
                        case "Miroir":
                        case "Miroir_800":
                        case "Miroir_1200":
                        case "Miroir_1600":
                        case "Miroir_2000":
                          echo "Miroir";
                        break;
                        case "Spinner_360":
                          echo "Spinner 360";
                        break;
                        case "Réalité_Virtuelle":
                          echo "Réalité Virtuelle";
                        break;
                      }

                      $result_bornes = mysqli_query($conn, "SELECT * FROM `bornes` WHERE `order_id` = ".$row_orders['id']);
                      while($row_bornes = mysqli_fetch_assoc($result_bornes)) {
                        echo"/";
                        echo ($row_bornes['amount'] > 1 ? $row_bornes['amount']." " : "");
                        switch($row_bornes['box_type']) {
                          case "Ring":
                          case "Ring_promotionnel":
                            echo "Ring";
                          break;
                          case "Vegas":
                          case "Vegas_800":
                          case "Vegas_1200":
                          case "Vegas_1600":
                          case "Vegas_2000":
                            echo "Vegas";
                          break;
                          case "Miroir":
                          case "Miroir_800":
                          case "Miroir_1200":
                          case "Miroir_1600":
                          case "Miroir_2000":
                            echo "Miroir";
                          break;
                          case "Spinner_360":
                            echo "Spinner 360";
                          break;
                          case "Réalité_Virtuelle":
                            echo "Réalité Virtuelle";
                          break;
                        }
                      }
                    ?>
                  </h2>
                </div>
                <div class="bienvenue-item">
                  <p class="item-text">Date de l’évènement</p>
                  <h2 class="item-title"><?php echo $row_orders['event_date'] ?></h2>
                </div>
                <div class="bienvenue-item">
                  <p class="item-text">Contact client</p>
                  <a href="tel:0606060606" class="item-phone">
                    <img src="./img/bienvenue/phone.svg" alt="phone icon" />
                    <span><?php echo $row_orders['phone'] ?></span>
                  </a>
                  <a href="mailto:camille@mail.fr" class="item-email">
                    <img src="./img/bienvenue/email.svg" alt="email icon" />
                    <?php echo $row_orders['email'] ?>
                  </a>
                </div>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>

    <!-- <main class="main bienvenue-main">
      <section class="bienvenue bienvenue--start">
        <div class="bienvenue-container">
          <h1 class="bienvenue-title">Bienvenue</h1>
          <p class="bienvenue-text">sur votre outil de création en ligne</p>

          <img
            class="bienvenue-img"
            src="./img/bienvenue/trait.svg"
            alt="trait"
          />
          <p class="bienvenue-description">
            Sur cet outil, vous pourrez laisser parler votre créativité et
            réaliser le contour idéal pour votre évènement !
          </p>

          <button class="bienvenue-btn">
            Créer à partir d’un modèle vierge
          </button>
          <button class="bienvenue-btn">
            Créer à partir de modèles existants
          </button>
        </div>
      </section>
    </main> -->

    <script type="text/javascript" src="./js/app.js"></script>
  </body>
</html>
