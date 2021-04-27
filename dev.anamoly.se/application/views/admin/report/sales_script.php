 <script src="<?php echo base_url("themes/backend/bower_components/chart.js/Chart.js"); ?>" ></script>
 
 <script>
 
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
          fillColor           : '<?php echo $item["fillColor"]; ?>',
          strokeColor         : '<?php echo $item["strokeColor"]; ?>',  
          data                : <?php echo $item["data"]; ?>
        },                          
                                        
                                        <?php }
        } ?>        
      ]
    }
 
   var barChartCanvas                   = $('#saleChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = areaChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 1,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : false
    }

    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
</script>