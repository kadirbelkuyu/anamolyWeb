<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Billing Settings"); ?></h3>

            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Details will shown on customer bills");
            echo "</blockquote>";
            echo _get_flash_message();
            echo form_open_multipart();
			echo _input_field("billing_name", _l("Billing Name")."<span class='text-danger'>*</span>", _get_post_back($field,'billing_name'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			echo _input_field("billing_address", _l("Billing Address"), _get_post_back($field,'billing_address'), 'text', array(),array(),"col-md-12");
            echo _input_field("tax_id", _l("Tax Identity"), _get_post_back($field,'tax_id'), 'text', array(),array(),"col-md-4");
		    echo _input_field("billing_contact", _l("Contact No."), _get_post_back($field,'billing_contact'), 'text', array(),array(),"col-md-4");
		    echo _input_field("billing_email", _l("Email ID"), _get_post_back($field,'billing_email'), 'text', array(),array(),"col-md-4");
            echo _textarea("billing_note",_l("Terms Note"), _get_post_back($field,'billing_note'),array(),array(),"col-md-12");
            echo _input_field("billing_signature", _l("Signature"), _get_post_back($field,'billing_signature'), 'text', array(),array(),"col-md-4");
            
            echo '<div class="clearfix"></div>';
        
			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>