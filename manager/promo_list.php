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
	$page_title = "Liste des codes promotionnels";
	include("header.php");
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Liste des codes promotionnels</h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered nowrap promo-table" width="100%">
			<thead>
				<tr>
					<th class="text-center" style="width: 75px;">ID</th>
					<th class="text-center">Code promo</th>
					<th class="text-center" style="width: 120px;">Début des actions</th>
					<th class="text-center" style="width: 120px;">Fin de l'action</th>
          <th class="text-center" style="width: 120px;">Montant de la remise, €</th>
					<th class="text-center" style="width: 75px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_promocode = mysqli_query($conn, "SELECT * FROM `promocode`");
				$i=1;
				while($row_promocode = mysqli_fetch_assoc($result_promocode)) {
          $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` IN(".$row_promocode['bornes_ids'].") ORDER BY `title`");
          $bornes = "(";
          while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
            $bornes .= $row_bornes_types['title'].", ";
          }
          $bornes = trim($bornes, ", ").")";
					echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
						<td class="text-center">'.$row_promocode['id'].'</td>
						<td><b>'.$row_promocode['promocode']."</b> ".$bornes.'</td>
            <td class="text-center">'.date("d.m.Y", $row_promocode['start_date']).'</td>
            <td class="text-center">'.date("d.m.Y", $row_promocode['end_date']).'</td>
						<td class="text-center">'.number_format($row_promocode['sum'], 2, '.', ' ').'</td>
						<td class="text-center">
							<a href="edit_promo.php?promo_id='.$row_promocode['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
							<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Supprimer" onClick="del(this, '.$row_promocode['id'].');"><i class="fa fa-times"></i></a>
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

		var table = $('#data-table').DataTable({
			rowReorder: {
				selector: 'td:nth-child(1)'
			},
			pageLength: 20,
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
            window.location.href = 'add_promo.php';
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

	});

	// Удаление варианта доставки
	function del(ele, id) {
		event.preventDefault();
		var tr = $(ele).parents('tr');
		swal({
			title: 'Voulez-vous vraiment',
			text: "supprimer ce code promotionnel ?",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui, supprimez !',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function() {
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'promo_delete', id: id},
				cache: false,
				success: function(responce){
					if (responce == 'done') {
						tr.remove();
					} else {
						showError(responce);
					}
				}
			});
		}, function(dismiss) {
			// dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
		});
	};
</script>

<?php include("end.php"); ?>