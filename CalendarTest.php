<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once(dirname(__FILE__) . '/Google/Calendar.php');

print "Start\n";

$username = 'e.putintsev@gmail.com';
$password = 'ypchmpytkmvkgchk';

$calendar = new Google_Calendar();

echo "Attempting authentication\n";
try {
    $calendar->login($username, $password);
} catch (HTTP_Request2_Exception $e) {
    echo "\nERROR:\nHTTP_Request2_Exception:\n" . $e->getMessage() . "\n\n";
    exit;
}

$lastCode = $calendar->getLastResponseCode();

echo "Response code: {$lastCode}\n";

if ( $lastCode != 200 ) {
    echo "\nERROR: Unsuccessful authentication\n";
    exit;
}

echo "Authentication successful\n";

// аутентификация успешна,
// можно добавлять событие

try {
    $calendar->addEvent(date('d.m.Y'), 'New my event');
} catch (exception $e) {
    echo "\nERROR:\nException:\n" . $e->getMessage() . "\n\n";
    exit;
}

$lastCode = $calendar->getLastResponseCode();
echo "Response code: {$lastCode}\n";

if ( $lastCode != 201 ) {
    echo "\nERROR: Failed to create event\n";
    exit;
}

// тест удачный
echo "The event was created.\n";
echo "Server reports:\n";

echo $calendar->getLastResponse()
    ->getBody() . "\n";

echo "SUCCESSFULL. END\n\n";

