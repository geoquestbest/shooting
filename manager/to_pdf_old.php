<?php
@require_once("../inc/mainfile.php");
@require_once("fpdf/fpdf.php");

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$order_id);
$row_orders = mysqli_fetch_assoc($result_orders);
$result_factures = mysqli_query($conn, "SELECT * FROM `factures` WHERE `order_id` = ".$row_orders['id']);
$row_factures = mysqli_fetch_assoc($result_factures);

class PDF extends FPDF
{
  // Page header
  function Header()
  {

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

$pdf->Image('logo.png', 10, 5, 75, 13);

$pdf->SetY(7);
$pdf->SetX(90);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(255, 18, 160);
$pdf->Cell(37, 5, mb_convert_encoding(($row_orders['status'] == 0 ? 'Devis' : 'Facture').' N°', 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(37, 5, mb_convert_encoding('Date', 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(37, 5, mb_convert_encoding('Client', 'windows-1252'), 1, 1, 'C', 1);
$pdf->SetX(90);
$pdf->Cell(37, 5, mb_convert_encoding($row_factures['num_id'], 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(37, 5, mb_convert_encoding(date("d/m/Y", $row_orders['created_at']), 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(37, 5, mb_convert_encoding($row_orders['id'], 'windows-1252'), 1, 1, 'C', 1);
$pdf->Ln(10);


$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(80, 5, mb_convert_encoding('SAS BY AMAZING EVENT', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(80, 5, mb_convert_encoding('5 RUE MALMAISON', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(80, 5, mb_convert_encoding('93170 BAGNOLET', 'windows-1252'), 0, 1, 'L', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(20, 5, mb_convert_encoding('Tél', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, 5, mb_convert_encoding(':   01 45 01 66 66', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, 5, mb_convert_encoding('Capital', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, 5, mb_convert_encoding(':   15 000,00 Euros', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, 5, mb_convert_encoding('R.C.S.', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, 5, mb_convert_encoding(':   849542469', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, 5, mb_convert_encoding('SIRET', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, 5, mb_convert_encoding(':   84954246900010', 'windows-1252'), 0, 1, 'L', 1);
$pdf->Cell(20, 5, mb_convert_encoding('N/Id CEE', 'windows-1252'), 0, 0, 'L', 1); $pdf->Cell(60, 5, mb_convert_encoding(':   FR46849542469', 'windows-1252'), 0, 1, 'L', 1);


$pdf->Rect(95, 33, 100, 30);
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(94, 38, 101, 20, 'F');
$pdf->Rect(100, 32, 90, 32, 'F');

$pdf->SetY(36);
$pdf->SetX(100);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(100, 4, mb_convert_encoding($row_orders['societe'], 'windows-1252'), 0, 1, 'L', 0);
$pdf->SetX(100);
$pdf->Cell(100, 4, mb_convert_encoding($row_orders['last_name']." ".$row_orders['first_name'], 'windows-1252'), 0, 1, 'L', 0);
$pdf->SetX(100);
$pdf->Cell(100, 4, mb_convert_encoding($row_orders['address'].", ".$row_orders['cp'], 'windows-1252'), 0, 1, 'L', 0);
$pdf->SetX(100);
$pdf->Cell(100, 4, mb_convert_encoding($row_orders['email'], 'windows-1252'), 0, 1, 'L', 0);
$pdf->SetX(100);
$pdf->Cell(100, 4, mb_convert_encoding($row_orders['phone'], 'windows-1252'), 0, 1, 'L', 0);
$pdf->SetX(100);
$pdf->Cell(100, 4, mb_convert_encoding("BDC : ".$row_orders['ord'], 'windows-1252'), 0, 1, 'L', 0);

$pdf->SetY(80);
$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(255, 18, 160);
$pdf->Cell(19, 5, mb_convert_encoding('Référence', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(60, 5, mb_convert_encoding('Désignation', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(10, 5, mb_convert_encoding('Qté', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(15, 5, mb_convert_encoding('P.U. HT', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(22, 5, mb_convert_encoding('Montant HT', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(22, 5, mb_convert_encoding('Montant TVA', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(22, 5, mb_convert_encoding('Montant TTC', 'windows-1252'), 'TLB', 0, 'C', 1);
$pdf->Cell(20, 5, mb_convert_encoding('Tx Remise', 'windows-1252'), 1, 1, 'C', 1);
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
$pdf->MultiCell(60, 3, mb_convert_encoding("DATE : ".$row_orders['event_date']."\nTYPE : ".$row_orders['event_type']."\nHORAIRES : ".$row_orders['event_schedule']."\nLIEU : ".$row_orders['event_place'], 'windows-1252'), 'L', 'L', 0);
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
$pdf->Cell(19, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(60, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(10, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(15, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
$pdf->Cell(20, 5, '', 'LR', 1, 'C', 0);

$articles_ids_arr = explode(",", $row_factures['articles_ids']);
$total_ht = 0;
$total_tva = 0;
foreach ($articles_ids_arr as $key => $value) {
  $value_arr = explode(":", $value);
  $result_articles = mysqli_query($conn, "SELECT * FROM `articles` WHERE `id` = ".$value_arr[0]);
  $row_articles = mysqli_fetch_assoc($result_articles);
  $pdf->SetX(29);
  $y = $pdf->GetY();
  $pdf->MultiCell(60, 3, mb_convert_encoding(htmlspecialchars_decode($row_articles['content'], ENT_QUOTES), 'windows-1252'), 'L', 'L', 0);
  $y2 = $pdf->GetY();
  $pdf->SetY($y);
  $pdf->SetX(10);
  $pdf->Cell(19, $y2 - $y, mb_convert_encoding($row_articles['num'], 'windows-1252'), 'LR', 0, 'C', 0);
  $pdf->SetX(89);
  $pdf->Cell(10, $y2 - $y, mb_convert_encoding('1,000', 'windows-1252'), 'L', 0, 'C', 0);
  if (strpos(strtolower($row_orders['select_type']), 'entrepris')) {
    $pdf->Cell(15, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1]*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1] + $value_arr[1]*0.2, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    if ($value_arr[1] != 0) {
      $total_ht = $total_ht + $value_arr[1];
      $total_tva = $total_tva + $value_arr[1]*0.2;
    }
  } else {
    $pdf->Cell(15, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1] - $value_arr[1]/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1] - $value_arr[1]/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1]/120*20, 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    $pdf->Cell(22, $y2 - $y, $value_arr[1] != 0 ? mb_convert_encoding(number_format($value_arr[1], 2, ',', ''), 'windows-1252') : '', 'L', 0, 'C', 0);
    if ($value_arr[1] != 0) {
      $total_ht = $total_ht + ($value_arr[1] - number_format($value_arr[1]/120*20, 2, '.', ''));
      $total_tva = $total_tva + number_format($value_arr[1]/120*20, 2, '.', '');
    }
  }
  $pdf->Cell(20, $y2 - $y, mb_convert_encoding('', 'windows-1252'), 'LR', 1, 'C', 0);
  $pdf->SetY($y2);
  $pdf->Cell(19, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(60, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(10, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(15, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(20, 5, '', 'LR', 1, 'C', 0);
}

if (strpos(strtolower($row_orders['select_type']), 'entrepris') === false) {
  $pdf->SetX(29);
  $y = $pdf->GetY();
  $pdf->MultiCell(60, 3, mb_convert_encoding("ACOMPTE : ".$row_orders['advance_payment']."€\nSOLDE au retrait : ".$row_orders['total']."€", 'windows-1252'), 'L', 'L', 0);
  $pdf->SetX(29);
  $pdf->SetFont('Arial', 'B', 7);
  $pdf->SetTextColor(255, 0, 0);
  $pdf->MultiCell(60, 3, mb_convert_encoding("\nCAUTION : 1500€ en chèque (si assurance non souscrite en amont)", 'windows-1252'), 'L', 'L', 0);
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
  $pdf->Cell(19, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(60, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(10, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(15, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(22, 5, '', 'L', 0, 'C', 0);
  $pdf->Cell(20, 5, '', 'LR', 1, 'C', 0);
}
$pdf->Cell(0, 1, '', 'T', 1, 'C', 0);

$pdf->Ln(5);

$pdf->SetX(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(255, 18, 160);
$pdf->Cell(15, 5, mb_convert_encoding('Code', 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(22, 5, mb_convert_encoding('Base HT', 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(19, 5, mb_convert_encoding('Taux TVA', 'windows-1252'), 1, 0, 'C', 1);
$pdf->Cell(22, 5, mb_convert_encoding('Montant TVA', 'windows-1252'), 1, 1, 'C', 1);
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(15, 5, mb_convert_encoding('5', 'windows-1252'), 1, 0, 'C', 0);
$pdf->Cell(22, 5, mb_convert_encoding(number_format($total_ht, 2, ',', ''), 'windows-1252'), 1, 0, 'C', 0);
$pdf->Cell(19, 5, mb_convert_encoding('20,00', 'windows-1252'), 1, 0, 'C', 0);
$pdf->Cell(22, 5, mb_convert_encoding(number_format($total_tva, 2, ',', ''), 'windows-1252'), 1, 1, 'C', 0);

$pdf->SetY($pdf->GetY() - 10);
$pdf->SetX(100);
$pdf->Cell(85, 5, mb_convert_encoding('Total HT', 'windows-1252'), 'LT', 0, 'L', 0);
$pdf->Cell(15, 5, mb_convert_encoding(number_format($total_ht, 2, ',', ''), 'windows-1252'), 'TR', 1, 'R', 0);
$pdf->SetX(100);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(85, 5, mb_convert_encoding('Net HT', 'windows-1252'), 'LB', 0, 'L', 0);
$pdf->Cell(15, 5, mb_convert_encoding(number_format($total_ht, 2, ',', ''), 'windows-1252'), 'RB', 1, 'R', 0);
$pdf->SetX(100);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(85, 5, mb_convert_encoding('Total TVA', 'windows-1252'), 'L', 0, 'L', 0);
$pdf->Cell(15, 5, mb_convert_encoding(number_format($total_tva, 2, ',', ''), 'windows-1252'), 'R', 1, 'R', 0);
$pdf->SetX(100);
$pdf->Cell(85, 5, mb_convert_encoding('Total TTC', 'windows-1252'), 'L', 0, 'L', 0);
$pdf->Cell(15, 5, mb_convert_encoding(number_format($total_ht + $total_tva- 50, 2, ',', ''), 'windows-1252'), 'R', 1, 'R', 0);
$pdf->SetX(100);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(85, 5, mb_convert_encoding('NET A PAYER', 'windows-1252'), 'LTB', 0, 'L', 1);
$pdf->Cell(15, 5, mb_convert_encoding(number_format($total_ht + $total_tva - 50, 2, ',', ''), 'windows-1252'), 'RTB', 1, 'R', 1);


$pdf->Image('footer.png', 3, 238, 203);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(255, 18, 160);
$pdf->SetY(260);
$pdf->SetX(100);
$pdf->Cell(100, 4, mb_convert_encoding('Shootnbox enseigne exploitée par Amazing Event SAS', 'windows-1252'), 0, 1, 'C', 0);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetX(100);
$pdf->Cell(100, 3, mb_convert_encoding('5, Rue Malmaison 93170 Bagnolet / SIRET 849 542 469 00010 RCS Bobigny', 'windows-1252'), 0, 1, 'C', 0);
$pdf->SetX(100);
$pdf->Cell(100, 3, mb_convert_encoding('Capital 15,000€/ N° TVA Intracommunautaire FR46849542469', 'windows-1252'), 0, 1, 'C', 0);
$pdf->SetX(100);
$pdf->Cell(100, 3, mb_convert_encoding('IBAN : FR76 3000 4005 7000 0101 4775 434', 'windows-1252'), 0, 1, 'C', 0);
$pdf->SetX(100);
$pdf->Cell(100, 3, mb_convert_encoding('BIC : BNPAFRPPXXX', 'windows-1252'), 0, 1, 'C', 0);

if ($row_orders['status'] == 0) {
  $pdf->AddPage();
  $pdf->Image('header.png', 35, 5, 140);
  $pdf->SetY(65);
  $pdf->SetFont('Arial', '', 7);
  $pdf->Write(3.6, mb_convert_encoding("La société AMAZING EVENT (ci-après désignée « Shoot’N Box ») est une Société par Actions Simplifiée au capital de 15.000 € immatriculée au RCS de Bobigny sous le numéro 849 542 469 et dont le siège social est situé au 5, Rue Malmaison – 93170 Bagnolet.

Elle exploite le site internet www.shootnbox.fr (ci-après « le Site ») dans le but de proposer à la location des bornes photographiques autonomes et automatiques (ci-après désignée séparément « la Borne Photo » ou ensemble « les Bornes Photo ») auprès de particuliers ou de professionnels (ciaprès désignés « le Client »).", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 1. Champ d’application', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("1.1. Les présentes conditions générales de location (ci-après « les CGL ») ont pour objet de définir les conditions de location et de mise à disposition des Bornes Photo (ci-après « la Prestation »).
1.2. Elles entrent en vigueur à compter de la réception par Shoot’N Box du devis (ci-après « le Devis », tel que défini à l’article 3.3 des présentes) signé par le Client et du paiement de l’acompte correspondant (ci-après « l’Acompte », tel que défini à l’article 3.4 des présentes) et restent en vigueur pendant toute la durée de la Prestation.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 2. Les Bornes', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("Les Bornes Photos proposées à la location sur le Site sont les suivantes :
-   La Borne Photo « le Ring » (ci-après « le Ring ») ;
-   La Borne Photo « le Vegas » (ci-après « le Vegas ») ;
-   La Borne Photo « le Miroir » (ci-après « le Miroir ») ;
-   La Borne Vidéo « le Spinner » (ci-après « le Spinner »).", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 3. Réservation de la Borne', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("3.1. Les Bornes peuvent être réservées pour un évènement déterminé (ci-après « la Location Evènementielle ») ou pour une durée plus
longue (ci-après « la Location Longue Durée »), étant précisé que la Location Longue Durée est réservée aux Clients professionnels (inscrits à un
registre du commerce et des sociétés ou à un registre des métiers et disposant d’un numéro de TVA intracommunautaire).
3.2. La réservation de la Borne intervient après demande de devis effectuée par le Client sur le Site. Pour que la demande de devis soit
effective, le Client devra envoyer Shoot’N Box les renseignements suivants, via le Site :
-   Ses nom et prénom ;
-   La date et le type d’évènement envisagé ;
-   La Borne désirée ;
-   Le cas échéant, les options et personnalisations désirées ;
-   Son adresse email et son numéro de téléphone afin que les équipes de Shoot’N Box puissent le recontacter.
Par ailleurs, il indiquera en cochant les cases dédiées à cet effet :
-   S’il souhaite recevoir ou non des offres commerciales de la part de Shoot’N Box ;
-   S’il souhaite recevoir les derniers articles du blog de Shoot’N Box.
3.3. Une fois la demande envoyée :
-   Le Client recevra un récapitulatif de la demande à l’adresse email indiquée ;
-   Shoot’N Box recontactera le Client sous 24h au numéro de téléphone indiqué afin de confirmer les caractéristiques de la demande
envoyée.
3.4. Une fois les caractéristiques de la demande confirmée et validée par le Client et Shoot’N Box, Shoot’N Box adressera au Client un devis
(ci-après « le Devis ») précisant les informations suivantes :
-   La date du Devis et sa durée de validité ;
-   La Borne et les accessoires éventuels objets de la location (ci-après dénommés ensemble « le Matériel ») ;
-   Les dates de début et de fin de la Prestation ;
-   Le décompte détaillé et la description de chaque élément loué et, le cas échéant, de chaque prestation supplémentaire en quantité et en
prix unitaire ;
-   Les conditions de paiement ;
-   Les conditions de retrait ou de livraison, et le cas échéant, le prix y afférent ;
-   La procédure de réclamation ;
-   Le montant global à payer en HT et en TTC en précisant les taux de TVA applicable.
3.5. Afin de valider sa réservation de la Prestation et d’accepter la conclusion du contrat de location, le Client devra :
-   Renvoyer à Shoot’N Box le Devis signé avant l’expiration de sa durée de validité et :
-   Verser à Shoot’N Box un Acompte d’un montant de 90 € (quatre-vingt-dix euros) ou tout autre montant indiqué sur le Devis tel que
signé par le Client.
Dans le cas où le Devis ne serait pas signé et/ou l’Acompte ne serait pas versé dans un délai de 7 jours à compter de l’envoi du Devis au Client,
Shoot’N Box se réserve le droit d’annuler la réservation et de louer la ou les Borne(s) à tout tiers de son choix.
Le contrat de location ainsi formé restera en vigueur jusqu’à la restitution de la Borne Photo dans les conditions prévues à l’article 10 des présentes.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 4. Conditions Financières', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("4.1. Prix

Les prix affichés sur le Site concernent uniquement les Locations Evènementielles. Les conditions financières afférentes aux Prestations de Location
Longue Durée sont transmises au Client au moment de l’établissement du Devis.

Les prix sont affichés toutes taxes comprises, sauf mention contraire (notamment dans le cadre de prestations effectuées pour des professionnels).
Ils recouvrent uniquement les prestations indiquées sur le Site pour une location d’une durée d’une journée.

Toute option non mentionnée sur le Site (location pour une durée plus longue, modification de l’habillage extérieur de la Borne Photo, fourniture de pastilles
aimantées adhésives, bobines supplémentaires, double impression, livraison de la Borne Photo sur site, installation, etc.) sera ajoutée au Devis et
pourra faire l’objet d’une facturation supplémentaire. La signature du Devis par le Client vaudra acceptation de la facturation supplémentaire, le cas
échéant.

4.2. Paiement

4.2.1. Client particulier

Dans le cas d’une Prestation réservée par un Client particulier, agissant en qualité de consommateur tel que défini par le Code de la Consommation,
le solde du montant figurant au Devis devra être réglé au plus tard au moment de la remise du Matériel par Shoot’N Box.

A défaut de paiement dans ce délai, Shoot’N Box se réserve le droit d’annuler la réservation de la Prestation et de ne pas remettre le Matériel au
Client, sans préjudice de tout dommages-intérêts éventuels que Shoot’N Box pourrait, le cas échéant, réclamer.

4.2.2. Client professionnel

(1) Dans le cas d’une Prestation réservée par un Client agissant en qualité de professionnel (inscrit à un registre du commerce et des sociétés
ou à un registre des métiers et disposant d’un numéro de TVA intracommunautaire), après paiement de l’Acompte à la date de la réservation,
l’ensemble des factures devra être payé à réception.

(2) Tout retard de paiement à la date indiquée entrainera l’application, sans mise en demeure préalable, d’un intérêt de retard calculé
prorata temporis à compter de la date d’échéance à un taux égal à trois fois l’intérêt légal en vigueur et l’exigibilité de l’ensemble des créances non
encore échues. En outre, l’ensemble de frais de recouvrement seront mis à la charge exclusive du Client.

(3) A défaut de paiement, Shoot’N Box se réserve le droit d’annuler la réservation de la Prestation et de ne pas remettre le Matériel au Client,
sans préjudice de tout dommages-intérêts éventuels que Shoot’N Box pourrait, le cas échéant, réclamer.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 5. Conditions de location', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("5.1. Durée de la location

La location prend effet à la remise du Matériel au Client et prend fin à sa restitution. La remise du Matériel sera matérialisée par la remise au Client
d’un bon de retrait ou de livraison au moment de la remise. La restitution du Matériel sera matérialisée par la remise au Client d’un bon de
restitution au moment de la restitution du Matériel à Shoot’N Box.

5.2. Location Evènementielle

5.2.1. Location des Bornes Photo « le Ring » et « le Vegas »

(1) Dans le cadre de la location des Bornes Photo « le Ring » et « le Vegas », et sauf accord contraire des Parties précisé au Devis, le Client
sera responsable de la bonne installation et de la bonne utilisation de la Borne Photo. A cet effet, un manuel d’installation et d’utilisation lui sera
remis concomitamment au retrait ou à la livraison du matériel.
Par ailleurs, le Client disposera de l’assistante téléphonique joignable 24h/24 pendant toute la durée de la Prestation au numéro suivant :
01.45.01.66.66. En outre, si la Prestation se déroule en Ile-de-France (départements 75, 77, 78, 91, 92, 93, 94 et 95), un service de maintenance sur
site pourra être proposé en cas de nécessité.
(2) Dans le cas où le Client souhaiterait la présence d’un opérateur de Shoot’N Box pendant la Prestation, il devra solliciter celle-ci au
moment de la réservation de la Borne Photo.
La mise à disposition d’un opérateur sur place pendant la Prestation pourra entrainer la facturation de sommes supplémentaires qui seront, le cas
échéant, indiquées au Devis présenté au Client pour acceptation.

5.2.2. Location de la Borne Photo « le Miroir »

Dans le cadre de la location de la Borne Photo « le Miroir » lors d'une prestation journalière standard, un opérateur de Shoot’N Box sera à
disposition à proximité du lieu de la Prestation pendant toute la durée de celle-ci afin d’installer et d’assurer le bon fonctionnement de la Borne et
ce, sans frais supplémentaire, sauf option additionnelle sollicitée par le Client. Dans cette hypothèse, toute option additionnelle fera l’objet d’une
facturation supplémentaire qui sera indiquée au Devis présenté au Client pour acceptation.

5.3. Location longue durée

Dans le cadre de la Location Longue Durée, Shoot’N Box assurera la maintenance matérielle et logicielle du Matériel dans des conditions définies
d’un commun accord entre les Parties.
Le détail des Prestations assurées par Shoot’N Box sera énoncé dans le Devis présenté au Client pour acceptation.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 6. Annulation de la Prestation', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("6.1. Conditions d’annulation

6.1.1. En cas d’annulation de la Prestation par le Client, Shoot’N Box conservera l’intégralité de l’Acompte versé par le Client au moment de la
signature du Devis.

6.1.2. En outre, si l’annulation intervient moins de 15 (quinze) jours avant la date de la Prestation, Shoot’N Box facturera au Client l’ensemble
des frais engagés pour l’exécution de la Prestation (notamment les frais de personnalisation des Bornes Photos) avant l’annulation de la Prestation
par le Client.

6.1.3. En cas d’annulation de la Prestation par le Client moins de 2 (deux) jours avant la date prévues, l’ensemble des sommes stipulées au
Devis seront dues par le Client.

6.2. Délai de rétractation (Client non-professionnel)

Nonobstant ce qui précède, et conformément aux dispositions de l’article L.121-21 du Code de la Consommation, le Client agissant en qualité de
consommateur tel que défini par les dispositions du Code de la Consommation disposera d’un délai de rétractation de 14 jours à compter de la
conclusion du Contrat de location, étant précisé que le Contrat est considéré comme conclu à réception par Shoot’N Box du Devis signé par le
Client et du versement de l’Acompte.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 7. Retrait – Livraison', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("7.1. Location Evénementielle

7.1.1. Bornes Photo « le Ring » et « le Vegas »

La remise des Bornes Photo « le Ring » et « le Vegas » et des accessoires correspondants peut être effectuée par retrait ou par livraison.
Une fiche d’état attestant de l’état du Matériel au moment de la remise (ci-après « la Fiche d’état ») devra être signée par le Client au retrait ou à la
réception du Matériel. Il appartiendra au Client de prendre copie ou de prendre en photo la Fiche d’état qui restera en la possession de Shoot’N
Box. Dans le cas où la Fiche d’état ne serait pas remplie, les constatations de Shoot’N Box feront foi.

(1) Retrait

Le Matériel loué pourra être retiré par le Client, ou toute personne désignée par lui, sans frais supplémentaire, directement dans les locaux de
Shoot’N Box situés à l’adresse suivante : 5, Rue Malmaison – 93170 Bagnolet (ci-après « les Locaux »).
Sauf accord contraire entre Shoot’N Box et le Client, le retrait du Matériel sera effectué pendant les horaires d’ouverture des Locaux, soit du lundi au
vendredi entre 9h et 13h. A cet effet, le Client devra prendre rendez-vous avez les équipes de Shoot’N Box au plus tard 24h avant l’heure prévue
pour le retrait.
En cas de location pour un évènement se déroulant le samedi ou le dimanche, le Matériel sera retiré le vendredi et restitué le lundi sans facturation
supplémentaire.

(2) Livraison

Le Matériel loué pourra être livré à toute adresse indiquée par le Client au moment de la réservation. Dans cette hypothèse, le Matériel sera livré par
les équipes de Shoot’N Box ou par tout transporteur désigné par elle.

La livraison et l’enlèvement du Matériel à la fin de la prestation seront facturés 79 € TTC pour toute livraison effectuée dans un rayon de 30km
autour des Locaux. Au-delà, tout kilomètre supplémentaire effectué à la livraison et à l’enlèvement du matériel sera facturé 1,50 € TTC.

En cas de changement d’adresse entre le moment de la réservation, le Client devra informer Shoot’N Box par écrit dans les plus brefs délais, et au
plus tard dans les 7 (sept) jours précédents la date de livraison à l’adresse email suivante : : contact@shootnbox.fr. Dans le cas contraire, Shoot’N
Box ne pourra être tenue responsable de l’absence de livraison du Matériel et le Client restera redevable de l’ensemble des sommes prévues au
Devis. Le montant payable pour la livraison sera recalculé en fonction de la nouvelle adresse fournie.

7.1.2. Borne Photo « le Miroir »

La remise de la Borne Photo « le Miroir » s’effectue uniquement par livraison.

La livraison et l’enlèvement sont gratuits pour toute livraison effectuée dans un rayon de 30km autour des Locaux. Au-delà, tout kilomètre
supplémentaire effectué à la livraison et à l’enlèvement du Matériel sera facturé 1,50 € (un euros cinquante) TTC.

7.1.3. Borne Photo « le Spinner »

La remise de la Borne Vidéo « le Spinner » s’effectue uniquement par livraison.

La livraison et l’enlèvement sont gratuits pour toute livraison effectuée dans un rayon de 30km autour des Locaux. Au-delà, tout kilomètre
supplémentaire effectué à la livraison et à l’enlèvement du Matériel sera facturé 1,50 € (un euros cinquante) TTC.

7.2. Location longue durée

En cas de location longue durée, les modalités de retrait ou de livraison du Matériel seront définies au cas par cas entre Shoot’N Box et le Client et
seront détaillées dans le Devis soumis au Client pour acceptation.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 8. Dépôt de garantie', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("8.1. Prestation sans opérateur Shoot’N Box

8.1.1. En cas de Prestation sans opérateur Shoot’N Box, la remise du Matériel au Client (par retrait ou livraison) est subordonnée à la remise à
Shoot’N Box d’un chèque bancaire d’un montant de 1.500 € (mille cinq cents euros) à titre de garantie (ci-après « la Garantie »). Aucune retrait ou
livraison de Matériel ne sera effectué à défaut de remise de la Garantie. Il est précisé que le montant de la Garantie n’a pas pour effet de limiter la
responsabilité financière du Client qui pourra être engagée au-delà dudit montant, le cas échéant.

8.1.2. La Garantie sera restituée au Client à la restitution du Matériel après vérification par Shoot’N Box que le Matériel restitué se trouve dans
un état conforme à la Fiche d’état signé lors de la remise du Matériel. Shoot’N Box dispose 48h à compter de la restitution du Matériel pour signifier
au Client toute dégradation du Matériel non-apparente lors de la restitution et qui n’aurait pas été signalée par le Client, ou toute personne
désignée par lui pour effectuer la restitution.

8.2. Prestation en présence d’un opérateur Shoot’N Box

En cas de Prestation réalisée en présence d’un opérateur Shoot’N Box, aucun dépôt de garantie ne sera requis pour la remise du Matériel.

Néanmoins, la présence d’un opérateur Shoot’N Box n’a pas pour effet de décharger le Client de sa responsabilité à l’égard du Matériel.

En cas de dégradation du Matériel par un tiers non-affilié à Shoot’N Box lors de la Prestation, Shoot’N Box facturera au client les frais de réparation
ou de remplacement des composants du Matériel ou, le cas échéant, le prix de rachat du Matériel, ainsi que le manque à gagner résultant de
l’impossibilité de relouer le Matériel pendant le délai de réparation ou de réparation du Matériel endommagé.", 'windows-1252'));

  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 9. Obligations des Parties', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("9.1. Obligations de Shoot’N Box

Dans le cadre des présentes, Shoot’N Box s’oblige à :
-   Remettre au Client un Matériel en bon état de marche et conforme aux caractéristiques décrites dans le Devis signé par le Client ;
-   Fournir la Prestation prévue aux Devis dans les conditions prévues aux présentes.

9.2. Obligations du Client

(1) Dans le cadre des présentes, le Client s’oblige à utiliser le Matériel conformément à sa destination été aux instructions données par
Shoot’N Box. Il s’engage notamment à :
-   Placer le Matériel dans un emplacement suffisant et adapté étant entendu que le Matériel devra impérativement être placé à l’abris des
intempéries ;
-   Fournir une installation électrique avec la puissance nécessaire au bon fonctionnement du Matériel ;
-   Fournir une connexion internet avec un débit suffisant pour permettre la transmission des photos par email depuis la Borne Photo. En
cas d’absence de connexion suffisante, Shoot’N Box ne pourra en aucun cas être tenu responsable de la non-transmission immédiate des
photographies réalisées depuis la Borne Photo ;
-   Veiller à la garde, la surveillance et la conservation du Matériel en vue d’éviter d’éventuels dommages ou vol.

(2) En outre, le Client s’interdit formellement :
-   D’apporter toute modification à la programmation logicielle du Matériel loué ;
-   D’apporter toute modification esthétique au Matériel.
Toute modification effectuée sur le Matériel par le Client sera considérée comme une dégradation donnant lieu à une facturation supplémentaire
dans les conditions spécifiées aux présentes.

(3) Le Client sera seul responsable de l’obtention de toutes les autorisations nécessaires à la réalisation de la Prestation commandée,
notamment, il devra s’assurer que le lieu prévu pour l’installation du Matériel soit disponible et accessible sans difficulté.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 10. Restitution du Matériel', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("10.1. Restitution dans les Locaux

10.1.1. Date de restitution

Le Matériel ayant fait l’objet d’un retrait devra faire l’objet d’une restitution par le Client dans les Locaux à la date indiquée sur le Devis aux horaires
d’ouverture des Locaux soit entre 9h et 13h.

10.1.2. Retard – Absence de restitution de la Borne Photo

(1) En cas de non-restitution du Matériel à la date indiquée sur le Devis, Shoot’N Box facturera au Client, pour chaque Borne Photo et par
journée de retard, le prix d’une journée de location tel qu’indiqué sur le Site.

(2) En cas de retard supérieur à 7 jours, le Matériel sera considéré comme non-restitué et sera facturé au Client au prix forfaitaire, par Borne
Photo louée, de :
-   2.000 € (deux mille euros) H.T. pour la Borne Photo « le Ring » ;
-   5.000 € (cinq mille euros) H.T. pour la Borne Photo « le Vegas ».

10.2. Enlèvement du Matériel sur site

(1) En cas d’enlèvement du Matériel sur le lieu de la Prestation, un rendez-vous sera convenu entre le Client et Shoot’N Box ou tout
transporteur de son choix.

(2) La non-restitution du matériel à la date et à l’heure du rendez-vous fixé entraînera l’application des stipulations de l’article 10.1.2 des
présentes.

10.3. Dégradation du Matériel

(1) Toute dégradation du Matériel devra être signalée par le Client à Shoot’N Box au moment de la restitution du Matériel.
En cas de dégradation du Matériel, Shoot’N Box se réserve le droit de conserver définitivement tout ou partie de la Garantie sans préjudice de son
droit à dommages-intérêts supplémentaires dans le cas où le dommage subi serait supérieur.

Ce dommage pourra, sans que cette liste ne soit exhaustive, comprendre les frais de réparation ou de remplacement des composants du Matériel
ou, le cas échéant, le prix de rachat du Matériel, ainsi que le manque à gagner résultant de l’impossibilité de relouer le Matériel pendant le délai de
réparation ou de réparation du Matériel endommagé.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 12. Force Majeure', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("Dans le cas où Shoot’N Box ne serait pas en mesure d’effectuer la Prestation, ou en cas de retard dans l’exécution de la Prestation, dû à la
survenance d’un évènement de force majeure ou cas fortuit tels que définis par le Code civil et la jurisprudence des cours et des tribunaux français,
notamment en cas de guerre, émeutes incendies, grèves internes ou externes, accident, blocage des routes, impossibilités d’approvisionnement et
tout autre cas indépendant de la volonté de Shoot’N Box et empêchant l’exécution normale de la Prestation, la responsabilité de Shoot’N Box ne
saurait être engagée pour manquement à ses obligations contractuelles.

Dans cette hypothèse, Shoot’N Box en avisera immédiatement le Client et procèdera au remboursement, en tout ou partie, selon que l’impossibilité
d’effectuer la Prestation soit totale ou partielle, des sommes versées par le Client.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 13. Propriété du Matériel', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("13.1. Propriété du Matériel

Il est précisé qu’aucune clause des présentes n’a pour effet de transférer un quelconque droit de propriété au profit du Client sur le Matériel objet
de la Prestation.
En conséquence, le Matériel ne peut être ni cédé ni remis en garantie à quelque titre que ce soit. En outre, le Client ne pourra consentir à l’égard du
Matériel aucun droit au profit de quiconque susceptible d’affecter la pleine propriété de Shoot’N Box ou d’en limiter la disponibilité ou la jouissance.

13.2. Propriété intellectuelle

La marque et le logo « Shoot’N Box » ainsi que l’ensemble des éléments graphiques, les équipements logiciels et la documentation fournie par
Shoot’N Box dans le cadre de la Prestation demeurent la propriété exclusive de Shoot’N Box et/ou de ses concédants. Aucune clause des présentes
n’a pour effet de transféré un quelconque droit de propriété au profit du Client sur ces éléments.

Sauf indication expresse, le Client autorise Shoot’N Box à mentionner sa marque ainsi que les photographies prises durant la Prestation dans le
cadre de sa communication.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 14. Données personnelles', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("Toutes les informations à caractère personnel communiquées par le Client via le Site, lors de la commande ou, le cas échéant, par les Utilisateurs de
la Borne lors de l’exécution de la Prestation sont traitées par Shoot’N Box conformément aux stipulations de la Politique de Confidentialité
consultable ici.", 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->SetTextColor(255, 18, 160);
  $pdf->Write(5, mb_convert_encoding('Article 15. Loi applicable – Attribution de compétence', 'windows-1252'));
  $pdf->Ln(7.5);
  $pdf->SetFont('Arial', '', 7);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->Write(3.6, mb_convert_encoding("Les présentes conditions générales sont régies par la loi française.

Tout différend relatif à leur validité, leur exécution ou leur interprétation sera soumis à la compétence exclusive du Tribunal de Commerce de
Bobigny et ce, même en cas de référé, d’appel en garantie et/ou de pluralité de défendeurs. Nonobstant ce qui précède, tout différend impliquant
un consommateur sera soumis aux règles de compétence définies par la loi.", 'windows-1252'));
}

$pdf->Output();

mysql_close($conn);
?>
