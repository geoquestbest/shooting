<?php
	$page_title = "Modifier une catégorie";
	$breadcrumbs = '<a href="gallery_list.php" title="catégories">catégories</a>';
	include("header.php");
	$error = 0;
	if (isset($_COOKIE['edit_gallery'])) {
		$result_gallery = mysqli_query($conn, "SELECT * FROM `gallery` WHERE `id` = ".$_COOKIE['edit_gallery']);
		if (mysqli_num_rows($result_gallery) == 0) {
			$error = 1;
		} else {
			$row_gallery = mysqli_fetch_assoc($result_gallery);
			$result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `gallery_id` = ".$row_gallery['id']);
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
		<h4 class="panel-title">Modifier une catégorie</h4>
	</div>
	<div class="panel-body">
		<div class="form-horizontal">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-9" style="padding: 0 0 15px 10px;">
				<form class="dropzone " id="dropzoneForm" enctype="multipart/form-data" method="post">
					<div class="fallback">
						<input type="file" />
					</div>
				</form>
			</div>
		</div>
		<form class="form-horizontal edit-gallery">
			<input type="hidden" class="images" value="" />
			<div class="form-group">
				<label class="col-md-3 control-label required">Nom de la catégorie</label>
				<div class="col-md-9">
					<input type="text" class="form-control title" value="<?php echo $row_gallery['title']; ?>" placeholder="Nom de la catégorie" />
				</div>
			</div>
			<div class="form-group hidden">
				<label class="col-md-3 control-label">Lien vers la catégorie (URL)</label>
				<div class="col-md-9">
					<b>gallery-</b>&nbsp;<input type="text" class="form-control url" value="<?php echo $row_gallery['url']; ?>" placeholder="seulement des lettres latines, des chiffres et «-»" style= "width: 50%; display: inline-block;" />&nbsp;<b>.html</b>
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

				<?php
					if (mysqli_num_rows($result_gallery_images) > 0) {
				?>
						var existingFileCount = 0;
				<?php
						while($row_gallery_images = mysqli_fetch_assoc($result_gallery_images)) {
				?>
							var mockFile = {name: '<?php echo $row_gallery['title']; ?>', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_images['image']); ?>};
							this.options.addedfile.call(this, mockFile);
							this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_gallery_images['image'], '120') ?>');
							mockFile.previewElement.classList.add('dz-success');
							mockFile.previewElement.classList.add('dz-complete');

							var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="Supprimer"><i class="fa fa-times"></i></a>');

							var _this = this;

							removeButton.addEventListener('click', function(e) {

								e.preventDefault();

								swal({
									title: 'Êtes-vous sûr',
									text: "de vouloir supprimer cet élément?",
									type: 'warning',
									showCancelButton: true,
									confirmButtonColor: '#d33',
									confirmButtonText: 'Supprimer',
									cancelButtonColor: '#929ba1',
									cancelButtonText: 'Annuler'
								}).then(function() {

									_this.options.maxFiles = _this.options.maxFiles + 1;

									e.stopPropagation();

									$.ajax({
										type: 'POST',
										url: 'd26386b04e.php',
										data: {event: 'delete_gallery_image', image: '<?php echo $row_gallery_images['image'] ?>'},
										cache: false,
										success: function(responce) {
											_this.removeFile(mockFile);
										}
									});

								}, function(dismiss) {
									// dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
								});

							});

							mockFile.previewElement.appendChild(removeButton);
							existingFileCount++;
				<?php
						}
				?>
						this.options.maxFiles = this.options.maxFiles - existingFileCount;
				<?php
					}
				?>

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

		$('.edit-gallery').on('submit', function(event) {
			event.preventDefault();
			if ($('.title').val() == '') {
				showError('Entrez le nom de la catégorie!');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_gallery', id: getCookie('edit_gallery'), title: $('.title').val(), url: $('.url').val(), images: $('.images').val()},
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
							window.location.href = 'gallery_list.php';
						});
					} else {
						showError(responce);
					}
				}
			});
		});

		$('.delete-gallery-image').on('click', function(event) {
			var res = $(this);
			swal({
				title: 'Êtes-vous sûr',
				text: "de vouloir supprimer cet élément?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				confirmButtonText: 'Supprimer',
				cancelButtonColor: '#929ba1',
				cancelButtonText: 'Annuler'
			}).then(function() {
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'delete_gallery_image', id: getCookie('edit_gallery')},
					cache: false,
					success: function(responce) {
						res.parents('label').html('');
					}
				});
			}, function(dismiss) {
				// dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
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
				window.location.href = 'gallery_list.php';
			});
		<?php } ?>

	});
</script>

<?php include("end.php"); ?>