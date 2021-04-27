<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Email Settings"); ?></h3>

            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("SendGrid email settings for email services for sent emails");
            echo "</blockquote>";
            echo _get_flash_message();
            echo form_open_multipart();
			echo _input_field("email_sender", _l("Email Sender")."<span class='text-danger'>*</span>", _get_post_back($field,'email_sender'), 'email', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
			echo _input_field("sendgrid_id", _l("Sendgrid ID")."<span class='text-danger'>*</span>", _get_post_back($field,'sendgrid_id'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
            echo _input_field("sendgrid_key", _l("Sendgrid Key")."<span class='text-danger'>*</span>", _get_post_back($field,'sendgrid_key'), 'text', array("data-validation" =>"required"),array(),"col-md-12");
		    echo '<div class="clearfix"></div>';
            echo _input_field("receiver_email", _l("Receiver Email")."<span class='text-danger'>*</span>", _get_post_back($field,'receiver_email'), 'email', array("data-validation" =>"email","maxlength"=>255),array(),"col-md-4");
            echo '<div class="clearfix"></div>';
			echo "<small class='col-md-12'>"._l("This will receive new register and new order emails")."</small>";
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