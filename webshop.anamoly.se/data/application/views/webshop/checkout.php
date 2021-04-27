<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("Checkout"); ?></h5>

            </div>

              <ul class="collection invoice-item">

             <li class="collection-item list">
              <div class="item-det">
                  <span class="title"><?php echo _l("Address"); ?></span>
                  <p id="deliveryAddress">
                  <?php
                  if(!empty($users->addresses))
                  {
                     echo $users->addresses[0]->postal_code.", ".$users->addresses[0]->house_no.", ".$users->addresses[0]->add_on_house_no.", ".$users->addresses[0]->city.", ".$users->addresses[0]->street_name;
                  }
                  ?>
                  </p>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><a href="#modalAddress" class="waves-effect waves-light modal-trigger"><i class="mdi mdi-square-edit-outline"></i> </a> </h6>
                </div>
            </li>

           <li class="collection-item list">
             <a href="#modalCoupon" class="waves-effect fullWidth waves-light modal-trigger">
              <div class="item-det">
                  <span class="title"><?php echo _l("Do you have coupon?"); ?></span>
                <p id="applyCoupon"></p>
              </div>
              <div class="secondary-content">
                    <h6 class="top-0"><i class="mdi mdi-chevron-right"></i>  </h6>
                </div>
</a>
            </li>


        </ul>
          <ul class="collection invoice-item">
            <?php if($carts->cart_total != $carts->net_paid_amount){ ?>
            <li class="collection-item list">
             <div class="item-det">

                 <div class="pull-left primary-text"><?php echo _l("Total excl. VAT"); ?></div>
                   <div class="pull-right"><?php echo _lprice($carts->cart_total); ?></div>
<div class="clearfix"></div>
             </div>

           </li>
           <?php } ?>
           <?php
                 if(!empty($carts->vat_prices))
                 {
                   foreach($carts->vat_prices as $item){ ?>

                        <li class="collection-item list">
             <div class="item-det">

                 <div class="pull-left primary-text"><?php echo _lvat($item,"vat_title"); ?></div>
                   <div class="pull-right"><?php echo _lprice($item->amount); ?></div>
<div class="clearfix"></div>
             </div>

           </li>

                   <?php } }?>

            <li id="discount" class="collection-item list" style="<?php if(isset($coupon) && isset($coupon->deduct_amount) > 0 ) { }else{ echo "display: none;"; } ?>">
             <div class="item-det">

                 <div class="pull-left primary-text"><span id="discountlabel"><?php echo (isset($coupon) && isset($coupon->discountlabel)) ? $coupon->discountlabel : ""; ?></span> &nbsp;&nbsp;<i class="mdi mdi-delete red" onclick="deleteCouponCode();"></i> </div>
                   <div class="pull-right" id="discount_amount"><?php echo (isset($coupon) && isset($coupon->deduct_amount)) ? $coupon->deduct_amount : ""; ?></div>
                   <div class="clearfix"></div>
             </div>

           </li>

            <li class="collection-item list">
             <div class="item-det">

                 <div class="pull-left primary-text"><?php echo _l("Total incl. VAT"); ?></div>
                <div class="pull-right" id="paid_amount" paidamount="<?php echo _lprice($carts->net_paid_amount); ?>"><?php echo _lprice($carts->net_paid_amount); ?></div>
                <div class="clearfix"></div>
             </div>

           </li>
          </ul>
          <form action="" method="post">
               <input type="hidden" id="delivery_type" name="delivery_type" value="<?php echo $post["delivery_type"]; ?>" />
               <input type="hidden" id="delivery_date" name="delivery_date" value="<?php echo $post["delivery_date"]; ?>" />
               <input type="hidden" id="delivery_time" name="delivery_time" value="<?php echo $post["delivery_time"]; ?>" />
               <input type="hidden" id="postal_code_field" name="postal_code" value="<?php echo (isset($post["postal_code"])) ? $post["postal_code"] : $users->addresses[0]->postal_code ; ?>" />
               <input type="hidden" id="house_no_field" name="house_no" value="<?php echo (isset($post["house_no"])) ? $post["house_no"] : $users->addresses[0]->house_no ; ?>" />
               <input type="hidden" id="add_on_house_no_field" name="add_on_house_no" value="<?php echo (isset($post["add_on_house_no"])) ? $post["add_on_house_no"] : $users->addresses[0]->add_on_house_no ; ?>" />
               <input type="hidden" id="city_field" name="city" value="<?php echo (isset($post["city"])) ? $post["city"] : $users->addresses[0]->city ; ?>" />
               <input type="hidden" id="street_name_field" name="street_name" value="<?php echo (isset($post["street_name"])) ? $post["street_name"] : $users->addresses[0]->street_name ; ?>" />
               <input type="hidden" id="is_express" name="is_express" value="<?php echo $carts->is_express; ?>" />
               <input type="hidden" id="coupon_code_field" name="coupon_code" value="<?php echo (isset($coupon) && isset($post["coupon_code"])) ? $post["coupon_code"] : ""; ?>" />
               <input type="hidden" id="order_note" name="order_note" value="<?php echo (isset($post["order_note"])) ? $post["order_note"] : ""; ?>" />

               <div class="card-panel invoice-to no-shadow">

                          <h5 class="top-0"><?php echo _l("How would you pay?"); ?></h5>
                          <?php
                          $allow_ideal = $this->session->userdata("enable_ideal_payment");
                          $allow_cod = $this->session->userdata("enable_code_payment");

                          if($allow_ideal != NULL && $allow_ideal == "yes"){
                          ?>
                          <div class="row">
                            <label>
                              <input name="paid_by" value="ideal" type="radio" <?php if($allow_ideal = "yes"){ echo "checked"; } ?> />
                              <span><img src="<?php echo base_url("themes/webshop/images/ideal.png"); ?>" style="height:25px;" /> <?php echo _l("IDEAL"); ?></span>
                            </label>
                          </div>
                          <?php } ?>
                          <?php

                          if($allow_cod != NULL && $allow_cod == "yes"){
                          ?>
                          <div class="row">
                            <label>
                                <input name="paid_by" value="cod" type="radio" <?php if($allow_ideal = "no" && $allow_cod == "yes"){ echo "checked"; } ?> />
                                <span><img src="<?php echo base_url("themes/webshop/images/cash_on_delivery.png"); ?>" style="height:25px;" /> <?php echo _l("CASH ON DELIVERY"); ?></span>
                            </label>
                          </div>
                          <?php } ?>
                      <!--
                      <div class="row">
                        <label>
                            <input name="paid_by" value="oncredit" type="radio" />
                            <span><img src="<?php echo base_url("themes/webshop/images/ic_credit.png"); ?>" style="height:25px;" /> <?php echo _l("ON CREDIT"); ?></span>
                        </label>
                      </div>
                      -->
                </div>

          <div class="text-center">
          <button type="submit" class="waves-effect waves-green btn-flat btn btn-primary"><?php echo _l("Continue"); ?></button>

          </div>
        </form>
        </div>

    </div>
