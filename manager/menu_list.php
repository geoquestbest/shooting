<?php
	$page_title = "Menu";
	include("header.php");
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Éléments de menu</h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered nowrap menu-table" width="100%">
			<thead>
				<tr>
					<th class="no-sort">Non</th>
					<th class="no-sort">ID</th>
					<th class="no-sort"><i class="fa fa-sort"></i></th>
					<th class="no-sort">Élément de menu parent</th>
					<th class="no-sort">Titre de l'élément de menu</th>
					<th class="no-sort">Référence</th>
					<th class="no-sort">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_menu = mysqli_query($conn, "SELECT * FROM `menu` ORDER BY `position`");
				$i=1;
				while($row_menu = mysqli_fetch_assoc($result_menu)) {
					echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
						<td>'.$i.'</td>
						<td>'.$row_menu['id'].'</td>
						<td><i class="fa fa-sort"></i></td>
						<td>';
							if ($row_menu['parent_id'] == 0) {
								echo "Non";
							} else {
								$result_parent_menu = mysqli_query($conn, "SELECT * FROM `menu` WHERE `id` = ".$row_menu['parent_id']);
								$row_parent_menu = mysqli_fetch_assoc($result_parent_menu);
								echo $row_parent_menu['title'];
							}
						echo'</td>
						<td>'.$row_menu['title'].'</td>
						<td>';
							if ($row_menu['content_id'] == 0) { echo $row_menu['url'];} else {
								$result_contents = mysqli_query($conn, "SELECT * FROM contents WHERE id = ".$row_menu['content_id']);
								$row_contents = mysqli_fetch_assoc($result_contents);
								echo $row_contents['url'].".html";
							}
						echo'</td>
						<td>
							<a href="edit_menu.php" class="btn btn-warning btn-icon btn-circle btn-sm menu-edit" title="Modifier" onClick="setCookie(\'edit_menu\', '.$row_menu['id'].');"><i class="fa fa-edit"></i></a>
							<a class="btn btn-danger btn-icon btn-circle btn-sm menu-delete" title="Supprimer" data-id="'.$row_menu['id'].'"><i class="fa fa-times"></i></a>
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
<link href="assets\plugins\fancyBox\source\jquery.fancybox.css?v=2.1.6" rel="stylesheet" type="text/css" media="screen" />
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
<script src="assets\plugins\fancyBox\source\jquery.fancybox.pack.js?v=2.1.6"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
	$(document).ready(function() {
		
		App.init();

		var table = $('#data-table').DataTable({
			rowReorder: {
				selector: 'td:nth-child(1)'
			},
			paging: false,
			responsive: true,
			columnDefs: [
				{
					targets: 0,
					visible: false,
				},
				{
					targets: 1,
					visible: false,
				},
				{
					targets: 2,
					className: 'reorder',

				},
				{
					targets: 'no-sort',
					orderable: false,
				}
			],
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print',
				{
					text: '<i class="fa fa-plus"></i> Télécharger',
					action: function (e, dt, node, config) {
						window.location.href = 'add_menu.php';
					}
				}
			]
		});
		
		//table.column('0:visible').order('asc').draw();

		table.on('row-reorder', function (e, diff, edit) {
			var new_positions = '';
			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = table.row(diff[i].node).data();
				new_positions += rowData[1] + ':' + (diff[i].newPosition + 1) + ';'
			}
			if (new_positions != '') {
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'menu_reorder', new_positions: new_positions},
					cache: false
				});
			}
		});


		// Удаление меню
		$('.menu-delete').on('click', function(event) {
			event.preventDefault();
			var id = $(this).attr('data-id'),
				tr = $(this).parents('tr');
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
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'delete_menu', id: id},
					cache: false,
					success: function(responce){
						if (responce == 'done') {
							tr.remove();
						} else {
							swal({
								title: 'Erreur!',
								text: responce,
								type: 'error',
								confirmButtonColor: '#348fe2',
								confirmButtonText: 'Fermer'
							});
						}
					}
				});
			}, function(dismiss) {
				// dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
			});
		});

	});
</script>

<?php include("end.php"); ?>