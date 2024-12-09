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
	$page_title = "Ajouter les catégorie";
	$breadcrumbs = '<a href="categories_list.php" title="Catégorie">Catégorie</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter les catégorie</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-type">
			<input type="hidden" class="image" value="" />
			<div class="form-group">
				<label class="col-md-3 control-label required">Catégorie</label>
				<div class="col-md-4">
					<input type="text" class="form-control title" placeholder="Catégorie" />
				</div>
			</div>
			<div class="form-group">
        <label class="col-md-3 control-label">Image</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
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

		Dropzone.options.dropzoneForm = {
			paramName: 'image',
			url: 'd26386b04e.php',
			method: 'post',
			maxFilesize: 4, // MB
			maxFiles: 1,
			acceptedFiles: 'image/jpeg,image/png',
			dictDefaultMessage: '<b><?php echo DRAG_CLICK_LOAD_IMAGE ?></b><br />(1 <?php echo NUMBER_SIZES_FILES ?> 4Mb)',
			dictMaxFilesExceeded: '<?php echo NUMBER_FILES_EXCEEDED ?>',
			uploadMultiple: false,
			init: function() {
				this.on('addedfile', function(file) {
					// Create the remove button
					var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="<?php echo DELETE ?>"><i class="fa fa-times"></i></a>');

					// Capture the Dropzone instance as closure.
					var _this = this;

					// Listen to the click event
					removeButton.addEventListener('click', function(e) {
						// Make sure the button click doesn't submit the form:
						e.preventDefault();
						e.stopPropagation();

						// Remove the file preview.
						_this.removeFile(file);

						// If you want to the delete the file on the server as well,
						// you can do the AJAX request here.
						$.ajax({
							type: 'POST',
							url: 'd26386b04e.php',
							data: {event: 'remove_image', image: $('.image').val()},
							cache: false,
							success: function(responce){
								$('.image').val('');
							}
						});
					});

					// Add the button to the file preview element.
					file.previewElement.appendChild(removeButton);
				});
				this.on('success', function(file, responseText) {
					$('.image').val(responseText);
				});
			}
		}

		// Добавление компетенции
		$('.add-type').on('submit', function(event){
			event.preventDefault();

			if ($('.title').val() == '' || $('.title').val() == 0) {
				showError('Entrez le nom du catégorie !');
				return false;
			}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_category',  title: $('.title').val(), image: $('.image').val()},
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
							window.location.href = 'categories_list.php';
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