</div>


<div id="modalAddress" class="modal modal-fixed-footer">

        <div class="modal-content">
           <h4><?php echo _l("Delivery Address"); ?></h4>
           <?php

           echo _input_field("postal_code",_l("Postal Code")."<span class='text-danger'>*</span>", _get_post_back($users->addresses[0],'postal_code'), 'text', array("data-validation" =>"required","maxlength"=>50,"placeholder"=>_l("Postal Code")),array(),"s12");
           echo _input_field("house_no",_l("House No")."<span class='text-danger'>*</span>", _get_post_back($users->addresses[0],'house_no'), 'text', array("data-validation" =>"required","maxlength"=>50,"placeholder"=>_l("House No")),array(),"s12");
           echo _input_field("add_on_house_no",_l("House Ad On"), _get_post_back($users->addresses[0],'add_on_house_no'), 'text', array("maxlength"=>50,"placeholder"=>_l("House Ad On")),array(),"s12");
           echo _input_field("city",_l("City"), _get_post_back($users->addresses[0],'city'), 'text', array("maxlength"=>100,"placeholder"=>_l("City")),array(),"s12");
           echo _input_field("street_name",_l("Street"), _get_post_back($users->addresses[0],'street_name'), 'text', array("maxlength"=>100,"placeholder"=>_l("Street"),"readonly"=>"readonly"),array(),"s12");

          ?>

        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="saveAddress();"><?php echo _l("Change Address"); ?></button>
        </div>

 </div>


 <div id="modalCoupon" class="modal">

        <div class="modal-content">
           <h4><?php echo _l("Coupon Code")?></h4>
           <?php

           echo _input_field("coupon_code","", "", 'text', array("maxlength"=>50,"placeholder"=>_l("Type your Coupon Code here")),array(),"s12");

          ?>

        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="applyCoupon();"><?php echo _l("Verify and Apply"); ?></button>
        </div>

 </div>

 <script>

  function saveAddress(){

    var postal_code=$("#postal_code").val();
    var house_no=$("#house_no").val();
    var add_on_house_no=$("#add_on_house_no").val();
    var street_name=$("#street_name").val();
    var city=$("#city").val();
	$.ajax({
    method: "POST",
    url: "<?php echo site_url("cart/validate_address"); ?>",
    data: { postal_code: postal_code,house_no:house_no }
	}).done(function( data ) {
      if(data.responce){

        $("#city_field").val(city);
        $("#street_name_field").val(street_name);
        $("#postal_code_field").val(data.data.postal_code);
        $("#house_no_field").val(data.data.house_no);
        $("#add_on_house_no_field").val(add_on_house_no);

        var deliveryAddress=$("#postal_code").val()+", "+$("#house_no").val()+", "+$("#add_on_house_no").val()+", "+$("#city").val()+", "+$("#street_name").val();
        $("#deliveryAddress").html(deliveryAddress);

        $("#modalAddress").modal("close");
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

function applyCoupon(){

if($("#coupon_code").val()=="")
{
    toastScript("Please enter coupon code!","error");
}
else
{
   var coupon_code=$("#coupon_code").val();

	$.ajax({
    method: "POST",
    url: "<?php echo site_url("cart/validate_couponcode"); ?>",
    data: { coupon_code: coupon_code }
	}).done(function( data ) {
      if(data.responce){
       $("#coupon_code_field").val(coupon_code);
       $("#applyCoupon").html(coupon_code);
       $("#discountlabel").html(data.data.discountlabel);
       $("#discount_amount").html(data.data.deduct_amount);
       $("#paid_amount").html(data.data.paid_amount);

        $("#discount").show();
        $("#modalCoupon").modal("close");
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

}

function deleteCouponCode()
{
    $("#coupon_code").val("");
    $("#applyCoupon").html("");
    $("#discountlabel").html("");
    $("#discount_amount").html("");
    $("#paid_amount").html($("#paid_amount").attr("paidamount"));

    $("#discount").hide();
}
 </script>
