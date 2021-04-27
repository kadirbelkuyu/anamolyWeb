<script>
        $("#user_type_id").change(function(){
            var val = $(this).val();
            if(val == <?php echo USER_COMPANY ?>){
                $(".company_data").removeClass("hide");
            }else{
                $(".company_data").addClass("hide");
            }
        });
</script>