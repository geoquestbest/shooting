<?php
  @session_start();
  if (!isset($_SESSION['order'])) { header("Location: login.php"); exit; }
  @require_once("../inc/mainfile.php");
  foreach ($_GET as $key => $value){if ($key != "submit") {$$key = mysqli_real_escape_string($conn, $value);}}
  if ($event == "unvalider") {
    if (!isset($id)) {
        mysqli_query($conn, "UPDATE `orders_new` SET  `template_status` = 0  WHERE `id` = ".$_SESSION['order']['id']);
    } else {
        mysqli_query($conn, "UPDATE `orders_images` SET  `template_status` = 0  WHERE `id` = ".$id);
    }
    header("Location: contour_photo.php");
    exit;
  }

  $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  $row_orders = mysqli_fetch_assoc($result_orders);

  $have_made = (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'un graphiste réalise mon contour') !== false || mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je réalise mon propre contour photo') !== false || mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je fais réaliser sur-mesure') !== false || strpos($row_orders['select_type'], 'entreprise') !== false || $row_options['template'] == 1);
  $personal = (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'contour personnalisé') !== false || mb_strpos(strtolower(trim($row_orders['selected_options'])), 'réaliser sur-mesure') !== false || $row_options['designer'] == 1);

  $delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') !== false || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
  $catalog = (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je choisis sur catalogue') !== false  || $row_orders['selected_options'] == "");
  $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']); // AND `template_status` = 1
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contour photo</title>
    <link rel="stylesheet" type="text/css" href="./css/index.min.css?v=1.4.1" />
    <link href="./dropzone/css/basic.css" rel="stylesheet">
    <link href="./dropzone/css/dropzone.css?v=0.2" rel="stylesheet">
    <style>
      .flex-ou {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 0 30px;
      }

      .flex-ou-col {
        flex-direction: column;
        gap: 10px;
      }

      .hidden {
        display: none!important;
      }

      .dropzone {
        min-height: auto;
        border: none;
        border-radius: 0;
        background: #f9f7f4;
        padding: 0;
      }

      .dropzone .dz-preview.dz-image-preview {
        width: 100%;
        margin-left: -5px;
        background: none;
      }

      .dz-image {
        margin: auto;
      }
      .dropzone.dz-started .dz-message {
        display: block !important;
      }

      .delete-btn {
        position: absolute;
        height: 25px;
        width: 25px;
        top: 0;
        right: 15px;
        display: block;
        color: #fff;
        background: #4dad58;
        text-align: center;
        border-radius: 50%;
        line-height: 25px;
        z-index: 100;
        font-weight: bold;
        cursor: pointer!important;
      }

       .form-field.form-field__checkboxes, .wraper-popup--active {
          display: -webkit-box !important;
          display: -ms-flexbox !important;
          display: flex !important;
      }

      .wraper-popup .popup-title {
        color: #4dad58;
      }

      .wraper-popup .popup-btn {
        background-color: #4dad58;
      }
      <?php if ($have_made !== false && strpos($row_orders['select_type'], 'entreprise') !== false) { ?>
        .contour.contour--load .form-field__checkbox {
          margin: 0 200px 0 320px;
      }
      <?php }  else { ?>
        .contour.contour--load .form-field__checkbox {
          margin: 0 auto;
        }

    <?php } ?>

      .contour.contour--load .form-field__checkbox.nospace {
          margin: 0;
      }


      @media (max-width: 1240px) {
        .contour.contour--load .form-field__checkbox {
          margin: 0 auto;
        }
      }

      .required {
        color: #ff0200;
      }
    </style>
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
          <section class="contour <?php if ($personal === false || $row_orders['image'] != '') {echo " hidden";} ?>">
            <div class="contour-container">
              <h1 class="contour-title">Contour photo</h1>
              <img
                class="contour-img"
                src="./img/contour/trait.svg"
                alt="trait"
              />
              <p class="contour-description">
                Vous avez pris l’option « contour personnalisé », pouvez-vous
                donc s’il vous plaît
                <span class="description-bold">
                  nous fournir les éléments ci-dessous AVANT la deadline
                  annoncée,
                </span>
                sans quoi nos équipes ne seront pas en mesure d’assurer dans les
                temps la création de votre contour photo un contour blanc sera
                alors automatiquement appliqué. Dès réception de ces éléments la
                team graphisme vous enverra une maquette du contour de la photo
                pour validation.
              </p>

              <form class="form contour-form<?php if ($row_orders['gallery_valid'] == 1) {echo" form--file";} ?>">
                <input type="hidden" id="image" value="" />
                <div class="form-fields">
                  <div class="form-field">
                    <label for="mail" class="form-field__label">
                      Indiquer une adresse mail sur laquelle envoyer une proposition de contour <span class="required">*</span>
                    </label>
                    <input
                      type="email"
                      id="mail"
                      class="form-field__input"
                      placeholder="sylvie@outlook.com"
                      value="<?php echo $row_orders['email'] ?>"
                      required
                    />
                    <span class="form-field__error">Field required</span>
                  </div>

                  <div class="form-field">
                    <label for="text" class="form-field__label">
                      Texte à ajouter sur le contour <span class="required">*</span>
                    </label>
                    <input
                      type="text"
                      id="text"
                      class="form-field__input"
                      placeholder="Summer Party - 5 juillet 2024"
                      required
                    />
                  </div>

                  <div class="form-field">
                    <label for="typographies" class="form-field__label">
                      Nom des typographies
                    </label>
                    <input
                      type="text"
                      id="typographies"
                      class="form-field__input"
                      placeholder="Calibri Bold / Bebas"
                    />
                  </div>

                  <div class="form-field">
                    <label for="palmier" class="form-field__label">
                      Thématique de l'évènement <span class="required">*</span>
                    </label>

                    <textarea
                      id="palmier"
                      class="form-field__textarea"
                      placeholder="Je voudrais des palmier et un soleil sur le contour"
                      required
                    ></textarea>
                  </div>

                  <div class="form-field">
                    <label for="code" class="form-field__label">Couleurs</label>
                    <input
                      type="text"
                      class="form-field__input"
                      id="code"
                      placeholder="52e54d"
                    />
                  </div>

                  <div class="form-field">
                    <label for="autres" class="form-field__label">
                      Autres remarques
                    </label>
                    <textarea
                      id="autres"
                      class="form-field__textarea"
                    ></textarea>
                  </div>
                </div>
                <div class="form-valid">
                  <h3 class="valid-title<?php if ($row_orders['gallery_valid'] == 1) echo" hidden"; ?>">Importer des documents</h3>
                  <p class="valid-description<?php if ($row_orders['gallery_valid'] == 1) echo" hidden"; ?>">
                    Envoyer tous les éléments de votre choix (logo, charte,
                    image, inspirations…)
                  </p>

                  <div class="valid-formats<?php if ($row_orders['gallery_valid'] == 1) echo" hidden"; ?>">
                    <div class="dropzone" id="dropzoneForm">
                      <div class="fallback">
                        <input type="file" />
                      </div>
                    </div>
                  </div>

                  <?php
                    switch($row_orders['box_type']) {
                      case "Ring":
                      case "Ring_promotionnel":
                        echo '<a href="./Exemples contours/Exemples Contours Photos - Particulier - Ring.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                      break;
                      case "Vegas":
                      case "Vegas_800":
                      case "Vegas_1200":
                      case "Vegas_1600":
                      case "Vegas_2000":
                          echo '<a href="./Exemples contours/Exemples contour photo - Particuliers - Vegas.pdf" target="_blank">
                            <div class="contour-example">
                              <img
                                src="./img/contour/example.svg"
                                alt="example"
                                class="example-icon"
                              />
                              <h4 class="example-title">Exemple de contour photo</h4>
                            </div>
                          </a>';
                      break;
                      case "Miroir":
                      case "Miroir_800":
                      case "Miroir_1200":
                      case "Miroir_1600":
                      case "Miroir_2000":
                        echo '<a href="./Exemples contours/Exemples contours photo - Particuliers - Miroir.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                        break;
                        case "Spinner_360":
                        case "Réalité_Virtuelle":
                        break;
                      }
                  ?>



                  <button class="valid-btn<?php if ($row_orders['gallery_valid'] == 1) echo" hidden"; ?>" type="submit" id="gallery_valid" <?php if ($row_orders['gallery_valid'] == 1) {echo" disabled";} ?>>Valider</button>
                </div>
              </form>
            </div>

            <p class="text">
              <!--N’hésitez pas à demander team facturation pour toutes questions ou indications supplémentaires.-->N’hésitez pas à nous contacter au 07 56 87 31 78 pour toutes questions.
            </p>
          </section>

          <section class="contour contour--load hidden">
            <div class="contour-container">
              <h1 class="contour-title">Contour photo</h1>
              <img src="./img/contour/trait.svg" class="contour-img" alt="trait" />
              <p class="contour-description">
                <span class="description-line">
                  Vous trouverez ci-dessous nos gabarits téléchargeables ainsi
                  que des exemples de contours photo.
                </span>

                <span class="description-bold">
                  Merci de nous retourner votre création au format fichier
                  source à l’adresse mail:
                  <a class="description-link" href="#">
                    graphisme@shootnbox.fr
                  </a>
                </span>
                Sans retour de votre part
                <span class="description-bold">
                  avant la deadline annoncée,
                </span>
                nos équipes ne seront pas en mesure d’assurer dans les temps la
                création de votre contour photo. Un contour blanc sera alors
                automatiquement appliqué.
              </p>
            </div>

            <form class="form">
              <div class="form-field form-field__row">

                  <?php
                    switch($row_orders['box_type']) {
                      case "Ring":
                      case "Ring_promotionnel":
                        echo '<a class="contour-btn" href="./Consignes techniques/Ring - Consignes Techniques - Shootnbox.pdf" target="_blank">
                          <span class="btn-icon">
                            <img src="./img/contour/load.svg" alt="load" />
                          </span>
                          <span class="btn-text">
                            <span class="btn-bold">Télécharger le gabarit</span>
                            (suite Adobe & Canva)
                          </span>
                        </a>';
                      break;
                      case "Vegas":
                      case "Vegas_800":
                      case "Vegas_1200":
                      case "Vegas_1600":
                      case "Vegas_2000":
                        echo '<a class="contour-btn" href="./Consignes techniques/Vegas - Consignes Techniques - Shootnbox.pdf" target="_blank">
                          <span class="btn-icon">
                            <img src="./img/contour/load.svg" alt="load" />
                          </span>
                          <span class="btn-text">
                            <span class="btn-bold">Télécharger le gabarit</span>
                            (suite Adobe & Canva)
                          </span>
                        </a>';
                      break;
                      case "Miroir":
                      case "Miroir_800":
                      case "Miroir_1200":
                      case "Miroir_1600":
                      case "Miroir_2000":
                        echo '<a class="contour-btn" href="./Consignes techniques/Vegas - Consignes Techniques - Shootnbox.pdf" target="_blank">
                          <span class="btn-icon">
                            <img src="./img/contour/load.svg" alt="load" />
                          </span>
                          <span class="btn-text">
                            <span class="btn-bold">Télécharger le gabarit</span>
                            (suite Adobe & Canva)
                          </span>
                        </a>';
                      break;
                      case "Spinner_360":
                      case "Réalité_Virtuelle":
                      break;
                    }
                  ?>
                <div class="contour-line">ou</div>

                <div class="form-field__checkbox">
                  <input type="checkbox" id="photo" />
                  <span class="box"></span>
                  <label for="photo">
                    <span>Je ne veux pas de contour photo</span>
                    <span class="checkbox-bold">
                      Je veux ma photo en pleine page
                    </span>
                  </label>
                </div>
              </div>
            </form>



            <p class="text">
              <!--N’hésitez pas à demander team facturation pour toutes questions ou indications supplémentaires.-->N’hésitez pas à nous contacter au 07 56 87 31 78 pour toutes questions.
            </p>

          </section>

          <div class="wraper-popup wraper-popup--warning">
              <div class="popup">
                <img
                  src="./img/popup/close.svg"
                  alt="close"
                  class="popup-close" onClick="closePopup('.wraper-popup--warning')"
                />
                <h2 class="popup-title">Attention</h2>

                <img
                  src="./img/popup/contour-trait.svg"
                  alt="trait"
                  class="popup-img"
                />

                <p class="popup-description">
                  Êtes-vous sûr de vouloir votre
                  <span>photo pleine page sans contour photo ?</span>
                </p>
                <p class="popup-text">
                  <img src="./img/popup/warning.svg" alt="warning" />
                  En cas de question, appellez le 01 45 01 66 66
                </p>

                <button class="popup-btn popup-btn--active cancel">Retour</button>
                <button class="popup-btn done">Valider</button>
              </div>
            </div>

            <div class="wraper-popup wraper-popup--warning2">
              <div class="popup">
                <img
                  src="./img/popup/close.svg"
                  alt="close"
                  class="popup-close" onClick="closePopup('.wraper-popup--warning2')"
                />
                <h2 class="popup-title">Attention</h2>

                <img
                  src="./img/popup/contour-trait.svg"
                  alt="trait"
                  class="popup-img"
                />

                <p class="popup-description">
                  Une fois ces informations validées,
                  <span>elles ne sont plus modifiables.</span>
                </p>
                <p class="popup-text">
                  <img src="./img/popup/warning.svg" alt="warning" />
                  En cas de question, appellez le 01 45 01 66 66
                </p>

                <button class="popup-btn popup-btn--active" onClick="closePopup('.wraper-popup--warning2')">Retour</button>
                <button class="popup-btn done2">Valider</button>
              </div>
            </div>
          <section class="contour contour--visualize<?php if ($row_orders['image'] == '') {echo " hidden";} ?>">  <!--(!$catalog || !$personal) && $have_made -->
            <div class="contour-container">
              <h1 class="contour-title">Contour photo</h1>
              <img
                src="./img/contour/trait.svg"
                class="contour-img"
                alt="trait"
              />
              <p class="contour-description">
                Visualisez votre personnalisation de contour photo.
              </p>
              <a href="../templates/" target="_blank" class="contour-edit<?php echo (($row_orders['image'] != '' /* && $row_orders['template_status'] == 1*/) ? ' hidden' : '') ?>">
                <div class="contour-edit-inner">
                  <img src="img/contour/edit.svg" alt="">
                </div>
                <p class="contour-edit-text">Acceder a votre outil de creation en ligne</p>
              </a>

              <div class="visualize-items">
                <?php

                  if ($row_orders['image'] != '') { //  && $row_orders['template_status'] == 1
                    $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$row_orders['template_id']);
                    $row_templates = mysqli_fetch_assoc($result_templates);

                    echo'<div class="visualize-item visualize-item--gray">
                      <div class="item-wraper">
                        <div class="item-outblock">
                          <img class="item-block" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120').'" alt="" />
                        </div>
                      </div>
                      <a href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'" class="item-eye" target="_blank">
                        <img src="./img/contour/eye.svg" alt="contour-eye" />
                      </a>
                    </div>';
                  }

                  $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']); // ." AND `template_status` = 1"
                  while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
                    $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$row_orders_images['template_id']);
                    $row_templates = mysqli_fetch_assoc($result_templates);
                    echo'<div class="visualize-item visualize-item--gray">
                      <div class="item-wraper">
                        <div class="item-outblock">
                          <img class="item-block" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120').'" alt="" />
                        </div>
                      </div>
                      <a href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image'].'" class="item-eye" target="_blank">
                        <img src="./img/contour/eye.svg" alt="contour-eye" />
                      </a>
                    </div>';
                  }
              ?>

            </div>

            <p class="text">
              <!--N’hésitez pas à demander team facturation pour toutes questions ou indications supplémentaires.-->N’hésitez pas à nous contacter au 07 56 87 31 78 pour toutes questions.
            </p>
          </section>



          <section class="contour contour--load contour--ou <?php if (($have_made === false || $personal != false) && !$catalog) {echo " hidden";} ?>">
            <div class="contour-container">
              <h1 class="contour-title">Contour photo</h1>
              <img
                class="contour-img"
                src="./img/contour/trait.svg"
                alt="trait"
              />

              <p class="contour-description contour-description-remove<?php echo ($row_orders['without_photo_frame'] == 1 ? ' hidden' : ''); ?>">
                Vous pouvez accéder à votre outil en ligne et commencer la création de votre contour photo. Merci de respecter la deadline sinon nos équipes ne seront pas en mesure d’assurer dans les temps la création de votre contour photo. Un contour blanc sera alors automatiquement appliqué.<br />
                Merci de renvoyer votre fichier source (Adobe) ou Lien de collaboration (canva) sur l’adresse mail suivante : <a href="mailto:graphisme@shootnbox.fr" style="color: #4dad58;">graphisme@shootnbox.fr</a>
              </p>
            </div>


              <div class="form-field form-field__row">
                <div class="for-remove<?php echo ($row_orders['without_photo_frame'] == 1 ? ' hidden' : ''); ?>" style="max-width: 976px; width: 95%;">
                <div class="contour-items" style="margin-bottom: 30px;">
                  <?php if ($row_orders['image'] == "") {?>
                    <button class="contour-btn<?php echo (($have_made !== false && strpos($row_orders['select_type'], 'entreprise') !== false) ? ' hidden' : ''); ?>" style="margin-left: 45px;">
                      <span class="btn-icon">
                        <img src="./img/contour/edit.svg" alt="edit" />
                      </span>
                      <a href="../templates/" target="_blank">
                        <span class="btn-text">
                          <span class="btn-bold">Accéder à votre outil</span>
                          <span class="btn-bold">de création en ligne</span>
                        </span>
                      </a>
                    </button>

                    <div class="<?php echo (($have_made !== false && strpos($row_orders['select_type'], 'entreprise') !== false) ? '' : ' hidden'); ?>">
                    <?php if (strpos($row_orders['select_type'], 'entreprise') === false || strpos($row_orders['select_type'], 'entreprise') !== false) {
                    switch($row_orders['box_type']) {
                      case "Ring":
                      case "Ring_promotionnel":
                        echo '<a href="./Exemples contours/Exemples Contours Photos - Particulier - Ring.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                      break;
                      case "Vegas":
                      case "Vegas_800":
                      case "Vegas_1200":
                      case "Vegas_1600":
                      case "Vegas_2000":
                          echo '<a class="contour-btn" href="./Consignes techniques/Vegas - Contour Consignes Techniques - Shootnbox.pdf" target="_blank">
                            <span class="btn-icon">
                              <img src="./img/contour/load.svg" alt="load" />
                            </span>
                            <span class="btn-text">
                              <span class="btn-bold">Télécharger le gabarit</span>
                              (suite Adobe & Canva)
                            </span>
                          </a>';
                              break;
                      case "Miroir":
                      case "Miroir_800":
                      case "Miroir_1200":
                      case "Miroir_1600":
                      case "Miroir_2000":
                        echo '<a href="./Exemples contours/Exemples contours photo - Particuliers - Miroir.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                        break;
                        case "Spinner_360":
                        case "Réalité_Virtuelle":
                        break;
                      }


                   } ?>
                 </div>

                    <!--h2 class="contour-center">ou</h2>
                    <button class="contour-btn">
                      <span class="btn-icon">
                        <img src="./img/contour/load.svg" alt="load" />
                      </span>
                      <a href="../uploads/images/<?php echo $row_orders['image']; ?>" target="_blank">
                        <span class="btn-text">
                          <span class="btn-bold">Télécharger le gabarit</span>
                          (suite Adobe & Canva)
                        </span>
                      </a>
                    </button-->
                <?php } ?>

                </div>
                <!--
                <?php
                    switch($row_orders['box_type']) {
                      case "Ring":
                      case "Ring_promotionnel":
                        echo '<a href="./Exemples contours/Exemples Contours Photos - Particulier - Ring.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                      break;
                      case "Vegas":
                      case "Vegas_800":
                      case "Vegas_1200":
                      case "Vegas_1600":
                      case "Vegas_2000":
                        if (strpos($row_orders['select_type'], 'entreprise') === false) {
                          echo '<a href="./Exemples contours/Exemples contour photo - Particuliers - Vegas.pdf" target="_blank">
                            <div class="contour-example">
                              <img
                                src="./img/contour/example.svg"
                                alt="example"
                                class="example-icon"
                              />
                              <h4 class="example-title">Exemple de contour photo</h4>
                            </div>
                          </a>';
                        } else {
                          echo '<a class="contour-btn" href="./Consignes techniques/Vegas - Contour Consignes Techniques - Shootnbox.pdf" target="_blank">
                            <span class="btn-icon">
                              <img src="./img/contour/load.svg" alt="load" />
                            </span>
                            <span class="btn-text">
                              <span class="btn-bold">Télécharger le gabarit</span>
                              (suite Adobe & Canva)
                            </span>
                          </a>';
                        }
                      break;
                      case "Miroir":
                      case "Miroir_800":
                      case "Miroir_1200":
                      case "Miroir_1600":
                      case "Miroir_2000":
                        echo '<a href="./Exemples contours/Exemples contours photo - Particuliers - Miroir.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                        break;
                        case "Spinner_360":
                        case "Réalité_Virtuelle":
                        break;
                      }
                  ?>
                -->

                <div class="contour-line">ou</div>

                </div>
                <div class="flex-ou flex-ou-col">
                <div class="form-field__checkbox nospace">
                  <input type="checkbox" id="block--1" <?php echo ($row_orders['without_photo_frame'] == 1 ? 'checked disabled' : ''); ?> />
                  <span class="box"></span>
                  <label for="block--1">
                    <span>Je ne veux pas de contour photo</span>
                    <span class="checkbox-bold">
                      Je veux ma photo en pleine page
                    </span>
                  </label>
                </div>
                <div class="<?php echo (($have_made !== false && strpos($row_orders['select_type'], 'entreprise') !== false) ? 'hidden' : ''); ?>">
                <?php if (strpos($row_orders['select_type'], 'entreprise') === false || strpos($row_orders['select_type'], 'entreprise') !== false) {
                    switch($row_orders['box_type']) {
                      case "Ring":
                      case "Ring_promotionnel":
                        echo '<a href="./Exemples contours/Exemples Contours Photos - Particulier - Ring.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                      break;
                      case "Vegas":
                      case "Vegas_800":
                      case "Vegas_1200":
                      case "Vegas_1600":
                      case "Vegas_2000":
                          echo '<a class="contour-btn" href="./Consignes techniques/Vegas - Contour Consignes Techniques - Shootnbox.pdf" target="_blank">
                            <span class="btn-icon">
                              <img src="./img/contour/load.svg" alt="load" />
                            </span>
                            <span class="btn-text">
                              <span class="btn-bold">Télécharger le gabarit</span>
                              (suite Adobe & Canva)
                            </span>
                          </a>';
                              break;
                      case "Miroir":
                      case "Miroir_800":
                      case "Miroir_1200":
                      case "Miroir_1600":
                      case "Miroir_2000":
                        echo '<a href="./Exemples contours/Exemples contours photo - Particuliers - Miroir.pdf" target="_blank">
                          <div class="contour-example">
                            <img
                              src="./img/contour/example.svg"
                              alt="example"
                              class="example-icon"
                            />
                            <h4 class="example-title">Exemple de contour photo</h4>
                          </div>
                        </a>';
                        break;
                        case "Spinner_360":
                        case "Réalité_Virtuelle":
                        break;
                      }


                   } ?>
                 </div>
                </div>
              </div>


            <p class="text">
              <!--N’hésitez pas à demander team facturation pour toutes questions ou indications supplémentaires.-->N’hésitez pas à nous contacter au 07 56 87 31 78 pour toutes questions.
            </p>
          </section>

          <!--section class="contour contour--logo">
            <div class="contour-container">
              <h1 class="contour-title">Contour photo</h1>
              <img
                src="./img/contour/trait.svg"
                class="contour-img"
                alt="trait"
              />
              <p class="contour-description">
                Merci ! Vos informations ont bien été prises en compte, nos
                équipes reviennent vers vous dès que possible.
              </p>

              <form class="form">
                <div class="form-fields">
                  <div class="form-field  ">
                    <label for="mail" class="form-field__label">
                      Adresse mail sur laquelle envoyer le BAT*
                    </label>
                    <input
                      type="text"
                      id="mail"
                      disabled
                      class="form-field__input"
                      placeholder="camille@shootnbox.fr"
                    />
                    <span class="form-field__error">Required field</span>
                  </div>

                  <div class="form-field">
                    <label for="text" class="form-field__label">
                      Texte à ajouter sur le contour
                    </label>
                    <input
                      type="text"
                      id="text"
                      class="form-field__input"
                      placeholder="Summer Party - 5 juillet 2024"
                    />
                  </div>

                  <div class="form-field">
                    <label for="typographies" class="form-field__label">
                      Calibri Bold / Bebas
                    </label>
                    <input
                      type="text"
                      id="typographies"
                      class="form-field__input"
                      placeholder="Nom des typographies"
                    />
                  </div>

                  <div class="form-field">
                    <label for="palmier" class="form-field__label">
                      Thématique de l'évènement
                    </label>

                    <textarea
                      id="palmier"
                      class="form-field__textarea"
                      placeholder="Je voudrais des palmier et un soleil sur le contour"
                    ></textarea>
                  </div>

                  <div class="form-field">
                    <label for="code" class="form-field__label"> 52e54d </label>
                    <input
                      type="text"
                      class="form-field__input"
                      id="code"
                      placeholder="Code couleur"
                    />
                  </div>

                  <div class="form-field">
                    <label for="autres" class="form-field__label">
                      Autres remarques
                    </label>
                    <textarea
                      id="autres"
                      class="form-field__textarea"
                    ></textarea>
                  </div>
                </div>
                <div class="form-valid">
                  <div class="form-field">
                    <input
                      type="text"
                      id="logo"
                      class="form-field__input"
                      placeholder="Logo"
                    />
                  </div>
                  <div class="form-field">
                    <input
                      type="text"
                      id="graphique"
                      class="form-field__input"
                      placeholder="Charte graphique"
                    />
                  </div>
                  <div class="form-field">
                    <input
                      type="text"
                      id="fleur"
                      class="form-field__input"
                      placeholder="Visuel Fleur"
                    />
                  </div>

                  <h3 class="valid-title">Importer des documents</h3>
                  <p class="valid-description">
                    Envoyer tous les éléments de votre choix (logo, charte,
                    image, inspirations…)
                  </p>

                  <div class="valid-formats">
                    <img
                      class="formats-icon"
                      src="./img/contour/formats.svg"
                      alt="formats"
                    />
                  </div>

                  <div class="contour-example">
                    <img
                      src="./img/contour/example.svg"
                      alt="example"
                      class="example-icon"
                    />
                    <h4 class="example-title">Exemple de contour photo</h4>
                  </div>

                  <button class="valid-btn" type="submit">Valider</button>
                </div>
              </form>
            </div>


            <p class="text">
              <!--N’hésitez pas à demander team facturation pour toutes questions ou indications supplémentaires>N’hésitez pas à nous contacter au 07 56 87 31 78 pour toutes questions.
            </p>
          </section> -->
        </main>
    </div>

    <script src="./dropzone/js/dropzone.js"></script>

    <script type="text/javascript" src="./js/app.js"></script>
    <script type="text/javascript">
      const image = document.getElementById('image');

      Dropzone.options.dropzoneForm = {
        paramName: 'image',
        url: '../manager/d26386b04e.php',
        method: 'post',
        maxFilesize: 20, // MB
        maxFiles: 5,
        //acceptedFiles: '.*',
        dictDefaultMessage: '<img src="./img/contour/formats.svg" alt="formats" class="formats-icon" />',
        dictMaxFilesExceeded: 'Limite de fichiers dépassée !',
        init: function() {
          this.on('addedfile', function(file) {
            // Create the remove button
            var removeButton = Dropzone.createElement('<a class="delete-btn" title="Supprimer">Х</a>');

            // Capture the Dropzone instance as closure.
            var _this = this;

            // Listen to the click event
            removeButton.addEventListener('click', function(e) {
              // Make sure the button click doesn't submit the form:
              e.preventDefault();
              e.stopPropagation();

              // Remove the file preview.
              _this.removeFile(file);

              // If you want to the delete the file on the server as well,
              // you can do the AJAX request here.

              const request = new XMLHttpRequest();
              const url = '../manager/d26386b04e.php';
              const params = 'event=remove_image&image=' + file.xhr.responseText;
              request.open("POST", url, true);
              request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              request.addEventListener("readystatechange", () => {
                if (request.readyState === 4 && request.status === 200) {
                  image.value = image.value.replace(file.xhr.responseText, '').trim();
                }
              });
              request.send(params);

            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);
          });

          this.on('success', function(file, responseText) {
            image.value = image.value == '' ? responseText : image.value + ' ' + responseText;
          });
        }
      }

      const form = document.querySelector('.contour-form'),
            mail = document.getElementById('mail'),
            text = document.getElementById('text'),
            typographies = document.getElementById('typographies'),
            palmier = document.getElementById('palmier'),
            code = document.getElementById('code'),
            autres = document.getElementById('autres');
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        console.log(12345);

        openPopup('.wraper-popup--warning2');

        var done_btn = document.querySelector('.done2');
        done_btn.classList.remove('done2');
        done_btn.classList.add('valider');

        var valider_btn = document.querySelector('.valider');

        valider_btn.addEventListener('click', (event) => {
          event.preventDefault();

          const request = new XMLHttpRequest();
          const url = '../manager/d26386b04e.php';
          const params = 'event=contour&mail=' + mail.value + '&text=' + text.value + '&typographies=' + typographies.value + '&palmier=' + palmier.value + '&code=' + code.value + '&autres=' + autres.value + '&image=' + image.value;
          request.open("POST", url, true);
          request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          request.addEventListener("readystatechange", () => {
            if(request.readyState === 4 && request.status === 200) {

              /*
              const contours = document.querySelectorAll('.contour'),
                    contour_load = document.querySelector('.contour--load');

              contours.forEach((element) => {
                element.classList.add('hidden');
              });

              contour_load.classList.remove('hidden')
              if (request.responseText == 'done') {

              } else {

              }*/
              closePopup('.wraper-popup--warning2');
              const gallery_valid = document.getElementById('gallery_valid'),
                    gallery_a = document.getElementById('sidebar-navigation__btn--contour'),
                    form_valid = document.querySelector('.form-valid');
              gallery_valid.disabled = true;
              gallery_valid.remove();
              gallery_a.classList.remove('sidebar-navigation__btn--msg');
              document.querySelectorAll('input, textarea').forEach((element) => {
                element.disabled = true;
              });
              form_valid.classList.add('hidden');
            }
          });
          request.send(params);
        });
      });


      const photo = document.getElementById('photo');
      photo.addEventListener('click', (event) => {
        if (photo.checked) {
          openPopup('.wraper-popup--warning');
        }
      });

      const block1 = document.getElementById('block--1');
       block1.addEventListener('change', (event) => {
        if (block1.checked) {
          openPopup('.wraper-popup--warning');
        }
        /*const request = new XMLHttpRequest();
        const url = '../manager/d26386b04e.php';
        const params = 'event=without_photo_frame&without_photo_frame=' + (block1.checked ? 1 : 0);
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
          if(request.readyState === 4 && request.status === 200) {
            console.log(request.responseText);
          }
        });
        request.send(params);*/
      });



      const cancel = document.querySelector('.cancel');
      cancel.addEventListener('click', (event) => {
        event.preventDefault();
        block1.checked = false;
        closePopup('.wraper-popup--warning');
      });

      const done = document.querySelector('.done'),
            contour_description = document.querySelector('.contour-description-remove'),
            for_remove = document.querySelector('.for-remove'),
            gallery_a = document.getElementById('sidebar-navigation__btn--contour');
      done.addEventListener('click', (event) => {
        event.preventDefault();
        setTimeout(() => {
          document.getElementById('block--1').checked = true;
        }, 100);
        contour_description.remove();
        for_remove.remove();
        closePopup('.wraper-popup--warning');
        gallery_a.classList.remove('sidebar-navigation__btn--msg');
        const request = new XMLHttpRequest();
        const url = '../manager/d26386b04e.php';
        const params = 'event=without_photo_frame&without_photo_frame=1';
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
          if(request.readyState === 4 && request.status === 200) {
            console.log(request.responseText);
          }
        });
        request.send(params);
      });



      function openPopup(popup_class) {
        const body = document.querySelector('body'),
              popup = document.querySelector(popup_class);
        body.style.overflow = 'hidden';
        popup.classList.add('wraper-popup--active');
      }

      function closePopup(popup_class) {
        const body = document.querySelector('body'),
              popup = document.querySelector(popup_class),
              block1 = document.getElementById('block--1');
        body.style.overflow = 'auto';
        popup.classList.remove('wraper-popup--active');
        block1.checked = false;
      }

      <?php if ($row_orders['gallery_valid'] == 1) {
        echo"
          document.querySelectorAll('input, textarea').forEach((element) => {
            element.disabled = true;
          });
        ";} ?>
    </script>
  </body>
</html>
