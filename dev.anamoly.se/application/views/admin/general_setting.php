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
        
			echo '<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Save").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
            ?>
        </div>
    </div>
    <!-- /.box -->
</section>