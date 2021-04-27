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
            
            <h3 class="box-title"><?php echo _l("Roles"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='".site_url($controller)."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Roles");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
                
            }       
			echo "<div class='clearfix'></div>";
            echo _input_field("role_title", _l("Role Title")."<span class='text-danger'>*</span>", _get_post_back($field,'role_title'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"col-md-4");
            
            echo "<div class='clearfix'></div>";
            
            
            ?>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <label><?php echo _l("Permissions"); ?></label>
            <div class="row">
                <div class="col-md-12">
                    <?php foreach($permissions as $permission){
                        echo "<div class='col-md-4 col-xs-4'>";
                        echo _checkbox("permission[".$permission->permission_id."]",_l($permission->permission_title),"",array(),(isset($permission) && isset($permission->role_id) && $permission->role_id != 0) ? true : false);
                        echo "</div>";
                    }
                    ?>
                </div>
        <?php
        echo '<div class="col-md-12">
        <br>
        <button type="submit" class="btn btn-primary btn-flat">'.$updBtn.'</button>&nbsp;';
    echo "<a class='btn btn-danger btn-flat' href='".site_url($controller)."'>Cancel</a>";
    echo '</div>';
        ?>
            </div>
        </div>
        
    </div>
    
    <?php echo form_close(); ?>
    <!-- /.box -->
</section>