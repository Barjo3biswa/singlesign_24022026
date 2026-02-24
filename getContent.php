<?php
session_start();
include "constants.php";
//var_dump($_SESSION);
?>
<style>
   .hide {
      display: none
   }
   .watermark {
      opacity: 0.5;
      color: red;
      -webkit-transform: rotate(-45deg);
      -moz-transform: rotate(-45deg);
      bottom: 120px;
      right: 0;
      z-index: -10000
   }
</style>
<div class="col-lg-12">
   <div class="row">
      <div class="col-lg-12">
         <h4 class="hide"><i class="fa fa-exclamation-circle"></i>&nbsp; Please Select Application you want to work </h4>
         <div class="row">
            <?php if ($_SESSION['credentials']['noc']) { ?>
               <div class="col-lg-4">
                  <div class="service-block-container hide_block">
                     <div class="service-block ">
                        <div class="service-underlay ">
                           <span class="service-name ">
                              NOC
                           </span>
                           <?php $user = $_SESSION['credentials']['noc']; ?>
                        </div>
                        <?php if ($_SESSION['credentials']['map'] == 'y' && (!in_array($_SESSION['user_desig_code'], ['CDA','DDA','ADA']))) { ?>
                           <a class="cta" href="#"  id="nocLoginBypass"> <span class="service-icon">
                                 <em style="color: #929235;" class="fa fa-3x fa-newspaper-o"></em>
                              </span> 
                           </a>
                           <?php } else { ?>
                              <a class="cta" href="https://<?=IP_HOST?>/<?=SINGLESIGN?>/mappeduser.php">
                                 <span class="service-icon">
                                 <em style="color: #929235;" class="fa fa-3x fa-newspaper-o"></em>
                              </span></a>
                              <?php } ?>
                              
                              <span class="service-desc hide">No Objection Certificate </span>
                     </div>
                  </div>
               </div>
            <?php } else { ?>
               <div class="col-lg-4">
                  <div class="service-block-container " style="opacity: 0.4">
                     <div class="service-block ">
                        <span class="service-icon">
                           <em style="color: #929235;" class="fa fa-3x fa-newspaper-o"></em>
                           <p class="watermark">Not Accessible Not Accessible Not Accessible Not Accessible Not Accessible </p>
                        </span>
                        <span class="service-desc hide">No Objection Certificate </span>
                     </div>
                  </div>
               </div>
            <?php }
            if ($_SESSION['credentials']['dharitree']) {
               $user = $_SESSION['credentials']['username']; ?>
               <div class="col-lg-4">
                  <div class="service-block-container test hide_block">
                     <div class="service-block">
                        <div class="service-underlay">
                           <span class="service-name">Dharitree</span>
                        </div>
                        <?php if ($_SESSION['credentials']['map'] == 'y' && (!in_array($_SESSION['user_desig_code'], ['DDA','ADA','CDA']))) { ?>
                           <a class="cta" href="#" id="dharitreeLoginBypass"> 
                           <span class="service-icon" ><em style="color: #929235;" class="fa fa-3x fa-drivers-license-o"></em>
                           </span>
                           </a>
                           <?php } else { ?>
                              <a class="cta" href="https://<?=IP_HOST?>/<?=SINGLESIGN?>/mappeduser.php">
                              <span class="service-icon" ><em style="color: #929235;" class="fa fa-3x fa-drivers-license-o"></em>
                              </span>
                              </a>
                              <?php } ?>
                        
                        <span class="service-desc hide">Land Records </span>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="service-block-container test hide_block" id='bhunaksha'>
                     <div class="service-block">
                        <div class="service-underlay">
			   <span class="service-name">
                              Bhunaksha
                           </span>
                        </div>
			<a class="cta" href="bhunaksha2.php">
                        <span class="service-icon">
                           <em style="color: #929235;" class="fa fa-3x fa-map"></em>
                        </span>
                        </a>
                        <span class="service-desc hide">
                           Digitized cadastral maps
                        </span>
                     </div>
                  </div>
               </div>
            <?php } else { ?>
               <div class="col-lg-4">
                  <div class="service-block-container " style="opacity: 0.4">
                     <div class="service-block ">
                        <span class="service-icon">
                           <em style="color: #929235;" class="fa fa-3x fa-newspaper-o"></em>
                           <p class="watermark"> Not Accessible Not Accessible Not Accessible Not Accessible Not Accessible </p>
                        </span>
                        <span class="service-desc hide">Dharitree</span>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="service-block-container " style="opacity: 0.4">
                     <div class="service-block ">
                        <span class="service-icon">
                           <em style="color: #929235;" class="fa fa-3x fa-newspaper-o"></em>
                           <p class="watermark">Not Accessible Not Accessible Not Accessible Not Accessible Not Accessible </p>
                        </span>
                        <span class="service-desc hide">Digitized cadastral maps</span>
                     </div>
                  </div>
               </div>
            <?php } ?>
            <?php if (isset($_SESSION['chitha_data']) && (!empty($_SESSION['credentials']['dharitree']))) : ?>
               <?php if ((!in_array($_SESSION['user_desig_code'], ['DDA','ADA','DEO','CDA']))) : ?>
                  <!-- Chithaentry -->
                  <div class="col-lg-4" id="chithaEntry">
                     <div class="service-block-container test">
                        <div class="service-block">
                           <div class="service-underlay">
                              <span class="service-name">
                                 Chitha Entry
                              </span>
                              <a class="cta" href=""></a>
                           </div>
                           <span class="service-icon">
                              <em style="color: #929235;" class="fa fa-3x fa-user-circle-o"></em>
                           </span>
                           <span class="service-desc hide">
                              Chitha Entry
                           </span>
                        </div>
                     </div>
                  </div>
               <?php endif; ?>
	    <?php endif; ?>
	    <?php if (isset($_SESSION['chitha_data']) && (!empty($_SESSION['credentials']['dharitree']))) : ?>
   		<?php if ((!in_array($_SESSION['user_desig_code'], ['DDA','ADA','DEO','CDA']))) : ?>
		      <!-- Chithaentry -->
		      <div class="col-lg-4" id="chithaEntry_barak">
		         <div class="service-block-container test">
		            <div class="service-block">
		               <div class="service-underlay">
		                  <span class="service-name">
		                     Chitha Entry(Barak)
		                  </span>
		                  <a class="cta" href=""></a>
		               </div>
		               <span class="service-icon">
		                  <em style="color: #929235;" class="fa fa-3x fa-user-circle-o"></em>
		               </span>
		               <span class="service-desc hide">
		                  Chitha Entry(Barak)
		               </span>
		            </div>
		         </div>
		      </div>
		<?php endif; ?>
	    <?php endif; ?>
	    <?php if (isset($_SESSION['resurvey_data']) && (!empty($_SESSION['credentials']['dharitree']))) : ?>
               <?php if ((!in_array($_SESSION['user_desig_code'], ['DDA','ADA','DEO','CDA']))) : ?>
                  <!-- Chithaentry -->
                  <div class="col-lg-4" id="resurvey">
                     <div class="service-block-container test">
                        <div class="service-block">
                           <div class="service-underlay">
                              <span class="service-name">
                                 Resurvey
                              </span>
                              <a class="cta" href=""></a>
                           </div>
                           <span class="service-icon">
                              <em style="color: #929235;" class="fa fa-3x fa-user-circle-o"></em>
                           </span>
                           <span class="service-desc hide">
                              Resurvey
                           </span>
                        </div>
                     </div>
                  </div>
               <?php endif; ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['credentials']['dharitree'])) : ?>
               <?php if (in_array($_SESSION['user_desig_code'], ['DC','ADC','CO','AST','DDA','ADA','CDA'])) : ?>
                  <!-- Chithaentry -->
                  <div class="col-lg-4" id="rccms">
                     <div class="service-block-container">
                        <div class="service-block">
                           <div class="service-underlay">
                              <span class="service-name">
                                 RCCMS
                              </span>
                              <a class="cta" href=""></a>
                           </div>
                           <span class="service-icon">
                              <em style="color: #929235;" class="fa fa-3x fa-file"></em>
                           </span>
                           <span class="service-desc hide">
                              Revenue Court
                           </span>
                        </div>
                     </div>
                  </div>
               <?php endif; ?>
            <?php endif; ?>
            <center>
               <div class="col-lg-3">
                  <span id="btnImageloading"></span>
                  <a href="logout.php" class="btn btn-primary btn-xs"> <i class="fa fa-sign-out"></i> Close >>> </a>
               </div>
            </center>
         </div>
      </div>
   </div>
