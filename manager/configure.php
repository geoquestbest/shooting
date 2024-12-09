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
  $page_title = "Configuration";
  $breadcrumbs = '<a href="ipad_list.php?status=0" title="Liste des commandes">Liste des commandes</a>';
  include("header.php");
  $error = 0;
  if (!isset($_GET['event_id'])) {
    $event_id = 0;
  } else {
    $event_id = $_GET['event_id'];
  }
  if (isset($_GET['order_id'])) {
    $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$_GET['order_id']." AND `event_id` = ".$event_id);
    if (mysqli_num_rows($result_configure_orders) == 0) {
       $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = 4182");
    }
    /*
      $video = "";
      $image = "";
      $background_color = "#ffffff";
      $text_color = "#000000";
      $photo_switch = 1;
      $photo_delay_1 = 5;
      $photo_delay_2 = 3;
      $gif_switch = 1;
      $gif_delay_1 = 5;
      $gif_delay_2 = 3;
      $gif_speed = 450;
      $boomerang_switch = 1;
      $boomerang_delay = 5;
      $boomerang_duration = 3;
      $boomerang_speed = 1.5;
      $prop_switch = 1;
      $gallery_id = 0;
      $sms_switch = 1;
      $sms_text = "Hello !\n\nToute la Team ShootnBox a le plaisir de vous envoyer votre photo via le lien ci-dessous :\n\n[medialink]\n\nShootnbox, générateur de sourires !";
      $sms_popup = "Entrez votre numéro";
      $sms_button = "Envoyer";
      $email_switch = 1;
      $email_from = "contact@shootnbox.fr";
      $email_subject = "Votre photo Shootnbox !";
      $email_text = "Hello !\n\nToute la Team ShootnBox a le plaisir de vous envoyer votre photo via le lien ci-dessous :\n\n[medialink]\n\nShootnbox, générateur de sourires !";
      $email_popup = "Entrez votre Email";
      $email_button = "Envoyer";
      $rgpd_switch = 0;
      $rgpd_text = "Mentions RGPD";
      $rgpd_yes = "J'accepte";
      $rgpd_no = "Je refuse";
      $photo_amount = 0;
      $photo_max = 0;
    } else {*/
      $row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
      $video = $row_configure_orders['video'];
      $image = $row_configure_orders['image'];
      $background_color = $row_configure_orders['background_color'];
      $text_color = $row_configure_orders['text_color'];
      $photo_switch = $row_configure_orders['photo_switch'];
      $photo_delay_1 = $row_configure_orders['photo_delay_1'];
      $photo_delay_2 = $row_configure_orders['photo_delay_2'];
      $gif_switch = $row_configure_orders['gif_switch'];
      $gif_delay_1 = $row_configure_orders['gif_delay_1'];
      $gif_delay_2 = $row_configure_orders['gif_delay_2'];
      $gif_speed = $row_configure_orders['gif_speed'];
      $boomerang_switch = $row_configure_orders['boomerang_switch'];
      $boomerang_delay = $row_configure_orders['boomerang_delay'];
      $boomerang_duration = $row_configure_orders['boomerang_duration'];
      $boomerang_speed = $row_configure_orders['boomerang_speed'];
      $prop_switch = $row_configure_orders['prop_switch'];
      $gallery_id = $row_configure_orders['gallery_id'];
      $sms_switch = $row_configure_orders['sms_switch'];
      $sms_text = $row_configure_orders['sms_text'];
      $sms_popup = $row_configure_orders['sms_popup'];
      $sms_button =$row_configure_orders['sms_button'];
      $email_switch = $row_configure_orders['email_switch'];
      $email_from = $row_configure_orders['email_from'];
      $email_subject = $row_configure_orders['email_subject'];
      $email_text = $row_configure_orders['email_text'];
      $email_popup = $row_configure_orders['email_popup'];
      $email_button =$row_configure_orders['email_button'];
      $qr_code_switch = $row_configure_orders['qr_code_switch'];
      $rgpd_switch = $row_configure_orders['rgpd_switch'];
      $rgpd_text = $row_configure_orders['rgpd_text'];
      $rgpd_yes = $row_configure_orders['rgpd_yes'];
      $rgpd_no = $row_configure_orders['rgpd_no'];
      $photo_amount = $row_configure_orders['photo_amount'];
      $photo_max = $row_configure_orders['photo_max'];
    //}
  } else {
    $error = 1;
  }
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title">Configuration</h4>
  </div>
  <div class="panel-body">
    <form class="form-horizontal configure-order">
      <input type="hidden" class="video" value="<?php echo $video; ?>" />
      <input type="hidden" class="video1" value="" />
      <input type="hidden" class="video2" value="" />
      <input type="hidden" class="video3" value="" />
      <input type="hidden" class="image" value="<?php echo $image; ?>" />
      <div class="form-group<?php if (isset($_GET['vegas'])) echo" hide"; ?>">
        <label class="col-md-3 control-label">Vidéo</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group<?php if (!isset($_GET['vegas'])) echo" hide"; ?>">
        <label class="col-md-3 control-label">Vidéo  touchez l'écran</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm1">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group<?php if (!isset($_GET['vegas']) || $_GET['type'] == 2) echo" hide"; ?>">
        <label class="col-md-3 control-label">Vidéo préparez vous</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm2">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group<?php if (!isset($_GET['vegas']) || $_GET['type'] == 2) echo" hide"; ?>">
        <label class="col-md-3 control-label">Fin de session</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm3">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Image du fond</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm4">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">Bouton</h3>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Couleur du fond</label>
        <div class="col-md-2">
          <div class="input-group colorpicker-component background-color" data-color="<?php echo $background_color ?>" data-color-format="rgb" />
              <input type="text" value="<?php echo $background_color ?>" class="form-control background_color" readonly="" />
              <span class="input-group-addon"><i></i></span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Couleur du texte</label>
        <div class="col-md-2">
          <div class="input-group colorpicker-component text-color" data-color="<?php echo $text_color ?>" data-color-format="rgb" />
              <input type="text" value="<?php echo $text_color ?>" class="form-control text_color" readonly="" />
              <span class="input-group-addon"><i></i></span>
          </div>
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">Photo</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="photo_switch"<?php if ($photo_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nombre de photos</label>
        <div class="col-md-2">
          <input type="text" class="form-control photo_amount" value="<?php echo $photo_amount ?>" placeholder="Nombre de photos" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nombre total des photos<br /><small>(0 - illimité)</small></label>
        <div class="col-md-2">
          <input type="text" class="form-control photo_max" value="<?php echo $photo_max ?>" placeholder="Nombre total des photos" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Compte à rebours photo 1,<br />sens: <b><span class="photo_delay_1_display"><?php echo $photo_delay_1 ?></span> sec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-1">
            <input type="text" class="photo_delay_1" value="<?php echo $photo_delay_1 ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Compte à rebours photos,<br />sens: <b><span class="photo_delay_2_display"><?php echo $photo_delay_2 ?></span> sec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-2">
            <input type="text" class="photo_delay_2" value="<?php echo $photo_delay_2 ?>" />
          </div>
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">Gif</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="gif_switch"<?php if ($gif_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Compte à rebours photo 1,<br />sens: <b><span class="gif_delay_1_display"><?php echo $gif_delay_1 ?></span> sec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-1">
            <input type="text" class="gif_delay_1" value="<?php echo $gif_delay_2 ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Compte à rebours photos,<br />sens: <b><span class="gif_delay_2_display"><?php echo $gif_delay_2 ?></span> sec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-2">
            <input type="text" class="gif_delay_2" value="<?php echo $gif_delay_2 ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Vitesse,<br />sens: <b><span class="gif_speed_display"><?php echo $gif_speed ?></span> msec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-3">
            <input type="text" class="gif_speed" value="<?php echo $gif_speed ?>" />
          </div>
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">Boomerang</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="boomerang_switch"<?php if ($boomerang_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Compte à rebours,<br />sens: <b><span class="boomerang_delay_display"><?php echo $boomerang_delay ?></span> sec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-1">
            <input type="text" class="boomerang_delay" value="<?php echo $boomerang_delay ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Durée d'enregistrement,<br />sens: <b><span class="boomerang_duration_display"><?php echo $boomerang_duration ?></span> sec.</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-2">
            <input type="text" class="boomerang_duration" value="<?php echo $boomerang_duration ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Vitesse,<br />sens: <b><span class="boomerang_speed_display"><?php echo $boomerang_speed ?></span>x</b></label>
        <div class="col-md-4">
          <div class="slider-wrapper slider-wrapper-3">
            <input type="text" class="boomerang_speed" value="<?php echo $boomerang_speed ?>" />
          </div>
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">Props</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="prop_switch"<?php if ($prop_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Catégorie</label>
        <div class="col-md-3">
          <select class="form-control gallery_id">
            <option value="0"<?php if ($gallery_id == 0) {echo" selected";} ?>>Choisir une catégorie ...</option>
            <?php
              $result_gallery = mysqli_query($conn, "SELECT * FROM `gallery` ORDER BY position");
              while($row_gallery = mysqli_fetch_assoc($result_gallery)) {
                echo"<option value=\"".$row_gallery['id']."\""; if ($gallery_id == $row_gallery['id']) {echo" selected";} echo">".$row_gallery['title']."</option>";
              }
            ?>
          </select>
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">SMS</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="sms_switch"<?php if ($sms_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Corps</label>
        <div class="col-md-4">
          <textarea class="form-control sms_text" placeholder="Corps" style="height: 100px;"><?php echo $sms_text ?></textarea><br />
          <a href="javascript:void(0)" class="add-image">Ajouter lien photo</a>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Texte popup</label>
        <div class="col-md-4">
          <input type="text" class="form-control sms_popup" value="<?php echo $sms_popup ?>" placeholder="Texte popup" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Texte envoi</label>
        <div class="col-md-4">
          <input type="text" class="form-control sms_button" value="<?php echo $sms_button ?>" placeholder="Texte envoi" />
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">E-mail</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="email_switch"<?php if ($email_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Mail expéditeur</label>
        <div class="col-md-4">
          <input type="email" class="form-control email_from" value="<?php echo $email_from ?>" placeholder="Mail expéditeur" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Objet</label>
        <div class="col-md-4">
          <input type="text" class="form-control email_subject" value="<?php echo $email_subject ?>" placeholder="Objet" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Corps</label>
        <div class="col-md-4">
          <textarea class="form-control email_text" placeholder="Corps" style="height: 100px;"><?php echo $email_text ?></textarea><br />
          <a href="javascript:void(0)" class="add-image">Ajouter lien media</a>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Texte popup</label>
        <div class="col-md-4">
          <input type="text" class="form-control email_popup" value="<?php echo $email_popup ?>" placeholder="Texte popup" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Texte envoi</label>
        <div class="col-md-4">
          <input type="text" class="form-control email_button" value="<?php echo $email_button ?>" placeholder="Texte envoi" />
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">QR Code</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="qr_code_switch"<?php if ($qr_code_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <br /><br />
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-3">
          <h3 style="margin: 0;">RGPD</h3>
        </div>
        <div class="col-md-1">
          <input type="checkbox" class="rgpd_switch"<?php if ($rgpd_switch == 1) {echo" checked";} ?> />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Corps</label>
        <div class="col-md-4">
          <textarea class="form-control rgpd_text" placeholder="Corps" style="height: 100px;"><?php echo $rgpd_text ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Texte "OUI"</label>
        <div class="col-md-4">
          <input type="text" class="form-control rgpd_yes" value="<?php echo $rgpd_yes ?>" placeholder="Texte OUI" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Texte "NON"</label>
        <div class="col-md-4">
          <input type="text" class="form-control rgpd_no" value="<?php echo $rgpd_no ?>" placeholder="Texte NON" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
          <button type="submit" class="btn btn-sm btn-success"><?php echo SAVE ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- end panel -->


