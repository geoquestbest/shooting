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
	$page_title = "Modifier un paiement";
	$breadcrumbs = '<a href="orders_list.php?status='.$_GET['status'].'" title="Demande">Demande</a>';
	include("header.php");
	$error = 0;
	if (isset($_GET['order_id'])) {
		$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
		if (mysqli_num_rows($result_orders) == 0) {
			$error = 1;
		} else {
			$row_orders = mysqli_fetch_assoc($result_orders);
			$delivery = mb_strpos($row_orders['selected_options'], 'Retrait boutique') ? 'Retrait boutique' : 'Livraison';
		}
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
		<h4 class="panel-title">Modifier une demande</h4>
	</div>
	<div class="panel-body">
		<div id="fullsize-pos"></div>
		<form class="form-horizontal edit-order">
      <div class="form-group">
        <label class="col-md-3 control-label">Modalité de paiement</label>
        <div class="col-md-2">
          <select class="form-control payment_type2">
            <option value="0"<?php if ($row_orders['payment_type2'] == "0") {echo" selected";} ?>>Modalité de paiement...</option>
            <option value="1"<?php if ($row_orders['payment_type2'] == "1") {echo" selected";} ?>>CB</option>
            <option value="2"<?php if ($row_orders['payment_type2'] == "2") {echo" selected";} ?>>Chèque</option>
            <option value="3"<?php if ($row_orders['payment_type2'] == "3") {echo" selected";} ?>>Espèces</option>
            <option value="4"<?php if ($row_orders['payment_type2'] == "4") {echo" selected";} ?>>Virement</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Enlèvement</label>
        <div class="col-md-2">
          <input type="text" class="form-control take_date" value="<?php echo $row_orders['take_date'] ?>" placeholder="Date de l’évènement" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Retour</label>
        <div class="col-md-2">
          <input type="text" class="form-control return_date" value="<?php echo $row_orders['return_date'] ?>" placeholder="Date de retour" />
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

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet">
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/basic.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/dropzone.css" rel="stylesheet">
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

    $('.take_date, .return_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.take_date').on('change', function() {
      var date = moment($(this).val(), 'DD.MM.YYYY').toDate();
      date = moment(date).add(3, 'days');
      $('.return_date').val(moment(date).format('DD.MM.YYYY'));
    });


		$('.edit-order').on('submit', function(event) {
			event.preventDefault();

      if ($('.payment_type2').val() == 0) {
        showError('Choisissez une option de paiement !');
        return false;
      }

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {
					event: 'edit_payment',
					id: <?php echo $order_id ?>,
          deposit: 0,
          payment_type1: 0,
          payment_type2: $('.payment_type2').val(),
          take_date: $('.take_date').val(),
          return_date: $('.return_date').val(),
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
							window.location.href = 'epayments_list.php?status=<?php echo $_GET['status'] ?>';
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
				window.location.href = 'epayments_list.php?status=<?php echo $_GET['status'] ?>';
			});
		<?php } ?>
	});
</script>

<?php include("end.php"); ?>