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
	$page_title = "Modification d'un code promotionnel";
	$breadcrumbs = '<a href="promo_list.php" title="Liste des codes promotionnels">Liste des codes promotionnels</a>';
	include("header.php");
	$error = 0;
	if (isset($_GET['promo_id']) && $_SESSION['user']['role'] == 1) {
		$promo_id = mysqli_real_escape_string($conn, $_GET['promo_id']);
		$result_promocode = mysqli_query($conn, "SELECT * FROM `promocode` WHERE `id` = ".$promo_id);
		if (mysqli_num_rows($result_promocode) == 0) {
			$error = 1;
		} else {
			$row_promocode = mysqli_fetch_assoc($result_promocode);
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
		<h4 class="panel-title">Modification d'un code promotionnel</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-promo">
			<div class="form-group">
				<label class="col-md-3 control-label required">Code promo</label>
				<div class="col-md-4">
					<input type="text" class="form-control promocode" value="<?php echo $row_promocode['promocode']; ?>" placeholder="Code promo" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Début des actions</label>
				<div class="col-md-4">
					<input type="text" class="form-control start_date" value="<?php echo date("d.m.Y", $row_promocode['start_date']); ?>" placeholder="Début des actions" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Fin de l'action</label>
				<div class="col-md-4">
					<input type="text" class="form-control end_date" value="<?php echo date("d.m.Y", $row_promocode['end_date']); ?>" placeholder="Fin de l'action" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Jours</label>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="1" <?php if (strpos($row_promocode['weekday'], "1") !== false) { echo"checked"; } ?>/>
            LUN
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="2" <?php if (strpos($row_promocode['weekday'], "2") !== false) { echo"checked"; } ?>/>
            MAR
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="3" <?php if (strpos($row_promocode['weekday'], "3") !== false) { echo"checked"; } ?>/>
            MER
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="4" <?php if (strpos($row_promocode['weekday'], "4") !== false) { echo"checked"; } ?>/>
            JEU
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="5" <?php if (strpos($row_promocode['weekday'], "5") !== false) { echo"checked"; } ?>/>
            VEN
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="6" <?php if (strpos($row_promocode['weekday'], "6") !== false) { echo"checked"; } ?>/>
            SAM
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="7" <?php if (strpos($row_promocode['weekday'], "7") !== false) { echo"checked"; } ?>/>
            DIM
          </label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Montant de la remise, €</label>
				<div class="col-md-4">
					<input type="number" class="form-control sum" value="<?php echo $row_promocode['sum']; ?>" placeholder="Montant de la remise, €" >
				</div>
			</div>
			<div class="form-group">
        <label class="col-md-3 control-label required">Bornes</label>
        <div class="col-md-6">
          <select class="form-control bornes_ids" multiple="multiple">
            <?php
            	$bornes_ids_arr = explode(",", $row_promocode['bornes_ids']);
              $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` ORDER BY `title`");
              while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
                echo'<option value="'.$row_bornes_types['id'].'"'.(in_array($row_bornes_types['id'], $bornes_ids_arr) ? " selected" : "").'>'.$row_bornes_types['title'].'</option>';
              }
            ?>
          </select>
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

<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		$('.start_date, .end_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.bornes_ids').select2({placeholder: 'Sélectionnez les bornes pour le code promo'});


		// Редактирование варианта доставки
		$('.edit-promo').on('submit', function(event){
			event.preventDefault();
			if ($('.promocode').val() == '') {
				showError('Saisir le code promotionnel !');
				return false;
			}
			if ($('.start_date').val() == '') {
				showError('Entrez la date de début du code promo !');
				return false;
			}
			if ($('.end_date').val() == '') {
				showError('Saisissez la date d\'expiration du code promo !');
				return false;
			}
			if ($('.sum').val() == '') {
				showError('Entrez le montant de la réduction !');
				return false;
			}

			var weekday = '';
	    $('.weekday').each(function(index, brand){
	     	if ($(this).is(':checked')) {
	      	weekday += $(this).val();
	      }
	    });

			var bornes_ids = '';
			$('.bornes_ids').each(function(index, brand){
				bornes_ids += $(this).val() + ';';
			});
			bornes_ids = bornes_ids.slice(0, -1);

			if (bornes_ids == '') {
				showError('Sélectionnez les bornes pour le code promo !');
				return false;
			}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_promo', id: <?php echo $promo_id; ?>, promocode: $('.promocode').val(), start_date: $('.start_date').val(), end_date: $('.end_date').val(), weekday: weekday, sum: $('.sum').val(), bornes_ids: bornes_ids},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Prêt!',
							text: 'Les informations ont été mises à jour avec succès !',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК'
						}).then(function() {
							window.location.href = 'promo_list.php';
						});
					} else {
						showError(responce);
					}
				}
			});
		});

		<?php if ($error == 1) { ?>
			swal({
				title: 'Ошибка!',
				text: 'Невозможно обработать запрос!',
				type: 'error',
				confirmButtonColor: '#348fe2',
				confirmButtonText: 'Закрыть',
				closeOnConfirm: true
			}).then(function() {
				window.location.href = 'promo_list.php';
			});
		<?php } ?>

	});
</script>

<?php include("end.php"); ?>