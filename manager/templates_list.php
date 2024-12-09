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
	$page_title = "Calalogue";
	include("header.php");

  if (isset($_GET['arch'])) {
    $rq = "WHERE `status` = 0";
  } else {
    $rq = "WHERE `status` = 1";
  }

  if (isset($_GET['box']) && $_GET['box'] != '') {
    $rq .= ($rq != "" ? " AND `boxes` LIKE '%".mysqli_real_escape_string($conn, $_GET['box'])."%'" : "WHERE `boxes` LIKE '%".mysqli_real_escape_string($conn, $_GET['box'])."%'");
  }
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Calalogue</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" style="position: absolute; left: 35vw;">
			<div class="form-group">
         <div class="col-md-5">
            <select class="form-control type_id">
              <option value="0"<?php if (!isset($_GET['type_id']) || $_GET['type_id'] == 0) {echo" selected";} ?>>Choisissez une thème ...</option>
              <?php
                $result_types = mysqli_query($conn, "SELECT * FROM `types` ORDER BY `title`");
              	while($row_types = mysqli_fetch_assoc($result_types)) {
                	echo"<option value=\"".$row_types['id']."\""; if ($row_types['id'] == $_GET['type_id']) {echo" selected";} echo">".$row_types['title']."</option>";
              	}
              ?>
            </select>
          </div>
          <div class="col-md-5">
            <select class="form-control box">
              <option value=""<?php if (!isset($_GET['box']) || $_GET['box'] == "") {echo" selected";} ?>>Choisissez une borne ...</option>
              <?php
                echo'<option value="Ring"'; if ($_GET['box'] == 'Ring') {echo" selected";} echo'>Ring</option>';
	              echo'<option value="Vegas"'; if ($_GET['box'] == 'Vegas') {echo" selected";} echo'>Vegas</option>';
	              echo'<option value="Miroir"'; if ($_GET['box'] == 'Miroir') {echo" selected";} echo'>Miroir</option>';
	              echo'<option value="Spinner_360"'; if ($_GET['box'] == 'Spinner_360') {echo" selected";} echo'>Spinner_360</option>';
	              echo'<option value="Réalité_virtuelle"'; if ($_GET['box'] == 'Réalité_virtuelle') {echo" selected";} echo'>Réalité_virtuelle</option>';
              ?>
            </select>
          </div>
	        <div class="col-md-1">
	          <button class="btn btn-sm btn-success show">Afficher</button>
	        </div>
	    </div>
	  </form>
		<table id="data-table" class="table table-striped table-bordered nowrap categories-table" width="100%">
			<thead>
				<tr>
					<th class="no-sort" style="width: 50px;">ID</th>
					<th class="no-sort" style="width: 120px;">Template</th>
					<th class="no-sort" style="width: 120px;">Image de présentation</th>
					<th class="no-sort" style="width: 120px;">Image terminée</th>
					<th class="no-sort" style="width: 120px;">Aperçu</th>
					<th class="no-sort" style="width: 120px;">Aperçu Marque blanche</th>
					<th class="no-sort">Thèmes</th>
					<th>Borne</th>
          <th>N</th>
          <th>Format</th>
          <th>Statistiques</th>
          <th class="no-sort" style="width: 50px;">Éditeur</th>
					<th class="no-sort" style="width: 75px;"><?php echo ACTIONS ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_templates = mysqli_query($conn, "SELECT * FROM `templates`".$rq);
				$i=1;
				while($row_templates = mysqli_fetch_assoc($result_templates)) {
          $types_ids_arr = explode(",", $row_templates['types_ids']);
          if ((isset($_GET['type_id']) && $_GET['type_id'] != 0 && in_array($_GET['type_id'], $types_ids_arr)) || (!isset($_GET['type_id']) || $_GET['type_id'] == 0)) {
            $result_orders = mysqli_query($conn, "SELECT `id` FROM `orders_new` WHERE `template_id` = ".$row_templates['id']);
  					echo'<tr id="tr'.$row_templates['id'].'" class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
  						<td>'.$row_templates['id'].'</td>
  						<td>';
  							if ($row_templates['image'] != "") {
  								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image'].'" title="'.$row_templates['title'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120').'" alt="" /></a>';
  							}
  						echo'</td>
  						<td>';
  							if ($row_templates['second_image'] != "") {
  								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_templates['second_image'].'" title="'.$row_templates['title'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['second_image'], '120').'" alt="" /></a>';
  							}
  						echo'</td>
  						<td>';
  							if ($row_templates['third_image'] != "") {
  								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_templates['third_image'].'" title="'.$row_templates['title'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['third_image'], '120').'" alt="" /></a>';
  							}
  						echo'</td>
  						<td>';
  							if ($row_templates['preview'] != "") {
  								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_templates['preview'].'" title="'.$row_templates['title'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview'], '120').'" alt="" /></a>';
  							}
  						echo'</td>
  						<td>';
  							if ($row_templates['preview2'] != "") {
  								echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_templates['preview2'].'" title="'.$row_templates['title'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview2'], '120').'" alt="" /></a>';
  							}
  						echo'</td>
  						<td>
  							<b>'.$row_templates['title'].'</b>';
  							if ($row_templates['types_ids'] != "") {
  								$result_types = mysqli_query($conn, "SELECT * FROM `types` WHERE `id` IN (".$row_templates['types_ids'].")");
  								while($row_types = mysqli_fetch_assoc($result_types)) {
  									echo " / ".$row_types['title'];
  								}
  							}
  						echo'<br /><br />Nombre de photos : '.$row_templates['photos_amount'].'
  						</td>
  						<td>'.$row_templates['boxes'].'</td>
              <td class="text-center">'.$row_templates['photos_amount'].'</td>
              <td class="text-center">';
                switch($row_templates['format_id']) {
                  case 1: echo'Paysage'; break;
                  case 2: echo'Portrait'; break;
                  case 3: echo'Strip'; break;
                  default:
                    echo"-";
                  break;

                }
              echo'</td>
              <td class="text-center">'.mysqli_num_rows($result_orders).'</td>
              <td class="text-center">';
                if ($row_templates['image'] != "" && $row_templates['second_image'] != "") {
                  echo'<a href="./editor/?image='.$row_templates['image'].'&sample_image='.$row_templates['second_image'].'&json='.$row_templates['data'].'&order_id=0&template_id='.$row_templates['id'].'" class="btn btn-primary btn-icon btn-circle btn-lg" title="Éditeur" data-id="'.$row_templates['id'].'" onclick="setCookie(\'page\', table.page.info().page);"><i class="fa fa-edit"></i></a>';
                }
              echo'</td>
  						<td>';
                if ($row_templates['status'] == 1) {
                  echo'<a class="btn btn-success btn-icon btn-circle btn-sm" title="Désactiver" onClick="activate_deactivate(this, '.$row_templates['id'].');"><i class="fa fa-eye"></i></a>';
                } else {
                  echo'<a class="btn btn-default btn-icon btn-circle btn-sm" title="Activer" onClick="activate_deactivate(this, '.$row_templates['id'].');"><i class="fa fa-eye-slash"></i></a>';
                }
  							echo'&nbsp;<a href="edit_template.php?template_id='.$row_templates['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="'.EDIT.'"><i class="fa fa-edit"></i></a>
  							<a class="btn btn-danger btn-icon btn-circle btn-sm" title="'.DELETE.'" onClick="del(this, '.$row_templates['id'].');"><i class="fa fa-times"></i></a>
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
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet">

<link href="assets/plugins/DataTables/extensions/RowReorder/css/rowReorder.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet">

<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/fancyBox/source/jquery.fancybox.css?v=2.1.6" rel="stylesheet" type="text/css" media="screen" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Select/js/dataTables.select.min.js"></script>
<script src="assets/plugins/DataTables/extensions/RowReorder/js/dataTables.rowReorder.min.js"></script>

<script src="assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>

<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/fancyBox/source/jquery.fancybox.pack.js?v=2.1.6"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
	var table;
	$(document).ready(function() {

		App.init();

		table = $('#data-table').DataTable({
			pageLength: 20,
      scrollY:        475,
      scrollX:        true,
      scrollCollapse: true,
      fixedHeader:           {
        header: true,
        footer: true
      },
			responsive: false,
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
						window.location.href = 'add_template.php';
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

		//table.column('0:visible').order('asc').draw();

		table.on('row-reorder', function (e, diff, edit) {
			var new_positions = '';
			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = table.row(diff[i].node).data();
				new_positions += rowData[1] + ':' + diff[i].newData + ';'
			}
			if (new_positions != '') {
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'categories_reorder', new_positions: new_positions},
					cache: false
				});
			}
		});



		table.on( 'page.dt', function () {
			setCookie('page', table.page.info().page);
		});

		<?php
			if (isset($_COOKIE["page"]) && $_COOKIE["page"] != 0) {
				echo"table.page(".$_COOKIE["page"].").draw('page');";
			}
		?>

    //table.page(1).draw('page');


    $('.show').on('click', function(event) {
      event.preventDefault();

      var href = 'templates_list.php?action=show';
      if ($('.type_id').val() != 0) {
      	href += '&type_id=' + $('.type_id').val();
      }
      if ($('.box').val() != '') {
      	href += '&box=' + $('.box').val();
      }

      window.location.href = href;

    });


		$('.fancybox').fancybox({
			padding: 0,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});

    /*$('.to-edit').click(function(event) {
      event.preventDefault();
      var id = $(this).attr('data-id');
      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'to_edit', id: id},
        cache: false,
        success: function(responce){
          if (responce == 'done') {
            window.location.href = './editor/';
          } else {
            console.log(responce);
          }
        }
      });
    });*/

	});


  function activate_deactivate(ele, id) {
    event.preventDefault();
    if ($(ele).hasClass('btn-default')) {
      $(ele).removeClass('btn-default').addClass('btn-success').removeClass('product-activate').addClass('product-deactivate');
      $(ele).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
      $(ele).parents('tr').removeClass('grayscale');
      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'template_activate', id: id},
        cache: false,
        success: function() {
          $('#tr' + id).remove();
        }
      });
    } else {
      $(ele).removeClass('btn-success').addClass('btn-default').removeClass('product-deactivate').addClass('product-activate');
      $(ele).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
      $(ele).parents('tr').addClass('grayscale');
      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'template_deactivate', id: id},
        cache: false,
        success: function() {
          $('#tr' + id).remove();
        }
      });
    }
  };

	// Удаление категории
	function del(ele, id) {
		event.preventDefault();
		var tr = $(ele).parents('tr');
		swal({
			title: '<?php echo ARE_YOU_SURE ?>',
			text: '<?php echo WANT_DELETE ?>',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: '<?php echo DELETE ?>',
			cancelButtonColor: '#929ba1',
			cancelButtonText: '<?php echo CANCEL ?>'
		}).then(function() {
			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'delete_template', id: id},
				cache: false,
				success: function(responce){
					if (responce == 'done') {
						tr.remove();
					} else {
						swal({
							title: 'Ошибка!',
							text: responce,
							type: 'error',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'Закрыть'
						});
					}
				}
			});
		}, function(dismiss) {
			// dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
		});
	}
</script>

<?php include("end.php"); ?>