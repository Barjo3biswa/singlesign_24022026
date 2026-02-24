
<?php 
    session_start();
    include "constants.php";
    //******************************************/
    //backend validation 
    if (empty($_POST['uname'])){
        echo json_encode([
            "result" => false,
            "msg" => "User Name is required."
        ]);
        exit;
    }
    $_SESSION['user_name'] = $_POST['uname'];
    //******************************************/
    $db=CENTRAL_AUTH;
    //$db='goalpara';
    $host        = "host = ".VERIFY_USER_DB_HOST;
    $port        = "port = ".VERIFY_USER_DB_PORT;
    $dbname      = "dbname = $db" ;

    $credentials = "user=postgres password=postgres";
    $db = pg_connect("$host $port $dbname $credentials");
    if(!$db) {
        echo json_encode([
            "result" => false,
            "msg" => "Error('#SIERR0001') : Some Error Occured..!, Please Contact Administrator..!"
        ]);
        exit;
    }
    //getting central auth data 
    $result = pg_query($db,"SELECT dhar_user as use_name,
    noc_user as code ,dist_code as dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no, 
    password_change_flag,password,mobile
    FROM central_auth where (dhar_user='$_POST[uname]' or noc_user='$_POST[uname]')");    
    $central_auth_row = pg_fetch_row($result);
    pg_close($db);
    if(!$central_auth_row){	
        //central_auth row not found 
        echo json_encode([
            "result" => false,
            "msg" => "User Not Found..!, Kindly Contact Administrator..!"
        ]);
        exit;
    }else{	
        //central auth row found 
        $password_change_flag = $central_auth_row[7];
        $mobile = $central_auth_row[9];
        if($password_change_flag == 0){
            //password not changed 
            echo json_encode([
                "result" => false,
                "msg" => "Old password is not updated yet, Kindly login with the old password..!"
            ]);
            exit;
        }
        if($mobile == "" || $mobile == null || empty($mobile)){
            //mobile no not found
            echo json_encode([
                "result" => false,
                "msg" => "Registered Mobile No Not Found..!, Kindly Contact Administrator..!"
            ]);
            exit;
        }
        if (ENABLE_LOGIN_OTP == 0) {
            echo json_encode([
                "result" => false,
                "msg" => "Can't Verify Mobile No Due To Internal Server Issue..!, Kindly Contact Administrator..!"
            ]);
            exit;
        }
		$data['values'] = array(
			'username' => $central_auth_row[0],
			'code' => $central_auth_row[1],
			'dharitree' => $central_auth_row[0],
			'noc' => $central_auth_row[1],
			'bhunaksha' => null,
			'dist_code' => $central_auth_row[2],
			'subdiv_code' => $central_auth_row[3],
			'cir_code' => $central_auth_row[4],
			'mouza_pargona_code' => $central_auth_row[5],
			'lot_no' => $central_auth_row[6],
			'map' => 'y',
            'mobile' => $mobile
		);
		$_SESSION["credentials"] = $data['values'];
        getLoginOtp($mobile);
    }
    function getLoginOtp($mobile_no)
    {
       
        $random_no = random_int(100000, 999999);
        // $_SESSION['otp'] = 123456;
        $_SESSION['otp'] = $random_no;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => SMS_API,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                    "key"       : "login_otp",
                    "variables" : "' . $_SESSION['otp'] . '",
                    "mobilenos" : "' . $mobile_no . '" 
                }',
        ));
        $response = curl_exec($curl);
        $resp = json_decode($response);
        echo json_encode([
            "result" => true,
            "msg" => $resp
        ]);
        curl_close($curl);   
    }
?>