</div>
<input type='hidden' id='perm_status' />
<input type='hidden' id='jtoken' />
<script type="text/javascript">
   $('.service-icon').click(function() {
      $(".service-block-container").hide();
      $('#btnImageloading').html('<img src="assets/img/Loader_1.gif"  />');
      //return false
   })
   $('#chithaEntry').click(function() {
      // authenticate_user();
      $.ajax({
         url: 'https://'+'<?=CHITHA_ENTRY_HOST?>' +'/index.php/ApiController/addLoginLog',
         method: "POST",
         data: {
            api_key: "chitha_application",
            dist_code: '<?= $_SESSION['credentials']['dist_code'] ?>',
            username: '<?= $user ?>'
         },
         async: true,
         dataType: 'json',
         success: function(data) {
            if (data.responseCode) {
               var chitha_data = '<?= isset($_SESSION['chitha_data'])?$_SESSION['chitha_data']:false ?>';
               if(chitha_data){
		  var barakcodes = [];
		  //var barakcodes = ['21','22','23'];
                  var dist_code  = '<?= $_SESSION['credentials']['dist_code'] ?>';
                  if(barakcodes.includes(dist_code))
                  {
                    // window.location = 'https://'+'<?=CHITHA_ENTRY_HOST?>'+'/chithaentry_barak/index.php/Login/singleSignRedirect?id=' + data.id + '&district=<?= $_SESSION['credentials']['dist_code'] ?>&'+ chitha_data;
		    alert('Click on the Barak Application');
                  }else{
                     window.location = 'https://'+'<?=CHITHA_ENTRY_HOST?>' +'/index.php/Login/singleSignRedirect?id=' + data.id + '&district=<?= $_SESSION['credentials']['dist_code'] ?>&'+ chitha_data;
                  }

               }
            } else {
               alert("ERROR: Try Again");
            }
         }
      });
      return false;
   });
   $('#chithaEntry_barak').click(function() {
      // authenticate_user();
      $.ajax({
         url: 'https://'+'<?=CHITHA_ENTRY_HOST?>' +'/index.php/ApiController/addLoginLog',
         method: "POST",
         data: {
            api_key: "chitha_application",
            dist_code: '<?= $_SESSION['credentials']['dist_code'] ?>',
            username: '<?= $user ?>'
         },
         async: true,
         dataType: 'json',
         success: function(data) {
            if (data.responseCode) {
               var chitha_data = '<?= isset($_SESSION['chitha_data'])?$_SESSION['chitha_data']:false ?>';
               if(chitha_data){
                  var barakcodes = ['21','22','23'];
                  var dist_code  = '<?= $_SESSION['credentials']['dist_code'] ?>';
                  if(barakcodes.includes(dist_code))
                  {
                     window.location = 'https://'+'<?=CHITHA_ENTRY_HOST?>'+'/chithaentry_barak/index.php/Login/singleSignRedirect?id=' + data.id + '&district=<?= $_SESSION['credentials']['dist_code'] ?>&'+ chitha_data;
                  }else{
                     alert("ERROR: Not authorised");
                  }
               }
            } else {
               alert("ERROR: Try Again");
            }
         }
      });
      return false;
   });
   $('#resurvey').click(function() {
      // authenticate_user();
      var dist_code = '<?= $_SESSION['credentials']['dist_code'] ?>';
      var username = '<?= $user ?>';
      console.log(dist_code, username);
      $.ajax({
         url: 'https://'+'<?=RESURVEY_HOST?>' +'/index.php/add_login_log',
         method: "POST",
         data: {
            api_key: "resurvey_application",
            dist_code: '<?= $_SESSION['credentials']['dist_code'] ?>',
            username: '<?= $user ?>'
         },
         async: true,
         dataType: 'json',
         success: function(data) {
            if (data.responseCode) {
               var resurvey_data = '<?= isset($_SESSION['resurvey_data'])?$_SESSION['resurvey_data']:false ?>';
               if(resurvey_data){
                  $.ajax({
                     url: 'https://'+'<?=RESURVEY_HOST?>' +'/index.php/singlesign_login',
                     method: "POST",
                     data: {
                        id: data.id,
                        district: <?= $_SESSION['credentials']['dist_code'] ?>,
                        resurvey_data: resurvey_data
                     },
                     async: true,
                     dataType: 'json',
                     success: function (resp) {
                        console.log(resp);
                        if(resp.status == 'y') {
                           window.location.href = 'https://'+'<?= RESURVEY_REACT_HOST ?>'+'/dashboard?id='+resp.data+'&usertype='+resp.usertype;
                        }
                     },
                     error: function (error) {
                        console.log(error);
                     }
                  });
               }
            } else {
               alert("ERROR: Try Again");
            }
         }
      });
      return false;
   });
