<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <?php $t = _l("Users");
            $f = "customer";
            switch($user_type_id){
                case USER_PLANNER :
                    $t = _l("Planner");
                    $f = "planner";
                break;
                case USER_BUYER :
                    $t = _l("Buyer");
                    $f = "buyer";
                break;
                case USER_ADMIN :
                    $t = _l("Admin");
                    $f = "admin";
                break;
                case USER_CUSTOMER :
                    $t = _l("Customer");
                    $f = "customer";
                break;
                case USER_COMPANY :
                    $t = _l("Company");
                    $f = "company";
                break;
            } ?>
            <h3 class="box-title"><?php echo $t; ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add/".$f);?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
					<th><?php echo _l("Full Name"); ?></th>
					<th><?php echo _l("Email ID"); ?></th>
                    <th><?php echo _l("Phone"); ?></th>
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
						<td><?php echo $dt->user_firstname." ".$dt->user_lastname; ?></td>
                        <td><?php echo $dt->user_email; ?></td>
                        <td><?php echo $dt->user_phone; ?></td>
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
