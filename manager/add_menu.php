<?php
	@session_start();
	$page_title = "Ajouter un élément de menu";
	$breadcrumbs = '<a href="menu_list.php" title="Menu">Menu</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter un élément de menu</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-menu">
			<div class="form-group">
				<label class="col-md-3 control-label">Élément de menu parent</label>
				<div class="col-md-9">
					<select class="form-control parent_id">
						<option value="0">Non</option>
						<?php echo addMenuTree(0, 0) ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Titre de l'élément de menu</label>
				<div class="col-md-9">
					<input type="text" class="form-control title" placeholder="Titre de l'élément de menu" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Description, attribut «title»</label>
				<div class="col-md-9">
					<input type="text" class="form-control description" placeholder="Description, attribut «title»" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Accrochez à la page</label>
				<div class="col-md-9">
					<select class="form-control content_id">
						<option value="0">Non</option>
						<?php
							$result_contents = mysqli_query($conn, "SELECT * FROM `contents` ORDER BY `id`");
							while($row_contents = mysqli_fetch_assoc($result_contents)) {
								echo"<option value=\"".$row_contents['id']."\">".$row_contents['title']."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group for-url">
				<label class="col-md-3 control-label">ou un lien vers une page</label>
				<div class="col-md-9">
					<input type="text" class="form-control url" placeholder="Lien vers la page" />
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
	function addMenuTree($ParentID, $ParentLevel) {
		global $conn, $level, $db_admin_prefix;
		$level = $ParentLevel;
		$level++; 
		$result_menu = mysqli_query($conn, "SELECT * FROM `menu` WHERE `parent_id` = $ParentID ORDER BY `position`");
		$numrows_menu = mysqli_num_rows($result_menu);
		if ($numrows_menu > 0) {
			while($row_menu = mysqli_fetch_assoc($result_menu)) {
				echo"<option value=\"".$row_menu['id']."\""; if ($row_menu['parent_id'] == 0) {echo" style=\"color: #348fe2;\"";} echo">"; if ($row_menu['parent_id'] != 0) {echo str_repeat("&nbsp;", $level * 2);} echo $row_menu['title']."</option>";
				addMenuTree($row_menu['id'], $level);
				$level--;
			}
		}
	}
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
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {
		
		App.init();

		/* Отображение/скрытие ввода url при добавлении и редактировании меню */
		$('.content_id').on('change', function(){
			if ($(this).val() == 0) {$('.for-url').slideDown();} else {$('.for-url').slideUp();}
		});

		$('.add-menu').on('submit', function(event){
			event.preventDefault();
			if ($('.title').val() == '') {
				showError('Entrez le nom de l\'élément de menu!');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_menu', type_id: 1, parent_id: $('.parent_id').val(), title: $('.title').val(), description: $('.description').val(), content_id: $('.content_id').val(), url: $('.url').val()},
				cache: false,
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'L\'élément de menu a été ajouté avec succès!',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'menu_list.php';
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