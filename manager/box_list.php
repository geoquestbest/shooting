<?php
	$page_title = "Liste des bornes";
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
					<th>Numéro</th>
					<th>Disposition</th>
					<th>Borne</th>
					<th>Type</th>
					<th>Date et l’heure du retour</th>
          <th class="no-sort" style="width: 50px;">Facture</th>
				</tr>
			</thead>
			<tbody>
				<?php
				switch($_GET['type_id']) {
					case 0:
						$rq = "`box_type` LIKE '%ring%'" ;
						$boxes = $row_settings['ring'];
					break;
					case 1:
						$rq = "`box_type` LIKE '%vegas%'" ;
						$boxes = $row_settings['vegas'];
					break;
					case 2:
						$rq = "`box_type` LIKE '%miroir%'" ;
						$boxes = $row_settings['miroir'];
					break;
					case 3:
						$rq = "`box_type` LIKE '%spinner%'" ;
						$boxes = $row_settings['spinner'];
					break;
					case 4:
						$rq = "`box_type` LIKE '%vr%'" ;
						$boxes = $row_settings['vr'];
					break;
				}
				if (isset($_GET['current_date'])) {
			    $current_date = mysqli_real_escape_string($conn, $_GET['current_date']);
			  } else {
			    $current_date = date("d.m.Y H:ш", time());
			  }
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `take_date` != '' AND (`return_date` LIKE '".substr($current_date, 0, 10)."%' OR `return_date` LIKE '".str_replace(".", "/", substr($current_date, 0, 10))."%') AND `status` = 2 AND $rq ORDER BY `id` DESC");
				$i=1; $total = 0;
				$box_arr = array();
				$box_type = "";
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					if($row_orders['box_id'] != '') {
							$box_arr[] = $row_orders['box_id'];
						}
					if(strtotime($current_date) > strtotime(str_replace("/", ".", $row_orders['return_date']))) {
						$delivery = mb_strpos($row_orders['selected_options'], 'Retrait boutique') ? 'Retrait boutique' : 'Livraison';
						$total = $total + $row_orders['total'];
						$box_type = $row_orders['box_type'];
						echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
							<td>'.$row_orders['box_id'].'</td>
							<td class="text-left">';
              //if(strtotime($current_date) < strtotime(str_replace("/", ".", $row_orders['return_date']))) {
								echo'<p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>
								E-mail : <a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a><br />
								Téléphone : <a href="tel:'.$row_orders['phone'].'" title="Envoyer un e-mail">'.$row_orders['phone'].'</a><br /><br />
								Lieu de l’évènement : <b>'.$row_orders['event_place'].'</b><br />
								Type d’événement : <b>' . $row_orders['event_type'] . '</b><br />
								'. ( $row_orders['societe'] ? 'Nom de la société : <b>'. $row_orders['societe']. '</b><br />' : '' ) .'
								Informations complémentaires : <b>'.$row_orders['description'].'</b><br />
				  				Comment avez-vous connu ShootnBox : <b>'.$row_orders['about'].'</b>';
              /*} else {
                echo"Bureau";
              }*/
							echo'</td>
							<td class="text-center">'.$row_orders['box_type'].'</td>
							<td>'.$row_orders['select_type'].'</td>
							<td data-sort="'.strtotime($row_orders['return_date']).'">'.$row_orders['return_date'].'</td>
              <td class="text-center">';
                $result_factures = mysqli_query($conn, "SELECT * FROM `factures` WHERE `order_id` = ".$row_orders['id']);
                if (mysqli_num_rows($result_factures) == 0) {
                  echo'<a href="add_facture.php?order_id='.$row_orders['id'].'" class="btn btn-primary btn-icon btn-circle btn-sm" title="Créer une '.$title.'"><i class="fa fa-list"></i></a>';
                } else {
                  echo'<a href="edit_facture.php?order_id='.$row_orders['id'].'" class="btn btn-success btn-icon btn-circle btn-sm" title="Modifier une '.$title.'"><i class="fa fa-list"></i></a>';
                }
              echo'</td>
						</tr>';
						$i++;
					}
				}
				for($j = 1; $j < $boxes; $j++) {
					if(!in_array($j, $box_arr)) {
						echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
								<td>'.$j.'</td>
								<td class="text-left">Bureau</td>
								<td class="text-center">'.$box_type.'</td>
								<td></td>
								<td></td>
	              <td></td>
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
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
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
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {

		App.init();

		$('.start_date, .end_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

    $('.show').on('click', function(event) {
      event.preventDefault();
      if ($('.start_date').val() != '' && $('.end_date').val() != '') {
        window.location.href = 'orders_list.php?status=<?php echo mysqli_real_escape_string($conn, $_GET['status']); if (isset($_GET['arch'])) { echo"&arch=true"; } ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val();
      }
    });

		$('#data-table').DataTable({
			scrollY:        475,
      scrollX:        true,
      scrollCollapse: true,
      fixedHeader:           {
        header: true,
        footer: true
      },
			paging: false,
			responsive: false,
			ordering: false,
			//order: [[ 0, 'desc' ]],
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

	$('.send-mail').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id'),
      ele = $(this);
		swal({
			title: 'Êtes-vous sûr?',
			text: '',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function(data) {
			if (data.value) {
				ele.addClass('btn-success');
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
			} else {
				//
			}
		}, function(dismiss) {
			//
		});
	});

	$('.send-sms').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id'),
        ele = $(this);
		swal({
			title: 'Êtes-vous sûr?',
			text: '',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function(data) {
			if (data.value) {
        ele.addClass('btn-success');
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'send_sms', id: order_id},
					cache: false,
					success: function(responce) {
						if (responce == 'done'){
							swal({
								title: 'Fait!',
								text: 'SMS a été envoyé avec succès.',
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

			} else {
				//
			}
		}, function(dismiss) {
			//
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