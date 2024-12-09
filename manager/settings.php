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
  switch($_GET['type']) {
    case 1: $page_title = "Paramètres iPad"; break;
    case 2: $page_title = "Paramètres Vegas iPad"; break;
    case 3: $page_title = "Paramètres Vegas Desktop"; break;
  }
  $breadcrumbs = '<a href="ipad_list.php?status=0" title="Liste des commandes">Liste des commandes</a>';
  include("header.php");
  $error = 0;
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title"><?php echo $page_title ?></h4>
  </div>
  <div class="panel-body">
    <form class="form-horizontal configure-order">
      <input type="hidden" class="video1" value="" />
      <input type="hidden" class="video2" value="" />
      <input type="hidden" class="video3" value="" />
      <input type="hidden" class="image" value="" />
      <div class="form-group">
        <label class="col-md-3 control-label">Vidéo  touchez l'écran</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm1">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group<?php if ($_GET['type'] == 1) echo" hide"; ?>">
        <label class="col-md-3 control-label">Vidéo préparez vous</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm2">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group<?php if ($_GET['type'] == 1) echo" hide"; ?>">
        <label class="col-md-3 control-label">Fin de session</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm3">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group<?php if ($_GET['type'] == 1 || $_GET['type'] > 2) echo" hide"; ?>">
        <label class="col-md-3 control-label">Template par defaut</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm4">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>

      <div class="form-group<?php if ($_GET['type'] != 1) echo" hide"; ?>">
        <label class="col-md-3 control-label">Image du fond</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm5">
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
<link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css" rel="stylesheet">
<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet">
<link href="assets/plugins/powerange/powerange.min.css" rel="stylesheet">

<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>
<script src="assets/plugins/powerange/powerange.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

  $(document).ready(function() {

    App.init();


    Dropzone.options.dropzoneForm1 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 100,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les vidéos.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 100 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".$_GET['type']." AND `type_id` = 1");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
        ?>
              var mockFile = {name: '<?php echo $row_gallery_videos['video']; ?>', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
              this.options.addedfile.call(this, mockFile);
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
                    data: {event: 'delete_gallery_video', video: '<?php echo $row_gallery_videos['video'] ?>'},
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
              data: {event: 'remove_gallery_video', videos: $('.video1').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.video1').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.video1').val($('.video1').val() != '' ? $('.video1').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm2 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 100,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les vidéos.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 100 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".$_GET['type']." AND `type_id` = 2");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
        ?>
              var mockFile = {name: 'Video', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
              this.options.addedfile.call(this, mockFile);
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
                    data: {event: 'delete_gallery_video', video: '<?php echo $row_gallery_videos['video'] ?>'},
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
              data: {event: 'remove_gallery_video', videos: $('.video2').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.video2').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.video2').val($('.video2').val() != '' ? $('.video2').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm3 = {
      paramName: 'file_gallery',
      url: 'd26386b04e.php',
      method: 'post',
      maxFilesize: 100, // MB
      maxFiles: 100,
      acceptedFiles: '.mp4',
      dictDefaultMessage: '<b>Faites glisser le fichier ici ou cliquez pour charger les vidéos.</b><br />(jusqu\'à 100 fichiers, la taille maximale du fichier est de 100 Mb)',
      dictMaxFilesExceeded: 'Le nombre de fichiers dépassé!',
      uploadMultiple: true,
      parallelUploads: 1,
      init: function() {

        <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".$_GET['type']." AND `type_id` = 3");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
        ?>
            var existingFileCount = 0;
        <?php
            while($row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos)) {
        ?>
              var mockFile = {name: 'Video', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
              this.options.addedfile.call(this, mockFile);
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
                    data: {event: 'delete_gallery_video', video: '<?php echo $row_gallery_videos['video'] ?>'},
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
              data: {event: 'remove_gallery_video', videos: $('.video3').val(), index: index},
              cache: false,
              success: function(responce) {
                $('.video3').val(responce);
              }
            });

          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
        this.on('success', function(file, responseText) {
          $('.video3').val($('.video3').val() != '' ? $('.video3').val() + ';' + responseText : responseText);
        });
      }
    }

    Dropzone.options.dropzoneForm4 = {
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

         <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".$_GET['type']." AND `type_id` = 4");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
            $row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
         ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video'] ?>');
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
                  data: {event: 'remove_gallery_video', videos: $('.image').val(), index: 0},
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
          $('.image').val(responseText);
        });
      }
    }

     Dropzone.options.dropzoneForm5 = {
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

         <?php
          $result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `order_id` = 0 AND `box_type` = ".$_GET['type']." AND `type_id` = 4");
          if (mysqli_num_rows($result_gallery_videos) > 0) {
            $row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
         ?>

          var mockFile = {name: 'Image', size: <?php echo filesize(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']); ?>};
          this.options.addedfile.call(this, mockFile);
          this.options.thumbnail.call(this, mockFile, '<?php echo ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video'] ?>');
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
                  data: {event: 'remove_gallery_video', videos: $('.image').val(), index: 0},
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
          $('.image').val(responseText);
        });
      }
    }





    $('.configure-order').on('submit', function(event){
      event.preventDefault();


      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {
          event: 'configure_box',
          box_type: <?php echo $_GET['type']; ?>,
          video1: $('.video1').val(),
          video2: $('.video2').val(),
          video3: $('.video3').val(),
          image: $('.image').val(),
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
              <?php
              if (isset($_GET['vegas'])) {
                echo"window.location.href = 'ipad_vegas_list.php?status=0';";
              } else {
                echo"window.location.href = 'ipad_list.php?status=0';";
              }
              ?>
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
        //window.location.href = 'ipad_list.php?status=0';
      });
    <?php } ?>

  });
</script>

<?php include("end.php"); ?>