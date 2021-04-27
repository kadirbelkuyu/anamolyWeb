<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("Cart"); ?></h5>

            </div>

            <div id="cart_item_lists">
              <?php $this->load->view("webshop/views/cart_items_lists"); ?>
            </div>



         <div class="card-panel no-shadow bg-primary invoice-to">

                    <h5 class="red-text top-0"><div class="pull-left"><?php echo _l("Discount"); ?></div> <div style="float: right;" id="cart_discount"><?php echo _lprice(($carts->discount)); ?></div>
                    <div class="clearfix"></div>
                    </h5>
                    <h5 class="top-0"><div class="pull-left"><?php echo _l("Total Amount"); ?></div> <div style="float: right;" class="lbl_cart_total"><?php echo _lprice($carts->cart_total); ?></div>
                    <div class="clearfix"></div>
                    </h5>
                  </div>
        <?php
        if(!empty($timeslots) || !empty($pickup_timeslots)){

          ?>
            <?php if(!empty($timeslots)){
              $delivery_date = $timeslots[0]->date;
              $delivery_time = $timeslots[0]->from_time."-".$timeslots[0]->to_time;
              $delivery_type = "delivery";
              ?>
        <a href="javascript:;"  onclick="openDeliveryTimeModal();">
            <div class="delivery_card btn_delivery active">
                <div class="pull-left"><i class="mdi mdi-check"></i><?php echo _l("Delivery"); ?></div>
                <div class="pull-right"><span id="lnkDeliveryTime"><?php echo date('d M',strtotime($timeslots[0]->date)).", ".date('H:i',strtotime($timeslots[0]->from_time))." - ".date('H:i',strtotime($timeslots[0]->to_time)); ?></span><i class="mdi mdi-chevron-right"></i></div>
            </div>
            <div class="clearfix"></div>
        </a>
            <?php } ?>
            <?php if(!empty($pickup_timeslots)){?>
        <a href="javascript:;"  onclick="openPickupTimeModal();">
            <div class="delivery_card btn_pickup">
                <div class="pull-left"><i class="mdi mdi-check"></i><?php echo _l("Pickup"); ?></div>
                <div class="pull-right"><span id="lnkPickupTime"><?php echo date('d M',strtotime($pickup_timeslots[0]->date)).", ".date('H:i',strtotime($pickup_timeslots[0]->from_time))." - ".date('H:i',strtotime($pickup_timeslots[0]->to_time)); ?></span><i class="mdi mdi-chevron-right"></i></div>
            </div>
            <div class="clearfix"></div>
        </a>
            <?php } ?>
        <div class="text-center">
          <form action="<?php echo site_url("cart/checkout") ?>" method="post">
            <input type="hidden" name="delivery_type" required value="<?php echo $delivery_type; ?>" id="delivery_type" />
            <input type="hidden" name="delivery_date" required value="<?php echo $delivery_date; ?>" id="delivery_date" />
            <input type="hidden" name="delivery_time" required value="<?php echo $delivery_time; ?>" id="delivery_time" />
            <button type="submit" class="waves-effect waves-green btn-flat btn btn-primary"><?php echo _l("Continue"); ?></button>
          </form>
        </div>
        <?php } ?>
        </div>

    </div>
</div>


 <div id="modalDeliveryTime" class="modal modal-fixed-footer">
        <div class="modal-content">
           <h4><?php echo _l("Preferred Delivery Time"); ?></h4>
           <div class="row" style="margin-bottom: 0px;">
           <div class="s12 col">
            <?php
            foreach($timeslots as $item){ ?>
               <div class="divDateTime" data-date="<?php echo $item->date; ?>" data-time="<?php echo $item->from_time."-".$item->to_time; ?>" onclick="selectTime(this)" date="<?php echo date('d M',strtotime($item->date)).", ".date('H:i',strtotime($item->from_time))." - ".date('H:i',strtotime($item->to_time)); ?>">

                <div class="s6 col">
               <?php echo _l(date('l',strtotime($item->date))); ?><br />
               <?php echo date('d',strtotime($item->date))." "._l(date('M',strtotime($item->date))); ?>
               </div>
                <div class="s6 col">
             <?php echo date('H:i',strtotime($item->from_time))." - ".date('H:i',strtotime($item->to_time)); ?>
               </div>

               <div class="clearfix"></div>
               </div>

        <?php } ?>
           </div>
           </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="SaveDateTime();"><?php echo _l("Save"); ?></button>
        </div>
</div>

<div id="modalPickupTime" class="modal modal-fixed-footer">
        <div class="modal-content">
           <h4><?php echo _l("Preferred Pickup Time"); ?></h4>
           <div class="row" style="margin-bottom: 0px;">
           <div class="s12 col">
            <?php
            foreach($pickup_timeslots as $item){ ?>
               <div class="divDateTime" data-date="<?php echo $item->date; ?>" data-time="<?php echo $item->from_time."-".$item->to_time; ?>" onclick="selectTime(this)" date="<?php echo date('d M',strtotime($item->date)).", ".date('H:i',strtotime($item->from_time))." - ".date('H:i',strtotime($item->to_time)); ?>">

                <div class="s6 col">
               <?php echo _l(date('l',strtotime($item->date))); ?><br />
               <?php echo date('d',strtotime($item->date))." "._l(date('M',strtotime($item->date))); ?>
               </div>
                <div class="s6 col">
             <?php echo date('H:i',strtotime($item->from_time))." - ".date('H:i',strtotime($item->to_time)); ?>
               </div>

               <div class="clearfix"></div>
               </div>

        <?php } ?>
           </div>
           </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="SavePickupDateTime();"><?php echo _l("Save"); ?></button>
        </div>
 </div>
 <script>

 function openDeliveryTimeModal()
 {
    $("#modalDeliveryTime .divDateTime").removeClass('red');
    $("#modalDeliveryTime .divDateTime").removeClass('selectred');

    $("#modalDeliveryTime div.divDateTime").each(function() {

      if($(this).attr("date")==$("#lnkDeliveryTime").html())
      {
        $(this).addClass('red');
        $(this).addClass('selectred');
      }

    });

     $("#modalDeliveryTime").modal("open");
 }
 function openPickupTimeModal()
 {
    $("#modalPickupTime .divDateTime").removeClass('red');
    $("#modalPickupTime .divDateTime").removeClass('selectred');

    $("#modalPickupTime div.divDateTime").each(function() {

      if($(this).attr("date")==$("#lnkPickupTime").html())
      {
        $(this).addClass('red');
        $(this).addClass('selectred');
      }

    });

     $("#modalPickupTime").modal("open");
 }
 function selectTime(item)
 {
   $(".divDateTime").removeClass('red');
   $(".divDateTime").removeClass('selectred');
   $(item).addClass('red');
   $(item).addClass('selectred');
 }

 function SaveDateTime()
 {
   $("#delivery_date").val($(".selectred").data("date"));
   $("#delivery_time").val($(".selectred").data("time"));
   $("#lnkDeliveryTime").html($(".selectred").attr("date"));
   $("#modalDeliveryTime").modal("close");
   $("#delivery_type").val("delivery");
   $(".btn_delivery").addClass("active");
   $(".btn_pickup").removeClass("active");
 }
 function SavePickupDateTime()
 {
   $("#delivery_date").val($(".selectred").data("date"));
   $("#delivery_time").val($(".selectred").data("time"));
   $("#lnkPickupTime").html($(".selectred").attr("date"));
   $("#modalPickupTime").modal("close");
   $("#delivery_type").val("pickup");
   $(".btn_pickup").addClass("active");
   $(".btn_delivery").removeClass("active");
 }
 </script>
