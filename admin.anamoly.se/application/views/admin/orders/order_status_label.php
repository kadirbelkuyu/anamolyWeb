<?php 
                        $action_button = [];
                        $status_title = "";
                        $btn_cls = "btn-secondary";
                        switch($dt->status){
                            case ORDER_PENDING :
                                $action_button[] = "<a href='javascript:assignOrder(\""._encrypt_val($dt->order_id)."\",".$count.")' >"._l("Confirm").'</a>';
                                $action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/"._encrypt_val($dt->order_id))."\",".$count.",".ORDER_DECLINE.")' >"._l("Decline").'</a>';
                                $status_title = _l("Pending");
                            break;
                            case ORDER_CANCEL :
                                $status_title = _l("Canceled");
                            break;
                            case ORDER_CONFIRMED :
                                $action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/"._encrypt_val($dt->order_id))."\",".$count.",".ORDER_OUT_OF_DELIVEY.")' >"._l("Out Of Delivery").'</a>';
                                
                                $status_title = _l("Confirmed");
                                $btn_cls = "btn-info";
                            break;
                            case ORDER_OUT_OF_DELIVEY :
                                $action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/"._encrypt_val($dt->order_id))."\",".$count.",".ORDER_DELIVERED.")' >"._l("Delivered").'</a>';
                                
                                $status_title = _l("Out of Delivery");
                                $btn_cls = "btn-warning";
                            break;
                            case ORDER_DELIVERED :
                                $status_title = _l("Delivered");
                                $btn_cls = "btn-success";
                            break;
                            case ORDER_DECLINE :
                                $status_title = _l("Declined");
                                $btn_cls = "btn-danger";
                            break;
                            case ORDER_UNPAID :
                                $action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/"._encrypt_val($dt->order_id))."\",".$count.",".ORDER_PAID.")' >"._l("Paid").'</a>';
                                $action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/"._encrypt_val($dt->order_id))."\",".$count.",".ORDER_DECLINE.")' >"._l("Decline").'</a>';
                                $status_title = _l("Unpaid");
                                $btn_cls = "btn-danger";
                            break;
                            case ORDER_PAID :
                                //$action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/"._encrypt_val($dt->order_id))."\",".$count.",".ORDER_OUT_OF_DELIVEY.")' >"._l("Out Of Delivery").'</a>';
                                $action_button[] = "<a href='javascript:assignOrder(\""._encrypt_val($dt->order_id)."\",".$count.")' >"._l("Assigned").'</a>';
                                
                                $status_title = _l("Paid");
                                $btn_cls = "btn-success";
                            break;
                        } ?>
                        <div class="dropdown">
                        <button class="btn <?php echo $btn_cls; ?> btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $status_title; ?>
                        </button>
                        <?php if(!empty($action_button)){?>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php foreach($action_button as $btn){
                                    echo "<li class='dropdown-item'>".$btn."</li>";
                                } ?>
                            </ul>
                        <?php } ?>
                        </div>