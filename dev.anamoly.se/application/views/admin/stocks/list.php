<!-- Main content -->
<section class="content">
<div class="box">
        <div class="box-body">
         <?php
            
            echo "<div>";
            echo "<form id='filter_product' method='get' action=''>";
            echo _select("category_id",$categories,_l("Category"),array("category_id","cat_name_nl"),_get_post_back($field,'category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
            echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category"),array("sub_category_id","sub_cat_name_nl"),_get_post_back($field,'sub_category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _select("product_id",(isset($products))?$products : array(),_l("Products"),array("product_id","product_name_nl"),_get_post_back($field,'product_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
           
            echo '<div class="col-md-3" ><button type="submit" id="filterProductStocks" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button></div>';
            
            echo "</form>";
            echo "</div>";            
        ?>
        </div>
    </div>
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Stocks"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
                <a href="<?php echo site_url($controller."/add");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="productStockList" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th><?php echo _l("Product"); ?></th>
                    <th><?php echo _l("Qty"); ?></th>
                    <th><?php echo _l("Date"); ?></th>				
					<th width='90'><?php echo _l("Action"); ?></th>
                </tr>
                </thead>
                
            </table>
        </div>
    </div>
    <!-- /.box -->
</section>
