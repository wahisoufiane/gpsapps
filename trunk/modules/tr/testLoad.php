<html>
<head>
<!-- For ease i'm just using a JQuery version hosted by JQuery- you can download any version and link to it locally -->
<script src="js/jquery-1.3.2.min.js"></script>
<script>
 $(document).ready(function() {
 	 $("#responsecontainer").load("ViewTracker.php");
   var refreshId = setInterval(function() {
      $("#responsecontainer").load('ViewTracker.php?randval='+ Math.random());
   }, 9000);
});
</script>
</head>
<body>
 
<div id="responsecontainer">
</div>
</body>