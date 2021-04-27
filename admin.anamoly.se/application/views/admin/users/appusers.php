<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            
            <h3 class="box-title"><?php echo _l("App User"); ?> / <?php echo _l("List"); ?></h3>
            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add_appuser");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
					<th><?php echo _l("Full Name"); ?></th>
					<th><?php echo _l("Email ID"); ?></th>
                    <th><?php echo _l("Phone"); ?></th>
                    <th><?php echo _l("Postal Code"); ?></th>
                    <th><?php echo _l("Type"); ?></th>
                    <th><?php echo _l("Registration"); ?></th>
                    <th><?php echo _l("Activation"); ?></th>
                    <th><?php echo _l("Last Login"); ?></th>
                    <th><?php echo _l("Status"); ?></th>
                    <?php if(isset($waiting)){
                            ?>
                            <th><?php echo _l("Waiting List"); ?></th>
                            <?php
                        } ?>
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
                        <td><?php echo $dt->postal_code; ?></td>
                        <td><?php switch($dt->user_type_id){
                            case USER_CUSTOMER:
                                echo _l("Customer");
                                break;
                            case USER_COMPANY:
                                echo _l("Company");
                                break;
                                
                        } ?></td>
                        <td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($dt->registration_date)); ?></td>
                        <td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($dt->activation_date)); ?></td>
                        <td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($dt->login_date)); ?></td>
                        <td><?php if($dt->status == 3){
                               echo "<span class='label label-primary'>"._l("Waiting")."(".$dt->req_queue.")"."</span>";
                        }else if($dt->postal_code_id == NULL || $dt->postal_code_id == 0){
                              echo "<span class='label label-info'>"._l("No Postal")."(".$dt->postal_code.")"."</span>";
                        }else if($dt->status == 0){
                            echo "<span class='label label-default'>"._l("In Active")."</span>";
                        }else if($dt->status == 1){
                            echo "<span class='label label-success'>"._l("Active")."</span>";
                        } ?></td>
                        <?php if(isset($waiting)){
                            ?>
                            <td><?php echo $dt->req_queue; ?></td>
                            <?php
                        } ?>
                        <td>
                            <div class="btn-group">
                                <a href="<?php echo site_url($controller."/details/"._encrypt_val($dt->$primary_key)); ?>" class="btn btn-default btn-xs"><?php echo _l("Details") ?></a>
								<?php if(isset($waiting)){
                                ?>
                                    <a href="javascript:accpetWaiting('<?php echo site_url($controller."/accpet_waiting/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)" class="btn btn-info btn-xs"><?php echo _l("Accept"); ?></a>
                                <?php
                                }?>
                                <?php if(_is_admin()){ ?>
                                <a href="<?php echo site_url($controller."/edit_appuser/"._encrypt_val($dt->$primary_key)); ?>" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
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
