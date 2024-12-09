<?php
  @session_start();
  if (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
  } else {
    $lang = "fr";
  }
  if (file_exists("assets/lang/".$lang.".php")) {
    include_once("assets/lang/".$lang.".php");
  } else {
    include_once("assets/lang/fr.php");
  }
  $page_title = "Modifier une demande";
  $breadcrumbs = '<a href="orders_list.php?status='.$_GET['status'].'" title="Demande">Demande</a>';
  include("header.php");
  $result_orders = mysqli_query($conn, "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'shoot' AND TABLE_NAME = 'orders_new'");
  $row_orders = mysqli_fetch_assoc($result_orders);
  $orders_amount = $row_orders['AUTO_INCREMENT'];
  $error = 0;
  $js = 'var group = 0, new_group = 0;';
  if (isset($_GET['order_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
    if (mysqli_num_rows($result_orders) == 0) {
      $error = 1;
    } else {
      $row_orders = mysqli_fetch_assoc($result_orders);
      $delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || htmlspecialchars_decode($row_orders['delivery_options'], ENT_QUOTES) == "") ? 'Retrait boutique' : 'Livraison';
      $result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `order_id` = ".$row_orders['id']);
       switch ($row_orders['agency_id']) {
        case 1: $agency = " Montreuil"; break;
        case 2: $agency = " Bordeaux"; break;
        default:
          $agency = "";
        break;
      }
    }
  } else {
    $error = 1;
  }
?>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
      href="https://fonts.googleapis.com/css2?family=Exo+2:wght@200;300;400&display=swap"
      rel="stylesheet"
  />
  <style>
      .section_window_wrapper {
        position: fixed !important;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 10000;
        display: none;
      }
      .section_window {
        position: absolute !important;
        left: calc(50% - 325px);
        top: calc(50% - 325px);
        width: 650px;
        height: 650px;
        background: rgba(255, 255, 255, 1);
        border: 1px solid rgba(0, 0, 0, 0.498);
        border-radius: 5px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
          rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: start;
        align-items: center;
        z-index: 10001;
      }
      .section_window .window_btn__close {
        position: absolute;
        right: 10px;
        top: 10px;
        border: 1px solid black;
        width: 25px;
        height: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 10002;
      }
      .section_window .window_title {
        display: block;
        font-size: 24px;
        font-family: "Exo 2", sans-serif;
        font-weight: 400;
      }
      .section_window .box_window_list {
        width: 100%;
        height: 300px;
        display: flex;
        justify-content: center;
        margin: 0 auto;
        flex-wrap: wrap;
      }
      .section_window .window_inputs {
        width: 265px;
        height: 40px;
        background: #e5398d;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-radius: 5px;
        color: rgb(255, 255, 255);
        font-size: 14px;
        line-height: 1.2;
        font-weight: 200;
        font-family: "Exo 2", sans-serif;
        margin: 10px 5px;
        cursor: pointer;
      }
      .section_window .window_inputs > input {
        width: 15px;
        height: 15px;
        cursor: pointer;
      }
      .section_window .window_btn {
        color: white;
        background: #e5398d;
        border: none;
        border-radius: 5px;
        width: 150px;
        height: 40px;
        font-size: 22px;
        font-family: "Exo 2", sans-serif;
        font-weight: 300;
        cursor: pointer;
        position: absolute;
        bottom: 30px;
        left: calc(50% - 75px);
      }
      /* --------------------------------------------- */
      .window_inputs,
      .window_inputsTwo {
        position: relative;
        padding: 0px 15px;
        width: auto;
        height: 30px;
        background: #e5398d;
        display: flex;
        justify-content: start;
        align-items: center;
        border-radius: 5px;
        color: rgb(255, 255, 255);
        font-size: 14px;
        font-weight: 200;
        font-family: sans-serif;
        margin: 10px;
      }
      .window_inputs > input,
      .window_inputsTwo > input {
        width: 38px;
        height: 18px;
        cursor: pointer;
        color: #000;
      }
      .window_inputs > label,
      .window_inputsTwo > label {
        min-width: 110px;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 0;
        color: #fff;
      }
      .window_btn__close,
      .window_btn__closeTwo {
        position: absolute;
        left: 5px;
        top: 7px;
        width: 15px;
        height: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
      }
      .window_upBox,
      .window_downBox {
        width: 100%;
        height: auto;
        border: 1px solid #ccc;
        border-radius: 10px;
        display: flex;
        justify-content: start;
        flex-wrap: wrap;
        align-items: center;
        justify-content: start;
        margin: 10px 0px;
      }
      .vehicle_up_price,
      .vehicle_price {
        width: 70px !important;
      }

      .window_upBox_addBox,
      .window_downBox_addBox,
      .addTemplate_btn {
        width: 25px;
        height: 25px;
        transform: rotate(45deg);
        border: 1px solid black;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 4px 8px;
        cursor: pointer;
      }
      .remove_btn {
        width: 25px;
        height: 25px;
        padding: 4px;
        border: 1px solid black;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 4px 8px;
        cursor: pointer;
      }
      .window_upBox_addBox:hover > svg,
      .window_downBox_addBox:hover > svg,
      .addTemplate_btn:hover > svg,
      .remove_btn:hover > svg {
        transform: scale(1.2);
      }

      h4.title {
        font-weight: bold;
        color: #e5398d;
        border-bottom: 1px solid #e5398d;
      }

      .col5p {
        width: 5%;
      }

      .totalPanel {
        position: fixed;
        right: 0;
        bottom: 100px;
        width: 200px;
        padding: 15px 50px 15px 15px;
        background: #e5398d;
        color: #fff;
        border-radius: 25px 0px 0px 25px;
        opacity: 0.9;
        z-index: 10000;
      }

      .totalPanel input {
        width: 150px;
        padding: 3px 5px;
        border: none;
        border-radius: 25px;
        font-size: 14px;
        color: #000;
      }
    </style>
<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title">Modifier une demande</h4>
  </div>
  <div class="panel-body">
    <div id="fullsize-pos"></div>
    <form class="form-horizontal edit-order">
      <input type="hidden" class="image" value="" />
      <input type="hidden" class="images" value="" />
      <div class="form-group">
        <label class="col-md-3 control-label">Protection</label>
        <div class="col-md-4">
          <input type="checkbox" id="protection" <?php if ($row_orders['status'] == 2) {echo" checked";} ?> />&nbsp;
          <label for="protection">Desactiver /Activer</label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Template</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group add-btn<?php if (mysqli_num_rows($result_template_images) != 0) {echo" hide";} ?>">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-4">
          <div class="addTemplate_btn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                x="0px"
                y="0px"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                style="fill: #000000"
              >
                <path
                  d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
                ></path>
              </svg>
          </div>
        </div>
      </div>
      <div class="form-group add-template<?php if (mysqli_num_rows($result_template_images) == 0) {echo" hide";} ?>">
        <label class="col-md-3 control-label">Supplémentaire templates</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm2">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Formats</label>
        <div class="col-md-2">
          <select class="form-control format_id">
            <option value="0"<?php if ($row_orders['format_id'] == 0) {echo" selected";} ?>>Choisissez un format ...</option>
            <option value="1"<?php if ($row_orders['format_id'] == 1) {echo" selected";} ?>>Paysage</option>
            <option value="2"<?php if ($row_orders['format_id'] == 2) {echo" selected";} ?>>Portrait</option>
            <option value="3"<?php if ($row_orders['format_id'] == 3) {echo" selected";} ?>>Strip</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">INFORMATION AGENCE</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Commercial</label>
        <div class="col-md-4">
          <select class="form-control user_id"<?php if (isset($row_orders['user_id']) && $row_orders['user_id'] != 0  && $_SESSION['user']['id'] != 1) {echo" disabled";} ?>>
            <option value="0"<?php if (isset($row_orders['user_id']) && $row_orders['user_id'] == 0) {echo" selected";} ?>>Choisissez une commercial ...</option>
            <?php
              $result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `is_commercial` = 1 ORDER BY `last_name`");
              while($row_users = mysqli_fetch_assoc($result_users)) {
                echo'<option value="'.$row_users['id'].'" '.(isset($row_orders['user_id']) && $row_orders['user_id'] == $row_users['id'] ? "selected" : "").'>'.$row_users['first_name'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Agence</label>
        <div class="col-md-4">
          <select class="form-control agency_id">
            <option value="0"<?php if (isset($row_orders['agency_id']) && $row_orders['agency_id'] == 0) {echo" selected";} ?>>Choisissez une agence ...</option>
            <option value="1"<?php if (isset($row_orders['agency_id']) && $row_orders['agency_id'] == 1) {echo" selected";} ?>>Paris</option>
            <option value="2"<?php if (isset($row_orders['agency_id']) && $row_orders['agency_id'] == 2) {echo" selected";} ?>>Bordeaux</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Provenance</label>
        <div class="col-md-4">
          <input type="checkbox" id="invite" <?php if ($row_orders['gclid'] != "") {echo' class="hidden"';} ?> <?php if ($row_orders['invite'] == 1) {echo" checked";} ?> /> <label for="invite" <?php if ($row_orders['gclid'] != "") {echo' class="hidden"';} ?>>bouche à l'oreille </label>&nbsp;&nbsp;
          <input type="checkbox" id="marriage" <?php if ($row_orders['gclid'] != "") {echo' class="hidden"';} ?> <?php if ($row_orders['marriage'] == 1) {echo" checked";} ?> /> <label for="marriage" <?php if ($row_orders['gclid'] != "") {echo' class="hidden"';} ?>>mariage </label>
          <?php
            if ($row_orders['gclid'] != "") {
              echo' <input id="gclid" type="checkbox" checked disabled /> <label for="gclid">ADS</label>';
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">COORDONNÉES CLIENT</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Types</label>
        <div class="col-md-4">
          <select class="form-control select_type">
            <option value="Une entreprise"<?php if ($row_orders['select_type'] == 'Une entreprise') {echo" selected";} ?>>Une entreprise</option>
            <option value="Un particulier"<?php if ($row_orders['select_type'] == "Un particulier") {echo" selected";} ?>>Un particulier</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nom de la société</label>
        <div class="col-md-4">
          <input type="text" class="form-control societe" value="<?php echo $row_orders['societe'] ?>" placeholder="Nom de la société" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nom</label>
        <div class="col-md-4">
          <input type="text" class="form-control last_name" value="<?php echo $row_orders['last_name'] ?>" placeholder="Nom" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Prenom</label>
        <div class="col-md-4">
          <input type="text" class="form-control first_name" value="<?php echo $row_orders['first_name'] ?>" placeholder="Prenom" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Adresse</label>
        <div class="col-md-4">
          <textarea class="form-control address" placeholder="Adresse"><?php echo (isset($row_orders['address']) ? htmlspecialchars_decode($row_orders['address'], ENT_QUOTES) : '') ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Ville</label>
        <div class="col-md-4">
          <input type="text" class="form-control city" value="<?php echo (isset($row_orders['city']) ? $row_orders['city'] : '') ?>" placeholder="Ville" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Code postal</label>
        <div class="col-md-2">
          <input type="number" class="form-control cp" value="<?php echo (isset($row_orders['cp']) ? $row_orders['cp'] : '') ?>" placeholder="Code postal" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Adresse mail</label>
        <div class="col-md-4">
          <input type="text" class="form-control email" value="<?php echo $row_orders['email'] ?>" placeholder="Adresse mail" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Numéro de téléphone</label>
        <div class="col-md-4">
          <input type="tel" class="form-control phone" value="<?php echo $row_orders['phone'] ?>" placeholder="Numéro de téléphone" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">DEVIS</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label"><br />Présent sur le devis</label>
        <div class="col-md-2">
          <label class="control-label"><b>Devis N°</b></label>
          <input type="text" class="form-control num_id" value="<?php echo ($row_orders['num_id'] != '' ? $row_orders['num_id'] : 'DE'.($orders_amount + 5000)) ?>" placeholder="Devis N"<?php if($row_orders['status'] == 2) {echo" readonly";} ?> />
        </div>
        <div class="col-md-2">
          <label class="control-label"><b>Sage</b></label>
          <input type="text" class="form-control sage" value="<?php echo $row_orders['sage']; ?>" placeholder="Sage" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Mot de passe</label>
        <div class="col-md-4">
          <input type="text" class="form-control password numeric" value="<?php echo $row_orders['password'] ?>" placeholder="Mot de passe" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">EVÈNEMENT</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Date de l’évènement</label>
        <div class="col-md-4">
          <input type="text" class="form-control event_date" value="<?php echo $row_orders['event_date'] ?>" placeholder="Date de l’évènement" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Type d’événement</label>
        <div class="col-md-4">
          <input type="text" class="form-control event_type" value="<?php echo $row_orders['event_type'] ?>" placeholder="Type d’événement" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">
            BORNE
            <a class="add_borne addTemplate_btn" style="display: inline-flex;">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                x="0px"
                y="0px"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                style="fill: #000000"
              >
                <path
                  d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
                ></path>
              </svg>
            </a>
          </h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="borne">
        <div class="form-group">
          <label class="col-md-3 control-label">Borne</label>
          <div class="col-md-2">
            <select class="form-control box_type box_type_0" data-index="0">
              <option value="" data-price="0" selected>Choisissez le type de stand</option>
              <?php
              if (strpos($row_orders['select_type'], 'entreprise') !== false) {
                $price_prefix = 'e';
                $price_sufix = 'HT';
              } else {
                $price_prefix = '';
                $price_sufix = 'TTC';
              }
              $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types`");
              while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
                  $prices_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
                 echo'<option value="'.$row_bornes_types['title'].'" data-price="'.$prices_arr[0].'"'.($row_bornes_types['title'] == $row_orders['box_type'] ? ' selected' : '').' data-group="'.$group.'">'.$row_bornes_types['title'].' - '.$prices_arr[0].'€</option>';
                 if ($row_bornes_types['title'] == $row_orders['box_type']) {
                  $js = 'var group = 1, new_group = 1;';
                  $price = $prices_arr[0];
                  $options_ids = $row_bornes_types[$price_prefix.'options_ids'];
                  $delivery_ids = $row_bornes_types['delivery_ids'];
                }
              }
              /*if (strpos($row_orders['select_type'], 'entreprise') !== false) {
                $prices_arr = json_decode(file_get_contents("../enterprise_price.ini"), true);
              } else {
                $prices_arr = json_decode(file_get_contents("../particulier_price.ini"), true);
              }
              foreach ($prices_arr as $key => $value) {
                switch($key) {
                  case 'ring_price': $title = "Ring"; $group = 1; break;
                  case 'vegas_price': $title = "Vegas"; $group = 2; break;
                  case 'vegas_price_2': $title = "Vegas_800"; $group = 2; break;
                  case 'vegas_price_1200': $title = "Vegas_1200"; $group = 2; break;
                  case 'miroir_price': $title = "Miroir"; $group = 3; break;
                  case 'miroir_price_2': $title = "Miroir_800"; $group = 3; break;
                  case 'miroir_price_1200': $title = "Miroir_1200"; $group = 3; break;
                  case 'spinner_price': $title = "Spinner_360"; $group = 4; break;
                  case 'vr_price': $title = "Réalité_Virtuelle"; $group = 4; break;
                }
                echo'<option value="'.$title.'" data-price="'.$value.'"'.($title == $row_orders['box_type'] ? ' selected' : '').' data-group="'.$group.'">'.$title.' - '.$value.'€</option>';
                if ($title == $row_orders['box_type']) {
                  $js = 'var group = '.$group.', new_group = '.$group.';';
                  $price = $value;
                }
              }*/
              ?>
            </select>
          </div>
          <label class="col-md-1 control-label">Prix, €</label>
          <div class="col-md-1">
            <input type="text" class="form-control price price_0" value="<?php echo ($row_orders['price'] != 0 ? $row_orders['price'] : $price) ?>" placeholder="Prix, €" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">Quantité</label>
          <div class="col-md-2">
            <input type="number" min="0" step="1" class="form-control amount" value="<?php echo (isset($row_orders['amount']) ? $row_orders['amount'] : '1') ?>" placeholder="Quantité" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">Mes options</label>
          <div class="col-md-9">
            <div class="window_upBox">
              <div class="window_upBox_addBox window_upBox_addBox_0" data-idx="0">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  x="0px"
                  y="0px"
                  width="15"
                  height="15"
                  viewBox="0 0 24 24"
                  style="fill: #000000"
                >
                  <path
                    d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
                  ></path>
                </svg>
              </div>
              <?php
                /*if (strpos($row_orders['select_type'], 'entreprise') !== false) {
                  $data = json_decode(file_get_contents("../enterprise.ini"), true);
                  switch($row_orders['box_type']) {
                    case "Ring": $options = $data['ring2']['options']; $deliverys = $data['ring2']['delivery']; break;
                    case "Vegas":
                    case "Vegas_800":
                    case "Vegas_1200": $options = $data['vegas']['options']; $deliverys = $data['vegas']['delivery']; break;
                    case "Miroir":
                    case "Miroir_800":
                    case "Miroir_1200": $options = $data['miroir']['options']; $deliverys = $data['miroir']['delivery']; break;
                    case "Spinner_360": $options = $data['spinner']['options']; $deliverys = $data['spinner']['delivery']; break;
                    case "Réalité_Virtuelle": $options = $data['vr2']['options']; $deliverys = $data['vr2']['delivery']; break;
                  }
                } else {
                  $data = json_decode(file_get_contents("../particulier.ini"), true);
                  switch($row_orders['box_type']) {
                    case "Ring": $options = $data['ring']['options']; $deliverys = $data['ring']['delivery']; break;
                    case "Vegas":
                    case "Vegas_800":
                    case "Vegas_1200": $options = $data['vegas']['options']; $deliverys = $data['vegas']['delivery']; break;
                    case "Miroir":
                    case "Miroir_800":
                    case "Miroir_1200": $options = $data['miroir']['options']; $deliverys = $data['miroir']['delivery']; break;
                    case "Spinner_360": $options = $data['spinner']['options']; $deliverys = $data['spinner']['delivery']; break;
                    case "Réalité_Virtuelle": $options = $data['vr2']['options']; $deliverys = $data['vr2']['delivery']; break;
                  }
                }
                //$deliverys[] = array('name' => 'Kilométriques supplémentaires', 'price' => 49);
                $html_popup = "";
                $selected_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(".", "", str_replace(", ", ",", trim($row_orders['selected_options']))))))));
                $selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))));
                $i = 0;
                foreach ($options as $key => $value) {
                  $html_popup .= '<div class="window_inputs">
                    <input type="checkbox" id="option'.$key.'" name="option'.$key.'" value="'.$value['name'].'" class="box_option" data-price="'.$value['price'].'"'.(in_array($value['name'], $selected_options_arr) ? ' checked' : '').' />
                    <label for="option'.$key.'">'.$value['name'].'</label>
                  </div>';
                  if (in_array($value['name'], $selected_options_arr)) {
                    $amount_arr = explode(":", $selected_options_value_arr[$i]);
                    echo'<div class="window_inputs window_inputs_item1 window_inputs_i_0 window_inputs_up'.$key.'">
                      <label for="vehicle_up'.$key.'">'.$value['name'].'</label>
                      <input class="vehicle_up" id="vehicle_up'.$key.'" name="vehicle_up'.$key.'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$value['name'].'" data-price="'.$value['price'].'" data-idx="'.$key.'" onChange="calcTotal()" />
                      &nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price'.$key.'" name="vehicle_up_price'.$key.'" type="number" min="0" step="1" value="'.(isset($amount_arr[2]) ? $amount_arr[2] : $value['price']).'" onChange="calcTotal()" />&nbsp;&euro;
                      <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$key.')">
                        <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                      </svg>
                    </div>';
                    $i++;
                  }
                }*/
                 $html_popup = "";
                //$selected_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(".", "", str_replace(", ", ",", trim($row_orders['selected_options']))))))));
                $row_orders['selected_options'] = trim(htmlspecialchars_decode($row_orders['selected_options'], ENT_QUOTES).",".htmlspecialchars_decode($row_orders['selected_personal_options'], ENT_QUOTES), ",");
                $selected_options_arr = array();
                $options_arr = explode(",", trim($row_orders['selected_options']));
                array_push($options_arr, explode(",", trim($row_orders['selected_options'])));
                foreach ($options_arr as $key => $value) {
                  $option_arr = explode(":", $value);
                  if (trim($option_arr[0]) != "Livraison" && trim($option_arr[0]) != "Retrait boutique") {
                    $selected_options_arr[] = trim($option_arr[0]);
                  }
                }
                $selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))));
                $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$options_ids.")");
                $i = 0;
                while($row_options = mysqli_fetch_assoc($result_options)) {
                   $html_popup .= '<div class="window_inputs">
                    <input type="checkbox" id="option'.$row_options['id'].'" name="option'.$row_options['id'].'" value="'.$row_options['title'].'" class="box_option" data-price="'.$row_options[$price_prefix.'price'].'"'.(in_array($row_options['title'], $selected_options_arr) ? ' checked' : '').' />
                    <label for="option'.$row_options['id'].'">'.$row_options['title'].'</label>
                  </div>';
                  if (in_array(trim($row_options['title']), $selected_options_arr)) {

                    $amount_arr = explode(":", $selected_options_value_arr[$i]);
                    echo'<div class="window_inputs window_inputs_item1 window_inputs_i_0 window_inputs_up'.$row_options['id'].'">
                      <label for="vehicle_up'.$row_options['id'].'">'.$row_options['title'].'</label>
                      <input class="vehicle_up" id="vehicle_up'.$row_options['id'].'" name="vehicle_up'.$row_options['id'].'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$row_options['title'].'" data-price="'.$row_options[$price_prefix.'price'].'" data-idx="'.$row_options['id'].'" onChange="calcTotal()" />
                      &nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price'.$row_options['id'].'" name="vehicle_up_price'.$row_options['id'].'" type="number" min="0" step="1" value="'.(isset($amount_arr[2]) ? $amount_arr[2] : $value['price']).'" onChange="calcTotal()" />&nbsp;&euro;
                      <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$row_options['id'].')">
                        <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                      </svg>
                    </div>';
                    $i++;
                  }
                }
                $html_popup .= '<button class="window_btn">Valider</div>';
              ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-3 control-label">Numéro de la borne</label>
          <div class="col-md-4">
            <select class="form-control box_id box_id_0" multiple="true"<?php if ($_GET['status'] != 2) echo" disabled"; ?>>
              <option value="">Numéro de la borne...</option>
              <?php
              if ($row_orders['agency_id'] != 0) {
                $prefix = $row_orders['agency_id'] == 1 ? "P" : "B";
                $box_ids = explode(",", $row_orders['box_id']);
                $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` = 1");
                    $row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
                    $html = '<option value="">Numéro de la borne...</option>';

                        for ($i = 1; $i <= $row_bornes_types['amount']; $i++) {
                          $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'R$i/$prefix'");
                          if (mysqli_num_rows($result_repair) == 0) {
                            if ($take_date != "") {
                              $show = true;
                              $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `box_id` LIKE '%R$i%'");
                              while($row_orders = mysqli_fetch_assoc($result_orders)) {
                                if (substr($take_date, 0, 10) == substr($row_orders['take_date'], 0, 10)) {
                                  $show = false;
                                }
                              }
                              if ($show) {
                                $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>'.$i.'/'.$prefix.'</option>';
                              }
                            } else {
                              $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>'.$i.'/'.$prefix.'</option>';
                            }
                          }
                        }
                    echo $html;
              }
              ?>
            </select>
          </div>
        </div>
      </div>
      <?php
        $k = 1;
        $result_bornes = mysqli_query($conn, "SELECT * FROM `bornes` WHERE `order_id` = ".$order_id);
        while($row_bornes = mysqli_fetch_assoc($result_bornes)) {
      ?>
          <div class="borne">
            <div class="col-md-1 col5p"></div>
            <div class="col-md-10"><h4 class="title"><div class="remove_btn" data-idx="<?php echo $k; ?>"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve"><defs></defs><g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)"><path d="M 86.5 48.5 h -83 C 1.567 48.5 0 46.933 0 45 s 1.567 -3.5 3.5 -3.5 h 83 c 1.933 0 3.5 1.567 3.5 3.5 S 88.433 48.5 86.5 48.5 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(29,29,27); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path><path d="M 86.5 48.5 h -83 C 1.567 48.5 0 46.933 0 45 s 1.567 -3.5 3.5 -3.5 h 83 c 1.933 0 3.5 1.567 3.5 3.5 S 88.433 48.5 86.5 48.5 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(29,29,27); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path></g></svg></div></h4></div>
            <div class="col-md-1 col5p"></div>
            <div class="form-group">
              <label class="col-md-3 control-label">Borne</label>
              <div class="col-md-2">
                <select class="form-control box_type box_type_<?php echo $k; ?>" data-index="<?php echo $k; ?>">
                  <option value="" data-price="0" selected>Choisissez le type de stand</option>
                  <?php
                    $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types`");
                    while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
                       echo'<option value="'.$row_bornes_types['title'].'" data-price="'.$row_bornes_types[$price_prefix.'price'].'"'.($row_bornes_types['title'] == $row_bornes['box_type'] ? ' selected' : '').' data-group="'.$group.'">'.$row_bornes_types['title'].' - '.$row_bornes_types[$price_prefix.'price'].'€</option>';
                    }
                  ?>
                </select>
              </div>
              <label class="col-md-1 control-label">Prix, €</label>
              <div class="col-md-1">
                <input type="text" class="form-control price price_<?php echo $k; ?>" value="<?php echo ($row_bornes['price'] != 0 ? $row_bornes['price'] : $price) ?>" placeholder="Prix, €" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">Quantité</label>
              <div class="col-md-2">
                <input type="number" min="0" step="1" class="form-control amount" value="<?php echo (isset($row_bornes['amount']) ? $row_bornes['amount'] : '1') ?>" placeholder="Quantité" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">Mes options</label>
              <div class="col-md-9">
                <div class="window_upBox">
                  <div class="window_upBox_addBox window_upBox_addBox_<?php echo $k; ?>" data-idx="<?php echo $k; ?>">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      x="0px"
                      y="0px"
                      width="15"
                      height="15"
                      viewBox="0 0 24 24"
                      style="fill: #000000"
                    >
                      <path
                        d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
                      ></path>
                    </svg>
                  </div>
                  <?php
                    $html_popup = "";
                    //$selected_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(".", "", str_replace(", ", ",", trim($row_bornes['selected_options']))))))));
                     $selected_options_arr = array();
                    $options_arr = explode(",", trim($row_bornes['selected_options']));
                    foreach ($options_arr as $key => $value) {
                      $option_arr = explode(":", $value);
                      if (trim($option_arr[0]) != "Livraison" && trim($option_arr[0]) != "Retrait boutique") {
                        $selected_options_arr[] = trim($option_arr[0]);
                      }
                    }
                    $selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_bornes['selected_options'])))));
                    $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$options_ids.")");
                    $i = 0;
                    while($row_options = mysqli_fetch_assoc($result_options)) {
                       $html_popup .= '<div class="window_inputs">
                        <input type="checkbox" id="option'.$row_options['id'].'" name="option'.$row_options['id'].'" value="'.$row_options['title'].'" class="box_option" data-price="'.$row_options[$price_prefix.'price'].'"'.(in_array($row_options['title'], $selected_options_arr) ? ' checked' : '').' />
                        <label for="option'.$row_options['id'].'">'.$row_options['title'].'</label>
                      </div>';
                      if (in_array($row_options['title'], $selected_options_arr)) {
                          $amount_arr = explode(":", $selected_options_value_arr[$i]);
                        echo'<div class="window_inputs window_inputs_item1 window_inputs_i_0 window_inputs_up'.$row_options['id'].'">
                          <label for="vehicle_up'.$row_options['id'].'">'.$row_options['title'].'</label>
                          <input class="vehicle_up" id="vehicle_up'.$row_options['id'].'" name="vehicle_up'.$row_options['id'].'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$row_options['title'].'" data-price="'.$row_options[$price_prefix.'price'].'" data-idx="'.$row_options['id'].'" onChange="calcTotal()" />
                          &nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price'.$row_options['id'].'" name="vehicle_up_price'.$row_options['id'].'" type="number" min="0" step="1" value="'.(isset($amount_arr[2]) ? $amount_arr[2] : $value['price']).'" onChange="calcTotal()" />&nbsp;&euro;
                          <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$row_options['id'].')">
                            <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                          </svg>
                        </div>';
                        $i++;
                      }
                    }
                    $html_popup .= '<button class="window_btn">Valider</div>';
                  ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">Numéro de la borne</label>
              <div class="col-md-4">
                <select class="form-control box_id box_id_<?php echo $k; ?>" multiple="true"<?php if ($_GET['status'] != 2) echo" disabled"; ?>>
                  <option value="">Numéro de la borne...</option>
                  <?php
                  $html = "";
                  if ($row_orders['agency_id'] != 0) {
                    $prefix = $row_orders['agency_id'] == 1 ? "P" : "B";
                    $box_ids = explode(",", $row_bornes['box_id']);
                    $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` = 1");
                    $row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
                    $html = '<option value="">Numéro de la borne...</option>';

                        for ($i = 1; $i <= $row_bornes_types['amount']; $i++) {
                          $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'R$i/$prefix'");
                          if (mysqli_num_rows($result_repair) == 0) {
                            if ($take_date != "") {
                              $show = true;
                              $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `box_id` LIKE '%R$i%'");
                              while($row_orders = mysqli_fetch_assoc($result_orders)) {
                                if (substr($take_date, 0, 10) == substr($row_orders['take_date'], 0, 10)) {
                                  $show = false;
                                }
                              }
                              if ($show) {
                                $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>'.$i.'/'.$prefix.'</option>';
                              }
                            } else {
                              $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>'.$i.'/'.$prefix.'</option>';
                            }
                          }
                        }
                    echo $html;
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
      <?php
        $k++;
        }
      ?>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">STATUT</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Statut</label>
        <div class="col-md-4">
          <select class="form-control status">
            <option value="0"<?php if ($row_orders['status'] == "0") {echo" selected";} ?>>Demandes</option>
            <!--option value="1"<?php if ($row_orders['status'] == "1") {echo" selected";} ?>>Attentes</option-->
            <option value="2"<?php if ($row_orders['status'] == "2") {echo" selected";} ?>>Réservations</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">LOGISTIQUE</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Ma Livraison ou retrait boutique</label>
        <div class="col-md-4">
          <select class="form-control delivery">
            <option value="Retrait boutique"<?php if ($delivery == 'Retrait boutique') {echo" selected";} ?>>Retrait boutique</option>
            <option value="Livraison"<?php if ($delivery == "Livraison") {echo" selected";} ?>>Livraison</option>
          </select>
        </div>
      </div>
      <div class="form-group delivery-row<?php if ($delivery == 'Retrait boutique') {echo" hide";} ?>">
        <label class="col-md-3 control-label">Livraison</label>
        <div class="col-md-9">
          <div class="window_downBox">
            <div class="window_downBox_addBox">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                x="0px"
                y="0px"
                width="15"
                height="15"
                viewBox="0 0 24 24"
                style="fill: #000000"
              >
                <path
                  d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
                ></path>
              </svg>
            </div>
            <?php
              $html_popup2 = "";
              $delivery_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(".", "", htmlspecialchars_decode($row_orders['delivery_options'], ENT_QUOTES)))));
              $delivery_options_value_arr = explode(",", htmlspecialchars_decode($row_orders['delivery_options'], ENT_QUOTES));
              $i = 0;
              /*foreach ($deliverys as $key => $value) {
                if ($value['name'] != "Retrait boutique") {
                  $html_popup2 .= '<div class="window_inputs">
                    <input type="checkbox" id="delivery'.$key.'" name="delivery'.$key.'" value="'.$value['name'].'" class="box_option" data-price="'.$value['price'].'"'.(in_array($value['name'], $delivery_options_arr) ? ' checked' : '').' />
                    <label for="delivery'.$key.'">'.$value['name'].'</label>
                  </div>';
                  if (in_array($value['name'], $delivery_options_arr)) {
                    $amount_arr = explode(":", $delivery_options_value_arr[$i]);
                    if ($value['name'] == "Kilométriques supplémentaires") {
                      echo'<div class="window_inputs window_inputs_item2 window_inputs_down'.$key.'">
                        <label for="vehicle_down'.$key.'">'.$value['name'].'</label>
                        <input class="vehicle_down hide" id="vehicle_down'.$key.'" name="vehicle_down'.$key.'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$value['name'].'" data-idx="'.$key.'" onChange="calcTotal()" />
                        &nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price'.$key.'" name="vehicle_price'.$key.'" type="number" min="0" step="1" value="'.$amount_arr[2].'" onChange="calcTotal()" />&nbsp;&euro;
                        <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery('.$key.')">
                          <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                        </svg>
                      </div>';
                    } else {
                      echo'<div class="window_inputs window_inputs_item2 window_inputs_down'.$key.'">
                        <label for="vehicle_down'.$key.'">'.$value['name'].'</label>
                        <input class="vehicle_down" id="vehicle_down'.$key.'" name="vehicle_down'.$key.'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$value['name'].'" data-idx="'.$key.'" onChange="calcTotal()" />
                         &nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price'.$key.'" name="vehicle_price'.$key.'" type="number" min="0" step="1" value="'.$amount_arr[2].'" onChange="calcTotal()" />&nbsp;&euro;
                        <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery('.$key.')">
                          <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                        </svg>
                      </div>';
                    }
                    $i++;
                  }
                }
              }*/
              $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery` WHERE `id` IN (".$delivery_ids.")");
              $i = 0;
              while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
                if ($row_delivery['title'] != "Retrait boutique") {
                  $html_popup2 .= '<div class="window_inputs">
                    <input type="checkbox" id="delivery'.$row_delivery['id'].'" name="delivery'.$row_delivery['id'].'" value="'.$row_delivery['title'].'" class="box_option" data-price="'.$row_delivery[$price_prefix.'price'].'"'.(in_array($row_delivery['title'], $delivery_options_arr) ? ' checked' : '').' />
                    <label for="delivery'.$row_delivery['id'].'">'.$row_delivery['title'].'</label>
                  </div>';
                  if (in_array($row_delivery['title'], $delivery_options_arr)) {
                    $amount_arr = explode(":", $delivery_options_value_arr[$i]);
                    if ($row_delivery['title'] == "Kilométriques supplémentaires") {
                      echo'<div class="window_inputs window_inputs_item2 window_inputs_down'.$row_delivery['id'].'">
                        <label for="vehicle_down'.$row_delivery['id'].'">'.$row_delivery['title'].'</label>
                        <input class="vehicle_down hide" id="vehicle_down'.$row_delivery['id'].'" name="vehicle_down'.$row_delivery['id'].'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$row_delivery['title'].'" data-idx="'.$row_delivery['id'].'" onChange="calcTotal()" />
                        &nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price'.$row_delivery['id'].'" name="vehicle_price'.$row_delivery['id'].'" type="number" min="0" step="1" value="'.$amount_arr[2].'" onChange="calcTotal()" />&nbsp;&euro;
                        <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery('.$row_delivery['id'].')">
                          <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                        </svg>
                      </div>';
                    } else {
                      echo'<div class="window_inputs window_inputs_item2 window_inputs_down'.$row_delivery['id'].'">
                        <label for="vehicle_down'.$row_delivery['id'].'">'.$row_delivery['title'].'</label>
                        <input class="vehicle_down" id="vehicle_down'.$row_delivery['id'].'" name="vehicle_down'.$row_delivery['id'].'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$row_delivery['title'].'" data-idx="'.$row_delivery['id'].'" onChange="calcTotal()" />
                         &nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price'.$row_delivery['id'].'" name="vehicle_price'.$row_delivery['id'].'" type="number" min="0" step="1" value="'.$amount_arr[2].'" onChange="calcTotal()" />&nbsp;&euro;
                        <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery('.$row_delivery['id'].')">
                          <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                        </svg>
                      </div>';
                    }
                    $i++;
                  }
                }
              }
              $html_popup2 .= '<button class="window_btn">Valider</div>';
            ?>
          </div>
        </div>
      </div>
      <div class="form-group delivery-row<?php if ($delivery == 'Retrait boutique') {echo" hide";} ?>">
        <label class="col-md-3 control-label">Créneau livraison</label>
        <div class="col-md-2">
          <select class="form-control event_time">
            <option value="0"<?php if ($row_orders['event_time'] == 0) {echo" selected";} ?>>Créneau livraison...</option>
            <option value="7"<?php if ($row_orders['event_time'] == 7) {echo" selected";} ?>>7h à 13h</option>
            <option value="8"<?php if ($row_orders['event_time'] == 8) {echo" selected";} ?>>13h à 19h</option>
          </select>
        </div>
      </div>
      <div class="form-group delivery-row<?php if ($delivery == 'Retrait boutique') {echo" hide";} ?>">
        <label class="col-md-3 control-label">Lieu du <span class="delivery-type"><?php echo $delivery; ?></label>
        <div class="col-md-2">
          <input type="text" class="form-control event_place" value="<?php echo $row_orders['event_place'] ?>" placeholder="Lieu du livraison" />
        </div>
        <label class="col-md-2 control-label">Lieu du retour</label>
        <div class="col-md-2">
          <input type="text" class="form-control return_place" value="<?php echo $row_orders['return_place'] ?>" placeholder="Lieu du retour" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Date du <span class="delivery-type"><?php echo $delivery; ?></span></label>
        <div class="col-md-2">
          <input type="text" class="form-control take_date" value="<?php echo $row_orders['take_date'] ?>" placeholder="Date du livraison" />
        </div>
        <label class="col-md-2 control-label">Date du retour</label>
        <div class="col-md-2">
          <input type="text" class="form-control return_date" value="<?php echo $row_orders['return_date'] ?>" placeholder="Date du retour" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Heure du <span class="delivery-type"><?php echo $delivery; ?></span></label>
        <div class="col-md-2">
          <select class="form-control take_time">
            <option value="" <?php if ($row_orders['take_time'] == "") {echo" selected";} ?>>Heure du livration / retrait</option>

            <option class="livraison-time" value="7h - 9h" <?php if ($row_orders['take_time'] == "7h - 9h") {echo" selected";} ?>>7h - 9h</option>
            <option class="livraison-time" value="7h30 - 9h30" <?php if ($row_orders['take_time'] == "7h30 - 9h30") {echo" selected";} ?>>7h30 - 9h30</option>

            <option class="livraison-time" value="8h - 10h" <?php if ($row_orders['take_time'] == "8h - 10h") {echo" selected";} ?>>8h - 10h</option>
            <option class="livraison-time" value="8h30 - 10h30" <?php if ($row_orders['take_time'] == "8h30 - 10h30") {echo" selected";} ?>>8h30 - 10h30</option>

            <option class="livraison-time" value="9h - 11h" <?php if ($row_orders['take_time'] == "9h - 11h") {echo" selected";} ?>>9h - 11h</option>
            <option class="livraison-time" value="9h30 - 11h30" <?php if ($row_orders['take_time'] == "9h30 - 11h30") {echo" selected";} ?>>9h30 - 11h30</option>

            <option class="livraison-time" value="10h - 12h" <?php if ($row_orders['take_time'] == "10h - 12h") {echo" selected";} ?>>10h - 12h</option>
            <option class="livraison-time" value="10h30 - 12h30" <?php if ($row_orders['take_time'] == "10h30 -12h30") {echo" selected";} ?>>10h30 - 12h30</option>

            <option class="livraison-time" value="11h - 13h" <?php if ($row_orders['take_time'] == "11h - 13h") {echo" selected";} ?>>11h - 13h</option>
            <option class="livraison-time" value="11h30 - 13h30" <?php if ($row_orders['take_time'] == "11h30 - 13h30") {echo" selected";} ?>>11h30 - 13h30</option>

            <option class="livraison-time" value="12h - 14h" <?php if ($row_orders['take_time'] == "12h - 14h") {echo" selected";} ?>>12h - 14h</option>
            <option class="livraison-time" value="12h30 - 14h30" <?php if ($row_orders['take_time'] == "12h30 - 14h30") {echo" selected";} ?>>12h30 - 14h30</option>

            <option class="livraison-time" value="13h - 15h" <?php if ($row_orders['take_time'] == "13h - 15h") {echo" selected";} ?>>13h - 15h</option>
            <option class="livraison-time" value="13h30 - 15h30" <?php if ($row_orders['take_time'] == "13h30 - 15h30") {echo" selected";} ?>>13h30 - 15h30</option>

            <option class="livraison-time" value="14h - 16h" <?php if ($row_orders['take_time'] == "14h - 16h") {echo" selected";} ?>>14h - 16h</option>
            <option class="livraison-time" value="14h30 - 16h30" <?php if ($row_orders['take_time'] == "14h30 - 16h30") {echo" selected";} ?>>14h30 - 16h30</option>

            <option class="livraison-time" value="15h - 17h" <?php if ($row_orders['take_time'] == "15h - 17h") {echo" selected";} ?>>15h - 17h</option>
            <option class="livraison-time" value="15h30 - 17h30" <?php if ($row_orders['take_time'] == "15h30 - 17h30") {echo" selected";} ?>>15h30 - 17h30</option>

            <option class="livraison-time" value="16h - 18h" <?php if ($row_orders['take_time'] == "16h - 18h") {echo" selected";} ?>>16h - 18h</option>
            <option class="livraison-time" value="16h30 - 18h30" <?php if ($row_orders['take_time'] == "16h30 - 18h30") {echo" selected";} ?>>16h30 - 18h30</option>

            <option class="livraison-time" value="17h - 19h" <?php if ($row_orders['take_time'] == "17h - 19h") {echo" selected";} ?>>17h - 19h</option>
            <option class="livraison-time" value="17h30 - 19h30" <?php if ($row_orders['take_time'] == "17h30 - 19h30") {echo" selected";} ?>>17h30 - 19h30</option>

            <option class="livraison-time" value="18h - 20h" <?php if ($row_orders['take_time'] == "18h - 20h") {echo" selected";} ?>>18h - 20h</option>
            <option class="livraison-time" value="18h30 - 20h30" <?php if ($row_orders['take_time'] == "18h30 - 20h30") {echo" selected";} ?>>18h30 - 20h30</option>

            <option class="livraison-time" value="19h - 21h" <?php if ($row_orders['take_time'] == "19h - 21h") {echo" selected";} ?>>19h - 21h</option>

            <option class="retrait-time" value="9h30 - 14h" <?php if ($row_orders['take_time'] == "9h30 - 14h") {echo" selected";} ?>>9h30 - 14h</option>
          </select>
        </div>
        <label class="col-md-2 control-label">Heure du retour</label>
        <div class="col-md-2">
          <select class="form-control return_time">
            <option class="livraison-time" value="" <?php if ($row_orders['return_time'] == "") {echo" selected";} ?>>Heure du retour</option>

            <option class="livraison-time" value="7h - 9h" <?php if ($row_orders['return_time'] == "7h - 9h") {echo" selected";} ?>>7h - 9h</option>
            <option class="livraison-time" value="7h30 - 9h30" <?php if ($row_orders['return_time'] == "7h30 - 9h30") {echo" selected";} ?>>7h30 - 9h30</option>

            <option class="livraison-time" value="8h - 10h" <?php if ($row_orders['return_time'] == "8h - 10h") {echo" selected";} ?>>8h - 10h</option>
            <option class="livraison-time" value="8h30 - 10h30" <?php if ($row_orders['return_time'] == "8h30 - 10h30") {echo" selected";} ?>>8h30 - 10h30</option>

            <option class="livraison-time" value="9h - 11h" <?php if ($row_orders['return_time'] == "9h - 11h") {echo" selected";} ?>>9h - 11h</option>
            <option class="livraison-time" value="9h30 - 11h30" <?php if ($row_orders['return_time'] == "9h30 - 11h30") {echo" selected";} ?>>9h30 - 11h30</option>

            <option class="livraison-time" value="10h - 12h" <?php if ($row_orders['return_time'] == "10h - 12h") {echo" selected";} ?>>10h - 12h</option>
            <option class="livraison-time" value="10h30 - 12h30" <?php if ($row_orders['return_time'] == "10h30 -12h30") {echo" selected";} ?>>10h30 - 12h30</option>

            <option class="livraison-time" value="11h - 13h" <?php if ($row_orders['return_time'] == "11h - 13h") {echo" selected";} ?>>11h - 13h</option>
            <option class="livraison-time" value="11h30 - 13h30" <?php if ($row_orders['return_time'] == "11h30 - 13h30") {echo" selected";} ?>>11h30 - 13h30</option>

            <option class="livraison-time" value="12h - 14h" <?php if ($row_orders['return_time'] == "12h - 14h") {echo" selected";} ?>>12h - 14h</option>
            <option class="livraison-time" value="12h30 - 14h30" <?php if ($row_orders['return_time'] == "12h30 - 14h30") {echo" selected";} ?>>12h30 - 14h30</option>

            <option class="livraison-time" value="13h - 15h" <?php if ($row_orders['return_time'] == "13h - 15h") {echo" selected";} ?>>13h - 15h</option>
            <option class="livraison-time" value="13h30 - 15h30" <?php if ($row_orders['return_time'] == "13h30 - 15h30") {echo" selected";} ?>>13h30 - 15h30</option>

            <option class="livraison-time" value="14h - 16h" <?php if ($row_orders['return_time'] == "14h - 16h") {echo" selected";} ?>>14h - 16h</option>
            <option class="livraison-time" value="14h30 - 16h30" <?php if ($row_orders['return_time'] == "14h30 - 16h30") {echo" selected";} ?>>14h30 - 16h30</option>

            <option class="livraison-time" value="15h - 17h" <?php if ($row_orders['return_time'] == "15h - 17h") {echo" selected";} ?>>15h - 17h</option>
            <option class="livraison-time" value="15h30 - 17h30" <?php if ($row_orders['return_time'] == "15h30 - 17h30") {echo" selected";} ?>>15h30 - 17h30</option>

            <option class="livraison-time" value="16h - 18h" <?php if ($row_orders['return_time'] == "16h - 18h") {echo" selected";} ?>>16h - 18h</option>
            <option class="livraison-time" value="16h30 - 18h30" <?php if ($row_orders['return_time'] == "16h30 - 18h30") {echo" selected";} ?>>16h30 - 18h30</option>

            <option class="livraison-time" value="17h - 19h" <?php if ($row_orders['return_time'] == "17h - 19h") {echo" selected";} ?>>17h - 19h</option>
            <option class="livraison-time" value="17h30 - 19h30" <?php if ($row_orders['return_time'] == "17h30 - 19h30") {echo" selected";} ?>>17h30 - 19h30</option>

            <option class="livraison-time" value="18h - 20h" <?php if ($row_orders['return_time'] == "18h - 20h") {echo" selected";} ?>>18h - 20h</option>
            <option class="livraison-time" value="18h30 - 20h30" <?php if ($row_orders['return_time'] == "18h30 - 20h30") {echo" selected";} ?>>18h30 - 20h30</option>

            <option class="livraison-time" value="19h - 21h" <?php if ($row_orders['return_time'] == "19h - 21h") {echo" selected";} ?>>19h - 21h</option>

            <option class="retrait-time" value="9h30 - 14h" <?php if ($row_orders['return_time'] == "9h30 - 14h") {echo" selected";} ?>>9h30 - 14h</option>
          </select>
        </div>
      </div>
      <div class="form-group hide">
        <label class="col-md-3 control-label">Horaire</label>
        <div class="col-md-4">
          <input type="text" class="form-control horaire" value="<?php echo $row_orders['horaire'] ?>" placeholder="Horaire" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Contact(s) sur place</label>
        <div class="col-md-6">
          <input type="text" class="form-control take_contact" value="<?php echo $row_orders['take_contact'] ?>" placeholder="Contact(s) sur place" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Code d'accès</label>
        <div class="col-md-6">
          <input type="text" class="form-control take_access" value="<?php echo $row_orders['take_access'] ?>" placeholder="Code d'accès" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Escalier</label>
        <div class="col-md-1">
          <input type="checkbox" class="form-control take_stairs"<?php echo ($row_orders['take_stairs'] == 1 ? ' checked' : ''); ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Plan d'accès</label>
        <div class="col-md-6">
          <input type="text" class="form-control plan_access" value="<?php echo $row_orders['plan_access'] ?>" placeholder="Plan d'accès" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Informations complémentaires</label>
        <div class="col-md-6">
          <textarea class="form-control description" placeholder="Informations complémentaires"><?php echo $row_orders['description'] ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">PRIX</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Acompte, €</label>
        <div class="col-md-2">
          <input type="text" class="form-control advance_payment numeric" value="<?php echo (isset($row_orders['advance_payment']) ? $row_orders['advance_payment'] : '') ?>" placeholder="Acompte, €" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Remise, %</label>
        <div class="col-md-2">
          <input type="text" class="form-control transportation_time" value="<?php echo $row_orders['transportation_time'] ?>" placeholder="Remise, %" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Mon tarif, €</label>
        <div class="col-md-4">
          <input type="text" class="form-control total" value="<?php echo $row_orders['total'] ?>" placeholder="Mon tarif, €" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Code Promo</label>
        <div class="col-md-4">
          <input type="text" class="form-control promocode" value="<?php echo $row_orders['promocode'] ?>" placeholder="Code Promo" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Remise, €</label>
        <div class="col-md-4">
          <input type="text" class="form-control discount" value="<?php echo $row_orders['discount'] ?>" placeholder="Remise, €" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Lien acompte</label>
        <div class="col-md-7">
          <input type="text" class="form-control deposit_link" value="<?php echo $row_orders['deposit_link'] ?>" placeholder="Lien acompte" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Lien solde</label>
        <div class="col-md-7">
          <input type="text" class="form-control sale_link" value="<?php echo $row_orders['sale_link'] ?>" placeholder="Lien solde" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Payement par</label>
        <div class="col-md-7">
           <label class="control-label"><b><?php echo $row_orders['payment_by'] ?></b></label>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">FACTURATION</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Entreprise / Nom Prenom</label>
        <div class="col-md-4">
          <?php if($row_orders['select_type'] == 'Une entreprise') { ?>
            <input type="text" class="form-control entreprise_pdf" value="<?php echo ((isset($row_orders['entreprise_pdf']) && $row_orders['entreprise_pdf'] != '') ? $row_orders['entreprise_pdf'] : $row_orders['societe']) ?>" placeholder="Entreprise / Nom Prenom" />
          <?php } else { ?>
            <input type="text" class="form-control entreprise_pdf" value="<?php echo ((isset($row_orders['entreprise_pdf']) && $row_orders['entreprise_pdf'] != '') ? $row_orders['entreprise_pdf'] : $row_orders['last_name'].' '.$row_orders['first_name']) ?>" placeholder="Entreprise" />
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Adresse</label>
        <div class="col-md-4">
          <textarea class="form-control address_pdf" placeholder="Adresse"><?php echo ((isset($row_orders['address_pdf']) && $row_orders['address_pdf'] != '') ? htmlspecialchars_decode($row_orders['address_pdf'], ENT_QUOTES) : htmlspecialchars_decode($row_orders['address'], ENT_QUOTES)) ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Ville</label>
        <div class="col-md-4">
          <input type="text" class="form-control city_pdf" value="<?php echo ((isset($row_orders['city_pdf']) && $row_orders['city_pdf'] != '') ? $row_orders['city_pdf'] : $row_orders['city']) ?>" placeholder="Ville" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Code postal</label>
        <div class="col-md-2">
          <input type="number" class="form-control cp_pdf" value="<?php echo ((isset($row_orders['cp_pdf']) && $row_orders['city_pdf'] != '') ? $row_orders['cp_pdf'] : $row_orders['cp']) ?>" placeholder="Code postal" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Numéro de siret</label>
        <div class="col-md-2">
          <input type="text" class="form-control number_pdf" value="<?php echo (isset($row_orders['number_pdf']) ? $row_orders['number_pdf'] : '') ?>" placeholder="Numéro de siret" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Bon de commande</label>
        <div class="col-md-4">
          <input type="text" class="form-control ord" value="<?php echo (isset($row_orders['ord']) ? $row_orders['ord'] : '') ?>" placeholder="Bon de commande" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Autre</label>
        <div class="col-md-4">
          <textarea class="form-control other_pdf" placeholder="Autre"><?php echo (isset($row_orders['other_pdf']) ? $row_orders['other_pdf'] : '') ?></textarea>
        </div>
      </div>
      <?php
        $result_devis = mysqli_query($conn, "SELECT * FROM `devis` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
      ?>
      <div class="form-group<?php echo mysqli_num_rows($result_devis) == 0 ? ' hidden' : ''; ?>">
        <label class="col-md-3 control-label">Devis</label>
        <div class="col-md-4">
          <!--a href="to_pdf.php?order_id=<?php echo $row_orders['id']; ?>&devis=<?php echo $row_orders['devis']; ?>" target="_blank">DE<?php echo $row_orders['devis']; ?></a-->
          <?php
            $result_devis = mysqli_query($conn, "SELECT * FROM `devis` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
            while($row_devis = mysqli_fetch_assoc($result_devis)) {
              echo'<a href="../uploads/Factures/'.substr($row_devis['pdf'], 0, 6).'/'.$row_devis['pdf'].'" target="_blank">'.$row_devis['pdf'].'</a><br />';
            }
          ?>
        </div>
      </div>
      <div class="form-group<?php echo $row_orders['status'] < 2 ? ' hidden' : ''; ?>">
        <label class="col-md-3 control-label">Facture</label>
        <div class="col-md-4">
          <!--a href="to_pdf.php?order_id=<?php echo $row_orders['id']; ?>" target="_blank"><?php echo $row_orders['num_id']; ?></a-->
          <?php
            $result_facture = mysqli_query($conn, "SELECT * FROM `facture` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
            while($row_facture = mysqli_fetch_assoc($result_facture)) {
              echo'<a href="../uploads/Factures/'.$row_orders['num_id'].'/'.$row_facture['pdf'].'" target="_blank">'.$row_facture['pdf'].'</a><br />';
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">E-MAIL</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Sujet du mail</label>
        <div class="col-md-9">
          <input type="text" class="form-control email_title" value="<?php echo ($row_orders['email_title'] == "" ? htmlspecialchars_decode($row_settings['email_title'], ENT_QUOTES) : htmlspecialchars_decode($row_orders['email_title'], ENT_QUOTES)) ?>" placeholder="Sujet du mail" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Corps du mail</label>
        <div class="col-md-9">
          <textarea class="form-control" id="email_message" placeholder="Corps du mail"><?php echo ($row_orders['email_message'] == "" ? htmlspecialchars_decode($row_settings['email_message'], ENT_QUOTES) : htmlspecialchars_decode($row_orders['email_message'], ENT_QUOTES)) ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Comment avez-vous connu ShootnBox</label>
        <div class="col-md-4">
          <textarea class="form-control about" placeholder="Comment avez-vous connu ShootnBox"><?php echo $row_orders['about'] ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Location longue durée</label>
        <div class="col-md-1">
          <input type="checkbox" class="long_duration"<?php if ($row_orders['long_duration'] == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
          <button type="submit" class="btn btn-sm btn-success">Sauvegarder</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- end panel -->


<?php
  include("footer.php");
?>

<div class="section_window_wrapper popup1_wrapper popup1_wrapper_0">
  <div class="section_window">
     <div class="window_btn__close">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        x="0px"
        y="0px"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        style="fill: #000000"
      >
        <path
          d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
        ></path>
      </svg>
    </div>
    <h3 class="window_title">Mes options</h3>
    <div class="box_window_list"><?php echo $html_popup; ?></div>
  </div>
</div>

<div class="section_window_wrapper popup2_wrapper">
  <div class="section_window">
     <div class="window_btn__close">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        x="0px"
        y="0px"
        width="24"
        height="24"
        viewBox="0 0 24 24"
        style="fill: #000000"
      >
        <path
          d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"
        ></path>
      </svg>
    </div>
    <h3 class="window_title">Livraison</h3>
    <div class="box_window_list"><div class="box_window_list"><?php echo $html_popup2; ?></div></div>
  </div>
</div>

<div class="totalPanel">
  <b>TARIF</b><br />
  <input type="text" value="<?php echo $row_orders['total'] ?> €" readonly />
</div>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet">
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/basic.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/dropzone.css" rel="stylesheet">
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/moment/moment-with-locales.js"></script>
<script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets\plugins\ckeditor\ckeditor.js"></script>
<script src="assets\plugins\ckfinder\ckfinder.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
  var idx = <?php echo mysqli_num_rows($result_bornes); ?>;

  <?php echo $js; ?>

  $(document).ready(function() {

    App.init();

    var editor = CKEDITOR.replace('email_message');
    CKFinder.setupCKEditor(editor, 'assets/plugins/ckfinder/');

    // $('.password').passwordStrength({targetDiv:'.passwordStrength'});

    elasticArea('.address');

    $('.box_id').select2({placeholder: 'Numéro de la borne'});

    long_duration = new Switchery(document.querySelector('.long_duration'), { color: '#00acac', jackColor: '#9decff' });



      for(let i = 0; i <= idx; i++) {

        $('.box_type_' + i).on('change', function(event) {
          console.log('change');
          $('.box_type_' + i).find('option').each(function(){
            if ($(this).is(':selected')) {
              new_group = $(this).attr('data-group');
              $('.price_' + i).val($(this).attr('data-price'));
            }
          })
          if (group != new_group) {
            group = new_group;
            $('.window_inputs_item1, .window_inputs_item2').last().remove();
            $('.box_window_list').last().html('');
            getBoxIDList(i);
            getDeliveryList();
          }
          calcTotal();
        });

        $('.window_upBox_addBox').eq(i).on('click', function(event) {
          event.preventDefault();
           if ($('#protection').is(':checked')) {
            return false;
          }
          getOptionsList($(this).attr('data-idx'));
          if ($('.box_type_' + $(this).attr('data-idx')).val() != 0) {
            $('.popup1_wrapper').fadeIn();
          }
        });

      }


      $('.remove_btn').on('click', function(event) {
        $('.borne').eq($(this).attr('data-idx')).remove();
        calcTotal();
      });



    $('.event_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.event_date').on('blur', function() {
      if ($('.take_date').val() == '') $('.take_date').val($(this).val());
    });

    $('.take_date, .return_date').datetimepicker({
      format: "DD.MM.YYYY",
      locale: 'fr'
    }).on('dp.change', function(e) {
      getBoxIDList();
    });

    /*$('.start_take_time, .start_return_time, .end_take_time, .end_return_time').datetimepicker({
      format: "HH:mm",
      locale: 'fr'
    }).on('dp.change', function(e) {
      getBoxIDList();
    });*/


    /*$('.take_date, .return_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });*/

    $('.take_date').on('change', function() {
      var date = moment($(this).val(), 'DD.MM.YYYY').toDate();
      date = moment(date).add(3, 'days');
      $('.return_date').val(moment(date).format('DD.MM.YYYY'));
    });

    $(".start_event, .end_event").timepicker({
      minuteStep: 1,
      showSeconds: false,
      showMeridian: false,
      defaultTime: true
    });

    Dropzone.options.dropzoneForm = {
      paramName: 'image',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 4, // MB
      maxFiles: 1,
      acceptedFiles: 'image/jpeg,image/png',
      dictDefaultMessage: '<b><?php echo DRAG_CLICK_LOAD_IMAGE ?></b><br />(1 <?php echo NUMBER_SIZES_FILES ?> 4Mb)',
      dictMaxFilesExceeded: '<?php echo NUMBER_FILES_EXCEEDED ?>',
      uploadMultiple: false,
      init: function() {

        <?php if ($row_orders['image'] != "" && file_exists(ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image']) && file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120'))) { ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120')); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120') ?>');
          mockFile.previewElement.classList.add('dz-success');
          mockFile.previewElement.classList.add('dz-complete');

          var existingFileCount = 1; // The number of files already uploaded
          this.options.maxFiles = this.options.maxFiles - existingFileCount;

          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="<?php echo DELETE ?>"><i class="fa fa-times"></i></a>');

          var _this = this;

          removeButton.addEventListener('click', function(e) {

            e.preventDefault();

            swal({
              title: '<?php echo ARE_YOU_SURE ?>',
              text: '<?php echo WANT_DELETE ?>',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              confirmButtonText: '<?php echo DELETE ?>',
              cancelButtonColor: '#929ba1',
              cancelButtonText: '<?php echo CANCEL ?>'
            }).then(function(data) {
              if (data.value) {
                _this.options.maxFiles = _this.options.maxFiles + 1;

                e.stopPropagation();

                $.ajax({
                  type: 'POST',
                  url: 'd26386b04e.php',
                  data: {event: 'eraser', id: <?php echo $_GET['order_id']; ?>},
                  cache: false,
                  success: function(responce) {
                    _this.removeFile(mockFile);
                  }
                });
              }
            }, function(dismiss) {
              // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            });
          });

          mockFile.previewElement.appendChild(removeButton);

        <?php } ?>

        this.on('addedfile', function(file) {
          // Create the remove button
          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="<?php echo DELETE ?>"><i class="fa fa-times"></i></a>');

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
            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'remove_image', image: $('.image').val()},
              cache: false,
              success: function(responce){
                $('.image').val('');
              }
            });
          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });

        this.on('success', function(file, responseText) {
          $('.image').val(responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm2 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 10, // MB
      maxFiles: 15,
      acceptedFiles: 'image/jpeg,image/png',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les images.</b><br />(jusqu\'à 5 fichiers, la taille maximale du fichier est de 10 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          if (mysqli_num_rows($result_template_images) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_template_images = mysqli_fetch_assoc($result_template_images)) {
        ?>
              var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_template_images['image']); ?>};
              this.options.addedfile.call(this, mockFile);
              this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_template_images['image'], '120') ?>');
              mockFile.previewElement.classList.add('dz-success');
              mockFile.previewElement.classList.add('dz-complete');

              var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="Supprimer"><i class="fa fa-times"></i></a>');

              var _this = this;

              removeButton.addEventListener('click', function(e) {

                e.preventDefault();

                swal({
                  title: 'Êtes-vous sûr',
                  text: "de vouloir supprimer cet élément?",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'Supprimer',
                  cancelButtonColor: '#929ba1',
                  cancelButtonText: 'Annuler'
                }).then(function() {

                  _this.options.maxFiles = _this.options.maxFiles + 1;

                  e.stopPropagation();

                  $.ajax({
                    type: 'POST',
                    url: 'd26386b04e.php',
                    data: {event: 'delete_template_image', image: '<?php echo $row_template_images['image'] ?>'},
                    cache: false,
                    success: function(responce) {
                      _this.removeFile(mockFile);
                    }
                  });

                }, function(dismiss) {
                  // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
                });

              });

              mockFile.previewElement.appendChild(removeButton);
              existingFileCount++;
        <?php
            }
        ?>
            this.options.maxFiles = this.options.maxFiles - existingFileCount;
        <?php
          }
        ?>

        this.on('addedfile', function(file) {
          // Create the remove button
          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg delete-template-image" title="Supprimer"><i class="fa fa-times"></i></a>');

          // Capture the Dropzone instance as closure.
          var _this = this;

          // Listen to the click event
          removeButton.addEventListener('click', function(e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();

            var index = $(this).index('.delete-template-image');

            e.stopPropagation();

            // Remove the file preview.
            _this.removeFile(file);

            // If you want to the delete the file on the server as well,
            // you can do the AJAX request here.

            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'remove_gallery_image', images: $('.images').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.images').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.images').val($('.images').val() != '' ? $('.images').val() + ';' + responseText : responseText);
        });
      }
    }


    $('.edit-order').on('submit', function(event) {
      event.preventDefault();
      if ($('.box_type').val() == '') {
        showError('Choisissez le type de stand !');
        return false;
      }

      if ($('.num_id').val() == '') {
        showError('Le numéro devis est obligatoire !');
        return false;
      }
      if ($('.password').val().length != 6) {
        showError('Le mot de passe doit comporter 6 chiffres !');
        return false;
      }

         if ($('.event_date').val() == '') {
        showError('Entrez la date de l\'événement !');
        return false;
      }


     var selected_options = '';
      $('.borne').eq(0).find('.vehicle_up').each(function(index, brand){
        selected_options += $(this).attr('data-name') + ':' + $(this).val() + ':' + $('#vehicle_up_price' + $(this).attr('data-idx')).val() + ',';
      });
      selected_options = selected_options.slice(0, -1);

      var delivery_options = '';
      $('.vehicle_down').each(function(index, brand){
        delivery_options += $(this).attr('data-name') + ':' + $(this).val() + ':' + $('#vehicle_price' + $(this).attr('data-idx')).val() + ',';
      });
      delivery_options = delivery_options.slice(0, -1);

      var box_id = '';
      $('.box_id').each(function(index, brand){
        if ($(this).val() != null) {
          box_id += $(this).val() + ',';
        }
      });
      box_id = box_id.slice(0, -1);

      if ($('.status').val() == 2 && (box_id == 'null' || box_id == '')) {
        showError('Choisissez le numéro de la borne !');
        return false;
      }

      var bornes = '';

      $('.borne').each(function(index){
        if (index > 0) {
          bornes += $(this).find('.box_type').val() + '::' + $(this).find('.price').val() + '::' + $(this).find('.amount').val() + '::' + $('.box_id_' + index).val() + '::';

          var selected_options2 = '';
          $(this).find('.vehicle_up').each(function(index2){
            selected_options2 += $(this).attr('data-name') + ':' + $(this).val() + ':' + $('#vehicle_up_price' + $(this).attr('data-idx')).val() + ',';
          });
          selected_options2 = selected_options2.slice(0, -1);
          bornes += selected_options2 + ';';
        }
      });
      bornes = bornes.slice(0, -1);

      console.log(bornes);

      var amount = 0;
      $('.amount').each(function(){
        amount = amount + +$(this).val();
      });


      if (box_id != '' && box_id != 'null' && amount != box_id.split(',').length) {
        showError('Nombre des bornes ne corresponde pas à la commande!');
        return false;
      }

      if ($('.delivery').val() == 'Retrait boutique' && ($('.take_date').val() == '' || $('.return_date').val())) {
        showError('Indiquer la date de retrait et de retour du borne !');
        return false;
      }

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'edit_order',
          id: <?php echo $order_id ?>,
          user_id: $('.user_id').val(),
          agency_id: $('.agency_id').val(),
          invite: $('#invite').is(':checked') ? 1 : 0,
          last_name: $('.last_name').val(),
          first_name: $('.first_name').val(),
          city: $('.city').val(),
          address: $('.address').val(),
          cp: $('.cp').val(),
          email: $('.email').val(),
          phone: $('.phone').val(),
          num_id: $('.num_id').val(),
          sage: $('.sage').val(),
          password:  $('.password').val(),
          ord: $('.ord').val(),
          select_type: $('.select_type').val(),
          box_type: $('.box_type').val(),
          price: $('.price').val(),
          amount: $('.amount').val(),
          bornes: bornes,
          event_date: $('.event_date').val(),
          event_time: $('.event_time').val(),
          event_place: $('.event_place').val(),
          return_place: $('.return_place').val(),
          event_type: $('.event_type').val(),
          advance_payment: $('.advance_payment').val(),
          take_date: $('.take_date').val(),
          return_date: $('.return_date').val(),
          take_time: $('.take_time').val(),
          return_time: $('.return_time').val(),
          horaire: $('.horaire').val(),
          transportation_time: $('.transportation_time').val(),
          box_id:  box_id,
          societe: $('.societe').val(),
          description: $('.description').val(),
          about: $('.about').val(),
          selected_options: (selected_options != 'null' ? selected_options : ''),
          delivery_options: ((delivery_options != 'null' && $('.delivery').val() != 'Retrait boutique') ? delivery_options : ''),
          total: $('.total').val(),
          discount: $('.discount').val(),
          image: $('.image').val(),
          images: $('.images').val(),
          status: $('.status').val(),
          email_title: $('.email_title').val(),
          email_message: CKEDITOR.instances.email_message.getData(),
          entreprise_pdf: $('.entreprise_pdf').val(),
          address_pdf: $('.address_pdf').val(),
          city_pdf: $('.city_pdf').val(),
          cp_pdf: $('.cp_pdf').val(),
          number_pdf: $('.number_pdf').val(),
          other_pdf: $('.other_pdf').val(),
          long_duration: $('.long_duration').is(':checked') ? 1 : 0,
          promocode: $('.promocode').val(),
          marriage: $('#marriage').is(':checked') ? 1 : 0,
          format_id: $('.format_id').val(),
          deposit_link: $('.deposit_link').val(),
          sale_link: $('.sale_link').val(),
        },
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
            swal({
              title: 'Fait!',
              text: 'L\'information a été mise à jour avec succès !',
              type: 'success',
              confirmButtonColor: '#348fe2',
              confirmButtonText: 'Fermer'
            }).then(function() {
              window.location.href = 'orders_list.php?status=' + $('.status').val() + '<?php if (isset($_GET['arch'])) echo'&arch=true'; ?>';
            });
          } else {
            showError(responce);
          }
        }
      });
    });

    <?php if ($error == 1) { ?>
      swal({
        title: 'Erreur!',
        text: 'Impossible de traiter la demande!',
        type: 'error',
        confirmButtonColor: '#348fe2',
        confirmButtonText: 'Fermer'
      }).then(function() {
        window.location.href = 'orders_list.php?status=<?php echo $_GET['status'] ?>';
      });
    <?php } ?>

     $('.status').on('change', function(event) {
      if ($(this).val() == 2) {
        $('.box_id').prop('disabled', false);
        getBoxIDList();
      } else {
        $('.box_id').prop('disabled', true);
      }
    });


    $('.section_window').on('click', function(event) {
      event.stopPropagation();
    });


    $('.window_upBox_addBox').on('click', function(event) {
      event.preventDefault();
       if ($('#protection').is(':checked')) {
            return false;
          }
      getOptionsList($(this).attr('data-idx'));
      if ($('.box_type_' + $(this).attr('data-idx')).val() != 0) {
        $('.popup1_wrapper').fadeIn();
      }
    });

    $('.window_downBox_addBox').on('click', function(event) {
      event.preventDefault();
       if ($('#protection').is(':checked')) {
            return false;
          }
      $('.popup2_wrapper').fadeIn();
    });

    $('.window_btn__close, .popup1_wrapper, .popup2_wrapper').on('click', function(event) {
      event.preventDefault();
      $('.popup1_wrapper, .popup2_wrapper').fadeOut();
    });

    $('.select_type').on('change', function(event) {
      $('.window_inputs_item1, .window_inputs_item2').remove();
      $('.box_window_list').html('');
      getBornesList();
    });

    $('.agency_id').on('change', function(event) {
      getBoxIDList();
      if (+$(this).val() == 0) $('.retrait-text').html('Retrait boutique');
      if (+$(this).val() == 1) $('.retrait-text').html('Retrait boutique Montreuil');
      if (+$(this).val() == 2) $('.retrait-text').html('Retrait boutique Bordeaux');
    });


    $('.price').on('change', function(event) {
      calcTotal();
    });

    $('.amount').on('change', function(event) {
      $('.vehicle_up').val($(this).val());
      if ($(this).val() == 0) {
        $('.price_0').attr('disabled', true);
      } else {
        $('.price_0').attr('false', true);
      }
      calcTotal();
    });

    $('.delivery').on('change', function(event) {
      if ($(this).val() == 'Livraison') {
        $('.delivery-row').removeClass('hide');
        getDeliveryList();
        $('.livraison-time').removeClass('hide');
        $('.retrait-time').addClass('hide');
        $('.delivery-type').html('Livraison');
      } else {
        $('.delivery-row').addClass('hide');
        $('.popup2_wrapper').find('.box_window_list').html('');
        $('.window_inputs_item2').remove();
        calcTotal();
        $('.livraison-time').addClass('hide');
        $('.retrait-time').removeClass('hide');
        $('.delivery-type').html('Retrait');
      }
    });

    if ($('.delivery').val() == 'Livraison') {
        $('.livraison-time').removeClass('hide');
        $('.retrait-time').addClass('hide');
        $('.delivery-type').html('Livraison');
      } else {
        $('.livraison-time').addClass('hide');
        $('.retrait-time').removeClass('hide');
        $('.delivery-type').html('Retrait');
      }

   $('.popup1_wrapper').find('.window_btn').on('click', function(event) {
            event.preventDefault();
            $('.popup1_wrapper').fadeOut();
            var html = '';
            $('.popup1_wrapper').find('.box_option').each(function(index, brand){
              var amount = $('.vehicle_up').length;
              if($(this).is(':checked')) {
                html += '<div class="window_inputs window_inputs_item1 window_inputs_up' + (amount + index) + '">' +
                  '<label for="vehicle_up' + (amount + index) + '">' + $(this).val() + '</label>' +
                  '<input class="vehicle_up" id="vehicle_up' + (amount + index) + '" name="vehicle_up' + (amount + index) + '" type="number" min="0" step="1" value="' + $('.amount').val() + '" data-name="' + $(this).val() + '" data-price="' + $(this).attr('data-price') + '" data-idx="' + (amount + index) + '" onChange="calcTotal()" />' +
                  '&nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price' + (amount + index) + '" name="vehicle_up_price' + (amount + index) + '" type="number" min="0" step="1" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                  '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption(' + (amount + index) + ')">' +
                    '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                  '</svg>' +
                '</div>';
              }
            });
            $('.window_inputs_item1').remove();
            $('.window_upBox_addBox').after(html);
            calcTotal();
          });

    $('.popup2_wrapper').find('.window_btn').on('click', function(event) {
            event.preventDefault();
            $('.popup2_wrapper').fadeOut();
            var html = '';
            $('.popup2_wrapper').find('.box_option').each(function(index, brand){
                if($(this).is(':checked')) {
                if ($(this).val() == 'Kilométriques supplémentaires') {
                  html += '<div class="window_inputs window_inputs_item2 window_inputs_down' + index + '">' +
                    '<label for="vehicle_down' + index + '">' + $(this).val() + '</label>' +
                    '<input class="vehicle_down hide" id="vehicle_down' + index + '" name="vehicle_down' + index + '" type="number" min="0" step="1" value="1" data-name="' + $(this).val() + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                    '&nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price' + index + '" name="vehicle_price' + index + '" type="number" min="0" step="1" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                    '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery(' + index + ')">' +
                      '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                    '</svg>' +
                  '</div>';
                } else {
                  html += '<div class="window_inputs window_inputs_item2 window_inputs_down' + index + '">' +
                    '<label for="vehicle_down' + index + '">' + $(this).val() + '</label>' +
                    '<input class="vehicle_down" id="vehicle_down' + index + '" name="vehicle_down' + index + '" type="number" min="0" step="1" value="' + $('.amount').val() + '" data-name="' + $(this).val() + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                    '&nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price' + index + '" name="vehicle_price' + index + '" type="number" min="0" step="1" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                    '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery(' + index + ')">' +
                      '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                    '</svg>' +
                  '</div>';
                }
              }
            });
            $('.window_inputs_item2').remove();
            $('.window_downBox_addBox').after(html);
            calcTotal();
          });

    $('.add-btn').on('click', function(event) {
      event.preventDefault();
      $('.add-template').removeClass('hide');
      $('.add-btn').addClass('hide');
    });

    $('.add_borne').on('click', function(event) {
      event.preventDefault();
      console.log('add', idx);
      idx++;
      $('.borne').last().after(
      '<div class="borne">' +
        '<div class="col-md-1 col5p"></div>' +
        '<div class="col-md-10">' +
          '<h4 class="title"><div class="remove_btn" data-idx="' + idx + '"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve"><defs></defs><g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" ><path d="M 86.5 48.5 h -83 C 1.567 48.5 0 46.933 0 45 s 1.567 -3.5 3.5 -3.5 h 83 c 1.933 0 3.5 1.567 3.5 3.5 S 88.433 48.5 86.5 48.5 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(29,29,27); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" /><path d="M 86.5 48.5 h -83 C 1.567 48.5 0 46.933 0 45 s 1.567 -3.5 3.5 -3.5 h 83 c 1.933 0 3.5 1.567 3.5 3.5 S 88.433 48.5 86.5 48.5 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(29,29,27); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" /></g></svg></div></h4>' +
        '</div>' +
        '<div class="col-md-1 col5p"></div>' +
          '<div class="form-group">' +
            '<label class="col-md-3 control-label">Borne</label>' +
            '<div class="col-md-2">' +
              '<select class="form-control box_type box_type_' + idx + '" data-index="' + idx + '"></select>' +
            '</div>' +
            '<label class="col-md-1 control-label">Prix, €</label>' +
            '<div class="col-md-1">' +
              '<input type="text" class="form-control price price_' + idx + '" value="0" placeholder="Prix, €" />' +
            '</div>' +
          '</div>' +
          '<div class="form-group">' +
            '<label class="col-md-3 control-label">Quantité</label>' +
            '<div class="col-md-2">' +
              '<input type="number" min="0" step="1" class="form-control amount" value="1" placeholder="Quantité" />' +
            '</div>' +
          '</div>' +
          '<div class="form-group">' +
            '<label class="col-md-3 control-label">Mes options</label>' +
            '<div class="col-md-9">' +
              '<div class="window_upBox">' +
                '<div class="window_upBox_addBox window_upBox_addBox_' + idx + '" data-idx="' + idx + '">' +
                  '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="15" height="15" viewBox="0 0 24 24" style="fill: #000000"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path></svg>' +
                '</div>' +
              '</div>' +
            '</div>' +
          '</div>' +
          '<div class="form-group">' +
            '<label class="col-md-3 control-label">Numéro de la borne</label>' +
            '<div class="col-md-2">' +
              '<select class="form-control box_id box_id_' + idx +'" multiple="true"' + ($('.status').val() != 2 ? ' disabled' : '') +  '>' +
                '<option value="">Numéro de la borne...</option>' +
              '</select>' +
            '</div>' +
          '</div>' +
        '</div>'
      );

      getBornesList();
      $('.box_id_' + idx).select2({placeholder: 'Numéro de la borne'});

      $('.box_type_' + idx).on('change', function(event) {
        console.log('change');
        $('.box_type_'+ idx).find('option').each(function(){
          if ($(this).is(':selected')) {
            new_group = $(this).attr('data-group');
            $('.price_' + idx).val($(this).attr('data-price'));
          }
        });
        if (group != new_group) {
          group = new_group;
          $('.window_inputs_item1, .window_inputs_item2').last().remove();
          $('.box_window_list').last().html('');
          getBoxIDList(idx);
          getDeliveryList();
        }
        calcTotal();
      });

      $('.window_upBox_addBox').eq(idx).on('click', function(event) {
        event.preventDefault();
        getOptionsList($(this).attr('data-idx'));
        if ($('.box_type_' + $(this).attr('data-idx')).val() != 0) {
          $('.popup1_wrapper').fadeIn();
        }
      });

      $('.price').on('change', function(event) {
        calcTotal();
      });

      $('.amount').on('change', function(event) {
        $('.vehicle_up').val($(this).val());
        calcTotal();
      });

      $('.remove_btn').on('click', function(event) {
        $('.borne').eq($(this).attr('data-idx')).remove();
        calcTotal();
      });

    });

  <?php if ($row_orders['status'] == 2) { ?>

    $('input, textarea, select').each(function(){
      $(this).prop('disabled', true);
    });
    $('#protection').prop('disabled', false);
  <?php } ?>

    $('#protection').on('change', function(event) {
      if ($(this).is(':checked')) {
         $('input, textarea, select').each(function(){
          $(this).prop('disabled', true);
        });
        $('#protection').prop('disabled', false);
      } else {
         $('input, textarea, select').each(function(){
          $(this).prop('disabled', false);
        });
      }
    });
  });

  function getBornesList() {
    $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'bornes_list',
          select_type: $('.select_type').val()
        },
        cache: false,
        success: function(response) {
          $('.box_type_' + idx).html(response);
          calcTotal();
        }
    });
  }

  function getBoxIDList(index = 0) {
    console.log($('.agency_id').val(), $('.box_type_' + index).val(), $('.take_date').val(), $('.return_date').val());
    $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'box_id_list',
          agency_id: $('.agency_id').val(),
          box_type: $('.box_type_' + index).val(),
          take_date: $('.take_date').val(),
          return_date: $('.return_date').val(),
          box_id: $('.box_id').val(),
        },
        cache: false,
        success: function(response) {
          $('.box_id_' + index).html(response);
        }
    });
  }

  function getOptionsList(i) {
    if ($('.box_type_' + i).val() == 0) {
      $('.popup1_wrapper').find('.box_window_list').html('');
      return false;
    }
    var txt_options = '';
    $('.window_inputs_i_' + i).find('label').each(function(){
      txt_options += $(this).html() + ';';
    });
    txt_options = txt_options.slice(0, -1);
    $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'options_list',
          select_type: $('.select_type').val(),
          box_type: $('.box_type_' + i).val(),
          txt_options: txt_options
        },
        cache: false,
        success: function(response) {
          $('.popup1_wrapper').find('.box_window_list').html(response);
          $('.popup1_wrapper').find('.window_btn').on('click', function(event) {
            event.preventDefault();
            $('.popup1_wrapper').fadeOut();
            var html = '';
            $('.popup1_wrapper').find('.box_option').each(function(index, brand){
              var amount = 100;
              if($(this).is(':checked')) {
                html += '<div class="window_inputs window_inputs_item1 window_inputs_i_' + i + ' window_inputs_up' + (amount + index) + '">' +
                  '<label for="vehicle_up' + (amount + index) + '">' + $(this).val() + '</label>' +
                  '<input class="vehicle_up" id="vehicle_up' + (amount + index) + '" name="vehicle_up' + (amount + index) + '" type="number" min="0" step="1" value="' + $('.amount').val() + '" data-name="' + $(this).val() + '" data-price="' + $(this).attr('data-price') + '" data-idx="' + (amount + index) + '" onChange="calcTotal()" />' +
                  '&nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price' + (amount + index) + '" name="vehicle_up_price' + (amount + index) + '" type="number" min="0" step="1" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                  '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption(' + (amount + index) + ')">' +
                    '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                  '</svg>' +
                '</div>';
              }
            });
            $('.window_inputs_i_' + i).remove();
            $('.window_upBox_addBox_' + i).after(html);
            calcTotal();
          });
        }
    });
  }

  function getDeliveryList() {
    if ($('.box_type_0').val() == 0) {
      $('.popup2_wrapper').find('.box_window_list').html('');
      return false;
    }
    $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'delivery_list',
          select_type: $('.select_type').val(),
          box_type: $('.box_type').val()
        },
        cache: false,
        success: function(response) {
          $('.popup2_wrapper').find('.box_window_list').html(response);
          $('.popup2_wrapper').find('.window_btn').on('click', function(event) {
            event.preventDefault();
            $('.popup2_wrapper').fadeOut();
            var html = '';
            $('.popup2_wrapper').find('.box_delivery').each(function(index, brand){
              if($(this).is(':checked')) {
                if ($(this).val() == 'Kilométriques supplémentaires') {
                  html += '<div class="window_inputs window_inputs_item2 window_inputs_down' + index + '">' +
                    '<label for="vehicle_down' + index + '">' + $(this).val() + '</label>' +
                    '<input class="vehicle_down hide" id="vehicle_down' + index + '" name="vehicle_down' + index + '" type="number" min="0" step="1" value="1" data-name="' + $(this).val() + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                    '&nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price' + index + '" name="vehicle_price' + index + '" type="number" min="0" step="1" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                    '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery(' + index + ')">' +
                      '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                    '</svg>' +
                  '</div>';
                } else {
                  html += '<div class="window_inputs window_inputs_item2 window_inputs_down' + index + '">' +
                    '<label for="vehicle_down' + index + '">' + $(this).val() + '</label>' +
                    '<input class="vehicle_down" id="vehicle_down' + index + '" name="vehicle_down' + index + '" type="number" min="0" step="1" value="' + $('.amount').val() + '" data-name="' + $(this).val() + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                    '&nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price' + index + '" name="vehicle_price' + index + '" type="number" min="0" step="1" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                    '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery(' + index + ')">' +
                      '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                    '</svg>' +
                  '</div>';
                }
              }
            });
            $('.window_inputs_item2').remove();
            $('.window_downBox_addBox').after(html);
            calcTotal();
          });
        }
    });
  }

  function removeOption(idx) {
    if ($('#protection').is(':checked')) {
      return false;
    }
    $('.window_inputs_up' + idx).remove();
    $('#option' + idx).prop('checked', false);
    calcTotal();
  }

  function removeDelivery(idx) {
    if ($('#protection').is(':checked')) {
      return false;
    }
    $('.window_inputs_down' + idx).remove();
    $('#delivery' + idx).prop('checked', false);
    calcTotal();
  }

  function calcTotal() {
    var total = 0;
    $('.price').each(function(index, brand){
      total += $('.price').eq(index).val() * $('.amount').eq(index).val();
    });
    $('.vehicle_up').each(function(index, brand){
      total = total + $('#vehicle_up_price' + $(this).attr('data-idx')).val() * $(this).val();
    });
    $('.vehicle_down').each(function(index, brand){
      total = total + $('#vehicle_price' + $(this).attr('data-idx')).val() * $(this).val();
    });
    $('.total').val(total.toFixed(2));
    $('.totalPanel input').val(total.toFixed(2) + ' €');
  }

  setTimeout(() => {
    if (!$('.box_id').val()) {
      getBoxIDList(0);
    }
  }, 1000)
</script>

<?php include("end.php"); ?>