<!-- Main content -->
<section class="content">
      <!-- Default box -->
	  <div class="row">
		<div class="col-md-offset-3 col-md-6">	
		  <div class="box">
			<div class="box-header with-border">
			  <h3 class="box-title"><?php echo _l("Change Password"); ?></h3>
			</div>
			<div class="box-body">
			  <?php
			  echo "<blockquote>";
			  echo _l("Change Password");
			  echo "</blockquote>";
			echo _get_flash_message();
			echo form_open_multipart();
			
			echo _input_field("old_password", _l("Old Password")."<span class='text-danger'>*</span>", _get_post_back($field,'old_password'), 'password', array("data-validation" =>"length required","data-validation-length"=>"min6"),array(),"col-md-12");
			
			echo _input_field("new_password", _l("New Password")."<span class='text-danger'>*</span>", _get_post_back($field,'new_password'), 'password', array("data-validation" =>"length required","data-validation-length"=>"min6"),array(),"col-md-12");
			echo _input_field("confirm_password", _l("Confirm Password")."<span class='text-danger'>*</span>", _get_post_back($field,'confirm_password'), 'password', array("data-validation" =>"length required","data-validation-length"=>"min6"),array(),"col-md-12");
			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat" name="change_password">'._l("Change Password").'</button>&nbsp;';
					echo '</div>';
	echo form_close();
	?>
			</div>
		  </div>
		  <!-- /.box -->
		</div>
	  </div>
</section>
