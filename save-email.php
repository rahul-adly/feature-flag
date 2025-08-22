<?php



header('Content-Type: application/json');

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$anonId= $data['anonId'];


require 'vendor/autoload.php';

use PostHog\PostHog;

// Init PostHog
PostHog::init('phc_xxx', ['host' => 'https://us.i.posthog.com']);

//$email = "rahul+aug2210@adly.com";
//$anonId = "d27948866f7869cbfa844ad2a5232124"; // from JS cookie

// Alias anonId → email (merge)
$result = PostHog::alias([
    'distinctId' => $anonId,
    'alias'      => $email,
]);

// Identify user with email + properties
PostHog::identify([
    'distinctId' => $email,
    'properties' => [
        'email' => $email,
    ],
]);

$res = ['data' => $result];
print_r($res);