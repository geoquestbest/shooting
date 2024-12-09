<?php
	$page_title = "SMS";
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">SMS</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-sms">
			<div class="form-group">
				<label class="col-md-3 control-label">SMS envoi identifiants contour photo :</label>
				<div class="col-md-9">
					<textarea class="form-control sms1" placeholder="SMS envoi identifiants contour photo"><?php echo htmlspecialchars_decode($row_settings['sms1'], ENT_QUOTES); ?></textarea>
          <small><span class="sms1_length"></span> caractères sur 160 saisis</small>
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
          <label class="checkbox-inline">
            <input type="checkbox" class="long1" value="1"<?php if ($row_settings['long1'] == "yes") {echo' checked="checked"';} ?> />
            SMS longs
          </label>
        </div>
      </div>
      <br /><br />
			<div class="form-group">
				<label class="col-md-3 control-label">SMS relance contour photo j-15 :</label>
				<div class="col-md-9">
					<textarea class="form-control sms2" placeholder="SMS relance contour photo j-15"><?php echo str_replace("textarea", "_textarea", htmlspecialchars_decode($row_settings['sms2'], ENT_QUOTES)); ?></textarea>
          <small><span class="sms2_length"></span> caractères sur 160 saisis</small>
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
          <label class="checkbox-inline">
            <input type="checkbox" class="long2" value="1"<?php if ($row_settings['long2'] == "yes") {echo' checked="checked"';} ?> />
            SMS longs
          </label>
        </div>
      </div>
      <br /><br />
			<div class="form-group">
				<label class="col-md-3 control-label">SMS veille de retrait boutique :</label>
				<div class="col-md-9">
					<textarea class="form-control sms3" placeholder="SMS veille de retrait boutique"><?php echo str_replace("textarea", "_textarea", htmlspecialchars_decode($row_settings['sms3'], ENT_QUOTES)); ?></textarea>
          <small><span class="sms3_length"></span> caractères sur 160 saisis</small>
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
          <label class="checkbox-inline">
            <input type="checkbox" class="long3" value="1"<?php if ($row_settings['long3'] == "yes") {echo' checked="checked"';} ?> />
            SMS longs
          </label>
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

    $('.sms1_length').html($('.sms1').val().length);
    $('.sms1').on('keyup', function() {
      $('.sms1_length').html($(this).val().length);
    });

    $('.sms2_length').html($('.sms2').val().length);
    $('.sms2').on('keyup', function() {
      $('.sms2_length').html($(this).val().length);
    });

    $('.sms3_length').html($('.sms3').val().length);
    $('.sms3').on('keyup', function() {
      $('.sms3_length').html($(this).val().length);
    });

		$('.edit-sms').on('submit', function(event) {
			event.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {
					event: 'edit_sms',
					sms1: $('.sms1').val(),
          long1: $('.long1').is(':checked') ? 'yes' : 'no',
					sms2: $('.sms2').val(),
          long2: $('.long2').is(':checked') ? 'yes' : 'no',
					sms3: $('.sms3').val(),
          long3: $('.long3').is(':checked') ? 'yes' : 'no'
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