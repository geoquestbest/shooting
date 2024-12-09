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
	$page_title = "Statistiques";
	include("header.php");

	$type = mysqli_real_escape_string($conn, $_GET['type']);
	switch($type) {
		case 1:
			$stitle = "Entreprises";
			$rq = " WHERE `select_type` LIKE 'Une entreprise'";
		break;
		case 2:
			$stitle = "Particuliers";
			$rq = " WHERE `select_type` LIKE 'Un particulier'";
		break;
		default:
			$stitle = "Total";
			$rq = "";
		break;
	}

	$period = 0;
	if (isset($_GET['period'])) {
		$period = mysqli_real_escape_string($conn, $_GET['period']);
	}


	$ring = $vegas = $miroir = $spinner = $vr = $event_type1 = $event_type2 = $event_type3 = $event_type4 = $event_type5 = $event_type6 = $event_type7 = $livraison = $retrait_boutique = $gratuit = $payant = $marque_blanche = $fond_vert = $assurance_degradation = $demandes = $reservations = $total = 0;
	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new`".$rq);
	while($row_orders = mysqli_fetch_assoc($result_orders)) {
		if ($period == 0 || ($period != 0 && strtotime($row_orders['event_date']) > time() - $period * 3600*24*30)) {
			if (strtolower(trim($row_orders['box_type'])) == "ring") $ring++;
			if (strtolower(trim($row_orders['box_type'])) == "vegas") $vegas++;
			if (strtolower(trim($row_orders['box_type'])) == "miroir") $miroir++;
			if (strtolower(trim($row_orders['box_type'])) == "spinner_360") $spinner++;
			if (strtolower(trim($row_orders['box_type'])) == "réalité_virtuelle") $vr++;
			if (strtolower(trim($row_orders['event_type'])) == "mariage") $event_type1++;
			if (strtolower(trim($row_orders['event_type'])) == "anniversaire") $event_type2++;
			if (strtolower(trim($row_orders['event_type'])) == "soirée privée") $event_type3++;
			if (strtolower(trim($row_orders['event_type'])) == "baby shower") $event_type4++;
			if (strtolower(trim($row_orders['event_type'])) == "gender reveal") $event_type5++;
			if (strtolower(trim($row_orders['event_type'])) == "evjf/evg") $event_type6++;
			if (strtolower(trim($row_orders['event_type'])) == "autre") $event_type7++;
			if (strtolower(trim($row_orders['event_type'])) == "autre") $event_type7++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'retrait boutique') !== false) $retrait_boutique++; else $livraison++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je fais réaliser sur-mesure') !== false) $payant++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je choisis sur catalogue') !== false) $gratuit++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'marque blanche') !== false) $marque_blanche++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'fond vert') !== false) $fond_vert++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'assurance dégradation') !== false) $assurance_degradation++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'double impression') !== false) $double_impression++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'pastilles magnétiques') !== false) $pastilles_magnetiques++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'pack déguisement') !== false) $pack_deguisement++;
			if ($row_orders['status'] == 0) {
				$demandes++;
			}
			if ($row_orders['status'] == 2) {
				$reservations++;
			}
			$total = $total + $row_orders['total'];
		}

	}
?>

<!-- begin panel -->
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
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Statistiques <?php echo $stitle ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal">
		<div class="form-group">
			<label class="col-md-3 control-label">Période, mois</label>
			<div class="col-md-1">
				<select class="form-control period">
					<?php
						for ($i = 0; $i <= 12; $i++) {
							echo'<option value="'.$i.'"'; if ($period == $i) {echo" selected";} echo'>'.($i == 0 ? 'Total': $i).'</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>Type de borne</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Ring</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $ring ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Vegas</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $vegas ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Miroir</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $miroir ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Spinner</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $spinner ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">VR</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $vr ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>Type d’événement</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Mariage</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type1 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Anniversaire</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type2 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Soirée privée</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type3 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Baby Shower</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type4 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Gender reveal</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type5 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">EVJF/EVG</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type6 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Autre</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type7 ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>Livraison</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Livraison</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $livraison ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Retrait boutique</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $retrait_boutique ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>OPTIONS souscrites :</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Template gratuit / payant</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $gratuit ?> / <?php echo $payant ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Marque blanche</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $marque_blanche ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Fond vert</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $fond_vert ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Assurance dégradation</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $assurance_degradation ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Double impression</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $double_impression ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Pastilles magnétiques</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $pastilles_magnetiques ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Pack déguisement</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $pack_deguisement ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>Demandes / Réservations fermes</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Demandes</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $demandes ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Réservations</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $reservations ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>La finance</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Chiffre d’affaires, €</label>
			<div class="col-md-1">
				<input type="text" class="form-control text-center" value= "<?php echo $total ?>" style="width: 75px; font-weight: bold;" />
			</div>
		</div>
	</form>
	</div>
</div>
<!-- end panel -->

<?php
	include("footer.php");
?>


<script src="assets\plugins\flot\jquery.flot.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.time.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.resize.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.pie.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.stack.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.crosshair.min.js"></script>
<script src="assets\plugins\flot\jquery.flot.categories.js"></script>
<script src="assets\js\apps.min.js"></script>

<script>

	$(document).ready(function() {

		App.init();

		$('.period').on('change', function() {
			window.location.href = 'statistics.php?type=<?php echo $type ?>&period=' + $(this).val();
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
                    borderWidth: 0
                }
            })

/*

    var r = [
                    [0, 42],
                    [1, 53],
                    [2, 66],
                    [3, 60],
                    [4, 68],
                    [5, 66],
                    [6, 71],
                    [7, 75],
                    [8, 69],
                    [9, 70],
                    [10, 68],
                    [11, 72],
                    [12, 78],
                    [13, 86]
                ],
                e = [
                    [0, 12],
                    [1, 26],
                    [2, 13],
                    [3, 18],
                    [4, 35],
                    [5, 23],
                    [6, 18],
                    [7, 35],
                    [8, 24],
                    [9, 14],
                    [10, 14],
                    [11, 29],
                    [12, 30],
                    [13, 43]
                ];
            $.plot($("#interactive-chart"), [{
                data: r,
                label: "Page Views",
                color: purple,
                lines: {
                    show: !0,
                    fill: !1,
                    lineWidth: 2
                },
                points: {
                    show: !1,
                    radius: 5,
                    fillColor: "#fff"
                },
                shadowSize: 0
            }, {
                data: e,
                label: "Visitors",
                color: green,
                lines: {
                    show: !0,
                    fill: !1,
                    lineWidth: 2,
                    fillColor: ""
                },
                points: {
                    show: !1,
                    radius: 3,
                    fillColor: "#fff"
                },
                shadowSize: 0
            }], {
                xaxis: {
                    tickColor: "#ddd",
                    tickSize: 2
                },
                yaxis: {
                    tickColor: "#ddd",
                    tickSize: 20
                },
                grid: {
                    hoverable: !0,
                    clickable: !0,
                    tickColor: "#ccc",
                    borderWidth: 1,
                    borderColor: "#ddd"
                },
                legend: {
                    labelBoxBorderColor: "#ddd",
                    margin: 0,
                    noColumns: 1,
                    show: !0
                }
            });
            var t = null;
            $("#interactive-chart").bind("plothover", function(r, e, o) {
                if ($("#x").text(e.x.toFixed(2)), $("#y").text(e.y.toFixed(2)), o) {
                    if (t !== o.dataIndex) {
                        t = o.dataIndex, $("#tooltip").remove();
                        var l = o.datapoint[1].toFixed(2),
                            i = o.series.label + " " + l;
                        a(o.pageX, o.pageY, i)
                    }
                } else $("#tooltip").remove(), t = null;
                r.preventDefault()
            })*/

	});
</script>

<?php include("end.php"); ?>