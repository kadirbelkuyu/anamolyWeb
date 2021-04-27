



  <?php if(isset($tabs) && count($tabs) > 0){?>
<div class="container">
      <div class="section ui-buttons">
          <div class="row ">
              <div class="col s12 pad-0">
                
                  <div class="hr_scroll">
                      <div class="hr_container">
                  <?php foreach ($tabs as $key => $value) {
                    ?>
                    <a href="<?php echo site_url("home/".$value->tag_ref);?>" class="waves-effect waves-light btn btn-rounded red lighten-2"><?php echo _lname($value,"tag_name");  ?></a>
                    <?php
                  }?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
</div>
  <?php } ?>
  <?php ?>
  <?php if(isset($banners) && !empty($banners)){
    ?>
  <div class="carousel carousel-fullscreen carousel-slider home_carousel">
    <?php foreach($banners as $banner){ ?>
      <div class="carousel-item" href="#one_<?php echo $banner->banner_id; ?>!">
          <div class="bg" style="background-image: url('<?php echo REMOTE_URL."/uploads/banners/".$banner->banner_image;?>')"></div>
      </div>
      <?php } ?>
  </div>
  <?php } ?>

<div class="container">
<?php foreach ($products as $value) {
  if(!empty($value->products)){
  ?>
  <div class="section">
      <h5><?php echo _lname($value,"sub_cat_name"); ?><a class="pull-right" style="font-weight:normal;" href="<?php echo site_url("products/".$value->category_id."/".$value->sub_category_id); ?>"><?php echo _l("Show more");?><i class="mdi mdi-arrow-right-drop-circle"></i></a></h5>
      <div class="row equal-height">
          <?php foreach ($value->products as $k => $val) { ?>
          <?php $this->load->view("webshop/views/product_card",array("val"=>$val)); ?>
          <?php } ?>

      </div>
  </div>
  <?php
  }
}?>

</div>
