<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="row">
    <div class="col-md-3">
          <a href="javascript:;" id="btnCompose" class="btn btn-primary btn-block margin-bottom"><i class="fa fa-send"></i> <label id="lblBtnCompose"><?php echo _l("Compose"); ?></label></a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button"  class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
               
              <ul class="nav nav-pills nav-stacked">
                  <?php foreach($mailBoxes as $mBox){
                      $icon = "fa-folder";
                      if ($mBox == "INBOX") {
                        $icon = "fa-inbox";
                        }else if (strpos($mBox, 'Sent')) {
                            $icon = "fa-envelope-o";
                      }else if (strpos($mBox, 'Drafts')) {
                        $icon = "fa-file-text-o";
                      }else if (strpos($mBox, 'Spam')) {
                        $icon = "fa-filter";
                      }else if (strpos($mBox, 'Deleted')) {
                        $icon = "fa-trash-o";
                      }
                      ?>
                      <li class="<?php if($active_mailbox == $mBox) { echo "active"; } ?>"><a href="<?php echo site_url("admin/mailbox/inbox"); ?>?folder=<?php echo $mBox; ?>"><i class="fa <?php echo $icon; ?>"></i><?php echo str_replace("INBOX/","",$mBox); ?></a></li>
                      <?php
                  } ?>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          
          <!-- /.box -->
        </div>
        <div class="col-md-9">
        <div class="box">
        <div class="box-body">
        <div class="">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><a href="<?php site_url("mailbox/inbox?folder=$mailbox")?>"><i class="fa fa-lastfm"></i></a> Mail</h3>

              <div class="box-tools pull-right">
                <!--<a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>-->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
            <div class="mailbox-read-info">
                 
                 <h3><?php echo $data["subject"]; ?></h3>
                 <h5><?php echo _l("From:"); ?> <?php echo $data["from"]["email"]." ".$data["from"]["name"] ?>
                   <span class="mailbox-read-time pull-right"><?php echo date(DEFAULT_DATE_TIME_FORMATE,$data["date"]); ?></span></h5>
               </div>
              <!--<iframe src="<?php echo site_url("admin/mailbox/iframe/$uid/$mailbox"); ?>" width="100%" height="500px" allowfullscreen="true"  style="border:none;"></iframe>-->
              <!-- /.mailbox-read-message -->
              <div id="contentHtml"></div>
            </div>
            
          </div>
          <!-- /. box -->
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
        </div>
        </div>
    </div>

    
</section>