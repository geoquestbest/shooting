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
	if (isset($_GET['order_id'])) {
		$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
		if (mysqli_num_rows($result_orders) == 0) {
			$error = 1;
		} else {
			$row_orders = mysqli_fetch_assoc($result_orders);
			$delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
      $result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `order_id` = ".$row_orders['id']);
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
      .window_upBox_addBox:hover > svg,
      .window_downBox_addBox:hover > svg,
      .addTemplate_btn:hover > svg {
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
        <div class="col-md-1 col5p"></div>
        <div class="col-md-10">
          <h4 class="title">INFORMATION AGENCE</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Commercial</label>
        <div class="col-md-4">
          <select class="form-control user_id"<?php if (isset($row_orders['user_id']) && $row_orders['user_id'] != 0) {echo" disabled";} ?>>
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
          <h4 class="title">BORNE</h4>
        </div>
        <div class="col-md-1 col5p"></div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label">Borne</label>
				<div class="col-md-2">
          <select class="form-control box_type">
            <option value="" data-price="0" selected>Choisissez le type de stand</option>
            <?php
            if (strpos($row_orders['select_type'], 'entreprise') !== false) {
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
            }
            ?>
          </select>
        </div>
        <label class="col-md-1 control-label">Prix, €</label>
        <div class="col-md-1">
          <input type="text" class="form-control price" value="<?php echo ($row_orders['price'] != 0 ? $row_orders['price'] : $price) ?>" placeholder="Prix, €" />
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
            <?php
              if (strpos($row_orders['select_type'], 'entreprise') !== false) {
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
              $deliverys[] = array('name' => 'Kilométriques supplémentaires', 'price' => 49);
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
                  echo'<div class="window_inputs window_inputs_item1 window_inputs_up'.$key.'">
                    <label for="vehicle_up'.$key.'">'.$value['name'].'</label>
                    <input class="vehicle_up" id="vehicle_up'.$key.'" name="vehicle_up'.$key.'" type="number" min="0" step="0.01" value="'.$amount_arr[1].'" data-name="'.$value['name'].'" data-price="'.$value['price'].'" data-idx="'.$key.'" onChange="calcTotal()" />
                    &nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price'.$key.'" name="vehicle_up_price'.$key.'" type="number" min="0" step="0.01" value="'.(isset($amount_arr[2]) ? $amount_arr[2] : $value['price']).'" onChange="calcTotal()" />&nbsp;&euro;
                    <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$key.')">
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
          <select class="form-control box_id" multiple="true"<?php if ($_GET['status'] != 2) echo" disabled"; ?>>
            <option value="">Numéro de la borne...</option>
            <?php
            if ($row_orders['agency_id'] != 0) {
              $prefix = $row_orders['agency_id'] == 1 ? "P" : "B";
              $box_ids = explode(",", $row_orders['box_id']);
              switch($row_orders['box_type']) {
                case "Ring":
                  for ($i = 1; $i <= $row_settings['ring']; $i++) {
                    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'R$i/$prefix'");
                    if (mysqli_num_rows($result_repair) == 0) {
                      $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>R'.$i.'/'.$prefix.'</option>';
                    }
                  }
                break;
                case "Vegas":
                case "Vegas_800":
                case "Vegas_1200":
                  for ($i = 1; $i <= $row_settings['vegas']; $i++) {
                    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'V$i/$prefix'");
                    if (mysqli_num_rows($result_repair) == 0) {
                      $html .= '<option value="V'.$i.'/'.$prefix.'"'.(in_array('V'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>V'.$i.'/'.$prefix.'</option>';
                    }
                  }
                  for ($i = 1; $i <= $row_settings['vegas_slim']; $i++) {
                    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'VS$i/$prefix'");
                    if (mysqli_num_rows($result_repair) == 0) {
                      $html .= '<option value="VS'.$i.'/'.$prefix.'"'.(in_array('VS'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>VS'.$i.'/'.$prefix.'</option>';
                    }
                  }
                break;
                case "Miroir":
                case "Miroir_800":
                case "Miroir_1200":
                  for ($i = 1; $i <= $row_settings['miroir']; $i++) {
                    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'M$i/$prefix'");
                    if (mysqli_num_rows($result_repair) == 0) {
                      $html .= '<option value="M'.$i.'/'.$prefix.'"'.(in_array('M'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>M'.$i.'/'.$prefix.'</option>';
                    }
                  }
                break;
                case "Spinner_360":
                  for ($i = 1; $i <= $row_settings['spinner']; $i++) {
                    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'S$i/$prefix'");
                    if (mysqli_num_rows($result_repair) == 0) {
                      $html .= '<option value="S'.$i.'/'.$prefix.'"'.(in_array('S'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>S'.$i.'/'.$prefix.'</option>';
                    }
                  }
                break;
                case "Réalité_Virtuelle":
                  for ($i = 1; $i <= $row_settings['vr']; $i++) {
                    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'VR$i/$prefix'");
                    if (mysqli_num_rows($result_repair) == 0) {
                      $html .= '<option value="VR'.$i.'/'.$prefix.'"'.(in_array('VR'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>VR'.$i.'/'.$prefix.'</option>';
                    }
                  }
                break;
              }
              echo $html;
            }
            ?>
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
              $delivery_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(".", "", $row_orders['delivery_options']))));
              $delivery_options_value_arr = explode(",", $row_orders['delivery_options']);
              $i = 0;
              foreach ($deliverys as $key => $value) {
                if ($value['name'] != "Retrait boutique") {
                  $html_popup2 .= '<div class="window_inputs">
                    <input type="checkbox" id="option'.$key.'" name="option'.$key.'" value="'.$value['name'].'" class="box_option" data-price="'.$value['price'].'"'.(in_array($value['name'], $delivery_options_arr) ? ' checked' : '').' />
                    <label for="option'.$key.'">'.$value['name'].'</label>
                  </div>';
                  if (in_array($value['name'], $delivery_options_arr)) {
                    $amount_arr = explode(":", $delivery_options_value_arr[$i]);
                    if ($value['name'] == "Kilométriques supplémentaires") {
                      echo'<div class="window_inputs window_inputs_item2 window_inputs_down'.$key.'">
                        <label for="vehicle_down'.$key.'">'.$value['name'].'</label>
                        <input class="vehicle_down hide" id="vehicle_down'.$key.'" name="vehicle_down'.$key.'" type="number" min="0" step="0.01" value="'.$amount_arr[1].'" data-name="'.$value['name'].'" data-idx="'.$key.'" onChange="calcTotal()" />
                        &nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price'.$key.'" name="vehicle_price'.$key.'" type="number" min="0" step="0.01" value="'.$amount_arr[2].'" onChange="calcTotal()" />&nbsp;&euro;
                        <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$key.')">
                          <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                        </svg>
                      </div>';
                    } else {
                      echo'<div class="window_inputs window_inputs_item2 window_inputs_down'.$key.'">
                        <label for="vehicle_down'.$key.'">'.$value['name'].'</label>
                        <input class="vehicle_down" id="vehicle_down'.$key.'" name="vehicle_down'.$key.'" type="number" min="0" step="0.01" value="'.$amount_arr[1].'" data-name="'.$value['name'].'" data-idx="'.$key.'" onChange="calcTotal()" />
                         &nbsp;&nbsp;<input class="vehicle_price" id="vehicle_price'.$key.'" name="vehicle_price'.$key.'" type="number" min="0" step="0.01" value="'.$amount_arr[2].'" onChange="calcTotal()" />&nbsp;&euro;
                        <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$key.')">
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
			<div class="form-group">
				<label class="col-md-3 control-label">Lieu de l’évènement</label>
				<div class="col-md-4">
					<input type="text" class="form-control event_place" value="<?php echo $row_orders['event_place'] ?>" placeholder="Lieu de l’évènement" />
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label">Date et l’heure d’enlèvement</label>
        <div class="col-md-2">
          <input type="text" class="form-control take_date" value="<?php echo $row_orders['take_date'] ?>" placeholder="Date et l’heure d’enlèvement" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Date et l’heure du retour</label>
        <div class="col-md-2">
          <input type="text" class="form-control return_date" value="<?php echo $row_orders['return_date'] ?>" placeholder="Date et l’heure du retour" />
        </div>
      </div>
       <div class="form-group">
        <label class="col-md-3 control-label">Horaire</label>
        <div class="col-md-4">
          <input type="text" class="form-control horaire" value="<?php echo $row_orders['horaire'] ?>" placeholder="Horaire" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Informations complémentaires</label>
        <div class="col-md-4">
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
          <input type="text" class="form-control discount" value="<?php echo $row_orders['discount'] ?>" placeholder="Code Promo" />
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
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
  var group = 0,
    new_group = 0;

  <?php echo $js; ?>

	$(document).ready(function() {

		App.init();

    var editor = CKEDITOR.replace('email_message');
    CKFinder.setupCKEditor(editor, 'assets/plugins/ckfinder/');

		// $('.password').passwordStrength({targetDiv:'.passwordStrength'});

		elasticArea('.address');

		$('.box_id').select2({placeholder: 'Numéro de la borne'});

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

    /*
    $('.take_date, .return_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.take_date').on('change', function() {
      var date = moment($(this).val(), 'DD.MM.YYYY').toDate();
      date = moment(date).add(3, 'days');
      $('.return_date').val(moment(date).format('DD.MM.YYYY'));
    });*/

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

      if (box_id != '' && box_id != 'null' && $('.amount').val() != box_id.split(',').length) {
        showError('Nombre des bornes ne corresponde pas à la commande!');
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
      } else {
        $('.box_id').prop('disabled', true);
      }
    });


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

    $('.select_type').on('change', function(event) {
      $('.window_inputs_item1, .window_inputs_item2').remove();
      $('.box_window_list').html('');
      getBornesList();
    });

    $('.agency_id').on('change', function(event) {
      getBoxIDList();
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

    $('.add-btn').on('click', function(event) {
      event.preventDefault();
      $('.add-template').removeClass('hide');
      $('.add-btn').addClass('hide');
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