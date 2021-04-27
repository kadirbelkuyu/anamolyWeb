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
                  <?php 
                  
                  foreach($mailBox as $mBox){
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
                      <li class="<?php if($active_mailbox == $mBox) { echo "active"; } ?>"><a href="?folder=<?php echo $mBox; ?>"><i class="fa <?php echo $icon; ?>"></i><?php echo str_replace("INBOX/","",$mBox); ?></a></li>
                      <?php
                  } ?>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          
          <!-- /.box -->
        </div>
        <div class="col-md-9" id="inboxList">
        <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Mail"); ?> / <?php echo _l(str_replace("INBOX/","",$active_mailbox)); ?></h3>

            <div class="box-tools pull-right">
                
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            
            <div class="col-md-12">
            <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped" id="tblEmailList">
                <tbody>
                <?php
                $count = 0;
                foreach($data as $dt){
                    $count++;
                    ?>
                    <tr id="row_<?php echo $count; ?>">
                        <td class="mailbox-star"><a href="javascript:readEmail('<?php echo $dt["uid"]; ?>','<?php echo $dt["mailbox"]; ?>')"><i class="fa fa-envelope text-yellow"></i></a></td>
                        <td class="mailbox-name"><a href="javascript:readEmail('<?php echo $dt["uid"]; ?>','<?php echo $dt["mailbox"]; ?>')"><?php echo (!empty($dt["from"]["name"])) ? $dt["from"]["name"] : $dt["from"]["email"];?></a></td>
                        <td class="mailbox-subject"><?php echo $dt["subject"]; ?>
                        </td>
                        <td class="mailbox-attachment"><?php if(!empty($dt["attachment"])) { echo '<i class="fa fa-paperclip"></i>'; } ?></td>
                        <td class="mailbox-date"><?php echo date(DEFAULT_DATE_TIME_FORMATE,$dt["date"]); ?></td>
                        <td><a href="javascript:deleteEmail('<?php echo $dt["uid"]; ?>','<?php echo $count; ?>')"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
            <div class="clearfix"></div>
            <?php echo $page_links; ?>
            </div>
            </div>
        </div>
    </div>
        </div>
        <div class="col-md-9 hide" id="readEmail">

          <div class="box box-primary">
            <div class="box-header with-border">
              <button class="btn btn-default" onclick="backFromDetails()"><?php echo _l("Back"); ?></button>
              <div class="clearfix"></div>
            </div>
            <div id="emailBodyHtml">

            </div>
          </div>
        </div>

        <!-- /.col -->
        <div class="col-md-9 hide" id="composeView">
          <div id="messageContainer"></div>
          <form id="composeForm" action="<?php echo site_url("admin/mailbox/send"); ?>" method="post" enctype="multipart/form-data">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                <input class="form-control" name="to" id="toEmail" placeholder="To:">
              </div>
              <div class="form-group">
                <input class="form-control" name="subject" placeholder="Subject:">
              </div>
              <div class="form-group">
                    <textarea id="compose-textarea" name="body" class="form-control" style="height: 300px">
                      
                    </textarea>
              </div>
              <div class="form-group">
              <i class="fa fa-paperclip"></i> Attachment
                <div class="btn btn-default">
                  
                  <input type="file" name="attachment" multiple>
                </div>
                <p class="help-block">Max. 20MB</p>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
          </form>
        </div>

    </div>
    
    <!-- /.box -->
</section>
