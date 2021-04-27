<script src="<?php echo base_url("themes/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"); ?>">
<script src="<?php echo base_url("themes/backend/plugins/tokenfields/bootstrap-tokenfield.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/tokenfields/css/bootstrap-tokenfield.min.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/tokenfields/css/tokenfield-typeahead.min.css"); ?>">
<!-- Page Script -->
<script>
  $(function () {
    //Add text editor
    $("#compose-textarea").wysihtml5();
    $("#btnCompose").click(function(){
        if($("#composeView").hasClass("hide")){
            $("#composeView").removeClass("hide");
            $("#inboxList").addClass("hide");
            $("#readEmail").addClass("hide");
            tokenField();
            $("#lblBtnCompose").html("<?php echo _l("Back to Mailbox"); ?>");
        }else{
            $("#composeView").addClass("hide");
            $("#inboxList").removeClass("hide");
            $("#lblBtnCompose").html("<?php echo _l("Compose"); ?>");
        }
    });

    $("#composeForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url =  form.attr('action');
    var frmData = form.serializefiles();

    $.ajax({
        type: "POST",
        url: url,
        data: frmData, // serializes the form's elements. form.serialize()
        contentType: false, 
processData: false,
        success: function(data)
        {   
                if(data.response){
                    $("#messageContainer").html(data.message);
                    hideMessage();
                    form[0].reset();
                }else{
                    $("#messageContainer").html(data.message);
                    hideMessage();
                }
        }
        });
    });

 });
 function tokenField(){
    $('#toEmail')
    .on('tokenfield:createtoken', function (e) {
        var data = e.attrs.value.split('|')
        e.attrs.value = data[1] || data[0]
        e.attrs.label = data[1] ? data[0] + ' (' + data[1] + ')' : data[0]
    })
    .on('tokenfield:createdtoken', function (e) {
        // Über-simplistic e-mail validation
        var re = /\S+@\S+\.\S+/
        var valid = re.test(e.attrs.value)
        if (!valid) {
        $(e.relatedTarget).addClass('invalid')
        }
    })
    .on('tokenfield:edittoken', function (e) {
        if (e.attrs.label !== e.attrs.value) {
        var label = e.attrs.label.split(' (')
        e.attrs.value = label[0] + '|' + e.attrs.value
        }
    })
    .on('tokenfield:removedtoken', function (e) {
        alert('Token removed! Token value was: ' + e.attrs.value)
    })
    .tokenfield();
 }
 (function($) {
$.fn.serializefiles = function() {
    var obj = $(this);
    /* ADD FILE TO PARAM AJAX */
    var formData = new FormData();
    $.each($(obj).find("input[type='file']"), function(i, tag) {
        $.each($(tag)[0].files, function(i, file) {
            formData.append(tag.name, file);
        });
    });
   
    var params = $(obj).serializeArray();
    $.each(params, function (i, val) {
        formData.append(val.name, val.value);
    });
    
    return formData;
};
})(jQuery);

function readEmail(id,mailbox){
    $("#emailBodyHtml").html("<img src='<?php echo base_url("themes/backend/img/email.gif"); ?>' />");
    
    $("#readEmail").removeClass("hide");
    $("#composeView").addClass("hide");
    $("#inboxList").addClass("hide");
    $("#emailBodyHtml").html($("<iframe  width='100%' height='700px' allowfullscreen='true' style='border:none;'/>").attr("src", '<?php echo site_url("admin/mailbox/details/"); ?>'+id+'?mailbox='+mailbox)); 
    /*
    $.ajax({
                url: '<?php echo site_url("admin/mailbox/details/"); ?>'+id+'?mailbox='+mailbox,
                type: 'post',
                
                success: function (data) {
                    //$("#readEmail").html(data);
                }
            });
            */
}
function deleteEmail(id,count){
    $.ajax({
                url: '<?php echo site_url("admin/mailbox/delete/"); ?>'+id,
                type: 'post',
                
                success: function (data) {
                    $("#tblEmailList #row_"+count).remove();
                    //$("#readEmail").html(data);
                }
            });
}
function backFromDetails(){
    $("#readEmail").addClass("hide");
    $("#composeView").addClass("hide");
    $("#inboxList").removeClass("hide");
}
</script>