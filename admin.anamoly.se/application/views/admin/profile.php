<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
		
            <h3 class="box-title"><?php echo _l("Profile"); ?> / <?php echo _l("Update"); ?></h3>

            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Profile detail update here.");
            echo "</blockquote>";
            echo _get_flash_message();
            echo form_open_multipart();
            
            $path = PROFILE_IMAGE_PATH;	
			
            echo "<div class='row'><div class='col-md-10'>";            
			echo _input_field("user_firstname", _l("First Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_firstname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
            	echo _input_field("user_lastname", _l("Last Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_lastname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-6");
			echo "<div class='clearfix'></div>";
            echo _input_field("user_phone", _l("Phone No")."<span class='text-danger'>*</span>", _get_post_back($field,'user_phone'), 'text', array("data-validation" =>"required number","maxlength"=>50),array(),"col-md-4");
            echo _input_field("user_company_name", _l("Company Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_company_name'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("user_company_id", _l("Company ID")."<span class='text-danger'>*</span>", _get_post_back($field,'user_company_id'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            
            echo "</div>";
			
			echo "<div class='col-md-2'>";
                    echo "<div class='image-droper'>";
            ?>
            <div class="profile-container">
               <img class="profileImage" src="<?php if(isset($field->photo)&& $field->photo != ""){ echo base_url($path."/crop/small/".$field->photo); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Photo"); ?>" />
               <input class="imageUpload" type="file" name="profile_photo" placeholder="Photo" capture> 
            </div>
            
            <?php                          
					echo "</div>";
            echo "</div>";
		
			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Update").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>