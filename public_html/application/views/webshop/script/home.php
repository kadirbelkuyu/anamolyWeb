<script>
	<?php
		$encode_data = json_encode($categories);
	?>
	var categories = <?php echo $encode_data; ?>;

	var sub_categories = categories[0];
    <?php if(isset($_GET["cat"])){
		?>
		var selected_cat_id = <?php echo $_GET["cat"]; ?>;
        var left_pad = 0;
		for(subcategories in categories){

			if(categories[subcategories].category_id == selected_cat_id){
				 sub_categories = categories[subcategories];
				 var letf_pos =  $('#'+sub_categories.category_id).offset().left ;
			     letf_pos = letf_pos - $( ".hr_scroll" ).offset().left + $( ".hr_scroll" ).scrollLeft();
				 $( ".hr_scroll" ).scrollLeft(letf_pos );
			}
			left_pad = left_pad + 10;
		}

		<?php
	} ?>
	$('#'+sub_categories.category_id).removeClass('red').addClass('blue');
	var p = sub_categories.subcategories;

		var html = '';
		for(x in p){
			var layout = '';
				layout += '<a href="';
				layout += '<?php echo site_url();?>/products/' + p[x].category_id + '/' + p[x].sub_category_id +'">';
				layout += '<div class="col s4 m4 l3 sub-category">';
				layout += '<div class="cat-card ">';
				layout += '<div class="cat-card-image" style="background-image : url(\''
				layout += '<?php echo REMOTE_URL."/uploads/categories/"?>';
				layout += p[x].sub_cat_image;
				layout += '\');">';
				layout +='</div>';
				layout += '<div class="card-content"><p class="product-title">';
				<?php if(_language() == "english"){?>
					layout += p[x].sub_cat_name_en;
				<?php }else{ ?>
					layout += p[x].sub_cat_name_nl;
				<?php } ?>
				layout += '</p></div></div></div>';
				layout += '</a>';
			html += layout;

	}
	document.getElementById("demo").innerHTML = html ;

    $('.sub-cat').click(function(event) {
		$('.sub-cat').removeClass('blue').addClass('red');
		var status = $(this).attr('id');
		$('#'+ status).removeClass('red').addClass('blue');


		for(subcategories in categories){
			if(categories[subcategories].category_id === status){
				var sub_categories = categories[subcategories];
			}
		}
		var p = sub_categories.subcategories;
		var html = '';
		for(x in p){
			console.log(p[x].sub_category_id);
			var layout = '';
				layout += '<a href="';
				layout += '<?php echo site_url();?>/products/' + p[x].category_id + '/' + p[x].sub_category_id +'">';
				layout += '<div class="col s4 m4 l3 ">';
				layout += '<div class="sub-category ">';
				layout += '<div class="cat-card-image" id="" style="background-image : url(\''
				layout += '<?php echo REMOTE_URL."/uploads/categories/"?>';
				layout += p[x].sub_cat_image;
				layout +='\');">';
				layout +='</div>';
				layout += '<div class="card-content"><p class="product-title" >';
				<?php if(_language() == "english"){?>
					layout += p[x].sub_cat_name_en;
				<?php }else{ ?>
					layout += p[x].sub_cat_name_nl;
				<?php } ?>
				layout += '</p></div></div></div></a>';
			html += layout;

		}
		document.getElementById("demo").innerHTML = html ;
	});
	$(".hr_scroll").mousewheel(function(event, delta) {

      this.scrollLeft -= (delta * 30);
    
      event.preventDefault();

   });
</script>
