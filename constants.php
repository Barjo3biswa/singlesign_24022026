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
defined('PASS_API_UPDATION')  OR define('PASS_API_UPDATION', "https://".IP_HOST."/".DHARITREE."/index.php/dharitreeApi/updateUserPassword");
defined('VERIFY_USER_DB_HOST')  OR define('VERIFY_USER_DB_HOST', '172.16.2.160');
defined('VERIFY_USER_DB_PORT')  OR define('VERIFY_USER_DB_PORT', '5432');

defined('DIST_ARRAY_1')  OR define('DIST_ARRAY_1', ['biswanath','charaideo','majuli','karimganj','bongaigaon','hojai','jorhat','goalpara','nalbari','kamrupM','golaghat','tinsukia']);
defined('DIST_ARRAY_2')  OR define('DIST_ARRAY_2', ['lakhimpur','dibrugarh','dhemaji','sibsagar','sonitpur','barpeta','darrang','morigaon','bajali','nagaon','dhubri','ssalmara','kamrup','chirang','hailakandi','cachar']);
defined('VERIFY_USER_DB_HOST_1')  OR define('VERIFY_USER_DB_HOST_1', '172.16.2.97');
defined('VERIFY_USER_DB_HOST_2')  OR define('VERIFY_USER_DB_HOST_2', '172.16.2.160');

defined('IS_CAPTCHA')  OR define('IS_CAPTCHA', 1);

defined('CENTRAL_AUTH')  OR define('CENTRAL_AUTH', 'central_auth');
defined('NOC_MASTER')  OR define('NOC_MASTER', 'nocmaster');

defined('UAT_DB_NAME')  OR define('UAT_DB_NAME', 'kamrup_demo');
defined('UAT_DIST_CODE')  OR define('UAT_DIST_CODE', '07');
defined('BY_PASS_PWD')  OR define('BY_PASS_PWD', 'qwe@123');

defined('OTP')  OR define('OTP', '123456');
defined('KEY')  OR define('KEY', 'abcd123haryanasinglesigonapplicationDFFEFSDAFE');

defined('ENABLE_LOGIN_OTP')  OR define('ENABLE_LOGIN_OTP', 1);
defined('ENABLE_PASSWORD_CHANGE')  OR define('ENABLE_PASSWORD_CHANGE', 1);
defined('PASSWORD_EXPIRY_DAYS')  OR define('PASSWORD_EXPIRY_DAYS', 30);
defined('ENABLE_EXPIRY_PASSWORD_MODULE')  OR define('ENABLE_EXPIRY_PASSWORD_MODULE', 1);
defined('LOG_FILE')  OR define('LOG_FILE', '/var/www/html/Singlesign/logs/');

defined('RESURVEY_HOST') OR define('RESURVEY_HOST', 'chitha.assam.gov.in/chithaapi');
defined('RESURVEY_REACT_HOST') OR define('RESURVEY_REACT_HOST', 'chitha.assam.gov.in/resurvey');
?>
