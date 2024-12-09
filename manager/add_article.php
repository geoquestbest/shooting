<?php
	$page_title = "Ajouter un article";
	$breadcrumbs = '<a href="article_list.php" title="Articleы">Articleы</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter un article</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-article">
			<div class="form-group">
				<label class="col-md-3 control-label required">Numéro article</label>
				<div class="col-md-3">
					<input type="text" class="form-control num" placeholder="Numéro article" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Le nom de l article</label>
				<div class="col-md-9">
					<input type="text" class="form-control title" placeholder="Le nom de l article" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Description de l article</label>
				<div class="col-md-9">
					<textarea class="form-control article_content" placeholder="Description de l article"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Types</label>
				<div class="col-md-4">
					<select class="form-control type_id">
						<option value="0">Sélectionnez le type...</option>
						<option value="1">Une entreprise</option>
						<option value="2">Un particulier</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Bornes</label>
				<div class="col-md-4">
					<select class="form-control box_id" multiple="multiple">
						<option value="0">All</option>
						<option value="1">Ring</option>
						<option value="2">Vegas</option>
						<option value="3">Miroir</option>
						<option value="4">Spinner_360</option>
						<option value="5">Réalité_virtuellee</option>
					</select>
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label required">Tarif, €</label>
        <div class="col-md-2">
          <input type="text" class="form-control numeric price" placeholder="Tarif, €" />
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success">Ajouter</button>
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
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets\plugins\dropzone\js\dropzone.js"></script>
<script src="assets\plugins\ckeditor\ckeditor.js"></script>
<script src="assets\plugins\ckfinder\ckfinder.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		elasticArea('.article_content');

		$('.box_id').select2({placeholder: 'Bornes'});

		// Добавление страницы
		$('.add-article').on('submit', function(event){
			event.preventDefault();
			if ($('.num').val() == '') {
				showError('Entrez le numéro du article !');
				return false;
			}
			if ($('.title').val() == '') {
				showError('Entrez le nom du article !');
				return false;
			}
			if ($('.type_id').val() == 0) {
				showError('Sélectionnez le type !');
				return false;
			}

			var box_id = '';
      $('.box_id').each(function(index, brand){
        box_id += $(this).val() + ',';
      });
      box_id = box_id.slice(0, -1);

			if ($('.box_id').val() == '') {
				showError('Sélectionnez le type borne !');
				return false;
			}
      if ($('.price').val() == '') {
        showError('Entrez le tarif du article !');
        return false;
      }
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_article', num: $('.num').val(), title: $('.title').val(), content: $('.article_content').val(), type_id: $('.type_id').val(), box_id: box_id, price: $('.price').val()},
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'Article a été ajouté avec succès!',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'articles_list.php';
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