<div class="product_tags" style=" padding: 10px 0px; z-index:10; background:#00529B; width:100%">
    <div class="container">
        <?php if(isset($subcategories)){?>
                <div class="col s12 pad-0 hr_scroll">
                  <div class="hr_container">
        <?php foreach ($subcategories as $value) {
          ?>
          <a href="<?php echo site_url("products/".$value->category_id."/".$value->sub_category_id); ?>" class="waves-effect waves-light btn btn-sub-category red lighten-2"><?php echo  _lname($value,"sub_cat_name");  ?></a>
        <?php } ?>
                  </div>
                </div>
      <?php } ?>
                <div class="col s12 pad-0 hr_scroll">
                    <div class="hr_container">
          <?php foreach ($data as $value) {
            ?>
            <a href="#<?php $group_name = _lname($value,"group_name"); echo url_title($group_name);?>" class="waves-effect waves-light btn btn-rounded red lighten-2"><?php echo  $group_name;  ?></a>
          <?php } ?>
                    </div>
                </div>
    </div>
</div>
<div style="height:54px;">
</div>
<div class="container">
  <div class="section">
  <?php foreach ($data as $value) {
    if(!empty($value->products)){
    ?>
        <h5 id="<?php $group_name = _lname($value,"group_name"); echo url_title($group_name);?>"><?php echo $group_name; ?></h5>
        <div class="row equal-height">
            <?php foreach ($value->products as $k => $val) { ?>
              <?php $this->load->view("webshop/views/product_card",array("val"=>$val)); ?>
            <?php } ?>
        </div>
    <?php
    }
  }?>
  </div>
</div>
