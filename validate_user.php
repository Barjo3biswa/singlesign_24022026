<?php
session_start();
include "constants.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // RCCMS API URL
    $url = RCCMS;
    $token = $_POST["jwt"] ?? "";
    $postData = [
        "jwt" => $_POST["jwt"] ?? ""
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);  
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    logMessage("API-RESPONSE".$response);
    $data = json_decode($response, true);
    //var_dump($data);
    if ($httpCode !== 200 || empty($data)) {
        die("Error: Invalid response from RCCMS server.");
    }
    $redirectUrl = $data["redirectUrl"];
    header("Location: " . $redirectUrl);
    exit();
    // $allowedDomains = ["127.0.0.1", "rccms.yourdomain.com"];
    $parsedUrl = parse_url($redirectUrl);
    // if (!isset($parsedUrl["host"]) || !in_array($parsedUrl["host"], $allowedDomains)) {
    if (!isset($parsedUrl["host"]) ) {
        die("Security Alert: Unauthorized redirect URL detected.");
    }
}
function logMessage($message)
{
    $timestamp = date('Ymd'); 
    $logFile=LOG_FILE.$timestamp.".log";
    file_put_contents($logFile, "$timestamp $message" . PHP_EOL, FILE_APPEND);
}