</script>
<form action ="https://<?=NOC_IP_HOST?>/<?=NOC?>/index.php/Login/SingleSign" method="POST" id='nocPostredirect'>
   <input type="hidden"  name="token" value="<?=$_SESSION['token']?>">
</form>
<form action ="https://<?=IP_HOST?>/<?=DHARITREE?>/index.php/login/nocBypass" method="POST" id='dharitreePostredirect'>
   <input type="hidden" name="user" value="<?=rawurlencode($user)?>">
   <input type="hidden" name="dist_code" value="<?=$_SESSION['credentials']['dist_code']?>">
   <input type="hidden"  name="token" value="<?=$_SESSION['token']?>">
</form>
<form action ="validate_user.php" method="POST" id='rccmsFormPost'>
   <input type="hidden"  name="jwt" id='rccms-token' value="">
</form>
<script type="text/javascript"> 
         $(document).ready(function() {
            authenticate_user();
         });
         function authenticate_user() {
	  var IpHost = "<?= IP_HOST ?>";
          var singleSign = "<?= SINGLESIGN ?>";
          var apiUrl = "https://" + IpHost + "/" + singleSign + "/rccms_user.php";
          $.ajax({
              url: apiUrl,
              method: "POST",
              async: true,
              dataType: 'json',
              success: function(data) {
                  if (data.status === 'y') {
                      $('#perm_status').val(data.status);
                      $('#jtoken').val(data.token);
                      $('#rccms-token').val(data.token); 
                  } else {
                      $('#perm_status').val('');
                      $('#jtoken').val('');
                      $("#rccms").hide();
                  }
              },
              error: function(xhr, status, error) {
                  console.error("Authentication failed: " + error);
              }
          });
         }
	$('#bhunaksha').click(function(e){
        window.location.href = "https://dharitree.assam.gov.in/Singlesign/bhunaksha.php";
        });
        $('#dharitreeLoginBypass').click(function(e)  
        {  
          e.preventDefault();
          $(".service-block-container").hide();
          $('#btnImageloading').html('<img src="assets/img/Loader_1.gif"  />'); 
          $('#dharitreePostredirect').submit();
          //return false  
        });
        $('#nocLoginBypass').click(function(e)  
        {  
          e.preventDefault();
          $(".service-block-container").hide();
          $('#btnImageloading').html('<img src="assets/img/Loader_1.gif"  />'); 
          $('#nocPostredirect').submit();
          //return false  
        }); 
        $('#rccms').click(function(e)  
        {  
          e.preventDefault();
          $('#btnImageloading').html('<img src="assets/img/Loader_1.gif"  />'); 
          $('#rccmsFormPost').submit();
          //return false  
        });  
</script>
