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
            <h3 class="box-title"><?php echo _l("Coupon"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Coupons");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("coupon_code", _l("Coupon Code")."<span class='text-danger'>*</span>", _get_post_back($field,'coupon_code'), 'text', array("data-validation" =>"required","maxlength"=>50),array(),"col-md-4");
			
            
            echo _select("coupon_type",$coupon_type,_l("Coupon Type")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'coupon_type'),array(),array("form_group_class"=>"col-md-4"));
            
            echo "<div class='clearfix'></div>";
            
            echo _input_field("discount", _l("Discount")."<span class='text-danger'>*</span>", _get_post_back($field,'discount'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
            
            
            echo _select("discount_type",$discount_types,_l("Discount Type")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'discount_type'),array(),array("form_group_class"=>"col-md-4"));
            
            echo _input_field("max_discount_amount", _l("Maximum Applicable Amount")."<span class='text-danger'>*</span>", _get_post_back($field,'max_discount_amount','',0), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-4");
            
            echo _input_field("min_order_amount", _l("Min Order Amount")."<span class='text-danger'>*</span>", _get_post_back($field,'min_order_amount','',0), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-4");
            
           
            echo _input_field("validity", _l("Validity")."<span class='text-danger'>*</span>", _get_post_back($field,'validity'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4","daterangepicker_field");
            
            echo "<div class='clearfix'></div>";
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("multi_usage",_l("Allow Multi Usage"),"",array(),(isset($field) && isset($field->multi_usage) && $field->multi_usage == 1) ? true : false);
            echo "</div>";
            
			echo "<div class='clearfix'></div>";
			
            echo _textarea('description_nl',_l("Description")._l("(Dutch)"),_get_post_back($field,'description_nl'),array(),array(),"col-md-12");
            echo _textarea('description_en',_l("Description")._l("(En)"),_get_post_back($field,'description_en'),array(),array(),"col-md-12");
			echo _textarea('description_ar',_l("Description")._l("(Ar)"),_get_post_back($field,'description_ar'),array(),array(),"col-md-12");
			echo _textarea('description_tr',_l("Description")._l("(Tr)"),_get_post_back($field,'description_tr'),array(),array(),"col-md-12");
			echo _textarea('description_de',_l("Description")._l("(De)"),_get_post_back($field,'description_de'),array(),array(),"col-md-12");
            
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