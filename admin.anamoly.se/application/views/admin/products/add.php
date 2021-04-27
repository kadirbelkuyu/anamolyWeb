
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
            <h3 class="box-title"><?php echo _l("Product"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
              <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
              <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				      <a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Products which you sale.");
            echo "</blockquote>";
            echo _get_flash_message();
            
            $is_edit = false;
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
                $is_edit = true;
            }       
			
            
            echo "<div class=''>";
            
            echo _input_field("product_name_nl", _l("Product Name")._l("(Dutch)")."<span class='text-danger'>*</span>", _get_post_back($field,'product_name_nl'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("product_name_en", _l("Product Name")._l("(En)"), _get_post_back($field,'product_name_en'), 'text', array("maxlength"=>200),array(),"col-md-4");
			echo _input_field("product_name_ar", _l("Product Name")._l("(Ar)"), _get_post_back($field,'product_name_ar'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("product_name_tr", _l("Product Name")._l("(Tr)"), _get_post_back($field,'product_name_tr'), 'text', array("maxlength"=>200),array(),"col-md-4");
			echo _input_field("product_name_de", _l("Product Name")._l("(De)"), _get_post_back($field,'product_name_de'), 'text', array("maxlength"=>200),array(),"col-md-4");
          
            echo "<div class='clearfix'></div>";
            
            echo _input_field("price_vat_exclude", _l("Price(Without VAT)")."<span class='text-danger'>*</span>", _get_post_back($field,'price_vat_exclude'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-2");
            echo _select("vat",$vats,_("VAT(%)")."<span class='text-danger'>*</span>",array("vat","vat_title"),_get_post_back($field,'vat'),array(),array("form_group_class"=>"col-md-2"));
            echo _input_field("price", _l("Price(With VAT)")."<span class='text-danger'>*</span>", _get_post_back($field,'price'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-2");
            
           
            echo _input_field("unit_value", _l("Unit Value")."<span class='text-danger'>*</span>", _get_post_back($field,'unit_value'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-2");
            
            echo _select("unit",$units,_("Unit")."<span class='text-danger'>*</span>",array("unit_nl","unit_nl"),_get_post_back($field,'unit'),array(),array("form_group_class"=>"col-md-2"));
            
            echo _input_field("qty", _l("Qty")."<span class='text-danger'>*</span>", _get_post_back($field,'qty','','1'), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>255,"minvalue"=>"1"),array(),"col-md-2");
            
            

            echo "<div class='clearfix'></div>";
            echo _input_field("product_barcode", _l("Barcode"), _get_post_back($field,'product_barcode'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_two", _l("Barcode 2"), _get_post_back($field,'barcode_two'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_three", _l("Barcode 3"), _get_post_back($field,'barcode_three'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_four", _l("Barcode 4"), _get_post_back($field,'barcode_four'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_five", _l("Barcode 5"), _get_post_back($field,'barcode_five'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo "<div class='clearfix'></div>";

            echo _input_field("price_note", _l("Price Note"), _get_post_back($field,'price_note'), 'text', array("maxlength"=>255),array(),"col-md-2");
            

            echo _select("product_tags[]",$tags,_("Tags"),array("tag_id","tag_name_nl"),_get_post_back($field,'product_tags'),array( "multiple"=>true),array("form_group_class"=>"col-md-3"));
            
            echo _select("product_ingredients[]",$ingredients,_("Ingredients"),array("ingredient_id","ingredient_name_nl"),_get_post_back($field,'product_ingredients'),array( "multiple"=>true),array("form_group_class"=>"col-md-5"));
            
            echo "<div class='clearfix'></div>";
            
            echo _input_field("max_cart_qty", _l("Max Cart QTY")."<span class='text-danger'>*</span>", _get_post_back($field,'max_cart_qty','','0'), 'number', array("data-validation" =>"required","step"=>"1","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-2");
              
           
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_express",_l("Is Express"),"",array(),(isset($field) && isset($field->is_express) && $field->is_express == 1) ? true : false);
            echo _checkbox("status",_l("Status"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
            echo "</div>";
            
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_nutritional",_l("Is Nutritional"),"",array(),(isset($field) && isset($field->is_nutritional) && $field->is_nutritional == 1) ? true : false);
            echo "</div>";
            
			      echo "<div class='clearfix'></div>";
            
            
              ?>
              <div class='col-md-2'>
                  <div class='image-droper'>
                    <label><?php echo _l("Image"); ?></label>
                    <div class="profile-container">
                      
                      <img class="profileImage" src="<?php if(isset($field->product_image)&& $field->product_image != ""){ echo base_url(PRODUCT_IMAGE_PATH."/crop/small/".$field->product_image); }else{ echo base_url("themes/backend/img/choose-image.png"); } ?>" alt="<?php echo _l("Image"); ?>" />
                      <input class="imageUpload" type="file" name="product_image" placeholder="Photo" capture> 
                    </div>	
                  </div>
              </div>
              <div class="col-md-10">
                <div class="row">
              <?php
              echo "<div class='col-md-12'>";
              if($is_edit){
                ?>
                <label><?php echo _l("Product Groups"); ?></label>
                <table id="example1" class="table table-bordered table-striped ">
                <thead>
                  <tr>
                      <th><?php echo _l("Group"); ?></th>
                      <th><?php echo _l("Category"); ?></th>
                      <th><?php echo _l("Sub Category"); ?></th>
                      <th width='120'><?php echo _l("Action"); ?>
                        <a href="javascript:;" class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#addGroupModal"><i class='fa fa-plus'></i> <?php echo _l("Add"); ?></a>
                      </th>
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
                <?php
              }else{
                echo _select("category_id",$categories,_l("Category")."<span class='text-danger'>*</span>",array("category_id","cat_name_nl"),"",array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
                echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category")."<span class='text-danger'>*</span>",array("sub_category_id","sub_cat_name_nl"),"",array(),array("form_group_class"=>"col-md-3"));
                echo _select("product_group_id",(isset($productgroups))?$productgroups : array(),_l("Product Groups")."<span class='text-danger'>*</span>",array("product_group_id","group_name_nl"),"",array(),array("form_group_class"=>"col-md-3"));
              
              } 
              echo "</div></div></div>";
              echo "<div class='clearfix'></div><br />";
              
              echo _textarea('picker_note',_l("Order picker note"),_get_post_back($field,'picker_note'),array(),array(),"col-md-12");
                

            ?>
            
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><?php echo _l("Description")._l("(Dutch)"); ?></a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><?php echo _l("Description")._l("(En)"); ?></a></li>
              <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false"><?php echo _l("Description")._l("(Ar)"); ?></a></li>
              <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false"><?php echo _l("Description")._l("(Tr)"); ?></a></li>
              <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false"><?php echo _l("Description")._l("(De)"); ?></a></li>
              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

                <a onclick="copycontent('product_desc_nl')" class="pull-right"><i class='fa fa-copy'></i></a>
                <div class="row">
                <?php
                echo _textarea('product_desc_nl',"",_get_post_back($field,'product_desc_nl'),array(),array(),"col-md-12");
                ?>
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <a onclick="copycontent('product_desc_en')" class="pull-right"><i class='fa fa-copy'></i></a>
                <div class="row">
                <?php echo _textarea('product_desc_en',"",_get_post_back($field,'product_desc_en'),array(),array(),"col-md-12");
			    ?>
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <a onclick="copycontent('product_desc_ar')" class="pull-right"><i class='fa fa-copy'></i></a>
                <div class="row">
                <?php echo _textarea('product_desc_ar',"",_get_post_back($field,'product_desc_ar'),array(),array(),"col-md-12"); ?>			
                </div>
              </div>
              <!-- /.tab-pane -->
              
               <div class="tab-pane" id="tab_4">
                <a onclick="copycontent('product_desc_tr')" class="pull-right"><i class='fa fa-copy'></i></a>
                <div class="row">
                <?php echo _textarea('product_desc_tr',"",_get_post_back($field,'product_desc_tr'),array(),array(),"col-md-12"); ?>			
                </div>
              </div>
              
               <div class="tab-pane" id="tab_5">
                <a onclick="copycontent('product_desc_de')" class="pull-right"><i class='fa fa-copy'></i></a>
                <div class="row">
                <?php echo _textarea('product_desc_de',"",_get_post_back($field,'product_desc_de'),array(),array(),"col-md-12"); ?>			
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
            
            <?php
			echo '<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'.$updBtn.'</button>&nbsp;';
			echo "<a class='btn btn-danger btn-flat' href='"._back_url()."'>"._l("Cancel")."</a>";
			echo '</div>';
            
            
            echo '</div>';
            
            
            ?>
        </div>
    </div>
    <!-- /.box -->
<!-- Modal -->
<?php echo form_close(); ?>
<div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
              <?php echo "<form id='add_product_groups' action='".site_url("admin/products/set_groups")."' enctype='multipart/form-data'>";
               ?>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Product Group") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
              <?php
              echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
              
                echo _select("category_id",$categories,_l("Category")."<span class='text-danger'>*</span>",array("category_id","cat_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-12","include_blank"=>_l("Select Category")));
                echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category")."<span class='text-danger'>*</span>",array("sub_category_id","sub_cat_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-12"));
                echo _select("product_group_id",(isset($productgroups))?$productgroups : array(),_l("Product Groups")."<span class='text-danger'>*</span>",array("product_group_id","group_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-12"));
                
                
              ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <?php echo '<button type="submit" class="btn btn-primary btn-flat">'._l("Add").'</button>';
                 ?>
      </div>
      <?php echo "</form>"; ?>
    </div>
  </div>
</div>
</section>