<?php
	$page_title = "Administrateurs";
	include("header.php");
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Liste des Administrateurs</h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered users-table nowrap" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Administrateur</th>
					<th>Accède</th>
					<th>Commercial</th>
					<th class="no-sort">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_users = mysqli_query($conn, "SELECT * FROM `users` ORDER BY `id` DESC");
				$i=1;
				while($row_users = mysqli_fetch_assoc($result_users)) {
					echo'<tr id="tr'.$row_users['id'].'" class="gradeX'; if ($row_users['role'] == 1) {echo' admin';} if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
						<td>'.$row_users['id'].'</td>
						<td>
							<a href="edit_user.php?user_id='.$row_users['id'].'" title="'.trim($row_users['first_name']." ".$row_users['last_name']).'">'.trim($row_users['first_name']." ".$row_users['last_name']).'</a><br />
							E-mail: <a href="mailto:'.$row_users['email'].'" title="Envoyer un e-mail">'.$row_users['email'].'</a>';
						echo'</td>
						<td>'.$row_users['accesses'].'</td>
						<td>'.($row_users['is_commercial'] == 1 ? 'Oui' : 'Non').'</td>
						<td>
							<a href="edit_user.php?user_id='.$row_users['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
							&nbsp;<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Supprimer" onClick="deleteUser('.$row_users['id'].');"><i class="fa fa-close"></i></a>
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
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {

		App.init();
		$('#data-table').DataTable({
			paging: false,
			responsive: true,
			order: [[ 0, 'desc' ]],
			columnDefs: [{
					targets: 'no-sort',
					orderable: false,
				}
			],
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print',
				{
					text: '<i class="fa fa-plus"></i> Ajouter un Administrateur',
					action: function (e, dt, node, config) {
						window.location.href = 'add_user.php';
					}
				}
			]
		});

	});


	// Удаление пользователя
	function deleteUser(id) {
		swal({
			title: 'Êtes-vous sûr,',
			text: 'de vouloir supprimer cet Administrateur?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui, effacez!',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function(data) {
			if (data.value) {
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'delete_user', id: id},
					cache: false,
					success: function(responce){
						$('#tr' + id).remove();
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