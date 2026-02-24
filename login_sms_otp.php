
<div class="modal fade" id="myModalOtp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="width: 40%;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: cyan;">
        <h4 class="modal-title">
          Enter OTP to Login<br>
        </h4>
      </div>
      <div class="modal-body">
        <div class="panel" style="margin-top:0px">                                                   
          <div class="panel-body">
            <div class="container">
              <b style="font-size:15px; color: red;">An OTP has been sent to your registered mobile no <span sty id="div_mobile"></span></b>
              <input type="text" id="mb_otp" class="form-control" maxlength="6"
                oninput="this.value = this.value.replace(/[^0-9\.]/g,'')" placeholder="Enter 6 digits OTP here" autofocus>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" id="verifyOtp">Verify OTP</button>
      </div> 
      
        <b style="font-size:15px; color: white; margin: 1%; background-color: red;">Note: In case of OTP not received, kindly reload the page</b>
      
    </div>
  </div>



</div>



