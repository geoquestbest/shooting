<?php
	$page_title = "Maintenance";
	include("header.php");

?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title"><?php echo $title ?></h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered nowrap widgets-table" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th style="width: 100px;">Agence</th>
					<th style="width: auto;">Borne</th>
          <th class="no-sort" style="width: 120px;">Date et l'heure</th>
					<th class="no-sort" style="width: 75px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_repair = mysqli_query($conn, "SELECT * FROM `repair` ORDER BY `id` DESC");
				$i=1;
				while($row_repair = mysqli_fetch_assoc($result_repair)) {
  					echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
  						<td>'.$row_repair['id'].'</td>
  						<td class="text-center">';
                switch($row_repair['agency_id']) {
                  case 1: echo "Paris"; break;
                  case 2: echo "Bordeaux"; break;
                  default: echo "-"; break;
                }
              echo'</td>
  						<td class="text-left">'.$row_repair['box_id'].'</td>
              <td class="text-center">'.date("d.m.Y H:i", $row_repair['created_at']).'</td>
  						<td>
  							<a href="edit_repair.php?repair_id='.$row_repair['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
  							<a class="btn btn-danger btn-icon btn-circle btn-sm repair-delete" title="Supprimer" data-id="'.$row_repair['id'].'"><i class="fa fa-times"></i></a>
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
<script src="assets\plugins\fancyBox\source\helpers\jquery.fancybox-media.js?v=2.1.6"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
	$(document).ready(function() {

		App.init();

		var table = $('#data-table').DataTable({
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
					text: '<i class="fa fa-plus"></i> Ajouter',
					action: function (e, dt, node, config) {
						window.location.href = 'add_repair.php';
					}
				}
			]
		});


		// Удаление страницы
		$('.repair-delete').on('click', function(event) {
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
					data: {event: 'delete_repair', id: id},
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
			});
		});

	});
</script>

<?php include("end.php"); ?>