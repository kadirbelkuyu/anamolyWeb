<div class="container">
    <div class="section ui-buttons">
        <div class="row">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("My Orders"); ?></h5>
            </div>

            <ul class="collection invoice-item">
             <?php foreach($orders as $order){ ?>
               <li class="collection-item avatar p_l_20">
                 <a href="<?php echo site_url("order/orderdetail/"._encrypt_val($order->order_id)); ?>">
              <div class="item-det">

                  <span class="title"><?php echo date('l d, M Y',strtotime($order->order_date)); ?></span>
                  <br/>
                  <span class="red-text price_label"><?php echo _lprice($order->net_amount); ?></span><span class="m_l_30"><?php echo _l("QTY"); ?> : <?php echo $order->total_qty; ?></span>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><?php echo _statuslabel($order->status);?><i class="mdi mdi-chevron-right"></i></h6>
                </div>
              </a>
            </li>
      <?php } ?>
        </ul>
        </div>
    </div>
</div>
