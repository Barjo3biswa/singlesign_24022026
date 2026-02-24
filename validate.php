<?php
		session_start();
		include "constants.php";
		$nocUser=$_POST['nocUser'];
        $password=md5($_POST['nocUserPass']);
        $dharpassword=$_POST['nocUserPass'];
        $dharuSER=$_SESSION['credentials']['username'];
        /////////////Only For Dharitree User Checking alreday mapped user ///////////////
        if($_SESSION['credentials']['dharitree']){
        	$dbname=$_SESSION['credentials']['dbname'];
        	$passName=explode("=",$dbname);
        	//var_dump($passName);
        	$db=datbaseswitch(trim($passName['1']));
        	$query="select user_map from loginuser_table where nocuser='$nocUser' ";
	        $data=pg_query($db, $query) or die("Cannot execute query: $query\n");
	        $row =pg_fetch_row($data);
	        if($row==false){
	        		///////////////User Exist Or Not///////////////////
	        	    $db=datbaseswitch(NOC_MASTER);
		        	$query="select usnm,usroll from user1 where usnm='$nocUser' ";
			        $data=pg_query($db, $query) or die("Cannot execute query: $query\n");
			        $result =pg_fetch_row($data);
			        if($result==false){
			        	$data=array('error'=>"NOC User Not Found or password not matched",
	        			'success'=>NULL);
	        			//exit;
			        }else{
	        		///////////////////////////////////////
	        		updateCentralAuth($result[0],$result[1]);
	            	$updateDhar=array(
			                'nocuser'=>$nocUser,
			                'user_map'=>'y'
			        );
	            	$updateQuery=array(
	            		'use_name'=>$_SESSION['credentials']['username'],
            			'user_code'=>$_SESSION['credentials']['code']
	            	);
	            	$db=datbaseswitch(trim($passName['1']));
	            	$res = pg_update($db,'loginuser_table', $updateDhar, $updateQuery);
	            	//var_dump($res);
		            $updateNoc = array(
		            	'dharitree_user' =>$_SESSION['credentials']['username'] ,
		                'user_map'=>'y' );
		            $updateQuery=array(
	            		'usnm'=>$nocUser
	            	);
	            	$db=datbaseswitch(NOC_MASTER);
	            	$res1 = pg_update($db, 'user1', $updateNoc, $updateQuery);
	            	//var_dump($res1);
	            	$data=array('success'=>"Dharitree User mapped successfully! Please logout and login again for use of Single Sign-on application and your USER ID for single sign on is $dharuSER ",
	            		'error'=>NULL
	            	);
	            	//exit;
	            }	
	        }else{
	        		$data=array('error'=>"Dharitree User already mapped. Please logout and login again for use of Single Sign-on application",
	        			'success'=>NULL);
	        		//exit;
	        }
        }else if($_SESSION['credentials']['noc']){
        	$dbname=$_SESSION['credentials']['dbname'];
        	$passName=explode("=",$dbname);
        	$db=datbaseswitch(NOC_MASTER);
        	$query="select user_map from user1 where usnm='$dharuSER' ";
	        $data=pg_query($db, $query) or die("Cannot execute query: $query\n");
	        $row =pg_fetch_row($data);
	        if($row==false){
	        		///////////////User Exist Or Not///////////////////
	        	    $db=datbaseswitch(NOC_MASTER);
		        	$query="select use_name,user_code from loginuser_table where use_name='$nocUser' ";
			        $data=pg_query($db, $query) or die("Cannot execute query: $query\n");
			        $result =pg_fetch_row($data);
			        if($result==false){
			        	$data=array('error'=>"Dharitree User Not Found or password not matched",
	        			'success'=>NULL);
	        			//exit;
			        }else{
	        		///////////////////////////////////////
	        		updateCentralAuth($result[0],$result[1]);
	            	$updateDhar=array(
			                'nocuser'=>$_SESSION['credentials']['username'],
			                'user_map'=>'y'
			            );
	            	$updateQuery=array(
	            		'use_name'=>$nocUser,
	            	);
	            	$res = pg_update($db, 'loginuser_table', $updateDhar, $updateQuery);
		            $updateNoc = array(
		            	'dharitree_user' =>$nocUser,
		                'user_map'=>'y' );
		            $updateQuery=array(
	            		'usnm'=>$_SESSION['credentials']['username']
	            	);
	            	$db=datbaseswitch(NOC_MASTER);
	            	$res = pg_update($db, 'user1', $updateDhar, $updateQuery);
			$data=array(
				'success'=>"NOC User mapped successfully! Please logout and login again for use of Single Sign-on application and your USER ID for single sign on is $dharuSER",
	            		'error'=>NULL
	            	);
	            	//exit;
	            }
	        }else{
			$data=array(
			'error'=>"NOC User already mapped. Please logout and login again for use of Single Sign-on application",
	      		'success'=>NULL
	        	);
	        	//exit;
	        }
        }
        echo json_encode($data);
        /////////////////////////////////////
        function datbaseswitch($auth){
           $db=$auth;
	   if(in_array($auth,DIST_ARRAY_1)){
	     $host        = "host = ".VERIFY_USER_DB_HOST_1;		   
	   }else if(in_array($auth,DIST_ARRAY_2)){  
	     $host        = "host = ".VERIFY_USER_DB_HOST_2;		   
	   }else{
	     $host        = "host = ".VERIFY_USER_DB_HOST;
	   }
           $port        = "port = ".VERIFY_USER_DB_PORT;
           $dbname      = "dbname = $db";
           $credentials = "user=postgres password=postgres";
	   logMessage('AUTH###'.$host."###HOST###".$dbname."####DBNAME###".$port."#####PORT####");
           $db =pg_connect("$host $port $dbname $credentials") or die ("Could not connect to server\n");
           return $db;  
    	}
        function updateCentralAuth($user,$code){
            if($_SESSION['credentials']['dharitree']){
                $push['dhar_user']=$_SESSION['credentials']['username'] ;
                $push['dhar_code']=$_SESSION['credentials']['code'] ;
                $push['unique_user_id']=$_SESSION['credentials']['username'];
                $push['noc_user']=$user;
                $push['noc_roll']=$code;
            }
            if($_SESSION['credentials']['noc']){
                $push['noc_user']=$_SESSION['credentials']['username'] ;
                $push['noc_roll']=$_SESSION['credentials']['code'] ;
                $push['unique_user_id']=$_SESSION['credentials']['username'];
                $push['dhar_user']=$user;
                $push['dhar_code']=$code;
            }
            $insert= array(
                'dist_code'=>$_SESSION['credentials']['dist_code'],
                'subdiv_code'=>$_SESSION['credentials']['subdiv_code'],
                'cir_code'=>$_SESSION['credentials']['cir_code'],
                'mouza_pargona_code'=>$_SESSION['credentials']['mouza_pargona_code'],
                'lot_no'=>$_SESSION['credentials']['lot_no'],
                'mapped_by'=> $_SESSION['credentials']['username'],
                'date_of_map'=>date('Y-m-d'),
                'password'=>$_SESSION['credentials']['secret'],
                'prev_password1'=>$_SESSION['credentials']['secret'],
                'password_change_flag'=>$_SESSION['credentials']['password_change_flag'],
                'mobile'=>$_SESSION['credentials']['mobile'],
            );
            $db=datbaseswitch(CENTRAL_AUTH);
	    $insert = array_merge($push, $insert);
	    $test=pg_insert($db,'central_auth',$insert);
        }
	function logMessage($message)
        {
    		$timestamp = date('Ymd');
		$logFile=LOG_FILE.$timestamp.".log";
		file_put_contents($logFile, "$timestamp $message" . PHP_EOL, FILE_APPEND);
	}

        exit;
