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
            $t = _l("App Users");
            $page = "";
            ?>
            <h3 class="box-title"><?php echo $t; ?> / <?php echo $updBtn; ?></h3>
            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo $t;
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }else{
                echo _input_field("user_email", _l("Login Email")."<span class='text-danger'>*</span>", _get_post_back($field,'user_email'), 'text', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
			    echo _input_field("user_password", _l("Login Password")."<span class='text-danger'>*</span>", _get_post_back($field,'user_password'), 'password', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			    
            }       
			echo "<div class='clearfix'></div>";
            echo _input_field("user_firstname", _l("First Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_firstname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("user_lastname", _l("Last Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_lastname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("user_phone", _l("Phone")."<span class='text-danger'>*</span>", _get_post_back($field,'user_phone'), 'text', array("data-validation" =>"number","maxlength"=>255),array(),"col-md-4");
            
            echo "<div class='clearfix'></div>";
            echo _select("user_type_id",$user_types,_("User Type")."<span class='text-danger'>*</span>",array(),_get_post_back($field,'user_type_id'),array(),array("form_group_class"=>"col-md-2"));
            
            echo "<div class='company_data hide'>";
            echo _input_field("user_company_name", _l("Company Name")."<span class='text-danger'>*</span>", _get_post_back($field,'user_company_name'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("user_company_id", _l("Company ID")."<span class='text-danger'>*</span>", _get_post_back($field,'user_company_id'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo "</div>";

            echo "<div class='clearfix'></div>";
            echo _input_field("postal_code", _l("Postal Code")."<span class='text-danger'>*</span>", _get_post_back($field,'postal_code'), 'text', array("data-validation" =>"custom","data-validation-regexp"=>"^\d{4}[a-zA-Z\d]","maxlength"=>6),array(),"col-md-4");
			echo _input_field("house_no", _l("House No")."<span class='text-danger'>*</span>", _get_post_back($field,'house_no'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("add_on_house_no", _l("Add On House No")."<span class='text-danger'>*</span>", _get_post_back($field,'add_on_house_no'), 'text', array("maxlength"=>255),array(),"col-md-4");
            
            
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
			echo "<a class='btn btn-danger btn-flat' href='"._back_url()."'>"._l("Cancel")."</a>";
			echo '</div>';
            
            ?>
        </div>
    </div>
    <?php echo form_close(); ?>
    <!-- /.box -->
</section>