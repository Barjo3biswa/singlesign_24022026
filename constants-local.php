<?php
defined('IS_PRODUCTION')  OR define('IS_PRODUCTION', 1);
defined('IP_HOST')  OR define('IP_HOST', 'dharitree.assam.gov.in');
//defined('IP_HOST')  OR define('IP_HOST', '172.16.3.95');
defined('NOC_IP_HOST')  OR define('NOC_IP_HOST', 'nocgovt.assam.gov.in');
defined('NOC')  OR define('NOC', 'nocforimpssign');
defined('DHARITREE')  OR define('DHARITREE', 'DharitreeSVN');
defined('SINGLESIGN')  OR define('SINGLESIGN', 'Singlesign');
defined('CHITHA_ENTRY_HOST')  OR define('CHITHA_ENTRY_HOST', 'chitha.assam.gov.in');
defined('BHUNAKSHA')  OR define('BHUNAKSHA', 'https://bhunaksha.assam.gov.in/');
defined('BHUNAKSHA_URL')  OR define('BHUNAKSHA_URL', 'https://bhunaksha.assam.gov.in/viewMap?token=');
defined('JWT_SECRET_KEY') OR define('JWT_SECRET_KEY','248de231b95682e768301be1dd5f6cfac8fb4f18');
defined('JWT_BHUNAKSHA_SECRET_KEY') OR define('JWT_BHUNAKSHA_SECRET_KEY','ghkjdtrjgkhohkbjdydhfkglgihgyrebrtbfvrvr3rcwlkjoijwmnbvwjegckencjkwegcyg3cb3kbchj3gc23ckjn2jkldhiu43gdjk43nj43hui4gfkj4fb4jfgyefdvjdtenf');
defined('RCCMS')  OR define('RCCMS', "https://rccms.assam.gov.in/rccms_live/v1/ssoLogin/userLoginSso");
//defined('RCCMS')  OR define('RCCMS', "https://129.154.254.176/rccms_stage/v1/ssoLogin/userLoginSso");
//defined('SMS_API')  OR define('SMS_API', 'https://basundhara.assam.gov.in/rtpsmb/SmsApiController/sendSms');
defined('SMS_API')  OR define('SMS_API', 'http://172.16.3.134/rtpsmb/SmsApiController/sendSms');
defined('PASS_API_UPDATION')  OR define('PASS_API_UPDATION', "https://".IP_HOST."/".DHARITREE."/index.php/dharitreeApi");
defined('VERIFY_USER_DB_HOST')  OR define('VERIFY_USER_DB_HOST', 'localhost');
defined('VERIFY_USER_DB_PORT')  OR define('VERIFY_USER_DB_PORT', '5432');

defined('DIST_ARRAY_1')  OR define('DIST_ARRAY_1', ['biswanath','charaideo','majuli','karimganj','bongaigaon','hojai','jorhat','goalpara','nalbari','kamrupM','golaghat','tinsukia']);
defined('DIST_ARRAY_2')  OR define('DIST_ARRAY_2', ['lakhimpur','dibrugarh','dhemaji','sibsagar','sonitpur','barpeta','darrang','morigaon','bajali','nagaon','dhubri','ssalmara','kamrup','chirang','hailakandi','cachar']);
defined('VERIFY_USER_DB_HOST_1')  OR define('VERIFY_USER_DB_HOST_1', 'localhost');
defined('VERIFY_USER_DB_HOST_2')  OR define('VERIFY_USER_DB_HOST_2', 'localhost');

defined('IS_CAPTCHA')  OR define('IS_CAPTCHA', 1);

defined('CENTRAL_AUTH')  OR define('CENTRAL_AUTH', 'central_auth');
defined('NOC_MASTER')  OR define('NOC_MASTER', 'nocmaster');

defined('UAT_DB_NAME')  OR define('UAT_DB_NAME', 'kamrup_uat');
defined('UAT_DIST_CODE')  OR define('UAT_DIST_CODE', '07');
defined('BY_PASS_PWD')  OR define('BY_PASS_PWD', 'qwe@123');

defined('OTP')  OR define('OTP', '123456');
defined('KEY')  OR define('KEY', 'abcd123haryanasinglesigonapplicationDFFEFSDAFE');

defined('ENABLE_LOGIN_OTP')  OR define('ENABLE_LOGIN_OTP', 0);
defined('ENABLE_PASSWORD_CHANGE')  OR define('ENABLE_PASSWORD_CHANGE', 1);
defined('PASSWORD_EXPIRY_DAYS')  OR define('PASSWORD_EXPIRY_DAYS', 30);
defined('ENABLE_EXPIRY_PASSWORD_MODULE')  OR define('ENABLE_EXPIRY_PASSWORD_MODULE', 1);
defined('LOG_FILE')  OR define('LOG_FILE', 'D:/wamp64/www/Audit/Singlesign/logs/');

defined('RESURVEY_HOST') OR define('RESURVEY_HOST', 'chitha.assam.gov.in/chithaapi');
defined('RESURVEY_REACT_HOST') OR define('RESURVEY_REACT_HOST', 'chitha.assam.gov.in/resurvey');
defined('EJORIP_REACT_HOST') OR define('EJORIP_REACT_HOST', '129.154.251.69');

// ---------- HTTP SECURITY HEADERS GLOBALLY ENFORCED ---------- //
// 1. Content Security Policy (CSP)
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:;");

// 2. Cross-Origin Resource Sharing (CORS)
header("Access-Control-Allow-Origin: *"); // Note: In strict production environments, replace '*' with your specific domain like 'https://assam.gov.in'
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, X-CSRF-TOKEN");

// 3. Additional Clickjacking and MIME-type Protection
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// 4. Missing Security Headers (Referrer, Permissions, XSS, Cache)
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// 5. Prevent Version Disclosure
header_remove("X-Powered-By");

// ---------- HTTP METHOD PROTECTION GLOBALLY ENFORCED ---------- //
$allowed_methods = ['GET', 'POST'];
if (isset($_SERVER['REQUEST_METHOD']) && !in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
    http_response_code(405);
    exit('Method Not Allowed');
}

// ---------- SECURE SESSION COOKIE GLOBALLY ENFORCED ---------- //
// Enforce Secure, HttpOnly, and SameSite flags on session cookies
if (session_status() === PHP_SESSION_NONE) {
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => true,      // Ensures the cookie is only sent over HTTPS (fixes the "Cookie without Secure Flag" vulnerability)
            'httponly' => true,    // Prevents JavaScript access to the session cookie
            'samesite' => 'Strict' // Protects against advanced CSRF attacks
        ]);
    } else {
        session_set_cookie_params(0, '/; samesite=Strict', '', true, true);
    }
    session_start();
}

// ---------- CSRF PROTECTION GLOBALLY ENFORCED ---------- //
if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
    $token = '';
    
    if (isset($headers['X-CSRF-TOKEN'])) {
        $token = $headers['X-CSRF-TOKEN'];
    } elseif (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    } elseif (isset($_POST['csrf_token'])) {
        $token = $_POST['csrf_token'];
    }

    if (empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['result' => false, 'msg' => 'Invalid or missing CSRF token', 'success' => false, 'status' => 'blocked', 'errors' => ['msg'=>'CSRF token mismatch']]);
        exit;
    }
}
?>
