<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Suggestions"); ?> / <?php echo _l("List"); ?></h3>

            
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <thead>
                <tr>
					<th width="110"><?php echo _l("Image"); ?></th>
					<th><?php echo _l("Suggestion"); ?></th>
                    <th><?php echo _l("User"); ?></th>
					<th width='90'><?php echo _l("Action"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
				$count = 0;
                foreach($data as $dt){
					$count++;	
                    ?>
                    <tr id="row_<?php echo $count; ?>">
						<td><?php if ($dt->image!="" && file_exists(SUGGESTION_IMAGE_PATH."/crop/small/".$dt->image)){ ?>
						<img class="profileImage" src="<?php if(isset($dt->image) && $dt->image != ""){ echo base_url(SUGGESTION_IMAGE_PATH."/crop/small/".$dt->image); } ?>" alt="<?php echo _l("Preview"); ?>" height="100"/>
						<?php } ?>
						</td>
                        <td><?php echo $dt->suggestion; ?></td>
                        <td><?php echo $dt->user_firstname." ".$dt->user_lastname; ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="javascript:acceptRecord('<?php echo site_url($controller."/accept/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)" class="btn <?php echo ($dt->status == 0) ? "btn-default" : "btn-success"; ?>  btn-xs"> <?php echo ($dt->status == 0)? _l("Accpet") : _l("Accpeted"); ?></a>
								<?php if(_is_admin()){ ?>
                                <a href="javascript:deleteRecord('<?php echo site_url($controller."/delete/"._encrypt_val($dt->$primary_key)); ?>',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
								<?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box -->
</section>
