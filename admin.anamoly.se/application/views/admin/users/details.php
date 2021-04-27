<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            
            <h3 class="box-title"><?php echo _l("User"); ?> / <?php echo _l("Details"); ?></h3>
                <div class="box-tools pull-right">  
                 <?php 
                $backurl=site_url($controller);
                if(!empty($this->session->userdata("backurl")))
                {
                    $backurl=$this->session->userdata('backurl');
                }
                ?>            
				<a href="<?php echo $backurl?>" class="btn btn-box-tool btn-bitbucket"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a>
            </div>
        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            <table id="example1" class="table table-bordered table-striped datatable">
                <tr>
					<th width="200px"><?php echo _l("Full Name"); ?></th><td><?php echo $data->user_firstname." ".$data->user_lastname; ?></td>
                </tr>
                <tr>
					<th><?php echo _l("Email ID"); ?></th><td><?php echo $data->user_email; ?></td>
                </tr>
                <tr>
                    <th><?php echo _l("Phone"); ?></th><td><?php echo $data->user_phone; ?></td>
                </tr>
                
                
                    <?php if($data->user_type_id == USER_COMPANY){
                        ?>
                        <tr>
                        <th><?php echo _l("Company"); ?></th><td><?php echo $data->user_company_name; ?></td>
                        </tr>
                        <tr>
                        <th><?php echo _l("Company ID"); ?></th><td><?php echo $data->user_company_id; ?></td>
                        </tr>
                        <?php
                    } ?>
            </table>
            <?php if(isset($address)){ ?>
            <h3><?php echo _l("Address"); ?></h3>
            <table id="example1" class="table table-bordered table-striped datatable">
                 <tr>
                    <td>
                    <?php echo $address->street_name; ?> <?php echo $address->house_no; ?> <?php echo $address->add_on_house_no; ?>
                    <br />
                    <?php echo $address->postal_code; ?>
                    <br />
                    <?php echo $address->city; ?>
                    </td>
                </tr>               
            </table>
            <?php }; ?>
            
             <h3><?php echo _l("Registration"); ?></h3>
            <table id="example1" class="table table-bordered table-striped datatable">
                 <tr>
                    <th width="200px"><?php echo _l("Registration date"); ?></th><td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($data->registration_date)); ?></td>
                </tr>
                <tr>
                    <th><?php echo _l("Activation date"); ?></th><td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($data->activation_date)); ?></td>
                </tr>
                  <tr>
                    <th><?php echo _l("Date last login"); ?></th><td><?php echo date(DEFAULT_DATE_TIME_FORMATE,strtotime($data->login_date)); ?></td>
                </tr>               
            </table>
        </div>
    </div>
    <!-- /.box -->
</section>
