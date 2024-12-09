<?php
	$page_title = "Ajouter une catégorie";
	$breadcrumbs = '<a href="gallery_list.php" title="catégories">catégories</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter une catégorie</h4>
	</div>
	<div class="panel-body">
		<label class="col-md-3 control-label"></label>
		<div class="col-md-9" style="padding: 0 0 15px 10px;">
			<form class="dropzone" id="dropzoneForm" enctype="multipart/form-data" method="post">
				<div class="fallback">
					<input type="file" />
				</div>
			</form>
		</div>
		<form class="form-horizontal add-gallery">
			<input type="hidden" class="images" value="" />
			<div class="form-group">
				<label class="col-md-3 control-label required">Nom de la catégorie</label>
				<div class="col-md-9">
					<input type="text" class="form-control title" placeholder="Nom de la catégorie" />
				</div>
			</div>
			<div class="form-group hidden">
				<label class="col-md-3 control-label">Lien vers la catégorie (URL)</label>
				<div class="col-md-9">
					<b>gallery-</b>&nbsp;<input type="text" class="form-control url" placeholder="seulement des lettres latines, des chiffres et «-»" style= "width: 50%; display: inline-block;" />&nbsp;<b>.html</b>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success">Télécharger</button>
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
<script src="assets\plugins\jquery.maskMoney\js\jquery.maskMoney.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		Dropzone.options.dropzoneForm = {
			paramName: 'file_gallery',
			url: 'd26386b04e.php',
			method: 'post',
			maxFilesize: 5, // MB
			maxFiles: 100,
			acceptedFiles: 'image/jpeg,image/png',
			dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les images.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 5 Mb)',
			dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
			uploadMultiple: true,
			parallelUploads: 1,
			init: function() {
				this.on('addedfile', function(file) {
					// Create the remove button
					var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg delete-gallery-image" title="Supprimer"><i class="fa fa-times"></i></a>');

					// Capture the Dropzone instance as closure.
					var _this = this;

					// Listen to the click event
					removeButton.addEventListener('click', function(e) {
						// Make sure the button click doesn't submit the form:
						e.preventDefault();

						var index = $(this).index('.delete-gallery-image');

						e.stopPropagation();

						// Remove the file preview.
						_this.removeFile(file);

						// If you want to the delete the file on the server as well,
						// you can do the AJAX request here.

						$.ajax({
							type: 'POST',
							url: 'd26386b04e.php',
							data: {event: 'remove_gallery_image', images: $('.images').val(), index: index},
							cache: false,
							success: function(responce) {
								$('.images').val(responce);
							}
						});

					});

					// Add the button to the file preview element.
					file.previewElement.appendChild(removeButton);
				});
				this.on('success', function(file, responseText) {
					$('.images').val($('.images').val() != '' ? $('.images').val() + ';' + responseText : responseText);
				});
			}
		}


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

		$('.add-gallery').on('submit', function(event){
			event.preventDefault();
			if ($('.title').val() == '') {
				showError('Entrez le nom de la catégorie!');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_gallery',  title: $('.title').val(), url: $('.url').val(), images: $('.images').val()},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'La catégorie a été ajoutée avec succès!',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'gallery_list.php';
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