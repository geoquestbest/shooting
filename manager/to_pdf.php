<?php
@require_once("../inc/mainfile.php");
@require_once("fpdf/fpdf.php");

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
$row_orders = mysqli_fetch_assoc($result_orders);

if (isset($_GET['devis'])) {
  $row_orders['status'] = 0;
  $row_orders['num_id'] = "DE".$_GET['devis'];
}

switch ($row_orders['agency_id']) {
        case 1: $agency = " Montreuil"; break;
        case 2: $agency = " Bordeaux"; break;
        default:
          $agency = "";
        break;
      }

if (strpos($row_orders['select_type'], 'entreprise') !== false) {
  $data = json_decode(file_get_contents("../enterprise.ini"), true);
  $prices_arr = json_decode(file_get_contents("../enterprise_price.ini"), true);
  switch($row_orders['box_type']) {
    case "Ring":
    case "Ring_promotionnel":
      $options = $data['ring2']['options'];
      $deliverys = $data['ring2']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_ring'");
      $price = $prices_arr['ring_price'];
    break;
    case "Vegas":
    case "Vegas_800":
    case "Vegas_1200":
    case "Vegas_1600":
    case "Vegas_2000":
      $options = $data['vegas']['options'];
      $deliverys = $data['vegas']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vegas".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")."'");
      $price = $prices_arr['vegas_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")];
    break;
    case "Miroir":
    case "Miroir_800":
    case "Miroir_1200":
    case "Miroir_1600":
    case "Miroir_2000":
      $options = $data['miroir']['options']; $deliverys = $data['miroir']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_miroir".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")."'");
      $price = $prices_arr['miroir_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")];
    break;
    case "Spinner_360":
      $options = $data['spinner']['options'];
      $deliverys = $data['spinner']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_spinner'");
      $price = $prices_arr['spinner_price'];
    break;
    case "Réalité_Virtuelle":
      $options = $data['vr2']['options'];
      $deliverys = $data['vr2']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vr'");
      $price = $prices_arr['vr_price'];
    break;
  }
} else {
  $data = json_decode(file_get_contents("../particulier.ini"), true);
  $prices_arr = json_decode(file_get_contents("../particulier_price.ini"), true);
  switch($row_orders['box_type']) {
    case "Ring":
    case "Ring_promotionnel":
      $options = $data['ring']['options'];
      $deliverys = $data['ring']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_ring'");
      $price = $prices_arr['ring_price'];
    break;
    case "Vegas":
    case "Vegas_800":
    case "Vegas_1200":
    case "Vegas_1600":
    case "Vegas_2000":
      $options = $data['vegas']['options'];
      $deliverys = $data['vegas']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vegas".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")."'");
      $price = $prices_arr['vegas_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")];
    break;
    case "Miroir":
    case "Miroir_800":
    case "Miroir_1200":
    case "Miroir_1600":
    case "Miroir_2000":
      $options = $data['miroir']['options']; $deliverys = $data['miroir']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_miroir".(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")."'");
      $price = $prices_arr['miroir_price'.(strpos($row_orders['box_type'], "800") === false ? "" : "_2").(strpos($row_orders['box_type'], "1200") === false ? "" : "_1200").(strpos($row_orders['box_type'], "1600") === false ? "" : "_1600").(strpos($row_orders['box_type'], "2000") === false ? "" : "_2000")];
    break;
    case "Spinner_360":
      $options = $data['spinner']['options'];
      $deliverys = $data['spinner']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_spinner'");
      $price = $prices_arr['spinner_price'];
    break;
    case "Réalité_Virtuelle":
      $options = $data['vr2']['options'];
      $deliverys = $data['vr2']['delivery'];
      $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vr'");
      $price = $prices_arr['vr_price'];
    break;
  }
}

if ($row_orders['price'] == 0 && $row_orders['price'] != "") {
  $price = 0;
} else {
  $price = $row_orders['price'];
}

$deliverys[] = array('name' => 'Kilométriques supplémentaires', 'price' => 49);
$row_description = mysqli_fetch_assoc($result_description);

$selected_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))))));
$selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))));

$delivery_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', $row_orders['delivery_options'])));
$delivery_options_value_arr = explode(",", $row_orders['delivery_options']);

$step = 5; $top = 70; $idx = 1;
$result_bornes = mysqli_query($conn, "SELECT * FROM `bornes` WHERE `order_id` = ".$order_id);
$idx = mysqli_num_rows($result_bornes);
if (mysqli_num_rows($result_bornes) > 0) {
  //$step = 5 - $idx/1.5;
  //$top = 75 - $idx * 10;
}
class PDF extends FPDF
{
  // Page header
  function Header()
  {

  }

  function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

  // Page footer
  function Footer()
  {

  }
}


// Instanciation of inherited class
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->Image('logo.png', 10, $step, 75, 13);

$pdf->SetY(7);
$pdf->SetX(90);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(255, 18, 160);

$pdf->RoundedRect(90, 7, 37, 5, 2, '12', 'DF');
if (!isset($_GET['avoir'])) {
  $pdf->Cell(37, $step, mb_convert_encoding(($row_orders['status'] == 0 ? 'Devis' : 'Facture').' N°', 'windows-1252'), 0, 0, 'C', 0);
} else {
  //$pdf->Cell(37, $step, mb_convert_encoding('Avoir N°', 'windows-1252'), 0, 0, 'C', 0);
  $pdf->Cell(37, $step, mb_convert_encoding($_GET['avoir'], 'windows-1252'), 0, 0, 'C', 0);
}

$pdf->RoundedRect(90 + 37, 7, 37, 5, 2, '12', 'DF');
$pdf->Cell(37, $step, mb_convert_encoding('Date', 'windows-1252'), 0, 0, 'C', 0);

