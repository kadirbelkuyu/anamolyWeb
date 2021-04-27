<form action="" method="post" class="validate_form">

  <div class="container">
    <div class="section">
    <div class="row ">
                <div class="col s12 pad-0"><h5 class="bot-20 sec-tit center"><?php echo _l("Register"); ?></h5>

      <div class="row">
        <div class="input-field col s10 offset-s1">
          <i class="mdi mdi-email-outline prefix"></i>
          <input id="email3" type="email" autocomplete="off" name="email" required class="validate">
          <label for="email3"><?php echo _l("Email"); ?></label>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s10 offset-s1">
          <i class="mdi mdi-account-key prefix"></i>
          <input id="pass3" type="password" autocomplete="off" name="password" required class="validate">
          <label for="pass3"><?php echo _l("Password"); ?></label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s10 offset-s1">
          <i class="mdi mdi-cellphone-iphone prefix"></i>
          <input id="mobile_number" type="text" autocomplete="off" name="mobile_number" required class="validate">
          <label for="mobile_number"><?php echo _l("Mobile Number"); ?></label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s5 m5 offset-s1 offset-m1">
          <i class="mdi mdi-account prefix"></i>
          <input id="first_name" type="text" autocomplete="off" name="first_name" required class="validate">
          <label for="first_name"><?php echo _l("First Name"); ?></label>
        </div>
        <div class="input-field col s5 m5 offset-s1 ">
          <input id="last_name" type="text" autocomplete="off" name="last_name" required class="validate">
          <label for="last_name"><?php echo _l("Last Name"); ?></label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s4 m4 offset-s1 offset-m1">
          <i class="mdi mdi-email-variant prefix"></i>
          <input id="postal_code" type="text" autocomplete="off" name="postal_code" required class="validate">
          <label for="postal_code"><?php echo _l("Postal Code"); ?></label>
        </div>
        <div class="input-field col s3 m3  ">
          <input id="house_no" type="text" autocomplete="off" name="house_no" class="validate">
          <label for="house_no"><?php echo _l("House No"); ?></label>
        </div>
        <div class="input-field col s3 m3  ">
          <input id="adon_no" type="text" autocomplete="off" name="adon_no" class="validate">
          <label for="adon_no"><?php echo _l("Adon No"); ?></label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m12 offset-s1 offset-m1">
          <label>
            <input name="is_company" id="is_company" type="checkbox" class="filled-in"  />
            <span><?php echo _l("I am a company"); ?></span>
          </label>
        </div>
      </div>
      <div class="spacer"></div>
      <div class="company_details" style="display:none">
        <div class="row">
          <div class="input-field col s10 offset-s1">
            <i class="mdi mdi-office-building prefix"></i>
            <input id="company_name" type="text" autocomplete="off" name="company_name" class="validate">
            <label for="company_name"><?php echo _l("Company Name"); ?></label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s10 offset-s1">
            <i class="mdi mdi-card-account-details prefix"></i>
            <input id="company_id" type="text" autocomplete="off"  name="company_id" class="validate">
            <label for="company_id"><?php echo _l("Company ID Number"); ?></label>
          </div>
        </div>
      </div>
      <div class="row center">
        <button type="submit" class="waves-effect waves-light btn-large bg-primary"><?php echo _l("Register"); ?></button>
        <div class="spacer"></div>
        <div class="links">
             <a href="<?php echo site_url("login"); ?>" class='waves-effect'><?php echo _l("Login"); ?></a> | <a href="<?php echo site_url("appinstruction/policy"); ?>" class='waves-effect'><?php echo _l("Privacy policy"); ?></a>
        </div>
        <div class="spacer"></div>
       </div>
    </div>
    </div>
    </div>
  </div>
</form>
