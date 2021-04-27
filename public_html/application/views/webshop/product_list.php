
<div class="container">
  <div class="section">
  <div class="row equal-height">
  <?php foreach ($data as $item) {
    ?>
  <?php $this->load->view("webshop/views/product_card",array("val"=>$item)); ?>
    <?php
  }?>


   <div class="col s6 m4 l3 ">
                <div class="z-depth-1 card product-card ">

                    <div class="card-image">
                        <img src="<?php echo base_url("themes/webshop/images/placeholder.png"); ?>">
                    </div>

                    <div class="card-content">
                        <p class="product-title"><?php echo _l("Missing Any Product?"); ?></p>
                       <a href="#modalRequest" class="waves-effect waves-light modal-trigger btn btn-danger"><?php echo _l("Request New"); ?></a>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
  </div>
  </div>
</div>

<div id="modalRequest" class="modal">

        <div class="modal-content">
           <h4><?php echo _l("Suggest missing products"); ?></h4>
           <?php
           echo _input_field("suggest","", _get_post_back($data,'suggest'), 'text', array("data-validation" =>"required","maxlength"=>255,"placeholder"=>_l("Ex. Organic, Banana, 5 Nos.")),array(),"s12");
          ?>
        <div>
              <a href="javascript:;" onclick="openfileUpload();"> <img src="<?php echo base_url("themes/webshop/images/placeholder.png"); ?>" id="suggest-prod-image" alt="<?php echo _l("Upload Product Image (Optional)"); ?>" />
              <br />
              <p class="imgName"><?php echo _l("Upload Product Image (Optional)"); ?></p>
              </a>
               <input type="file" name="fileUpload" id="fileUpload" style="display: none;" onchange="UploadImage();">
            </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
          <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="Send();"><?php echo _l("Send"); ?></button>
        </div>
 </div>

 <script>
 function openfileUpload(){
    $("#fileUpload").click();
    }

 function UploadImage(){

    var ext = $("#fileUpload").val().split(".");
        var extension = ext[ext.length - 1].toLowerCase();
        var arrayExtensions = ["jpg", "jpeg", "png", "bmp", "gif"];

        if (arrayExtensions.lastIndexOf(extension) == -1) {
            toastScript("Select Valid Image File !","error");
        }
        else
        {
             var imagename = $("#fileUpload").val().split("\\");
           $(".imgName").html(imagename[imagename.length - 1]);
        }
    }

function Send(){

if($("#suggest").val()=="")
{
   toastScript("Please enter suggest !","error");
}
else
{
    var data = new FormData();

    data.append("suggest", $("#suggest").val());

    if($("#fileUpload").val()!="")
    {
      var files = $("#fileUpload").get(0).files;
            if (files.length > 0) {
                data.append("fileUpload", files[0]);
            }
    }
    else
    {
        data.append("fileUpload", null);
    }

            $.ajax({
                method: "POST",
                url: "<?php echo site_url("products/send"); ?>",
                data: data,
                processData: false,
                contentType: false,
            	}).done(function( data ) {
                  if(data.responce){
                      toastScript(data.message,"success");
                      $("#fileUpload").val("");
                      $("#modalRequest").modal("close");
                  }
                  else
                  {
                    toastScript(data.message,"error");
                  }
            	});
    }
}

 </script>
