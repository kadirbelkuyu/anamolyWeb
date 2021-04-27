<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
			
            <h3 class="box-title"><?php echo _l("Product Images"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            
            echo _get_flash_message();
            
                echo "<div class='col-md-12'>";
                echo "<form id='upload_form' enctype='multipart/form-data'>";
                
                echo "<div class='row'>";
                echo "<div class='col-md-3'>";
                echo "<label for='product_image'>"._l("Choose Product Image")."</label>";
                echo "</div>";
                echo "<div class='col-md-8'>";
                
                echo '<input class="imgUpload" id="product_image" type="file" name="product_image" placeholder="Photo" capture>';
                echo "</div>";
                echo "</div>";
            
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
                echo "</form>";
                echo "</div>";
            
                echo "<div class='row'>";
                echo "<div id='image_list'>";
                foreach($images as $key=>$img){
                    ?>
                    <div class="col-md-3" id="product_image_<?php echo $key; ?>">
                        <div class="img-responsive img prod_list_image" style="height:120px; background:url('<?php echo base_url(PRODUCT_IMAGE_PATH."/crop/small/".$img->product_image); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                            <a href="javascript:deleteProductImage('<?php echo _encrypt_val($img->product_image_id); ?>','<?php echo $key; ?>')"><i class="fa fa-remove"></i></a>
                        </div>
                        
                    </div>
                    <?php
                }
                echo "</div>";
                echo "</div>";
                
                
        
            ?>
            
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
			
            <h3 class="box-title"><?php echo _l("Product Groups"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            
                echo "<div class='row'>";
                echo "<form id='add_product_groups' action='".site_url("admin/products/set_groups")."' enctype='multipart/form-data'>";
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
                echo _select("category_id",
                         $categories,_l("Category")."<span class='text-danger'>*</span>",
                         array("category_id","cat_name_nl"),
                         "",
                         array("data-validation"=>"required"),
                         array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
                echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category")."<span class='text-danger'>*</span>",array("sub_category_id","sub_cat_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-3"));
                
                echo _select("product_group_id",(isset($productgroups))?$productgroups : array(),_l("Product Groups")."<span class='text-danger'>*</span>",array("product_group_id","group_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-3"));
                
                echo '<div class="col-md-12"><button type="submit" class="btn btn-primary btn-flat">'._l("Add").'</button></div>';
                
                echo "</form>";
                echo "</div>";
                
        
            ?>
            <br />
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th><?php echo _l("Group"); ?></th>
                    <th><?php echo _l("Category"); ?></th>
                    <th><?php echo _l("Sub Category"); ?></th>
                    <th width='90'><?php echo _l("Action"); ?></th>
                </tr>
                </thead>
                <tbody id="groups_list">
                <?php
				$count = 0;
                foreach($maps as $dt){
					$count++;	
                    $this->load->view("admin/products/row_groups",array("dt"=>$dt,"count"=>$count));
                } ?>

                </tbody>
            </table>
        </div>
    </div>
    
</section>