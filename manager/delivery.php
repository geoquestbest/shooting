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
    <title><?php echo $delivery; ?></title>
    <link rel="stylesheet" type="text/css" href="./css/index.min.css" />
    <style>
      .hidden {
        display: none;
      }

      .delivery .form-valider__items--active .valider-item__importer {
        display: flex;
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
        position: relative;
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
        right: 0;
        display: block;
        color: #fff;
        background: #e3763e;
        text-align: center;
        border-radius: 50%;
        line-height: 25px;
        z-index: 100;
        font-weight: bold;
        cursor: pointer!important;
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
        <?php if ($delivery=="Livraison") { ?>
          <section class="delivery">
            <h1 class="delivery-title">Livraison/Reprise</h1>

            <img
              src="./img/delivery/trait.svg"
              alt="trait"
              class="delivery-img"
            />
            <p class="delivery-description">
              Merci de remplir les éléments ci-dessous afin de préparer votre
              livraison. Ces élements sont à nous communiquer
              <span class="description-bold">AVANT la deadline annoncée,</span>
              sans quoi nos équipes ne seront pas en mesure d’assurer votre
              livraison.
            </p>

            <form class="form delivery-form">
              <div class="form-items">
                <div class="form-fields">
                  <div class="form-fields__row">
                    <div class="delivery-btn">Livraison</div>
                  </div>

                  <div class="form-field form-field__calendar">
                    <label for="livraison" class="form-field__label">
                      Jour de livraison*
                    </label>
                    <img src="./img/delivery/calendar.svg" alt="calendar" />
                    <input
                      type="date"
                      value="<?php echo date("Y-m-d", strtotime($row_orders['take_date']) != 0 ? strtotime($row_orders['take_date']) : time() ); ?>"
                      id="livraison"
                      class="form-field__input livraison"
                      required
                    />
                    <span class="form-field__error">required field</span>
                  </div>
                  <div class="form-field form-field__select">
                    <label for="creneau" class="form-field__label">
                      Créneau horaire*
                    </label>
                    <img src="./img/delivery/select.svg" alt="select" />
                    <input type="text" id="creneau" value="<?php echo ($row_orders['take_time'] != "" ? $row_orders['take_time'] : "--:--") ?>" class="form-field__input livraison" disabled />
                  </div>
                  <div class="form-field">
                    <label for="adresse1" class="form-field__label">
                      Adresse de livraison*
                    </label>
                    <input
                      type="text"
                      id="adresse1"
                      value="<?php echo $row_orders['event_place']; ?>"
                      class="form-field__input livraison"
                      required
                    />
                  </div>
                  <div class="form-field">
                    <label for="contacts" class="form-field__label">
                      Contact(s) sur place*
                    </label>
                    <input
                      type="text"
                      id="contacts"
                      value="<?php echo $row_orders['take_contact']; ?>"
                      class="form-field__input livraison"
                      placeholder="Nom Prénom"
                      required
                    />
                  </div>
                  <div class="form-field">
                    <label for="contacts_phone" class="form-field__label"></label>
                    <input type="text" id="contacts_phone" value="<?php echo $row_orders['return_contact']; ?>" class="form-field__input livraison" placeholder="Numéro de téléphone portable" required />
                  </div>
                  <div class="form-field">
                    <label for="dacces" class="form-field__label">
                      Code d'accès
                    </label>
                    <input type="text" id="dacces" value="<?php echo $row_orders['take_access']; ?>" class="form-field__input livraison" />
                  </div>

                  <div class="form-field">
                    <label class="form-field__label"> </label>
                    <div class="form-field__checkbox">
                      <input type="checkbox" id="escalier"<?php echo ($row_orders['take_stairs'] == 1 ? ' checked' : ''); ?> />
                      <span class="box"></span>
                      <label for="escalier">Escalier</label>
                    </div>
                  </div>
                </div>
                <div class="form-fields">
                  <div class="form-fields__row">
                    <div class="delivery-btn">Reprise</div>
                    <div class="form-field__checkbox">
                      <input type="checkbox" id="identical" />
                      <span class="box"></span>
                      <label for="identical">Identique à la livraison</label>
                    </div>
                  </div>

                  <div class="form-field form-field__calendar">
                    <label for="jour" class="form-field__label">
                      Jour de livraison*
                    </label>
                    <img src="./img/delivery/calendar.svg" alt="calendar" />
                    <input type="date" value="<?php echo date("Y-m-d", strtotime($row_orders['return_date']) != 0 ? strtotime($row_orders['return_date']) : time()) ?>" id="jour" class="form-field__input retour" required />
                  </div>
                  <div class="form-field form-field__select">
                    <label for="horaire" class="form-field__label">
                      Créneau horaire*
                    </label>
                    <img src="./img/delivery/select.svg" alt="select" />
                    <input type="text" id="horaire" value="<?php echo ($row_orders['return_time'] != "" ? $row_orders['return_time'] : "--:--") ?>" class="form-field__input retour" disabled />
                  </div>
                  <div class="form-field">
                    <label for="adresse" class="form-field__label">
                      Adresse de livraison*
                    </label>
                    <input type="text" id="adresse" value="<?php echo $row_orders['return_place']; ?>" class="form-field__input retour" required />
                  </div>
                  <div class="form-field">
                    <label for="place" class="form-field__label">
                      Contact(s) sur place*
                    </label>
                    <input type="text" id="place" value="<?php echo $row_orders['return_contact']; ?>" class="form-field__input retour" placeholder="Nom Prénom" required />
                  </div>
                  <div class="form-field">
                    <label for="place_phone" class="form-field__label"></label>
                    <input type="text" id="place_phone" value="<?php echo $row_orders['return_contact']; ?>" class="form-field__input retour" placeholder="Numéro de téléphone portable" required />
                  </div>
                  <div class="form-field">
                    <label for="code" class="form-field__label">
                      Code d'accès
                    </label>
                    <input type="text" id="code" value="<?php echo $row_orders['return_access']; ?>" class="form-field__input retour" />
                  </div>

                  <div class="form-field">
                    <label class="form-field__label"> </label>
                    <div class="form-field__checkbox">
                      <input type="checkbox" id="escalier1" class="retour"<?php echo ($row_orders['return_stairs'] == 1 ? ' checked' : ''); ?> />
                      <span class="box"></span>
                      <label for="escalier1">Escalier</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-valider__items form-valider__items--active">
                <div class="form-field">
                  <input
                    type="text"
                    id="plan_access"
                    value=""
                    class="form-field__input"
                    placeholder="Plan d'accès"
                    disabled
                    style="display: none"
                     />
                     <!--?php echo $row_orders['plan_access']; ?-->
                  <!--input
                    type="text"
                    id="adresse"
                    class="form-field__input"
                    disabled
                    placeholder="Feuille d'accès"
                  /-->
                </div>
                <div class="valider-item__importer">
                  <div class="item-img">
                    <div class="dropzone" id="dropzoneForm">
                      <div class="fallback">
                        <input type="file" />
                      </div>
                    </div>
                  </div>
                  <div class="item-details">
                    <h3>
                      Importer des documents supplémentaires (plan d’accès etc…)
                    </h3>
                    <span> Formats pdf, jpeg, png </span>
                  </div>
                </div>
                <div class="notification">
                  <img
                    src="./img/notification/delivery-attention.svg"
                    alt="delivery-attention"
                  />
                  <div class="notification-details">
                    <h3 class="notification-title">Attention</h3>
                    <p class="notification-description">
                      Une fois arrivé à l'adresse indiquée, votre livreur prend
                      contact avec vous par téléphone. S'il n'arrive pas à vous
                      joindre, il vous enverra un SMS et patientera 15min. Après
                      quoi il sera dans l'obligation de poursuivre sa tournée.
                    </p>
                  </div>
                </div>
                <button class="form-submit" id="delivery_valid" <?php if ($row_orders['delivery_valid'] == 1) {echo" disabled";} ?>>Valider</button>
              </div>
            </form>

            <div class="wraper-popup">
              <div class="popup">
                <img
                  src="./img/popup/close.svg"
                  alt="close"
                  class="popup-close"
                />
                <h2 class="popup-title">Attention</h2>

                <img
                  src="./img/popup/delivery-trait.svg"
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
                <button class="popup-btn popup-btn--active">Retour</button>
                <button class="popup-btn">Valider</button>
              </div>
            </div>

            <div class="wraper-popup wraper-popup--confirmation">
              <div class="popup popup--active">
                <img
                  src="./img/popup/close.svg"
                  alt="close"
                  class="popup-close"
                   onClick="closePopup('.wraper-popup--confirmation')"
                />

                <h2 class="popup-title">Confirmation</h2>

                <img
                  src="./img/delivery/trait.svg"
                  alt="trait"
                  class="popup-img"
                />

                <p class="popup-description">
                  Si il y a un escalier pour accéder à l’emplacement prévu pour
                  la borne, merci de s’assurer que quelqu’un sera sur place pour
                  aider le livreur.
                </p>

                <p class="popup-text">Merci de sélectionner « J’ai compris »</p>

                <div class="popup-row">
                  <div class="form-field__checkbox">
                    <input type="checkbox" id="compris" />
                    <span class="box"></span>
                    <label for="compris">J’ai compris</label>
                  </div>
                  <button class="popup-btn">OK</button>
                </div>
              </div>
            </div>
          </section>
        <?php } else { ?>
          <section class="delivery">
            <h1 class="delivery-title">Retrait boutique</h1>

            <img
              src="./img/delivery/trait.svg"
              alt="trait"
              class="delivery-img"
            />
            <p class="delivery-description">
              Retrouvez ci-dessous vos informations de retrait et de retour à
              notre showroom. <br />
              Merci de respecter les créneaux annoncés
            </p>

            <div class="delivery-list">
              <div class="list-items">
                <div class="list-item">
                  <button class="delivery-btn">Livraison</button>
                  <h2 class="item-title">Jour du retrait</h2>
                  <p class="item-text"><?php echo date("d.m.Y", strtotime($row_orders['take_date'])); ?></p>
                </div>
                <div class="list-item">
                  <h2 class="item-title">Créneau horaire</h2>
                  <p class="item-text"><?php echo ($row_orders['take_time'] != "" ? $row_orders['take_time'] : "--:--") ?></p>
                </div>
                <div class="list-item">
                  <h2 class="item-title">Adresse du retrait</h2>
                  <p class="item-text">
                    <?php echo ($row_orders['agency_id'] == 1 ? '5 Sentier des Marécages 93100 MONTREUIL' : '4 rue Voltaire – 33 130 Bègle'); ?>
                  </p>
                </div>
              </div>
              <div class="list-items">
                <div class="list-item">
                  <button class="delivery-btn">Retour</button>
                  <h2 class="item-title">Jour du retour</h2>
                  <p class="item-text"><?php echo date("d.m.Y", strtotime($row_orders['return_date'])); ?></p>
                </div>
                <div class="list-item">
                  <h2 class="item-title">Créneau horaire</h2>
                  <p class="item-text"><?php echo ($row_orders['return_time'] != "" ? $row_orders['return_time'] : "--:--") ?></p>
                </div>
                <div class="list-item">
                  <h2 class="item-title">Adresse du retour</h2>
                  <p class="item-text">
                    <?php echo ($row_orders['agency_id'] == 1 ? '5 Sentier des Marécages 93100 MONTREUIL' : '4 rue Voltaire – 33 130 Bègle'); ?>
                  </p>
                </div>
              </div>
            </div>

            <div class="delivery-attention">
              <img
                src="./img/delivery/attention.svg"
                alt="attention"
                class="attention-svg"
              />
              <p class="attention-text">
                <span class="text-bold">Attention !</span>
                Si vous n'êtes pas à jour de vos règlements,
                <br />Merci de préparer votre solde (chèque, espèces ou CB)
              </p>
            </div>

            <div class="delivery-formats">
              <a href="../faq/" class="formats-block" target="_blank">
                <img src="./img/delivery/faq.svg" alt="faq" />
                <h3 class="block-title">
                  Une question ? <span>Notre FAQ</span>
                </h3>
              </a>

              <div class="formats-list">
                <span class="list-title">Format de la valise</span>

                <?php if (strpos($row_orders['box_type'], 'Ring') !== false)  { ?>

                  <span class="list-br">
                    <span class="list-border">Largeur :</span> 60cm
                  </span>
                  <span class="list-br">
                    <span class="list-border">Hauteur :</span> 80cm
                  </span>
                  <span class="list-br">
                    <span class="list-border">Profondeur :</span> 30cm
                  </span>
                  <span class="list-br"
                    ><span class="list-border">Poids :</span> 30kg
                  </span>

              <?php } else { ?>

                  <span class="list-br">
                    <span class="list-border">Largeur :</span> 60cm
                  </span>
                  <span class="list-br">
                    <span class="list-border">Hauteur :</span> 90cm
                  </span>
                  <span class="list-br">
                    <span class="list-border">Profondeur :</span> 35cm
                  </span>
                  <span class="list-br"
                    ><span class="list-border">Poids :</span> 50kg
                  </span>

              <?php } ?>
              </div>
              <a href="Notices/<?php echo ($row_orders['box_type'] == "Ring" ? "Shootnbox - Notice Ring - Sans QR code copie.pdf" : "Shootnbox - Notice Vegas.pdf"); ?>" class="formats-block" target="_blank">
                <img src="./img/delivery/book.svg" alt="book" />
                <h3 class="block-title">
                  Télécharger la
                  <span>notice de montage</span>
                </h3>
              </a>
            </div>
          </section>
        <?php } ?>
        </main>
      </div>
    </div>
    <script src="./dropzone/js/dropzone.js"></script>
    <script type="text/javascript" src="./js/app.js"></script>

    <script type="text/javascript">


      const identical = document.getElementById('identical'),
      livraisons = document.querySelectorAll('.livraison'),
            retours = document.querySelectorAll('.retour'),
            form = document.querySelector('.delivery-form'),
            livraison = document.getElementById('livraison'),
            creneau = document.getElementById('creneau'),
            adresse1 = document.getElementById('adresse1'),
            contacts = document.getElementById('contacts'),
            contacts_phone = document.getElementById('contacts_phone'),
            dacces = document.getElementById('dacces'),
            escalier = document.getElementById('escalier'),
            jour = document.getElementById('jour'),
            horaire = document.getElementById('horaire'),
            adresse = document.getElementById('adresse'),
            place = document.getElementById('place'),
            place_phone = document.getElementById('place_phone'),
            code = document.getElementById('code'),
            escalier1 = document.getElementById('escalier1'),
            plan_access = document.getElementById('plan_access');

      escalier.addEventListener('change', (event) => {
          if (escalier.checked) {
            document.querySelector('.wraper-popup--confirmation').classList.add('wraper-popup--active');
          }
      });

      escalier1.addEventListener('change', (event) => {
          if (escalier1.checked) {
            document.querySelector('.wraper-popup--confirmation').classList.add('wraper-popup--active');
          }
      });

      document.querySelector('.wraper-popup--confirmation').querySelector('.popup-btn').addEventListener('click', (event) => {
        event.preventDefault();
        if (document.getElementById('compris').checked) {
          document.querySelector('.wraper-popup--confirmation').classList.remove('wraper-popup--active');
          document.getElementById('compris').checked = false;
        }
      });

      <?php if ($row_orders['delivery_valid'] == 1) { ?>
        retours.forEach((element) => {
          element.disabled = true;
        });
        livraisons.forEach((element) => {
          element.disabled = true;
        });
      <?php } ?>

      if (identical) {
        identical.addEventListener('change', (event) => {
          if (identical.checked) {
            retours.forEach((element) => {
              element.disabled = true;
              element.required = false;
              jour.value = livraison.value;
              horaire.value = creneau.value;
              adresse.value = adresse1.value;
              place.value = contacts.value;
              place_phone.value = contacts_phone.value;
              code.value = dacces.value;
              escalier1.checked = escalier.checked;
            });
          } else {
            retours.forEach((element) => {
              element.disabled = false;
              element.required = true;
            });
          }
        });
      }

      Dropzone.options.dropzoneForm = {
        paramName: 'image',
        url: '../manager/d26386b04e.php',
        method: 'post',
        maxFilesize: 5, // MB
        maxFiles: 1,
        acceptedFiles: '.jpeg,.png,.jpeg,.pdf',
        dictDefaultMessage: '<img src="./img/delivery/load.svg" alt="load" style="cursor: pointer;" />',
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
                  plan_access.value = plan_access.value.replace(file.xhr.responseText, '').trim();
                }
              });
              request.send(params);

            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);
          });

          this.on('success', function(file, responseText) {
            //plan_access.value = plan_access.value == '' ? responseText : plan_access.value + ' ' + responseText;
            plan_access.value = 'https://ftp.shootnbox.fr/uploads/images/' + responseText;
          });
        }
      }

    if (form) {
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        const request = new XMLHttpRequest();
        const url = '../manager/d26386b04e.php';
        const params = 'event=livraison' +
                      '&take_date=' + livraison.value +
                      '&take_time=' + creneau.value +
                      '&event_place=' + adresse1.value +
                      '&take_contact=' + contacts.value +
                      '&take_phone=' + contacts_phone.value +
                      '&take_access=' + dacces.value +
                      '&take_stairs=' + (escalier.checked ? 1 : 0) +
                      '&return_date=' + jour.value +
                      '&return_time=' + horaire.value +
                      '&return_place=' + adresse.value +
                      '&return_contact=' + place.value +
                      '&return_phone=' + place_phone.value +
                      '&return_access=' + code.value  +
                      '&return_stairs=' + (escalier1.checked ? 1 : 0) +
                      '&plan_access=' + plan_access.value;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
          if(request.readyState === 4 && request.status === 200) {
           // alert('Informations enregistrées !');
            const delivery_valid = document.getElementById('delivery_valid'),
                  delivery_a = document.getElementById('sidebar-navigation__btn--delivery');
            delivery_valid.disabled = true;
            delivery_valid.remove();
            delivery_a.classList.remove('sidebar-navigation__btn--msg');
            retours.forEach((element) => {
              element.disabled = true;
            });
            livraisons.forEach((element) => {
              element.disabled = true;
            });
            /*if (request.responseText == 'done') {

            } else {

            }*/
          }
        });
        request.send(params);
      });
    }

    function closePopup(popup_class) {
        const body = document.querySelector('body'),
              popup = document.querySelector(popup_class);
         document.getElementById('escalier').checked = false;
         document.getElementById('escalier1').checked = false;
        body.style.overflow = 'auto';
        popup.classList.remove('wraper-popup--active');
      }
    </script>
  </body>
</html>
