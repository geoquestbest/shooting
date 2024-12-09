<?php
	$page_title = "Articles";
	include("header.php");
	$boxes_arr = array('Ring', 'Vegas', 'Miroir', 'Spinner_360', 'Réalité_virtuelle');
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Articles</h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered nowrap widgets-table" width="100%">
			<thead>
				<tr>
					<th>Nom</th>
					<th style="width: 100px;">Article</th>
					<th style="width: auto;">Nom</th>
					<th style="width: auto;">Contenu</th>
					<th style="width: 100px;">Type</th>
					<th style="width: 100px;">Borne</th>
					<th style="width: 100px;">Tarif</th>
					<th class="no-sort">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_articles = mysqli_query($conn, "SELECT * FROM `articles` ORDER BY `id`");
				$i=1;
				while($row_articles = mysqli_fetch_assoc($result_articles)) {
					echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
						<td>'.$i.'</td>
						<td class="text-center">'.$row_articles['num'].'</td>
						<td>'.$row_articles['title'].'</td>
						<td class="text-left">'.nl2br(htmlspecialchars_decode($row_articles['content'], ENT_QUOTES)).'</td>
						<td class="text-center">'.($row_articles['type_id'] == 1 ? 'Une entreprise' : 'Un particulier').'</td>
						<td class="text-center">';
							$box_id_arr = explode(",", $row_articles['box_id']);
							if (in_array("0", $box_id_arr)) {echo"All<br/>";}
							if (in_array("1", $box_id_arr)) {echo"Ring<br/>";}
							if (in_array("2", $box_id_arr)) {echo"Vegas<br/>";}
							if (in_array("3", $box_id_arr)) {echo"Miroir<br/>";}
							if (in_array("4", $box_id_arr)) {echo"Spinner_360<br/>";}
							if (in_array("5", $box_id_arr)) {echo"Réalité_virtuellee<br/>";}
						echo'</td>
						<td class="text-center">'.$row_articles['price'].'€ '.($row_articles['type_id'] == 1 ? 'HT' : 'TTC').'</td>
						<td>
							<a href="edit_article.php" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier" onClick="setCookie(\'edit_article\', '.$row_articles['id'].');"><i class="fa fa-edit"></i></a>
							<a class="btn btn-danger btn-icon btn-circle btn-sm article-delete" title="Supprimer" data-id="'.$row_articles['id'].'"><i class="fa fa-times"></i></a>
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
						window.location.href = 'add_article.php';
					}
				}
			]
		});


		// Удаление страницы
		$('.article-delete').on('click', function(event) {
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
					data: {event: 'delete_article', id: id},
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