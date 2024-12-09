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
	$page_title = "Ajouter un livreur";
	$breadcrumbs = '<a href="deliveris_list.php" title="Livreur">Livreur</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter un livreur</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-deliveris">
			<div class="form-group">
				<label class="col-md-3 control-label required">Livreur</label>
				<div class="col-md-4">
					<input type="text" class="form-control title" placeholder="Livreur" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-3">
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

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets\plugins\DataTables\media\css\dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Select\css\select.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Responsive\css\responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\dropzone\css\basic.css" rel="stylesheet">
<link href="assets\plugins\dropzone\css\dropzone.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets\plugins\dropzone\js\dropzone.js"></script>
<script src="assets\plugins\ckeditor\ckeditor.js"></script>
<script src="assets\plugins\ckfinder\ckfinder.js"></script>

<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		// Добавление компетенции
		$('.add-deliveris').on('submit', function(event){
			event.preventDefault();

			if ($('.title').val() == '' || $('.title').val() == 0) {
				showError('Entrez le livreur !');
				return false;
			}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_deliveris',  title: $('.title').val()},
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
							window.location.href = 'deliveris_list.php';
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