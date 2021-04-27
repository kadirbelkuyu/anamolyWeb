<script>
function reOrderClick(){

if($("#order_id").val()=="")
{
    toastScript("Order referance required","error");
}
else
{
   var order_id=$("#order_id").val();

	$.ajax({
    method: "POST",
    url: "<?php echo site_url("order/reorder"); ?>",
    data: { order_id: order_id }
	}).done(function( data ) {
      if(data.responce){
          $("#modalReOrder").modal("close");
            window.location.replace("<?php echo site_url("cart/viewcart"); ?>");
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

}
</script>
