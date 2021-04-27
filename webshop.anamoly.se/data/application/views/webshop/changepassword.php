<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("Change Password"); ?></h5>

              <div class="editprof-img">
            <div class="img-wrap circle">
            <img src="<?php echo _get_current_user_image(); ?>" alt="">
            </div>
             <div><?php echo _get_current_user_companyname(); ?></div>
            <div><?php echo _get_current_user_email(); ?></div>
          </div>

            </div>

             <?php echo form_open();

           	echo _input_field("old_password", _l("Old Password")."<span class='text-danger'>*</span>", _get_post_back($field,'old_password'), 'password', array("required"=>""),array(),"s12");
			echo _input_field("new_password", _l("New Password")."<span class='text-danger'>*</span>", _get_post_back($field,'new_password'), 'password', array("required"=>""),array(),"s12");
			echo _input_field("confirm_password", _l("Confirm Password")."<span class='text-danger'>*</span>", _get_post_back($field,'confirm_password'), 'password', array("required"=>""),array(),"s12");

        	echo '<div class="s12">
				<button type="submit" class="btn btn-primary btn-flat" name="change_password">'._l("Change Password").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>

        </div>
    </div>
</div>
