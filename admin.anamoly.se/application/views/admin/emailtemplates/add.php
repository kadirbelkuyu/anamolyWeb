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
            <h3 class="box-title"><?php echo _l("Email Template"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <div class="btn-group">
                      <a type="button" href="?lang=english" class="btn btn-default <?php if($lang == "english"){ echo "active";  } ?>">English</a>
                      <a type="button" href="?lang=dutch" class="btn btn-default <?php if($lang == "dutch"){ echo "active";  } ?>">Swedish</a>
                      <a type="button" href="?lang=turkish" class="btn btn-default <?php if($lang == "turkish"){ echo "active";  } ?>">Turkish</a>
                      <a type="button" href="?lang=arabic" class="btn btn-default <?php if($lang == "arabic"){ echo "active";  } ?>">Arabic</a>
                      <a type="button" href="?lang=german" class="btn btn-default <?php if($lang == "german"){ echo "active";  } ?>">German</a>
                    </div>
            <?php
            echo _get_flash_message();

            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden");
            }
            echo _input_field("lang","",(!empty($lang)) ? $lang : "english","hidden");
            echo _input_field("email_subject", _l("Subject")."<span class='text-danger'>*</span>", _get_post_back($field,'email_subject'), 'text', array("data-validation" =>"required","maxlength"=>200),array(),"col-md-4");

            echo _textarea('email_message',_l("Message"),_get_post_back($field,'email_message'),array(),array(),"col-md-12");
            echo '<p class="help-block col-md-12">'._l("You may use html tags for design template, please not. if unsupported tags will be removed by editor").'</p>';
			echo "<div class='clearfix'></div>";
			?>
            <div class="col-md-12">
            <?php
                $tags = explode(",",$field->email_tags);
                foreach($tags as $tag){
            ?>
                <a href="#" class="btn btn-default" onclick="insertAtCaret('email_message', '##<?php echo trim($tag); ?>##');return false;">##<?php echo trim($tag) ?>##</a>
            <?php
                }
            ?>
            <br />
            <small><?php echo _l("Note : this tags you may insert in your template desing this will take values from server while send email") ?></small>
                                        </div>
            <?php
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
