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
  $page_title = "Conversion";
  include("header.php");

  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", time());
  }

  $type = mysqli_real_escape_string($conn, $_GET['type']);
  switch($type) {
    case 1:
      $stitle = "Entreprises";
      $rq = " WHERE `select_type` LIKE '%entreprise%' AND `created_at` >= ".strtotime($start_date)." AND `created_at` <= ".strtotime($end_date);
    break;
    case 2:
      $stitle = "Particuliers";
      $rq = " WHERE `select_type` LIKE '%particulier%' AND `created_at` >= ".strtotime($start_date)." AND `created_at` <= ".strtotime($end_date);
    break;
    default:
      $stitle = "Total";
      $rq = " WHERE `created_at` >= ".strtotime($start_date)." AND `created_at` <= ".strtotime($end_date);
    break;
  }

  $result_total_orders = mysqli_query($conn, "SELECT * FROM `orders_new`".$rq);
  $result_total_orders2 = mysqli_query($conn, "SELECT SUM(total) as all_total FROM `orders_new`".$rq);
  $row_total_orders2 = mysqli_fetch_assoc($result_total_orders2);
  $result_completed_orders = mysqli_query($conn, "SELECT * FROM `orders_new`".$rq." AND `status` = 2");
  $result_completed_orders2 = mysqli_query($conn, "SELECT SUM(total) as all_total FROM `orders_new`".$rq." AND `status` = 2");
  $row_completed_orders2 = mysqli_fetch_assoc($result_completed_orders2);

?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title">Conversion <?php echo $stitle ?></h4>
  </div>
  <div class="panel-body">
    <form class="form-horizontal">
    <div class="form-group">
       <label class="col-md-1 control-label">Période</label>
        <div class="col-md-2">
          <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
        </div>
        <div class="col-md-1">
          <button class="btn btn-sm btn-success show">Afficher</button>
        </div>
    </div>

    <div class="form-group">
      <label class="col-md-1 control-label"></label>
      <div class="col-md-2">
        <h3>Conversion</h3>
      </div>
      <div class="col-md-2">
        <h3>Chiffre d'affaire</h3>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-1 control-label"></label>
      <div class="col-md-2">
        <div id="bar-chart3" class="height-sm"></div>
      </div>
      <div class="col-md-2">
        <div id="bar-chart4" class="height-sm"></div>
      </div>
    </div>
  </form>
  </div>
</div>
<!-- end panel -->

<?php
  include("footer.php");
?>

<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />

<script src="assets\plugins\flot\jquery.flot.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.time.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.resize.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.pie.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.stack.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.crosshair.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.categories.js"></script>
<script src="assets\plugins\flot\jquery.flot.barlabels.js"></script>

<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets\js\apps.min.js"></script>

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
        window.location.href = 'conversion.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val();
      }
    });

    var blue = "#007aff",
    blueLight = "#409bff",
    blueDark = "#005bbf",
    aqua = "#5AC8FA",
    aquaLight = "#83d6fb",
    aquaDark = "#4396bb",
    green = "#4CD964",
    greenLight = "#79e38b",
    greenDark = "#39a34b",
    orange = "#FF9500",
    orangeLight = "#ffb040",
    orangeDark = "#bf7000",
    dark = "#222222",
    grey = "#bbbbbb",
    purple = "#5856D6",
    purpleLight = "#8280e0",
    purpleDark = "#4240a0",
    red = "#FF3B30",
    hotpink = "#FF69B4";


    var data3 = [
      {data: [["Demandes", <?php echo mysqli_num_rows($result_total_orders) ?>]], label: "Demandes - <?php echo mysqli_num_rows($result_total_orders) ?>", color: blue},
      {data: [["Réservations", <?php echo mysqli_num_rows($result_completed_orders) ?>]], label: "Réservations - <?php echo mysqli_num_rows($result_completed_orders) ?> ( <?php echo round(mysqli_num_rows($result_completed_orders)/mysqli_num_rows($result_total_orders)*100, 2) ?>%)", color: orange},
    ];

    $.plot("#bar-chart3", data3, {
      series: {
       pie: {
            show: true,
            radius: 1
        }
      }
    });

    var data4 = [
      {data: [["Réalisé", <?php echo $row_completed_orders2['all_total'] ?>]], label: "Réalisé - <?php echo $row_completed_orders2['all_total'] ?>€", color: orange},
      {data: [["Perdu", <?php echo $row_total_orders2['all_total'] - $row_completed_orders2['all_total'] ?>]], label: "Perdu - <?php echo $row_total_orders2['all_total'] - $row_completed_orders2['all_total'] ?>€", color: purple},
    ];

    $.plot("#bar-chart4", data4, {
      series: {
       pie: {
            show: true,
            radius: 1
        }
      }
    });

  });
</script>

<?php include("end.php"); ?>