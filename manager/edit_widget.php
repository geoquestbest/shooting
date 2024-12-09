<?php
	$page_title = "Modifier un widget";
	$breadcrumbs = '<a href="widgets_list.php" title="Widgets">Widgets</a>';
	include("header.php");
	$error = 0;
	if (isset($_COOKIE['edit_widget'])) {
		$result_widgets = mysqli_query($conn, "SELECT * FROM `widgets` WHERE `id` = ".$_COOKIE['edit_widget']);
		if (mysqli_num_rows($result_widgets) == 0) {
			$error = 1;
		} else {
			$row_widgets = mysqli_fetch_assoc($result_widgets);
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
		<h4 class="panel-title">Modifier un widget</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-widget">
			<div class="form-group">
				<label class="col-md-3 control-label required">Widget</label>
				<div class="col-md-9">
					<input type="text" class="form-control title" value="<?php echo $row_widgets['title'] ?>" placeholder="Widget" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Contenu du widget</label>
				<div class="col-md-9">
					<textarea class="form-control widget_content" placeholder="Contenu du widget"><?php echo str_replace("textarea", "_textarea", htmlspecialchars_decode($row_widgets['content'], ENT_QUOTES)); ?></textarea>
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

		elasticArea('.widget_content');

		$('.edit-widget').on('submit', function(event){
			event.preventDefault();
			if ($('.title').val() == '') {
				showError('Entrez le nom du widget!');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_widget', id: getCookie('edit_widget'), title: $('.title').val(), content: $('.widget_content').val()},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'L\'information a été mise à jour avec succès!',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'widgets_list.php';
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
				confirmButtonText: 'Fermer',
				closeOnConfirm: true
			}).then(function() {
				window.location.href = 'widgets_list.php';
			});
		<?php } ?>

	});
</script>
<?php include("end.php"); ?>