$pdf->RoundedRect(90 + 37*2, 7, 37, 5, 2, '12', 'DF');
$pdf->Cell(37, $step, mb_convert_encoding('Client', 'windows-1252'), 0, 1, 'C', 0);

$pdf->SetX(90);

$pdf->RoundedRect(90, 12, 37, 5, 2, '34', 'DF');
$pdf->RoundedRect(90 + 37, 12, 37, 5, 2, '34', 'DF');
if (!isset($_GET['avoir'])) {
  $pdf->Cell(37, $step, mb_convert_encoding($row_orders['num_id'], 'windows-1252'), 0, 0, 'C', 0);
  $pdf->Cell(37, $step, mb_convert_encoding(date("d/m/Y", ($row_orders['updated_at'] != 0 ? $row_orders['updated_at'] : $row_orders['created_at'])), 'windows-1252'), 0, 0, 'C', 0);
} else {
  //$pdf->Cell(37, $step, mb_convert_encoding($_GET['avoir'], 'windows-1252'), 0, 0, 'C', 0);
  $pdf->Cell(37, $step, mb_convert_encoding($row_orders['num_id'], 'windows-1252'), 0, 0, 'C', 0);
  $pdf->Cell(37, $step, mb_convert_encoding(date("d/m/Y", ($row_orders['updated_at'] != 0 ? $row_orders['updated_at'] : $row_orders['created_at'])), 'windows-1252'), 0, 0, 'C', 0);
}

$pdf->RoundedRect(90 + 37*2, 12, 37, 5, 2, '34', 'DF');
$pdf->Cell(37, $step, mb_convert_encoding($row_orders['id'], 'windows-1252'), 0, 1, 'C', 0);
$pdf->Ln(10);


