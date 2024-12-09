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
	$page_title = "Calendrier";
	include("header.php");

	if (isset($_GET['current_date'])) {
    $current_date = mysqli_real_escape_string($conn, $_GET['current_date']);
  } else {
    $current_date = date("d.m.Y H:i", time());
  }

  $ring = $row_settings['ring'];
  $vegas = $row_settings['vegas'];
  $vegas_slim = $row_settings['vegas_slim'];
  $miroir = $row_settings['miroir'];
  $spinner = $row_settings['spinner'];
  $vr = $row_settings['vr'];
  $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `take_date` != '' AND `return_date` != '' AND `status` = 2");
  while($row_orders = mysqli_fetch_assoc($result_orders)) {
    if (strtotime(str_replace("/", ".", $row_orders['take_date'])) < strtotime($current_date)) {
      if (time() > strtotime(str_replace("/", ".", $row_orders['take_date'])) && time() <  strtotime(str_replace("/", ".", $row_orders['return_date'])) + $row_orders['transportation_time'] * 3600 && strtolower(trim($row_orders['box_type'])) == "ring") {$ring--;}
      if (time() > strtotime(str_replace("/", ".", $row_orders['take_date'])) && time() <  strtotime(str_replace("/", ".", $row_orders['return_date'])) + $row_orders['transportation_time'] * 3600 && strtolower(trim($row_orders['box_type'])) == "vegas") {$vegas--;}
      if (time() > strtotime(str_replace("/", ".", $row_orders['take_date'])) && time() <  strtotime(str_replace("/", ".", $row_orders['return_date'])) + $row_orders['transportation_time'] * 3600 && strtolower(trim($row_orders['box_type'])) == "vegas slim") {$vegas--;}
      if (time() > strtotime(str_replace("/", ".", $row_orders['take_date'])) && time() <  strtotime(str_replace("/", ".", $row_orders['return_date'])) + $row_orders['transportation_time'] * 3600 && strtolower(trim($row_orders['box_type'])) == "miroir") {$miroir--;}
      if (time() > strtotime(str_replace("/", ".", $row_orders['take_date'])) && time() <  strtotime(str_replace("/", ".", $row_orders['return_date'])) + $row_orders['transportation_time'] * 3600 && strtolower(trim($row_orders['box_type'])) == "spinner_360") {$spinner--;}
      if (time() > strtotime(str_replace("/", ".", $row_orders['take_date'])) && time() <  strtotime(str_replace("/", ".", $row_orders['return_date'])) + $row_orders['transportation_time'] * 3600 && strtolower(trim($row_orders['box_type'])) == "réalité_virtuelle") {$vr--;}
    }
  }



?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Calendrier</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal">
    <div class="form-group">
       <label class="col-md-1 control-label">Date</label>
        <div class="col-md-2">
          <input type="text" class="form-control current_date" value="<?php echo $current_date; ?>" placeholder="Début de période" />
        </div>
        <div class="col-md-1">
          <button class="btn btn-sm btn-success show">Afficher</button>
        </div>
    </div>
		<!--div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>Type de borne</h3>
			</div>
		</div-->
		<div class="row">
		  <div class="col-md-6">
		    <div class="panel panel-inverse" data-sortable-id="index-1">
		      <div class="panel-heading">
		        <div class="panel-heading-btn">
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
		        </div>
		        <h4 class="panel-title">Type de borne</h4>
		      </div>
		      <div class="panel-body">
		        <div id="bar-chart" class="height-sm"></div>
		      </div>
		    </div>
		  </div>
		</div>


	</form>
	</div>
</div>
<!-- end panel -->

<?php
	include("footer.php");
?>

<link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<script src="assets\plugins\flot\jquery.flot.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.time.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.resize.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.pie.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.stack.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.crosshair.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.categories.js"></script>
<script src="assets\plugins\flot\jquery.flot.barlabels.js"></script>

<script src="assets/plugins/moment/moment-with-locales.js"></script>
<script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets\js\apps.min.js"></script>

<script>

	$(document).ready(function() {

		App.init();

    $('.current_date').datetimepicker({
      format: "DD.MM.YYYY HH:mm",
      locale: 'fr'
    });

    $('.show').on('click', function(event) {
      event.preventDefault();
      if ($('.current_date').val() != '') {
        window.location.href = 'calendar.php?current_date=' + $('.current_date').val();
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

    var data = [
     	{data: [["Ring", <?php echo $ring ?>]], color: blue},
     	{data: [["Vegas", <?php echo $vegas ?>]], color: aqua},
      {data: [["Vegas Slim", <?php echo $vegas_slim ?>]], color: aquaDark},
     	{data: [["Miroir", <?php echo $miroir ?>]], color: green},
      {data: [["Spinner", <?php echo $spinner ?>]], color: orange},
      {data: [["VR", <?php echo $vr ?>]], color: purple}
    ];

    $.plot("#bar-chart", data, {
      series: {
        bars: {
          show: !0,
          barWidth: .4,
          align: "center",
        }
      },
      xaxis: {
        mode: "categories",
        tickColor: "#ddd",
        tickLength: 0
      },
      grid: {
      	hoverable: true,
        clickable: true,
        borderWidth: 0
      }
    });

    var previousPoint = null,
    previousLabel = null;

		function showTooltip(x, y, color, contents) {
		    $('<div id="tooltip">' + contents + '</div>').css({
		        position: 'absolute',
		        display: 'none',
		        top: y - 40,
		        left: x - 40,
		        border: '2px solid ' + color,
		        padding: '3px',
		            'font-size': '9px',
		            'border-radius': '5px',
		            'background-color': '#fff',
		            'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
		        opacity: 0.9
		    }).appendTo("body").fadeIn(200);
		}

    $("#bar-chart").bind("plotclick", function (event, pos, item) {
      if (item) {
        console.log(item);
        window.location.href = 'box_list.php?type_id=' + item.datapoint[0] + '&current_date=' + $('.current_date').val();
      }
    });


		$("#bar-chart").on("plothover", function (event, pos, item) {
		    if (item) {
		        if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
		            previousPoint = item.dataIndex;
		            previousLabel = item.series.label;
		            $("#tooltip").remove();

		            var x = item.datapoint[0];
		            var y = item.datapoint[1];

		            var color = item.series.color;

		            showTooltip(item.pageX,
		            item.pageY,
		            color,
		            "<strong>" + item.series.xaxis.ticks[x].label + " : " + item.datapoint[1] + "</strong>");
		        }
		    } else {
		        $("#tooltip").remove();
		        previousPoint = null;
		    }
		});




	});
</script>

<?php include("end.php"); ?>