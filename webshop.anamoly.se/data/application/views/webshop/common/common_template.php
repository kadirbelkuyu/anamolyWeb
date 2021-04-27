<!DOCTYPE html>
<html class=" ">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title><?php echo (isset($page_title)) ? $page_title : APP_NAME; ?></title>
    <meta content="<?php echo (isset($page_description)) ? $page_description :
APP_NAME; ?>" name="description" />
    <meta content="<?php echo APP_NAME; ?>" name="author" />


    <!-- App Icons -->
    <link rel="apple-touch-icon" sizes="57x57"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-57x57.png"); ?>">
    <link rel="apple-touch-icon" sizes="60x60"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-60x60.png"); ?>">
    <link rel="apple-touch-icon" sizes="72x72"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-72x72.png"); ?>">
    <link rel="apple-touch-icon" sizes="76x76"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-76x76.png"); ?>">
    <link rel="apple-touch-icon" sizes="114x114"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-114x114.png"); ?>">
    <link rel="apple-touch-icon" sizes="120x120"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-120x120.png"); ?>">
    <link rel="apple-touch-icon" sizes="144x144"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-144x144.png"); ?>">
    <link rel="apple-touch-icon" sizes="152x152"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-152x152.png"); ?>">
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo base_url("themes/webshop/images/icons/apple-icon-180x180.png"); ?>">
    <link rel="icon" type="image/png" sizes="192x192"
        href="<?php echo base_url("themes/webshop/images/icons/android-icon-192x192.png"); ?>">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo base_url("themes/webshop/images/icons/favicon-32x32.png"); ?>">
    <link rel="icon" type="image/png" sizes="96x96"
        href="<?php echo base_url("themes/webshop/images/icons/favicon-96x96.png"); ?>">
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo base_url("themes/webshop/images/icons/favicon-16x16.png"); ?>">
    <link rel="manifest" href="<?php echo base_url("themes/webshop/images/icons/manifest.json"); ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage"
        content="<?php echo base_url("themes/webshop/images/icons/ms-icon-144x144.png"); ?>">
    <meta name="theme-color" content="#ffffff">

    <link href="<?php echo base_url("themes/webshop/css/preloader.css"); ?>" type="text/css" rel="stylesheet"
        media="screen,projection" />
    <link href="<?php echo base_url("themes/webshop/css/materialize.min.css"); ?>" type="text/css" rel="stylesheet"
        media="screen,projection" />
    <link href="<?php echo base_url("themes/webshop/fonts/mdi/materialdesignicons.min.css"); ?>" type="text/css"
        rel="stylesheet" media="screen,projection" />
    <link href="<?php echo base_url("themes/webshop/plugins/perfect-scrollbar/perfect-scrollbar.css"); ?>"
        type="text/css" rel="stylesheet" media="screen,projection" />
    <link href="<?php echo base_url("themes/webshop/css/style.css"); ?>?v=1.0.<?php echo time(); ?>" type="text/css" rel="stylesheet"
        media="screen,projection" id="main-style" />
<style>
<?php
$header_color = $this->session->userdata("header_color");
$header_text_color = $this->session->userdata("header_text_color");
$header_logo = $this->session->userdata("header_logo");
$decorative_text_one = $this->session->userdata("decorative_text_one");
$decorative_text_two = $this->session->userdata("decorative_text_two");
$info_box_bg = $this->session->userdata("info_box_bg");
$default_text_color = $this->session->userdata("default_text_color");

$second_button_text_color = $this->session->userdata("second_button_text_color");
$second_button_color = $this->session->userdata("second_button_color");
$button_text_color = $this->session->userdata("button_text_color");
$button_color = $this->session->userdata("button_color");

