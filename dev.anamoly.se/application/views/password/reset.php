<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo APP_NAME; ?> | <?php echo _l("Sign In"); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/font-awesome/css/font-awesome.min.css"); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/Ionicons/css/ionicons.min.css"); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/dist/css/AdminLTE.min.css"); ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url("themes/backend/plugins/iCheck/square/blue.css"); ?>">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:;"><b><?php echo APP_NAME; ?></b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Reset Password</p>
    <?php
        echo _get_flash_message();
        if(!$success){
            echo form_open();
            echo _input_field("password",_l("Password"),'','password',array("placeholder"=>_l("Password"),"data-validation"=>"required"));
            echo _input_field("repeat_password",_l("Repeat Password"),'','password',array("placeholder"=>_l("Repeat Password"),"data-validation"=>"confirmation","data-validation-confirm"=>"password"));
        
            echo _submit_button(_l("Reset"));
            echo form_close();
        }   
    ?>
    <div class="clearfix"></div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url("themes/backend/bower_components/jquery/dist/jquery.min.js"); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url("themes/backend/bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
<!-- iCheck -->
<script src="<?php echo base_url("themes/backend/plugins/iCheck/icheck.min.js"); ?>"></script>
<!-- Form Validation -->
<script src="<?php echo base_url("themes/backend/bower_components/form-validator/jquery.form-validator.min.js"); ?>"></script>
<script>
  $(function () {
    $.validate({  modules : 'security' });
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
