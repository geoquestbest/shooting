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
	$page_title = ADDING_ROUTE;
	$breadcrumbs = '<a href="templates_list.php" title="'.LIST_ROUTES.'">'.LIST_ROUTES.'</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title"><?php echo ADDING_ROUTE ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-template">
			<input type="hidden" class="image" value="" />
      <input type="hidden" class="second_image" value="" />
			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo TITLE ?></label>
				<div class="col-md-9">
					<input type="text" class="form-control title" placeholder="<?php echo TITLE ?>" />
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label required"><?php echo QUESTS_TYPES ?></label>
        <div class="col-md-6">
          <select class="form-control types_ids" multiple="multiple">
            <?php
              $result_types = mysqli_query($conn, "SELECT * FROM `types` ORDER BY `title`");
              while($row_types = mysqli_fetch_assoc($result_types)) {
                echo'<option value="'.$row_types['id'].'">'.$row_types['title'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Bornes</label>
        <div class="col-md-6">
          <select class="form-control boxes" multiple="multiple">
            <?php
              $boxes_arr = explode(",", $row_templates['boxes']);
              echo'<option value="Ring">Ring</option>';
              echo'<option value="Vegas">Vegas</option>';
              echo'<option value="Miroir">Miroir</option>';
              echo'<option value="Spinner_360">Spinner_360</option>';
              echo'<option value="Réalité_virtuelle">Réalité_virtuelle</option>';
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nombre de photos</label>
        <div class="col-md-1">
          <select class="form-control photos_amount">
            <option value="1" selected>1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Formats</label>
        <div class="col-md-2">
          <select class="form-control format_id">
            <option value="1">Paysage</option>
            <option value="2">Portrait</option>
            <option value="3">Strip</option>
          </select>
        </div>
      </div>
       <div class="form-group">
        <label class="col-md-3 control-label">Template</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Image de présentation</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm2">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
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

		$('.types_ids').select2({placeholder: '<?php echo QUESTS_TYPES ?>'});
    $('.boxes').select2({placeholder: 'Bornes'});


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
          $('.title').val(file.name);
					$('.image').val(responseText);
				});
			}
		}

    Dropzone.options.dropzoneForm2 = {
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
          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="<?php echo DELETE ?>"><i class="fa fa-close"></i></a>');

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
              data: {event: 'remove_image', image: $('.second_image').val()},
              cache: false,
              success: function(responce){
                $('.second_image').val('');
              }
            });
          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.second_image').val(responseText);
        });
      }
    }



		$('.add-template').on('submit', function(event){
			event.preventDefault();
			/*if ($('.title').val() == '') {
				showError('<?php echo TITLE_ERROR ?>');
				return false;
			}*/

			var types_ids = '';
			$('.types_ids').each(function(index, brand){
				types_ids += $(this).val() + ';';
			});
			types_ids = types_ids.slice(0, -1);

      if (types_ids == '') {
        showError('Sélectionnez des thèmes!');
        return false;
      }

      var boxes = '';
      $('.boxes').each(function(index, brand){
        boxes += $(this).val() + ';';
      });
      boxes = boxes.slice(0, -1);

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {
					event: 'add_template',
          title: $('.title').val(),
					types_ids: types_ids,
          photos_amount: $('.photos_amount').val(),
          format_id: $('.format_id').val(),
					title: $('.title').val(),
					image: $('.image').val(),
          second_image: $('.second_image').val(),
          boxes: boxes
				},
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
							window.location.href = 'templates_list.php';
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