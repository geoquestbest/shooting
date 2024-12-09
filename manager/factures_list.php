<?php
  $status = $_GET['type'];
  switch($status) {
    case 0:
      $title = "Devis";
    break;
    case 2:
      $title = "Factures";
    break;
  }
	$page_title = $title;
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
					<th style="width: 120px;">Numéro commande</th>
					<th style="width: auto;">Articles</th>
          <th class="no-sort" style="width: 50px;"><?php echo $title ?></th>
					<th class="no-sort" style="width: 75px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_factures = mysqli_query($conn, "SELECT * FROM `factures` ORDER BY `id` DESC");
				$i=1;
				while($row_factures = mysqli_fetch_assoc($result_factures)) {
          $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$row_factures['order_id']);
          $row_orders  = mysqli_fetch_assoc($result_orders);
          if ($row_orders['status'] == $status) {
  					echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
  						<td>'.$row_factures['id'].'</td>
  						<td class="text-center">'.$row_factures['order_id'].'</td>
  						<td class="text-left">';
                $articles_ids_arr = explode(",", $row_factures['articles_ids']);
                foreach ($articles_ids_arr as $key => $value) {
                  $value_arr = explode(":", $value);
                  $result_articles = mysqli_query($conn, "SELECT * FROM `articles` WHERE `id` = ".$value_arr[0]);
                  $row_articles = mysqli_fetch_assoc($result_articles);
                  echo $row_articles['num'].' - '.$row_articles['title'].' / '.$value_arr[1].'€<br />';
                }
  						echo'</td>
              <td class="text-center">
                <a href="to_pdf.php?order_id='.$row_factures['order_id'].'" class="btn btn-primary btn-icon btn-circle btn-sm" title="Create '.$title.'" target="_blank"><i class="fa fa-list"></i></a>
              </td>
  						<td>
  							<a href="edit_facture.php?order_id='.$row_factures['order_id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
  							<a class="btn btn-danger btn-icon btn-circle btn-sm facture-delete" title="Supprimer" data-id="'.$row_factures['id'].'"><i class="fa fa-times"></i></a>
  						</td>
  					</tr>';
  					$i++;
          }
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
						window.location.href = 'add_facture.php';
					}
				}
			]
		});


		// Удаление страницы
		$('.facture-delete').on('click', function(event) {
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
					data: {event: 'delete_facture', id: id},
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