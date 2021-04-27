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
            <h3 class="box-title"><?php echo _l("Referral Codes"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Referral Codes");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("coupon_code", _l("Referral Code")."<span class='text-danger'>*</span>", _get_post_back($field,'coupon_code'), 'text', array("data-validation" =>"required","maxlength"=>50),array(),"col-md-4");
			
            
            echo _select("coupon_type",$coupon_type,_l("Referral Type")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'coupon_type'),array(),array("form_group_class"=>"col-md-4"));
            
            echo "<div class='clearfix'></div>";
            
            echo _input_field("discount", _l("Discount")."<span class='text-danger'>*</span>", _get_post_back($field,'discount'), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>20,"minvalue"=>"0"),array(),"col-md-4");
            
            
            echo _select("discount_type",$discount_types,_l("Discount Type")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'discount_type'),array(),array("form_group_class"=>"col-md-4"));
            
            echo _input_field("max_discount_amount", _l("Maximum Applicable Amount")."<span class='text-danger'>*</span>", _get_post_back($field,'max_discount_amount','',0), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-4");
            
            echo _input_field("min_order_amount", _l("Min Order Amount")."<span class='text-danger'>*</span>", _get_post_back($field,'min_order_amount','',0), 'number', array("data-validation" =>"required","step"=>"0.1","maxlength"=>255,"minvalue"=>"0"),array(),"col-md-4");
            
           
            echo _input_field("valid_days", _l("Valid till Days")."<span class='text-danger'>*</span>", _get_post_back($field,'valid_days'), 'number', array("data-validation" =>"number","maxlength"=>255),array(),"col-md-4","");
            
            echo "<div class='clearfix'></div>";
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("multi_usage",_l("Allow Multi Usage"),"",array(),(isset($field) && isset($field->multi_usage) && ($field->multi_usage == 1 || $field->multi_usage == "on")) ? true : false);
            echo "</div>";
            echo _input_field("maximum_usages", _l("Maximum Usage"), _get_post_back($field,'maximum_usages'), 'number', array("data-validation" =>"number","maxlength"=>255),array(),"col-md-4","");
            
		
			echo "<div class='clearfix'></div>";
            echo "<div class='col-md-4 col-xs-4'>";
            echo _checkbox("status",_l("Enable"),"",array(),(isset($field) && isset($field->status) && ($field->status == 1 || $field->status == "on")) ? true : false);
            echo "</div>";
            
            echo _textarea('tnc_nl',_l("Terms And Condtion")._l("(Dutch)"),_get_post_back($field,'tnc_nl'),array(),array(),"col-md-12");
            echo _textarea('tnc_en',_l("Terms And Condtion")._l("(En)"),_get_post_back($field,'tnc_en'),array(),array(),"col-md-12");
			echo _textarea('tnc_ar',_l("Terms And Condtion")._l("(Ar)"),_get_post_back($field,'tnc_ar'),array(),array(),"col-md-12");
			echo _textarea('tnc_tr',_l("Terms And Condtion")._l("(Tr)"),_get_post_back($field,'tnc_tr'),array(),array(),"col-md-12");
			echo _textarea('tnc_de',_l("Terms And Condtion")._l("(De)"),_get_post_back($field,'tnc_de'),array(),array(),"col-md-12");
			
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