if(isset($header_color)){ ?>
nav,.product_tags { background: <?php echo "#".$header_color; ?> !important; }
<?php }
if(isset($header_text_color)){
  ?>
  nav a.navicon { color: <?php echo "#".$header_text_color; ?> !important; }
  <?php
}
if(isset($default_text_color)){
  ?>
  body, .dark-text, .footer-menu li a { color: <?php echo "#".$default_text_color; ?> !important; }
  <?php
}
if(isset($decorative_text_one)){
  ?>
  a,body .primary-text { color: <?php echo "#".$decorative_text_one; ?> !important; }
  .cart_total_amount { background-color: <?php echo "#".$decorative_text_one; ?> !important; }
  <?php
}
if(isset($decorative_text_two)){
  ?>
  .red-text { color: <?php echo "#".$decorative_text_two; ?> !important; }
  .sec-tit::after { background:<?php echo "#".$decorative_text_two; ?> !important;  }
  <?php
}
if(isset($second_button_color)){
  ?>
  .red.lighten-2,.delivery_card.active, .red, .footer-menu .cart_total_amount { background-color: <?php echo "#".$second_button_color; ?> !important;}
  <?php
}
if(isset($second_button_text_color)){
  ?>
  .red.lighten-2,.delivery_card.active, .red , .footer-menu .cart_total_amount{ color: <?php echo "#".$second_button_text_color; ?> !important;}
  <?php
}
if(isset($button_color)){
  ?>
  .btn, .btn-large, .btn-small, .btn-floating, .footer-menu li a.active i { background-color: <?php echo "#".$button_color; ?> !important;}
 [type="radio"]:checked + span::after, [type="radio"].with-gap:checked + span::before, [type="radio"].with-gap:checked + span::after{
   border-color: <?php echo "#".$button_color; ?> !important;
 }
 [type="radio"]:checked + span::after, [type="radio"].with-gap:checked + span::after
 {
   background-color : <?php echo "#".$button_color; ?> !important;
 }
 .sub-cat.red,.quick-sub-cat.red,.product_tags .red.lighten-2 { background-color: #ffffff !important; color: <?php echo "#".$button_color; ?> !important; }
 .sub-cat.blue,.quick-sub-cat.blue{ background-color: <?php echo "#".$button_color; ?> !important; color: #ffffff !important; }
 .sub-cat.btn,.quick-sub-cat.btn { box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0),0 3px 1px -2px rgba(0, 0, 0, 0),0 1px 5px 0 rgba(0, 0, 0, 0.08); }
  <?php
}
if(isset($button_text_color)){
  ?>
  .btn, .btn-large, .btn-small, .btn-floating, .footer-menu li a.active i { color: <?php echo "#".$button_text_color; ?> !important;}
  <?php
}
if(isset($info_box_bg)){
  ?>
  .card-panel.bg-primary { background-color: <?php echo "#".$info_box_bg; ?> !important;}
  <?php
}
?>
.cart_qty_label { background-color: gray;}
.red.error { background-color: red !important; }
</style>
</head>

<body class="  html" data-header="light" data-footer="dark" data-header_align="center" data-menu_type="left"
    data-menu="light" data-menu_icons="on" data-footer_type="left" data-site_mode="light" data-footer_menu="show"
    data-footer_menu_style="light">
    <div class="preloader-background">
        <div class="preloader-wrapper">
            <div id="preloader"></div>
        </div>
    </div>
    <nav class="fixedtop topbar navigation" role="navigation">
        <div class="nav-wrapper container">
            <a id="logo-container" href="<?php echo site_url(); ?>" class="brand-logo"><img src="<?php echo (isset($header_logo)) ? REMOTE_URL."/uploads/app/".$header_logo :
base_url("themes/webshop/images/top_logo.png"); ?>" /></a>
            <!--<a href="#" data-target="" class="waves-effect waves-circle navicon back-button htmlmode show-on-large "><i
                    class="mdi mdi-chevron-left" data-page=""></i></a>-->
            <?php if(_is_user_login()){?>
            <a href="#" data-target="slide-nav"
                class="waves-effect waves-circle navicon sidenav-trigger show-on-large"><i class="mdi mdi-menu"></i></a>
            <?php } ?>
            <a href="<?php echo site_url("cart/viewcart") ?>" data-target="" class="waves-effect top-cart waves-circle navicon right nav-site-mode show-on-large"><i
                        class="mdi mdi-cart mdi-transition1"></i><span class='cart_total_amount'></span></a>
            <a href="#!" data-target="dropdownLang" class="dropdown-trigger waves-effect waves-circle navicon right sidenav-trigger show-on-large pulse"><i class="mdi mdi-translate"></i></a>
            <ul id="dropdownLang" class="dropdown-content">
              <li><a href="<?php echo site_url("languageswitcher/switchlang/swedish"); ?>">Swedish</a></li>
              <li><a href="<?php echo site_url("languageswitcher/switchlang/english"); ?>">English</a></li>
                <li><a href="<?php echo site_url("languageswitcher/switchlang/turkish"); ?>">Turkish</a></li>
              <li><a href="<?php echo site_url("languageswitcher/switchlang/arabic"); ?>">Arabic</a></li>
              <!--<li><a href="<?php echo site_url("languageswitcher/switchlang/german"); ?>">German</a></li>-->
            </ul>
        </div>
    </nav>

    <ul id="slide-nav" class="sidenav sidemenu">
            <li class="menu-close"><i class="mdi mdi-close"></i></li>
            <li class="user-wrap" style="padding-bottom: 20px;background-color: #f0f0f0;">
            <div class="user-view row">
            <div class="col s3 imgarea">
                <a href="<?php echo site_url("user/myprofile") ?>"><img src="<?php echo
_get_current_user_image(); ?>" class="circle user-profile-img" alt="<?php echo
_get_current_user_companyname(); ?>"></a></div>
            <div class="col s9 infoarea">
                <a href="<?php echo site_url("user/myprofile") ?>"><span class="name"><?php echo
_get_current_user_companyname(); ?></span></a>
                <a href="<?php echo site_url("user/myprofile") ?>"><span class="email"><?php echo
_get_current_user_email(); ?></span></a>
            </div>
            </div>
            </li>
            <li class="menulinks">
                <ul class="collapsible">

                            <li class="lvl1">
                            <div class="waves-effect " >
                                <a href="<?php echo site_url("order/myorder") ?>">
                                <span class="title"><i class="mdi mdi-cart"></i> <?php echo _l("My Orders"); ?></span>
                                    </a>
                                </div>
                            </li>
                             <li class="lvl1">
                            <div class="waves-effect " >
                                <a href="<?php echo site_url("setting") ?>">
                                <span class="title"><i class="mdi mdi-phone-settings"></i> <?php echo _l("Settings"); ?></span>
                                    </a>
                                </div>
                            </li>
                             <li class="lvl1">
                            <div class="waves-effect " >
                                <a href="<?php echo site_url("appinstruction") ?>">
                                <span class="title"><i class="mdi mdi-information"></i> <?php echo _l("App Instruction"); ?></span>
                                    </a>
                                </div>
                            </li>
                             <li class="lvl1">
                            <div class="waves-effect " >
                                <a href="<?php echo site_url("contactus") ?>">
                                <span class="title"><i class="mdi mdi-email"></i> <?php echo _l("Contact Us"); ?></span>
                                    </a>
                                </div>
                            </li>
                            <li class="lvl1">
                            <div class="waves-effect " >
                                <a href="<?php echo site_url("user/changepassword") ?>">
                                <span class="title"><i class="mdi mdi-key-change"></i> <?php echo _l("Change Password"); ?></span>
                                    </a>
                                </div>
                            </li>
                            <li class="lvl1">
                            <div class="waves-effect " >
                                <a href="<?php echo site_url("login/logout") ?>">
                                <span class="title"><i class="mdi mdi-logout"></i> <?php echo _l("Log Out"); ?></span>
                                    </a>
                                </div>
                            </li>
                </ul>
            </li>
        </ul>
    <?php if (isset($page_content)) {
    echo $page_content;
} ?>

    <div class="footer-menu">
        <ul>
            <li>
                <a href="<?php echo site_url(); ?>" class='<?php echo ($this->
router->fetch_class() == "home") ? "active" : ""; ?>'> <i class="mdi mdi-home-outline"></i>
                    <span><?php echo _l("Home"); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url("category"); ?>" class="<?php echo ($this->
router->fetch_class() == "category") ? "active" : ""; ?>" > <i class="mdi mdi-search-web"></i>
                    <span><?php echo _l("Search"); ?></span>
                </a>
            </li>
            <li>
                <a href="#modalQuickCat" class="modal-trigger" >
                    <i class="menu-middle-icon">
                      <img src="<?php echo base_url("themes/webshop/images/middle_tab.png");?>" style="width:100%;"  />
                    </i>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url("cart/viewcart") ?>" class="<?php echo ($this->
router->fetch_class() == "cart") ? "active" : ""; ?>" > <i class="mdi mdi-cart"></i><span style="margin-left: 36px;" class='cart_total_amount'></span>
                    <span><?php echo _l("Cart"); ?></span>
                </a>
            </li>

            <li>
                <a href="<?php echo site_url("user/myprofile") ?>" class="<?php echo ($this->
