 <script src="<?php echo base_url("themes/backend/bower_components/chart.js/Chart.js"); ?>" ></script>
 
<script>
    var areaChartCanvas =document.getElementById("userChart").getContext("2d");

    var areaChartData = {
      labels  : [<?php echo $chart_label; ?>],
       datasets: 
      [
      <?php
        if (!empty($chart_array)) {
            foreach ($chart_array as $item) {
        ?>       
              {
          label               : '<?php echo $item["label"]; ?>',
          backgroundColor     : '<?php echo $item["backgroundColor"]; ?>',
          borderColor         : '<?php echo $item["borderColor"]; ?>',
          pointRadius         : <?php echo $item["pointRadius"]; ?>,
          pointColor          : '<?php echo $item["pointColor"]; ?>',
          pointStrokeColor    : '<?php echo $item["pointStrokeColor"]; ?>',
          pointHighlightFill  : '<?php echo $item["pointHighlightFill"]; ?>',
          pointHighlightStroke: '<?php echo $item["pointHighlightStroke"]; ?>',
          data                : <?php echo $item["data"]; ?>
        },                          
                                        
                                        <?php }
        } ?>        
      ]
    }

 var areaChart=new Chart(areaChartCanvas).Line(areaChartData, {
			responsive: true,
      maintainAspectRatio : false,
		});
</script>