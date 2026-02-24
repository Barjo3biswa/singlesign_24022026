<?php
        session_start();
        include "constants.php";
        $insert=array();
        $confirm=$_POST['confirm'];
        $db=datbaseswitch(CENTRAL_AUTH);
        $user=$_SESSION['credentials']['username'];
        $query="Select * from central_auth where 
            dhar_user='$user' ";
        $data=pg_query($db, $query) or die("Cannot execute query: $query\n");
        $row =pg_fetch_row($data);
        if($row){
           $data=array('error'=>'Error!! Please contact System Administrator for resolving the issue');
        }else{
            if($_SESSION['credentials']['dharitree']){
                $push['dhar_user']=$_SESSION['credentials']['username'] ;
                $push['dhar_code']=$_SESSION['credentials']['code'] ;
            }
            if($_SESSION['credentials']['noc']){
                $push['noc_user']=$_SESSION['credentials']['username'] ;
                $push['noc_roll']=$_SESSION['credentials']['code'] ;
            }
            $insert= array(
                'dist_code'=>$_SESSION['credentials']['dist_code'],
                'subdiv_code'=>$_SESSION['credentials']['subdiv_code'],
                'cir_code'=>$_SESSION['credentials']['cir_code'],
                'mouza_pargona_code'=>$_SESSION['credentials']['mouza_pargona_code'],
                'lot_no'=>$_SESSION['credentials']['lot_no'],
                'mapped_by'=> $_SESSION['credentials']['username'],
                'date_of_map'=>date('Y-m-d'),
                'password'=>md5($_SESSION['credentials']['secret']),
                'prev_password1'=>$_SESSION['credentials']['secret'],
                'mobile'=>$_SESSION['credentials']['mobile'],
            );
        $insert = array_merge($push, $insert);
        $test=pg_insert($db,'central_auth',$insert);
        $dbname=$_SESSION['credentials']['dbname'];
        $passName=explode("=",$dbname);
        $db=datbaseswitch(trim($passName['1']));
        ///////////////////
        $updateDhar=array(
                'user_map'=>'y'
        );
        
        if($_SESSION['credentials']['noc']){
            $updateQuery=array(
                'usnm'=>$_SESSION['credentials']['username'],
            );
            $table='user1';
        }else{
            $updateQuery=array(
                'use_name'=>$_SESSION['credentials']['username'],
                'user_code'=>$_SESSION['credentials']['code']
            );
            $table='loginuser_table'; 
        }
        //////////////Update in Dharitree or NOC////////////////
        $res = pg_update($db, $table, $updateDhar, $updateQuery);
        if ($res) {
            $data=array(
                'success'=>"Record Updated Successfully. Please use USERID:  $user  for next single sign on",
            );
        }else{
            $data=array('error'=>'Error in server side. Please try again');
        }
        ////////////End of Else////////////
    }
    echo json_encode($data);
    function datbaseswitch($auth){
        $db=$auth;
        $host        = "host = ".VERIFY_USER_DB_HOST;		   
        $port        = "port = ".VERIFY_USER_DB_PORT;
        $dbname      = "dbname = $db";
        $credentials = "user=postgres password=postgres";
	////*******CHANGES FOR NEW DBS*********///
            $dist_name_check = trim($auth);
            if(in_array($dist_name_check, DIST_ARRAY_1))
             {
              $host = "host = ".VERIFY_USER_DB_HOST_1;
             }
             elseif(in_array($dist_name_check, DIST_ARRAY_2))
             {
              $host = "host = ".VERIFY_USER_DB_HOST_2;
             }
             else{
              $host = "host = ".VERIFY_USER_DB_HOST;
            }
        $db =pg_connect("$host $port $dbname $credentials") or die ("Could not connect to server\n");
        return $db;  
    }

?>
