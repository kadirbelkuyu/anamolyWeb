<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("General Settings"); ?></h3>

            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("General Settings");
            echo "</blockquote>";
            echo _get_flash_message();
            echo form_open_multipart();
			echo _input_field("name", _l("Name")."<span class='text-danger'>*</span>", _get_post_back($field,'name'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("copyright", _l("Copy Right")."<span class='text-danger'>*</span>", _get_post_back($field,'copyright'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo _input_field("website", _l("Website")."<span class='text-danger'>*</span>", _get_post_back($field,'website'), 'url', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
		    echo '<div class="clearfix"></div>';
            echo _input_field("currency", _l("Currency")."<span class='text-danger'>*</span>", _get_post_back($field,'currency'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("currency_symbol", _l("Currency Symbol")."<span class='text-danger'>*</span>", _get_post_back($field,'currency_symbol'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            echo '<div class="clearfix"></div>';
            
            echo _input_field("gateway_charges", _l("Gateway Charges")."<span class='text-danger'>*</span>", _get_post_back($field,'gateway_charges'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255),array(),"col-md-4");
			echo _input_field("express_delivery_charge", _l("Express Delivery Charges")."<span class='text-danger'>*</span>", _get_post_back($field,'express_delivery_charge'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255),array(),"col-md-4");
			echo _input_field("express_delivery_time", _l("Express Delivery Time in (Min)")."<span class='text-danger'>*</span>", _get_post_back($field,'express_delivery_time'), 'number', array("data-validation" =>"required","step"=>"0.01","maxlength"=>255),array(),"col-md-4");
            
            echo '<div class="clearfix"></div>';
            
            echo _select("enable_code_payment",array("yes","no"),_("Enable COD Payment")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'enable_code_payment'),array(),array("form_group_class"=>"col-md-4"));
            echo _select("enable_ideal_payment",array("yes","no"),_("Enable IDEAL Payment")."<span class='text-danger'>*</span>",array("value"),_get_post_back($field,'enable_ideal_payment'),array(),array("form_group_class"=>"col-md-4"));
            echo _input_field("stock_alert", _l("Stock Alert")."<span class='text-danger'>*</span>", _get_post_back($field,'stock_alert'), 'number', array("data-validation" =>"required","step"=>"1","maxlength"=>255,"minvalue"=>"1"),array(),"col-md-4");

			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>