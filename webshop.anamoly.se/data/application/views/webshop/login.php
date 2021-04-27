
<form action="" method="post" class="validate_form">

  <div class="container">
    <div class="section">
    <div class="row ">
                <div class="col s12 pad-0"><h5 class="bot-20 sec-tit center dark-text"><?php echo _l("Login"); ?></h5>
      <div class="row">
        <div class="input-field col s10 offset-s1">
          <i class="mdi mdi-email-outline prefix"></i>
          <input id="email3" type="email" name="email" autocomplete="off" required class="validate">
          <label for="email3"><?php echo _l("Email"); ?></label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s10 offset-s1">
          <i class="mdi mdi-account-key prefix"></i>
          <input id="pass3" type="password" name="password" required class="validate">
          <label for="pass3"><?php echo _l("Password"); ?></label>
        </div>
      </div>

      <div class="row center">
        <button type="submit" class="waves-effect waves-light btn-large bg-primary"><?php echo _l("Login");?></button>
        <div class="spacer"></div>
        <div class="links">
             <a href="<?php echo site_url("login/forgotpassword"); ?>" class='waves-effect'><?php echo _l("Forgot Password");?></a>
             <span class="sep">|</span>
             <a href="<?php echo site_url("login/add"); ?>" class='waves-effect waves-light btn red lighten-2'><?php echo _l("Register"); ?></a>
        </div>
        <div class="spacer"></div>
       </div>
    </div>
    </div>
    </div>
  </div>
</form>
