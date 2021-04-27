<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Orders"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
               
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12" id="messages"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
					<th><?php echo _l("Order No"); ?></th>
                    <th><?php echo _l("Order Date"); ?></th>
                    <th><?php echo _l("Delivery Date"); ?></th>
                    <th><?php echo _l("Customer"); ?></th>
                    <th><?php echo _l("Customer Phone"); ?></th>
                    <th><?php echo _l("Postal Code"); ?></th>
                    <th><?php echo _l("City"); ?></th>
                    <th><?php echo _l("Total Amounts"); ?></th>
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
						<td><?php echo $dt->order_no; ?></td>
                        <td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($dt->order_date)); ?></td>
                        <td><?php echo date(DEFAULT_DATE_FORMATE,strtotime($dt->delivery_date))." ".date(DEFAULT_TIME_FORMATE,strtotime($dt->delivery_time)); ?></td>
                        <td><?php echo $dt->user_firstname." ".$dt->user_lastname; ?></td>
                        <td><?php echo $dt->user_phone; ?></td>
                        <td><?php echo $dt->postal_code; ?></td>
                        <td><?php echo $dt->city; ?></td>
                        <td><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$dt->net_amount; ?></td>
                        <td>
                            <?php $this->load->view("admin/orders/order_status_label",array("dt"=>$dt,"count"=>$count)); ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="<?php echo site_url("admin/orders/receipt/"._encrypt_val($dt->order_id)); ?>" class="btn btn-default btn-xs"><i class="fa fa-print"></i> <?php echo _l("Print"); ?></a>
                                <?php if(_is_admin()){ ?>
                                <a href="javascript:deleteRecord('<?php echo site_url("admin/orders/delete/"._encrypt_val($dt->order_id)); ?>',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
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
<div class="modal fade" id="assignDeliveryBoyModal" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Assign Delivery Boy") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body delivery-boy-content">
      <?php
      
					echo form_open_multipart("admin/orders/assign_deliveryboy",array("id"=>"form_assign_delivery_boy"));
                    echo _input_field("assign_order_id","","","hidden");
                    echo _input_field("row_index","","","hidden"); 
                    echo _select("delivery_boy_id",$deliveryboys,_l("Delivery Boy")."<span class='text-danger'>*</span>",array("delivery_boy_id","boy_name"),"",array("data-validation"=>"required"),array("form_group_class"=>"col-md-6","include_blank"=>_l("Select Delivery Boy")));
					echo '<div class="col-md-2">
							<br />
							<button type="submit" class="btn btn-primary btn-flat">'._l("Assign").'</button>&nbsp;';
					echo '</div>';
					echo form_close();
				?>
                <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>