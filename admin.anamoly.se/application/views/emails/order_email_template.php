 
        <h3 class="text-bold text-center"><?php echo _l("Estimate"); ?></h3>
							<table class="table table-condensed" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td>
										<b class="text-bold"><?php echo $setting["billing_name"]; ?></b>
                                        <?php echo $setting["billing_address"]; ?><br/>
                                        <?php echo _l("Phone:")." ".$setting["billing_contact"]; ?><br/>
                                        <?php echo _l("Email:")." ".$setting["billing_email"]; ?><br/>
                                        <?php echo $setting["tax_id"]; ?>
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
									<td width="20%">
										
											<span class="text-bold"><?php echo _l("Order Date :"); ?></span> 
											<?php echo date(DEFAULT_DATE_FORMATE,strtotime($order->order_date)); ?><br/>
										
											<span class="text-bold"><?php echo _l("Order No.:"); ?></span> 
											<?php echo $order->order_no; ?>
                                        
									</td>
								</tr>	
									
							</table>	
							<table class="table table-bordered table-condensed"  width="100%" cellspacing="0" cellpadding="0">
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
										<td>
												<?php echo ($language == "english" && $item->product_name_en!="") ? $item->product_name_en : $item->product_name_nl; ?>
										</td>
										<td><?php echo $item->unit_value; ?></td>
										<td><?php echo ($language == "english" && $item->unit_en!="") ? $item->unit_en : $item->unit; ?></td>
										<td><?php echo $item->order_qty; ?></td>
										<td class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".sprintf( "%.2f",$item->product_price - (($item->discount_amount == NULL) ? 0 : $item->discount_amount)); ?></td>
                                        <td class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".($item->price * $item->order_qty); ?></td>
									</tr>
								<?php }} ?>	
								</tbody>
								<tfoot>
									<tr>
										<th colspan="5" class="text-right"><?php echo _l("Total Amount"); ?></th>
										
										<th class="text-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$order->net_amount; ?></th>
									</tr>
								</tfoot>
							</table>
							
		