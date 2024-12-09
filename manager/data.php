<?php
	$page_title = "Data";

  include("header.php");

  $rq = "WHERE `status` = 2";

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
		<h4 class="panel-title"><?php echo $page_title ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" style="position: absolute; left: 37vw;">
			<div class="form-group">
          <div class="col-md-3">
            <select class="form-control type_id">
              <option value="">Tous</option>
              <option value="2">SMS</option>
              <option value="1">Email</option>
            </select>
          </div>
          <label class="col-md-1 control-label">Période</label>
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
					<th>ID</th>
					<th>Utilisateur</th>
					<th>Date de l’évènement</th>
					<th>SMS</th>
					<th>Email</th>
					<th>Tous</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` $rq ORDER BY `id` DESC");
				$i=1; $total = 0;
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
          $result_orders_data = mysqli_query($conn, "SELECT * FROM `orders_data` WHERE `order_id` = ".$row_orders['id']);
					if (mysqli_num_rows($result_orders_data) > 0 && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) {
            $result_orders_data_mail = mysqli_query($conn, "SELECT * FROM `orders_data` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 1");
            $result_orders_data_sms = mysqli_query($conn, "SELECT * FROM `orders_data` WHERE `order_id` = ".$row_orders['id']." AND `type_id` = 2");
						echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
							<td class="text-center">'.$row_orders['id'].'</td>
							<td class="text-left">
                '.( $row_orders['societe'] ? '<b>'. $row_orders['societe']. '</b><br />' : '' ).'
								<p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>
							</td>
							<td class="text-center" data-sort="'.strtotime($row_orders['event_date']).'">'.$row_orders['event_date'].'</td>
              <td class="text-center">
                '.(mysqli_num_rows($result_orders_data_sms) > 0 ? '<a href="data_xls.php?order_id='.$row_orders['id'].'&type_id=2" class="btn btn-primary btn-icon btn-circle btn-sm" title="SMS"><i class="fa fa-send-o"></i></a><br /><small>('.mysqli_num_rows($result_orders_data_sms).')</small>' : '-').'
              </td>
              <td class="text-center">
                '.(mysqli_num_rows($result_orders_data_mail) > 0 ? '<a href="data_xls.php?order_id='.$row_orders['id'].'&type_id=1" class="btn btn-success btn-icon btn-circle btn-sm" title="Email"><i class="fa fa-envelope-o"></i></a><br /><small>('.mysqli_num_rows($result_orders_data_mail).')</small>' : '-').'
              </td>
              <td class="text-center">
                <a href="data_xls.php?order_id='.$row_orders['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Tous"><i class="fa fa-download"></i></a><br /><small>('.mysqli_num_rows($result_orders_data).')</small>
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
        window.location.href = 'data.php?start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val();
      }
    });


		$('#data-table').DataTable({
      fixedHeader:           {
        header: true,
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
				'csv', 'excel', 'pdf', 'print',
        {
          text: '<i class="fa fa-download"></i> Télécharger',
          action: function (e, dt, node, config) {
            window.location.href = 'all_data_xls.php?start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + ($('.type_id').val() != '' ? '&type_id=' + $('.type_id').val() : '');;
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




</script>

<?php include("end.php"); ?>