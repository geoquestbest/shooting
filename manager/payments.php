<?php
	switch($_GET['status']) {
		default: $page_title = "Tous paiements"; break;
	}
	include("header.php");
	if (isset($_GET['status'])) {
		$rq = "WHERE `status` > 0 AND `select_type` LIKE 'Un particulier' AND `payment_status` = ".mysqli_real_escape_string($conn, $_GET['status']);
	} else {
		$rq = "WHERE `status` > 0 ";
	}
  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", strtotime("31.12.".date("Y", time())));
  }
  if ($_GET['facture_date'] == 1) {
    $rq2 = "AND `created_at` >= ".strtotime($start_date)." AND `created_at` <= ".strtotime($end_date);
  } else {
    $rq2 = "";
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
    <form class="form-horizontal" style="position: absolute; left: 45vw;">
      <div class="form-group">
        <div class="col-md-3">
          <label class="checkbox-inline">
            <input type="checkbox" class="facture_date"<?php if ($_GET['facture_date'] == 1) echo 'checked="checked"'; ?> />
            Date de facture
          </label>
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
        </div>
        <div class="col-md-3">
          <button class="btn btn-sm btn-success show">Afficher</button>
        </div>
      </div>
     </form>

		<table id="data-table" class="table table-striped table-bordered orders-table nowrap" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					 <th>Société</th>
          <th>Personne</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Facture</th>
          <th>Date de facture</th>
          <th>Statut</th>
					<th>Borne</th>
					<th>Type</th>
					<th>Date de l’évènement</th>
					<th style="width: auto;">J-X</th>
					<th>Livraison</th>
					<th>Tarif HT, €</th>
          <th>Tarif TTC, €</th>
          <th>Comment</th>
          <?php
            if ($_GET['status'] != 2) {
              echo'<th class="no-sort">Acompte, €</th>';
            }
            if ($_GET['status'] == 1) {
              echo'<th class="no-sort">Solde, €</th>';
            }
          ?>
					<th class="no-sort" style="width: 160px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` $rq ORDER BY `id` DESC");
				$i=1;
        $total = $total2 = $total3 = 0;
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					 $result_facture = mysqli_query($conn, "SELECT * FROM `facture` WHERE `order_id` = ".$row_orders['id']." ".$rq2." ORDER BY `id` DESC LIMIT 1");
          if (strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date) && (($_GET['facture_date'] == 1 && mysqli_num_rows($result_facture ) > 0) || (!isset($_GET['facture_date']) || $_GET['facture_date'] == 0))) {
						$delivery = mb_strpos($row_orders['selected_options'], 'Retrait boutique') ? 'Retrait boutique' : 'Livraison';
						echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
							<td>'.$row_orders['id'].'</td>
							<td>
                '.$row_orders['societe'].'
              </td>
              <td>
                '.$row_orders['last_name'].' '.$row_orders['first_name'].'
              </td>
              <td>
                <a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a>
              </td>
              <td>
               <a href="tel:'.$row_orders['phone'].'" title="Envoyer un e-mail">'.$row_orders['phone'].'</a>
              </td>
              <td class="text-center">';
                $result_facture = mysqli_query($conn, "SELECT * FROM `facture` WHERE `order_id` = ".$row_orders['id']." ORDER BY `id` DESC LIMIT 1");
                if (mysqli_num_rows($result_facture ) > 0) {
                  $row_facture = mysqli_fetch_assoc($result_facture);
                  echo'<a class="facture-list" data-url="'.$row_orders['num_id'].'/'.$row_facture['pdf'].'" href="../uploads/Factures/'.$row_orders['num_id'].'/'.$row_facture['pdf'].'" target="_blank">'. $row_orders['num_id'].'</a>
                  </td>
                  <td class="text-center" data-sort="'.$row_facture['created_at'].'">'.date("d.m.Y H:i", $row_facture['created_at']);
                } else {
                  echo $row_orders['num_id'].'
                  </td>
                  <td class="text-center">-';
                }
              echo'</td>
              <td>';
              if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
                if ($row_orders['payment_status'] == 0) {
                  echo"Nonsoldé";
                } else {
                   echo"Soldé";
                }
              } else {
                if ($row_orders['deposit'] == 0 && $row_orders['payment_status'] == 0) {
                  echo"Nonsoldé";
                }
                if (($row_orders['deposit'] > 0 && $row_orders['payment_status'] == 2) || $row_orders['payment_type2'] > 0) {
                  echo"Soldé";
                }
                if ($row_orders['deposit'] > 0 && $row_orders['payment_status'] < 2) {
                  //echo"Sans acompte";
                  echo"Nonsoldé";
                }
              }
              echo'</td>
							<td class="text-center">'.$row_orders['box_type'].'</td>
							<td>'.$row_orders['select_type'].'</td>
							<td>'.$row_orders['event_date'].'</td>
							<td class="text-center">'.(floor((strtotime($row_orders['event_date']) - time())/(3600*24)) > 0 ? floor((strtotime($row_orders['event_date']) - time())/(3600*24)) : "-").'</td>
							<td>'.$delivery.'</td>
							<!--td class="text-center">'.$row_orders['total'].'€ '. ($row_orders['select_type'] == 'Une entreprise' ? 'HT' : 'TTC').( $row_orders['discount'] ? '<br />Votre reduction avec code promo : -'.$row_orders['discount'].'€<br />' : '').'</td-->

              <td class="text-center">'.($row_orders['select_type'] == 'Une entreprise' ? number_format(str_replace(",", ".", $row_orders['total']), 2, '.', '') : number_format(str_replace(",", ".", $row_orders['total'])/1.2, 2, '.', '')).'</td>
              <td class="text-center">'.($row_orders['select_type'] == 'Une entreprise' ? number_format((str_replace(",", ".", $row_orders['total']) + str_replace(",", ".", $row_orders['total'])*0.2), 2, '.', '') : number_format(str_replace(",", ".", $row_orders['total']), 2, '.', '')).'</td>
              <td class="text-center">'.($row_orders['discount'] != 0 ? '<br />Votre reduction avec code promo : -'.$row_orders['discount'].'€<br />' : '').'</td>';

              if ($_GET['status'] != 2) {
							  echo'<td class="text-center">
                  '.$row_orders['deposit'];
                echo'</td>';
              }
              if ($_GET['status'] == 1) {
                echo'<td class="text-center">'.($row_orders['total'] - $row_orders['deposit']).'</td>';
              }
							echo'<td>';
                if ($row_orders['payment_type2'] != 0) {
                  echo'<a href="#" class="btn '.($row_orders['payment_status'] == 0 ? 'btn-danger' : 'btn-success').' btn-icon btn-circle btn-sm pay-status" data-id="'.$row_orders['id'].'" title="Acompte statut"><i class="fa fa-money"></i></a>&nbsp;';
                }
                if ($row_orders['deposit'] > 0) {
                  echo'<a href="#" class="btn '.($row_orders['payment_status'] == 0 ? 'btn-danger' : 'btn-success').' btn-icon btn-circle btn-sm pay-status" data-id="'.$row_orders['id'].'" data-btn="1" data-status="0" title="Acompte statut"><i class="fa fa-money"></i></a>&nbsp;';
                }
                if ($row_orders['deposit'] > 0 && $row_orders['payment_status'] > 0) {
                  echo'<a href="#" class="btn '.($row_orders['payment_status'] != 2 ? 'btn-danger' : 'btn-success').' btn-icon btn-circle btn-sm pay-status" data-id="'.$row_orders['id'].'" data-btn="2" data-status="'.$row_orders['payment_status'].'" title="Solde statut"><i class="fa fa-money"></i></a>&nbsp;';
                }
								echo'<a href="edit_payment.php?order_id='.$row_orders['id'].'&status='.mysqli_real_escape_string($conn, $_GET['status']).'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
							</td>
						</tr>';
						$i++;
            if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
              $total = $total + (str_replace(",", ".", $row_orders['total']) + str_replace(",", ".", $row_orders['total'])*0.2);
            } else {
              $total = $total + str_replace(",", ".", $row_orders['total']);
            }
            $total2 = $total2 + str_replace(",", ".", $row_orders['deposit']);
            $total3 = $total3 + (str_replace(",", ".", $row_orders['total']) - str_replace(",", ".", $row_orders['deposit']));
					}
				}
				?>
			</tbody>
      <tfoot>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th><?php echo number_format($total, 2, '.', ''); ?>€</th>
          <th></th>
          <?php
            if ($_GET['status'] != 2) {
              echo'<th>'.$total2.'€</th>';
            }
            if ($_GET['status'] == 1) {
              echo'<th>'.$total3.'€</th>';
            }
          ?>
          <th></th>
        </tr>
      </tfoot>
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
        window.location.href = 'payments.php?start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&facture_date=' + ($('.facture_date').is(':checked') ? 1 : 0);
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
          text: '<i class="fa fa-download"></i> Télécharger',
          action: function (e, dt, node, config) {
            var facture_list = '';
            $('.facture-list').each(function(){
              facture_list += $(this).attr('data-url') + ';'
            });
            if (facture_list != '') {
              facture_list = facture_list.slice(0, -1);
              window.location.href = 'get_factures.php?urls=' + facture_list;
            }
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

  $('.pay-status').on('click', function(event) {
    event.preventDefault();
    if ($(this).hasClass('btn-danger')) {
      $(this).removeClass('btn-danger').addClass('btn-success');
      if ($(this).attr('data-btn') == 1) {
        var status = 1;
      } else {
        var status = 2;
      }
    } else {
      $(this).removeClass('btn-success').addClass('btn-danger');
      var status = $(this).attr('data-status');
    }
    var order_id = $(this).attr('data-id');

    $.ajax({
      type: 'POST',
      url: 'd26386b04e.php',
      data: {event: 'pay_status', id: order_id, status: status},
      cache: false,
      success: function(responce) {
        if (responce == 'done'){
          swal({
            title: 'Fait!',
            text: 'Statut de paiement défini avec succès.',
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

	$('.send-mail').on('click', function(event) {
		event.preventDefault();
		$(this).addClass('btn-success');
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