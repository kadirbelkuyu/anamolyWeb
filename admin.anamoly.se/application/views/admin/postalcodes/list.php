<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Postal Codes"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
					<th><?php echo _l("Postal Code"); ?></th>
                    <th><?php echo _l("User Limits"); ?></th>
                    <th><?php echo _l("No Of Users"); ?></th>
                    <th><?php echo _l("Min. Order Amount"); ?></th>
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
						
                        <td><?php echo strtoupper($dt->postal_code); ?></td>
                        <td><?php echo $dt->users_limits; ?></td>
                        <td><?php echo $dt->total_users; ?></td>
                        <td><?php echo $dt->min_order_amount; ?></td>
                        <td>
                            <div class="btn-group">
                            <a href="<?php echo site_url($controller."/add/".$dt->postal_code); ?>" class="btn btn-default btn-xs"><i class="fa fa-copy"></i></a>
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