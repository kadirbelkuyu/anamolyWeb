
<form action="" method="post">
<div class="login-bg access-login"></div>
  <div class="container login-area">
    <div class="section">
    <div class="row ">
                  <div class="col s12 pad-0"><h5 class="bot-20 sec-tit center white-text"><?php echo _l("Verification Code");?></h5>
                  <p class="center"><?php echo _l("Please type the verification code sent to"); ?> <?php echo $email; ?></p>
                  <div class="row">
                    <div class="input-field col s10 offset-s1">
                      <input id="otp" type="number" name="otp" class="validate">
                      <label for="otp"><?php echo _l("OTP")?></label>
                    </div>
                  </div>
                  <div class="row center">
                    <button type="submit" class="waves-effect waves-light btn-large bg-primary"><?php echo _l("Verify"); ?></button>
                    <a href="javascript:resendOtp()" class='waves-effect'><?php echo _l("Resend"); ?></a>
                  </div>
              </div>
    </div>
    </div>
  </div>
</form>
<script>
function resendOtp(){
  $.ajax({
    method: "POST",
    url: "<?php echo site_url("login/resend"); ?>",
    data: { }
  }).done(function( data ) {
      if(data.responce){
          toastScript(data.message,"success");
      }
      else
      {
          toastScript(data.message,"error");
      }
  });
}
</script>
