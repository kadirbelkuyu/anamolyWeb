<!-- Main content -->
<section class="content">
<div class="box">
        <div class="box-body">
        <?php
            
            echo "<div class=''>";
            echo "<form method='post' action=''>";
            
            echo _input_field("date_range", _l("Date Range"), _get_post_back($field,'date_range'), 'text', array(),array(),"col-md-3","daterangepicker_field");
            
            echo '<div class="col-md-3" ><button type="submit" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button>';
          
            echo '</div>';
            
            echo "</form>";
            echo "</div>";    
        ?>
        </div>
</div>

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Reports"); ?> / <?php echo _l("VAT"); ?></h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-responsive">
                <tr>
                    <th><?php echo _l("VAT %"); ?></th>
                    <th><?php echo _l("TOTAL AMOUNT INC. TAX"); ?></th>
                    <th><?php echo _l("TOTAL AMOUNT EXCL. TAX"); ?></th>
                    <th><?php echo _l("TOTAL TAX"); ?></th>
                </tr>
                <?php 
                    foreach($data as $dt){
                        ?>
                        <tr>
                            <td><?php echo $dt->vat; ?></td>
                            <td><?php echo $dt->totla_amount; ?></td>
                            <td><?php echo $dt->totla_amount - abs($dt->vat_amount); ?></td>
                            <td><?php echo abs($dt->vat_amount); ?></td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </div>
    </div>
</section>