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
	$page_title = "Catégorie";
	include("header.php");
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Catégorie</h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered nowrap categories-table" width="100%">
			<thead>
				<tr>
					<th style="width: 50px;">ID</th>
					<th class="no-sort" style="width: 120px;">Image</th>
					<th>Catégorie</th>
					<th class="no-sort" style="width: 75px;"><?php echo ACTIONS ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_categories = mysqli_query($conn, "SELECT * FROM `categories`");
				$i=1;
				while($row_categories = mysqli_fetch_assoc($result_categories)) {
					echo'<tr id="tr'.$row_categories['id'].'" class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
						<td>'.$row_categories['id'].'</td>
						<td>';
							if ($row_categories['image'] != "") {
								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_categories['image'].'" title="'.$row_categories['title'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_categories['image'], '120').'" alt="" /></a>';
							}
						echo'</td>
						<td>'.$row_categories['title'].'</td>
						<td>
							<a href="edit_category.php?category_id='.$row_categories['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="'.EDIT.'"><i class="fa fa-edit"></i></a>
							<a class="btn btn-danger btn-icon btn-circle btn-sm project-type-delete" title="'.DELETE.'" onClick="deleteType('.$row_categories['id'].');"><i class="fa fa-times"></i></a>
						</td>
					</tr>';
					$i++;
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<!-- end panel -->

<?php include("footer.php"); ?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets\plugins\DataTables\media\css\dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Select\css\select.bootstrap.min.css" rel="stylesheet">

<link href="assets\plugins\DataTables\extensions\RowReorder\css\rowReorder.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Buttons\css\buttons.bootstrap.min.css" rel="stylesheet">

<link href="assets\plugins\DataTables\extensions\Responsive\css\responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/fancyBox/source/jquery.fancybox.css?v=2.1.6" rel="stylesheet" type="text/css" media="screen" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets\plugins\DataTables\media\js\jquery.dataTables.js"></script>
<script src="assets\plugins\DataTables\media\js\dataTables.bootstrap.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Select\js\dataTables.select.min.js"></script>
<script src="assets\plugins\DataTables\extensions\RowReorder\js\dataTables.rowReorder.min.js"></script>

<script src="assets\plugins\DataTables\extensions\Buttons\js\dataTables.buttons.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.bootstrap.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.flash.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\jszip.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\pdfmake.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\vfs_fonts.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.html5.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.print.min.js"></script>

<script src="assets\plugins\DataTables\extensions\Responsive\js\dataTables.responsive.min.js"></script>
<script src="assets/plugins/fancyBox/source/jquery.fancybox.pack.js?v=2.1.6"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
	$(document).ready(function() {

		App.init();

		var table = $('#data-table').DataTable({
			paging: true,
			responsive: true,
			columnDefs: [
				{
					targets: 'no-sort',
					orderable: false,
				}
			],
			dom: 'Bfrtip',
			buttons: [
				'csv', 'excel', 'pdf', 'print',
				{
					text: '<i class="fa fa-plus"></i> <?php echo ADD ?>',
					action: function (e, dt, node, config) {
						window.location.href = 'add_category.php';
					}
				}
			],
			oLanguage: {
				sSearch: '<?php echo SEARCH ?> ',
				sEmptyTable: '<?php echo NO_DATA_AVAILABLE_IN_TABLE ?>',
				sInfo: '<?php echo TABLE_SHOWED ?>',
				sInfoEmpty: '<?php echo TABLE_SHOWED_EMPTY ?>',
				sInfoFiltered: '<?php echo FILTERED_FROM ?>'
			}
		});

		$('.fancybox').fancybox({
			padding: 0,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});

	});

	function deleteType(id) {
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
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'delete_category', id: id},
					cache: false,
					success: function(responce) {
						if (responce == 'done') {
							$('#tr' + id).remove();
						} else {
							showError(responce);
						}
					}
				});
			} else {
				//
			}
		}, function(dismiss) {
			//
		});
	}
</script>

<?php include("end.php"); ?>