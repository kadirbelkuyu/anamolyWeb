<script>
function quickEdit(quickid,r_index){
    
    $.ajax({
        url:'<?php echo site_url("admin/products/quickedit/"); ?>'+quickid,
        data:{ id : quickid, r_index : r_index },
        type:"post",
        success:function(res){
            $("#quickEditModal").modal("show");
            $('#quickEditModal').on('shown.bs.modal', function (e) {
                $(".quick-content").html(res);
            });
        }
    });
    


}
function quickGroup(quickid,r_index){
    
    $.ajax({
        url:'<?php echo site_url("admin/products/quickgroup/"); ?>'+quickid,
        data:{ id : quickid, r_index : r_index },
        type:"post",
        success:function(res){
            $("#quickGroupModal").modal("show");
            $('#quickGroupModal').on('shown.bs.modal', function (e) {
                $(".quick-group").html(res);
            });
        }
    });
    


}
function updateCellGroup(row){
    row = row -1;
    var group = "";
        $('#groups_list > tr').each(function(index, tr) { 
            var td = tr.cells[0];
                if(group != ""){
                    group = group +",";
                }
                group = group + td.innerHTML;
                
            });
            datatable2.cell( row, 3 ).data( group );
    }
var datatable2;
//reload_datatable("","","","","","");
reloadNow();
function reloadNow(){
    reload_datatable($("#category_id").val(),$("#sub_category_id").val(),$("#product_group_id").val(),$("#f_status").val(),$("#f_express").val(),$("#f_nutritional").val());

}
function reload_datatable(category_id,sub_category_id,product_group_id,f_status,f_express,f_nutritional)
{
	
	$("#productList").dataTable().fnDestroy();
	datatable2 = $('#productList').DataTable({
		serverSide: true,
        "aaSorting": [],
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
		"columns": [
            { "orderable": false },
            null,
            null,
            null,
            { "orderable": false },
            { "orderable": false },
            null,
            null,
            null,
            null,
            null,
            null,
            { "orderable": false },
            { "orderable": false },
        ],
		ajax: {
			url: '<?php echo site_url('admin/products/ajax_list'); ?>',
			type: 'POST',
			data: { product_group_id : product_group_id, sub_category_id : sub_category_id, category_id : category_id,f_status : f_status,f_express : f_express,f_nutritional : f_nutritional}
		}
	});
}

$(document).ready(function(){
    $("#filterProducts").click(function(e) {
        reload_datatable($("#category_id").val(),$("#sub_category_id").val(),$("#product_group_id").val(),$("#f_status").val(),$("#f_express").val(),$("#f_nutritional").val());
    });
    $('#selectAll').change(function() {
        $(".del").prop( "checked", this.checked );
        $("#btnDeleteAll").addClass("hide"); 
        $( ".del" ).each(function( index ) {
            if ($(this).prop('checked')==true){ 
                $("#btnDeleteAll").removeClass("hide");
            }
        });     
    });
    $('body').on("change",'.del',function() {
        $("#btnDeleteAll").addClass("hide"); 
        $( ".del" ).each(function( index ) {
            if ($(this).prop('checked')==true){ 
                $("#btnDeleteAll").removeClass("hide");
            }
        });     
    });
    $("#btnDeleteAll").click(function(){
        
        var ids = "";
        $( ".del" ).each(function( index ) {
            if ($(this).prop('checked')==true){ 
                if(ids != ""){
                    ids = ids + ",";
                }
                ids = ids + $(this).data("ref");
            }
        });
        if(ids != ""){
            var r = confirm("<?php echo _l("Are you sure want to delete?") ?>");
            if (r == true) {
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url("admin/products/multipledelete"); ?>',
                    data: { ids : ids }, // serializes the form's elements. form.serialize()
                    success: function(data)
                    {   
                        $("#btnDeleteAll").addClass("hide"); 
                        reloadNow();
                    }
                });
            }
        }
    });
});
function changeStatus(status,prodid,r_index){
    $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/products/change_status"); ?>',
            data: { status : status, prod_id : prodid }, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.responce){
                    var prod = data.data;
                    
                    var row = r_index ;
                    
                    if(prod.status == 1){
                        datatable2.cell( row, 9 ).data( '<a href="javascript:changeStatus(\''+0+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-success"><?php echo _l("Enable"); ?></span></a>' );
                    }else{
                        datatable2.cell( row, 9 ).data( '<a href="javascript:changeStatus(\''+1+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-danger"><?php echo _l("Disable"); ?></span></a>' );
                    }
                }
            }
            });
}
function changeExpress(status,prodid,r_index){
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/products/change_express"); ?>',
            data: { status : status, prod_id : prodid }, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.responce){
                    var prod = data.data;
                    
                    var row = r_index ;
                    
                    if(prod.is_express == 1){
                        datatable2.cell( row, 8 ).data( '<a href="javascript:changeExpress(\''+0+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-success"><?php echo _l("Yes"); ?></span></a>' );
                    }else{
                        datatable2.cell( row, 8 ).data( '<a href="javascript:changeExpress(\''+1+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-default"><?php echo _l("No"); ?></span></a>' );
                    }
                }
            }
        });
}
function deleteProductRecord(url,record)
	  {
	   if(confirm('<?php echo _l("Are you sure to delete..?"); ?>'))
       {
        	$.ajax({
				url:url,
				type:"get",
			}).done(function( data ) {
					$("#productList tbody").find("tr:eq("+(record-1)+")").remove();
			});
       }		
}
$(document).ready(function(){
    $("body").on("click",".productImage",function(){
        var prodid = $(this).data("ref");
        $("#imagePreviewModal").modal("toggle");
        $('#imagePreviewModal').on('shown.bs.modal', function () {
            
        });
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/products/imagepreview"); ?>',
            data: { prod_id : prodid }, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                $(".image-previews").html(data);
            }
        });
    });
    
    
  $("#form_add_stock").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url =  form.attr('action');
        var frmData = form.serializeArray();
     
        $.ajax({
            type: "POST",
            url: url,
            data: frmData, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.response){
                    var prod = data.prod[0];
                    
                    var row = $("#rp_index").val() - 1;
                   
                    datatable2.cell( row, 11 ).data( prod.finalstock );

                    $("#productStockModal").modal("hide");
                }
            }
            });
        });    
});

function addStock(productid,r_index){
    $("#pid").val(productid);
    $("#rp_index").val(r_index);
    $("#qty").val(1);
    $("#stockdate").val("");
    $("#productStockModal").modal("show");
}

</script>