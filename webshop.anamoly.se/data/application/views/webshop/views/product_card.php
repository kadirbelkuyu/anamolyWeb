<div class="col s6 m4 l3 ">
    <div class="z-depth-1 card product-card ">
        <a href="<?php echo site_url("products/details/".$val->product_id); ?>">
        <div class="card-image" id="image_<?php echo $val->product_id; ?>" style="background-image : url('<?php echo _limage($val->product_image,PRODUCT_IMAGE_PATH); ?>')">
          <?php
            if($val->offer_type != NULL && $val->offer_type != ""){
                $offer_text = "";
                if($val->offer_type == "plusone"){
                    $offer_text = $val->number_of_products." + 1 gratis";
                }else if($val->offer_type == "flatcombo"){
                    $offer_text = $val->number_of_products." voor "._currency_symbol()." ".$val->offer_discount;
                }
                if($offer_text != ""){
                    echo "<span class='offer-span-tag'>$offer_text</span>";
                }
            }
          ?>
        </div>
        </a>
        <div class="card-content">
            <p class="product-title"><?php echo _lname($val,"product_name"); ?></p>
            <small class="note"><?php echo $val->qty." "._lunit($val,"unit"); ?></small><br/>

            <div class="price pull-right">
                <?php
                $amount = $val->price;
                if($val->discount > 0){
                    echo "<span style='text-decoration: line-through; font-weight:normal; color: #ccc; margin-right:10px;'>"._lprice($val->price)."</span>";
                    if($val->discount_type == "flat"){
                        $amount = $amount - $val->discount;
                    }else if($val->discount_type == "percentage"){
                        $amount = $amount - ( $val->discount * $amount / 100 );
                    }
                }
                echo _lprice($amount);
                ?>
            </div>
            <div class="pull-left add-cart-button">
              <a class="btn-floating btn btn-small waves-effect waves-light add_cart_qty" onclick="addToCart('<?php echo $val->product_id; ?>','1','0')" data-product_id="<?php echo $val->product_id; ?>"><i class="mdi mdi-plus"></i></a>
              <span class="cart_qty" id="<?php echo "product_qty_".$val->product_id; ?>"><?php if($val->cart_qty > 0){ echo $val->cart_qty; } ?></span>
              <span class="minus_cart_qty <?php if($val->cart_qty <= 0){ echo 'hide_me'; } ?>" id="minus_cart_qty_<?php echo $val->product_id; ?>"><a class="btn-floating btn btn-small waves-effect waves-light " onclick="deleteFromCart('<?php echo $val->product_id; ?>','1','0')" data-product_id="<?php echo $val->product_id; ?>"><i class="mdi mdi-minus"></i></a></span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
