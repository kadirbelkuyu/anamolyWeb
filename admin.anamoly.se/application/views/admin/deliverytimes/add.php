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
            <h3 class="box-title"><?php echo _l("Delivery Schedule"); ?> / <?php echo $updBtn; ?></h3>

            <div class="box-tools pull-right">
                <?php echo '<button type="submit" class="btn btn-primary btn-flat btn-sm">'.$updBtn.'</button>&nbsp;'; ?>
                <?php echo "<a class='btn btn-danger btn-flat btn-sm' href='"._back_url()."'>"._l("Cancel")."</a>"; ?>
				<a href="<?php echo _back_url();//site_url($controller);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
            <?php
            echo "<blockquote>";
            echo _l("Delivery Time Schedule");
            echo "</blockquote>";
            echo _get_flash_message();
            
            if(!empty($field) && !empty($field->$primary_key)){
                echo _input_field("id","",(!empty($field) && !empty($field->$primary_key)) ? _encrypt_val($field->$primary_key) : "","hidden"); // hidden field use for edit item
            }       
			echo _select("postal_code",$postalcodes,_l("Postal Code")."<span class='text-danger'>*</span>",array("postal_code","postal_code"),_get_post_back($field,'postal_code'),array("data-validation"=>"required"),array("form_group_class"=>"col-md-4"));
            
            //echo _input_field("postal_code", _l("Postal Code")."<span class='text-danger'>*</span>", _get_post_back($field,'postal_code'), 'text', array("data-validation" =>"required","maxlength"=>50),array(),"col-md-4");
            echo "<div class='clearfix'></div>";
            
            $days = $this->config->item("days");
            foreach($days as $day){
                
                $selected_day = _get_post_back($field,'days');
                if(!is_array($selected_day)){
                    $selected_day = explode(",",$selected_day);
                }
                echo "<div class='col-md-1 col-xs-2'>";
                
                                    echo _checkbox("days[$day]",_l($day),$day,array(),(in_array($day,$selected_day)) ? true : false);
                                    echo "</div>";
            $from_time = _get_post_back($field,"from_time[$day]");
            if(!empty($from_time)){
                $from_time = date(DEFAULT_TIME_FORMATE,strtotime($from_time));
            }
			echo _input_field("from_time[$day]", _l("From Time")._l("(En)")."<span class='text-danger'>*</span>", $from_time, 'text', array("data-validation" =>"required"," data-validation-help"=>"HH:mm","maxlength"=>5,"data-validation-depends-on"=>"days[$day]","data-day"=>$day),array(),"col-md-2","from_timepicker");
            
            
            $to_time = _get_post_back($field,"to_time[$day]");
            if(!empty($to_time)){
                $to_time = date(DEFAULT_TIME_FORMATE,strtotime($to_time));
            }
            
                
            echo _input_field("to_time[$day]", _l("To Time")."<span class='text-danger'>*</span>", $to_time, 'text', array("data-validation" =>"required","data-validation-format"=>"HH:mm","data-validation-help"=>"HH:mm","maxlength"=>5,"data-validation-depends-on"=>"from_time[$day]","data-day"=>$day),array(),"col-md-2","to_timepicker");
            
            
            echo _input_field("allow_book_before[$day]", _l("Allow Book Before")._l("(Min.)")."<span class='text-danger'>*</span>", _get_post_back($field,"allow_book_before[$day]"), 'number', array("data-validation" =>"number","data-validation-depends-on"=>"from_time[$day]"),array(),"col-md-3");
            
            echo _input_field("max_no_of_orders[$day]", _l("Maximum Number Of Orders")."<span class='text-danger'>*</span>", _get_post_back($field,"max_no_of_orders[$day]"), 'number', array("data-validation" =>"number","data-validation-depends-on"=>"from_time[$day]"),array(),"col-md-3");
            
            echo "<div class='clearfix'></div>";
            
            
                
            }
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