$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(80, $step, mb_convert_encoding('SAS BY AMAZING EVENT', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(80, $step, mb_convert_encoding('3 SENTIER DES MARÉCAGES', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(80, $step, mb_convert_encoding('93100 MONTREUIL', 'windows-1252'), 0, 1, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, $step, mb_convert_encoding('Tél', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, $step, mb_convert_encoding(':   01 45 01 66 66', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, $step, mb_convert_encoding('Capital', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, $step, mb_convert_encoding(':   15 000,00 Euros', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, $step, mb_convert_encoding('R.C.S.', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, $step, mb_convert_encoding(':   849542469', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, $step, mb_convert_encoding('SIRET', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, $step, mb_convert_encoding(':   84954246900044', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, $step, mb_convert_encoding('N/Id CEE', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, $step, mb_convert_encoding(':   FR46849542469', 'windows-1252'), 0, 1, 'L', 1);


$pdf->Rect(95, 33 - $step*$idx, 100, 30 - $step*$idx);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(94, 38 - $step*$idx, 101, 21 - $step*$idx, 'F');
$pdf->Rect(100, 32 - $step*$idx, 90, 32 - $step*$idx, 'F');

$pdf->SetY(35 - $step*$idx);
$pdf->SetFont('Arial', 'B', 8);

if ($row_orders['status'] == 2) {
  if ($row_orders['entreprise_pdf'] == "") {
    if ($row_orders['societe'] != "") {
      $pdf->SetX(100);
      $pdf->Cell(100, 4, mb_convert_encoding(htmlspecialchars_decode($row_orders['societe'], ENT_QUOTES), 'windows-1252'), 0, 1, 'L', 0);
    }
    if ($row_orders['last_name'] != "" || $row_orders['first_name'] != "") {
      $pdf->SetX(100);
      $pdf->Cell(100, 4, mb_convert_encoding(htmlspecialchars_decode($row_orders['last_name'], ENT_QUOTES)." ".htmlspecialchars_decode($row_orders['first_name'], ENT_QUOTES), 'windows-1252'), 0, 1, 'L', 0);
    }
  } else {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['entreprise_pdf'], 'windows-1252'), 0, 1, 'L', 0);
  }

  if ($row_orders['address_pdf'] == "") {
    if ($row_orders['address'] != "") {
      $pdf->SetX(100);
      $pdf->Cell(100, 4, mb_convert_encoding(htmlspecialchars_decode($row_orders['address'], ENT_QUOTES), 'windows-1252'), 0, 1, 'L', 0);
    }
    if ($row_orders['city'] != "" || $row_orders['cp'] != "") {
      $pdf->SetX(100);
      $pdf->Cell(100, 4, mb_convert_encoding($row_orders['cp'].", ".$row_orders['city'], 'windows-1252'), 0, 1, 'L', 0);
    }
  } else {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding(htmlspecialchars_decode($row_orders['address_pdf'], ENT_QUOTES), 'windows-1252'), 0, 1, 'L', 0);
    if ($row_orders['city_pdf'] != "" || $row_orders['cp_pdf'] != "") {
      $pdf->SetX(100);
      $pdf->Cell(100, 4, mb_convert_encoding($row_orders['cp_pdf'].", ".$row_orders['city_pdf'], 'windows-1252'), 0, 1, 'L', 0);
    }
  }

  if ($row_orders['number_pdf'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding("Siret : ".$row_orders['number_pdf'], 'windows-1252'), 0, 1, 'L', 0);
  }

  /*if ($row_orders['email'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['email'], 'windows-1252'), 0, 1, 'L', 0);
  }
  if ($row_orders['phone'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['phone'], 'windows-1252'), 0, 1, 'L', 0);
  }*/

  if ($row_orders['ord'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding("BDC : ".$row_orders['ord'], 'windows-1252'), 0, 1, 'L', 0);
  }

  if ($row_orders['other_pdf'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['other_pdf'], 'windows-1252'), 0, 1, 'L', 0);
  }
} else {
  if ($row_orders['societe'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['societe'], 'windows-1252'), 0, 1, 'L', 0);
  }
  if ($row_orders['last_name'] != "" || $row_orders['first_name'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['last_name']." ".$row_orders['first_name'], 'windows-1252'), 0, 1, 'L', 0);
  }

  if ($row_orders['address'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['address'], 'windows-1252'), 0, 1, 'L', 0);
  }
  if ($row_orders['city'] != "" || $row_orders['cp'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['city'].", ".$row_orders['cp'], 'windows-1252'), 0, 1, 'L', 0);
  }

  if ($row_orders['email'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['email'], 'windows-1252'), 0, 1, 'L', 0);
  }
  if ($row_orders['phone'] != "") {
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding($row_orders['phone'], 'windows-1252'), 0, 1, 'L', 0);
  }
}

$pdf->SetY($top);
$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(255, 18, 160);
$pdf->RoundedRect(10, $top, 19, 5, 2, '1', 'DF');
$pdf->Cell(19, $step, mb_convert_encoding('Référence', 'windows-1252'), '', 0, 'C', 0);
$pdf->Cell(60, $step, mb_convert_encoding('Désignation', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(10, $step, mb_convert_encoding('Qté', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(15, $step, mb_convert_encoding('P.U. HT', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(22, $step, mb_convert_encoding('Montant HT', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(22, $step, mb_convert_encoding('Montant TVA', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(22, $step, mb_convert_encoding('Montant TTC', 'windows-1252'), 'TLB', 0, 'C', 1);

$pdf->RoundedRect(180, $top, 20, 5, 2, '2', 'DF');
$pdf->Cell(20, $step, mb_convert_encoding('Tx Remise', 'windows-1252'), 0, 1, 'C', 0);
$pdf->Cell(19, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(60, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(10, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(15, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(22, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(22, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(22, 2, '', 'L', 0, 'C', 0);
$pdf->Cell(20, 2, '', 'LR', 1, 'C', 0);

$pdf->SetFont('Arial', '', 7);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetX(29);
$y = $pdf->GetY();
$pdf->MultiCell(60, 3, mb_convert_encoding("DATE : ".$row_orders['event_date']."\nTYPE : ".$row_orders['event_type']."\nHORAIRES :", 'windows-1252'), 'L', 'L', 0);
$pdf->SetX(29);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(255, 0, 0);
$delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique '.$agency : 'Livraison '.$row_orders['event_place'];
if ($row_orders['take_date'] != "" && $row_orders['return_date'] != "") {
  $pdf->MultiCell(60, 3, mb_convert_encoding($row_orders['take_date'].($row_orders['take_time'] != "" ? " entre ".$row_orders['take_time'] : "").", retour le ".$row_orders['return_date'].($row_orders['return_time'] != "" ? " entre ".$row_orders['return_time']: ""), 'windows-1252'), 'L', 'L', 0);
} else {
  $pdf->MultiCell(60, 3, mb_convert_encoding('À définir', 'windows-1252'), 'L', 'L', 0);
}
$pdf->SetX(29);
$pdf->SetFont('Arial', '', 7);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(60, 3, mb_convert_encoding("LIEU : ".$delivery, 'windows-1252'), 'L', 'L', 0);
$y2 = $pdf->GetY();
$pdf->SetY($y);
$pdf->SetX(10);
$pdf->Cell(19, $y2 - $y, mb_convert_encoding('ART0013', 'windows-1252'), 'LR', 0, 'C', 0);
$pdf->SetX(89);
$pdf->Cell(10, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'L', 0, 'C', 0);
$pdf->Cell(15, $y2 - $y, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
$pdf->Cell(20, $y2 - $y, '', 'LR', 1, 'C', 0);
$pdf->SetY($y2);
$pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);

$total_ht = 0;
$total_tva = 0;
$idx = 1;
if ($row_orders['amount'] != 0) {
    $pdf->SetX(29);
    $y = $pdf->GetY();
    $pdf->MultiCell(60, 3, mb_convert_encoding($row_description['meta_value'], 'windows-1252'), 'L', 'L', 0);
    $y2 = $pdf->GetY();
    $pdf->SetY($y);
    $pdf->SetX(10);
    $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".$idx, 'windows-1252'), 'LR', 0, 'C', 0);
    $pdf->SetX(89);
    $pdf->Cell(10, $y2 - $y, mb_convert_encoding($row_orders['amount'].',000', 'windows-1252'), 'L', 0, 'C', 0);
    if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
      $pdf->Cell(15, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount'], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount'], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount']*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount'] + $price*$row_orders['amount']*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($price != 0) {
        $total_ht = $total_ht + $price*$row_orders['amount'];
        $total_tva = $total_tva + $price*$row_orders['amount']*0.2;
      }
    } else {
      $pdf->Cell(15, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount'] - $price*$row_orders['amount']/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount'] - $price*$row_orders['amount']/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount']/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_orders['amount'], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($price != 0) {
        $total_ht = $total_ht + ($price*$row_orders['amount'] - number_format($price*$row_orders['amount']/120*20, 2, '.', ''));
        $total_tva = $total_tva + number_format($price*$row_orders['amount']/120*20, 2, '.', '');
      }
    }
    $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
    $pdf->SetY($y2);
    $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
}



$i = 0;
$assurance = false;
$idx++;

foreach ($options as $key => $value) {
  if (in_array(trim($value['name']), $selected_options_arr)) {
    if (mb_strtolower(trim($value['name'])) == 'assurance dégradation') {
      $assurance = true;
    }
    $amount_arr = explode(":", $selected_options_value_arr[$i]);
    $pdf->SetX(29);
    $y = $pdf->GetY();
    $pdf->MultiCell(60, 3, mb_convert_encoding($value['article'], 'windows-1252'), 'L', 'L', 0);
    $y2 = $pdf->GetY();
    $pdf->SetY($y);
    $pdf->SetX(10);
    $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".$idx, 'windows-1252'), 'LR', 0, 'C', 0);
    $pdf->SetX(89);
    $pdf->Cell(10, $y2 - $y, mb_convert_encoding($amount_arr[1].',000', 'windows-1252'), 'L', 0, 'C', 0);
    if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
      $pdf->Cell(15, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2] : $value['price']), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1]*0.2 : $value['price']*$amount_arr[1]*0.2), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($value['price'] != 0) {
        $total_ht = $total_ht + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]);
        $total_tva = $total_tva + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])*0.2;
      } else {
        $total_ht = $total_ht + $amount_arr[2]*$amount_arr[1];
        $total_tva = $total_tva + $amount_arr[2]*$amount_arr[1]*0.2;
      }
    } else {
      $pdf->Cell(15, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2] : $value['price']) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($value['price'] != 0) {
        $total_ht = $total_ht + ((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) - number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, '.', ''));
        $total_tva = $total_tva + number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, '.', '');
      } else {
        $total_ht = $total_ht + $amount_arr[2]*$amount_arr[1] - $amount_arr[2]*$amount_arr[1]/120*20;
        $total_tva = $total_tva + $amount_arr[2]*$amount_arr[1]/120*20;
      }
    }

    $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
    $pdf->SetY($y2);
    $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
    $i++;
    $idx++;
  }
  $j = $key;
}


$result_bornes = mysqli_query($conn, "SELECT * FROM `bornes` WHERE `order_id` = ".$order_id);
while($row_bornes = mysqli_fetch_assoc($result_bornes)) {
  if (strpos($row_orders['select_type'], 'entreprise') !== false) {
    $data = json_decode(file_get_contents("../enterprise.ini"), true);
    $prices_arr = json_decode(file_get_contents("../enterprise_price.ini"), true);
    switch($row_bornes['box_type']) {
      case "Ring":
      case "Ring_promotionnel":
        $options = $data['ring2']['options'];
        //$deliverys = $data['ring2']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_ring'");
        $price = $prices_arr['ring_price'];
      break;
      case "Vegas":
      case "Vegas_800":
      case "Vegas_1200":
      case "Vegas_1600":
      case "Vegas_2000":
        $options = $data['vegas']['options'];
        //$deliverys = $data['vegas']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vegas".(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")."'");
        $price = $prices_arr['vegas_price'.(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")];
      break;
      case "Miroir":
      case "Miroir_800":
      case "Miroir_1200":
      case "Miroir_1600":
      case "Miroir_2000":
        $options = $data['miroir']['options'];
        //$deliverys = $data['miroir']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_miroir".(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")."'");
        $price = $prices_arr['miroir_price'.(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")];
      break;
      case "Spinner_360":
        $options = $data['spinner']['options'];
        //$deliverys = $data['spinner']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_spinner'");
        $price = $prices_arr['spinner_price'];
      break;
      case "Réalité_Virtuelle":
        $options = $data['vr2']['options'];
        //$deliverys = $data['vr2']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vr'");
        $price = $prices_arr['vr_price'];
      break;
    }
  } else {
    $data = json_decode(file_get_contents("../particulier.ini"), true);
    $prices_arr = json_decode(file_get_contents("../particulier_price.ini"), true);
    switch($row_bornes['box_type']) {
      case "Ring":
      case "Ring_promotionnel":
        $options = $data['ring']['options'];
        //$deliverys = $data['ring']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_ring'");
        $price = $prices_arr['ring_price'];
      break;
      case "Vegas":
      case "Vegas_800":
      case "Vegas_1200":
      case "Vegas_1600":
      case "Vegas_2000":
        $options = $data['vegas']['options'];
        //$deliverys = $data['vegas']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vegas".(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")."'");
        $price = $prices_arr['vegas_price'.(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")];
      break;
      case "Miroir":
      case "Miroir_800":
      case "Miroir_1200":
      case "Miroir_1600":
      case "Miroir_2000":
        $options = $data['miroir']['options'];
        //$deliverys = $data['miroir']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_miroir".(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")."'");
        $price = $prices_arr['miroir_price'.(strpos($row_bornes['box_type'], "800") === false ? "" : "_2").(strpos($row_bornes['box_type'], "1200") === false ? "" : "_1200").(strpos($row_bornes['box_type'], "1600") === false ? "" : "_1600").(strpos($row_bornes['box_type'], "2000") === false ? "" : "_2000")];
      break;
      case "Spinner_360":
        $options = $data['spinner']['options'];
        //$deliverys = $data['spinner']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_spinner'");
        $price = $prices_arr['spinner_price'];
      break;
      case "Réalité_Virtuelle":
        $options = $data['vr2']['options'];
        //$deliverys = $data['vr2']['delivery'];
        $result_description = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 989862 AND `meta_key` = 'descriptions_vr'");
        $price = $prices_arr['vr_price'];
      break;
    }
  }

   $price = $row_bornes['price'];

  $row_description = mysqli_fetch_assoc($result_description);

  $selected_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_bornes['selected_options'])))))));
  $selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_bornes['selected_options'])))));

  $pdf->SetX(29);
  $y = $pdf->GetY();
    $pdf->MultiCell(60, 3, mb_convert_encoding($row_description['meta_value'], 'windows-1252'), 'L', 'L', 0);
    $y2 = $pdf->GetY();
    $pdf->SetY($y);
    $pdf->SetX(10);
    $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".$idx, 'windows-1252'), 'LR', 0, 'C', 0);
    $pdf->SetX(89);
    $pdf->Cell(10, $y2 - $y, mb_convert_encoding($row_bornes['amount'].',000', 'windows-1252'), 'L', 0, 'C', 0);
    if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
      $pdf->Cell(15, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount'], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount'], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount']*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount'] + $price*$row_bornes['amount']*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($price != 0) {
        $total_ht = $total_ht + $price*$row_bornes['amount'];
        $total_tva = $total_tva + $price*$row_bornes['amount']*0.2;
      }
    } else {
      $pdf->Cell(15, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount'] - $price*$row_bornes['amount']/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount'] - $price*$row_bornes['amount']/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount']/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $price != 0 ? mb_convert_encoding(number_format($price*$row_bornes['amount'], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($price != 0) {
        $total_ht = $total_ht + ($price*$row_bornes['amount'] - number_format($price*$row_bornes['amount']/120*20, 2, '.', ''));
        $total_tva = $total_tva + number_format($price*$row_bornes['amount']/120*20, 2, '.', '');
      }
    }
    $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
    $pdf->SetY($y2);
    $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);

    $i = 0;
    $idx++;
    foreach ($options as $key => $value) {
      if (in_array(trim($value['name']), $selected_options_arr)) {
        if (mb_strtolower(trim($value['name'])) == 'assurance dégradation') {
          $assurance = true;
        }
        $amount_arr = explode(":", $selected_options_value_arr[$i]);
        $pdf->SetX(29);
        $y = $pdf->GetY();
        $pdf->MultiCell(60, 3, mb_convert_encoding($value['article'], 'windows-1252'), 'L', 'L', 0);
        $y2 = $pdf->GetY();
        $pdf->SetY($y);
        $pdf->SetX(10);
        $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".$idx, 'windows-1252'), 'LR', 0, 'C', 0);
        $pdf->SetX(89);
        $pdf->Cell(10, $y2 - $y, mb_convert_encoding($amount_arr[1].',000', 'windows-1252'), 'L', 0, 'C', 0);
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $pdf->Cell(15, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1]*0.2 : $value['price']*$amount_arr[1]*0.2), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          if ($value['price'] != 0) {
            $total_ht = $total_ht + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]);
            $total_tva = $total_tva + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])*0.2;
          }
        } else {
          $pdf->Cell(15, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $value['price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          if ($value['price'] != 0) {
            $total_ht = $total_ht + ((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1]) - number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, '.', ''));
            $total_tva = $total_tva + number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $value['price']*$amount_arr[1])/120*20, 2, '.', '');
          }
        }
        $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
        $pdf->SetY($y2);
        $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
        $i++;
        $idx++;
      }
      $j = $key;
    }



}

