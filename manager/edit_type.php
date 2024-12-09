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
	$page_title = EDITING_TYPE;
	$breadcrumbs = '<a href="types_list.php" title="'.QUESTS_TYPES.'">'.QUESTS_TYPES.'</a>';
	$breadcrumbs = '<a href="types_list.php" title="Liste de types">Liste de types</a>';
	include("header.php");
	$error = 0;
	if (isset($_GET['type_id']) && $_SESSION['user']['role'] == 1) {
		$type_id = mysqli_real_escape_string($conn, $_GET['type_id']);
		$result_types = mysqli_query($conn, "SELECT * FROM `types` WHERE `id` = ".$type_id);
		if (mysqli_num_rows($result_types) == 0) {
			$error = 1;
		} else {
			$row_types = mysqli_fetch_assoc($result_types);
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
		<h4 class="panel-title"><?php EDITING_TYPE ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-type">
			<div class="form-group">
				<label class="col-md-3 control-label required"><?php echo TYPE ?></label>
				<div class="col-md-4">
					<input type="text" class="form-control title" value="<?php echo $row_types['title'] ?>" placeholder="<?php echo TYPE ?>" />
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

		$('.edit-type').on('submit', function(event){
			event.preventDefault();

			if ($('.title').val() == '' || $('.title').val() == 0) {
				showError('<?php echo TYPE_ERROR ?>');
				return false;
			}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_type', id: <?php echo $type_id ?>, title: $('.title').val()},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: '<?php echo DONE ?>',
							text: '<?php echo INFORMATION_SUCCESSFULLY_UPDATED ?>',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'OK',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'types_list.php';
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
				window.location.href = 'types_list.php';
			});
		<?php } ?>

	});
</script>

<?php include("end.php"); ?>