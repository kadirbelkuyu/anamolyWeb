<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Category"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
        	<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped ">
                <thead>
                <tr>
                    <th width='15'><?php echo _l("Ref"); ?></th>
					<th width="110"><?php echo _l("Image"); ?></th>
					<th><?php echo _l("Category"); ?></th>
          <th><?php echo _l("Featured"); ?></th>
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
                    <tr id="row_<?php echo $count; ?>" data-ref="<?php  echo _encrypt_val($dt->$primary_key); ?>">
						<td><?php echo $count; ?></td>
                        <td ><?php if ($dt->cat_image!="" && file_exists(CATEGORY_IMAGE_PATH."/crop/small/".$dt->cat_image)){ ?>
						<img class="profileImage" src="<?php if(isset($dt->cat_image) && $dt->cat_image != ""){ echo base_url(CATEGORY_IMAGE_PATH."/crop/small/".$dt->cat_image); } ?>" alt="<?php echo _l("Preview"); ?>" height="50"/>
						<?php } ?>
						</td>
                        <td><?php echo $dt->cat_name_nl; ?></td>
                        <td><?php echo ($dt->is_featured == 1) ? _l("Yes") : _l("No"); ?></td>
                        <td><?php echo ($dt->status == 1)? '<a href="javascript:changeStatus(\'0\',\''.$dt->$primary_key.'\',\''.($count-1).'\')" id="ref_'.$dt->$primary_key.'"><span class="label label-success">'._l("Enable").'</span></a>' : '<a href="javascript:changeStatus(\'1\',\''.$dt->$primary_key.'\',\''.($count-1).'\')" id="ref_'.$dt->$primary_key.'"><span class="label label-danger">'._l("Disable").'</span></a>'; ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="javascript:;" data-ref="<?php  echo _encrypt_val($dt->$primary_key); ?>" class="moved btn btn-default btn-xs"><i class="fa fa-arrows-v"></i></a>
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
