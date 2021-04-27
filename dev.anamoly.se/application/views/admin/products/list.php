<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-body">
        <?php
            
            echo "<div class=''>";
            echo "<form id='filter_product' method='get' action=''>";
            echo _select("category_id",$categories,_l("Category"),array("category_id","cat_name_nl"),_get_post_back($field,'category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("Select Category")));
            echo _select("sub_category_id",(isset($subcategories))?$subcategories : array(),_l("Sub Category"),array("sub_category_id","sub_cat_name_nl"),_get_post_back($field,'sub_category_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _select("product_group_id",(isset($productgroups))?$productgroups : array(),_l("Product Groups"),array("product_group_id","group_name_nl"),_get_post_back($field,'product_group_id'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo "<div class='clearfix'></div>";
            echo _select("f_status",(isset($f_status))?$f_status : array(),_l("Status"),array(),_get_post_back($field,'f_status'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _select("f_express",(isset($f_express))?$f_express : array(),_l("Express"),array(),_get_post_back($field,'f_express'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            echo _select("f_nutritional",(isset($f_nutritional))?$f_nutritional : array(),_l("Nutritional"),array(),_get_post_back($field,'f_nutritional'),array(),array("form_group_class"=>"col-md-3","include_blank"=>_l("All")));
            
            echo '<div class="col-md-3" ><button type="submit" id="filterProducts" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button></div>';
            
            echo "</form>";
            echo "</div>";
            
    
        ?>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Products"); ?> / <?php echo _l("List"); ?></h3>

            <div class="box-tools pull-right">
            <a href="javascript:deleteSelected()" id="btnDeleteAll" class="btn btn-box-tool btn-bitbucket hide"><i class="fa fa-trash"></i> <?php echo _l("Delete Selected"); ?></a> <a href="<?php echo site_url($controller."/add");?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-plus-circle"></i> <?php echo _l("Add"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>           
            <table id="productList" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><?php echo _l("Image"); ?></th>
                    <th><?php echo _l("Product Name"); ?></th>
                    <th><?php echo _l("Barcode"); ?></th>
                    <th><?php echo _l("Groups"); ?></th>
                    <th><?php echo _l("Unit"); ?></th>
                    <th><?php echo _l("BTW(%)"); ?></th>
                    <th><?php echo _l("Price Per Unit"); ?></th>
                    <th><?php echo _l("QTY"); ?></th>
                    <th><?php echo _l("Is Express"); ?></th>
                    <th><?php echo _l("Status"); ?></th>
                    <th><?php echo _l("Created At"); ?></th>
                     <th><?php echo _l("Stock"); ?></th>
                    <th width='120'><?php echo _l("Action"); ?></th>
                    <th><input type="checkbox" id="selectAll" /></th>
                </tr>
                </thead>
                
            </table>
            
        </div>
    </div>
    <!-- /.box -->
</section>
<div class="modal fade bd-example-modal-lg" id="quickEditModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content ">
        <div class="modal-header">
            <h5 class="modal-title"><?php echo _l("Quick Edit"); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body quick-content">
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="quickGroupModal" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Product Group") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body quick-group">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-2" role="dialog" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imagePreviewModalLabel"><?php echo _l("Image Previews") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body image-previews">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="productStockModal" tabindex="-2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _l("Product Stock") ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body product-stock">
      
      <!-- Main content -->
<section class="content">
   
        <div class="row">
            <?php
            
            echo _get_flash_message();
            echo form_open_multipart("admin/products/addstock",array("id"=>"form_add_stock"));
            echo _input_field("pid","","","hidden"); // hidden field use for edit item
            echo _input_field("rp_index","","","hidden"); // hidden field use for edit item
            
            echo _input_field("qty", _l("Qty")."<span class='text-danger'>*</span>", _get_post_back($field,'qty','','1'), 'number', array("data-validation" =>"required","step"=>"1","maxlength"=>255,"minvalue"=>"1"),array(),"col-md-6");
            echo _input_field("stockdate", _l("Date")."<span class='text-danger'>*</span>", _get_post_back($field,'stockdate'), 'text', array("data-validation" =>"required","maxlength"=>255,"readonly"=>"readonly"),array(),"col-md-6","datepicker");
            
            echo "<div class='clearfix'></div>";
             
			echo '<div class="col-md-6">
				<button type="submit" class="btn btn-primary btn-flat">'._l("Add Stock").'</button>&nbsp;';
			echo '</div>';
            echo form_close();
                        
            ?>
        </div>   
    <!-- /.box -->
</section>
      
      </div>
    </div>
  </div>
</div>