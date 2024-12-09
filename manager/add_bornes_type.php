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
	$page_title = "Ajouter les type";
	$breadcrumbs = '<a href="bornes_options_list.php" title="Types de bornes">Types de bornes</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter les type</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-bornes-type">
			<input type="hidden" class="image2" value="" />
			<div class="form-group">
        <label class="col-md-3 control-label">Image générale</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Image description</label>
        <div class="col-md-9" style="padding: 0 15px 15px 10px;">
          <div class="dropzone" id="dropzoneForm2">
            <div class="fallback">
              <input type="file" />
            </div>
          </div>
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label required"><?php echo TITLE ?></label>
				<div class="col-md-4">
					<input type="text" class="form-control title" placeholder="<?php echo TITLE ?>" />
				</div>
			</div>
      <div class="form-group description-row">
    				<label class="col-md-3 control-label">Description</label>
            <div class="col-md-3">
              <input class="form-control description-icon" type="file" name="icon[]" />
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control description" placeholder="Description" />
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <input type="number" class="form-control position" placeholder="Ordre" />
                <div class="input-group-btn">
                  <a href="javascript:void(0)" class="btn btn-primary" title="+" onClick="addDescription($(this))"><i class="fa fa-plus"></i></a>
                </div>
              </div>
            </div>
    			</div>
      <div class="form-group">
        <label class="col-md-3 control-label">Description PDF</label>
        <div class="col-md-6">
          <textarea class="form-control description_pdf" placeholder="Description PDF" style="height: 150px;"></textarea>
        </div>
      </div>
      <div class="form-group">
				<label class="col-md-3 control-label">Département</label>
				<div class="col-md-6">
					<input type="text" class="form-control department" placeholder="Département" />
				</div>
			</div>
			<div  style="background: #eee;">
        <div class="form-group">
          <label class="col-md-3 control-label">Nombre de jours</label>
          <label class="col-md-1 control-label text-center">1</label>
          <label class="col-md-1 control-label text-center">2</label>
          <label class="col-md-1 control-label text-center">3</label>
          <label class="col-md-1 control-label text-center">4 ou +</label>
        </div>
  			<div class="form-group">
  				<label class="col-md-3 control-label required">Particulier prix TTC, €</label>
  				<div class="col-md-1">
  					<input type="text" class="form-control numeric price" placeholder="Prix, €" />
  				</div>
          <div class="col-md-1">
            <input type="text" class="form-control numeric price2" placeholder="Prix, €" />
          </div>
          <div class="col-md-1">
            <input type="text" class="form-control numeric price3" placeholder="Prix, €" />
          </div>
          <div class="col-md-1">
            <input type="text" class="form-control numeric price4" placeholder="Prix, €" />
          </div>
  			</div>
  			<div class="form-group">
  				<label class="col-md-3 control-label required">Entreprise prix HT, €</label>
  				<div class="col-md-1">
  					<input type="text" class="form-control numeric eprice" placeholder="Prix, €" />
  				</div>
          <div class="col-md-1">
            <input type="text" class="form-control numeric eprice2" placeholder="Prix, €" />
          </div>
          <div class="col-md-1">
            <input type="text" class="form-control numeric eprice3" placeholder="Prix, €" />
          </div>
          <div class="col-md-1">
            <input type="text" class="form-control numeric eprice4" placeholder="Prix, €" />
          </div>
  			</div>
      </div>
			<div class="form-group">
        <label class="col-md-3 control-label">Options de bornes (Particulier)</label>
        <div class="col-md-6">
          <select class="form-control options_ids" multiple="multiple">
            <?php
              $result_options = mysqli_query($conn, "SELECT * FROM `options` ORDER BY `title`");
              while($row_options = mysqli_fetch_assoc($result_options)) {
                echo'<option value="'.$row_options['id'].'">'.$row_options['title'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Options de bornes (Entreprise)</label>
        <div class="col-md-6">
          <select class="form-control eoptions_ids" multiple="multiple">
            <?php
              $result_options = mysqli_query($conn, "SELECT * FROM `options` ORDER BY `title`");
              while($row_options = mysqli_fetch_assoc($result_options)) {
                echo'<option value="'.$row_options['id'].'">'.$row_options['title'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Livraison pour les bornes</label>
        <div class="col-md-6">
          <select class="form-control delivery_ids" multiple="multiple">
            <?php
              $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery` ORDER BY `title`");
              while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
                echo'<option value="'.$row_delivery['id'].'">'.$row_delivery['title'].'</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Couleur</label>
        <div class="col-md-2">
          <div class="input-group colorpicker-component color" data-color="#da398d" data-color-format="hex" />
            <input type="text" value="#da398d" class="form-control color-input" />
            <span class="input-group-addon"><i></i></span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Quantité</label>
        <div class="col-md-1">
          <input type="number" class="form-control numeric amount" placeholder="Quantité" />
        </div>
      </div>
            <div class="form-group">
        <label class="col-md-3 control-label">Largeur, cm</label>
        <div class="col-md-1">
          <input type="number" class="form-control numeric width" value="" placeholder="Largeur, cm" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Hauteur, cm</label>
        <div class="col-md-1">
          <input type="number" class="form-control numeric height" value="" placeholder="Hauteur, cm" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Profondeur, cm</label>
        <div class="col-md-1">
          <input type="number" class="form-control numeric depth" value="" placeholder="Profondeur, cm" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Poids, kg</label>
        <div class="col-md-1">
          <input type="number" class="form-control numeric weight" value="" placeholder="Poids, kg" />
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
<link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css" rel="stylesheet">
<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/dropzone/js/dropzone.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		$('.options_ids').select2({placeholder: 'Options de bornes'});
    $('.eoptions_ids').select2({placeholder: 'Options de bornes'});
		$('.delivery_ids').select2({placeholder: 'Livraison pour les bornes'});

		$('.color').colorpicker({format:"hex"});

		Dropzone.options.dropzoneForm = {
			paramName: 'image',
			url: 'd26386b04e.php',
			method: 'post',
			maxFilesize: 4, // MB
			maxFiles: 1,
			acceptedFiles: '.png,.jpg,.jpeg,.webp',
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

		Dropzone.options.dropzoneForm2 = {
			paramName: 'image',
			url: 'd26386b04e.php',
			method: 'post',
			maxFilesize: 4, // MB
			maxFiles: 1,
			acceptedFiles: '.png,.jpg,.jpeg,.webp',
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
							data: {event: 'remove_image', image: $('.image2').val()},
							cache: false,
							success: function(responce){
								$('.image2').val('');
							}
						});
					});

					// Add the button to the file preview element.
					file.previewElement.appendChild(removeButton);
				});
				this.on('success', function(file, responseText) {
          //$('.title').val(file.name);
					$('.image2').val(responseText);
				});
			}
		}

		$('.add-bornes-type').on('submit', function(event){
			event.preventDefault();

			if ($('.title').val() == '' || $('.title').val() == 0) {
				showError('<?php echo TITLE_ERROR ?>');
				return false;
			}

			var description = '';
      $('.description-icon').each(function(index){
        var fileReader = new FileReader();
        fileReader.onload = function () {
          var data = fileReader.result;  // data <-- in this var you have the file data in Base64 format
          console.log(data);
          description = description + data + '||' + $('.description').eq(index).val() + '||' + ($('.position').eq(index).val() != '' ? $('.position').eq(index).val() : 0) + '|||';
        };
        fileReader.readAsDataURL($(this).prop('files')[0]);
      });

      setTimeout(() => {
        description = description.slice(0, -3);
        console.log(description);

				var options_ids = '';
				$('.options_ids').each(function(index, brand){
					options_ids += $(this).val() + ';';
				});
				options_ids = options_ids.slice(0, -1);

	       var eoptions_ids = '';
	      $('.eoptions_ids').each(function(index, brand){
	        eoptions_ids += $(this).val() + ';';
	      });
	      eoptions_ids = eoptions_ids.slice(0, -1);

				var delivery_ids = '';
				$('.delivery_ids').each(function(index, brand){
					delivery_ids += $(this).val() + ';';
				});
				delivery_ids = delivery_ids.slice(0, -1);

				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'add_bornes_type', title: $('.title').val(), description: $('.description').val(), description_pdf: $('.description_pdf').val(), image: $('.image').val(), image2: $('.image2').val(), department: $('.department').val(), price: $('.price').val() + ',' +  $('.price2').val() + ',' +  $('.price3').val() + ',' +  $('.price4').val(), eprice: $('.eprice').val() + ',' +  $('.eprice2').val() + ',' +  $('.eprice3').val() + ',' +  $('.eprice4').val(), options_ids: options_ids, eoptions_ids: eoptions_ids, delivery_ids: delivery_ids, color: $('.color-input').val(), amount: $('.amount').val(), width: $('.width').val(), height: $('.height').val(), depth: $('.depth').val(), weight: $('.weight').val()},
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
								window.location.href = 'bornes_types_list.php';
							});
						} else {
							showError(responce);
						}
					}
				});
			}, 1000);
		});

	});

	function addDescription(ele) {
    var content = '<div class="form-group description-row">' +
      '<label class="col-md-3 control-label"></label>' +
      '<!--div class="col-md-1"></div-->' +
      '<div class="col-md-3">' +
        '<input class="form-control description-icon" type="file" name="icon[]" /> '+
      '</div>' +
      '<div class="col-md-4">' +
        '<input type="text" class="form-control description" placeholder="Description" />' +
      '</div>' +
      '<div class="col-md-2">' +
        '<div class="input-group">' +
          '<input type="number" class="form-control position" placeholder="Ordre" />' +
          '<div class="input-group-btn">' +
            '<a href="javascript:void(0)" class="btn btn-danger" title="-" onClick="$(this).parents(\'.description-row\').remove()"><i class="fa fa-minus"></i></a>' +
            '<a href="javascript:void(0)" class="btn btn-primary" title="+" onClick="addDescription($(this))"><i class="fa fa-plus"></i></a>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>';
    ele.parents('.description-row').after(content);
  }
</script>

<?php include("end.php"); ?>