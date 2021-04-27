<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-body">
        <?php
            
            echo "<div class=''>";
            echo "<form id='filter_product' method='get' action=''>";
            echo _select("category_id",
                     $categories,_l("Category"),
                     array("category_id","cat_name_nl"),
                     _get_post_back($field,'category_id'),
                     array(),
                     array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
            echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category"),array("sub_category_id","sub_cat_name_nl"),_get_post_back($field,'sub_category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _select("product_group_id",(isset($productgroups))?$productgroups : array(),_l("Product Groups"),array("product_group_id","group_name_nl"),_get_post_back($field,'product_group_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo "<div class='clearfix'></div>";
            echo _select("f_status",(isset($f_status))?$f_status : array(),_l("Status"),array(),_get_post_back($field,'f_status'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _select("discount_types",(isset($discount_types))?$discount_types : array(),_l("Type"),array("value"),_get_post_back($field,'discount_types'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _input_field("date_range", _l("Date Range"), _get_post_back($field,'date_range'), 'text', array(),array(),"col-md-3","daterangepicker_field");
            
            echo '<div class="col-md-3" ><button type="submit" id="filterProducts" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button>';
            $get = $this->input->get();
            if(!empty($get)){
                echo "<a href='".site_url($controller)."' style='margin-top:25px;'  class='btn btn-default btn-flat'>"._l("Clear")."</a>";
            }
            echo '</div>';
            
            echo "</form>";
            echo "</div>";
            
    
        ?>
        </div>
    </div>
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Discounts"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
					<th><?php echo _l("Product"); ?></th>
                    <th><?php echo _l("Discount"); ?></th>
                    <th><?php echo _l("Type"); ?></th>
					<th><?php echo _l("Validity"); ?></th>
                    <th><?php echo _l("Status"); ?></th>
					<th width='90'><?php echo _l("Action"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
				$count = 0;
                foreach($data as $dt){
					$count++;	
                    ?>
                    <tr id="row_<?php echo $count; ?>">
						<td><?php echo $dt->product_name_nl; ?></td>
                        <td><?php   if($dt->discount_type == "flat" || $dt->discount_type == "flatcombo"){
                                        echo $dt->discount;
                                    }else{
                                        echo $dt->discount . "%";
                                    }?>
                        </td>
                        <td><?php echo $dt->discount_type; ?></td>
                        <td><?php echo date(DEFAULT_DATE_FORMATE,strtotime($dt->start_date)) . _l(" TO ") . date(DEFAULT_DATE_FORMATE,strtotime($dt->end_date)); ?></td>
                        <td><?php echo ($dt->status == 1)? '<a href="javascript:changeStatus(\'0\',\''.$dt->$primary_key.'\',\''.($count-1).'\')" id="ref_'.$dt->$primary_key.'"><span class="label label-success">'._l("Published").'</span></a>' : '<a href="javascript:changeStatus(\'1\',\''.$dt->$primary_key.'\',\''.($count-1).'\')" id="ref_'.$dt->$primary_key.'"><span class="label label-default">'._l("Pending").'</span></a>'; ?></td>
                        
                        <td>
                            <div class="btn-group">
                                <a href="<?php echo site_url($controller."/edit/"._encrypt_val($dt->$primary_key)); ?>" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
								<?php if(_is_admin()){ ?>
                                <a href="javascript:deleteRecord('<?php echo site_url($controller."/delete/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
								<?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box -->
</section>
