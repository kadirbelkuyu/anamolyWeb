<div style="background:#ffffff">
  <div class="container center">
      <div class="section">
  	<h5 class=""><?php echo _l("Welcome! Your place is reserved"); ?></h5>

  	<p><?php echo _l("You are in queue"); ?></p>
    <h2><?php echo $this->session->userdata("req_queue"); ?></h2>
    <div class="spacer"></div>
    <p><?php echo _l("We will send you a message as soon as you can order"); ?></p>
    <div class="spacer"></div>
    <a href="<?php echo site_url("contactus"); ?>"><?php echo _l("Contact Us If need help"); ?></a>
    <p><?php echo _l("You are in the queue, wait until administrator confirm your registration."); ?></p>
    <div class="row center">
      <a type="submit" href="<?php echo site_url("login/whencanstart"); ?>" class="waves-effect waves-light btn-large bg-primary"><?php echo _l("When can I start?"); ?></a>
      <div class="spacer"></div>

     </div>
    </div>
  </div>
</div>
