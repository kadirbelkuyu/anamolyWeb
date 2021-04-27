<?php foreach($carts->products as $item){ ?>
  <ul class="collection ">
  <?php foreach($item->items as $inneritem){ ?>
    <li class="collection-item  cart-item row" id="row_<?php echo $inneritem->cart_id; ?>">
 <div class="item-det col l8 m7 s12">
     <img src="<?php echo _limage($inneritem->product_image,PRODUCT_IMAGE_PATH); ?>" alt="" style="width:60px; float:left; margin-right:10px;" class="circle">
     <div class="pull-left">
       <span class="title"><?php echo _lname($inneritem,"product_name"); ?></span><br />
       <span class="red-text"><?php echo $inneritem->unit_value." "._lunit($inneritem,"unit"); ?></span>
     </div>
 </div>
   <div class="col l4 m5 s12 text-right controls">
       <span class="top-0 price_label"><?php echo _lprice($inneritem->effected_price * $inneritem->qty); ?></span>
       <a class="btn-floating btn-small waves-effect waves-light bg-primary btn-qty-minus" href="javascript:deleteFromCart(<?php echo $inneritem->product_id; ?>,1,<?php echo $inneritem->cart_id; ?>)" ><i class="mdi mdi-minus"></i></a>
       <span class="cart_qty_label" id="cart_id_qty_<?php echo $inneritem->cart_id; ?>"><?php echo $inneritem->qty; ?></span>
       <a class="btn-floating btn-small waves-effect waves-light bg-primary btn-qty-plus" href="javascript:addToCart(<?php echo $inneritem->product_id; ?>,1,<?php echo $inneritem->cart_id; ?>)" ><i class="mdi mdi-plus"></i></a>
   </div>
</li>
 <?php } ?>
 </ul>
<?php } ?>
