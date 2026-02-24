<?php session_start();
include "constants.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ILRMS | Government of Assam</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="assets/img/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.bxslider.css">
	 <!-- Script -->
    <script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.bxslider.min.js"></script>
</head>
<body>
	<div class="ilrms_belowbanner">
    <div class="container">
        <div class="col-lg-12">
                    <div class="row">
					<div class='col-lg-8'>
						<div class="border_login">
 <div class="container-fluid">
 	<div class="panel panel-info panel-form">
 		<div class="panel-heading">
                    <h3 class="panel-title">Please Map Dharitree and NOC user for Single Sign On</h3>
        </div>
 		<div class="panel-body">
 			<?php

 				 if($_SESSION['credentials']['noc']) { 
 				 	$var="Dharitree";
 				 }else if($_SESSION['credentials']['dharitree']){
 				 	$var="NOC";
 				 }
 			?>
 			<form class="form-horizontal" id='validate_form' method="POST">
 				<div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label"><?=$var?> User ID</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="<?=$var?> User ID" name="nocUser">
                    </div>
                
                    <label for="inputEmail3" class="col-sm-4 control-label"><?=$var?> Password</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" placeholder="<?=$var?> Password" name="nocUserPass">
                    </div>
                </div>
                <span id='loading'></span><span id='msg'></span>
                <div class="form-group" >
                <input class="btn col-md-6 btn-primary btn-block" type="submit" value="Please Click Here to Validate User" >
            	</div>
 			</form>
 			<a id='logout' href="logout.php">Sign Out</a>
 		</div>
 	</div>
 </div>
</div>
</div>
</div>
</div>
</div>
</div>

 <!-- Modal Start -->
<!-- <div class="modal" id="myModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"> -->
    <div class="modal" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">User Confirmation for using Single Sign On Application</h4>
        </div>
        <div class="modal-body">
          Do You Have Access in Both <b class="red">Dharitree</b> and <b class="red">NOC</b> application ? 
          <form id='userConfirm' method="post">
              <div class="form-check-inline">
                  <input class="form-check-input" type="radio" value="Y" name="confirm" id="confirmY">
                  <label class="form-check-label" for="flexRadioDefault1">
                    Yes
                  </label>
              </div>
              <div class="form-check-inline">
                  <input class="form-check-input" type="radio" value="N" name="confirm" id="confirmN">
                  <label class="form-check-label" for="flexRadioDefault1">
                    No
                  </label>
              </div>
              <hr>
              <span id='mloading'></span>
              <span id='modalmsg'></span>
              <div id='proceed'>
              <?php if($_SESSION['credentials']['noc']) { ?>
                    <a class="cta" href="http://<?=IP_HOST?>/<?=NOC?>/index.php/login/SingleSign/<?php echo $_SESSION['credentials']['username'] ?>">
              <?php }else if($_SESSION['credentials']['dharitree']) { ?>
              		<a class="cta" href="http://<?=IP_HOST?>/<?=DHARITREE?>/index.php/login/nocBypass/<?=$_SESSION['credentials']['username']?>/<?=$_SESSION['credentials']['dist_code']?>"></a>
              <?php } ?>
          	  </div>
            <center>  <input type="submit" name="Please confirm" class="btn modalbtn btn-success"></center>
          </form>
        </div>
      </div>
      
    </div>
  </div>
<!--  ////////////////////////////// -->
 <script type="text/javascript">
  $(document).ready(function(){
    $(window).on('load', function() {
        $('#myModal').modal('show',{
            backdrop: 'static',
            keyboard: false,
            });
    });
    $('#userConfirm').on('submit', function(event){
        event.preventDefault();
        if(document.getElementById('confirmY').checked) {
            var confirmY=$('#confirmY').val();
        }else if(document.getElementById('confirmN').checked){
            var confirmN=$('#confirmN').val();
        }
        if(confirmY=='Y'){
            $('#myModal').modal('hide');
        }else{
            var formData = $(this).serialize();
            var url='userConfirmation.php';
            $.ajax({
                type        : 'POST', 
                url         : url, 
                data        : formData, 
                dataType    : 'json', 
                encode      : true,
                beforeSend: function(){
                        $("#mloading").html("Updating ...Please wait...");
                        $('.modalbtn').hide();
                        $('#proceed').hide();
                    },
                success: function(data){
                    if(data.error){
                    	$("#mloading").hide();
                    	$('#proceed').hide();
                      $('#modalmsg').html('<div class="alert alert-danger text-center">' + data.error + '</div>');
                      $('#modalmsg').append('<a class="btn btn-danger" href=\'logout.php\'>Logout</a>');
                    }else if(data.success){
                    	$("#mloading").hide();
                    	$('#proceed').show();
                        $('#modalmsg').html('<div class="alert alert-success">' + data.success + '</div>');
                        $('#modalmsg').append('<a class="btn btn-danger" href=\'logout.php\'>Logout</a>');
                    }
                },
            });
        }       
    });
    //////////////////////////
  $('#validate_form').on('submit', function(event){
  		event.preventDefault();
		var formData = $(this).serialize();
		var url='validate.php';
        $.ajax({
            type        : 'POST', 
            url         : url, 
            data        : formData, 
            dataType    : 'json', 
            encode      : true,
            beforeSend: function(){
                        $("#loading").html("Validating ...Please wait...");
                        $('.btn-block').hide();
                        $('.red').hide();
                        $('.alert').hide();
                    },
            success: function(data){
            	console.log(data);
            	if(data.success!=null){
            		//alert('hai');
            		$("#loading").hide();
            		$('#msg').append('<div class="alert alert-info text-center">' + data.success + '</div>');
            	}else if(data.error!=null){
            		$("#loading").hide();
            		$('.btn-block').show();
            		$('#msg').html('<div class="alert alert-danger text-center">' + data.error + '</div>');
            	}
            },
        });
    });
});
</script>
</body>
</html>