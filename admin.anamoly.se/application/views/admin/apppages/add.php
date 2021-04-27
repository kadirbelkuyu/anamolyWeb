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
            <h3 class="box-title"><?php echo _l("App Page"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Pages content use in app to display");
            echo "</blockquote>";
            echo _get_flash_message();
            ?>
            <div class="btn-group">
                      <a type="button" href="?lang=english" class="btn btn-default <?php if($lang == "english"){ echo "active";  } ?>">English</a>
                      <a type="button" href="?lang=dutch" class="btn btn-default <?php if($lang == "dutch"){ echo "active";  } ?>">Swedish</a>
                      <a type="button" href="?lang=turkish" class="btn btn-default <?php if($lang == "turkish"){ echo "active";  } ?>">Turkish</a>
                      <a type="button" href="?lang=arabic" class="btn btn-default <?php if($lang == "arabic"){ echo "active";  } ?>">Arabic</a>
                      <a type="button" href="?lang=german" class="btn btn-default <?php if($lang == "german"){ echo "active";  } ?>">German</a>
                    </div>
            <?php

            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }
			echo _input_field("lang","",(!empty($lang)) ? $lang : "english","hidden");

            echo _input_field("page_title", _l("Title")."<span class='text-danger'>*</span>", _get_post_back($field,'page_title'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");

            echo _textarea('page_content',_l("Page Content"),_get_post_back($field,'page_content'),array(),array(),"col-md-12");
            echo '<p class="help-block col-md-12">'._l("You may use html tags for design template, please not. if unsupported tags will be removed by editor").'</p>';
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
<script>
function insertAtCaret(areaId, text) {
        var txtarea = document.getElementById(areaId);
        if (!txtarea) {
            return;
        }
        CKEDITOR.instances[areaId].insertText(text);
}
</script>
