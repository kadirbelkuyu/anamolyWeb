<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.6/css/rowReorder.dataTables.min.css">

<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.6/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript">
var datatable2;
$(function () {
    $(document).ready(function() {
        var table = $('#example1').DataTable( {
            rowReorder: {
                selector: 'a.moved'
            }
        } );
        datatable2 = table;
        table.on( 'row-reorder', function ( e, diff, edit ) {

            /*
            var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';
            
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                var rowData = table.row( diff[i].node ).data();
    
                result += rowData[1]+' updated to be in position '+
                    diff[i].newData+' (was '+diff[i].oldData+')<br>';
            }
    
            alert(result);
            */
            var update_data = [];
            $('#example1 > tbody  > tr').each(function(index, tr) { 
                console.log(index+" - "+tr.getAttribute("data-ref"));
                var data = { s_index : index , ref : tr.getAttribute("data-ref") };
                update_data.push(data);
            });
            console.log(update_data);

            $.ajax({
                url: '<?php echo site_url("admin/categories/updateOrder"); ?>',
                type: 'post',
                
                success: function (data) {
                    console.log(data);
                },
                data: { data : JSON.stringify(update_data) }
            });

        } );
    } );
});
function changeStatus(status,prodid,r_index){
    $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/categories/change_status"); ?>',
            data: { status : status, prod_id : prodid }, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.responce){
                    var prod = data.data;
                    
                    var row = r_index ;
                    
                    if(prod.status == 1){
                        datatable2.cell( row, 3 ).data( '<a href="javascript:changeStatus(\''+0+'\',\''+prod.category_id+'\',\''+row+'\')" id="ref_'+prod.category_id+'"><span class="label label-success"><?php echo _l("Enable"); ?></span></a>' );
                    }else{
                        datatable2.cell( row, 3 ).data( '<a href="javascript:changeStatus(\''+1+'\',\''+prod.category_id+'\',\''+row+'\')" id="ref_'+prod.category_id+'"><span class="label label-danger"><?php echo _l("Disable"); ?></span></a>' );
                    }
                }
            }
            });
}
</script>