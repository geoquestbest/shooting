<?php
	switch($_GET['status']) {
		/*case 0: $page_title = "Liste des demandes"; break;
		case 1: $page_title = "Liste des attentes"; break;
		case 2: $page_title = "Liste des réservations"; break;*/
		default: $page_title = "Liste des commandes de l'albums"; break;
	}
	include("header.php");
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title"><?php echo $page_title ?></h4>
	</div>
	<div class="panel-body">
		<table id="data-table" class="table table-striped table-bordered orders-table nowrap" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Utilisateur</th>
					<th>Borne</th>
					<th>Num ID</th>
					<th>Identifant</th>
					<th>Date de l’évènement</th>
					<th class="no-sort" style="width: 75px;">E-mail</th>
          <th class="no-sort" style="width: 75px;">Connexion</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `status` > 1 ORDER BY `id` DESC");
				$i=1;
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					if (floor((strtotime($row_orders['event_date']) - time())/(3600*24)) < 0) {
						$result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
						$delivery = mb_strpos($row_orders['selected_options'], 'Retrait boutique') ? 'Retrait boutique' : 'Livraison';
						echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
							<td>'.$row_orders['id'].'</td>
							<td class="text-left">
								<p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>
								E-mail : <a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a><br />
								Téléphone : <a href="tel:'.$row_orders['phone'].'" title="Envoyer un phone">'.$row_orders['phone'].'</a><br /><br />
								Lieu de l’évènement : <b>'.$row_orders['event_place'].'</b><br />
								Type d’événement : <b>' . $row_orders['event_type'] . '</b>
							</td>
							<td class="text-center">'.$row_orders['box_type'].'</td>
							<td class="text-center">'.$row_orders['num_id'].'</td>
							<td class="text-center">'.$row_orders['password'].'</td>
							<td class="text-center">'.$row_orders['event_date'].'</td>
							<td class="text-center">';
								echo'<a href="#" class="btn '.($row_orders['tripadvisor_mail'] == 0 ? 'btn-primary' : 'btn-success').' btn-icon btn-circle btn-sm tripadvisor-mail" data-id="'.$row_orders['id'].'" title="Mail Tripadvisor"><i class="fa fa-envelope-o"></i></a>
									&nbsp;<a href="#" class="btn '.($row_orders['album_mail'] == 0 ? 'btn-primary' : 'btn-success').' btn-icon btn-circle btn-sm album-mail" data-id="'.$row_orders['id'].'" title="Mail Albums"><i class="fa fa-book"></i></a>
							</td>
              <td class="text-center"><a  class="btn btn-danger btn-icon btn-circle btn-sm" href="../album/?login='.$row_orders['num_id'].'&password='.$row_orders['password'].'" target="_blank" title="Connexion"><i class="fa fa-key"></i></a></td>
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
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {

		App.init();
		$('#data-table').DataTable({
			scrollY:        475,
      scrollX:        false,
      scrollCollapse: true,
      fixedHeader:           {
        header: true,
        footer: true
      },
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
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});

	$('.tripadvisor-mail').on('click', function(event) {
		event.preventDefault();
		$(this).addClass('btn-success');
		var order_id = $(this).attr('data-id')

		$.ajax({
			type: 'POST',
			url: 'd26386b04e.php',
			data: {event: 'tripadvisor_mail', id: order_id},
			cache: false,
			success: function(responce) {
				if (responce == 'done'){
					swal({
						title: 'Fait!',
						text: 'Email a été envoyé avec succès.',
						type: 'success',
						confirmButtonColor: '#348fe2',
						confirmButtonText: 'Fermer',
						closeOnConfirm: true
					});
				} else {
					showError(responce);
				}
			}
		});
	});

	$('.album-mail').on('click', function(event) {
		event.preventDefault();
		$(this).addClass('btn-success');
		var order_id = $(this).attr('data-id')

		$.ajax({
			type: 'POST',
			url: 'd26386b04e.php',
			data: {event: 'album_mail', id: order_id},
			cache: false,
			success: function(responce) {
				if (responce == 'done'){
					swal({
						title: 'Fait!',
						text: 'Email a été envoyé avec succès.',
						type: 'success',
						confirmButtonColor: '#348fe2',
						confirmButtonText: 'Fermer',
						closeOnConfirm: true
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