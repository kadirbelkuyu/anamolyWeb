
<form action="" method="post" class="validate_form">

  <div class="container">
    <div class="section">
    <div class="row ">
        <div class="col s12 pad-0"><h5 class="bot-20 sec-tit center dark-text"><?php echo _l("Forgot Password"); ?></h5>
        <p class="center"><?php echo _l("We will send you recovery link to your registered email address to reset password"); ?></p>          
      <div class="row">
        <div class="input-field col s10 offset-s1">
          <i class="mdi mdi-email-outline prefix"></i>
          <input id="email3" type="email" name="email" autocomplete="off" required class="validate">
          <label for="email3"><?php echo _l("Email"); ?></label>
        </div>
      </div>
      <div class="row center">
        <button type="submit" class="waves-effect waves-light btn-large bg-primary"><?php echo _l("Submit"); ?></button>
        <div class="spacer"></div>
        <div class="links">
             <a href="<?php echo site_url("login"); ?>" class='waves-effect'><?php echo _l("Login"); ?>
             </a>
        </div>
        <div class="spacer"></div>
       </div>
    </div>
    </div>
    </div>
  </div>
</form>
