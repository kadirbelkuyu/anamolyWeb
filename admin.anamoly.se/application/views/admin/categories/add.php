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
            <h3 class="box-title"><?php echo _l("Category"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
				<?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
				<?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Category");
            echo "</blockquote>";
            echo _get_flash_message();

            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }

            echo _input_field("cat_name_nl", _l("Title")._l("(Dutch)")."<span class='text-danger'>*</span>", _get_post_back($field,'cat_name_nl'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
			echo _input_field("cat_name_en", _l("Title")._l("(En)"), _get_post_back($field,'cat_name_en'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("cat_name_ar", _l("Title")._l("(Ar)"), _get_post_back($field,'cat_name_ar'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("cat_name_tr", _l("Title")._l("(Tr)"), _get_post_back($field,'cat_name_tr'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("cat_name_de", _l("Title")._l("(De)"), _get_post_back($field,'cat_name_de'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo "<div class='clearfix'></div>";

			echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("status",_l("Status"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
			echo "</div>";
			echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_featured",_l("Featured Category"),"",array(),(isset($field) && isset($field->is_featured) && $field->is_featured == 1) ? true : false);
			echo "</div>";
			echo "<div class='clearfix'></div>";
			?>
				<div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("Image"); ?></label>
						<div class="profile-container">

						   <img class="profileImage" src="<?php if(isset($field->cat_image)&& $field->cat_image != ""){ echo base_url(CATEGORY_IMAGE_PATH."/crop/small/".$field->cat_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="cat_image" placeholder="Photo" capture>
						</div>
					</div>
				</div>
                <div class='col-md-2'>
					<div class='image-droper'>
						<label><?php echo _l("Banner"); ?></label>
						<div class="profile-container">

						   <img class="profileImage" src="<?php if(isset($field->cat_banner)&& $field->cat_banner != ""){ echo base_url(CATEGORY_IMAGE_PATH."/crop/small/".$field->cat_banner); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Banner"); ?>" />
						   <input class="imageUpload" type="file" name="cat_banner" placeholder="Photo" capture>
						</div>
					</div>
				</div>
			<?php
			echo "<div class='clearfix'></div>";

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
