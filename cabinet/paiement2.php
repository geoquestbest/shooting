<?php
  @session_start();
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
    <title>Paiement</title>
    <link rel="stylesheet" type="text/css" href="./css/index.min.css?v=1.0.4" />
    <link href="./dropzone/css/basic.css" rel="stylesheet">
    <link href="./dropzone/css/dropzone.css?v=0.2" rel="stylesheet">
    <style>
      .form-field__radio{grid-area:input;position:relative;margin-right:55px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.form-field__radio input[type=radio]{display:none;padding:0;height:initial;width:initial;margin-bottom:0;display:none}.form-field__radio .box{width:23px;height:23px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;position:absolute;border-radius:6px;border:1px solid #707070;background-color:inherit}.form-field__radio input:checked+span:after{content:"";position:absolute;width:19px;height:19px;background-color:#707070;border-radius:6px}.form-field__radio label{color:#707070;font-size:16px;font-weight:400;cursor:pointer;position:relative;padding-left:38px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center}

      .hidden {
        display: none!important;
      }

      .form-valid {
        width: 175px;
        text-align: center;
        float: right;
      }

      .form-valid .valid-title {
        color: #707070;
        font-size: 17px;
        font-weight: 700;
        line-height: 18px;
        margin-bottom: 5px;
        text-align: center;
      }

      .form-valid .valid-description {
        opacity: .75;
        color: #707070;
        font-size: 12px;
        font-weight: 400;
        line-height: 18px;
        margin-bottom: 16px;
        text-align: center;
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

      .paiement-form .form {
        max-width: max-content;
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
          <input type="hidden" id="image" value="" />
        <?php if (strpos($row_orders['select_type'], 'entreprise') === false) { ?>
          <section class="paiement paiement-form">
            <div class="paiement-container">
              <h1 class="paiement-title">Paiement</h1>
              <img
                src="./img/paiement/trait.svg"
                alt="trait"
                class="paiement-img"
              />

              <?php if ($row_orders['deposit_link'] == "" && $row_orders['sale_link'] == "") { ?>

              <p class="paiement-description">
                Retrouvez très prochainement ici les liens afin de régler l'acompte ainsi que le solde de votre réservation.
              </p>

              <?php } else { ?>

              <p class="paiement-description<?php if ($row_orders['payment_status'] != 0) { echo " hidden"; } ?>">
                Afin de finaliser votre réservation, vous trouverez ci-dessous
                le lien de paiement pour l’acompte des 50€ (attention, vous avez
                5 jours pour régler l’acompte).
              </p>

              <p class="paiement-description<?php if ($row_orders['payment_status'] != 1) { echo " hidden";} ?>">
                Le règlement de l'acompte a bien été pris en compte, merci !
              </p>

              <?php } ?>

              <div class="paiement-steps form-field  form-field__row">
                <?php if ($row_orders['deposit_link'] != "" && $row_orders['payment_status'] == 0) { ?>
                  <div class="paiement-step">
                    <h2 class="step-title">Étape 1</h2>
                    <a href="<?php echo $row_orders['deposit_link']; ?>" class="deposit_link_view" target="_blank">
                      <button class="step-btn">Payer l’acompte</button>
                    </a>
                    <p class="step-description">
                      Concernant le solde, vous pourrez le régler au moment de votre retrait dans notre boutique.<br />
                      Si vous avez l'option livraison, nos équipes vont vous recontacter 1 semaine avant votre évènement.
                    </p>
                  </div>
                <?php } ?>
                <?php if ($row_orders['deposit_link'] != "" && $row_orders['payment_status'] != 0) { ?>
                  <div class="paiement-step">
                    <h2 class="step-title">Étape 1</h2>
                    <button class="step-btn" style="opacity: 0.5;" disabled>Payer l’acompte</button>
                    <p class="step-description">
                      Concernant le solde, vous pourrez le régler au moment de votre retrait dans notre boutique.<br />
                      Si vous avez l'option livraison, nos équipes vont vous recontacter 1 semaine avant votre évènement.
                    </p>
                  </div>
                <?php } ?>

                <?php if ($row_orders['sale_link'] != "" && $row_orders['payment_status'] == 1) { ?>
                  <div class="paiement-step">
                    <h2 class="step-title">Étape 2</h2>
                    <a href="<?php echo $row_orders['sale_link']; ?>" class="sale_link_view" target="_blank">
                      <button class="step-btn">Payer le solde restant</button>
                    </a>
                    <!--p class="step-description">
                      Fonctionnalité disponible une fois l’accompte réglé
                    </p-->
                  </div>
                <?php } ?>
                <?php if ($row_orders['sale_link'] != "" && $row_orders['payment_status'] == 2) { ?>
                  <div class="paiement-step">
                    <h2 class="step-title">Étape 2</h2>
                    <button class="step-btn" style="opacity: 0.5;" disabled>Payer le solde restant</button>
                    <!--p class="step-description">
                      Fonctionnalité disponible une fois l’accompte réglé
                    </p-->
                  </div>
                <?php } ?>
              </div>

              <div class="notification<?php echo ((strpos($row_orders['select_type'], 'entreprise') === false && ($row_orders['deposit_link'] != "" || $row_orders['sale_link'] != "") && $row_orders['status'] != 2) ? ' notification--active' : ''); ?> form-field form-field__row" style="position: relative; bottom: inherit; right: inherit; margin-top: 0">
                <img
                  src="./img/notification/paiement-warning.svg"
                  alt="warning"
                  class="notification-icon"
                />
                  <div class="notification-details">
                    <p class="notification-description">
                      Vous pouvez également choisir de payer le solde restant lors
                      du retrait de votre borne par chèque, CB ou espèces.
                    </p>
                  </div>
              </div>
              <div class="paiement-downloads form-field  form-field__row">
                <?php if ($row_orders['payment_status'] != 2 && $href != "" ) { ?>
                  <a href="<?php echo $href; ?>" class="paiement-download" target="_blank">
                    <img
                      src="./img/paiement/file.svg"
                      alt="file"
                      class="download-icon"
                    />
                    <h4 class="download-title">Télécharger votre facture</h4>
                  </a>
                <?php } ?>
                <?php if ($row_orders['payment_status'] == 2 && $href != "" ) { ?>
                  <a href="<?php echo $href; ?>" class="paiement-download paiement-download--active" target="_blank">
                    <img
                      src="./img/paiement/file-active.svg"
                      alt="file"
                      class="download-icon"
                    />
                    <h4 class="download-title">
                      Télécharger votre facture soldée
                    </h4>
                  </a>
                <?php } ?>
              </div>
            </div>

            <p class="text">
              N’hésitez pas à nous contacter au 01 45 01 66 66 pour toutes questions.
            </p>

            <div class="wraper-popup">
              <div class="popup">
                <img
                  src="./img/popup/close.svg"
                  alt="close"
                  class="popup-close"
                />
                <h2 class="popup-title">Attention</h2>

                <img
                  class="popup-img"
                  src="./img/popup/paiement-trait.svg"
                  alt="trait"
                />

                <p class="popup-description">
                  Une fois ces informations validées,
                  <span>elles ne sont plus modifiables.</span>
                </p>
                <p class="popup-text">
                  <img src="./img/popup/warning.svg" alt="warning" />
                  En cas de question, appellez le 01 45 01 66 66
                </p>
                <button class="popup-btn popup-btn--active">Retour</button>
                <button class="popup-btn">Valider</button>
              </div>
            </div>
          </section>
        <? } ?>
        <?php if (strpos($row_orders['select_type'], 'entreprise') !== false) { ?>
          <section class="paiement paiement-form">
            <div class="paiement-container">
              <h1 class="paiement-title">Paiement</h1>
              <img
                class="paiement-img"
                src="./img/paiement/trait.svg"
                alt="trait"
              />
              <p class="paiement-description">
                Nous vous informons que le règlement du solde doit
                impérativement avoir lieu
                <span class="bold">AVANT la date de votre prestation.</span>
                <span class="italic"
                  >Sauf conditions de paiements exceptionnelles convenues :
                  (Etablissements publics, fidélités clientèles etc…), nous
                  recommandons un paiement avant prestation pour assurer le bon
                  déroulement de la prestation.</span
                >
              </p>

              <form class="form payment-form<?php if ($row_orders['payment_valid'] == 1) {echo" form--file";} ?>">
                <div class="form-wrap">
                <div class="form-fields">
                  <div class="form-field">
                    <label for="entreprise_pdf" class="form-field__label">
                      Entreprise
                    </label>
                    <input
                      type="text"
                      id="entreprise_pdf"
                      class="form-field__input"
                      placeholder="Entreprise"
                      value="<?php echo $row_orders['entreprise_pdf'] ?>"
                    />
                  </div>

                  <div class="form-field">
                    <label for="address_pdf" class="form-field__label">
                      Adresse de facturation
                    </label>

                    <textarea
                      id="address_pdf"
                      class="form-field__textarea"
                      placeholder="12 rue Jean &#10;Dupont 75 012 Paris"
                    ><?php echo htmlspecialchars_decode($row_orders['address_pdf'], ENT_QUOTES) ?></textarea>
                  </div>

                  <div class="form-field">
                    <label for="city_pdf" class="form-field__label">
                      Ville
                    </label>
                    <input
                      type="text"
                      class="form-field__input"
                      id="city_pdf"
                      placeholder="Ville"
                      value="<?php echo $row_orders['city_pdf'] ?>"
                    />
                  </div>

                  <div class="form-field">
                    <label for="cp_pdf" class="form-field__label">
                      Code postal
                    </label>
                    <input
                      type="text"
                      class="form-field__input"
                      id="cp_pdf"
                      placeholder="Code postal"
                      value="<?php echo $row_orders['cp_pdf'] ?>"
                    />
                  </div>

                  <div class="form-field">
                    <label for="number_pdf" class="form-field__label">
                      Numéro de siret
                    </label>
                    <input
                      type="text"
                      class="form-field__input"
                      id="number_pdf"
                      placeholder="Numéro de siret"
                      value="<?php echo $row_orders['number_pdf'] ?>"
                      required
                    />
                  </div>

                  <div class="form-field">
                    <label for="ord" class="form-field__label">
                      Bon de commande
                    </label>
                    <input
                      type="text"
                      class="form-field__input"
                      id="ord"
                      placeholder="Bon de commande"
                      value="<?php echo $row_orders['ord'] ?>"
                    />
                  </div>

                  <!--div class="form-field" style="display: none;">
                    <label for="other_pdf" class="form-field__label">
                      Autre
                    </label>

                    <textarea
                      id="other_pdf"
                      class="form-field__textarea"
                      placeholder="Autre"
                    ><?php echo htmlspecialchars_decode($row_orders['other_pdf'], ENT_QUOTES) ?></textarea>
                  </div-->

                  <div class="form-field form-field__checkboxes">
                    <label for="" class="form-field__label">
                      Mode de règlement*
                    </label>


                    <div class="form-field__radio">
                      <input type="radio" name="payment_by" id="virement"<?php if ($row_orders['payment_by'] == "" || strpos($row_orders['payment_by'], 'AVANT') !== false || strpos($row_orders['payment_by'], 'JOUR') !== false || strpos($row_orders['payment_by'], '30') !== false) { echo " checked";} ?> />
                      <span class="box"></span>
                      <label for="virement">Virement</label>
                    </div>

                    <div class="form-field__radio">
                      <input type="radio" name="payment_by" id="chorus"<?php if (strpos($row_orders['payment_by'], 'Chorus') !== false) { echo " checked";} ?> />
                      <span class="box"></span>
                      <label for="chorus">Chorus</label>
                    </div>

                    <div class="form-field__radio">
                      <input type="radio" name="payment_by" id="lien"<?php if (strpos($row_orders['payment_by'], 'Lien de paiement sécurisé (carte bleue)') !== false) { echo " checked";} ?> />
                      <span class="box"></span>
                      <label for="lien">Lien de paiement sécurisé (carte bleue)</label>
                    </div>
                  </div>

                  <div class="form-field form-field__checkboxes virement-info<?php if (strpos($row_orders['payment_by'], 'Chorus') !== false || strpos($row_orders['payment_by'], 'Lien de paiement sécurisé (carte bleue)') !== false) { echo " hidden";} ?>">


                    <div class="form-field__radio">
                      <input type="radio" name="payment_by2" id="payment_facture"<?php if (strpos($row_orders['payment_by'], 'AVANT') !== false) { echo " checked";} ?> />
                      <span class="box"></span>
                      <label for="payment_facture">Paiement à la réception de la facture AVANT prestation</label>
                    </div>

                  </div>

                  <div class="form-valid">
                    <h3 class="valid-title">Importer des documents</h3>
                    <p class="valid-description">
                      Ajouter des pièces jointe
                    </p>
                    <div class="valid-formats">
                      <div class="dropzone dz-clickable" id="dropzoneForm">
                        <div class="dz-default dz-message"><span><img src="./img/paiement/formats.svg" alt="formats" class="formats-icon"></span></div></div>
                      </div>
                    </div>
                  </div>

                  <div class="form-field form-field__checkboxes virement-info<?php if (strpos($row_orders['payment_by'], 'Chorus') !== false || strpos($row_orders['payment_by'], 'Lien de paiement sécurisé (carte bleue)') !== false) { echo " hidden";} ?>">


                    <div class="form-field__radio">
                      <input type="radio" name="payment_by2" id="payment_factureJOUR"<?php if (strpos($row_orders['payment_by'], 'JOUR') !== false) { echo " checked";} ?> />
                      <span class="box"></span>
                      <label for="payment_factureJOUR">Paiement à la réception de la facture JOUR J</label>
                    </div>


                  </div>

                   <div class="form-field form-field__checkboxes virement-info<?php if (strpos($row_orders['payment_by'], 'Chorus') !== false || strpos($row_orders['payment_by'], 'Lien de paiement sécurisé (carte bleue)') !== false) { echo " hidden";} ?>">

                    <div class="form-field__radio">
                      <input type="radio" name="payment_by2" id="payment_facture30"<?php if (strpos($row_orders['payment_by'], '30') !== false) { echo " checked";} ?> />
                      <span class="box"></span>
                      <label for="payment_facture30">Paiement à 30 jours</label>
                    </div>

                  </div>

                  <div class="form-field form-field__checkboxes chorus-info <?php if (strpos($row_orders['payment_by'], 'Chorus') === false) { echo " hidden";} ?>">
                    <label for="" class="form-field__label"></label>
                    <div class="form-field__radio">
                      <label style="padding-left: 0;">Merci d’importer votre bon de commande.</label>
                    </div>
                  </div>

                  <div class="form-field form-field__checkboxes lien-info <?php if (strpos($row_orders['payment_by'], 'Lien de paiement sécurisé (carte bleue)') === false) { echo " hidden";} ?>">
                    <label for="" class="form-field__label"></label>
                    <div class="form-field__radio">
                      <label style="padding-left: 0;">Vous avez choisi de payer par lien de paiement sécurisé.<br />Vous serez notifié par mail lorsque votre lien de paiement sera disponible sur cet espace.</label>
                    </div>
                  </div>


                  <?php if ($row_orders['deposit_link'] != "" && $row_orders['payment_status'] == 0) { ?>
                    <div class="paiement-step" style="width: 35%;">
                      <a class="step-btn deposit_link_view" href="<?php echo $row_orders['deposit_link']; ?>" target="_blank" style="width: 35%;">
                        Payer l’acompte
                      </a>
                    </div>
                  <?php } ?>
                  <?php if ($row_orders['sale_link'] != "" && $row_orders['payment_status'] == 1) { ?>
                    <div class="paiement-step" style="width: 35%;">
                      <a class="step-btn sale_link_view" href="<?php echo $row_orders['sale_link']; ?>" target="_blank">
                        Payer le solde restant
                      </a>
                    </div>
                  <?php } ?>
                  <?php if (($row_orders['deposit_link'] != "" && $row_orders['payment_status'] == 0) || ($row_orders['sale_link'] != "" && $row_orders['payment_status'] == 1)) { ?>
                    <div style="clear: both;"></div>
                  <?php } ?>
                  <div class="form-field">
                    <label for="other_pdf" class="form-field__label">
                      Autres remarques
                    </label>

                    <textarea
                      id="other_pdf"
                      class="form-field__textarea"
                      placeholder="Autres remarques"
                    ><?php echo htmlspecialchars_decode($row_orders['other_pdf'], ENT_QUOTES) ?></textarea>
                  </div>
                <button class="paiement-form__btn" id="payment_valid" type="submit"<?php if ($row_orders['payment_valid'] == 1) {echo" disabled";} ?>>
                  Valider
                </button>
              </div>
                <div class="form-files">
                  <?php
                  if ($row_orders['facture_enabled'] != 0) {

                    $href = '../manager/to_pdf.php?order_id='.$row_orders['id'];

                    $result_facture = mysqli_query($conn, "SELECT * FROM `facture` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC LIMIT 1");
                    if (mysqli_num_rows($result_facture ) > 0) {
                      $row_facture = mysqli_fetch_assoc($result_facture);
                        if ($row_orders['payment_status'] == 0) {
                          echo'<a class="form-file" href="'.$href.'" target="_blank">
                            <img src="./img/paiement/file.svg" alt="file" />
                            <p>
                              <span>Télécharger</span>
                              <span>votre facture</span>
                            </p>
                          </a>';
                        } else {
                          echo'<a class="form-file" href="'.$href.'" target="_blank">
                            <img
                              src="./img/paiement/file-active.svg"
                              alt="file-active"
                            />
                            <p>
                              <span>Télécharger</span>
                              <span>votre facture soldée</span>
                            </p>
                          </a>';
                        }
                    } else {
                       echo'<a class="form-file" href="'.$href.'" target="_blank">
                            <img src="./img/paiement/file.svg" alt="file" />
                            <p>
                              <span>Télécharger</span>
                              <span>votre facture</span>
                            </p>
                          </a>';
                    }
                  }
                 ?>
                </div>
              </form>
            </div>
            <p class="text">
              N’hésitez pas à nous contacter au 01 45 01 66 66 pour toutes questions.
            </p>
          </section>
        <?php } ?>

          <!-- <section class="paiement paiement-form">
            <div class="paiement-container">
              <h1 class="paiement-title">Paiement</h1>

              <img
                class="paiement-img"
                src="./img/paiement/trait.svg"
                alt="trait"
              />

              <p class="paiement-description">
                Merci ! Vos informations ont bien été prises en compte, nos
                équipes reviennent vers vous si nécessaire.
              </p>

              <form class="form form--file">
                <div class="form-wrap">
                  <div class="form-fields">
                    <div class="form-field  ">
                      <label for="email" class="form-field__label">
                        Email du destinataire
                      </label>
                      <input
                        type="text"
                        id="email"
                        class="form-field__input"
                        disabled
                        placeholder="camille@shootnbox.fr"
                      />
                      <span class="form-field__error">required field</span>
                    </div>

                    <div class="form-field">
                      <label for="command" class="form-field__label">
                        N° de bon de commande
                      </label>
                      <input
                        type="text"
                        class="form-field__input"
                        id="command"
                        placeholder="BDC56387"
                      />
                    </div>

                    <div class="form-field">
                      <label for="chanel" class="form-field__label">
                        Entitée
                      </label>
                      <input
                        type="text"
                        class="form-field__input"
                        id="chanel"
                        placeholder="Chanel"
                      />
                    </div>

                    <div class="form-field">
                      <label for="address" class="form-field__label">
                        Adresse de facturation
                      </label>

                      <textarea
                        id="address"
                        class="form-field__textarea"
                        placeholder="12 rue Jean &#10;Dupont 75 012 Paris"
                      ></textarea>
                    </div>

                    <div class="form-field form-field__checkboxes">
                      <label for="" class="form-field__label">
                        Mode de règlement
                      </label>

                      <div class="form-field__checkbox">
                        <input type="checkbox" id="virement" />
                        <span class="box"></span>
                        <label for="virement">Virement</label>
                      </div>

                      <div class="form-field__checkbox">
                        <input type="checkbox" id="cheque" />
                        <span class="box"></span>
                        <label for="cheque">Chèque</label>
                      </div>

                      <div class="form-field__checkbox">
                        <input type="checkbox" id="chorus" />
                        <span class="box"></span>
                        <label for="chorus">Chorus</label>
                      </div>
                    </div>
                  </div>
                  <button class="paiement-form__btn" type="submit">
                    Valider
                  </button>
                </div>
                <div class="form-files">
                  <div class="form-file">
                    <img src="./img/paiement/file.svg" alt="file" />
                    <p>
                      <span>Télécharger</span>
                      <span>votre facture</span>
                    </p>
                  </div>
                  <div class="form-file">
                    <img
                      src="./img/paiement/file-active.svg"
                      alt="file-active"
                    />
                    <p>
                      <span>Télécharger</span>
                      <span>votre facture soldée</span>
                    </p>
                  </div>
                </div>
              </form>
            </div>
            <p class="text">
              N’hésitez pas à nous contacter au 01 45 01 66 66 pour toutes questions.
            </p>
          </section> -->
        </main>
      </div>
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
            const request = new XMLHttpRequest();
            const url = '../manager/d26386b04e.php';
            const params = 'event=payment_doc' +
                          '&image=' + image.value;
            request.open("POST", url, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.addEventListener("readystatechange", () => {
              if(request.readyState === 4 && request.status === 200) {

              }
            });
            request.send(params);
          });
        }
      }

      const form = document.querySelector('.payment-form'),
            entreprise_pdf = document.getElementById('entreprise_pdf'),
            address_pdf = document.getElementById('address_pdf'),
            city_pdf = document.getElementById('city_pdf'),
            cp_pdf = document.getElementById('cp_pdf'),
            number_pdf = document.getElementById('number_pdf'),
            ord = document.getElementById('ord'),
            other_pdf = document.getElementById('other_pdf'),
            virement = document.getElementById('virement'),
            lien = document.getElementById('lien'),
            chorus = document.getElementById('chorus'),
            payment_facture = document.getElementById('payment_facture'),
            payment_factureJOUR = document.getElementById('payment_factureJOUR'),
            payment_facture30 = document.getElementById('payment_facture30'),
            virement_info = document.querySelectorAll('.virement-info'),
            chorus_info = document.querySelector('.chorus-info'),
            lien_info = document.querySelector('.lien-info');

      if(virement) {
        virement.addEventListener('change', (event) => {
          chorus_info.classList.add('hidden');
          lien_info.classList.add('hidden');
          virement_info.forEach((ele) => {
            ele.classList.remove('hidden');
          });
        });
    }

if(chorus) {
      chorus.addEventListener('change', (event) => {
        if (chorus.checked) {
          chorus_info.classList.remove('hidden');
          lien_info.classList.add('hidden');
          virement_info.forEach((ele) => {
            ele.classList.add('hidden');
          });
        }
      });
  }
if(lien) {
      lien.addEventListener('change', (event) => {
        if (lien.checked) {
          lien_info.classList.remove('hidden');
          chorus_info.classList.add('hidden');
          virement_info.forEach((ele) => {
            ele.classList.add('hidden');
          });
        }
      });
}
      const deposit_link_view = document.querySelector('.deposit_link_view');

      if (deposit_link_view) {
        deposit_link_view.addEventListener('click', (event) => {
          const request = new XMLHttpRequest();
          const url = '../manager/d26386b04e.php';
          const params = 'event=deposit_link_view';
          request.open("POST", url, true);
          request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          request.addEventListener("readystatechange", () => {
            if(request.readyState === 4 && request.status === 200) {
              payment_a = document.getElementById('sidebar-navigation__btn--paiement');
              payment_a.classList.remove('sidebar-navigation__btn--msg');
              if (request.responseText == 'done') {

              } else {

              }
            }
          });
          request.send(params);
        });
      }

      const sale_link_view = document.querySelector('.sale_link_view');

      if (sale_link_view) {
        sale_link_view.addEventListener('click', (event) => {
          const request = new XMLHttpRequest();
          const url = '../manager/d26386b04e.php';
          const params = 'event=sale_link_view';
          request.open("POST", url, true);
          request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          request.addEventListener("readystatechange", () => {
            if(request.readyState === 4 && request.status === 200) {
              payment_a = document.getElementById('sidebar-navigation__btn--paiement');
              payment_a.classList.remove('sidebar-navigation__btn--msg');
              if (request.responseText == 'done') {

              } else {

              }
            }
          });
          request.send(params);
        });
      }


      if (form) {
        form.addEventListener('submit', (event) => {
          event.preventDefault();
          const request = new XMLHttpRequest();
          const url = '../manager/d26386b04e.php';
          const params = 'event=payment' +
                        '&entreprise_pdf=' + entreprise_pdf.value +
                        '&address_pdf=' + address_pdf.value +
                        '&city_pdf=' + city_pdf.value +
                        '&cp_pdf=' + cp_pdf.value +
                        '&number_pdf=' + number_pdf.value +
                        '&ord=' + ord.value +
                        '&other_pdf=' + other_pdf.value +
                        '&payment_by=' + (lien.checked ? 'Lien de paiement sécurisé (carte bleue)' : '') + (chorus.checked ? 'Chorus' : '') + (virement.checked && payment_facture.checked ? 'Paiement à la réception de la facture AVANT prestation' : '') + (virement.checked && payment_factureJOUR.checked ? 'Paiement à la réception de la facture JOUR J' : '') + (virement.checked && payment_facture30.checked ? 'Paiement à 30 jours' : '') +
                        '&payment_facture=';
          request.open("POST", url, true);
          request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          request.addEventListener("readystatechange", () => {
            if(request.readyState === 4 && request.status === 200) {
              //alert('Informations enregistrées !');
              const payment_valid = document.getElementById('payment_valid'),
                payment_a = document.getElementById('sidebar-navigation__btn--paiement');
                payment_valid.disabled = true;
                payment_valid.remove();
                payment_a.classList.remove('sidebar-navigation__btn--msg');
                document.querySelectorAll('input, textarea').forEach((element) => {
                  element.disabled = true;
                });
              if (request.responseText == 'done') {

              } else {

              }
            }
          });
          request.send(params);
        });
      }

      <?php if ($row_orders['payment_valid'] == 1) {
        echo"
          document.querySelectorAll('input, textarea').forEach((element) => {
            element.disabled = true;
          });
        ";} ?>

    </script>
  </body>
</html>
