<script>
    $(document).ready(function(){
        $.ajax({
                url: '<?php echo site_url("admin/mailbox/iframe/$uid?mailbox=$mailbox"); ?>',
                type: 'post',
                
                success: function (data) {
                    $("#contentHtml").html(data);
                }
            });
        
    });
</script>
