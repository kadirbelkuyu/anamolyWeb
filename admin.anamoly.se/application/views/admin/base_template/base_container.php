<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo APP_NAME; ?> | <?php echo ucfirst($this->router->fetch_class()); ?></title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>">
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/font-awesome/css/font-awesome.min.css"); ?>">
<!-- Ionicons -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/Ionicons/css/ionicons.min.css"); ?>">
<?php
if(isset($datepicker)){
    ?>
    <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"); ?>">
    <?php
}
?>
<?php
if(isset($select2)){
    ?>
    <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/select2/dist/css/select2.min.css"); ?>">
    <?php
}
?>
<?php
if(isset($wysihtml5)){
    ?>
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"); ?>">
  <?php
}
?>
<?php
if(isset($iCheck)){
    ?>
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/iCheck/all.css"); ?>">
<?php
}
?>
<?php
if(isset($timepicker)){
    ?>
<!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/timepicker/bootstrap-timepicker.min.css"); ?>">
<?php
}
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"); ?>">
<?php
if(isset($datatable_export)){
    ?>
    <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/datatables.net-bs/css/buttons.dataTables.min.css"); ?>">
    <?php
}
?>
<?php if(isset($status_js)){
?>
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/bootstrap-toggle/bootstrap-toggle.min.css"); ?>">
<?php } ?>
<?php if(isset($colorpickerjs)){
?>
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/colorpicker/css/colorpicker.css"); ?>">
<?php } ?>
<?php if(isset($from_to_timepicker)){
?>
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/jquery-timepicker/jquery.timepicker.min.css"); ?>">
<?php } ?>
<!-- Daterangepicker -->
<?php
if(isset($daterangepicker)){
?>
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/bootstrap-daterangepicker/daterangepicker.css"); ?>">
<?php
}
?>
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/dist/css/AdminLTE.min.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("themes/backend/dist/css/custom.css"); ?>">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/dist/css/skins/_all-skins.min.css"); ?>">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-yellow sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <?php $this->load->view("admin/base_template/common_header"); ?>
  <?php $this->load->view("admin/base_template/common_sidebar"); ?>  

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if(isset($page_content)){ echo $page_content; }  ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view("admin/base_template/common_footer"); ?>  
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- jQuery 3 -->
<script src="<?php echo base_url("themes/backend/bower_components/jquery/dist/jquery.min.js") ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url("themes/backend/bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url("themes/backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"); ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url("themes/backend/bower_components/fastclick/lib/fastclick.js"); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url("themes/backend/dist/js/adminlte.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/backend/bower_components/form-validator/jquery.form-validator.min.js"); ?>"></script>
<script>
var JQ_BASE_URL = "<?php echo site_url(); ?>";
$(document).ready(function () {
    $.validate({
        modules : 'date, logic'
    });
    $('.sidebar-menu').tree();
    hideMessage();   
});
function hideMessage(){
    $(".alert.alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
		$(".alert.alert-dismissible").slideUp(500);
	});
}
function showCommonAlertMessage(data){
    $(".message_container").html(data);
    $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert-dismissible").slideUp(500);
        $(".message_container").html("");
    });
}
function printData(divId)
{
   window.print();
   
}
<?php
if(isset($printbutton)){
    ?>
function printButton(divName) {
     window.print();
}
    <?php
}
?>
</script>
<?php if(isset($fileupload)){ ?>
<script>
$(document).ready(function () {
$(".profileImage").click(function(e) {
    var parent = $(this).parent();
    parent.children(".imageUpload").click();
    
    //$(".imageUpload").click();
    
});

function fasterPreview( uploader, parent ) {
    if ( uploader.files && uploader.files[0] ){
          parent.find('.profileImage').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}

$(".imageUpload").change(function(){
    fasterPreview( this, $(this).parent() );
});
});
</script>
<?php } ?>
<?php if(isset($datepicker)){
?>
<script src="<?php echo base_url("themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"); ?>"></script>
<script>
$('.datepicker').datepicker({
      autoclose: true,
      format: '<?php echo DEFAULT_DATE_PICKER_FORMATE; ?>',
});
</script>
<?php
    }
?>

<?php if(isset($select2)){
?>
<script src="<?php echo base_url("themes/backend/bower_components/select2/dist/js/select2.full.min.js"); ?>"></script>
<script>
$('.select2').select2()
</script>
<?php
    }
?>
<?php if(isset($wysihtml5)){
?>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url("themes/backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"); ?>"></script>
<script>
$(function () {
    //bootstrap WYSIHTML5 - text editor
    $('.wysihtml').wysihtml5()
})
</script>
<?php
    }
?>
<?php if(isset($ckeditor)){
?>
<!-- CK HTML Editor -->
<script src="<?php echo base_url("themes/backend/bower_components/ckeditor/ckeditor.js"); ?>"></script>
<script>
$(function() {
    <?php if(is_array($ckeditor)){
        foreach($ckeditor as $ck){
    ?>
        CKEDITOR.replace('<?php echo $ck; ?>');
    <?php
        }
    }else{
        ?>
        CKEDITOR.replace('<?php echo $ckeditor; ?>');
    <?php
    } ?>
    
});
</script>
<?php
    }
?>
<?php if(isset($iCheck)){
?>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url("themes/backend/plugins/iCheck/icheck.min.js"); ?>"></script>
<script>
//iCheck for checkbox and radio inputs
$('input[type="checkbox"], input[type="radio"]').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass   : 'iradio_minimal-blue'
});
</script>
<?php
    }
