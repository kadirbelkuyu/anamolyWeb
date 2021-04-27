<html>
  <head>
    <!-- your head definitions -->
  </head>
  <body>
    <!-- your own html code -->
    <div id="checkout-container"></div>
    <form id="paynow" action="<?php echo $url; ?>" method="<?php echo $method; ?>">
        <?php
        if(!empty($fields)){
            foreach($fields as $key=>$field){
                echo "<input type='hidden' name='$key' value='$field'>";
            }
        }
        ?>
    </form>
    <!-- your own html code -->
  </body>
  <script>
    window.onload = function(){
  document.forms['paynow'].submit();
}
  </script>
</html>
