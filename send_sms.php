<?php
use AfricasTalking\SDK\AfricasTalking;
use GuzzleHttp\Exception\GuzzleException;

function sendsingleSMS($recipient, $message) {
    require_once 'vendor/autoload.php';
    $username = getenv('USERNAME');
    $apikey = getenv('API_KEY') ;
    $AT = new AfricasTalking($username, $apikey);
    $sms = $AT->sms();
    try {
        $result = $sms->send([
            'to' => $recipient,
            'message' => $message
        ]);
        return $result;
    } catch (GuzzleException $e) {
        return "Error: " . $e->getMessage();
    }
}

function sendBulkSMS($recipients, $message) {
    require_once 'vendor/autoload.php';
     $username = getenv('USERNAME');
    $apikey = getenv('API_KEY') ;
    $AT = new AfricasTalking($username, $apikey);
    $sms = $AT->sms();
    try {
        $result = $sms->send([
            'to' => $recipients,
            'message' => $message
        ]);
        return $result;
    } catch (GuzzleException $e) {
        return "Error: " . $e->getMessage();
    }
}

