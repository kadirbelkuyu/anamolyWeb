<script>
    function accpetWaiting(url,record){
        $.ajax({
            url:url,
            data:{},
            type:"post",
            success:function(res){
                $( "#row_"+record ).hide( "slow", function() {
                    $("#row_"+record).remove();
                    $("#messages").prepend(res);
                });
                
            }
	    });
    }
</script>