router->fetch_class() == "user") ? "active" : ""; ?>"  > <i class="mdi mdi-face-profile"></i>
                    <span><?php echo _l("Account"); ?></span>
                </a>
            </li>

        </ul>
    </div>
    <?php
    $is_featured = $this->session->userdata("is_featured");
    if (!empty($is_featured)) {
    ?>
    <div id="modalQuickCat" class="modal bottom-sheet" style="bottom:61px; background-color:transparent;">
       <div class="modal-content">
          <div class="hr_scroll" style="">
             <div class="hr_container max-content">
              <?php foreach ($is_featured as $key => $category) {
                ?>
                <div data-id="<?php echo $category->category_id; ?>" class="quick-sub-cat waves-effect btn-rounded waves-light btn red lighten-2" id="<?php echo $category->category_id; ?>"><?php echo _lname($category,"cat_name"); ?></div>
                <?php
              }?>
            </div>
            <div id="quickSubCategories">
            </div>
          </div>

          <div style="text-align:center;">
            <a href="#!" class="modal-close"> <i class="menu-middle-icon" style="margin-left: auto; margin-right: auto; margin-bottom:-20px;">
              <img src="<?php echo base_url("themes/webshop/images/middle_tab.png");?>" style="width:100%;"  />
            </i></a>
          </div>
       </div>
     </div>
    <?php } ?>
    <div class="combo-popup" style="display:none;">
        <a href="javascript:hideComboPopup()" class="combo-close"><i class="mdi mdi-close"></i></a>
        <span id="combo-popup-title">2+1 Gain</span>
        <div class="combo-pack">
        </div>
    </div>
