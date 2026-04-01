<?php
defined('IS_PRODUCTION')  OR define('IS_PRODUCTION', 0);
defined('IP_HOST')  OR define('IP_HOST', 'dharitreestage.assam.gov.in');
defined('NOC_IP_HOST')  OR define('NOC_IP_HOST', '172.16.3.140');
defined('NOC')  OR define('NOC', 'nocforimpssign_prod');
defined('DHARITREE')  OR define('DHARITREE', 'dhargit/dev');
defined('SINGLESIGN')  OR define('SINGLESIGN', 'single_new_dev');
defined('CHITHA_ENTRY_HOST')  OR define('CHITHA_ENTRY_HOST', 'chithastage.assam.gov.in/chitha-demo');
defined('BHUNAKSHA')  OR define('BHUNAKSHA', 'http://129.154.247.103/');
defined('SMS_API')  OR define('SMS_API', 'https://basundhara.assam.gov.in/rtpsmb/SmsApiController/sendSms111');
defined('PASS_API_UPDATION')  OR define('PASS_API_UPDATION', "https://".IP_HOST."/".DHARITREE."/index.php/dharitreeApi/updateUserPassword");
defined('RESURVEY_HOST') OR define('RESURVEY_HOST', 'chithastage.assam.gov.in/chithaapi');
defined('RESURVEY_REACT_HOST') OR define('RESURVEY_REACT_HOST', 'chithastage.assam.gov.in/resurvey');
defined('RESURVEY_HOST_EJORIP') OR define('RESURVEY_HOST_EJORIP', ' ejoripstage.assam.gov.in');
defined('VERIFY_USER_DB_HOST')  OR define('VERIFY_USER_DB_HOST', '172.16.2.218');

defined('VERIFY_USER_DB_PORT')  OR define('VERIFY_USER_DB_PORT', '5432');

defined('IS_CAPTCHA')  OR define('IS_CAPTCHA', 0);

defined('CENTRAL_AUTH')  OR define('CENTRAL_AUTH', 'central_auth_demo');
defined('NOC_MASTER')  OR define('NOC_MASTER', 'nocmaster_demo');

defined('UAT_DB_NAME')  OR define('UAT_DB_NAME', 'darrang_demo');
defined('UAT_DB_NAME_1')  OR define('UAT_DB_NAME_1', 'kamrup_demo');
defined('UAT_DB_NAME_2')  OR define('UAT_DB_NAME_2', 'dhemaji_demo');
defined('UAT_DB_NAME_3')  OR define('UAT_DB_NAME_3', 'lakhimpur_demo');
defined('UAT_DB_NAME_4')  OR define('UAT_DB_NAME_4', 'dibrugarh_demo');
defined('UAT_DB_NAME_5')  OR define('UAT_DB_NAME_5', 'jorhat_250828');
defined('UAT_DB_NAME_6')  OR define('UAT_DB_NAME_6', 'south_salmara_demo_1');
defined('UAT_DIST_CODE')  OR define('UAT_DIST_CODE', '08');
defined('UAT_DIST_CODE_1')  OR define('UAT_DIST_CODE_1', '07');
defined('UAT_DIST_CODE_2')  OR define('UAT_DIST_CODE_2', '25');
defined('UAT_DIST_CODE_3')  OR define('UAT_DIST_CODE_3', '12');
defined('UAT_DIST_CODE_4')  OR define('UAT_DIST_CODE_4', '17');
defined('UAT_DIST_CODE_5')  OR define('UAT_DIST_CODE_5', '15');
defined('UAT_DIST_CODE_6')  OR define('UAT_DIST_CODE_6', '38');
defined('BY_PASS_PWD')  OR define('BY_PASS_PWD', 'qwe@123');

defined('OTP')  OR define('OTP', '123456');
defined('KEY')  OR define('KEY', 'abcd123haryanasinglesigonapplicationDFFEFSDAFE');

defined('ENABLE_LOGIN_OTP')  OR define('ENABLE_LOGIN_OTP', 0);
defined('ENABLE_PASSWORD_CHANGE')  OR define('ENABLE_PASSWORD_CHANGE', 1);
defined('PASSWORD_EXPIRY_DAYS')  OR define('PASSWORD_EXPIRY_DAYS', 10);
defined('LOG_FILE')  OR define('LOG_FILE', '/var/www/html/dharitree/single_new_dev/logs/');
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
