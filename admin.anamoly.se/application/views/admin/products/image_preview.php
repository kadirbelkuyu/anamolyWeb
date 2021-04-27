<div class="row">
    <div class="col-md-4 ">
        <span>Product Grid:</span>
        <div class="thumbnail" style="width: 200px; height: 120px; background-image: url('<?php echo base_url(PRODUCT_IMAGE_PATH."/".$product->product_image); ?>'); background-repeat:no-repeat; background-position:center; background-size:cover;" ></div>
        <strong><?php echo $product->product_name_nl; ?></strong>
    </div>
    <div class="col-md-8 ">
        <span>Single Product:</span>
        <div class="thumbnail" style="width: 360px; height: 210px; background-image: url('<?php echo base_url(PRODUCT_IMAGE_PATH."/".$product->product_image); ?>'); background-repeat:no-repeat; background-position:center; background-size:cover;" ></div>
        <h3><?php echo $product->product_name_nl; ?></h3>
    </div>
</div>
