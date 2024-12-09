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
			$rq = " WHERE `status` = 2 AND `select_type` LIKE '%entreprise%'";
			$rq2 = " WHERE `select_type` LIKE '%entreprise%'";
		break;
		case 2:
			$stitle = "Particuliers";
			$rq = " WHERE `status` = 2 AND `select_type` LIKE '%particulier%'";
			$rq2 = " WHERE `select_type` LIKE '%particulier%'";
		break;
		default:
			$stitle = "Total";
			$rq = " WHERE `status` = 2";
			$rq2 = "";
		break;
	}

  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", time());
  }

  if (isset($_GET['agency_id']) && $_GET['agency_id'] != 0) {
    if ($rq != "") {
      $rq .= " AND `agency_id` = ".$_GET['agency_id'];
    } else {
      $rq = " WHERE `agency_id` = ".$_GET['agency_id'];
    }
  }

  $old_id = 0;
	$ring = $vegas = $miroir = $spinner = $vr = $event_type1 = $event_type2 = $event_type3 = $event_type4 = $event_type5 = $event_type6 = $event_type7 = $livraison = $livraison2 = $livraison3 = $retrait_boutique = $double_vegas = $total_double_vegas = $gratuit = $total_gratuit = $payant = $total_payant = $marque_blanche = $total_marque_blanche = $sur_mesure = $assurance_degradation = $total_assurance_degradation = $multi_impression = $total_multi_impression = $pastilles_magnetiques = $total_pastilles_magnetiques = $pack_deguisement = $total_pack_deguisement = $tech = $total_tech = $bobine = $total_bobine = $heure_sup = $total_heure_sup = $operateur = $total_operateur = $flocage = $total_flocage = $accessoires_selfie = $total_accessoires_selfie = $demandes = $reservations = $refuse = $refuse_0 = $refuse_1 = $refuse_2 = $refuse_3 = $refuse_4 = $refuse_5 = $refuse_6 = $refuse_7 = $refuse_8 = $refuse_9 = $refuse_10 = $refuse_11 = $refuse_12 = $refuse_13 = $refuse_14 = $total = $total_ring = $total_vegas = $total_miroir = $total_spinner = $total_vr = $total_retrait_boutique = $total_livraison = $total_livraison2 = $total_livraison3 = $total_livraison_sb = $total_livraison2_sb = $total_livraison3_sb = $total_livraison_lv = $total_livraison2_lv = $total_livraison3_lv = $invites = $gclid = $marriage = $km = $total_km = $km_sb = $km_lv = 0;
	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new`".$rq);
	while($row_orders = mysqli_fetch_assoc($result_orders)) {
    $selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))));
    $delivery_options_value_arr = explode(",", $row_orders['delivery_options']);
    $row_orders_total = str_replace(",", ".", $row_orders['total']);
    if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
      $row_orders_total = $row_orders_total + $row_orders_total*0.2;
    }
		if (((!isset($_GET['search_type']) || $_GET['search_type'] == 0) && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date))  || ($_GET['search_type'] == 1 && $row_orders['created_at'] >= strtotime($start_date." 00:00:00") && $row_orders['created_at'] <= strtotime($end_date." 23:59:59"))) {
			if (strtolower(trim($row_orders['box_type'])) == "ring") {$ring = $ring + $row_orders['amount']; $total_ring = $total_ring + $row_orders_total;}
			if (strtolower(trim($row_orders['box_type'])) == "vegas") {$vegas = $vegas + $row_orders['amount']; $total_vegas = $total_vegas + $row_orders_total;}
			if (strtolower(trim($row_orders['box_type'])) == "miroir") {$miroir = $miroir + $row_orders['amount']; $total_miroir = $total_miroir + $row_orders_total;}
			if (strtolower(trim($row_orders['box_type'])) == "spinner_360") {$spinner = $spinner + $row_orders['amount']; $total_spinner = $total_spinner + $row_orders_total;}
			if (strtolower(trim($row_orders['box_type'])) == "réalité_virtuelle") {$vr = $vr + $row_orders['amount']; $total_vr = $total_vr + $row_orders_total;}
			if (strtolower(trim($row_orders['event_type'])) == "mariage") $event_type1++;
			if (strtolower(trim($row_orders['event_type'])) == "anniversaire") $event_type2++;
			if (strtolower(trim($row_orders['event_type'])) == "soirée privée") $event_type3++;
			if (strtolower(trim($row_orders['event_type'])) == "baby shower") $event_type4++;
			if (strtolower(trim($row_orders['event_type'])) == "gender reveal") $event_type5++;
			if (strtolower(trim($row_orders['event_type'])) == "evjf/evg") $event_type6++;
			if (strtolower(trim($row_orders['event_type'])) == "autre") $event_type7++;

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'retrait boutique') !== false || $row_orders['delivery_options'] == "") {
        $retrait_boutique++;
        foreach ($delivery_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'retrait boutique') !== false) {
            $total_retrait_boutique = $total_retrait_boutique + $options_arr[2];
          }
        }
      } elseif (mb_strpos(strtolower(trim($row_orders['delivery_options'])), 'installation') !== false) {
        foreach ($delivery_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          //echo $row_orders['id']." - ".$options_arr[0]."<br />";
          if (strtolower(trim($options_arr[0])) != '' && $row_orders['id'] != $old_id) {
            //echo $row_orders['id']." - ".$options_arr[0]."<br />";
            $livraison2 += $options_arr[1];
            $total_livraison2 = $total_livraison2 + $options_arr[1] * $options_arr[2];

            if ($row_orders['delivery_price'] != "") {
              $delivery_price_arr = explode("/", $row_orders['delivery_price']);
            }

            if ($row_orders['courier'] == "Shoontbox" || $row_orders['courier_r'] == "Shoontbox") {
              $total_livraison2_sb = $total_livraison2_sb + (isset($delivery_price_arr[0]) ? $delivery_price_arr[0] : 0);
            }

            if ($row_orders['courier'] == "Livreur" || $row_orders['courier_r'] == "Livreur") {
              $total_livraison2_lv = $total_livraison2_lv + (isset($delivery_price_arr[0]) ? $delivery_price_arr[0] : 0);
            }
          }
          $old_id = $row_orders['id'];
        }
      } elseif (mb_strpos(strtolower(trim($row_orders['delivery_options'])), 'premium') !== false) {
        foreach ($delivery_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          //echo $row_orders['id']." - ".$options_arr[0]."<br />";
          if (strtolower(trim($options_arr[0])) != '' && $row_orders['id'] != $old_id) {
            //echo $row_orders['id']." - ".$options_arr[0]."<br />";
            $livraison3 += $options_arr[1];
            $total_livraison3 = $total_livraison3 + $options_arr[1] * $options_arr[2];

            if ($row_orders['delivery_price'] != "") {
              $delivery_price_arr = explode("/", $row_orders['delivery_price']);
            }

            if ($row_orders['courier'] == "Shoontbox" || $row_orders['courier_r'] == "Shoontbox") {
              $total_livraison3_sb = $total_livraison3_sb + (isset($delivery_price_arr[0]) ? $delivery_price_arr[0] : 0);
            }

             if ($row_orders['courier'] == "Livreur" || $row_orders['courier_r'] == "Livreur") {
              $total_livraison3_lv = $total_livraison3_lv + (isset($delivery_price_arr[0]) ? $delivery_price_arr[0] : 0);
            }
          }
          $old_id = $row_orders['id'];
        }
      } else {
        foreach ($delivery_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          //echo $row_orders['id']." - ".$options_arr[0]."<br />";
          if (strtolower(trim($options_arr[0])) != '' && $row_orders['id'] != $old_id) {
            //echo $row_orders['id']." - ".$options_arr[0]." - ".$options_arr[1]."<br />";
            $livraison += $options_arr[1];
            $total_livraison = $total_livraison + $options_arr[1] * $options_arr[2];

            if ($row_orders['delivery_price'] != "") {
              $delivery_price_arr = explode("/", $row_orders['delivery_price']);
            }

            if ($row_orders['courier'] == "Shoontbox" || $row_orders['courier_r'] == "Shoontbox") {
              $total_livraison_sb = $total_livraison_sb + (isset($delivery_price_arr[0]) ? $delivery_price_arr[0] : 0);
            }

            if ($row_orders['courier'] == "Livreur" || $row_orders['courier_r'] == "Livreur") {
              $total_livraison2_lv = $total_livraison2_lv + (isset($delivery_price_arr[0]) ? $delivery_price_arr[0] : 0);
            }
            $old_id = $row_orders['id'];
          }
        }
      }


      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je fais réaliser sur-mesure') !== false || (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'contour personnalisé')) !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'je fais réaliser sur-mesure') !== false || (mb_strpos(strtolower(trim($options_arr[0])), 'contour personnalisé')) !== false) {
            $payant += $options_arr[1];
            $total_payant = $total_payant + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if ($type != 1) {
				if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je choisis sur catalogue') !== false) {
          foreach ($selected_options_value_arr as $key => $value) {
            $options_arr = explode(":", $value);
            if (mb_strpos(strtolower(trim($options_arr[0])), 'je choisis sur catalogue') !== false) {
              $gratuit += $options_arr[1];
              $total_gratuit = $total_gratuit + $options_arr[1] * $options_arr[2];
            }
          }
        }
			} else {
				$gratuit++;
			}
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'double vegas') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'double vegas') !== false) {
            $double_vegas += $options_arr[1];
            $total_double_vegas = $total_double_vegas + $options_arr[1] * $options_arr[2];
          }
        }
      }
			//if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je choisis sur catalogue') !== false) { $gratuit++; $total_gratuit = $total_gratuit + $row_orders_total; }
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'marque blanche') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'marque blanche') !== false) {
            $marque_blanche += $options_arr[1];
            $total_marque_blanche = $total_marque_blanche + $options_arr[1] * $options_arr[2];
          }
        }
      }
			//if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'je fais realiser sur mesure') !== false) $sur_mesure++;
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'assurance dégradation') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'assurance dégradation') !== false) {
            $assurance_degradation += $options_arr[1];
            $total_assurance_degradation = $total_assurance_degradation + $options_arr[1] * $options_arr[2];
          }
        }
      }
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'impression') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'impression') !== false) {
            $multi_impression += $options_arr[1];
            $total_multi_impression = $total_multi_impression + $options_arr[1] * $options_arr[2];
          }
        }
      }
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'pastilles magnétiques') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'pastilles magnétiques') !== false) {
            $pastilles_magnetiques += $options_arr[1];
            $total_pastilles_magnetiques = $total_pastilles_magnetiques + $options_arr[1] * $options_arr[2];
          }
        }
      }
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'pack déguisement') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'pack déguisement') !== false) {
            $pack_deguisement += $options_arr[1];
            $total_pack_deguisement = $total_pack_deguisement + $options_arr[1] * $options_arr[2];
          }
        }
      }
			if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'technicien présent') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'technicien présent') !== false) {
            $tech += $options_arr[1];
            $total_tech = $total_tech + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'bobine') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'bobine') !== false) {
            $bobine += $options_arr[1];
            $total_bobine = $total_bobine + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'heure sup') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'heure sup') !== false) {
            $heure_sup += $options_arr[1];
            $total_heure_sup = $total_heure_sup + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'opérateur') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'opérateur') !== false) {
            $operateur += $options_arr[1];
            $total_operateur = $total_operateur + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'flocage') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'flocage') !== false) {
            $flocage += $options_arr[1];
            $total_flocage = $total_flocage + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'kilomètres supplémentaires') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'kilomètres supplémentaires') !== false) {
            $km += $options_arr[1];
            $total_km = $total_km + $options_arr[1] * $options_arr[2];

            if ($row_orders['delivery_price'] != "") {
              $delivery_price_arr = explode("/", $row_orders['delivery_price']);
            }

            if ($row_orders['courier'] == "Shoontbox" || $row_orders['courier_r'] == "Shoontbox") {
              $km_sb = $km_sb + (isset($delivery_price_arr[1]) ? $delivery_price_arr[1] : 0);
            }

            if ($row_orders['courier'] == "Livreur" || $row_orders['courier_r'] == "Livreur") {
              $km_lv = $km_lv + (isset($delivery_price_arr[1]) ? $delivery_price_arr[1] : 0);
            }

          }
        }
      }

      if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'accessoires selfie') !== false) {
        foreach ($selected_options_value_arr as $key => $value) {
          $options_arr = explode(":", $value);
          if (mb_strpos(strtolower(trim($options_arr[0])), 'accessoires selfie') !== false) {
            $accessoires_selfie += $options_arr[1];
            $total_accessoires_selfie = $total_accessoires_selfie + $options_arr[1] * $options_arr[2];
          }
        }
      }

      if ($row_orders['status'] == 2) {
				$reservations++;
			}
			$total = $total + $row_orders_total;

      if ($row_orders['invite'] == 1 && $row_orders['gclid'] == "") { $invites++; }
      if ($row_orders['marriage'] == 1 && $row_orders['gclid'] == "") { $marriage++; }
      if ($row_orders['gclid'] != "") { $gclid++; }
		}
	}

	$result_orders2 = mysqli_query($conn, "SELECT * FROM `orders_new`".$rq2);
	while($row_orders2 = mysqli_fetch_assoc($result_orders2)) {
		if (((!isset($_GET['search_type']) || $_GET['search_type'] == 0) && strtotime($row_orders2['event_date']) >= strtotime($start_date) && strtotime($row_orders2['event_date']) <= strtotime($end_date)) || ($_GET['search_type'] == 1 && $row_orders2['created_at'] >= strtotime($start_date) && $row_orders2['created_at'] <= strtotime($end_date))) {
			if ($row_orders2['status'] == 0) {
				$demandes++;
			}
      if ($row_orders2['status'] == -1) {
        $refuse++;

        switch($row_orders2['refuse_title']) {
          case 'Trop cher': $refuse_1++; break;
          case 'Livraison cher': $refuse_2++; break;
          case 'Impos de retrait': $refuse_3++; break;
          case 'Ne repond pas': $refuse_4++; break;
          case 'Presta annulé': $refuse_5++; break;
          case 'KM sup cher': $refuse_6++; break;
          case 'Orga. Event': $refuse_7++; break;
          case 'Aix et marseille': $refuse_8++; break;
          case 'Brest': $refuse_9++; break;
          case 'Centre de france': $refuse_10++; break;
          case 'Lille': $refuse_11++; break;
          case 'Lyon': $refuse_12++; break;
          case 'Strasbourg': $refuse_13++; break;
          case 'Toulouse': $refuse_14++; break;
          default:
            $refuse_0++;
          break;
        }
      }
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
		<h4 class="panel-title">Statistiques <?php echo $stitle ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal">
    <div class="form-group">
      <label class="col-md-1 control-label">Agence</label>
      <div class="col-md-1">
        <select class="form-control agency_id">
          <option value="0"<?php if (!isset($_GET['agency_id']) || $_GET['agency_id'] == 0) echo" selected" ?>>Toutes</option>
          <option value="1"<?php if (isset($_GET['agency_id']) && $_GET['agency_id'] == 1) echo" selected" ?>>Paris</option>
          <option value="2"<?php if (isset($_GET['agency_id']) && $_GET['agency_id'] == 2) echo" selected" ?>>Bordeaux</option>
        </select>
      </div>

      <div class="col-md-2">
            <select class="form-control search_type">
              <option value="0"<?php if (!isset($_GET['search_type']) || $_GET['search_type'] == 0) {echo" selected";} ?>>Date évenement</option>
              <option value="1"<?php if (isset($_GET['search_type']) && $_GET['search_type'] == 1) {echo" selected";} ?>>Date demande</option>
            </select>
      </div>

       <label class="col-md-2 control-label">Période</label>
        <div class="col-md-2">
          <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
        </div>
        <div class="col-md-2">
          <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
        </div>
        <div class="col-md-2">
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
		  <div class="col-md-6">
		    <div class="panel panel-inverse" data-sortable-id="index-1">
		      <div class="panel-heading">
		        <div class="panel-heading-btn">
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
		        </div>
		        <h4 class="panel-title">Type d’événement</h4>
		      </div>
		      <div class="panel-body">
		        <div id="bar-chart2" class="height-sm"></div>
		      </div>
		    </div>
		  </div>
		</div>

		<!--div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Ring</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $ring ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Vegas</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $vegas ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Miroir</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $miroir ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Spinner</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $spinner ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">VR</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $vr ?>" style="width: 100px; font-weight: bold;" />
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
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type1 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Anniversaire</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type2 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Soirée privée</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type3 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Baby Shower</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type4 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Gender reveal</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type5 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">EVJF/EVG</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type6 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Autre</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $event_type7 ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div-->
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>Livraison</h3>
			</div>
      <div class="col-md-4">
        <h3>Shootnbox Livreur</h3>
      </div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Livraison</label>
			<div class="col-md-4">
				<input type="text" class="form-control text-center" value= "<?php echo $livraison ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_livraison ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
      <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $total_livraison_sb ?>€" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_livraison_lv ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
		</div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Livraison + Installation</label>
      <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $livraison2 ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_livraison2 ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
       <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $total_livraison2_sb ?>€" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_livraison2_lv ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Livraison Premium</label>
      <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $livraison3 ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_livraison3 ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
       <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $total_livraison3_sb ?>€" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_livraison3_lv ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Kilomètres supplémentaires</label>
      <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $km ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_km ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control text-center" value= "<?php echo $km_sb ?>€" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $km_lv ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Retrait boutique</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $retrait_boutique ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_retrait_boutique ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>
    <div class="form-group">
      <label class="col-md-3 control-label text-danger" style="font-weight: bold;">Total</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo ($livraison + $livraison2  + $livraison3 + $retrait_boutique + $km) ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo ($total_livraison + $total_livraison2 + $total_livraison3 + $total_retrait_boutique + $total_km) ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-4">
				<h3>OPTIONS souscrites :</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Template gratuit</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $gratuit ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_gratuit ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Template payant</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo $payant ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_payant ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Double Vegas</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $double_vegas ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_double_vegas ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Marque blanche</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $marque_blanche ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_marque_blanche ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>
		<!--div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Je fais realiser sur mesure</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $sur_mesure ?>" style="width: 100px; font-weight: bold;" />
			</div>
		</div-->
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Assurance dégradation</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $assurance_degradation ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_assurance_degradation ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Multi impression</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $multi_impression ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_multi_impression ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Pastilles magnétiques</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $pastilles_magnetiques ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_pastilles_magnetiques ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Pack déguisement</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $pack_deguisement ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_pack_deguisement ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Technicien présent</label>
			<div class="col-md-5">
				<input type="text" class="form-control text-center" value= "<?php echo $tech ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_tech ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
			</div>
		</div>

    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Bobine</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo $bobine ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_bobine ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Heure sup</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo $heure_sup ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_heure_sup ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Opérateur</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo $operateur ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_operateur ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Flocage</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo $flocage ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_flocage ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Accessoires Selfie</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo $accessoires_selfie ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo $total_accessoires_selfie ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label text-danger" style="font-weight: bold;">Total</label>
      <div class="col-md-5">
        <input type="text" class="form-control text-center" value= "<?php echo ($gratui + $payant + $double_vegas + $marque_blanche + $assurance_degradation + $multi_impression + $pastilles_magnetiques + $pack_deguisement + $tech + $bobine + $heure_sup + $operateur + $flocage + $accessoires_selfie) ?>" style="width: 100px; font-weight: bold; display: inline-block;" /> - <input type="text" class="form-control text-center" value= "<?php echo ($total_gratui + $total_payant + $total_double_vegas + $total_marque_blanche + $total_assurance_degradation + $total_multi_impression + $total_pastilles_magnetiques + $total_pack_deguisement + $total_tech + $total_bobine + $total_heure_sup + $total_operateur + $total_flocage + $total_accessoires_selfie) ?>€" style="width: 100px; font-weight: bold; display: inline-block;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-4">
        <h3>Provenance</h3>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Bouche à l'oreille</label>
      <div class="col-md-2">
        <input type="text" class="form-control text-center" value= "<?php echo $invites; ?>" style="width: 100px; font-weight: bold;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">Mariage</label>
      <div class="col-md-2">
        <input type="text" class="form-control text-center" value= "<?php echo $marriage; ?>" style="width: 100px; font-weight: bold;" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label" style="font-weight: bold;">ADS</label>
      <div class="col-md-2">
        <input type="text" class="form-control text-center" value= "<?php echo $gclid; ?>" style="width: 100px; font-weight: bold;" />
      </div>
    </div>
    <!--?php
      $result_utm = mysqli_query($conn, "SELECT DISTINCT `utm_source` FROM `orders_new` ".$rq." AND `utm_source` IS NOT NULL");
      while($row_utm = mysqli_fetch_assoc($result_utm)) {
        $result_total_utm = mysqli_query($conn, "SELECT `id` FROM `orders_new` ".$rq." AND `utm_source` = '".$row_utm['utm_source']."'");
        echo'<div class="form-group">
          <label class="col-md-3 control-label" style="font-weight: bold;">'.$row_utm['utm_source'].'</label>
          <div class="col-md-2">
            <input type="text" class="form-control text-center" value= "'.mysqli_num_rows($result_total_utm).'" style="width: 100px; font-weight: bold;" />
          </div>
        </div>';
      }
    ?-->
    <div class="form-group">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-9">
        <h3>Demandes refusées</h3>
      </div>
    </div>
    <div class="row">
      <label class="col-md-3 control-label"></label>
      <div class="col-md-9">
        <div class="panel panel-inverse" data-sortable-id="index-1">
          <div class="panel-heading">
            <div class="panel-heading-btn">
              <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
              <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
              <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
              <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
            </div>
            <h4 class="panel-title">Demandes refusées</h4>
          </div>
          <div class="panel-body">
            <div id="bar-chart4" class="height-sm"></div>
          </div>
        </div>
      </div>
    </div>
		<div class="form-group">
			<label class="col-md-3 control-label"></label>
			<div class="col-md-9">
				<h3>Demandes / Refusées / Réservations fermes</h3>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label" style="font-weight: bold;">Demandes / Refusées / Réservations</label>
			<div class="col-md-3">
				<input type="text" class="form-control text-center" value= "<?php echo $demandes ?> / <?php echo $refuse ?> / <?php echo $reservations ?>" style="width: 125px; font-weight: bold;" />
			</div>
      <div class="col-md-3">
        <div id="bar-chart3" class="height-sm"></div>
      </div>
		</div>
		<div class="form-group hidden">
			<label class="col-md-3 control-label" style="font-weight: bold;">Réservations</label>
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo $reservations ?>" style="width: 100px; font-weight: bold;" />
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
			<div class="col-md-2">
				<input type="text" class="form-control text-center" value= "<?php echo number_format($total, 2, '.', '') ?>" style="width: 100px; font-weight: bold;" />
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
        window.location.href = 'statistics.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&search_type=' + $('.search_type').val();
      }
    });

    $('.agency_id').on('change', function(event) {
      event.preventDefault();
      if ($('.agency_id').val() != 0) {
        if ($('.start_date').val() != '' && $('.end_date').val() != '') {
          window.location.href = 'statistics.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&agency_id=' + $('.agency_id').val() + '&search_type=' + $('.search_type').val();
        } else {
          window.location.href = 'statistics.php?type=<?php echo $type ?>&agency_id=' + $('.agency_id').val() + '&search_type=' + $('.search_type').val();
        }
      } else {
        if ($('.start_date').val() != '' && $('.end_date').val() != '') {
          window.location.href = 'statistics.php?type=<?php echo $type ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&search_type=' + $('.search_type').val();
        } else {
          window.location.href = 'statistics.php?type=<?php echo $type ?>';
        }
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
     	{data: [["Ring", <?php echo $ring ?>]], label: "<?php echo number_format($total_ring, 2, '.', '') ?>€", color: blue},
     	{data: [["Vegas", <?php echo $vegas ?>]], label: "<?php echo number_format($total_vegas, 2, '.', '') ?>€", color: aqua},
     	{data: [["Miroir", <?php echo $miroir ?>]], label: "<?php echo number_format($total_miroir, 2, '.', '') ?>€", color: green},
      {data: [["Spinner", <?php echo $spinner ?>]], label: "<?php echo number_format($total_spinner, 2, '.', '') ?>€", color: orange},
      {data: [["VR", <?php echo $vr ?>]], label: "<?php echo number_format($total_vr, 2, '.', '') ?>€", color: purple}
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


		$("#bar-chart").on("plothover", function (event, pos, item) {
		    if (item) {
		        if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
		            previousPoint = item.dataIndex;
		            previousLabel = item.series.label;
		            $("#tooltip").remove();

		            var x = item.datapoint[0];
		            var y = item.datapoint[1];

		            var color = item.series.color;

		            console.log(item.series.xaxis.ticks[x].label);

		            showTooltip(item.pageX,
		            item.pageY,
		            color,
		            "<strong>" + item.series.xaxis.ticks[x].label + "</strong><br />Total : <strong>" + item.series.label + "</strong>");
		        }
		    } else {
		        $("#tooltip").remove();
		        previousPoint = null;
		    }
		});


    var data2 = [
     	{data: [["Mariage", <?php echo $event_type1 ?>]], color: blue},
     	{data: [["Anniversaire", <?php echo $event_type2 ?>]], color: aqua},
     	{data: [["Soirée privée", <?php echo $event_type3 ?>]], color: green},
      {data: [["Baby Shower", <?php echo $event_type4 ?>]], color: orange},
      {data: [["Gender reveal", <?php echo $event_type5 ?>]], color: purple},
      {data: [["EVJF/EVG", <?php echo $event_type6 ?>]], color: blueDark},
      {data: [["Autre", <?php echo $event_type7 ?>]], color: greenDark}
    ];

    $.plot("#bar-chart2", data2, {
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
    });

    var data3 = [
      {data: [["Demandes", <?php echo $demandes ?>]], label: "Demandes - <?php echo $demandes ?>", color: blue},
      {data: [["Demandes refusées", <?php echo $refuse ?>]], label: "Demandes refusées - <?php echo $refuse ?>", color: purple},
      {data: [["Réservations", <?php echo $reservations ?>]], label: "Réservations - <?php echo $reservations ?>", color: orange},
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
      {data: [["Trop cher", <?php echo $refuse_1 ?>]], color: blue},
      {data: [["Livraison cher", <?php echo $refuse_2 ?>]], color: aqua},
      {data: [["Impos de retrait", <?php echo $refuse_3 ?>]], color: green},
      {data: [["Ne repond pas", <?php echo $refuse_4 ?>]], color: orange},
      {data: [["Presta annulé", <?php echo $refuse_5 ?>]], color: purple},
      {data: [["KM sup cher", <?php echo $refuse_6 ?>]], color: blueDark},
      {data: [["Orga. Event", <?php echo $refuse_7 ?>]], color: greenDark},
      {data: [["Aix et marseille", <?php echo $refuse_8 ?>]], color: blueLight},
      {data: [["Brest", <?php echo $refuse_9 ?>]], color: aquaLight},
      {data: [["Centre de francer", <?php echo $refuse_10 ?>]], color: greenLight},
      {data: [["Lille", <?php echo $refuse_11 ?>]], color: orangeDark},
      {data: [["Lyon", <?php echo $refuse_12 ?>]], color: purpleDark},
      {data: [["Strasbourg", <?php echo $refuse_13 ?>]], color: orangeLight},
      {data: [["Toulouse", <?php echo $refuse_14 ?>]], color: purpleLight},
      {data: [["Non indiqué", <?php echo $refuse_0 ?>]], color: red}
    ];

    $.plot("#bar-chart4", data4, {
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
    });

	});
</script>

<?php include("end.php"); ?>