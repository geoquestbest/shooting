<?php
	$page_title = "Stocks";
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Stocks</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-stocks">
			<div class="form-group">
				<label class="col-md-3 control-label required">Ring</label>
				<div class="col-md-2">
					<input type="text" class="form-control numeric ring" value="<?php echo $row_settings['ring']; ?>" placeholder="Ring" />
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Vegas</label>
        <div class="col-md-2">
          <input type="text" class="form-control numeric vegas" value="<?php echo $row_settings['vegas']; ?>" placeholder="Vegas" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Vegas Slim</label>
        <div class="col-md-2">
          <input type="text" class="form-control numeric vegas_slim" value="<?php echo $row_settings['vegas_slim']; ?>" placeholder="Vegas" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Miroir</label>
        <div class="col-md-2">
          <input type="text" class="form-control numeric miroir" value="<?php echo $row_settings['miroir']; ?>" placeholder="Miroir" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Spinner 360</label>
        <div class="col-md-2">
          <input type="text" class="form-control numeric spinner_360" value="<?php echo $row_settings['spinner']; ?>" placeholder="Spinner 360" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Réalité virtuellee</label>
        <div class="col-md-2">
          <input type="text" class="form-control numeric vr" value="<?php echo $row_settings['vr']; ?>" placeholder="Réalité virtuellee" />
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


<?php include("footer.php"); ?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets\plugins\DataTables\media\css\dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Select\css\select.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Responsive\css\responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\dropzone\css\basic.css" rel="stylesheet">
<link href="assets\plugins\dropzone\css\dropzone.css" rel="stylesheet">
<link href="assets\plugins\jquery-simplecolorpicker\jquery.simplecolorpicker.css" rel="stylesheet">
<link href="assets\plugins\jquery-simplecolorpicker\jquery.simplecolorpicker-fontawesome.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets\plugins\dropzone\js\dropzone.js"></script>
<script src="assets\plugins\jquery-simplecolorpicker\jquery.simplecolorpicker.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		$('.edit-stocks').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {
					event: 'edit_stocks',
					ring: $('.ring').val(),
					vegas: $('.vegas').val(),
          vegas_slim: $('.vegas_slim').val(),
					miroir: $('.miroir').val(),
					spinner: $('.spinner_360').val(),
					vr: $('.vr').val()
				},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'L\'information a été mise à jour avec succès!',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК'
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