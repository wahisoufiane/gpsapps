<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>
		
		
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/highcharts.js"></script>
		
		<!-- 1a) Optional: the exporting module -->
		<script type="text/javascript" src="js/modules/exporting.js"></script>
		
		
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<script type="text/javascript">
		function loadSpeedGraph(speedData) 
		{
			var aData = new Array();
				aData = [speedData];
			alert((aData));
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						zoomType: 'x'
					},
				        title: {
						text: 'Speed Graph'
					},
				        subtitle: {
						text: 'Click and drag in the plot area to zoom in'
					},
					xAxis: {
						type: 'Time',
						title: {
							text: null
						}
					},
					yAxis: {
						title: {
							text: 'Kmph'
						},
						min: 0,
						startOnTick: false,
						showFirstLabel: false
					},
					tooltip: {
						formatter: function() {
							return ''+
								'Speed = '+ this.y +' km';
						}
					},
					legend: {
						enabled: false
					},
					plotOptions: {
						area: {
							fillColor: {
								linearGradient: [0, 0, 0, 300],
								stops: [
									[0, '#4572A7'],
									[1, 'rgba(2,0,0,0)']
								]
							},
							lineWidth: 1,
							marker: {
								enabled: false,
								states: {
									hover: {
										enabled: true,
										radius: 5
									}
								}
							},
							shadow: false,
							states: {
								hover: {
									lineWidth: 1						
								}
							}
						}
					},
				
					series: [{
						type: 'area',
						name: 'Speed',
						pointInterval: 2,
						pointStart: 0,
						data: aData
					}]
				});
				
				
			});
		}
		loadSpeedGraph("1,2,15,15,23,20,2,2,3,46,21,41,32,18,9,27,21,1,27,17,39,1,10,19,34,23,14,2,2,1") ;
		
		</script>
		
	</head>
	<body>
		
		<!-- 3. Add the container -->
		<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>
		
				
	</body>
</html>
