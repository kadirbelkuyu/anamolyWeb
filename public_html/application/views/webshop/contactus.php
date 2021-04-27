<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("How may I help you?"); ?></h5>
               <p><?php echo _l("Feel free to contact us if need any help or support"); ?></p>
            </div>

             <?php echo form_open();

            echo _input_field("fullname","", _get_post_back($field,'fullname'), 'text', array("required"=>"","maxlength"=>255,"placeholder"=>_l("Full Name")),array(),"s4");
			      echo _input_field("phoneno", "", _get_post_back($field,'phoneno'), 'text', array("data-validation" =>"number","maxlength"=>50,"placeholder"=>_l("Phone No.")),array(),"s4");
            echo _textarea("content","",_get_post_back($field,'content'),array("required"=>"","placeholder"=>_l("Write your message here..."),"style"=>"height:120px;"),array(),"s4");

            echo "<div class='clearfix'></div>";

                echo '<div class="s12">
				<br>
				<button type="submit" class="btn btn-primary btn-flat">'._l("Send").'</button>';

			echo '</div>';
            echo form_close();
            ?>

        </div>
    </div>
</div>