?>
<?php if(isset($timepicker)){
?>
<!-- bootstrap time picker -->
<script src="<?php echo base_url("themes/backend/plugins/timepicker/bootstrap-timepicker.min.js"); ?>"></script>
<script>
    $(document).ready(function(){
       //Timepicker
        $('.timepicker').timepicker({
          showInputs: false
        }) 
    });
</script>
<?php
    }
?>
<?php if(isset($timepair)){
?>
<!-- Date time paid Picker -->
<script src="<?php echo base_url("themes/backend/bower_components/datepair-picker/Datepair.js"); ?>"></script>
<script src="<?php echo base_url("themes/backend/bower_components/datepair-picker/jquery.datepair.js"); ?>"></script>
<script>
    $(document).ready(function(){
       //Timepicker
        $('#timePair .time').timepicker({
            'showDuration': true,
            'timeFormat': 'g:ia'
        });
        $('#timePair').datepair();
    });
</script>
<?php
    }
?>
<!-- DataTables -->
<script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js"); ?>"></script>
<script src="<?php echo base_url("themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"); ?>"></script>
<?php if(isset($datatable_export)){
    ?>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/dataTables.buttons.min.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/buttons.flash.min.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/jszip.min.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/pdfmake.min.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/vfs_fonts.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/buttons.html5.min.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/datatables.net/js/buttons.print.min.js"); ?>"></script>
    <script>
      $(function () {
        $('.datatable,.datatable_adv').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 
                {
                    extend: 'excel',
                    messageTop: null
                },
                {
                    extend: 'pdf',
                    messageBottom: null,
                    messageTop: null
                },
                {
                    extend: 'print',
                    messageTop: null,
                    messageBottom: null
                }
            ]
        } );
      })
    </script>
    <?php
}else{ ?>
    <script>
    var datatable;
      $(function () {
        datatable = $('.datatable').DataTable({ "aaSorting": [] })
        $('.datatable_adv').DataTable({
          'paging'      : true,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false
        })
      })
    </script>
<?php } ?>

<?php if(isset($status_js)){
?>
<script src="<?php echo base_url("themes/backend/bower_components/bootstrap-toggle/bootstrap-toggle.min.js"); ?>"></script>
<script>
$('.toggle-button').bootstrapToggle({
	on:"Lock",
	off:"UnLock"
});
function update_status(data_url,ele)
{
	sts=(ele.checked?1:0);
	$.ajax({
		url:data_url,
		data:{sts:sts},
		type:"post",
		success:function(res){
			
		}
	});
}
</script>
<?php
    }
?>
<?php if(isset($colorpickerjs)){
?>
<script src="<?php echo base_url("themes/backend/bower_components/colorpicker/js/colorpicker.js"); ?>"></script>
<script>
$(function(){
   $('.colorpickerjs').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
           
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        },
        onChange: function (hsb, hex, rgb, el) {
		      $(el).val(hex);
            
	    }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    }); 
});
</script>
<?php
    }
?>    

	<script>
      function deleteRecord(url,record)
	  {
	   if(confirm('<?php echo _l("Are you sure to delete..?"); ?>'))
       {
        	$.ajax({
				url:url,
				type:"get",
			}).done(function( data ) {
					$("#row_"+record).remove();
			});
       }		
	  }
      
    </script>
<?php if(isset($ajax_subcat)){
    ?>
    <script>
    $(function(){
        $("body").on("change","#category_id",function(){
           $.ajax({
                url:'<?php echo site_url("admin/subcategories/get_sub_category_by_category_id"); ?>',
                data:{category_id:$("#category_id").val()},
                type:"post",
                success:function(res){
                    res=jQuery.parseJSON(res);
                    $("#sub_category_id").empty();
                    $("#sub_category_id").append("<option value=''>Choose Sub Category</option>");
                    if(res.length>0)
                    {
                        $.each(res,function(ind,val){
                            $("#sub_category_id").append("<option value='"+val.sub_category_id+"'>"+val.sub_cat_name_nl+"</option>");	
                        });

                    }
                }
            }); 
        });
    });
    </script>
    <?php
}
    ?>
    
