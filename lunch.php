#!/usr/bin/php

<?php
// this is a lunch script I put together so that we don't have to log into PD 
// every time on call wants to grab lunch and needs coverage

// set the default timezone to use.
date_default_timezone_set('America/Phoenix');
$person_doing_override = strtolower($argv[1]);
$when_to_switch = $argv[2];

switch ($person_doing_override) {
    case "kblankenship":
        $person_doing_override = "USERID1";
        break;
    case "vromano":
        $person_doing_override = "USERID2";
        break;
    default:
	echo "./lunch.php [user overriding] [now/5]\n";
	exit();
}

switch ($when_to_switch) {
    case "now":
        $when_to_switch = date("c");
	$when_to_switch_back = date("c", strtotime('+60 minutes'));
        break;
    case 5:
	$when_to_switch = date("c", strtotime('+5 minutes'));
	$when_to_switch_back = date("c", strtotime('+65 minutes'));
	break;
    default:
        echo "./lunch.php [user overriding] [now/5]\n";
        exit();
}

// using this key anywhere but lunch is a paddlin'
$API_ACCESS_KEY = 'ACCESSKEY';
$SCHEDULE_ID = 'SCHEDULE';
$PAYLOAD = array(
  'override' => array(
    'start' => $when_to_switch,
    'end' => $when_to_switch_back,
    'user' => array(
      'id' => $person_doing_override,
      'type' => 'user_reference'
    )
  )
);

// thanks Pagerduty for literally never saying it needed to be json_encoded.
$JSON = json_encode($PAYLOAD);
$URL = 'https://api.pagerduty.com/schedules/' . urlencode($SCHEDULE_ID) . '/overrides';
$session = curl_init();
curl_setopt($session, CURLOPT_URL, $URL);
curl_setopt($session, CURLOPT_HTTPHEADER, array(
    'Content-type: application/json',
    'Authorization: Token token=' . $API_ACCESS_KEY
));
curl_setopt($session, CURLOPT_POSTFIELDS, $JSON);
$output = curl_exec($session);
echo "$output.\n\n";

