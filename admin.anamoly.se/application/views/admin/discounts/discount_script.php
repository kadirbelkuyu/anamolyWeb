<script>
        $("#discount_type").change(function(){
            var val = $(this).val();
            if(val == "plusone" || val == "flatcombo"){
                //$("label[for='discount']").html("X+1 Offer");
                if(val == "plusone"){
                    $("label[for='discount']").html("Discount <span class='text-danger'>*</span>");
                    $("label[for='number_of_products']").html("X+1 Offer");
                    $("#number_of_products_note").html("Ger 1 Item free on this offer");
                }else if(val == "flatcombo"){
                    $("label[for='discount']").html("Flat Price <span class='text-danger'>*</span>");
                    $("label[for='number_of_products']").html("X Numbers");
                    $("#number_of_products_note").html("Apply Flat price on X number of products");
                }
                $("div[app-field-wrapper='number_of_products']").removeClass("hide");
            }else{
                $("#number_of_products").val("");
                $("div[app-field-wrapper='number_of_products']").addClass("hide");
            }
        });
        $("#number_of_products").on("change paste keyup", function() {
            var val = $("#discount_type").val();
            if(val == "plusone"){
                var no_of_products = $(this).val();
                var discount = 0;
                if(no_of_products > 0){
                    discount = 100 / no_of_products;
                }
                $("#discount").val(discount.toFixed(2));
            } 
        });
        function changeStatus(status,prodid,r_index){
    $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/discounts/change_status"); ?>',
            data: { status : status, prod_id : prodid }, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.responce){
                    var prod = data.data;
                    
                    var row = r_index ;
                    
                    if(prod.status == 1){
                        datatable.cell( row, 4 ).data( '<a href="javascript:changeStatus(\''+0+'\',\''+prod.product_discount_id+'\',\''+row+'\')" id="ref_'+prod.product_discount_id+'"><span class="label label-success"><?php echo _l("Published"); ?></span></a>' );
                    }else{
                        datatable.cell( row, 4 ).data( '<a href="javascript:changeStatus(\''+1+'\',\''+prod.product_discount_id+'\',\''+row+'\')" id="ref_'+prod.product_discount_id+'"><span class="label label-default"><?php echo _l("Pending"); ?></span></a>' );
                    }
                }
            }
            });
}
</script>