<div class="page-footer"></div>
<?php get_flash_alert(); ?>
<!-- PWA Service Worker Code -->

<script type="text/javascript">
  // This is the "Offline copy of pages" service worker

// Add this below content to your HTML page, or add the js file to your page at the very top to register service worker

// Check compatibility for the browser we're running this in
if ("serviceWorker" in navigator) {
  if (navigator.serviceWorker.controller) {
    console.log("[PWA Builder] active service worker found, no need to register");
  } else {
    // Register the service worker
    navigator.serviceWorker
      .register("<?php echo base_url("pwabuilder-sw.js"); ?>", {
        scope: "."
      })
      .then(function (reg) {
        console.log("[PWA Builder] Service worker has been registered for scope: " + reg.scope);
      });
  }
}

</script>


<!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->

<!-- CORE JS FRAMEWORK - START -->
<script src="<?php echo base_url("themes/webshop/js/jquery-2.2.4.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/webshop/js/materialize.js"); ?>"></script>
<script src="<?php echo base_url("themes/webshop/plugins/perfect-scrollbar/perfect-scrollbar.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/webshop/js/jquery.validate.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/webshop/js/additional-methods.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/webshop/js/jquery.mousewheel.min.js"); ?>"></script>
<!-- CORE JS FRAMEWORK - END -->


<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
<script type="text/javascript">
  $(document).ready(function(){
      $(".dropdown-trigger").dropdown();
      $(".validate_form").validate({ errorClass : "helper-text", errorElement:"span" });
      $(".carousel-fullscreen.carousel-slider").carousel({
        fullWidth: true,
        indicators: true,
        height:200,
      });
      setTimeout(autoplay, 3500);
      function autoplay() {
          $(".carousel").carousel("next");
          setTimeout(autoplay, 3500);
      }
         $(".slider3").slider({
                indicators: false,
                height: 200,
        });

      $("body").on("click","#is_company",function(){
          if($("#is_company").is(":checked")){
              $(".company_details").show();
              $("#company_id").attr("required","true");
              $("#company_name").attr("required","true");
          }else{
              $(".company_details").hide();
              $("#company_id").removeAttr("required");
              $("#company_name").removeAttr("required");

          }
      });
  });
    </script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->


