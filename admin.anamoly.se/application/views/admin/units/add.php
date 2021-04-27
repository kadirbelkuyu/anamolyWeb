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
            <h3 class="box-title"><?php echo _l("Units"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Units use for products classification");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			
            echo _input_field("unit_nl", _l("Unit")._l("(Dutch)")."<span class='text-danger'>*</span>", _get_post_back($field,'unit_nl'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");
			echo _input_field("unit_en", _l("Unit")._l("(En)"), _get_post_back($field,'unit_en'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("unit_ar", _l("Unit")._l("(Ar)"), _get_post_back($field,'unit_ar'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("unit_tr", _l("Unit")._l("(Tr)"), _get_post_back($field,'unit_tr'), 'text', array("maxlength"=>200),array(),"col-md-4");
            echo _input_field("unit_de", _l("Unit")._l("(De)"), _get_post_back($field,'unit_de'), 'text', array("maxlength"=>200),array(),"col-md-4");
                       
			echo "<div class='clearfix'></div>";

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