$i = 0;
$livriason = false;
foreach ($deliverys as $key => $value) {
  if (in_array(trim($value['name']), $delivery_options_arr)) {
    if (strpos(mb_strtolower(trim($value['name'])), 'livraison') !== false || strpos(mb_strtolower(trim($value['name'])), 'installation') !== false) {
      $livriason = true;
    }
    $amount_arr = explode(":", $delivery_options_value_arr[$i]);
    $pdf->SetX(29);
    $y = $pdf->GetY();
    $pdf->MultiCell(60, 3, mb_convert_encoding($value['article'], 'windows-1252'), 'L', 'L', 0);
    $y2 = $pdf->GetY();
    $pdf->SetY($y);
    $pdf->SetX(10);
    $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".($j + $key + 2), 'windows-1252'), 'LR', 0, 'C', 0);
    $pdf->SetX(89);
    $pdf->Cell(10, $y2 - $y, mb_convert_encoding($amount_arr[1].',000', 'windows-1252'), 'L', 0, 'C', 0);
    if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
      $pdf->Cell(15, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1]*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1] + $amount_arr[2]*$amount_arr[1]*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($amount_arr[2] != 0) {
        $total_ht = $total_ht + $amount_arr[2]*$amount_arr[1];
        $total_tva = $total_tva + $amount_arr[2]*$amount_arr[1]*0.2;
      }
    } else {
      $pdf->Cell(15, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1] - $amount_arr[2]*$amount_arr[1]/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1] - $amount_arr[2]*$amount_arr[1]/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1]/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      $pdf->Cell(22, $y2 - $y, $amount_arr[2] != 0 ? mb_convert_encoding(number_format($amount_arr[2]*$amount_arr[1], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
      if ($amount_arr[2] != 0) {
        $total_ht = $total_ht + ($amount_arr[2]*$amount_arr[1] - number_format($amount_arr[2]*$amount_arr[1]/120*20, 2, '.', ''));
        $total_tva = $total_tva + number_format($amount_arr[2]*$amount_arr[1]/120*20, 2, '.', '');
      }
    }
    $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
    $pdf->SetY($y2);
    $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
    $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
    $i++;
  }
}

