<?php echo "<form id='add_product_groups' action='".site_url("admin/products/set_groups")."' enctype='multipart/form-data'>";
               ?>
<?php
              echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
              echo _input_field("r_index","",$r_index,"hidden");
              echo "<div class='filter_group row'>";
                echo _select("category_id",$categories,_l("Category")."<span class='text-danger'>*</span>",array("category_id","cat_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-12","select_class"=>"category_id","include_blank"=>_l("Select Category")));
                echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category")."<span class='text-danger'>*</span>",array("sub_category_id","sub_cat_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-12","select_class"=>"sub_category_id"));
                echo _select("product_group_id",(isset($productgroups))?$productgroups : array(),_l("Product Groups")."<span class='text-danger'>*</span>",array("product_group_id","group_name_nl"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-12","select_class"=>"product_group_id"));
                echo "</div>";
                
              ?>
<?php echo '<button type="submit" class="btn btn-primary btn-flat">'._l("Add").'</button>';
                 ?>
<?php echo "</form>"; ?>
<label><?php echo _l("Product Groups"); ?></label>
<table id="example1" class="table table-bordered table-striped ">
    <thead>
        <tr>
            <th><?php echo _l("Group"); ?></th>
            <th><?php echo _l("Category"); ?></th>
            <th><?php echo _l("Sub Category"); ?></th>
            <th width='120'><?php echo _l("Action"); ?></th>
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