<?php if(isset($ajax_group)){
    ?>
    <script>
    $(function(){
        
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        $("body").on("change","#sub_category_id",function(){
           $.ajax({
                url:'<?php echo site_url("admin/productgroups/get_by_sub_category_id"); ?>',
                data:{sub_category_id:$("#sub_category_id").val()},
                type:"post",
                success:function(res){
                    res=jQuery.parseJSON(res);
                    $("#product_group_id").empty();
                    $("#product_group_id").append("<option value=''>Choose Group</option>");
                    if(res.length>0)
                    {
                        $.each(res,function(ind,val){
                            $("#product_group_id").append("<option value='"+val.product_group_id+"'>"+val.group_name_nl+"</option>");	
                        });

                    }
                }
            }); 
        });
        
        $("#add_product_groups").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url =  form.attr('action');
            var frmData = form.serializeArray();
            var count = $("#groups_list tr").length + 1;
            frmData.push( {name: 'count' , value: count });
            
            $.ajax({
                   type: "POST",
                   url: url,
                   data: frmData, // serializes the form's elements. form.serialize()
                   success: function(data)
                   {
                       $("#groups_list").prepend(data);
                       $("#category_id").val('').trigger('change');
                       $("#sub_category_id").html("");
                       $("#product_group_id").html("");
                       $("#addGroupModal").modal('toggle');
                   }
                 });


        });
    });
    </script>
    <?php
}
    ?>
<?php if(isset($from_to_timepicker)){
?>
<!-- bootstrap time picker -->
<script src="<?php echo base_url("themes/backend/bower_components/jquery-timepicker/jquery.timepicker.min.js"); ?>"></script>
<script>
    $(document).ready(function(){
       //Timepicker
        if($(".to_timepicker").val() == "")
            $(".to_timepicker").prop('disabled', true);
        else{
            $('.to_timepicker').timepicker({
            timeFormat: '<?php echo $this->config->item("time_formate_for_picker"); ?>',
            interval: <?php echo $this->config->item("time_interval_min"); ?>,
            minTime: '<?php echo $this->config->item("min_start_time"); ?>',
            maxTime: '<?php echo $this->config->item("max_end_time"); ?>',
            startTime: $('.to_timepicker').val(),
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            change: function(time) {
                // the input field
                var element = $(this), text;
                // get access to this Timepicker instance
                var timepicker = element.timepicker();
                text = 'Selected time is: ' + timepicker.format(time);
                element.siblings('span.help-line').text(text);
                var day = $(this).data("day");
                
            }
            }); 
        }
        $('.from_timepicker').timepicker({
            timeFormat: '<?php echo $this->config->item("time_formate_for_picker"); ?>',
            interval: <?php echo $this->config->item("time_interval_min"); ?>,
            minTime: '<?php echo $this->config->item("min_start_time"); ?>',
            maxTime: '<?php echo $this->config->item("max_end_time"); ?>',
            startTime: '<?php echo $this->config->item("min_start_time"); ?>',
            dynamic: false,
            dropdown: true,
            scrollbar: true,
            change: function(time) {
                // the input field
                var element = $(this), text;
                // get access to this Timepicker instance
                var timepicker = element.timepicker();
                text = 'Selected time is: ' + timepicker.format(time);
                element.siblings('span.help-line').text(text);
                var day = $(this).data("day");
                var to_time = $("[name='to_time["+day+"]']");
                $("[name='days["+day+"]']").prop("checked", true); 
                to_time.val(timepicker.format(time));
                to_time.prop('disabled', false);
                to_time.timepicker({
                    timeFormat: '<?php echo $this->config->item("time_formate_for_picker"); ?>',
                    interval: <?php echo $this->config->item("time_interval_min"); ?>,
                    minTime: '<?php echo $this->config->item("min_start_time"); ?>',
                    maxTime: '<?php echo $this->config->item("max_end_time"); ?>',
                    startTime: timepicker.format(time),
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });
            }
        }); 
        
    });
</script>
<?php
    }
?>
<?php
if(isset($daterangepicker)){
?>
    <script src="<?php echo base_url("themes/backend/bower_components/moment/min/moment.min.js"); ?>"></script>
    <script src="<?php echo base_url("themes/backend/bower_components/bootstrap-daterangepicker/daterangepicker.js"); ?>"></script>    

    <script>
    $('.daterangepicker_field').daterangepicker({ autoUpdateInput: false });
    $('.daterangepicker_field').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.daterangepicker_field').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
        /*
    $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' TO ' + picker.endDate.format('DD-MM-YYYY'));
    });

    $('#daterangepicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
        */
    </script>
<?php
    }
?>  
<?php
if(isset($productupload)){
    ?>
<script>
function deleteProductImage(id,key_id){
    $.ajax({
            type: "POST",
            url: "<?php echo site_url("admin/products/delete_image") ?>",
            data: { id : id },
            success: function(msg) {
                $("#product_image_"+key_id).remove();
            },
            error: function() {

            }
        });
}
$(function(){
    $("#upload_form input[type=file]").change(function(){
   
        var formData = new FormData($('#upload_form')[0]);
        formData.append('product_image', $('#upload_form input[type=file]')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url("admin/products/send_image") ?>",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {

            },
            success: function(msg) {
                $("#image_list").prepend(msg);
                $("#upload_form").trigger("reset");
            },
            error: function() {

            }
        });
    });
});
</script>
    <?php
}
?>

<?php if(isset($page_script)){ echo $page_script; }  ?>

</body>
</html>