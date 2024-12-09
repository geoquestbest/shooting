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
	$page_title = "Ajouter une option";
	$breadcrumbs = '<a href="options_list.php" title="Options de bornes">Options de bornes</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter une option</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-option">
			<input type="hidden" class="image" value="" />
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
				<label class="col-md-3 control-label">Icon</label>
				<div class="col-md-1">
					<input type="text" class="form-control icon" value="<?php echo $row_options['icon'] ?>" placeholder="Icon" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required"><?php echo TITLE ?></label>
				<div class="col-md-4">
					<input type="text" class="form-control title" placeholder="<?php echo TITLE ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Description</label>
				<div class="col-md-6">
					<textarea class="form-control description" placeholder="Description" style="height: 150px;"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Description facture</label>
				<div class="col-md-6">
					<textarea class="form-control description_pdf" placeholder="Description facture" style="height: 50px;"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Particulier prix TTC, €</label>
				<div class="col-md-2">
					<input type="text" class="form-control numeric price" placeholder="Particulier prix TTC, €" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Entreprise prix HT, €</label>
				<div class="col-md-2">
					<input type="text" class="form-control numeric eprice" placeholder="Entreprise prix HT, €" />
				</div>
			</div>
			<div class="form-group">
        <label class="col-md-3 control-label">Options contradictoires</label>
        <div class="col-md-9">
          <select class="form-control conflicting_options_ids" multiple="multiple">
            <?php
              $result_options2 = mysqli_query($conn, "SELECT * FROM `options` ORDER BY `title`");
              while($row_options2 = mysqli_fetch_assoc($result_options2)) {
                echo'<option value="'.$row_options2['id'].'">'.$row_options2['title'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Contour photo</label>
        <div class="col-md-1">
          <label class="radio-inline">
            <input type="radio" class="template" name="template_designer" value="1" <?php echo ($row_options['template'] == 1 ? ' checked' : '') ?> />
            Template
          </label>
        </div>
        <div class="col-md-1">
          <label class="radio-inline">
            <input type="radio" class="designer" name="template_designer" value="1" <?php echo ($row_options['designer'] == 1 ? ' checked' : '') ?> />
            Graphiste
          </label>
        </div>
      </div>
			<div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-6">
          <label class="checkbox-inline">
            <input type="checkbox" class="is_personal" value="1" />
            Personnalisation
          </label>
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


<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/dropzone/css/basic.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/dropzone.css" rel="stylesheet">
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		$('.conflicting_options_ids').select2({placeholder: 'Options contradictoires'});

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
          //$('.title').val(file.name);
					$('.image').val(responseText);
				});
			}
		}

		// Добавление компетенции
		$('.add-option').on('submit', function(event){
			event.preventDefault();

			if ($('.title').val() == '' || $('.title').val() == 0) {
				showError('<?php echo TITLE_ERROR ?>');
				return false;
			}

			var conflicting_options_ids = '';
      $('.conflicting_options_ids').each(function(index, brand){
        conflicting_options_ids += $(this).val() + ';';
      });
      conflicting_options_ids = conflicting_options_ids.slice(0, -1);

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_option',  title: $('.title').val(), description: $('.description').val(), description_pdf: $('.description_pdf').val(), image: $('.image').val(), price: $('.price').val(), eprice: $('.eprice').val(), icon: $('.icon').val(), image: $('.image').val(), conflicting_options_ids: conflicting_options_ids, is_personal: $('.is_personal').is(':checked') ? 1 : 0, template: $('.itemplate').is(':checked') ? 1 : 0, designer: $('.designer').is(':checked') ? 1 : 0},
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
							window.location.href = 'options_list.php';
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