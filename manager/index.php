<?php
@session_start();
include("header.php");

/*if ($_SESSION['user']['folder'] != "") {
	$dirs_arr = scandir(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']);
	if (count($dirs_arr) > 2) {
		require_once("ImageResize.php");
		echo'
		<p class="text-right"><a href="get_photos_archive.php" title="Télécharger tout les photos" class="btn btn-sm btn-success">Télécharger tout les photos</a></p>
		<div class="superbox">';
		foreach($dirs_arr as $key => $dir) {
			if ($dir != "." && $dir != ".." && strpos($dir, "thumb_") === false) {
				if (is_file(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/".$dir)) {
					if (!file_exists(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/thumb_".$dir)) {
						$image = new ImageResize(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/".$dir);
						$image->resizeToHeight(250);
						$image->crop(250, 250, ImageResize::CROPCENTER);
						$image->save(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/thumb_".$dir);
					}
					echo'<div class="superbox-list">
						<img src="'.ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/thumb_".$dir.'" data-img="'.ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/".$dir.'" alt="" class="superbox-img" />
					</div>';
				}
			}
		}
		echo'</div>';
	}
}*/
if (isset($_SESSION['user'])) {
  echo'<img src="img/logo.svg" alt="" style="display: block; width: 40.0vw; margin: 30.0vh auto;" />';
} else {
?>
<!-- begin panel -->
<div class="panel panel-inverse">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title" style="font-size: 18px;">Ma commande</h4>
  </div>
  <div class="panel-body">
    <table id="data-table" class="table table-striped table-bordered orders-table nowrap" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Info</th>
          <th>Date de l’évènement</th>
          <th class="no-sort" style="width: 120px;">Template</th>
          <th class="no-sort" style="width: 75px;">Configuration</th>
          <th class="no-sort" style="width: 160px;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
        $i=1;
        while($row_orders = mysqli_fetch_assoc($result_orders)) {
          echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
            <td>'.$row_orders['id'].'</td>
            <td class="text-left">
                '.( $row_orders['societe'] ? '<b>'. $row_orders['societe']. '</b><br />' : '' ).'
                <p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>
                E-mail : <a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a><br />
                Téléphone : <a href="tel:'.$row_orders['phone'].'" title="Envoyer un e-mail">'.$row_orders['phone'].'</a><br /><br />
                Lieu de l’évènement : <b>'.$row_orders['event_place'].'</b><br />
                Type d’événement : <b>' . $row_orders['event_type'] . '</b><br />
                '.($row_orders['description'] != "" ? '<!--Informations complémentaires : <b>'.$row_orders['description'].'</b-->' : '').'
                <!--br />Comment avez-vous connu ShootnBox : <b>'.$row_orders['about'].'</b-->
              </td>
            <td>'.$row_orders['event_date'].'</td>
            <td>';
              if ($row_orders['image'] != "" && strtolower($row_orders['box_type']) != "vegas") {
                echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120').'" alt="" /></a>';
              }
            echo'</td>
            <td class="text-center">
              <a href="configure.php?order_id='.$row_orders['id'].'" class="btn btn-'.((mysqli_num_rows($result_configure_orders) == 0) ? 'primary' : 'warning').' btn-icon btn-circle btn-lg" title="Configuration 1"><i class="fa fa-edit"></i></a>&nbsp;';
              if (strtolower($row_orders['box_type']) != "vegas") {
                echo'<a href="be/?image='.$row_orders['image'].'&order_id='.$row_orders['id'].'" class="btn btn-'.(($row_orders['data'] == "") ? 'success' : 'danger').' btn-icon btn-circle btn-lg" title="Configuration 2"><i class="fa fa-edit"></i></a>';}
                echo'</td>
              <td></td>
          </tr>';
          $i++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<!-- end panel -->

<div class="panel panel-inverse">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title" style="font-size: 18px;">Mes événements</h4>
  </div>
  <div class="panel-body">
    <table id="data-table2" class="table table-striped table-bordered orders-table nowrap" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Info</th>
          <th>Date de l’évènement</th>
          <th class="no-sort" style="width: 120px;">Template</th>
          <th class="no-sort" style="width: 75px;">Configuration</th>
          <th class="no-sort" style="width: 160px;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $result_events = mysqli_query($conn, "SELECT * FROM `events` WHERE `order_id` = ".$_SESSION['order']['id']);
        $j=0;
        while($row_events = mysqli_fetch_assoc($result_events)) {
          echo'<tr id="tre'.$row_events['id'].'" class="gradeX';if ($j%2 == 0) {echo' odd';} else {echo' even';} echo'">
            <td>'.$_SESSION['order']['id'].$alphabet[$j].'</td>
            <td class="text-left">
                '.$row_events['title'].'
            </td>
            <td>'.date("d.m.Y H:i", $row_events['start_date']).' - '.date("d.m.Y H:i", $row_events['end_date']).'</td>
            <td>';
              if ($row_events['image'] != "") {
                echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_events['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_events['image'], '120').'" alt="" /></a>';
              } else {
                echo'<a href="../template/templates.php?event_id='.$row_events['id'].'" title="Sélectionnez">Sélectionnez</a>';
              }
            echo'</td>
            <td class="text-center">
              <a href="configure.php??order_id='.$_SESSION['order']['id'].'&event_id='.$row_events['id'].'" class="btn btn-'.((mysqli_num_rows($result_configure_orders) == 0) ? 'primary' : 'warning').' btn-icon btn-circle btn-lg" title="Configuration 1"><i class="fa fa-edit"></i></a>&nbsp;
                <a href="be/?image='.$row_events['image'].'&event_id='.$row_events['id'].'" class="btn btn-'.(($row_events['data'] == "") ? 'success' : 'danger').' btn-icon btn-circle btn-lg" title="Configuration 2"><i class="fa fa-edit"></i></a>
              </td>
              <td>';
              echo'<a href="edit_event.php?event_id='.$row_events['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
              &nbsp;<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Supprimer" onClick="deleteEvent('.$row_events['id'].');"><i class="fa fa-close"></i></a>
            </td>
          </tr>';
          $j++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<!-- end panel -->
<?php
}
include("footer.php");
?>

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
      info: false,
      responsive: false,
      searching: false,
      columnDefs: [{
          targets: [0,1,2,3,4,5],
          orderable: false,
        }
      ],
      dom: 'Bfrtip',
      buttons: []
    });

    $('#data-table2').DataTable({
      paging: false,
      responsive: false,
      order: [[ 0, 'asc' ]],
      columnDefs: [{
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
            window.location.href = 'add_event.php';
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