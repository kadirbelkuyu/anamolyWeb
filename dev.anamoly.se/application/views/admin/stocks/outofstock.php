<!-- Main content -->
<section class="content">
<div class="box">
        <div class="box-body">
         <?php
            
            echo "<div>";
            echo "<form id='filter_product' method='get' action=''>";
            echo _select("category_id",$categories,_l("Category"),array("category_id","cat_name_nl"),_get_post_back($field,'category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
            echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category"),array("sub_category_id","sub_cat_name_nl"),_get_post_back($field,'sub_category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
          
            echo '<div class="col-md-3" ><button type="submit" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button></div>';
            
            echo "</form>";
            echo "</div>";            
        ?>
        </div>
    </div>
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Out of Stocks"); ?> / <?php echo _l("List"); ?></h3>

        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
           <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
                   <th><?php echo _l("Product"); ?></th>
                   <th><?php echo _l("Plus Stock"); ?></th>
                   <th><?php echo _l("Minus Stock"); ?></th>
                   <th><?php echo _l("Remaining Stock"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
			
                foreach($data as $dt){					
                    ?>
                    <tr>                       
                        <td><?php echo $dt->product_name_nl; ?></td>
                        <td><?php echo $dt->productstock; ?></td>
                        <td><?php echo $dt->orderqty; ?></td>
                        <td><?php echo $dt->finalstock; ?></td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box -->
</section>
