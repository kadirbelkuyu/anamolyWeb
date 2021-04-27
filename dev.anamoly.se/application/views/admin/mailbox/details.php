<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?php echo base_url("themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>">
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


            <!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="mailbox-read-info">
                 
                 <h3><?php echo $data["subject"]; ?></h3>
                 <h5><?php echo _l("From:"); ?> <?php echo $data["from"]["email"]." ".$data["from"]["name"] ?>
                   <span class="mailbox-read-time pull-right"><?php echo date(DEFAULT_DATE_TIME_FORMATE,$data["date"]); ?></span></h5>
               </div>
              <!--<iframe src="<?php echo site_url("admin/mailbox/iframe/$uid/$mailbox"); ?>" width="100%" height="500px" allowfullscreen="true"  style="border:none;"></iframe>-->
              <!-- /.mailbox-read-message -->
              <div id="contentHtml">
              <div class="mailbox-read-message">
                <?php 
                if (isset($data["body"]["text/html"])) {
                    echo $data["body"]["text/html"];
                }else if($data["body"]["text/plain"]){
                    echo $data["body"]["text/plain"];
                } ?>
              </div>
              
              </div>
            </div>
            
         
     
        <div class="box-footer">
          <!-- /.mailbox-read-message -->
          <ul class="mailbox-attachments clearfix">
                <?php if(!empty($data["attachment"])){
                    $index = 0;
                    foreach($data["attachment"] as $filename=>$attachment){
                        
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        
                            ?>
                            <li>
                                <div class="mailbox-attachment-info">
                                    <a href="<?php echo site_url("admin/mailbox/filedownload/"._encrypt_val($filename)) ?>" class="mailbox-attachment-name" target="_blank" ><i class="fa fa-paperclip"></i> <?php echo $filename; ?></a>
                                        <span class="mailbox-attachment-size">
                                        
                                        <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                        <div class="clearfix"></div>
                                        </span>
                                </div>
                                </li>
                            <?php
                        $index++;
                    }

                }
                 ?>
                 </ul>
        </div>
      