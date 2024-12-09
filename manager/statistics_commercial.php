<?php
	$page_title = "Commercial";
	include("header.php");
	if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", strtotime("31.12.".(date("Y", time()) + 2)));
  }
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Commercial</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" style="position: absolute; left: 45vw;">
			<div class="form-group">
	       <label class="col-md-2 control-label">Période</label>
	        <div class="col-md-3">
	          <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
	        </div>
	        <div class="col-md-3">
	          <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
	        </div>
	        <div class="col-md-1">
	          <button class="btn btn-sm btn-success show">Afficher</button>
	        </div>
	    </div>
	  </form>
		<table id="data-table" class="table table-striped table-bordered orders-table nowrap" width="100%">
			<thead>
				<tr>
					<th>N</th>
					<th>Prénom</th>
					<th>Demandes</th>
					<th>Réservation</th>
          <th>Relances</th>
          <th>Refusé</th>
          <th>Archives</th>
					<th>Conversion</th>
          <th>Chiffre d’affaire, HT</th>
					<th>Chiffre d’affaire, TTC</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `is_commercial` = 1 ORDER BY `first_name` ASC");
				$i=1; $total1 = $total2 = 0;
				while($row_users = mysqli_fetch_assoc($result_users)) {
					$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `user_id` = ".$row_users['id']);
					$total_orders = $reservation_orders = $arch_orders = $arch_refuse = $total = 0;
					while($row_orders = mysqli_fetch_assoc($result_orders)) {
						if (strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) {
							$total_orders++;
							if ($row_orders['status'] == 2) {
								$reservation_orders++;
								if (strpos(strtolower($row_orders['select_type']), 'entreprise') === false) {
								  $total += str_replace(",", ".", $row_orders['total']);
		            } else {
		              $total += str_replace(",", ".", $row_orders['total'])*1.2;
		            }
							} elseif ($row_orders['status'] == 0) {
                  if (strtotime($row_orders['event_date']) < time()) {
                    $arch_orders++;
                  }
                } elseif ($row_orders['status'] == -1) {
                  $arch_refuse++;
                }

						}
					}
          $total1 = $total1 + $total/1.2;
          $total2 = $total2 + $total;
					echo'<tr id="tr'.$row_users['id'].'" class="gradeX'; if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
						<td>'.$i.'</td>
						<td>'.$row_users['first_name'].'</td>
						<td>'.$total_orders.'</td>
						<td>'.$reservation_orders.'</td>
            <td class="text-center"><a href="javascript:void(0)" title="Relances" onClick="toOrders('.$row_users['id'].')">'.($total_orders - $reservation_orders - $arch_orders - $arch_refuse).'</a></td>
            <td class="text-center"><a href="javascript:void(0)" title="Refusé" onClick="toRefuse('.$row_users['id'].')">'.$arch_refuse.'</a></td>
            <td class="text-center"><a href="javascript:void(0)" title="Archives" onClick="toArchives('.$row_users['id'].')">'.$arch_orders.'</a></td>
						<td>'.($total_orders > 0 ? number_format($reservation_orders / $total_orders * 100, 1, '.', '').'%' : '-').'</td>
						<td class="text-center">'.number_format($total/1.2, 2, '.', '').'€</td>
            <td class="text-center">'.number_format($total, 2, '.', '').'€</td>
					</tr>';
					$i++;
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
          <th><?php echo number_format($total1, 2, '.', ''); ?>€</th>
          <th><?php echo number_format($total2, 2, '.', ''); ?>€</th>
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

		$('#data-table').DataTable({
			paging: false,
			responsive: true,
			order: [[ 1, 'asc' ]],
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

		$('.show').on('click', function(event) {
      event.preventDefault();
      if ($('.start_date').val() != '' && $('.end_date').val() != '') {
        window.location.href = 'statistics_commercial.php?start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val();
      }
    });
	});

  function toOrders(user_id) {
    window.location.href = 'orders_list.php?status=0&user_id=' + user_id + '&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val();
  }

   function toRefuse(user_id) {
    window.location.href = 'orders_list.php?status=-1&user_id=' + user_id + '&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val();
  }

  function toArchives(user_id) {
    window.location.href = 'orders_list.php?status=0&user_id=' + user_id + '&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&arch=true';
  }

</script>

<?php include("end.php"); ?>