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
            <h3 class="box-title"><?php echo _l("Banners"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
				<?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;';?>
				<?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Banners use in app");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("banner_title_nl", _l("Title")._l("(Dutch)")."<span class='text-danger'>*</span>", _get_post_back($field,'banner_title_nl'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
			echo _input_field("banner_title_en", _l("Title")._l("(En)")."<span class='text-danger'>*</span>", _get_post_back($field,'banner_title_en'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("banner_title_ar", _l("Title")._l("(Ar)")."<span class='text-danger'>*</span>", _get_post_back($field,'banner_title_ar'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("banner_title_tr", _l("Title")._l("(Tr)")."<span class='text-danger'>*</span>", _get_post_back($field,'banner_title_tr'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("banner_title_de", _l("Title")._l("(De)")."<span class='text-danger'>*</span>", _get_post_back($field,'banner_title_de'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo "<div class='clearfix'></div>";
            
            echo _select("sub_cat_ids[]",$subcategories,_l("Sub Categories")."<span class='text-danger'>*</span>",array("sub_category_id","sub_cat_name_nl"),_get_post_back($field,'sub_cat_ids'),array( "multiple"=>true),array("form_group_class"=>"col-md-4"));
            
            echo _select("tag_ids[]",$tags,_l("Tags")."<span class='text-danger'>*</span>",array("tag_id","tag_name_nl"),_get_post_back($field,'tag_ids'),array("multiple"=>true),array("form_group_class"=>"col-md-4"));
            
			echo "<div class='clearfix'></div>";
			?>	
				<div class='col-md-4'>
					<div class='image-droper'>
						<label><?php echo _l("Image"); ?></label>
						<div class="profile-container">
							
						   <img class="profileImage" src="<?php if(isset($field->banner_image)&& $field->banner_image != ""){ echo base_url(BANNER_IMAGE_PATH."/crop/small/".$field->banner_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
						   <input class="imageUpload" type="file" name="banner_image" placeholder="Photo" capture> 
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