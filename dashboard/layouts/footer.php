    <!-- Essential javascripts for application to work-->
    <script src="<?php echo $root_path ?>/dashboard/assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo $root_path ?>/dashboard/assets/js/popper.min.js"></script>
    <script src="<?php echo $root_path ?>/dashboard/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $root_path ?>/dashboard/assets/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?php echo $root_path ?>/dashboard/assets/js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="<?php echo $root_path ?>/dashboard/assets/js/plugins/chart.js"></script>

    <!-- Untuk lihat bukti pembayaran-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if(isset($chartJs)): ?>
    <script type="text/javascript">
      var pdata = [
        <?php
        $color = array('#F7464A','#17a2b8','#009688','#222d32');
        $highlight = array('#FF46FF','#FFa2bF','#FF968F','#FF2d32');
        foreach($chartProduct as $index => $charts): 
          ?>
      	{
          
      		value: <?php echo $charts['countP'] ?>,
      		color: "<?php echo $color[$index] ?>",
      		highlight: "<?php echo $highlight[$index] ?>",
      		label: "<?php echo $charts['name'] ?>"
      	},
        <?php
        
        endforeach ?>
      	// {
      	// 	value: 6,
      	// 	color:"#F7464A",
      	// 	highlight: "#FF5A5E",
      	// 	label: "In-Progress"
      	// }
      ]
      
      
      var ctxp = $("#pieChartDemo1").get(0).getContext("2d");
      var pieChart = new Chart(ctxp).Pie(pdata);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
            var ctx = document.getElementById('revenueChart').getContext('2d');
            var revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: '2020',
                        data: [12000, 15000, 18000, 20000, 25000, 30000, 35000, 32000, 37000, 40000, 45000, 50000],
                        borderColor: 'blue',
                        fill: false
                    }, {
                        label: '2021',
                        data: [10000, 13000, 14000, 16000, 22000, 29000, 34000, 33000, 38000, 42000, 47000, 49000],
                        borderColor: 'red',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Revenue'
                            }
                        }
                    }
                }
            });
        </script>
    <?php endif ?>

  </body>
</html>