$i = 0;
    $idx++;

    $row_orders['selected_options'] = trim(htmlspecialchars_decode($row_orders['selected_options'], ENT_QUOTES).",".htmlspecialchars_decode($row_orders['selected_personal_options'], ENT_QUOTES), ",");
    $selected_options_arr = array();
    $options_arr = explode(",", trim($row_orders['selected_options']));
    array_push($options_arr, explode(",", trim($row_orders['selected_options'])));
    foreach ($options_arr as $key => $value) {
      $option_arr = explode(":", $value);
      if (trim($option_arr[0]) != "Livraison" && trim($option_arr[0]) != "Retrait boutique") {
        $selected_options_arr[] = trim($option_arr[0]);
      }
    }
    $selected_options_value_arr = explode(",", str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", ", ",", trim($row_orders['selected_options'])))));
    $result_options = mysqli_query($conn, "SELECT * FROM `options`");

    while($row_options = mysqli_fetch_assoc($result_options)) {
      if (mb_strtolower(trim($value['name'])) == 'assurance dégradation') {
        $assurance = true;
      }
      if (in_array(trim($row_options['title']), $selected_options_arr)) {
        $amount_arr = explode(":", $selected_options_value_arr[$i]);
        $pdf->SetX(29);
        $y = $pdf->GetY();
        $pdf->MultiCell(60, 3, mb_convert_encoding($row_options['title'], 'windows-1252'), 'L', 'L', 0);
        $y2 = $pdf->GetY();
        $pdf->SetY($y);
        $pdf->SetX(10);
        $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".$idx, 'windows-1252'), 'LR', 0, 'C', 0);
        $pdf->SetX(89);
        $pdf->Cell(10, $y2 - $y, mb_convert_encoding($amount_arr[1].',000', 'windows-1252'), 'L', 0, 'C', 0);
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $pdf->Cell(15, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1]*0.2 : $row_options[$price_prefix.'price']*$amount_arr[1]*0.2), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          if ($row_options[$price_prefix.'price'] != 0) {
            $total_ht = $total_ht + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]);
            $total_tva = $total_tva + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])*0.2;
          }
        } else {
          $pdf->Cell(15, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          if ($row_options[$price_prefix.'price'] != 0) {
            $total_ht = $total_ht + ((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) - number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, '.', ''));
            $total_tva = $total_tva + number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, '.', '');
          }
        }
        $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
        $pdf->SetY($y2);
        $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
          /*echo'<div class="window_inputs window_inputs_item1 window_inputs_i_0 window_inputs_up'.$row_options['id'].'">
                      <label for="vehicle_up'.$row_options['id'].'">'.$row_options['title'].'</label>
                      <input class="vehicle_up" id="vehicle_up'.$row_options['id'].'" name="vehicle_up'.$row_options['id'].'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$row_options['title'].'" data-price="'.$row_options[$price_prefix.'price'].'" data-idx="'.$row_options['id'].'" onChange="calcTotal()" />
                      &nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price'.$row_options['id'].'" name="vehicle_up_price'.$row_options['id'].'" type="number" min="0" step="1" value="'.(isset($amount_arr[2]) ? $amount_arr[2] : $value['price']).'" onChange="calcTotal()" />&nbsp;&euro;
                      <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$row_options['id'].')">
                        <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                      </svg>
                    </div>';*/
        $i++;
        $idx++;
      }
    }

