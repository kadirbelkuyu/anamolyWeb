<div class="container">
    <div class="section">

        <div class="search-results contacts">

            <ul class="collection  no-shadow contacts z-depth-1">
                <?php foreach($categories as $category){ ?>
                <li class="collection-item avatar">

                    <a href="<?php echo site_url("products/".$category->category_id."/".$category->sub_category_id);?>" class='chatlink waves-effect'> <img src="<?php echo _limage($category->sub_cat_image,CATEGORY_IMAGE_PATH);?>"
                            class="circle">
                        <span class="title"><?php echo _lname($category,"sub_cat_name"); ?></span>

                    </a>
                    <div class="secondary-content">
                        <a href="<?php echo site_url("products/".$category->category_id."/".$category->sub_category_id);?>"><i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </li>
                <?php } ?>


            </ul>
        </div>
    </div>
</div>
