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
            <h3 class="box-title"><?php echo _l("Groups"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Group of products");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("group_name_nl", _l("Name")._l("(Dutch)")."<span class='text-danger'>*</span>", _get_post_back($field,'group_name_nl'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
			echo _input_field("group_name_en", _l("Name")._l("(En)"), _get_post_back($field,'group_name_en'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("group_name_ar", _l("Name")._l("(Ar)"), _get_post_back($field,'group_name_ar'), 'text', array("maxlength"=>200),array(),"col-md-4");
           
           	echo _input_field("group_name_tr", _l("Name")._l("(Tr)"), _get_post_back($field,'group_name_tr'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("group_name_de", _l("Name")._l("(De)"), _get_post_back($field,'group_name_de'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo "<div class='clearfix'></div>";
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("status",_l("Status"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
			echo "</div>";
			echo "<div class='clearfix'></div>";
            echo _select("category_id",
                         $categories,_l("Category")."<span class='text-danger'>*</span>",
                         array("category_id","cat_name_nl"),
                         _get_post_back($field,'category_id'),
                         array("data-validation"=>"required"),
                         array("form_group_class"=>"col-md-4","include_blank"=>_l("Select Category")));
            echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category")."<span class='text-danger'>*</span>",array("sub_category_id","sub_cat_name_nl"),_get_post_back($field,'sub_category_id'),array("data-validation"=>"required"),array("form_group_class"=>"col-md-4"));
            echo "<div class='clearfix'></div>";
            
            
			?>
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