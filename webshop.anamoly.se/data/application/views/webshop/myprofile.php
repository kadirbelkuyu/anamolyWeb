<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0 mb-30">
                <h5 class="bot-20 sec-tit"><?php echo _l("My Profile"); ?></h5>

              <div class="editprof-img">
            <div class="img-wrap circle">
            <img class="user-profile-img" src="<?php echo _get_current_user_image(); ?>" alt="">
            <i class="mdi mdi-pencil prefix" onclick="openfileUpload();"></i>
            <input type="file" style="display: none;" id="fileUpload" onchange="UploadImage();">
            </div>
             <div><?php echo _get_current_user_companyname(); ?></div>
            <div><?php echo _get_current_user_email(); ?></div>
          </div>

            </div>

             <ul class="collection invoice-item">
             <li class="collection-item avatar p_l_20">
              <div class="item-det ">
                  <span class="title"><?php echo _l("Full Name"); ?></span>
                  <p><?php echo $users->user_firstname." ".$users->user_lastname; ?>
                  </p>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><a href="#modalFullname" class="waves-effect waves-light modal-trigger"><i class="mdi mdi-square-edit-outline"></i> </a> </h6>
                </div>
            </li>

             <li class="collection-item avatar p_l_20">
              <div class="item-det">
                  <span class="title"><?php echo _l("Email ID"); ?></span>
                  <p><?php echo $users->user_email; ?>
                  </p>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><a href="#modalEmail" class="waves-effect waves-light modal-trigger"><i class="mdi mdi-square-edit-outline"></i> </a> </h6>
                </div>
            </li>

             <li class="collection-item avatar p_l_20">
              <div class="item-det">
                  <span class="title"><?php echo _l("Phone No."); ?></span>
                  <p><?php echo $users->user_phone; ?>
                  </p>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><a href="#modalPhone" class="waves-effect waves-light modal-trigger"><i class="mdi mdi-square-edit-outline"></i> </a> </h6>
                </div>
            </li>

             <li class="collection-item avatar p_l_20">
              <div class="item-det">
                  <span class="title"><?php echo _l("Address"); ?></span>
                  <p>
                  <?php
                  if(!empty($users->addresses))
                  {
                     echo $users->addresses[0]->postal_code.", ".$users->addresses[0]->house_no.", ".$users->addresses[0]->add_on_house_no.", ".$users->addresses[0]->city.", ".$users->addresses[0]->street_name;
                  }

                  ?>

                  </p>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><a href="#modalAddress" class="waves-effect waves-light modal-trigger"><i class="mdi mdi-square-edit-outline"></i> </a> </h6>
                </div>
            </li>

             <li class="collection-item avatar p_l_20">
              <div class="item-det">
                  <span class="title"><?php echo _l("Home Situation"); ?></span>
                  <p>
                  <?php
                  if(!empty($users->family))
                  {
                    for($no=0;$no<$users->family->no_of_adults;$no++)
                    {
                       echo "<img class='home-suite-img' src=".base_url(WEB_THEME_BASE."/images/ic_adult.png").">";
                    }
                    for($no=0;$no<$users->family->no_of_child;$no++)
                    {
                       echo "<img class='home-suite-img'  src=".base_url(WEB_THEME_BASE."/images/ic_child.png").">";
                    }
                    for($no=0;$no<$users->family->no_of_dogs;$no++)
                    {
                       echo "<img class='home-suite-img'  src=".base_url(WEB_THEME_BASE."/images/ic_dog.png").">";
                    }
                    for($no=0;$no<$users->family->no_of_cats;$no++)
                    {
                       echo "<img class='home-suite-img'  src=".base_url(WEB_THEME_BASE."/images/ic_cat.png").">";
                    }
                   }

                  ?>

                  </p>
              </div>
                <div class="secondary-content">
                    <h6 class="top-0"><a href="#modalHomeSituation" class="waves-effect waves-light modal-trigger"><i class="mdi mdi-square-edit-outline"></i> </a> </h6>
                </div>
            </li>
        </ul>
        </div>
    </div>
</div>