<!-- CORE TEMPLATE JS - START -->
<script src="<?php echo base_url("themes/webshop/js/init.js"); ?>"></script>
<?php if (isset($script)) {
    echo $script;
} ?>

<!-- END CORE TEMPLATE JS - END -->
<script type="text/javascript">
         $(".tabs").tabs();
         $("#tabs-swipe-demo").tabs({ swipeable: true });
    </script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(){
    $('.preloader-background').delay(10).fadeOut('slow');
  });
</script>
<?php if(isset($page_script)) { echo $page_script; }?>
<script>
function addToCart(product_id,qty,cart_id){
	$.ajax({
    method: "POST",
    url: "<?php echo site_url("cart/add"); ?>",
    data: { product_id:product_id, qty: qty }
	}).done(function( data ) {
      if(data.responce){
          addtoCartEffect(product_id);
          $("#product_qty_"+product_id).show();

          var ht = $("#product_qty_"+product_id).html();

          if(cart_id != 0 && cart_id != "0"){
              ht = $("#cart_id_qty_"+cart_id).html();
          }
          if(ht == ""){
            ht = "0";
          }

          var qty = parseInt(ht);
          $("#product_qty_"+product_id).html(qty+1);
          if(cart_id != 0 && cart_id != "0"){
            $("#cart_id_qty_"+cart_id).html(qty+1);
          }

          var cart_discount = parseFloat(data.data.discount).toFixed(2);;
          var discount_splt = cart_discount.split(".");
          var str_discount = "<?php echo _currency_symbol(); ?>"+" "+discount_splt[0];
          if(discount_splt.length == 2){
              str_discount = str_discount + ".<sup>"+discount_splt[1]+"</sup>"
          }
          $("#cart_discount").html(str_discount);

          var cart_total = parseFloat(data.data.cart_total).toFixed(2);
          var amt_splt = cart_total.split(".");
          var str_amt = "<?php echo _currency_symbol(); ?>"+" "+amt_splt[0];
          if(amt_splt.length == 2){
              str_amt = str_amt + ".<sup>"+amt_splt[1]+"</sup>"
          }
          $(".lbl_cart_total").html(str_amt);

          $(".cart_total_amount").html(cart_total);
          $("#minus_cart_qty_"+product_id).show();

          var cart_item_lists = $('#cart_item_lists');
          if (cart_item_lists && cart_item_lists.length) {
              cart_item_lists.html(data.html);
          }
          checkComboPack(data.cart_id, data.data.products);
      }else{
          toastScript(data.message,'error');
      }
	});
}
function deleteFromCart(product_id,qty,cart_id){
  $("#product_qty_"+product_id).show();
  var ht = $("#product_qty_"+product_id).html();
  if(cart_id != 0 && cart_id != "0"){
      ht = $("#cart_id_qty_"+cart_id).html();
  }
  if(ht == ""){
    ht = "0";
  }
  var nqty = parseInt(ht);
  if(nqty < 1){
    return;
  }
	$.ajax({
    method: "POST",
    url: "<?php echo site_url("cart/remove"); ?>",
    data: { product_id:product_id, qty: qty }
	}).done(function( data ) {
      if(data.responce){
          $("#product_qty_"+product_id).html(nqty-1);
          if(cart_id != '0' && cart_id != 0){
            $("#cart_id_qty_"+cart_id).html(nqty-1);
            if(nqty-1 == 0){
                $("#row_"+cart_id).remove();
            }
          }
          var cart_discount = parseFloat(data.data.discount).toFixed(2);;
          var discount_splt = cart_discount.split(".");
          var str_discount = "<?php echo _currency_symbol(); ?>"+" "+discount_splt[0];
          if(discount_splt.length == 2){
              str_discount = str_discount + ".<sup>"+discount_splt[1]+"</sup>"
          }
          $("#cart_discount").html(str_discount);


          var cart_total = parseFloat(data.data.cart_total).toFixed(2);
          $(".cart_total_amount").html(cart_total);

          var amt_splt = cart_total.split(".");
          var str_amt = "<?php echo _currency_symbol(); ?>"+" "+amt_splt[0];
          if(amt_splt.length == 2){
              str_amt = str_amt + ".<sup>"+amt_splt[1]+"</sup>"
          }
          $(".lbl_cart_total").html(str_amt);

          var cart_item_lists = $('#cart_item_lists');
          if (cart_item_lists && cart_item_lists.length) {
              cart_item_lists.html(data.html);
          }
          checkComboPack(data.cart_id, data.data.products);
      }else{
          toastScript(data.message,'error');
      }
	});
}
function checkComboPack(cart_id,cart_items){
  $.each(cart_items, function (i, product) {
      var cart_item = null;
      $.each(product.items, function (index, item) {
          if(cart_id == item.cart_id){
              cart_item = item;
              return;
          }
      });
      if(cart_item != null){
        if(cart_item.offer_type != null){
          if(cart_item.offer_type == "plusone"){
              $("#combo-popup-title").html(cart_item.number_of_products+" + 1 Gain");
              var total_combo_items = parseInt(cart_item.number_of_products) + 1;
          }else if(cart_item.offer_type == "flatcombo"){
              $("#combo-popup-title").html(cart_item.number_of_products+" voor <?php echo _currency_symbol(); ?> "+cart_item.offer_discount);
              var total_combo_items = parseInt(cart_item.number_of_products);
          }

          var html = "";
          var count = 0;
          $.each(product.items, function (index, item) {
              count = count + 1;
              html += '<div class="item" style="background-color:#e5e5e5; background-image:url(\'<?php echo REMOTE_URL."/".PRODUCT_IMAGE_PATH."/"; ?>'+item.product_image+'\')"><i class="plus mdi mdi-plus"></i></div>';
          });
          if(total_combo_items > count){
              for (var i = count; i < total_combo_items; i++) {
                  html += '<div class="item"><i class="plus mdi mdi-plus"></i></div>';
              }
          }
          $(".combo-popup .combo-pack").html(html);
          $(".combo-popup").show();

        }

        return;
      }
  });
}
function hideComboPopup(){
    $(".combo-popup").hide();
}
function loadCart(){
	$.ajax({
    method: "POST",
    url: "<?php echo site_url("cart/get"); ?>",
    data: { }
	}).done(function( data ) {
      if(data.responce){
          var cart_total = parseFloat(data.data.cart_total).toFixed(2);
          $(".cart_total_amount").html(cart_total);
      }
	});
}
loadCart();

