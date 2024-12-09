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
	$page_title = "Ajouter une reservation";
	$breadcrumbs = '<a href="orders_list.php?status='.$_GET['status'].'" title="Demande">Demande</a>';
	include("header.php");
  $result_orders = mysqli_query($conn, "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'shoot' AND TABLE_NAME = 'orders_new'");
  $row_orders = mysqli_fetch_assoc($result_orders);
  $orders_amount = $row_orders['AUTO_INCREMENT'];
  if (isset($_GET['order_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
    $row_orders = mysqli_fetch_assoc($result_orders);
    $delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
  } else {
    $delivery = "";
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
        top: calc(50% - 275px);
        width: 650px;
        height: 550px;
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
      .window_downBox_addBox {
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
      .window_upBox_addBox:hover > svg,
      .window_downBox_addBox:hover > svg {
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
		<h4 class="panel-title">Ajouter une reservation</h4>
	</div>
	<div class="panel-body">
		<div id="fullsize-pos"></div>
		<form class="form-horizontal add-order">
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
          <select class="form-control user_id">
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
            <option value="Une entreprise"<?php if (isset($row_orders['select_type']) && $row_orders['select_type'] == 'Une entreprise') {echo" selected";} ?>>Une entreprise</option>
            <option value="Un particulier"<?php if (isset($row_orders['select_type']) && $row_orders['select_type'] == 'Un particulier') {echo" selected";} ?>>Un particulier</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nom de la société</label>
        <div class="col-md-4">
          <input type="text" class="form-control societe" value="<?php echo (isset($row_orders['societe']) ? $row_orders['societe'] : '') ?>" placeholder="Nom de la société" />
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label">Nom</label>
				<div class="col-md-4">
					<input type="text" class="form-control last_name" value="<?php echo (isset($row_orders['last_name']) ? $row_orders['last_name'] : '') ?>" placeholder="Nom" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Prenom</label>
				<div class="col-md-4">
					<input type="text" class="form-control first_name" value="<?php echo (isset($row_orders['first_name']) ? $row_orders['first_name'] : '') ?>" placeholder="Prenom" />
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
					<input type="text" class="form-control email" value="<?php echo (isset($row_orders['email']) ? $row_orders['email'] : '') ?>" placeholder="Adresse mail" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Numéro de téléphone</label>
				<div class="col-md-4">
					<input type="tel" class="form-control phone" value="<?php echo (isset($row_orders['phone']) ? $row_orders['phone'] : '') ?>" placeholder="Numéro de téléphone" />
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
          <input type="text" class="form-control num_id" value="DE<?php echo ($orders_amount) + 5000 ?>" placeholder="Devis N" />
        </div>
        <div class="col-md-2">
          <label class="control-label"><b>Sage</b></label>
          <input type="text" class="form-control sage" value="" placeholder="Sage" />
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label">Mot de passe</label>
				<div class="col-md-4">
					<input type="text" class="form-control password numeric" value="<?php echo rand(100000, 999999); ?>" placeholder="Mot de passe" />
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
          <input type="text" class="form-control event_date" value="<?php echo (isset($row_orders['event_date']) ? $row_orders['event_date'] : '') ?>" placeholder="Date de l’évènement" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Type d’événement</label>
        <div class="col-md-4">
          <input type="text" class="form-control event_type" value="<?php echo (isset($row_orders['event_type']) ? $row_orders['event_type'] : '') ?>" placeholder="Type d’événement" />
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">BORNE</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label">Borne</label>
				<div class="col-md-2">
          <select class="form-control box_type"></select>
				</div>
        <label class="col-md-1 control-label">Prix, €</label>
        <div class="col-md-1">
          <input type="text" class="form-control price" value="<?php echo (isset($row_orders['price']) ? $row_orders['price'] : 0) ?>" placeholder="Prix, €" />
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
            <div class="window_upBox_addBox">
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
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Numéro de la borne</label>
        <div class="col-md-2">
          <select class="form-control box_id" multiple="true" disabled>
            <option value="">Numéro de la borne...</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">LIVRAISON OU RETRAIT</h4>
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
      <div class="form-group delivery-row hide">
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
          </div>
        </div>
      </div>
      <div class="form-group delivery-row hide">
        <label class="col-md-3 control-label">Créneau livraison</label>
        <div class="col-md-2">
          <select class="form-control event_time">
            <option value="0">Créneau livraison...</option>
            <option value="7">7h à 13h</option>
            <option value="8">13h à 19h</option>
          </select>
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label">Lieu de l’évènement</label>
				<div class="col-md-4">
					<input type="text" class="form-control event_place" value="<?php echo (isset($row_orders['event_place']) ? $row_orders['event_place'] : '') ?>" placeholder="Lieu de l’évènement" />
				</div>
			</div>

      <div class="form-group">
        <label class="col-md-3 control-label">Date et l’heure d’enlèvement</label>
        <div class="col-md-2">
          <input type="text" class="form-control take_date" value="<?php echo (isset($row_orders['take_date']) ? $row_orders['take_date'] : '') ?>" placeholder="Date et l’heure d’enlèvement" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Date et l’heure du retour</label>
        <div class="col-md-2">
          <input type="text" class="form-control return_date" value="<?php echo (isset($row_orders['return_date']) ? $row_orders['return_date'] : '') ?>" placeholder="Date et l’heure du retour" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Horaire</label>
        <div class="col-md-4">
          <input type="text" class="form-control horaire" value="<?php echo (isset($row_orders['horaire']) ? $row_orders['horaire'] : '') ?>" placeholder="Horaire" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Informations complémentaires</label>
        <div class="col-md-4">
          <textarea class="form-control description" placeholder="Informations complémentaires"><?php echo (isset($row_orders['description']) ? $row_orders['description'] : '') ?></textarea>
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
          <input type="text" class="form-control advance_payment numeric" value="<?php echo (isset($row_orders['advance_payment']) ? $row_orders['advance_payment'] : '0') ?>" placeholder="Acompte, €" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Remise, %</label>
        <div class="col-md-2">
          <input type="text" class="form-control transportation_time" value="<?php echo (isset($row_orders['transportation_time']) ? $row_orders['transportation_time'] : 0) ?>" placeholder="Remise, %" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Mon tarif, €</label>
        <div class="col-md-4">
          <input type="text" class="form-control total" value="<?php echo (isset($row_orders['total']) ? $row_orders['total'] : '') ?>" placeholder="Mon tarif, €" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Code Promo</label>
        <div class="col-md-4">
          <input type="text" class="form-control discount" value="<?php echo (isset($row_orders['discount']) ? $row_orders['discount'] : '') ?>" placeholder="Code Promo" />
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
            <input type="text" class="form-control entreprise_pdf" value="" placeholder="Entreprise / Nom Prenom" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Adresse</label>
        <div class="col-md-4">
          <textarea class="form-control address_pdf" placeholder="Adresse"></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Ville</label>
        <div class="col-md-4">
          <input type="text" class="form-control city_pdf" value="" placeholder="Ville" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Code postal</label>
        <div class="col-md-2">
          <input type="number" class="form-control cp_pdf" value="" placeholder="Code postal" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Numéro de siret</label>
        <div class="col-md-2">
          <input type="text" class="form-control number_pdf" value="" placeholder="Numéro de siret" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Bon de commande</label>
        <div class="col-md-4">
          <input type="text" class="form-control ord" value="" placeholder="Bon de commande" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Autre</label>
        <div class="col-md-4">
          <textarea class="form-control other_pdf" placeholder="Autre"></textarea>
        </div>
      </div>
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
						<option value="0"<?php if (isset($row_orders['status']) && $row_orders['status'] == "0") {echo" selected";} ?>>Demandes</option>
            <!--option value="1"<?php if ($row_orders['status'] == "1") {echo" selected";} ?>>Attentes</option-->
            <option value="2"<?php if (isset($row_orders['status']) && $row_orders['status'] == "2") {echo" selected";} ?>>Réservations</option>
					</select>
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label">Sujet du mail</label>
        <div class="col-md-9">
          <input type="text" class="form-control email_title" value="<?php echo htmlspecialchars_decode($row_settings['email_title'], ENT_QUOTES) ?>" placeholder="Sujet du mail" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Corps du mail</label>
        <div class="col-md-9">
          <textarea class="form-control" id="email_message" placeholder="Corps du mail"><?php echo $row_settings['email_message'] ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Comment avez-vous connu ShootnBox</label>
        <div class="col-md-4">
          <textarea class="form-control about" placeholder="Comment avez-vous connu ShootnBox"><?php echo (isset($row_orders['about']) ? $row_orders['about'] : '') ?></textarea>
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success">Ajouter</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- end panel -->


<?php
	include("footer.php");
?>

<div class="section_window_wrapper popup1_wrapper">
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
    <div class="box_window_list"></div>
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
    <div class="box_window_list"></div>
  </div>
</div>
<div class="totalPanel">
  <b>TARIF</b><br />
  <input type="text" value="0 €" readonly />
</div>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet">
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/moment/moment-with-locales.js"></script>
<script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets\plugins\ckeditor\ckeditor.js"></script>
<script src="assets\plugins\ckfinder\ckfinder.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
  var group = 0,
    new_group = 0;

	$(document).ready(function() {

		App.init();

		$('.box_id').select2({placeholder: 'Numéro de la borne'});

    var editor = CKEDITOR.replace('email_message');
    CKFinder.setupCKEditor(editor, 'assets/plugins/ckfinder/');

		//$('.password').passwordStrength({targetDiv:'.passwordStrength'});

		elasticArea('.address');

		$('.event_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.take_date, .return_date').datetimepicker({
      format: "DD.MM.YYYY HH:mm",
      locale: 'fr'
    }).on('dp.change', function(e) {
      getBoxIDList();
    });


		$(".start_event, .end_event").timepicker({
			minuteStep: 1,
			showSeconds: false,
			showMeridian: false,
			defaultTime: true
		});


    $('.add-order').on('submit', function(event) {
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
      $('.vehicle_up').each(function(index, brand){
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
        box_id += $(this).val() + ',';
      });
      box_id = box_id.slice(0, -1);

      if ($('.status').val() == 2 && (box_id == 'null' || box_id == '')) {
        showError('Choisissez le numéro de la borne !');
        return false;
      }

      $.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {
					event: 'add_order',
          user_id: $('.user_id').val(),
          agency_id: $('.agency_id').val(),
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
          ord:  $('.ord').val(),
					select_type: $('.select_type').val(),
					box_type: $('.box_type').val(),
          price: $('.price').val(),
          amount: $('.amount').val(),
					event_date: $('.event_date').val(),
          event_time: $('.event_time').val(),
					event_place: $('.event_place').val(),
					event_type: $('.event_type').val(),
          advance_payment: $('.advance_payment').val(),
          take_date: $('.take_date').val(),
          return_date: $('.return_date').val(),
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
					status: $('.status').val(),
          email_title: $('.email_title').val(),
          email_message: CKEDITOR.instances.email_message.getData(),
          entreprise_pdf: $('.entreprise_pdf').val(),
          address_pdf: $('.address_pdf').val(),
          city_pdf: $('.city_pdf').val(),
          cp_pdf: $('.cp_pdf').val(),
          number_pdf: $('.number_pdf').val(),
          other_pdf: $('.other_pdf').val(),
				},
				cache: false,
				success: function(responce) {
					if (responce == 'done'){
						swal({
              title: '<?php echo DONE ?>',
              text: '<?php echo ENTRY_SUCCESSFULLY_ADDED ?>',
              type: 'success',
              confirmButtonColor: '#348fe2',
              confirmButtonText: 'OK',
              closeOnConfirm: true
            }).then(function() {
              window.location.href = 'orders_list.php?status=' + $('.status').val();
            });
					} else {
						showError(responce);
					}
				}
			});
		});

    getBornesList();

    $('.section_window').on('click', function(event) {
      event.stopPropagation();
    });


    $('.window_upBox_addBox').on('click', function(event) {
      event.preventDefault();
      $('.popup1_wrapper').fadeIn();
    });

    $('.window_downBox_addBox').on('click', function(event) {
      event.preventDefault();
      $('.popup2_wrapper').fadeIn();
    });

    $('.window_btn__close, .popup1_wrapper, .popup2_wrapper').on('click', function(event) {
      event.preventDefault();
      $('.popup1_wrapper, .popup2_wrapper').fadeOut();
    });

    $('.agency_id').on('change', function(event) {
      getBoxIDList();
    });

    $('.select_type').on('change', function(event) {
      $('.window_inputs_item1, .window_inputs_item2').remove();
      $('.box_window_list').html('');
      getBornesList();
    });

    $('.status').on('change', function(event) {
      if ($(this).val() == 2) {
        $('.box_id').prop('disabled', false);
      } else {
        $('.box_id').prop('disabled', true);
      }
    });



    $('.box_type').on('change', function(event) {

        $('.box_type option').each(function(){
          if ($(this).is(':selected')) {
            new_group = $(this).attr('data-group');
            $('.price').val($(this).attr('data-price'));
          }
        })
        if (group != new_group) {
          group = new_group;
          $('.window_inputs_item1, .window_inputs_item2').remove();
          $('.box_window_list').html('');
          getBoxIDList();
          getOptionsList();
          getDeliveryList();
        }
        calcTotal();
    });

    $('.price').on('change', function(event) {
      calcTotal();
    });

    $('.amount').on('change', function(event) {
      $('.vehicle_up').val($(this).val());
      calcTotal();
    });


    $('.delivery').on('change', function(event) {
      if ($(this).val() == 'Livraison') {
        $('.delivery-row').removeClass('hide');
        getDeliveryList();
      } else {
        $('.delivery-row').addClass('hide');
        $('.popup2_wrapper').find('.box_window_list').html('');
        $('.window_inputs_item2').remove();
        calcTotal();
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
          $('.box_type').html(response);
          calcTotal();
        }
    });
  }

  function getBoxIDList() {
    $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'box_id_list',
          agency_id: $('.agency_id').val(),
          box_type: $('.box_type').val(),
          take_date: $('.take_date').val(),
          return_date: $('.return_date').val()
        },
        cache: false,
        success: function(response) {
          $('.box_id').html(response);
        }
    });
  }

  function getOptionsList() {
    if ($('.box_type').val() == 0) {
      $('.popup1_wrapper').find('.box_window_list').html('');
      return false;
    }
    $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'options_list',
          select_type: $('.select_type').val(),
          box_type: $('.box_type').val()
        },
        cache: false,
        success: function(response) {
          $('.popup1_wrapper').find('.box_window_list').html(response);
          $('.popup1_wrapper').find('.window_btn').on('click', function(event) {
            event.preventDefault();
            $('.popup1_wrapper').fadeOut();
            var html = '';
            $('.popup1_wrapper').find('.box_option').each(function(index, brand){
              if($(this).is(':checked')) {
                html += '<div class="window_inputs window_inputs_item1 window_inputs_up' + index + '">' +
                  '<label for="vehicle_up' + index + '">' + $(this).val() + '</label>' +
                  '<input class="vehicle_up" id="vehicle_up' + index + '" name="vehicle_up' + index + '" type="number" min="0" step="0.01" value="' + $('.amount').val() + '" data-name="' + $(this).val() + '" data-price="' + $(this).attr('data-price') + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                  '&nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price' + index + '" name="vehicle_up_price' + index + '" type="number" min="0" step="0.01" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                  '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption(' + index + ')">' +
                    '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                  '</svg>' +
                '</div>';
              }
            });
            $('.window_inputs_item1').remove();
            $('.window_upBox_addBox').after(html);
            calcTotal();
          });
        }
    });
  }

  function getDeliveryList() {
    if ($('.box_type').val() == 0) {
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
                    '<input class="vehicle_down hide" id="vehicle_down' + index + '" name="vehicle_down' + index + '" type="number" min="0" step="0.01" value="1" data-name="' + $(this).val() + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                    '&nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price' + index + '" name="vehicle_price' + index + '" type="number" min="0" step="0.01" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
                    '<svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeDelivery(' + index + ')">' +
                      '<path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>' +
                    '</svg>' +
                  '</div>';
                } else {
                  html += '<div class="window_inputs window_inputs_item2 window_inputs_down' + index + '">' +
                    '<label for="vehicle_down' + index + '">' + $(this).val() + '</label>' +
                    '<input class="vehicle_down" id="vehicle_down' + index + '" name="vehicle_down' + index + '" type="number" min="0" step="0.01" value="' + $('.amount').val() + '" data-name="' + $(this).val() + '" data-idx="' + index + '" onChange="calcTotal()" />' +
                    '&nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price' + index + '" name="vehicle_price' + index + '" type="number" min="0" step="0.01" value="' + $(this).attr('data-price') + '" onChange="calcTotal()" />&nbsp;&euro;' +
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
    $('.window_inputs_up' + idx).remove();
    $('#option' + idx).prop('checked', false);
    calcTotal();
  }

  function removeDelivery(idx) {
    $('.window_inputs_down' + idx).remove();
    $('#delivery' + idx).prop('checked', false);
    calcTotal();
  }

  function calcTotal() {
    var total = $('.price').val() * $('.amount').val();
    $('.vehicle_up').each(function(index, brand){
      total = total + $('#vehicle_up_price' + $(this).attr('data-idx')).val() * $(this).val();
    });
    $('.vehicle_down').each(function(index, brand){
      total = total + $('#vehicle_price' + $(this).attr('data-idx')).val() * $(this).val();
    });
    $('.total').val(total);
    $('.totalPanel input').val(total + ' €');
  }
</script>

<?php include("end.php"); ?>