<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("App Settings"); ?></h3>

            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <?php
            $headerpath = APP_IMAGE_PATH;
            $loginpath = APP_IMAGE_PATH;

            echo "<blockquote>";
            echo _l("App Settings");
            echo "</blockquote>";
            echo _get_flash_message();
            echo form_open_multipart();
			echo _input_field("app_contact", _l("Contact No.")."<span class='text-danger'>*</span>", _get_post_back($field,'app_contact'), 'text', array("data-validation" =>"number","maxlength"=>255),array(),"col-md-4");
			echo _input_field("app_whatsapp", _l("Whatsapp No")."<span class='text-danger'>*</span>", _get_post_back($field,'app_whatsapp'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("app_email", _l("Email ID")."<span class='text-danger'>*</span>", _get_post_back($field,'app_email'), 'email', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
		    echo '<div class="clearfix"></div>';

            echo "<blockquote>";
            echo _l("Theme Settings");
            echo "</blockquote>";

            echo "<div class='col-md-3'>";
             echo "<label>"._l("Header Logo")."</label>";
                    echo "<div class='image-droper'>";
            ?>
            <div class="profile-container">
               <img class="profileImage" src="<?php if(isset($field["header_logo"]) && $field["header_logo"] != ""){ echo base_url($headerpath."/crop/small/".$field["header_logo"]); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Header Logo"); ?>" />
               <input class="imageUpload" type="file" name="header_logo" placeholder="Header Logo" capture>
            </div>

            <?php
					echo "</div>";
            echo "</div>";

            echo "<div class='col-md-3'>";
            echo "<label>"._l("Login / Register Top Image")."</label>";
                    echo "<div class='image-droper'>";
            ?>
            <div class="profile-container">
               <img class="profileImage" src="<?php if(isset($field["login_top_image"]) && $field["login_top_image"] != ""){ echo base_url($loginpath."/crop/small/".$field["login_top_image"]); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Login / Register Top Image"); ?>" />
               <input class="imageUpload" type="file" name="login_top_image" placeholder="Login / Register Top Image" capture>
            </div>

            <?php
					echo "</div>";
            echo "</div>";

            echo _color_input("header_color", _l("Header Color")."<span class='text-danger'>*</span>", _get_post_back($field,'header_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("header_text_color", _l("Header Text Color")."<span class='text-danger'>*</span>", _get_post_back($field,'header_text_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");



            echo '<div class="clearfix"></div>';

            echo _color_input("button_color", _l("Button Color")."<span class='text-danger'>*</span>", _get_post_back($field,'button_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("button_text_color", _l("Button Text Color")."<span class='text-danger'>*</span>", _get_post_back($field,'button_text_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("second_button_color", _l("Second Button Color")."<span class='text-danger'>*</span>", _get_post_back($field,'second_button_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("second_button_text_color", _l("Second Button Text Color")."<span class='text-danger'>*</span>", _get_post_back($field,'second_button_text_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("default_text_color", _l("Default Text Color")."<span class='text-danger'>*</span>", _get_post_back($field,'default_text_color'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("decorative_text_one", _l("Decorative Text 1")."<span class='text-danger'>*</span>", _get_post_back($field,'decorative_text_one'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("decorative_text_two", _l("Decorative Text 2")."<span class='text-danger'>*</span>", _get_post_back($field,'decorative_text_two'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");
            echo _color_input("info_box_bg", _l("Info Box Background")."<span class='text-danger'>*</span>", _get_post_back($field,'info_box_bg'),array("data-validation" =>"required","maxlength"=>50,"readonly"=>true),array(),"col-md-3");


			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>
