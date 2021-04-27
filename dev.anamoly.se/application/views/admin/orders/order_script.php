<script>
    function assignOrder(id,record){
        $("#assign_order_id").val(id);
        $("#row_index").val(record-1);
        $("#assignDeliveryBoyModal").modal("toggle");

        $("#form_assign_delivery_boy").submit(function(e) {

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
                    
                    
                    var row = $("#row_index").val();
                    datatable.cell( row, 8 ).data( data.data );

                    
                    $("#assignDeliveryBoyModal").modal("hide");
                }
            }
            });


        });
    }
    function updateStatus(url,record,status){
       
            $.ajax({
                url:url,
                data:{status:status,row_index:record},
                type:"post",
                success:function(res){
                    if(res.response){
                        datatable.cell( (record - 1), 8 ).data( res.data );
                        $("#messages").prepend(res.message);
                    
                    }
                    
                }
            });
        
    }
    $(document).ready(function(){
       
        
    });
</script>