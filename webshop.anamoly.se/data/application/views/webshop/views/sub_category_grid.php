<a href="<?php echo site_url("products/".$sub_cat->category_id."/".$sub_cat->sub_category_id); ?>">
  <div class="col s4 m4 l3 sub-category">
    <div class="cat-card ">
      <div class="cat-card-image" style="background-image : url('<?php echo REMOTE_URL.'/uploads/categories/'.$sub_cat->sub_cat_image; ?>');"></div>
      <div class="card-content">
        <p class="product-title"><?php echo _lname($sub_cat,"sub_cat_name"); ?></p>
      </div>
    </div>
  </div>
</a>
