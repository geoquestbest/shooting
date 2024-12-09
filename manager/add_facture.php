<?php
	$page_title = "Ajouter une facture";
	$breadcrumbs = '<a href="facture_list.php" title="Articleы">Articleы</a>';
	include("header.php");
	if (isset($_GET['order_id'])) {
		$order_id = $_GET['order_id'];
	} else {
		$order_id = 0;
	}
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Ajouter une facture</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal add-facture">
			<div class="form-group">
				<label class="col-md-3 control-label required">Commande</label>
				<div class="col-md-4">
					<select class="form-control order_id">
						<option value="0">Sélectionnez une commande...</option>
						<?php
							$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` ORDER BY `id` DESC");
							while($row_orders = mysqli_fetch_assoc($result_orders)) {
								if ((floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0)) {
									echo'<option value="'.$row_orders['id'].'"'.($order_id == $row_orders['id'] ? ' selected' : '').'>'.$row_orders['id'].' - '.$row_orders['event_date'].'</option>';
								}
							}
						?>
					</select>
				</div>
			</div>
      <div class="add-row">
  			<div class="form-group">
  				<label class="col-md-3 control-label required">Article</label>
  				<div class="col-md-3">
    				<select class="form-control article_id">
              <option value="0">Sélectionnez le article...</option>
    					<?php
    						if ($order_id != 0) {
    							$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
    							$row_orders = mysqli_fetch_assoc($result_orders);
    							if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
    								$rq = "WHERE `type_id` = 1";
    							} else {
    								$rq = "WHERE `type_id` = 2";
    							}
    							if (strtolower(trim($row_orders['box_type'])) == "ring") {$box_id = 1;}
    							if (strtolower(trim($row_orders['box_type'])) == "vegas") {$box_id = 2;}
    							if (strtolower(trim($row_orders['box_type'])) == "miroir") {$box_id = 3;}
    							if (strtolower(trim($row_orders['box_type'])) == "spinner_360") {$box_id = 4;}
    							if (strtolower(trim($row_orders['box_type'])) == "réalité_virtuelle") {$box_id = 5;}
    						} else {
    							$rq = "";
    						}
    						$result_articles = mysqli_query($conn, "SELECT * FROM `articles` $rq ORDER BY `id`");
    						while($row_articles = mysqli_fetch_assoc($result_articles)) {
    							$box_id_arr = explode(",", $row_articles['box_id']);
    							if (in_array($box_id, $box_id_arr) || $order_id == 0) {
    								echo'<option value="'.$row_articles['id'].'">'.$row_articles['num'].' - '.$row_articles['title'].' / '.$row_articles['price'].'€</option>';
    							}
    						}
    					?>
    				</select>
          </div>
          <label class="col-md-1 control-label">Tariff, € :</label>
          <div class="col-md-2">
            <div class="input-group">
              <input type="text" class="form-control price numeric" value="0" placeholder="Tariff, €" />
              <div class="input-group-btn coord-btn">
                <a href="javascript:void(0)" class="btn btn-primary" title="Ajouter le article" onClick="addArticle($(this))"><i class="fa fa-plus"></i></a>
              </div>
            </div>
  				</div>
  			</div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success">Ajouter</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- end panel -->


<?php
	include("footer.php");
?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		// Добавление страницы
		$('.add-facture').on('submit', function(event){
			event.preventDefault();
			if ($('.order_id').val() == 0) {
				showError('Sélectionnez une commande !');
				return false;
			}

			var articles_ids = '';
      $('.add-row').each(function(idx) {
        if ($(this).find('.article_id').val() != 0) {
          articles_ids += $(this).find('.article_id').val() + ':' + $(this).find('.price').val() + ',';
        }
      });
      articles_ids = articles_ids.slice(0, -1);

			if ($('.articles_ids').val() == '') {
				showError('Sélectionnez le articles !');
				return false;
			}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_facture', order_id: $('.order_id').val(), articles_ids: articles_ids},
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'Article a été ajouté avec succès!',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'ОК',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'factures_list.php?type=<?php echo $row_orders['status'] ?>';
						});
					} else {
						showError(responce);
					}
				}
			});
		});

    $('.article_id').on('change', function(event) {
      if ($(this).val() != 0) {
        var data = $(this).find('option:selected').text(),
          data_arr = data.split(' / ');
        $(this).parents('.form-group').find('.price').val((data_arr[1]).replace('€', '').trim());
      } else {
        $(this).parents('.form-group').find('.price').val(0);
      }
    });

	});
  var i = 1;
  function addArticle(ele) {
    var content = '<div class="add-row add-row' + i + '">' +
        '<div class="form-group">' +
        '<label class="col-md-3 control-label required">Article</label>' +
        '<div class="col-md-3">' +
          '<select class="form-control article_id"></select>'+
        '</div>' +
        '<label class="col-md-1 control-label">Tariff, € :</label>' +
        '<div class="col-md-2">' +
          '<div class="input-group">' +
            '<input type="text" class="form-control price numeric" value="0" placeholder="Tariff, €" />' +
            '<div class="input-group-btn">' +
              '<a href="javascript:void(0)" class="btn btn-danger" title="Supprimer le article" onClick="$(this).parents(\'.add-row\').remove();"><i class="fa fa-minus"></i></a>' +
              '<a href="javascript:void(0)" class="btn btn-primary" title="Ajouter le article" onClick="addArticle($(this))"><i class="fa fa-plus"></i></a>' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>';
    ele.parents('.add-row').after(content);

    $('.add-row' + i).find('.article_id').html($('.article_id:first-child').html());

    $('.article_id').on('change', function(event) {
      if ($(this).val() != 0) {
        var data = $(this).find('option:selected').text(),
          data_arr = data.split(' / ');
        $(this).parents('.form-group').find('.price').val((data_arr[1]).replace('€', '').trim());
      } else {
        $(this).parents('.form-group').find('.price').val(0);
      }
    });

    i++;
  }
</script>

<?php include("end.php"); ?>