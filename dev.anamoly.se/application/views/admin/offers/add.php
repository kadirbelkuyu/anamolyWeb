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
            <h3 class="box-title"><?php echo _l("Offer"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Offer");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }
            
            echo _select("product_ids[]",$products,_l("Products")."<span class='text-danger'>*</span>",array("product_id","product_name_nl"),_get_post_back($field,'product_ids'),array("data-validation"=>"required","multiple"=>"true"),array("form_group_class"=>"col-md-12","include_blank"=>_l("Select Product")));
            echo "<div class='clearfix'></div>";
            $offer_type = _get_post_back($field,'offer_type');
            $nop_title = _l("X+1");
            $nop_note = _l("Ger 1 Item free on this offer");
            $discount_title = _l("Discount");

            $is_hide = "";
            if($offer_type == "flatcombo"){
                $nop_title = _l("X Numbers");
                $nop_note = _l("Apply Flat price on X number of products");
                $discount_title = _l("Flat Price");
            }else{
                $is_hide = "hide";
            }
            
            echo _input_field("number_of_products", $nop_title."<span class='text-danger'>*</span>", _get_post_back($field,'number_of_products'), 'number', array("step"=>"1","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4 ".(($offer_type == "plusone" || $offer_type == "flatcombo")? "" : "") ,'',$nop_note);
           
            echo _input_field("offer_discount", $discount_title."<span class='text-danger'>*</span>", _get_post_back($field,'offer_discount'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4 div_offer_discount $is_hide");
            
            
            echo _select("offer_type",$offer_types,_l("Offer Type")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'offer_type'),array(),array("form_group_class"=>"col-md-4"));
            
			echo "<div class='clearfix'></div>";
            echo _input_field("validity", _l("Validity")."<span class='text-danger'>*</span>", _get_post_back($field,'validity'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4","daterangepicker_field");
            
            
            echo "<div class='clearfix'></div>";
            
            
            echo "<div class='clearfix'></div>";
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("status",_l("Published"),"",array(),(isset($field) && isset($field->status) && $field->status == 1) ? true : false);
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