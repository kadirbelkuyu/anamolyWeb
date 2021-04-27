
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

   <?php if(isset($categories) && count($categories) > 0){?>
<div class="container">
      <div class=" ui-buttons">
          <div class="row ">
              <div class="col s12 pad-0">
                  <div class="hr_scroll">
                      <div class="hr_container">
                  <?php foreach($categories as $category){ ?>
                    <div class="sub-cat waves-effect waves-light btn btn-rounded red lighten-2" id="<?php echo $category->category_id; ?>"><?php echo _lname($category,"cat_name"); ?></div>
                    <?php
                  }?>
                      </div>
                  </div>
              </div>
          </div>

      </div>
      <div class="">
        <div id="demo" class="row"></div>
      </div>
</div>
  <?php } ?>