$i = 0;
    $idx++;
$delivery_options_arr = explode(",", str_replace(":", "", preg_replace('/\d/', '', str_replace(".", "", htmlspecialchars_decode($row_orders['delivery_options'], ENT_QUOTES)))));
$delivery_options_value_arr = explode(",", htmlspecialchars_decode($row_orders['delivery_options'], ENT_QUOTES));

$result_delivery = mysqli_query($conn, "SELECT * FROM `delivery`");
while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
  if (in_array(trim($row_delivery['title']), $delivery_options_arr)) {
        $amount_arr = explode(":", $delivery_options_value_arr[$i]);
        $pdf->SetX(29);
        $y = $pdf->GetY();
        $pdf->MultiCell(60, 3, mb_convert_encoding($row_delivery['title'], 'windows-1252'), 'L', 'L', 0);
        $y2 = $pdf->GetY();
        $pdf->SetY($y);
        $pdf->SetX(10);
        $pdf->Cell(19, $y2 - $y, mb_convert_encoding("ART00".$idx, 'windows-1252'), 'LR', 0, 'C', 0);
        $pdf->SetX(89);
        $pdf->Cell(10, $y2 - $y, mb_convert_encoding($amount_arr[1].',000', 'windows-1252'), 'L', 0, 'C', 0);
        if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
          $pdf->Cell(15, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0 ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1]*0.2 : $row_options[$price_prefix.'price']*$amount_arr[1]*0.2), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          if ($row_options[$price_prefix.'price'] != 0) {
            $total_ht = $total_ht + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]);
            $total_tva = $total_tva + (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])*0.2;
          }
        } else {
          $pdf->Cell(15, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) - (isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          $pdf->Cell(22, $y2 - $y, $row_options[$price_prefix.'price'] != 0 || $amount_arr[2] != 0  ? mb_convert_encoding(number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]), 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
          if ($row_options[$price_prefix.'price'] != 0) {
            $total_ht = $total_ht + ((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1]) - number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, '.', ''));
            $total_tva = $total_tva + number_format((isset($amount_arr[2]) ? $amount_arr[2]*$amount_arr[1] : $row_options[$price_prefix.'price']*$amount_arr[1])/120*20, 2, '.', '');
          }
        }
        $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
        $pdf->SetY($y2);
        $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
        $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
          /*echo'<div class="window_inputs window_inputs_item1 window_inputs_i_0 window_inputs_up'.$row_options['id'].'">
                      <label for="vehicle_up'.$row_options['id'].'">'.$row_options['title'].'</label>
                      <input class="vehicle_up" id="vehicle_up'.$row_options['id'].'" name="vehicle_up'.$row_options['id'].'" type="number" min="0" step="1" value="'.$amount_arr[1].'" data-name="'.$row_options['title'].'" data-price="'.$row_options[$price_prefix.'price'].'" data-idx="'.$row_options['id'].'" onChange="calcTotal()" />
                      &nbsp;&nbsp;<input class="vehicle_up_price" id="vehicle_up_price'.$row_options['id'].'" name="vehicle_up_price'.$row_options['id'].'" type="number" min="0" step="1" value="'.(isset($amount_arr[2]) ? $amount_arr[2] : $value['price']).'" onChange="calcTotal()" />&nbsp;&euro;
                      <svg class="window_btn__close" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style="fill: white" onClick="removeOption('.$row_options['id'].')">
                        <path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"></path>
                      </svg>
                    </div>';*/
        $i++;
        $idx++;
      }
}


