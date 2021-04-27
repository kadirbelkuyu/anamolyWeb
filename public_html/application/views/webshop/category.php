<div class="container">
    <div class="section">
        <div class="search-bar">
            <nav class="ui-forms  no-shadow">
                <div class="nav-wrapper">
                    <form>
                        <div class="input-field">
                            <input id="search"  autocomplete="off" type="search" placeholder="<?php echo _l("Search Products"); ?>" required>
                            <label for="search"><i class="mdi mdi-magnify"></i></label>
                            <i class="material-icons mdi mdi-close"></i>

                        </div>
                    </form>
                </div>
            </nav>
        </div>
        <div class="search-results contacts">
<div id="suggesstion-box"></div>
            <ul class="collection no-shadow contacts z-depth-1">
                <?php foreach($categories as $category){ ?>
                <li class="collection-item avatar">

                    <a href="<?php echo site_url("category/subcat/".$category->category_id); ?>" class='chatlink waves-effect'> <img src="<?php echo _limage($category->cat_image,CATEGORY_IMAGE_PATH);?>"
                            class="circle">
                        <span class="title"><?php echo _lname($category,"cat_name"); ?></span>

                    </a>
                    <div class="secondary-content">
                        <a href="<?php echo site_url("category/subcat/".$category->category_id); ?>"><i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </li>
                <?php } ?>


            </ul>
        </div>
    </div>
</div>
