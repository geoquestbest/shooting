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
  $page_title = EDITING_ROUTE;
  $breadcrumbs = '<a href="templates_list.php" title="'.LIST_ROUTES.'">'.LIST_ROUTES.'</a>';
  include("header.php");
  $error = 0;
  if (isset($_GET['template_id'])) {
    $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$_GET['template_id'].$rq);
    if (mysqli_num_rows($result_templates) == 0) {
      $error = 1;
    } else {
      $row_templates = mysqli_fetch_assoc($result_templates);
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
    <h4 class="panel-title"><?php echo EDITING_ROUTE ?></h4>
  </div>
  <div class="panel-body">
    <form class="form-horizontal edit-template">
      <input type="hidden" class="image" value="" />
      <input type="hidden" class="second_image" value="" />
      <div class="form-group">
        <label class="col-md-3 control-label"><?php echo TITLE ?></label>
        <div class="col-md-9">
          <input type="text" class="form-control title" value="<?php echo $row_templates['title'] ?>" placeholder="<?php echo TITLE ?>" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required"><?php echo QUESTS_TYPES ?></label>
        <div class="col-md-6">
          <select class="form-control types_ids" multiple="multiple">
            <?php
              $types_ids_arr = explode(",", $row_templates['types_ids']);
              $result_types = mysqli_query($conn, "SELECT * FROM `types` ORDER BY `title`");
              while($row_types = mysqli_fetch_assoc($result_types)) {
                echo"<option value=\"".$row_types['id']."\""; if (in_array($row_types['id'], $types_ids_arr)) {echo" selected";} echo">".$row_types['title']."</option>";
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
              echo'<option value="Ring"'; if (in_array('Ring', $boxes_arr)) {echo" selected";} echo'>Ring</option>';
              echo'<option value="Vegas"'; if (in_array('Vegas', $boxes_arr)) {echo" selected";} echo'>Vegas</option>';
              echo'<option value="Miroir"'; if (in_array('Miroir', $boxes_arr)) {echo" selected";} echo'>Miroir</option>';
              echo'<option value="Spinner_360"'; if (in_array('Spinner_360', $boxes_arr)) {echo" selected";} echo'>Spinner_360</option>';
              echo'<option value="Réalité_virtuelle"'; if (in_array('Réalité_virtuelle', $boxes_arr)) {echo" selected";} echo'>Réalité_virtuelle</option>';
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Nombre de photos</label>
        <div class="col-md-1">
          <select class="form-control photos_amount">
            <option value="1"<?php if ($row_templates['photos_amount'] == 1) {echo" selected";} ?>>1</option>
            <option value="2"<?php if ($row_templates['photos_amount'] == 2) {echo" selected";} ?>>2</option>
            <option value="3"<?php if ($row_templates['photos_amount'] == 3) {echo" selected";} ?>>3</option>
            <option value="4"<?php if ($row_templates['photos_amount'] == 4) {echo" selected";} ?>>4</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Formats</label>
        <div class="col-md-2">
          <select class="form-control format_id">
            <option value="1"<?php if ($row_templates['format_id'] == 1) {echo" selected";} ?>>Paysage</option>
            <option value="2"<?php if ($row_templates['format_id'] == 2) {echo" selected";} ?>>Portrait</option>
            <option value="3"<?php if ($row_templates['format_id'] == 3) {echo" selected";} ?>>Strip</option>
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
        <label class="col-md-3 control-label">Image de présentatione</label>
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

        <?php if ($row_templates['image'] != "" && file_exists(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image']) && file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120'))) { ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120')); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120') ?>');
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
                  data: {event: 'delete_image_template', id: <?php echo $_GET['template_id']; ?>},
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

         <?php if ($row_templates['second_image'] != "" && file_exists(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['second_image']) && file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['second_image'], '120'))) { ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['second_image'], '120')); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['second_image'], '120') ?>');
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
                  data: {event: 'delete_second_image_template', id: <?php echo $_GET['template_id']; ?>},
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



    $('.edit-template').on('submit', function(event){
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
          event: 'edit_template',
          id: <?php echo $_GET['template_id']; ?>,
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

    <?php if ($error == 1) { ?>
      swal({
        title: '<?php echo ERROR ?>',
        text: '<?php echo CAN_NOT_PROCESS_REQUEST ?>',
        type: 'error',
        confirmButtonColor: '#348fe2',
        confirmButtonText: '<?php echo CLOSE ?>',
        closeOnConfirm: true
      }).then(function() {
        window.location.href = 'templates_list.php';
      });
    <?php } ?>

  });
</script>

<?php include("end.php"); ?>