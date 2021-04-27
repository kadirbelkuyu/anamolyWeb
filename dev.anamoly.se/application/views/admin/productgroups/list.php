<!-- Main content -->
<section class="content">
    <!-- Default box -->
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
            echo _select("f_status",(isset($f_status))?$f_status : array(),_l("Status"),array(),_get_post_back($field,'f_status'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo '<div class="col-md-3" ><button type="submit" id="filterProducts" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button></div>';
            
            echo "</form>";
            echo "</div>";
            
    
        ?>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Groups"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
                    <th><?php echo _l("Group"); ?></th>
					<th><?php echo _l("Category"); ?></th>
                    <th><?php echo _l("Sub Category"); ?></th>
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
						<td><?php echo $dt->group_name_nl; ?>
						</td>
                        <td><?php echo $dt->cat_name_nl; ?></td>
                        <td><?php echo $dt->sub_cat_name_nl; ?></td>
                        <td><?php echo ($dt->status == 1)? '<a href="javascript:changeStatus(\'0\',\''.$dt->$primary_key.'\',\''.($count-1).'\')" id="ref_'.$dt->$primary_key.'"><span class="label label-success">'._l("Enable").'</span></a>' : '<a href="javascript:changeStatus(\'1\',\''.$dt->$primary_key.'\',\''.($count-1).'\')" id="ref_'.$dt->$primary_key.'"><span class="label label-danger">'._l("Disable").'</span></a>'; ?></td>
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
