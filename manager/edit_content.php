<?php
	$page_title = "Modifier la page";
	$breadcrumbs = '<a href="content_list.php" title="Pages">Pages</a>';
	include("header.php");
	$error = 0;
	if (isset($_COOKIE['edit_content'])) {
		$result_contents = mysqli_query($conn, "SELECT * FROM `contents` WHERE `id` = ".$_COOKIE['edit_content']);
		if (mysqli_num_rows($result_contents) == 0) {
			$error = 1;
		} else {
			$row_contents = mysqli_fetch_assoc($result_contents);
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
		<h4 class="panel-title">Modifier la page</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-content">
			<div class="form-group">
				<label class="col-md-3 control-label required">Titre de la page</label>
				<div class="col-md-9">
					<input type="text" class="form-control title" value="<?php echo $row_contents['title'] ?>" placeholder="Titre de la page" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Contenu de la page</label>
				<div class="col-md-9">
					<textarea class="form-control" id="page_content" name="page_content"><?php echo $row_contents['content'] ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Lien vers la page (URL)</label>
				<div class="col-md-9">
					<input type="text" class="form-control url" value="<?php echo $row_contents['url'] ?>" placeholder="seulement des lettres latines, des chiffres et «-»" style= "width: 50%; display: inline-block;" />&nbsp;<b>.html</b>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">META Title</label>
				<div class="col-md-9">
					<input type="text" class="form-control meta_title" value="<?php echo $row_contents['meta_title'] ?>" placeholder="META Title" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">META Description</label>
				<div class="col-md-9">
					<textarea class="form-control meta_description" placeholder="META Description"><?php echo $row_contents['meta_description'] ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">META Keywords</label>
				<div class="col-md-9">
					<textarea class="form-control meta_keywords" placeholder="META Keywords"><?php echo $row_contents['meta_keywords'] ?></textarea>
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

		var editor = CKEDITOR.replace('page_content');
		CKFinder.setupCKEditor(editor, 'assets/plugins/ckfinder/');

		elasticArea('.meta_description, .meta_keywords');

		// Формирование URL
		$('.title').blur(function(){
			if ($('.url').val() == '') {
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'title2url', title: $(this).val()},
					cache: false,
					success: function(responce){
						$('.url').val(responce);
					}
				});
			}
		});

		$('.edit-content').on('submit', function(event){
			event.preventDefault();
			if ($('.title').val() == '') {
				showError('Entrez le titre de la page!');
				return false;
			}
			if ($('.url').val() == '') {
				showError('Entrez l\'URL de la page!');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_content', id: getCookie('edit_content'), title: $('.title').val(), content: CKEDITOR.instances.page_content.getData(), url: $('.url').val(), meta_title: $('.meta_title').val(), meta_description: $('.meta_description').val(), meta_keywords: $('.meta_keywords').val()},
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
							window.location.href = 'contents_list.php';
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
				window.location.href = 'contents_list.php';
			});
		<?php } ?>

	});
</script>
<?php include("end.php"); ?>