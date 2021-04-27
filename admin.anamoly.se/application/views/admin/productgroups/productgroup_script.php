<script>
function changeStatus(status,prodid,r_index){
    $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/productgroups/change_status"); ?>',
            data: { status : status, prod_id : prodid }, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.responce){
                    var prod = data.data;
                    var row = r_index ;
                    if(prod.status == 1){
                        datatable.cell( row, 3 ).data( '<a href="javascript:changeStatus(\''+0+'\',\''+prod.product_group_id+'\',\''+row+'\')" id="ref_'+prod.product_group_id+'"><span class="label label-success"><?php echo _l("Enable"); ?></span></a>' );
                    }else{
                        datatable.cell( row, 3 ).data( '<a href="javascript:changeStatus(\''+1+'\',\''+prod.product_group_id+'\',\''+row+'\')" id="ref_'+prod.product_group_id+'"><span class="label label-danger"><?php echo _l("Disable"); ?></span></a>' );
                    }
                }
            }
    });
}
</script>