$("body").on("click",".quick-sub-cat",function(){
    var cat_id = $(this).data("id");
    $(".quick-sub-cat").addClass("red");
    $(this).removeClass("red");
    window.location.replace("<?php echo site_url()?>?cat="+cat_id);
    /*
    $.ajax({
      method: "POST",
      url: "<?php echo site_url("category/load_sub_cat"); ?>",
      data: { cat_id : cat_id }
    }).done(function( data ) {
        //if(data.responce){
            $("#quickSubCategories").html(data);
        //}
    });
    */
});

 $(".modal").modal();

 $('img').on("error", function() {
   $(this).attr('src', '<?php echo base_url("themes/webshop/images/placeholder.png"); ?>');
 });

function addtoCartEffect(product_id){
        var cart = $('.footer-menu .cart_total_amount');
        var imgtodrag = $('#image_'+product_id);

        if (imgtodrag && imgtodrag.length) {
            var imgclone = imgtodrag.clone()
                .offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            })
                .css({
                'opacity': '0.5',
                    'position': 'absolute',
                    'height': '150px',
                    'width': '150px',
                    'z-index': '100'
            })
                .appendTo($('body'))
                .animate({
                'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 10,
                    'height': 10
            }, 500);
            /*
            setTimeout(function () {
                cart..shake();
            }, 1000);
            */

            imgclone.animate({
                'width': 0,
                    'height': 0
            }, function () {
                //$(this).detach()
                imgclone.remove();
            });
        }
}
window.mobileCheck = function() {
  let check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};
/*
if(mobileCheck()){
  $(".hr_container").addClass("max-content");
}else {
  $(".hr_container").removeClass("max-content");
}
*/
$('#modalQuickCat').modal();
</script>
</body>
</html>
