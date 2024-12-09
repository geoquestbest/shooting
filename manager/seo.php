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
	$page_title = "Provenance";
	include("header.php");

	$type = mysqli_real_escape_string($conn, $_GET['type']);
	switch($type) {
		case 1:
			$stitle = "Entreprises";
			$rq = " WHERE `select_type` LIKE '%entreprise%'";
			$rq2 = " WHERE `select_type` LIKE '%entreprise%'";
		break;
		case 2:
			$stitle = "Particuliers";
			$rq = " WHERE select_type` LIKE '%particulier%'";
			$rq2 = " WHERE `select_type` LIKE '%particulier%'";
		break;
		default:
			$stitle = "Total";
			$rq = " WHERE `id` >= 0";
			$rq2 = "";
		break;
	}

  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", time());
  }

  if (isset($_GET['agency_id']) && $_GET['agency_id'] != 0) {
    if ($rq != "") {
      $rq .= " AND `agency_id` = ".$_GET['agency_id'];
    } else {
      $rq = " WHERE `agency_id` = ".$_GET['agency_id'];
    }
  }

  $old_id = 0;

  $invite_reservations = $invite_demandes = $invite_realise  = $invite_perdu = $invite = $gclid_reservations = $gclid_demandes = $gclid_realise  = $gclid_perdu = $gclid = $total = 0;
  $invite_ids = $invite_reservations_ids = $invite_demandes_ids = $gclid_ids = $gclid_reservations_ids = $gclid_demandes_ids = $total_ids = "";

	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new`".$rq);
	while($row_orders = mysqli_fetch_assoc($result_orders)) {

    $row_orders_total = str_replace(",", ".", $row_orders['total']);

    if (((!isset($_GET['search_type']) || $_GET['search_type'] == 0) && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) || ($_GET['search_type'] == 1 && $row_orders['created_at'] >= strtotime($start_date." 00:00:00") && $row_orders['created_at'] <= strtotime($end_date." 23:59:59"))) {

    if ($row_orders['invite'] == 1) {
      if ($row_orders['status'] == 2) {
        $invite_reservations++;
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $invite_realise = $invite_realise + $row_orders_total*1.2;
        } else {
          $invite_realise = $invite_realise + $row_orders_total;
        }
        $invite_reservations_ids .= $row_orders['id'].",";
      } else {
        $invite_demandes++;
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $invite_perdu = $invite_perdu + $row_orders_total*1.2;
        } else {
          $invite_perdu = $invite_perdu + $row_orders_total;
        }
        $invite_demandes_ids .= $row_orders['id'].",";
      }
      $invite++;
      $invite_ids .= $row_orders['id'].",";
    }

    if ($row_orders['invite'] != 1 && trim($row_orders['gclid']) != "") {
      if ($row_orders['status'] == 2) {
        $gclid_reservations++;
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $gclid_realise = $gclid_realise + $row_orders_total*1.2;
        } else {
          $gclid_realise = $gclid_realise + $row_orders_total;
        }
        $gclid_reservations_ids .= $row_orders['id'].",";
      } else {
        $gclid_demandes++;
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $gclid_perdu = $gclid_perdu + $row_orders_total*1.2;
        } else {
          $gclid_perdu = $gclid_perdu + $row_orders_total;
        }
        $gclid_demandes_ids .= $row_orders['id'].",";
      }
      $gclid++;
      $gclid_ids .= $row_orders['id'].",";
    }

    $invite_demandes_ids_arr = explode(",", trim($invite_demandes_ids, ","));
    $invite_reservations_ids_arr = explode(",", trim($invite_reservations_ids, ","));

    $gclid_demandes_ids_arr = explode(",", trim($gclid_demandes_ids, ","));
    $gclid_reservations_ids_arr = explode(",", trim($gclid_reservations_ids, ","));

    if ($row_orders['status'] == 2) {
      $total_reservations++;
      if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
        $total_realise = $total_realise + $row_orders_total*1.2;
      } else {
        $total_realise = $total_realise + $row_orders_total;
      }
      if (!in_array($row_orders['id'], $invite_reservations_ids_arr) && !in_array($row_orders['id'], $gclid_reservations_ids_arr)) {
        $total_reservations_ids .= $row_orders['id'].",";
      }
    } else {
      $total_demandes++;
      if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
        $total_perdu = $total_perdu + $row_orders_total*1.2;
      } else {
        $total_perdu = $total_perdu + $row_orders_total;
      }
      if (!in_array($row_orders['id'], $invite_demandes_ids_arr) && !in_array($row_orders['id'], $gclid_demandes_ids_arr)) {
        $total_demandes_ids .= $row_orders['id'].",";
      }
    }
    $total++;

    $invite_ids_arr = explode(",", trim($invite_ids, ","));
    $gclid_ids_arr = explode(",", trim($gclid_ids, ","));

    if (!in_array($row_orders['id'], $invite_ids_arr) && !in_array($row_orders['id'], $gclid_ids_arr)) {
      $total_ids .= $row_orders['id'].",";
    }
	}
}

file_put_contents('invite_reservations.dat', trim($invite_reservations_ids, ","));
file_put_contents('invite_demandes.dat', trim($invite_demandes_ids, ","));
file_put_contents('invite.dat', trim($invite_ids, ","));

file_put_contents('gclid_reservations.dat', trim($gclid_reservations_ids, ","));
file_put_contents('gclid_demandes.dat', trim($gclid_demandes_ids, ","));
file_put_contents('gclid.dat', trim($gclid_ids, ","));

file_put_contents('total_reservations.dat', trim($total_reservations_ids, ","));
file_put_contents('total_demandes.dat', trim($total_demandes_ids, ","));
file_put_contents('total.dat', trim($total_ids, ","));

?>

<!-- begin panel -->
<style>
  .data-control {
    display: inline-block;
    height: 34px;
    padding: 6px 0;
    line-height: 1.42857143;
    color: #000;
    font-weight: bold;
  }
</style>
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Statistiques <?php echo $stitle ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal">
    <div class="form-group">
      <label class="col-md-1 control-label">Agence</label>
      <div class="col-md-1">
        <select class="form-control agency_id">
          <option value="0"<?php if (!isset($_GET['agency_id']) || $_GET['agency_id'] == 0) echo" selected" ?>>Toutes</option>
          <option value="1"<?php if (isset($_GET['agency_id']) && $_GET['agency_id'] == 1) echo" selected" ?>>Paris</option>
          <option value="2"<?php if (isset($_GET['agency_id']) && $_GET['agency_id'] == 2) echo" selected" ?>>Bordeaux</option>
        </select>
      </div>

      <div class="col-md-2">
            <select class="form-control search_type">
              <option value="0"<?php if (!isset($_GET['search_type']) || $_GET['search_type'] == 0) {echo" selected";} ?>>Date évenement</option>
              <option value="1"<?php if (isset($_GET['search_type']) && $_GET['search_type'] == 1) {echo" selected";} ?>>Date demande</option>
            </select>
      </div>

       <label class="col-md-2 control-label">Période</label>
        <div class="col-md-2">
          <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
        </div>
        <div class="col-md-2">
          <button class="btn btn-sm btn-success show">Afficher</button>
        </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-4">
        <h3>Bouche à l'oreille</h3>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Total</label>
      <div class="col-md-1">
        <a class="data-control" href="orders_seo.php?ids=invite" target="_blank"><?php echo $invite; ?></a>
      </div>
      <label class="col-md-1 control-label text-success" style="font-weight: bold;"><?php echo number_format($invite_realise + $invite_perdu, 2); ?>€</label>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Conversion (Demandes / Réservations)</label>
      <div class="col-md-1">
        <a class="data-control" href="orders_seo.php?ids=invite_demandes" target="_blank"><?php echo $invite_demandes; ?></a> / <a class="data-control" href="orders_seo.php?ids=invite_reservations" target="_blank"><?php echo $invite_reservations; ?></a>
      </div>
      <label class="col-md-1 control-label text-danger" style="font-weight: bold;"><?php echo number_format($invite_reservations/$invite*100, 2); ?>%</label>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Chiffre d'affaire (Réalisé / Perdu)</label>
      <div class="col-md-3">
        <span class="data-control text-primary"><?php echo number_format($invite_realise, 2); ?>€ / <?php echo number_format($invite_perdu, 2); ?>€</span>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-4">
        <h3>ADS</h3>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Total</label>
      <div class="col-md-1">
        <a class="data-control" href="orders_seo.php?ids=gclid" target="_blank"><?php echo $gclid; ?></a>
      </div>
      <label class="col-md-1 control-label text-success" style="font-weight: bold;"><?php echo number_format($gclid_realise + $gclid_perdu, 2); ?>€</label>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Conversion (Demandes / Réservations)</label>
      <div class="col-md-1">
        <a class="data-control" href="orders_seo.php?ids=gclid_demandes" target="_blank"><?php echo $gclid_demandes; ?></a> / <a class="data-control" href="orders_seo.php?ids=gclid_reservations" target="_blank"><?php echo $gclid_reservations; ?></a>
      </div>
      <label class="col-md-1 control-label text-danger" style="font-weight: bold;"><?php echo number_format($gclid_reservations/$gclid*100, 2); ?>%</label>
    </div>
     <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Chiffre d'affaire (Réalisé / Perdu)</label>
      <div class="col-md-3">
        <span class="data-control text-primary"><?php echo number_format($gclid_realise, 2); ?>€ / <?php echo number_format($gclid_perdu, 2); ?>€</span>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-4">
        <h3>SEO</h3>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Total</label>
      <div class="col-md-1">
        <a class="data-control" href="orders_seo.php?ids=total" target="_blank"><?php echo $total - $invite - $gclid; ?></a>
      </div>
      <label class="col-md-1 control-label text-success" style="font-weight: bold;"><?php echo number_format(($total_realise - $invite_realise - $gclid_realise) + ($total_perdu - $invite_perdu - $gclid_perdu), 2); ?>€</label>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Conversion (Demandes / Réservations)</label>
      <div class="col-md-1">
        <a class="data-control" href="orders_seo.php?ids=total_demandes" target="_blank"><?php echo $total_demandes - $invite_demandes - $gclid_demandes; ?></a> / <a class="data-control" href="orders_seo.php?ids=total_reservations" target="_blank"><?php echo $total_reservations - $invite_reservations - $gclid_reservations; ?></a>
      </div>
      <label class="col-md-1 control-label text-danger" style="font-weight: bold;"><?php echo number_format(($total_reservations - $invite_reservations - $gclid_reservations)/($total - $invite - $gclid)*100, 2); ?>%</label>
    </div>
     <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Chiffre d'affaire (Réalisé / Perdu)</label>
      <div class="col-md-3">
        <span class="data-control text-primary"><?php echo number_format($total_realise - $invite_realise - $gclid_realise, 2); ?>€ / <?php echo number_format($total_perdu - $invite_perdu - $gclid_perdu, 2); ?>€</span>
      </div>
    </div>
	</form>
	</div>
</div>
<!-- end panel -->

<?php
	include("footer.php");
?>

<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />

<script src="assets\plugins\flot\jquery.flot.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.time.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.resize.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.pie.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.stack.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.crosshair.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.categories.js"></script>
<script src="assets\plugins\flot\jquery.flot.barlabels.js"></script>

<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets\js\apps.min.js"></script>

<script>

	$(document).ready(function() {

		App.init();

		$('.start_date, .end_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.show').on('click', function(event) {
      event.preventDefault();
      if ($('.start_date').val() != '' && $('.end_date').val() != '') {
        window.location.href = 'seo.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&search_type=' + $('.search_type').val();
      }
    });

    $('.agency_id').on('change', function(event) {
      event.preventDefault();
      if ($('.agency_id').val() != 0) {
        if ($('.start_date').val() != '' && $('.end_date').val() != '') {
          window.location.href = 'seo.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&agency_id=' + $('.agency_id').val() + '&search_type=' + $('.search_type').val();
        } else {
          window.location.href = 'seo.php?type=<?php echo $type ?>&agency_id=' + $('.agency_id').val() + '&search_type=' + $('.search_type').val();
        }
      } else {
        if ($('.start_date').val() != '' && $('.end_date').val() != '') {
          window.location.href = 'seo.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&search_type=' + $('.search_type').val();
        } else {
          window.location.href = 'seo.php?type=<?php echo $type ?>';
        }
      }
    });

	});
</script>

<?php include("end.php"); ?>