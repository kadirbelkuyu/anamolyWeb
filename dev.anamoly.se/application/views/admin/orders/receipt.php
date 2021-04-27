<!-- Main content -->
    <!-- Default box -->
    
<section class="content">
<div class="box noprint">
        <div class="box-header with-border ">
			<div>
				<div class="col-md-6">
				<?php
					echo _get_flash_message();
					echo form_open_multipart();
					echo _select("delivery_boy_id",$deliveryboys,_l("Delivery Boy")."<span class='text-danger'>*</span>",array("delivery_boy_id","boy_name"),_get_post_back($order,'delivery_boy_id'),array("data-validation"=>"required"),array("form_group_class"=>"col-md-6","include_blank"=>_l("Select Delivery Boy")));
					echo '<div class="col-md-2">
							<br />
							<button type="submit" class="btn btn-primary btn-flat">'._l("Assign").'</button>&nbsp;';
					echo '</div>';
					echo form_close();
				?>
				</div>
				<div class="col-md-6">
					<a href="<?php echo site_url($controller."/receipt/"._encrypt_val($order->order_id)."/packing") ?>" class="btn btn-warning pull-right" style="margin-top:20px;" ><?php echo _l("Packing Slip") ?></a>
				
					<?php if($order->vehicle_id > 0){ ?>

					<table class="table">
						<tr><td><?php echo _l("Vehicle No.") ?></td><td><?php echo $order->vehicle_no; ?></td></tr>
						<tr><td><?php echo _l("Boy Name") ?></td><td><?php echo $order->boy_name; ?></td></tr>
						<tr><td><?php echo _l("Boy Phone") ?></td><td><?php echo $order->boy_phone; ?></td></tr>
					</table>
					<?php } ?>
				</div>
			</div>
        </div>
	</div>

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border noprint">
            <h3 class="box-title"><?php echo _l("Order") ; ?> / <?php echo _l("Detail"); ?></h3>

            <div class="box-tools pull-right">
				<button class="btn btn-box-tool btn-default" type="button" onclick="window.print()"><?php echo _l("Print"); ?></button>
                <?php 
                $backurl=site_url($controller);
                if(!empty($this->session->userdata("backurl")))
                {
                    $backurl=$this->session->userdata('backurl');
                }
                ?>
                <a href="<?php echo $backurl?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>

        </div>
        <div class="box-body">
            
        <h3 class="text-bold text-center"><?php echo _l("Estimate"); ?></h3>
							<table class="table table-condensed">
								<tr>
									<td >
										<b class="text-bold"><?php echo $setting["billing_name"]; ?></b>
                                        
                                        <p>
                                        <?php echo $setting["billing_address"]; ?><br/>
                                        <?php echo _l("Phone:")." ".$setting["billing_contact"]; ?><br/>
                                        <?php echo _l("Email:")." ".$setting["billing_email"]; ?><br/>
                                        <?php echo $setting["tax_id"]; ?>
                                        </p>
									</td>
									<td>
									<p>
												<span class="text-bold"><?php echo _l("To:"); ?></span><br/>
											
												<?php echo $order->user_firstname." ".$order->user_lastname; ?><br/>
                                                <?php echo $order->street_name." ".$order->house_no." ".$order->add_on_house_no; ?><br/>
                                                <?php echo $order->postal_code." ".$order->city; ?><br/>
                                                <?php echo _l("Phone:")." ".$order->user_phone; ?>
										</p>
									</td>
									<td width="200px">
									
											<span class="text-bold"><?php echo _l("Order Date :"); ?></span> 
											<?php echo date(DEFAULT_DATE_FORMATE,strtotime($order->order_date)); ?><br/>
										
											<span class="text-bold"><?php echo _l("Order No.:"); ?></span> 
											<?php echo $order->order_no; ?>
									
                                        
									</td>
								</tr>	
									
							</table>	
							<table class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th><?php echo _l("Item"); ?></th>
										<th><?php echo _l("Unit Value"); ?></th>
										<th><?php echo _l("Unit"); ?></th>
										<th><?php echo _l("Qty"); ?></th>
										<th class="text-right"><?php echo _l("Price / Qty"); ?></th>
                                        <th class="text-right"><?php echo _l("Total"); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
									
									if(!empty($items)){ 
                                        foreach($items as $item){ 
									
								?>
									<tr>
										<td><?php echo $item->product_name_nl; ?>
											<?php if($item->picker_note != ""){
												?>
												<small><?php echo $item->picker_note; ?></small>
												<?php
											} ?>
										</td>
										<td><?php echo $item->unit_value; ?></td>
										<td><?php echo $item->unit; ?></td>
										<td><?php echo $item->order_qty; ?></td>
										<td class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".sprintf( "%.2f",$item->price); ?></td>
                                        <td class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$item->price * $item->order_qty; ?></td>
									</tr>
								<?php }} ?>	
								</tbody>
								<tfoot>
									<?php
										if($order->delivery_amount > 0){
									?>
									<tr>
										<th colspan="3" class="text-right"><?php echo _l("Delivery Charges"); ?></th>
										<th class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->delivery_amount; ?></th>
									</tr>
									<?php
										}
									?>
									<?php
										if($order->gateway_charges > 0){
									?>
									<tr>
										<th colspan="3" class="text-right"><?php echo _l("Gateway Charges"); ?></th>
										<th class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->gateway_charges; ?></th>
									</tr>
									<?php
										}
									?>
									<tr>
										<th colspan="5" class="text-right"><?php echo _l("Total Amount"); ?></th>
										
										<th class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->net_amount; ?></th>
									</tr>
								</tfoot>
							</table>
							<small class="">
								<?php echo $setting["billing_note"]; ?>
							</small>
							<div class="col-md-12 col-sm-12 col-xs-12 col-xl-12">
								<div class="col-md-8 col-sm-8 col-xs-8 col-xl-8"></div>		
								<div class="col-md-4 col-sm-4 col-xs-4 col-xl-4">
									<br/>
									<hr/>
									<h5 class="text-bold"><?php echo _l("Receiver's Signature"); ?></h5>
									<br/>
									<hr/>
									<h5 class="text-bold"><?php echo _l("Date & Time"); ?></h5>
								</div>		
							</div>
			
        </div>
    </div>
    <!-- /.box -->
</section>
