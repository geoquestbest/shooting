<?php
	switch($_GET['status']) {
		/*case 0: $page_title = "Liste des demandes"; break;
		case 1: $page_title = "Liste des attentes"; break;
		case 2: $page_title = "Liste des réservations"; break;*/
		default: $page_title = "Liste des commandes"; break;
	}
	include("header.php");
	if (isset($_GET['status'])) {
		$rq = "WHERE `status` = ".mysqli_real_escape_string($conn, $_GET['status']);
		$status = mysqli_real_escape_string($conn, $_GET['status']);
	} else {
		$rq = "";
	}
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
					<th>Facture</th>
					<th>Identifant</th>
					<th>Date de l’évènement</th>
					<th class="no-sort" style="width: 120px;">Template</th>
					<th class="no-sort" style="width: 75px;">Configuration</th>
					<th class="no-sort" style="width: 160px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `box_type` LIKE 'vegas' AND (`image` != ''/*OR `select_type` LIKE 'Une entreprise'*/) ORDER BY `id` DESC");
				$i=1;
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					$result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC");
					if (($status == 0 && (mysqli_num_rows($result_configure_orders) == 0 || $row_orders['data'] == '' || $row_orders['image'] == '')) || ($status == 1 && mysqli_num_rows($result_configure_orders) > 0 && $row_orders['image'] != '' && $row_orders['data'] != '')) {
						if (strtotime($row_orders['event_date']) >= strtotime(date("d.m.Y", time())."00:00:00")) {

							$add = true;
			        $template = ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'];
			        list($width, $height) = getimagesize($template);
			        if ($width < $height) {
			          //$add = false;
			        }
			        if ($add) {
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
									<td class="text-center">';
	                  echo'<a href="to_pdf.php?order_id='.$row_orders['id'].'" title="Facture" target="_blank">'.$row_orders['num_id'].'</a>';
	               echo'</td>
									<td>'.$row_orders['password'].'</td>
									<td>'.$row_orders['event_date'].'</td>
									<td>';
										if ($row_orders['image'] != "") {
											echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120').'" alt="" /></a>';
										}
									echo'</td>
									<td class="text-center">
			              <a href="configure.php?order_id='.$row_orders['id'].'&vegas=true&desktop=true&back_url='.$_SERVER['REQUEST_URI'].'" class="btn btn-'.((mysqli_num_rows($result_configure_orders) == 0) ? 'primary' : 'warning').' btn-icon btn-circle btn-lg" title="Configuration 1"><i class="fa fa-edit"></i></a>&nbsp;
			              <!--a href="editor/?image=blank.png&sample_image=blank.png&json=&order_id=-'.$row_orders['id'].'&template_id=0" class="btn btn-'.(($row_orders['image'] == "") ? 'success' : 'danger').' btn-icon btn-circle btn-lg" title="Configuration 2"><i class="fa fa-edit"></i></a-->
			              <a href="vegas/?image='.$row_orders['image'].'&order_id='.$row_orders['id'].'&vegas=true&data='.$row_orders['data'].'" class="btn btn-'.(($row_orders['data'] == "") ? 'success' : 'danger').' btn-icon btn-circle btn-lg" title="Configuration 2"><i class="fa fa-edit"></i></a>
			             </td>
									<td>';
										if ($row_orders['image'] != "" && $row_orders['template_status'] == 1) {
											echo'<a href="#" class="btn btn-success btn-icon btn-circle btn-sm eraser" data-id="'.$row_orders['id'].'" title="Supprimer le template"><i class="fa fa-eraser"></i></a>&nbsp;';
										}
										echo'<a href="edit_order.php?order_id='.$row_orders['id'].'&status='.mysqli_real_escape_string($conn, $_GET['status']).'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
										&nbsp;<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Supprimer" onClick="deleteOrder('.$row_orders['id'].');"><i class="fa fa-close"></i></a>
									</td>
								</tr>';
								$i++;
							}
						}
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
			paging: false,
			responsive: false,
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
					text: '<i class="fa fa-plus"></i> Ajouter',
					action: function (e, dt, node, config) {
						window.location.href = 'add_order.php';
					}
				}
			]
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

	$('.send-mail').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id')

		$.ajax({
			type: 'POST',
			url: 'd26386b04e.php',
			data: {event: 'send_mail', id: order_id},
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

	$('.eraser').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id')

		$.ajax({
			type: 'POST',
			url: 'd26386b04e.php',
			data: {event: 'eraser', id: order_id},
			cache: false,
			success: function(responce) {
				if (responce == 'done'){
					window.location.reload();
				} else {
					showError(responce);
				}
			}
		});
	});


	// Удаление пользователя
	function deleteOrder(id) {
		swal({
			title: 'Êtes-vous sûr,',
			text: 'de vouloir supprimer cet commande?',
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
					data: {event: 'delete_order', id: id},
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