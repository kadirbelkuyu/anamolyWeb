<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("App Settings"); ?></h5>
               <p><?php echo _l("You may manage settings of notifications and emails"); ?></p>
            </div>

             <?php echo form_open();

            echo "<div class='s12'>";
            echo _checkbox("general_notifications",_l("General Push Notifications"),"",array(),(isset($field) && isset($field->general_notifications) && $field->general_notifications == 1) ? true : false);
            echo "</div>";
            echo "<div class='clearfix'></div>";

            echo "<div class='s12'>";
            echo _checkbox("order_notifications",_l("Push Notification On Orders"),"",array(),(isset($field) && isset($field->order_notifications) && $field->order_notifications == 1) ? true : false);
            echo "</div>";
            echo "<div class='clearfix'></div>";

            echo "<div class='s12'>";
            echo _checkbox("general_emails",_l("General Emails"),"",array(),(isset($field) && isset($field->general_emails) && $field->general_emails == 1) ? true : false);
            echo "</div>";
            echo "<div class='clearfix'></div>";

            echo "<div class='s12'>";
            echo _checkbox("order_emails",_l("Emails On Orders"),"",array(),(isset($field) && isset($field->order_emails) && $field->order_emails == 1) ? true : false);
            echo "</div>";
            echo "<div class='clearfix'></div>";

            echo form_close();
            ?>

        </div>
    </div>
</div>