<div id="modalFullname" class="modal">

        <div class="modal-content">
           <h4><?php echo _l("Full Name"); ?></h4>
           <?php
           echo _input_field("user_firstname",_l("First Name")."<span class='text-danger'>*</span>", _get_post_back($users,'user_firstname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"s12");
           echo _input_field("user_lastname",_l("Last Name")."<span class='text-danger'>*</span>", _get_post_back($users,'user_lastname'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"s12");
          ?>

        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="saveFullname();"><?php echo _l("Save"); ?></button>
        </div>

 </div>

<div id="modalEmail" class="modal">
        <div class="modal-content">
           <h4><?php echo _l("Email ID"); ?></h4>
           <?php
           echo _input_field("user_email",_l("Email ID")."<span class='text-danger'>*</span>", _get_post_back($users,'user_email'), 'text', array("data-validation" =>"required","maxlength"=>255),array(),"s12");
          ?>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" onclick="saveEmail();" class="waves-effect waves-green btn-flat btn btn-primary"><?php echo _l("Save"); ?></button>
        </div>
 </div>

 <div id="modalPhone" class="modal">
        <div class="modal-content">
           <h4><?php echo _l("Phone No"); ?></h4>
           <?php
            echo _input_field("user_phone",_l("Phone No.")."<span class='text-danger'>*</span>", _get_post_back($users,'user_phone'), 'text', array("data-validation" =>"required","maxlength"=>50),array(),"s12");
            ?>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="savePhone();"><?php echo _l("Save"); ?></button>
        </div>
 </div>

 <div id="modalAddress" class="modal modal-fixed-footer">
        <div class="modal-content">
           <h4><?php echo _l("Delivery Address"); ?></h4>
           <?php
           echo _input_field("postal_code",_l("Postal Code")."<span class='text-danger'>*</span>", _get_post_back($users->addresses[0],'postal_code'), 'text', array("data-validation" =>"required","maxlength"=>50,"placeholder"=>_l("Postal Code")),array(),"s12");
           echo _input_field("house_no",_l("House No")."<span class='text-danger'>*</span>", _get_post_back($users->addresses[0],'house_no'), 'text', array("data-validation" =>"required","maxlength"=>50,"placeholder"=>_l("House No")),array(),"s12");
           echo _input_field("add_on_house_no",_l("House Ad On"), _get_post_back($users->addresses[0],'add_on_house_no'), 'text', array("maxlength"=>50,"placeholder"=>_l("House Ad On")),array(),"s12");
           echo _input_field("city",_l("City"), _get_post_back($users->addresses[0],'city'), 'text', array("maxlength"=>100,"placeholder"=>_l("City")),array(),"s12");
           echo _input_field("street_name",_l("Street"), _get_post_back($users->addresses[0],'street_name'), 'text', array("maxlength"=>100,"placeholder"=>_l("Street")),array(),"s12");
          ?>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel")?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="saveAddress();"><?php echo _l("Save"); ?></button>
        </div>
 </div>

  <div id="modalHomeSituation" class="modal modal-fixed-footer">
        <div class="modal-content">
           <h4><?php echo _l("Home Situation"); ?></h4>
           <p><?php echo _l("Number of people, child's and pets in your family"); ?></p>
           <input type="hidden" id="hfFamilyImagePath" value="<?php echo base_url(WEB_THEME_BASE."/images")?>" />
          <b><?php echo _l("Adults"); ?> - <span id="spanTotalAdult"><?php echo (isset($users->family->no_of_adults)) ? $users->family->no_of_adults : 0; ?> </span></b>
          <div>
          <div id="divAdult" class="pull-left">
           <?php
                  if(!empty($users->family) && isset($users->family->no_of_adults))
                  {
                    for($no=0;$no<$users->family->no_of_adults;$no++)
                    {
                       echo "<img class='home-suite-img' src=".base_url(WEB_THEME_BASE."/images/ic_adult.png").">";
                    }

                   }

                  ?>
          </div>
          <div class="pull-right incr-desc-btn">
          <a href="javascript:;" onclick="manageAdult(0);"><i class="mdi mdi-minus-box"></i></a><a href="javascript:;" onclick="manageAdult(1);"><i class="mdi mdi-plus-box"></i></a>
          </div>

                <div class="clearfix"></div>
           </div>

            <b><?php echo _l("Child"); ?> - <span id="spanTotalChild"><?php echo (isset($users->family->no_of_child))? $users->family->no_of_child : 0; ?> </span></b>
          <div>
               <div id="divChild" class="pull-left">
           <?php
                  if(!empty($users->family) && isset($users->family->no_of_child))
                  {
                    for($no=0;$no<$users->family->no_of_child;$no++)
                    {
                       echo "<img class='home-suite-img' src=".base_url(WEB_THEME_BASE."/images/ic_child.png").">";
                    }
                  }
                  ?>
          </div>
          <div class="pull-right incr-desc-btn">
          <a href="javascript:;" onclick="manageChild(0);"><i class="mdi mdi-minus-box"></i></a><a href="javascript:;" onclick="manageChild(1);"><i class="mdi mdi-plus-box"></i></a>
          </div>

                <div class="clearfix"></div>
           </div>

               <b><?php echo _l("Dogs"); ?> - <span id="spanTotalDog"><?php echo (isset($users->family->no_of_dogs)) ? $users->family->no_of_dogs : 0; ?> </span></b>
          <div>
               <div id="divDog" class="pull-left">
           <?php
                  if(!empty($users->family) && isset($users->family->no_of_dogs))
                  {
                    for($no=0;$no<$users->family->no_of_dogs;$no++)
                    {
                       echo "<img class='home-suite-img' src=".base_url(WEB_THEME_BASE."/images/ic_dog.png").">";
                    }

                   }

                  ?>
          </div>
          <div class="pull-right incr-desc-btn">
          <a href="javascript:;" onclick="manageDog(0);"><i class="mdi mdi-minus-box"></i></a><a href="javascript:;" onclick="manageDog(1);"><i class="mdi mdi-plus-box"></i></a>
          </div>

                <div class="clearfix"></div>
           </div>

               <b><?php echo _l("Cats"); ?> - <span id="spanTotalCat"><?php echo (isset($users->family->no_of_cats)) ? $users->family->no_of_cats : 0; ?> </span></b>
          <div>
               <div id="divCat" class="pull-left">
           <?php
                  if(!empty($users->family) && isset($users->family->no_of_cats))
                  {
                    for($no=0;$no<$users->family->no_of_cats;$no++)
                    {
                       echo "<img class='home-suite-img' src=".base_url(WEB_THEME_BASE."/images/ic_cat.png").">";
                    }
                    }
                    ?>
          </div>
          <div class="pull-right incr-desc-btn">
          <a href="javascript:;" onclick="manageCat(0);"><i class="mdi mdi-minus-box"></i></a><a href="javascript:;" onclick="manageCat(1);"><i class="mdi mdi-plus-box"></i></a>
          </div>

                <div class="clearfix"></div>
           </div>

        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="saveFamily();"><?php echo _l("Save"); ?></button>
        </div>

 </div>

 <script>

 function manageAdult(type)
 {
    var total=parseInt($("#spanTotalAdult").html());
    if(type==0)
    {
        if(total>0)
        {
          total=total-1;
        }
    }
    else
    {
        total=total+1;
    }

    $("#divAdult").html("");

    for(no=0;no<total;no++)
    {
        $("#divAdult").append("<img  src='"+$("#hfFamilyImagePath").val()+"/ic_adult.png'/>");
    }

    $("#spanTotalAdult").html(total);
 }

 function manageChild(type)
 {
    var total=parseInt($("#spanTotalChild").html());
    if(type==0)
    {
        if(total>0)
        {
          total=total-1;
        }
    }
    else
    {
        total=total+1;
    }

    $("#divChild").html("");

    for(no=0;no<total;no++)
    {
        $("#divChild").append("<img  src='"+$("#hfFamilyImagePath").val()+"/ic_child.png'/>");
    }

    $("#spanTotalChild").html(total);
 }

 function manageDog(type)
 {
    var total=parseInt($("#spanTotalDog").html());
    if(type==0)
    {
        if(total>0)
        {
          total=total-1;
        }
    }
    else
    {
        total=total+1;
    }

    $("#divDog").html("");

    for(no=0;no<total;no++)
    {
        $("#divDog").append("<img  src='"+$("#hfFamilyImagePath").val()+"/ic_dog.png'/>");
    }

    $("#spanTotalDog").html(total);
 }

 function manageCat(type)
 {
    var total=parseInt($("#spanTotalCat").html());
    if(type==0)
    {
        if(total>0)
        {
          total=total-1;
        }
    }
    else
    {
        total=total+1;
    }

    $("#divCat").html("");

    for(no=0;no<total;no++)
    {
        $("#divCat").append("<img  src='"+$("#hfFamilyImagePath").val()+"/ic_cat.png'/>");
    }

    $("#spanTotalCat").html(total);
 }

 function saveFullname(){

    var user_firstname=$("#user_firstname").val();
    var user_lastname=$("#user_lastname").val();

	$.ajax({
    method: "POST",
    url: "<?php echo site_url("user/update_name"); ?>",
    data: { user_firstname: user_firstname,user_lastname:user_lastname }
	}).done(function( data ) {
      if(data.responce){
          toastScript(data.message,"success");
          location.reload();
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

 function savePhone(){

    var phoneno=$("#user_phone").val();

	$.ajax({
    method: "POST",
    url: "<?php echo site_url("user/update_phone"); ?>",
    data: { user_phone: phoneno }
	}).done(function( data ) {
      if(data.responce){
          toastScript(data.message,"success");
          location.reload();
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

 function saveAddress(){

    var postal_code=$("#postal_code").val();
    var house_no=$("#house_no").val();
    var add_on_house_no=$("#add_on_house_no").val();
var city=$("#city").val();
var street_name=$("#street_name").val();
	$.ajax({
    method: "POST",
    url: "<?php echo site_url("user/update_address"); ?>",
    data: { postal_code: postal_code,house_no:house_no,add_on_house_no:add_on_house_no,street_name:street_name,city:city }
	}).done(function( data ) {
      if(data.responce){
          toastScript(data.message,"success");
          location.reload();
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

 function saveEmail(){
    var user_email=$("#user_email").val();
  	$.ajax({
      method: "POST",
      url: "<?php echo site_url("user/update_email"); ?>",
      data: { user_email: user_email }
  	}).done(function( data ) {
        if(data.responce){
            toastScript(data.message,"success");
            window.location.replace("<?php echo site_url("user/varify_email_otp"); ?>");

        }
        else
        {
          toastScript(data.message,"error");
        }
  	});
}

 function saveFamily(){

    var no_of_adults=$("#spanTotalAdult").html();
    var no_of_child=$("#spanTotalChild").html();
    var no_of_dogs=$("#spanTotalDog").html();
    var no_of_cats=$("#spanTotalCat").html();

	$.ajax({
    method: "POST",
    url: "<?php echo site_url("user/update_setfamily"); ?>",
    data: { no_of_adults: no_of_adults,no_of_child:no_of_child,no_of_dogs:no_of_dogs,no_of_cats:no_of_cats }
	}).done(function( data ) {
      if(data.responce){
          toastScript(data.message,"success");
          location.reload();
      }
      else
      {
        toastScript(data.message,"error");
      }
	});
}

function openfileUpload(){
    $("#fileUpload").click();
    }

function UploadImage(){

    var ext = $("#fileUpload").val().split(".");
        ext = ext[ext.length - 1].toLowerCase();
        var arrayExtensions = ["jpg", "jpeg", "png", "bmp", "gif"];

        if (arrayExtensions.lastIndexOf(ext) == -1) {
            toastScript("Select Valid Image File !","error");
        }
        else
        {
            var data = new FormData();
            var files = $("#fileUpload").get(0).files;
            if (files.length > 0) {
                data.append("fileUpload", files[0]);
            }
            $.ajax({
                method: "POST",
                url: "<?php echo site_url("user/update_photo"); ?>",
                data: data,
                processData: false,
                contentType: false,
            	}).done(function( data ) {
                  if(data.responce){
                      toastScript(data.message,"success");
                      $(".user-profile-img").attr("src","<?php echo REMOTE_URL."/uploads/profile/"; ?>"+data.data);            //location.reload();
                  }
                  else
                  {
                    toastScript(data.message,"error");
                  }
            	});
        }
    }

 </script>
