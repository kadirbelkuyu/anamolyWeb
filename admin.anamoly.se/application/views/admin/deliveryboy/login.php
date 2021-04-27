<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
			<?php
				
					$updBtn=_l("Login");
				
			?>
            <h3 class="box-title"><?php echo _l("Delivery Boy"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Delivery Login Details");
            echo "</blockquote>";
            echo _get_flash_message();
            echo form_open_multipart();
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
                echo _input_field("boy_phone", _l("Boy Phone")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_phone'), 'text', array("data-validation" =>"phone","maxlength"=>200),array(),"col-md-4");
            
                echo _input_field("boy_password", _l("Login Password")."<span class='text-danger'>*</span>", _get_post_back($field,'boy_password'), 'password', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
			    
            
			echo '<div class="col-md-12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'._l("Update").'</button>&nbsp;';
			echo "<a class='btn btn-danger btn-flat' href='".site_url($controller)."'>Cancel</a>";
			echo '</div>';
            echo form_close();
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>