<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="">
        
        <div class="row">
            <?php
            
            echo _get_flash_message();
            echo form_open_multipart("admin/products/quicksubmit",array("id"=>"form_quick_edit"));
            echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            echo _input_field("r_index","",$r_index,"hidden"); // hidden field use for edit item
            
            echo "<div class=''>";
            
            echo _input_field("product_name_nl", _l("Product Name")._l("(Dutch)")."<span class='text-danger'>*</span>", _get_post_back($field,'product_name_nl'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
            echo _input_field("product_name_en", _l("Product Name")._l("(En)"), _get_post_back($field,'product_name_en'), 'text', array("maxlength"=>200),array(),"col-md-4");
		    echo _input_field("product_name_ar", _l("Product Name")._l("(Ar)"), _get_post_back($field,'product_name_ar'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("product_name_tr", _l("Product Name")._l("(Tr)"), _get_post_back($field,'product_name_tr'), 'text', array("maxlength"=>200),array(),"col-md-4");
		    echo _input_field("product_name_de", _l("Product Name")._l("(De)"), _get_post_back($field,'product_name_de'), 'text', array("maxlength"=>200),array(),"col-md-4");
              echo "<div class='clearfix'></div>";
            
            echo _input_field("price_vat_exclude", _l("Price(Without VAT)")."<span class='text-danger'>*</span>", _get_post_back($field,'price_vat_exclude'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-3");
            echo _select("vat",$vats,_("VAT(%)")."<span class='text-danger'>*</span>",array("vat","vat_title"),_get_post_back($field,'vat'),array(),array("form_group_class"=>"col-md-3"));
            echo _input_field("price", _l("Price(With VAT)")."<span class='text-danger'>*</span>", _get_post_back($field,'price'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-3");
            
            echo "<div class='clearfix'></div>";

           
            echo _input_field("unit_value", _l("Unit Value")."<span class='text-danger'>*</span>", _get_post_back($field,'unit_value'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-2");
            
            echo _select("unit",$units,_("Unit")."<span class='text-danger'>*</span>",array("unit_nl","unit_nl"),_get_post_back($field,'unit'),array(),array("form_group_class"=>"col-md-2"));
            
            echo _input_field("qty", _l("Qty")."<span class='text-danger'>*</span>", _get_post_back($field,'qty','','1'), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>255,"minvalue"=>"1"),array(),"col-md-2");
            
            echo _input_field("price_note", _l("Price Note"), _get_post_back($field,'price_note'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("max_cart_qty", _l("Max Cart QTY")."<span class='text-danger'>*</span>", _get_post_back($field,'max_cart_qty','','0'), 'number', array("data-validation" =>"required","step"=>"1","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-2");
            
            echo "<div class='clearfix'></div>";
            echo _input_field("product_barcode", _l("Barcode"), _get_post_back($field,'product_barcode'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_two", _l("Barcode 2"), _get_post_back($field,'barcode_two'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_three", _l("Barcode 3"), _get_post_back($field,'barcode_three'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_four", _l("Barcode 4"), _get_post_back($field,'barcode_four'), 'text', array("maxlength"=>255),array(),"col-md-2");
            echo _input_field("barcode_five", _l("Barcode 5"), _get_post_back($field,'barcode_five'), 'text', array("maxlength"=>255),array(),"col-md-2");
            
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_express",_l("Is Express"),"",array(),(isset($field) && isset($field->is_express) && $field->is_express == 1) ? true : false);
            echo _checkbox("status",_l("Status"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
            echo "</div>";
            
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("is_nutritional",_l("Is Nutritional"),"",array(),(isset($field) && isset($field->is_nutritional) && $field->is_nutritional == 1) ? true : false);
            echo "</div>";
            echo "<div class='clearfix'></div>";
            echo _textarea('picker_note',_l("Order picker note"),_get_post_back($field,'picker_note'),array(),array(),"col-md-12");
              
			echo '<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'._l("Update").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            
            echo '</div>';
            
            
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>