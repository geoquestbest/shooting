<?php
	$page_title = "Liste des commandes";
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
					<th>Utilisateur / Type</th>
					<th>Voir</th>
					<th>Dossier</th>
					<th>Avancé</th>
					<th>Prix, €</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE `user_id`= 6 ORDER BY `id` DESC");
				$i=1;
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					$result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = ".$row_orders['user_id']);
					$row_users = mysqli_fetch_assoc($result_users);
					echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
						<td>'.$row_orders['id'].'</td>
						<td>
							<b>'.$row_users['civility'].' '.trim($row_users['first_name']." ".$row_users['last_name']).'</b><br />
							E-mail: <a href="mailto:'.$row_users['email'].'" title="Envoyer un e-mail">'.$row_users['email'].'</a>
							'.(($row_users['phone'] != "") ? "<br />Téléphone: <b>".$row_users['phone']."</b>" : "");
							$address = trim(trim(htmlspecialchars_decode($row_users['address'], ENT_QUOTES).", ".$row_users['city'].", ".$row_users['zip'], ", "));
							if ($address != "") {
								echo"<br />Adresse: <b>".$address."</b>";
							}
							if ($row_orders['type'] != "") {echo"<p><br />Type: <b>".$row_orders['type']."</b></p>";}
							if ($row_orders['themplate'] == 0) {
								echo"<p style=\"color: #fff;\">Je n'ai pas trouvé le Template, je choisis un Template sur mesur</p>";
							}
							if ($row_orders['themplate'] != -1) {
								echo"<p style=\"color: #390;\">Ensemble commun de Template :</p>";
								$result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `id` = ".$row_orders['themplate']);
								$row_gallery_images = mysqli_fetch_assoc($result_gallery_images);
								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_images['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_gallery_images['image'], '120').'" alt="" /></a>';

							}
						echo'</td>
						<td>'.(($row_orders['order_type'] == 0) ? 'Entreprises' : 'Particuliers').'</td>
						<td>'.(($row_orders['event_date'] != 0) ? date("d.m.Y", $row_orders['event_date'])."<br />".$row_orders['start_event']." - ".$row_orders['end_event'] : '').'</td>
						<td>';
						if ($row_orders['social_network'] != 0) {echo"+ \"cadre réseau social\"<br />";}
						if ($row_orders['dressing_box'] != 0) {echo"+ \"habillage box\"";}
						echo'</td>
						<td>'.(($row_orders['price'] > 0) ? $row_orders['price'] : '').'</td>
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
				'copy', 'csv', 'excel', 'pdf', 'print'
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