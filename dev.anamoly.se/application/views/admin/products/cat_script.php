<script>
    $(function(){
        $('.select2').select2();
        $("body .filter_group").on("change",".category_id",function(){
            var category_id = $(this).val();
           $.ajax({
                url:'<?php echo site_url("admin/subcategories/get_sub_category_by_category_id"); ?>',
                data:{category_id:category_id},
                type:"post",
                success:function(res){
                    res=jQuery.parseJSON(res);
                    $(".sub_category_id").empty();
                    $(".sub_category_id").append("<option value=''>Choose Sub Category</option>");
                    if(res.length>0)
                    {
                        $.each(res,function(ind,val){
                            $(".sub_category_id").append("<option value='"+val.sub_category_id+"'>"+val.sub_cat_name_nl+"</option>");	
                        });

                    }
                }
            }); 
        });
    });
    /*
    $(function(){
        $('#quickGroupModal').on('hidden.bs.modal', function (e) {
            var group = "";
            $('#groups_list > tr > td:eq(0)').each(function(index, td) { 
                if(group != ""){
                    group = group +",";
                }
                group = group + td.html();
                datatable.cell( row, 2 ).data( group );
            });
        }).
    });
    */
    
</script>
<script>
    $(function(){
        $("body .filter_group").on("change",".sub_category_id",function(){
            var sub_category_id = $(this).val();
           $.ajax({
                url:'<?php echo site_url("admin/productgroups/get_by_sub_category_id"); ?>',
                data:{sub_category_id:sub_category_id},
                type:"post",
                success:function(res){
                    res=jQuery.parseJSON(res);
                    $(".product_group_id").empty();
                    $(".product_group_id").append("<option value=''>Choose Group</option>");
                    if(res.length>0)
                    {
                        $.each(res,function(ind,val){
                            $(".product_group_id").append("<option value='"+val.product_group_id+"'>"+val.group_name_nl+"</option>");	
                        });

                    }
                }
            }); 
        });
        
        $("#add_product_groups").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url =  form.attr('action');
            var frmData = form.serializeArray();
            var count = $("#groups_list tr").length + 1;
            frmData.push( {name: 'count' , value: count });
            
            $.ajax({
                   type: "POST",
                   url: url,
                   data: frmData, // serializes the form's elements. form.serialize()
                   success: function(data)
                   {
                       $("#groups_list").prepend(data);
                       $(".category_id").val('').trigger('change');
                       $(".sub_category_id").html("");
                       $(".product_group_id").html("");
                       updateCellGroup($("#r_index").val());
                   }
                 });


        });
    });
    function deleteTableRecord(url,table,record)
	  {
	   if(confirm('<?php echo _l("Are you sure to delete..?"); ?>'))
       {
        	$.ajax({
				url:url,
				type:"get",
			}).done(function( data ) {
					$("#groups_list .row_"+record).remove();
                    updateCellGroup($("#r_index").val());
			});
       }		
	  }
</script>