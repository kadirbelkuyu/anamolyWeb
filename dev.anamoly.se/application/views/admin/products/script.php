<script>
    function copycontent(areaId){
        var txtarea = document.getElementById(areaId);
        if (!txtarea) {
            return;
        }
        CKEDITOR.instances[areaId].insertHtml($(".sample_description").html());
    }
</script>
<div class="sample_description" style="display:none">
    <table width="100%">
        <tr>
            <th></th><th><strong><?php echo _l("per 100 g") ?></strong></th><th><?php echo _l("%RL")?></th>
        </tr>
        <tr><td><?php echo _l("Energie"); ?></td><td>1.5 kj/</td><td>18%</td></tr>
        <tr><td></td><td>396 kcal</td><td>-</td></tr>
        <tr><th><?php echo _l("Vet"); ?></th><td>8,4g</td><td>12%</td></tr>
        <tr><td><?php echo _l("verzadigd"); ?></td><td>1,3g</td><td>6,5%</td></tr>
        <tr><th><?php echo _l("Koolhydraten"); ?></th><td>56g</td><td>21%</td></tr>
        <tr><td><?php echo _l("waarvan suikers"); ?></td><td>1,0g</td><td>1,1%</td></tr>
        <tr><th><?php echo _l("Eiwit"); ?></th><td>12g</td><td>24%</td></tr>
        <tr><th><?php echo _l("Zout"); ?></th><td>0,01g</td><td>0,2%</td></tr>
        <tr><th><?php echo _l("Vezels"); ?></th><td>9,1g</td><td>36%</td></tr>
    </table>
    <p>RI is de referentie-inname van een gemiddelde volwassene (8400 KJ/2000 kcal)</p>
</div>
<script>

function change(){
    var without_vat = $("#price_vat_exclude").val();
    var with_vat = $("#price").val();

    var vat = $("#vat").val();

    if(without_vat != "" && without_vat > 0){
        var vat_amount = without_vat * vat / 100;
        $("#price").val((parseFloat(without_vat) + vat_amount).toFixed(2));
    }else if(with_vat != "" && with_vat > 0){
        var vat_amount = with_vat - (with_vat / (1 + (vat / 100 )));
        $("#price_vat_exclude").val((with_vat - vat_amount).toFixed(2));
    }
    
}

    $(document).ready(function(){
        $("#price_vat_exclude").on("change paste keyup", function() {
            $("#price").val("");
            change();
        });
        $("#price").on("change paste keyup", function() {
            $("#price_vat_exclude").val("");
            change();
        });
        $("#vat").on("change paste keyup", function() {
            change();
        });

        $("#form_quick_edit").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url =  form.attr('action');
        var frmData = form.serializeArray();
        //var count = $("#groups_list tr").length + 1;
        //frmData.push( {name: 'count' , value: count });
          
        $.ajax({
            type: "POST",
            url: url,
            data: frmData, // serializes the form's elements. form.serialize()
            success: function(data)
            {   
                if(data.response){
                    var prod = data.prod[0];
                    
                    var row = $("#r_index").val() - 1;
                    datatable2.cell( row, 1 ).data( prod.product_name_nl );
                    datatable2.cell( row, 2 ).data( prod.product_barcode );
                    datatable2.cell( row, 3 ).data( prod.group_name_nl );
                    datatable2.cell( row, 4 ).data( prod.unit_value+" "+prod.unit );
                    datatable2.cell( row, 5 ).data( prod.vat+"%" );
                    datatable2.cell( row, 6 ).data( "<?php echo MY_Controller::$site_settings["currency_symbol"]; ?> "+prod.price );
                    datatable2.cell( row, 7 ).data( prod.qty );

                    if(prod.is_express == 1){
                        datatable2.cell( row, 8 ).data( '<a href="javascript:changeExpress(\''+0+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-success"><?php echo _l("Yes"); ?></span></a>' );
                    }else{
                        datatable2.cell( row, 8 ).data( '<a href="javascript:changeExpress(\''+1+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-default"><?php echo _l("No"); ?></span></a>' );
                    }
                    if(prod.status == 1){
                        datatable2.cell( row, 9 ).data( '<a href="javascript:changeStatus(\''+0+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-success"><?php echo _l("Enable"); ?></span></a>' );
                    }else{
                        datatable2.cell( row, 9 ).data( '<a href="javascript:changeStatus(\''+1+'\',\''+prod.product_id+'\',\''+row+'\')" id="ref_'+prod.product_id+'"><span class="label label-danger"><?php echo _l("Disable"); ?></span></a>' );
                    }
                    $("#quickEditModal").modal("hide");
                }
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