if (strpos(strtolower($row_orders['select_type']), 'entrepris') === false) {
  $pdf->SetX(29);
  $y = $pdf->GetY();
  if (!isset($_GET['refund'])) {
    $pdf->MultiCell(60, 3, mb_convert_encoding("ACOMPTE : ".$row_orders['advance_payment']."€\nSOLDE ".($livriason ? "avant livriason": "au retrait")." : ".(($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']) - $row_orders['advance_payment'])."€", 'windows-1252'), 'L', 'L', 0);
  }
  $pdf->SetX(29);

  if(!$assurance) {
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(60, 3, mb_convert_encoding("\nCAUTION : 1500€ en chèque (si assurance non souscrite en amont)", 'windows-1252'), 'L', 'L', 0);
  }
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $y2 = $pdf->GetY();

  if (!isset($_GET['refund'])) {
  $pdf->SetY($y);
  $pdf->SetX(10);
  $pdf->Cell(19, $y2 - $y, mb_convert_encoding('ART0012', 'windows-1252'), 'LR', 0, 'C', 0);
  $pdf->SetX(89);
  $pdf->Cell(10, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(15, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(20, $y2 - $y, '', 'LR', 1, 'C', 0);
}
  $pdf->SetY($y2);
  $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
} else {
  $pdf->SetX(29);
  $y = $pdf->GetY();
  $pdf->MultiCell(60, 3, mb_convert_encoding("Règlement sur facture", 'windows-1252'), 'L', 'L', 0);
  $pdf->SetX(29);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $y2 = $pdf->GetY();
  $pdf->SetY($y);
  $pdf->SetX(10);
  $pdf->Cell(19, $y2 - $y, mb_convert_encoding('ART0012', 'windows-1252'), 'LR', 0, 'C', 0);
  $pdf->SetX(89);
  $pdf->Cell(10, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(15, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $y2 - $y, '', 'L', 0, 'C', 0);
  $pdf->Cell(20, $y2 - $y, '', 'LR', 1, 'C', 0);

  $pdf->SetY($y2);
  $pdf->Cell(19, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
  $pdf->Cell(20, $step, '', 'LR', 1, 'C', 0);
}

$y2 = $pdf->GetY();
$pdf->SetY($y2);
$pdf->Cell(19, $step, '', '', 0, 'C', 0);
$pdf->Cell(60, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(10, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(15, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(22, $step, '', 'L', 0, 'C', 0);
$pdf->Cell(20, $step, '', 'L', 1, 'C', 0);
//$pdf->Cell(0, 1, '', 'T', 1, 'C', 0);

$pdf->SetFillColor(255, 18, 160);
$pdf->RoundedRect(10, $y2, 190, 5, 2, '34', 'DF');

$pdf->Ln(2);

$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(255, 18, 160);

$y2 = $pdf->GetY();
$pdf->RoundedRect(10, $y2, 15, 5, 2, '1', 'DF');
$pdf->Cell(15, $step, mb_convert_encoding('Code', 'windows-1252'), 0, 0, 'C', 0);
$pdf->Cell(22, $step, mb_convert_encoding('Base HT', 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(19, $step, mb_convert_encoding('Taux TVA', 'windows-1252'), 1, 0, 'C', 1);
$pdf->RoundedRect(66, $y2, 22, 5, 2, '2', 'DF');
$pdf->Cell(22, $step, mb_convert_encoding('Montant TVA', 'windows-1252'), 0, 1, 'C', 0);
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0);

$y2 = $pdf->GetY();
$pdf->RoundedRect(10, $y2, 15, 5, 2, '4', 'D');
$pdf->Cell(15, $step, mb_convert_encoding('5', 'windows-1252'), 0, 0, 'C', 0);
$pdf->Cell(22, $step, mb_convert_encoding(number_format($total_ht - $total_ht/100*$row_orders['transportation_time'], 2, ',', ''), 'windows-1252'), 1, 0, 'C', 0);
$pdf->Cell(19, $step, mb_convert_encoding('20,00', 'windows-1252'), 1, 0, 'C', 0);
$pdf->RoundedRect(66, $y2, 22, 5, 2, '3', 'D');
$pdf->Cell(22, $step, mb_convert_encoding(number_format($total_tva - $total_tva/100*$row_orders['transportation_time'], 2, ',', ''), 'windows-1252'), 0, 1, 'C', 0);

$pdf->SetY($pdf->GetY() - (4 + $step));
if ($row_orders['transportation_time'] > 0) {
  $pdf->SetX(100);
  $pdf->SetFont('Arial', 'B', 9);
  $pdf->SetTextColor(255, 255, 255);
  $pdf->Cell(85, $step, mb_convert_encoding('Remise, '.$row_orders['transportation_time'].'%', 'windows-1252'), 'LT', 0, 'L', 1);
  $pdf->Cell(15, $step, mb_convert_encoding(number_format((($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']) - ($row_orders['deposit'] == 0 ? $row_orders['advance_payment'] : $row_orders['deposit']))/100*$row_orders['transportation_time'], 2, ',', ''), 'windows-1252'), 'TR', 1, 'R', 1);
}
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(100);

$y2 = $pdf->GetY();
$pdf->RoundedRect(100, $y2, 100, 10, 2, '12', 'D');

$pdf->Cell(85, $step, mb_convert_encoding('Total HT', 'windows-1252'), '', 0, 'L', 0);
$pdf->Cell(15, $step, mb_convert_encoding(number_format($total_ht - $total_ht/100*$row_orders['transportation_time'], 2, ',', ''), 'windows-1252'), '', 1, 'R', 0);
$pdf->SetX(100);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(85, $step, mb_convert_encoding('Net HT', 'windows-1252'), '', 0, 'L', 0);
$pdf->Cell(15, $step, mb_convert_encoding(number_format($total_ht - $total_ht/100*$row_orders['transportation_time'], 2, ',', ''), 'windows-1252'), '', 1, 'R', 0);
$pdf->SetX(100);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(85, $step, mb_convert_encoding('Total TVA', 'windows-1252'), 'L', 0, 'L', 0);
$pdf->Cell(15, $step, mb_convert_encoding(number_format($total_tva - $total_tva/100*$row_orders['transportation_time'], 2, ',', ''), 'windows-1252'), 'R', 1, 'R', 0);
$pdf->SetX(100);
$pdf->Cell(85, $step, mb_convert_encoding('Total TTC', 'windows-1252'), 'L', 0, 'L', 0);
$pdf->Cell(15, $step, mb_convert_encoding(number_format(($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']), 2, ',', ''), 'windows-1252'), 'R', 1, 'R', 0);
$pdf->SetX(100);
if (!isset($_GET['avoir']) && ($row_orders['advance_payment'] != 0) && $row_orders['status'] != 0 && !isset($_GET['refund'])) {
  $pdf->Cell(85, $step, mb_convert_encoding('Acompte', 'windows-1252'), 'L', 0, 'L', 0);
  $pdf->Cell(15, $step, mb_convert_encoding(number_format($row_orders['advance_payment'], 2, ',', ''), 'windows-1252'), 'R', 1, 'R', 0);
  $pdf->SetX(100);
}
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255);
if (!isset($_GET['credit'])) {
  $y2 = $pdf->GetY();
  $pdf->RoundedRect(100, $y2, 100, 5, 2, '34', 'DF');
  if (!isset($_GET['refund'])) {
    $pdf->Cell(85, $step, mb_convert_encoding('NET A PAYER', 'windows-1252'), '', 0, 'L', 0);
  } else {
    $pdf->Cell(85, $step, mb_convert_encoding('CREDIT', 'windows-1252'), '', 0, 'L', 0);
  }
  if ($row_orders['payment_status'] == 0) {
    $pdf->Cell(15, $step, mb_convert_encoding(number_format(($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']), 2, ',', ''), 'windows-1252'), '', 0, 'R', 0);
  } else {
    //if (strpos($row_orders['select_type'], 'entreprise') === false) {
      if ($row_orders['payment_status'] != 2) {
        $pdf->Cell(15, $step, mb_convert_encoding(number_format((($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']) - ($row_orders['advance_payment'] != 0 ? $row_orders['advance_payment'] : $row_orders['deposit'])), 2, ',', ''), 'windows-1252'), '', 0, 'R', 0);
      } else {
        $pdf->Cell(15, $step, mb_convert_encoding("0,00", 'windows-1252'), '', 0, 'R', 0);
      }
    //} else {
      //$pdf->Cell(15, $step, mb_convert_encoding(number_format((($total_ht - $total_ht/100*$row_orders['transportation_time']) + ($total_tva - $total_tva/100*$row_orders['transportation_time']) - ($row_orders['advance_payment'] != 0 ? $row_orders['advance_payment'] : $row_orders['deposit'])), 2, ',', ''), 'windows-1252'), '', 0, 'R', 0);
      //$pdf->Cell(15, $step, mb_convert_encoding("0,00", 'windows-1252'), '', 0, 'R', 0);
    //}
  }
} else {
  $pdf->Cell(85, $step, mb_convert_encoding('CREDIT', 'windows-1252'), 'LTB', 0, 'L', 1);
  $pdf->Cell(15, $step, mb_convert_encoding(number_format($_GET['credit'], 2, ',', ''), 'windows-1252'), 'RTB', 1, 'R', 1);
}



$pdf->Image('footer.png', 3, 238, 203);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->SetTextColor(255, 18, 160);
  if ($row_orders['facteur'] == 0) {
    $pdf->SetY(260);
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding('Shootnbox enseigne exploitée par Amazing Event SAS', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('3, Sentier des Marécages, 93100 Montreuil / SIRET 849 542 469 00044 RCS Bobigny', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('Capital 15,000€/ N° TVA Intracommunautaire FR46849542469', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('IBAN : FR76 3000 4005 7000 0101 4775 434', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('BIC : BNPAFRPPXXX', 'windows-1252'), 0, 1, 'C', 0);
  } else {
    $pdf->SetY(242);
    $pdf->SetX(100);
    $pdf->Cell(100, 4, mb_convert_encoding('Shootnbox enseigne exploitée par Amazing Event SAS', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('Pour être libératoire, le règlement de cette facture doit être effectué et adressé à :', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('BPCE FACTOR Tél : 01 58 32 80 00', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('5, avenue de la Liberté - 94676 Charenton-le-Pont Cedex', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('RIB : 30007 00011 00010500791 36 - Domiciliation : NATIXIS Paris', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('IBAN : FR76 3000 7000 1100 0105 0079 136', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('SWIFT CODE (BIC) : NATX FR PP XXX', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('N° ICS : FR57ZZZ483693', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('BPCE FACTOR a acquis notre créance par voie de subrogation dans le cadre d’un', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('contrat d’affacturage.', 'windows-1252'), 0, 1, 'C', 0);
    $pdf->SetX(100);
    $pdf->Cell(100, 3, mb_convert_encoding('BPCE FACTOR devra être avisée de toute réclamation.', 'windows-1252'), 0, 1, 'C', 0);
  }

/*if ($row_orders['status'] == 0) {
  $result_article = mysqli_query($conn, "SELECT `meta_value` FROM `wp_postmeta` WHERE `post_id` = 988571 AND `meta_key` = 'article'");
  $row_article = mysqli_fetch_assoc($result_article);
  $pdf->AddPage();
  $pdf->Image('header.png', 35, $step, 140);
  $pdf->SetY(65);
  $pdf->SetFont('Arial', '', 7);
  $pdf->Write(3.6, mb_convert_encoding($row_article['meta_value'], 'windows-1252'));
}*/

if(!isset($_GET['file_name'])) {
  $pdf->Output('I', $row_orders['num_id'].".pdf");
} else {
  $pdf->Output('F', $_GET['file_name']);
  echo"done";
}

mysqli_close($conn);
?>
