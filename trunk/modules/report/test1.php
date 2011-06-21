<?php
$query =mysql_query("select
               date_format(connect_time,'%Y-%m-%d %H %i') AS date,
               Customers.name as customer,
               Sum(duration) as secondes
               from CDR_Vendors
               inner join Customers on (CDR_Vendors.i_customer = Customers.i_customer)
               where
               i_vendor='32'
               and
               connect_time between '2010-09-01 00:00:00' and '2010-09-01 00:10:00'
               group by date
               ORDER BY date", $link) or die(mysql_error());
$row = mysql_fetch_assoc($query);
$customer[] = $row['customer'];
$json_secondes = array();
$json_date = array();
do{
$secondes[] = $row['secondes'];
array_push($json_secondes, $row['secondes']);
array_push($json_date, $row['date']);
}
while($row = mysql_fetch_assoc($query));
//echo json_encode($json_secondes,$row);
//echo json_encode($json_date,$row);
//echo join($secondes, ', ');
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>Highcharts Example</title>


      <!-- 1. Add these JavaScript inclusions in the head of your page -->
      <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
      <script type="text/javascript" src="../js/highcharts.js"></script>

      <!-- 1a) Optional: the exporting module -->
      <script type="text/javascript" src="../js/modules/exporting.js"></script>


      <!-- 2. Add the JavaScript to initialize the chart on document ready -->
      <script type="text/javascript">

         var chart;
         $(document).ready(function() {
            chart = new Highcharts.Chart({
               chart: {
                  renderTo: 'container',
                  defaultSeriesType: 'column'
               },
               title: {
                  text: 'Monthly Average Rainfall'
               },
               subtitle: {
                  text: 'Source: WorldClimate.com'
               },
               xAxis: {
                  categories: <?php echo json_encode($json_date,$row);?>
               },
               yAxis: {
                  min: 0,
                  title: {
                     text: 'Rainfall (mm)'
                  }
               },
               legend: {
                  layout: 'vertical',
                  backgroundColor: '#FFFFFF',
                  align: 'center',
                  verticalAlign: 'top',
                  x: 100,
                  y: 70
               },
               tooltip: {
                  formatter: function() {
                     return ''+
                        this.x +': '+ this.y +' Min';
                  }
               },
               plotOptions: {
                  column: {
                     pointPadding: 0.2,
                     borderWidth: 0
                  }
               },
                    series: [{
                  name: '<?php echo join($customer, ', ');?>',
                  data: [<?php echo join($secondes, ', ');?>]

               }]
            });


         });

      </script>

   </head>
   <body>

      <!-- 3. Add the container -->
      <div id="container" style="width: 1300px; height: 500px; margin: 0 auto"></div>


   </body>
</html>