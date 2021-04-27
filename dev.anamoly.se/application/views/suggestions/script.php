<script>
    function acceptRecord(url,record)
	{
        $.ajax({
				url:url,
				type:"get",
			}).done(function( data ) {
					$("#row_"+record).remove();
		});
    }
</script>