<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?php echo form_open_multipart(); ?>
    <div class="box">
        <div class="box-header with-border">
			<?php
				if(!empty($field) && !empty($field->$primary_key))
				{
					$updBtn=_l("Update");
				}
				else
				{
					$updBtn=_l("Add");
				}
            ?>
            
            <h3 class="box-title"><?php echo _l("Sub Admin"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='".site_url($controller)."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Sub Admin");
            echo "</blockquote>";
            echo _get_flash_message();
            
            echo _input_field("user_type_id","",$user_type_id,"hidden");
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
                echo _input_field("user_email_id", _l("Login Email")."<span class='text-danger'>*</span>", _get_post_back($field,'user_email'), 'text', array("maxlength"=>255,"disabled"=>"true"),array(),"col-md-4");
			    
                echo _input_field("user_password", _l("Reset Password"),"", 'password', array("maxlength"=>255),array(),"col-md-4");
			    
            }else{
                echo _input_field("user_email", _l("Login Email")."<span class='text-danger'>*</span>", _get_post_back($field,'user_email'), 'text', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
			    echo _input_field("user_password", _l("Login Password")."<span class='text-danger'>*</span>", _get_post_back($field,'user_password'), 'password', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			    
            }       
            echo _select("role_id",$roles,_l("Role")."<span class='text-danger'>*</span>",array("role_id","role_title"),_get_post_back($field,'role_id'),array("data-validation"=>"required"),array("form_group_class"=>"col-md-4"));
            
			echo "<div class='clearfix'></div>";
            echo _input_field("user_firstname", _l("First Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_firstname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("user_lastname", _l("Last Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_lastname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("user_phone", _l("Phone")."<span class='text-danger'>*</span>", _get_post_back($field,'user_phone'), 'text', array("data-validation" =>"number","maxlength"=>255),array(),"col-md-4");
            
            echo "<div class='clearfix'></div>";
            
			?>	
				<div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("User Image"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->user_image)&& $field->user_image != ""){ echo base_url(PROFILE_IMAGE_PATH."/crop/small/".$field->user_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="user_image" placeholder="Photo" capture> 
						</div>	
					</div>
				</div>
                
            <?php
                
			echo "<div class='clearfix'></div>";
			
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("status",_l("Login Enable"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
            echo "</div>";
			
            echo '<div class="col-md-12">
            <br>
            <button type="submit" class="btn btn-primary btn-flat">'.$updBtn.'</button>&nbsp;';
        echo "<a class='btn btn-danger btn-flat' href='".site_url($controller)."'>Cancel</a>";
        echo '</div>';
            ?>
        </div>
    </div>
    
    <?php echo form_close(); ?>
    <!-- /.box -->
</section>