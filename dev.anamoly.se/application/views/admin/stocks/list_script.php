<script>

function fillProduct(){
     $.ajax({
                url:'<?php echo site_url("admin/stocks/get_product_by_category_sub_category_id"); ?>',
                data:{category_id:$("#category_id").val(),sub_category_id:$("#sub_category_id").val()},
                type:"post",
                success:function(res){
                    res=jQuery.parseJSON(res);
                    $("#product_id").empty();
                    $("#product_id").append("<option value=''>All</option>");
                    if(res.length>0)
                    {
                        $.each(res,function(ind,val){
                            $("#product_id").append("<option value='"+val.product_id+"'>"+val.product_name_nl+"</option>");	
                        });

                    }
                }
            }); 

}

function deleteProductStockRecord(url,record)
	  {
	   if(confirm('<?php echo _l("Are you sure to delete..?"); ?>'))
       {
        	$.ajax({
				url:url,
				type:"get",
			}).done(function( data ) {
					$("#productStockList tbody").find("tr:eq("+(record-1)+")").remove();
			});
       }		
}

var datatable2;
//reload_datatable("","","","","","");
reloadNow();
function reloadNow(){
    reload_datatable($("#category_id").val(),$("#sub_category_id").val(),$("#product_id").val());
}

function reload_datatable(category_id,sub_category_id,product_id)
{
	
	$("#productStockList").dataTable().fnDestroy();
	datatable2 = $('#productStockList').DataTable({
		serverSide: true,
        "aaSorting": [],       
		"columns": [            
            null,
            null,
            null,           
            { "orderable": false },
        ],
		ajax: {
			url: '<?php echo site_url('admin/stocks/ajax_list'); ?>',
			type: 'POST',
			data: {category_id : category_id, sub_category_id : sub_category_id,product_id : product_id  }
		}
	});
}

$(document).ready(function(){
     $("#filterProductStocks").click(function(e) {
        reload_datatable($("#category_id").val(),$("#sub_category_id").val(),$("#product_id").val());
    });
});
    
$(function(){
   
$("body").on("change","#category_id",function(){
    fillProduct();
});

$("body").on("change","#sub_category_id",function(){
    fillProduct();
});

});

</script>