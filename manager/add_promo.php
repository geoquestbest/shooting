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
	$page_title = "Ajout d'un code promotionnel";
	$breadcrumbs = '<a href="promo_list.php" title="Liste des codes promotionnels">Liste des codes promotionnels</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajout d'un code promotionnel</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-promo">
			<div class="form-group">
				<label class="col-md-3 control-label required">Code promo</label>
				<div class="col-md-4">
					<input type="text" class="form-control promocode" placeholder="Code promo" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Début des actions</label>
				<div class="col-md-4">
					<input type="text" class="form-control start_date" placeholder="Début des actions" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Fin de l'action</label>
				<div class="col-md-4">
					<input type="text" class="form-control end_date" placeholder="Fin de l'action" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Jours</label>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="1" checked />
            LUN
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="2" checked />
            MAR
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="3" checked />
            MER
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="4" checked />
            JEU
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="5" checked />
            VEN
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="6" checked />
            SAM
          </label>
				</div>
				<div class="col-md-1">
					<label class="checkbox-inline">
            <input type="checkbox" class="weekday" value="7" checked />
            DIM
          </label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Montant de la remise, €</label>
				<div class="col-md-4">
					<input type="number" class="form-control sum" placeholder="Montant de la remise, €" >
				</div>
			</div>
			<div class="form-group">
        <label class="col-md-3 control-label required">Bornes</label>
        <div class="col-md-6">
          <select class="form-control bornes_ids" multiple="multiple">
            <?php
              $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` ORDER BY `title`");
              while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
                echo'<option value="'.$row_bornes_types['id'].'">'.$row_bornes_types['title'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success"><?php echo ADD ?></button>
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

		// Добавление варианта доставки
		$('.add-promo').on('submit', function(event){
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
				data: {event: 'add_promo', promocode: $('.promocode').val(), start_date: $('.start_date').val(), end_date: $('.end_date').val(), weekday: weekday, sum: $('.sum').val(), bornes_ids: bornes_ids},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Prêt!',
							text: 'Le code promotionnel a été ajouté avec succès !',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'promo_list.php';
						});
					} else {
						showError(responce);
					}
				}
			});
		});

	});
</script>

<?php include("end.php"); ?>