<script>

$(document).ready(function(){
    $("#suggesstion-box").html("");
	$("#search").keyup(function(){
		$.ajax({
		type: "POST",
		url: "<?php echo site_url("category/search"); ?>",
		data:'keyword='+$(this).val(),	
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);			
		}
		});
	});
});

</script>
