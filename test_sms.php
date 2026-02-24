<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://172.16.3.134/rtpsmb/SmsApiController/sendSms',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"key":"login_otp",
"mobilenos":"9818730074",
"variables":"984785"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Cookie: Basu-stickysession=43d30a327c6adc25f1c806b5ab7f19d232d443ba; ci_session=pcvt4mvue58udfip2idk40iuisu9l9gm'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
