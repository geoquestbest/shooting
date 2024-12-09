<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class GoogleCalendarApi
{

    public function GetAccessTokenRefresh($client_id, $client_secret, $refresh_token)
    {
        $url = 'https://accounts.google.com/o/oauth2/token';

        $curlPost = 'client_id=' . $client_id . '&client_secret=' . $client_secret . '&refresh_token=' . $refresh_token . '&grant_type=refresh_token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            throw new Exception('Error : Failed to receieve access token');
        }
        return $data;
    }

    public function GetUserCalendarTimezone($access_token)
    {
        $url_settings = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_settings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to get timezone');

        return $data['value'];
    }

    public function GetUserCalendarColors($access_token)
    {
        $url_settings = 'https://www.googleapis.com/calendar/v3/colors';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_settings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to get colors');

        return $data;
    }

    public function CreateCalendarEvent($calendar_id, $summary, $all_day, $event_time, $event_timezone, $color_id, $access_token)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events';

        $curlPost = array('summary' => $summary);
        if ($all_day == 1) {
            $curlPost['start'] = array('date' => $event_time['event_date']);
            $curlPost['end'] = array('date' => $event_time['event_date']);
        } else {
            $curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
            $curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
        }
        $curlPost['colorId'] = $color_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to create event');

        return $data['id'];
    }

    public function EditCalendarEvent($calendar_id, $event_id, $summary, $city, $description, $all_day, $event_time, $event_timezone, $color_id, $access_token)
    {
        $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events/'.$event_id;

        $curlPost = array('summary' => $summary);
        if ($all_day == 1) {
            $curlPost['start'] = array('date' => $event_time['event_date']);
            $curlPost['end'] = array('date' => $event_time['event_date']);
        } else {
            $curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
            $curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
        }
        $curlPost['colorId'] = $color_id;
        $curlPost['location'] = $city;
        $curlPost['description'] = $description;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_events);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code != 200)
            throw new Exception('Error : Failed to create event');

        return $data;
    }

}


$capi = new GoogleCalendarApi();
//const APPLICATION_ID = '937294077679-d8ou6g9pj2jglkvspgmc7h7jbsv2c92g.apps.googleusercontent.com';
const APPLICATION_ID = '1065668008565-3kd3fhf8c3naruk87pfb2qhsrr7i2omf.apps.googleusercontent.com';
const APPLICATION_REDIRECT_URL = 'https://shootnbox.fr/calendar.php';
//const APPLICATION_SECRET = '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';
const APPLICATION_SECRET = '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';


  $data = $capi->GetAccessTokenRefresh(APPLICATION_ID, APPLICATION_SECRET, "1//0385HQTzs0sbqCgYIARAAGAMSNwF-L9Ir6YpHCcSB3_wHGkTpGqNH1ypJ3SmSdzw9VXvVLYgWh3Q5MR_PK6HZM0Uvm-H0CwC_WJQ");
  //print_r($data);
  $access_token = $data['access_token'];

  $user_timezone = $capi->GetUserCalendarTimezone($data['access_token']);
  $calendar_id = 'primary';
/*

    $event_title = 'Event Title Meetanshi';

// Event starting & finishing at a specific time
    $full_day_event = 0;
    $event_time = ['start_time' => '2023-03-22T13:00:00', 'end_time' => '2023-03-22T13:15:00'];

// Full day event
    $full_day_event = 1;
    $event_time = ['event_date' => '2023-03-22'];

  print_r($capi->GetUserCalendarColors($data['access_token']));

// Create event on primary calendar
    $event_id = $capi->CreateCalendarEvent($calendar_id, $event_title, $full_day_event, $event_time, $user_timezone, 6, $data['access_token']);

    echo 'new event added';

*/
/*

Vegas = серый - 8
Ring = фиолетоввый - 3
Miroir = оранжевый - 6
Spinner = зеленый -10
*/

@require_once("inc/mainfile.php");
$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_GET['order_id']);
$row_orders = mysqli_fetch_assoc($result_orders);
if (strtotime($row_orders['take_date']) <= strtotime($row_orders['return_date']) && $row_orders['agenda'] != '0') {
    if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'retrait boutique') !== false || $row_orders['delivery_options'] == "") {
      $prefix = "R";
    } elseif (mb_strpos(strtolower(trim($row_orders['delivery_options'])), 'installation') !== false) {
      $prefix = "LIR";
    } else {
      $prefix = "LR";
    }
    $agency_id = $row_orders['agency_id'] == 1 ? 'P' : 'B';
    $event_title = $row_orders['societe'] != "" ? $agency_id." (".$prefix.") ".$row_orders['societe'] : $agency_id."(".$prefix.") ".$row_orders['first_name']." ".$row_orders['last_name'];
    if (strtotime($row_orders['take_date']) != strtotime($row_orders['return_date'])) {
      $full_day_event = 0;
      $start_time = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($row_orders['take_date'])));
      $end_time = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($row_orders['return_date'])));
      $event_time = ['start_time' => $start_time, 'end_time' => $end_time];
    } else {
      $full_day_event = 1;
      $event_time = ['event_date' => date("Y-m-d", strtotime($row_orders['take_date']))];
    }
    if (strtolower(trim($row_orders['box_type'])) == "ring") { $color_id = 3; }
    if (strtolower(trim($row_orders['box_type'])) == "vegas") {$color_id = 8;;}
    if (strtolower(trim($row_orders['box_type'])) == "miroir") {$color_id = 6;}
    if (strtolower(trim($row_orders['box_type'])) == "spinner_360") {$color_id = 10;}
    if (strtolower(trim($row_orders['box_type'])) == "réalité_virtuelle") {$color_id = 1;}

    $description =
    '<b>E-mail :</b> '.$row_orders['email'].'<br />'.
    '<b>Téléphone :</b> '.$row_orders['phone'].'<br /><br />'.
    '<b>Facture :</b> <a href="https://shootnbox.fr/manager/to_pdf.php?order_id='.$row_orders['id'].'">'.$row_orders['num_id'].'</a><br /><br />'.
    $row_orders['description'];

    var_dump($capi->EditCalendarEvent($calendar_id, $row_orders['agenda'], $event_title, $row_orders['city'], $description, $full_day_event, $event_time, $user_timezone, $color_id, $data['access_token']));
}
echo"done";