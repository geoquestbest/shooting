<?php
	$page_title = "Pages";
	include("header.php");
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Pages</h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered nowrap contents-table" width="100%">
			<thead>
				<tr>
					<th>Nom</th>
					<th>En-tête</th>
					<th class="no-sort">Référence</th>
					<th><i class="fa fa-chain"></i> Menu</th>
					<th class="no-sort">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_contents = mysqli_query($conn, "SELECT * FROM `contents` ORDER BY `id`");
				$i=1;
				while($row_contents = mysqli_fetch_assoc($result_contents)) {
					echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
						<td>'.$i.'</td>
						<td>'.$row_contents['title'].'</td>
						<td><a href="../'.$row_contents['url'].'.html" title="'.$row_contents['title'].'" target="_blank">'.$row_contents['url'].'.html</a></td>
						<td>';
							$result_menu = mysqli_query($conn, "SELECT * FROM menu WHERE `content_id` = ".$row_contents['id']);
							if (mysqli_num_rows($result_menu)) {
								$row_menu = mysqli_fetch_assoc($result_menu);
								echo $row_menu['title'];
							}
						echo'</td>
						<td><a href="edit_content.php" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier" onClick="setCookie(\'edit_content\', '.$row_contents['id'].');"><i class="fa fa-edit"></i></a>
							<a class="btn btn-danger btn-icon btn-circle btn-sm content-delete" title="Supprimer" data-id="'.$row_contents['id'].'"><i class="fa fa-times"></i></a>
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
			pageLength: <?php echo $onpage; ?>,
			responsive: true,
			columnDefs: [
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
						window.location.href = 'add_content.php';
					}
				}
			]
		});
		

		// Удаление страницы
		$('.content-delete').on('click', function(event) {
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
					data: {event: 'delete_content', id: id},
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