<?php
  include("footer.php");
?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/dropzone/css/basic.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/dropzone.css" rel="stylesheet">
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css" rel="stylesheet">
<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet">
<link href="assets/plugins/powerange/powerange.min.css" rel="stylesheet">
<style>
  .switchery>small {
    background: #CAD0D6!important;
  }

  .range-handle {
    background: #ff00ff!important;
    height: 16px;
    top: -6px;
    width: 16px;
  }

  .slider-wrapper-1 .range-quantity {
    background-color: #ff00ff!important;
  }

  .slider-wrapper-2 .range-quantity {
    background-color: #017afd!important;
  }

  .slider-wrapper-3 .range-quantity {
    background-color: #f59c1a!important;
  }
</style>
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>
<script src="assets/plugins/powerange/powerange.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

  $(document).ready(function() {

    App.init();

    $('.background-color, .text-color').colorpicker({format:"hex"});

    new Switchery(document.querySelector('.photo_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Powerange(document.querySelector('.photo_delay_1'), { min: 1, max: 10, start: <?php echo $photo_delay_1 ?> });
    $('.photo_delay_1').on('change', function() {
      $('.photo_delay_1_display').html($('.photo_delay_1').val());
    });

    new Powerange(document.querySelector('.photo_delay_2'), { min: 1, max: 10, start: <?php echo $photo_delay_2 ?> });
    $('.photo_delay_2').on('change', function() {
      $('.photo_delay_2_display').html($('.photo_delay_2').val());
    });


    new Switchery(document.querySelector('.gif_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Powerange(document.querySelector('.gif_delay_1'), { min: 1, max: 10, start: <?php echo $gif_delay_1 ?> });
    $('.gif_delay_1').on('change', function() {
      $('.gif_delay_1_display').html($('.gif_delay_1').val());
    });

    new Powerange(document.querySelector('.gif_delay_2'), { min: 1, max: 10, start: <?php echo $gif_delay_2 ?> });
    $('.gif_delay_2').on('change', function() {
      $('.gif_delay_2_display').html($('.gif_delay_2').val());
    });

    new Powerange(document.querySelector('.gif_speed'), { min: 50, max: 500, start: <?php echo $gif_speed ?>, step: 50 });
    $('.gif_speed').on('change', function() {
      $('.gif_speed_display').html($('.gif_speed').val());
    });

    new Switchery(document.querySelector('.boomerang_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Powerange(document.querySelector('.boomerang_delay'), { min: 1, max: 10, start: <?php echo $boomerang_delay ?> });
    $('.boomerang_delay').on('change', function() {
      $('.boomerang_delay_display').html($('.boomerang_delay').val());
    });

    new Powerange(document.querySelector('.boomerang_duration'), { min: 1, max: 10, start: <?php echo $boomerang_duration ?> });
    $('.boomerang_duration').on('change', function() {
      $('.boomerang_duration_display').html($('.boomerang_duration').val());
    });

    new Powerange(document.querySelector('.boomerang_speed'), { min: 1, max: 5, start: <?php echo $boomerang_speed ?>, step: 0.5, decimal: true });
    $('.boomerang_speed').on('change', function() {
      $('.boomerang_speed_display').html($('.boomerang_speed').val());
    });

    new Switchery(document.querySelector('.prop_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Switchery(document.querySelector('.sms_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Switchery(document.querySelector('.email_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Switchery(document.querySelector('.qr_code_switch'), { color: '#00acac', jackColor: '#9decff' });

    new Switchery(document.querySelector('.rgpd_switch'), { color: '#00acac', jackColor: '#9decff' });


    Dropzone.options.dropzoneForm = {
      paramName: 'image',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 4,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b><?php echo DRAG_CLICK_LOAD_IMAGE ?></b><br />(4 <?php echo NUMBER_SIZES_FILES ?> 10Mb)',
      dictMaxFilesExceeded: '<?php echo NUMBER_FILES_EXCEEDED ?>',
      uploadMultiple: false,
      init: function() {

        <?php if ($video != "") { ?>

          <?php
          $video_arr = explode(";", $video);
        ?>
            var existingFileCount = 0;
        <?php
            foreach ($video_arr as $key => $value) {

        ?>

          var mockFile = {name: '<?php echo $value; ?>', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$value); ?>};
          this.options.addedfile.call(this, mockFile);
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
                  data: {event: 'delete_configure_orders_video', id: <?php echo $_GET['order_id']; ?>},
                  cache: false,
                  success: function(responce) {
                    _this.removeFile(mockFile);
                    $('.video').val('');
                  }
                });
              }
            }, function(dismiss) {
              // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            });
          });

          mockFile.previewElement.appendChild(removeButton);
          existingFileCount++;
<?php
            }
         } ?>

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
              data: {event: 'remove_configure_orders_video', video: $('.video').val()},
              cache: false,
              success: function(responce){
                $('.video').val('');
              }
            });
          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });

        this.on('success', function(file, responseText) {
          $('.video').val($('.video').val() != '' ? $('.video').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm1 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 100,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les vidéos.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 100 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$_GET['order_id']." AND `type_id` = 1");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
        ?>
              var mockFile = {name: 'Video', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
              this.options.addedfile.call(this, mockFile);
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
                    data: {event: 'delete_gallery_video', video: '<?php echo $row_gallery_videos['video'] ?>'},
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
          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg delete-gallery-image" title="Supprimer"><i class="fa fa-times"></i></a>');

          // Capture the Dropzone instance as closure.
          var _this = this;

          // Listen to the click event
          removeButton.addEventListener('click', function(e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();

            var index = $(this).index('.delete-gallery-image');

            e.stopPropagation();

            // Remove the file preview.
            _this.removeFile(file);

            // If you want to the delete the file on the server as well,
            // you can do the AJAX request here.

            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'remove_gallery_video', videos: $('.video1').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.video1').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.video1').val($('.video1').val() != '' ? $('.video1').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm2 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 100,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les vidéos.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 100 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$_GET['order_id']." AND `type_id` = 2");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
        ?>
              var mockFile = {name: 'Video', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
              this.options.addedfile.call(this, mockFile);
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
                    data: {event: 'delete_gallery_video', video: '<?php echo $row_gallery_videos['video'] ?>'},
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
          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg delete-gallery-image" title="Supprimer"><i class="fa fa-times"></i></a>');

          // Capture the Dropzone instance as closure.
          var _this = this;

          // Listen to the click event
          removeButton.addEventListener('click', function(e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();

            var index = $(this).index('.delete-gallery-image');

            e.stopPropagation();

            // Remove the file preview.
            _this.removeFile(file);

            // If you want to the delete the file on the server as well,
            // you can do the AJAX request here.

            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'remove_gallery_video', videos: $('.video2').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.video2').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.video2').val($('.video2').val() != '' ? $('.video2').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm3 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 100,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les vidéos.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 100 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = ".$_GET['order_id']." AND `type_id` = 3");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
        ?>
              var mockFile = {name: 'Video', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
              this.options.addedfile.call(this, mockFile);
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
                    data: {event: 'delete_gallery_video', video: '<?php echo $row_gallery_videos['video'] ?>'},
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
          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg delete-gallery-image" title="Supprimer"><i class="fa fa-times"></i></a>');

          // Capture the Dropzone instance as closure.
          var _this = this;

          // Listen to the click event
          removeButton.addEventListener('click', function(e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();

            var index = $(this).index('.delete-gallery-image');

            e.stopPropagation();

            // Remove the file preview.
            _this.removeFile(file);

            // If you want to the delete the file on the server as well,
            // you can do the AJAX request here.

            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'remove_gallery_video', videos: $('.video3').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.video3').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.video3').val($('.video3').val() != '' ? $('.video3').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm4 = {
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

         <?php if ($image != "" && file_exists(ADMIN_UPLOAD_IMAGES_DIR.$image) && file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'))) { ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120')); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120') ?>');
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
                  data: {event: 'delete_configure_orders_image', id: <?php echo $_GET['order_id']; ?>},
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
              data: {event: 'remove_image', image: $('.second_image').val()},
              cache: false,
              success: function(responce){
                $('input.image').val('');
              }
            });
          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });

        this.on('success', function(file, responseText) {
          $('input.image').val(responseText);
        });
      }
    }

    $('.add-image').on('click', function(event){
      event.preventDefault();
      $(this).parent('div').find('textarea').val($(this).parent('div').find('textarea').val() + '[medialink]');
    });



    $('.configure-order').on('submit', function(event){
      event.preventDefault();

      if ($('.prop_switch').is(':checked') && +$('.gallery_id').val() == 0) {
        showError('Choisissez une catégorie !');
        return false;
      }

      if ($('.sms_switch').is(':checked') && ($('.sms_text').val() == '' || $('.sms_popup').val() == '' || $('.sms_button').val() == '')) {
        showError('Remplissez tous les champs pour les SMS !');
        return false;
      }

      if ($('.email_switch').is(':checked') && ($('.email_from').val() == '' || $('.email_subject').val() == '' || $('.email_text').val() == '' || $('.email_popup').val() == '' || $('.email_button').val() == '')) {
        showError('Remplissez tous les champs pour les E-mail !');
        return false;
      }

      if ($('.rgpd_switch').is(':checked') && ($('.rgpd_text').val() == '' || $('.rgpd_yes').val() == '' || $('.rgpd_no').val() == '')) {
        showError('Remplissez tous les champs pour les E-mail !');
        return false;
      }

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'configure_order',
          id: <?php echo $_GET['order_id']; ?>,
          event_id: <?php echo $event_id; ?>,
          video: $('.video').val(),
          video1: $('.video1').val(),
          video2: $('.video2').val(),
          video3: $('.video3').val(),
          image: $('input.image').val(),
          background_color: $('.background_color').val(),
          text_color: $('.text_color').val(),
          photo_switch: $('.photo_switch').is(':checked') ? 1 : 0,
          photo_delay_1: $('.photo_delay_1').val(),
          photo_delay_2: $('.photo_delay_2').val(),
          gif_switch: $('.gif_switch').is(':checked') ? 1 : 0,
          gif_delay_1: $('.gif_delay_1').val(),
          gif_delay_2: $('.gif_delay_2').val(),
          gif_speed: $('.gif_speed').val(),
          boomerang_switch: $('.boomerang_switch').is(':checked') ? 1 : 0,
          boomerang_delay: $('.boomerang_delay').val(),
          boomerang_duration: $('.boomerang_duration').val(),
          boomerang_speed: $('.boomerang_speed').val(),
          prop_switch: $('.prop_switch').is(':checked') ? 1 : 0,
          gallery_id: $('.gallery_id').val(),
          sms_switch: $('.sms_switch').is(':checked') ? 1 : 0,
          sms_text: $('.sms_text').val(),
          sms_popup: $('.sms_popup').val(),
          sms_button: $('.sms_button').val(),
          email_switch: $('.email_switch').is(':checked') ? 1 : 0,
          email_from: $('.email_from').val(),
          email_subject: $('.email_subject').val(),
          email_text: $('.email_text').val(),
          email_popup: $('.email_popup').val(),
          email_button: $('.email_button').val(),
          qr_code_switch: $('.qr_code_switch').is(':checked') ? 1 : 0,
          rgpd_switch: $('.rgpd_switch').is(':checked') ? 1 : 0,
          rgpd_text: $('.rgpd_text').val(),
          rgpd_yes: $('.rgpd_yes').val(),
          rgpd_no: $('.rgpd_no').val(),
          photo_amount: $('.photo_amount').val(),
          photo_max: $('.photo_max').val(),
        },
        cache: false,
        success: function(responce){
          if (responce == 'done'){
            swal({
              title: '<?php echo DONE ?>',
              text: '<?php echo ENTRY_SUCCESSFULLY_ADDED ?>',
              type: 'success',
              confirmButtonColor: '#348fe2',
              confirmButtonText: 'OK',
              closeOnConfirm: true
            }).then(function() {
              window.location.href = '<?php echo $_GET['back_url'] ?>';
            });
          } else {
            showError(responce);
          }
        }
      });
    });

    <?php if ($error == 1) { ?>
      swal({
        title: '<?php echo ERROR ?>',
        text: '<?php echo CAN_NOT_PROCESS_REQUEST ?>',
        type: 'error',
        confirmButtonColor: '#348fe2',
        confirmButtonText: '<?php echo CLOSE ?>',
        closeOnConfirm: true
      }).then(function() {
        window.location.href = 'ipad_list.php?status=0';
      });
    <?php } ?>

  });
</script>

<?php include("end.php"); ?>