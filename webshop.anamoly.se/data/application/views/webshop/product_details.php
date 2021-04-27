
<div class="container product-details">
  <div class="section pad-top-0">
    <div class="product_image" id="image_<?php echo $data->product_id; ?>" style="background-image: url('<?php echo _limage($data->product_image,PRODUCT_IMAGE_PATH); ?>')">

      <?php
        if($data->offer_type != NULL && $data->offer_type != ""){
            $offer_text = "";
            if($data->offer_type == "plusone"){
                $offer_text = $data->number_of_products." + 1 gratis";
            }else if($data->offer_type == "flatcombo"){
                $offer_text = $data->number_of_products." voor "._currency_symbol()." ".$data->offer_discount;
            }
            if($offer_text != ""){
                echo "<span class='offer-span-tag'>$offer_text</span>";
            }
        }
      ?>
    </div>

    <h5 class="title"><?php echo  _lname($data,"product_name"); ?></h5>
    <small class="note"><?php echo $data->qty." "._lunit($data,"unit"); ?></small>
    <div class="price">
        <?php
        $amount = $data->price;
        if($data->discount > 0){
            echo "<span style='text-decoration: line-through; font-weight:normal; color: #ccc; margin-right:10px;'>"._lprice($data->price)."</span>";
            if($data->discount_type == "flat"){
                $amount = $amount - $data->discount;
            }else if($data->discount_type == "percentage"){
                $amount = $amount - ( $data->discount * $amount / 100 );
            }
        }
        echo _lprice($amount);?>
    </div>
    <div class="ingredients_tag">
      <?php
      foreach ($data->ingredients as $key => $value) { ?>
        <span class="ingredients" style="background-color:<?php echo '#'.$value->ingredient_colour; ?>"><?php echo _lname($value,"ingredient_name");?></span>
      <?php
      } ?>
   </div>
    <div class="description">
      <?php echo str_replace(array("<html>","<head>","<body>","</html>","</head>","</body>"),"", _lname($data,"product_desc")); ?>
    </div>
    <div class='floating-add-controls'>
        <a class="btn-floating btn waves-effect waves-light  add_cart_qty" onclick="addToCart('<?php echo $data->product_id; ?>','1','0')" data-product_id="<?php echo $data->product_id; ?>"><i class="mdi mdi-plus"></i></a>
          <span class="cart_qty" id="<?php echo 'product_qty_'.$data->product_id; ?>"><?php if($data->cart_qty > 0){ echo $data->cart_qty; } ?></span>
        <span class="minus_cart_qty <?php if($data->cart_qty <= 0){ echo 'hide_me'; } ?>" id="minus_cart_qty_<?php echo $data->product_id; ?>"><a class="btn-floating btn waves-effect waves-light " onclick="deleteFromCart('<?php echo $data->product_id; ?>','1','0')" data-product_id="<?php echo $data->product_id; ?>"><i class="mdi mdi-minus"></i></a></span>

    </div>
  </div>
</div>
