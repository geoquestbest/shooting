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
	$page_title = "Modifier les Récuperation";
	$breadcrumbs = '<a href="recovery_list.php" title="Récuperation pour les bornes">Récuperation pour les bornes</a>';
	include("header.php");
	$error = 0;
	if (isset($_GET['recovery_id']) && $_SESSION['user']['role'] == 1) {
		$recovery_id = mysqli_real_escape_string($conn, $_GET['recovery_id']);
		$result_recovery = mysqli_query($conn, "SELECT * FROM `recovery` WHERE `id` = ".$recovery_id);
		if (mysqli_num_rows($result_recovery) == 0) {
			$error = 1;
		} else {
			$row_recovery = mysqli_fetch_assoc($result_recovery);
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
		<h4 class="panel-title">Modifier les Récuperation</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-recovery">
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
				<label class="col-md-3 control-label required"><?php echo TITLE ?></label>
				<div class="col-md-4">
					<input type="text" class="form-control title" value="<?php echo $row_recovery['title'] ?>" placeholder="<?php echo TITLE ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Description</label>
				<div class="col-md-6">
					<textarea class="form-control description" placeholder="Description" style="height: 150px;"><?php echo htmlspecialchars_decode($row_recovery['description'], ENT_QUOTES) ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Département</label>
				<div class="col-md-6">
					<input type="text" class="form-control department" value="<?php echo $row_recovery['department'] ?>" placeholder="Département" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Description facture</label>
				<div class="col-md-6">
					<textarea class="form-control description_pdf" placeholder="Description facture" style="height: 50px;"><?php echo htmlspecialchars_decode($row_recovery['description_pdf'], ENT_QUOTES) ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Particulier prix TTC, €</label>
				<div class="col-md-2">
					<input type="text" class="form-control numeric price" value="<?php echo $row_recovery['price'] ?>" placeholder="Particulier prix TTC, €" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Entreprise prix HT, €</label>
				<div class="col-md-2">
					<input type="text" class="form-control numeric eprice" value="<?php echo $row_recovery['eprice'] ?>" placeholder="Entreprise prix HT, €" />
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
<link href="assets/plugins/dropzone/css/basic.css" rel="stylesheet">
<link href="assets/plugins/dropzone/css/dropzone.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/js/apps.min.js"></script>
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

        <?php if ($row_recovery['image'] != "" && file_exists(ADMIN_UPLOAD_IMAGES_DIR.$row_recovery['image']) && file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_recovery['image'], '120'))) { ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_recovery['image'], '120')); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_recovery['image'], '120') ?>');
          mockFile.previewElement.classList.add('dz-success');
          mockFile.previewElement.classList.add('dz-complete');

          var existingFileCount = 1; // The number of files already uploaded
          this.options.maxFiles = this.options.maxFiles - existingFileCount;

          var removeButton = Dropzone.createElement('<a class="btn btn-danger btn-icon btn-circle btn-lg" title="<?php echo DELETE ?>"><i class="fa fa-times"></i></a>');

          var _this = this;

          removeButton.addEventListener('click', function(e) {

            e.preventDefault();

            swal({
              title: '<?php echo ARE_YOU_SURE ?>',
              text: '<?php echo WANT_DELETE ?>',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              confirmButtonText: '<?php echo DELETE ?>',
              cancelButtonColor: '#929ba1',
              cancelButtonText: '<?php echo CANCEL ?>'
            }).then(function(data) {
              if (data.value) {
                _this.options.maxFiles = _this.options.maxFiles + 1;

                e.stopPropagation();

                $.ajax({
                  type: 'POST',
                  url: 'd26386b04e.php',
                  data: {event: 'delete_image_recovery', id: <?php echo $_GET['recovery_id']; ?>},
                  cache: false,
                  success: function(responce) {
                    _this.removeFile(mockFile);
                  }
                });
              }
            }, function(dismiss) {
              // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            });
          });

          mockFile.previewElement.appendChild(removeButton);

        <?php } ?>

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

		$('.edit-recovery').on('submit', function(event){
			event.preventDefault();

			if ($('.title').val() == '' || $('.title').val() == 0) {
				showError('<?php echo TITLE_ERROR ?>');
				return false;
			}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_recovery', id: <?php echo $recovery_id ?>, title: $('.title').val(), description: $('.description').val(), description_pdf: $('.description_pdf').val(), image: $('.image').val(), department: $('.department').val(), price: $('.price').val(), eprice: $('.eprice').val()},
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
							window.location.href = 'recovery_list.php';
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
				window.location.href = 'recovery.php';
			});
		<?php } ?>

	});
</script>

<?php include("end.php"); ?>