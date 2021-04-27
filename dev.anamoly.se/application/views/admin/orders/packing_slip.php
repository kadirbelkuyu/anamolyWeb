<!-- Main content -->
    <!-- Default box -->
    
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border noprint">
            <h3 class="box-title"><?php echo _l("Order") ; ?> / <?php echo _l("Packing Detail"); ?></h3>

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
            
        <h3 class="text-bold text-center"><?php echo _l("Packing Slip"); ?></h3>
							<table class="table table-bordered">
								<tr>
									<td width="60%">
                                        <?php if($order->vehicle_id > 0){ ?>
                                        <table class="table table-condensed">
                                            <tr><th><?php echo _l("Vehicle No.") ?></th><td><?php echo $order->vehicle_no; ?></td></tr>
                                            <tr><th><?php echo _l("Driver Name") ?></th><td><?php echo $order->driver_name; ?></td></tr>
                                            <tr><th><?php echo _l("Driver Phone") ?></th><td><?php echo $order->driver_phone; ?></td></tr>
                                        </table>
                                        <?php } ?>
									</td>
									<td width="40%">
										<h5>
											<span class="text-bold"><?php echo _l("Order Date :"); ?></span> 
											<?php echo date(DEFAULT_DATE_FORMATE,strtotime($order->order_date)); ?><br/>
										
											<span class="text-bold"><?php echo _l("Order No.:"); ?></span> 
											<?php echo $order->order_no; ?>
										</h5>
                                        <h5>
												<span class="text-bold"><?php echo _l("To:"); ?></span><br/>
											
												<?php echo $order->user_firstname." ".$order->user_lastname; ?><br/>                                                
                                                <?php echo $order->street_name." ".$order->house_no." ".$order->add_on_house_no; ?><br/>
                                                <?php echo $order->postal_code." ".$order->city; ?><br/>
                                                <?php echo _l("Phone:")." ".$order->user_phone; ?>
										</h5>
									</td>
								</tr>	
									
							</table>	
							<table class="table table-bordered">
								<thead>
									<tr>
										<th><?php echo _l("Item"); ?></th>
										<th><?php echo _l("Unit Value"); ?></th>
										<th><?php echo _l("Unit"); ?></th>
										<th><?php echo _l("Qty"); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
									
									if(!empty($items)){ 
                                        $total_qty = 0;
                                        foreach($items as $item){ 
                                            $total_qty += $item->order_qty;
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
									</tr>
								<?php }} ?>	
								</tbody>
								<tfoot>
									<tr>
										<th class="text-right" colspan="3"><?php echo _l("Total Qty"); ?></th>
										
										<th><?php echo $total_qty; ?></th>
									</tr>
								</tfoot>
							</table>
							
							<div class="col-md-12 col-sm-12 col-xs-12 col-xl-12">
								<div class="col-md-6 col-sm-6 col-xs-6 col-xl-6"></div>		
								<div class="col-md-3 col-sm-3 col-xs-3 col-xl-3">
                                    <br>
									<hr>
									<h5 class="text-bold"><?php echo _l("Date & Time"); ?></h5>
								</div>
                                <div class="col-md-3 col-sm-3 col-xs-3 col-xl-3">
									<br>
									<hr>
									<h5 class="text-bold"><?php echo _l("Signature"); ?></h5>
								</div>		
										
							</div>
			
        </div>
    </div>
    <!-- /.box -->
</section>
