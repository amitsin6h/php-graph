<!-- posting data-->
<?php
if (isset($_POST['submitted'])){
	
	include('db_connect.php');
	
	$xaxis = $_POST['xaxis'];
	$yaxis = $_POST['yaxis'];
	$sqlinsert = "INSERT INTO graph (x_axis, y_axis) VALUES ('$xaxis', '$yaxis')";
	
	if(!mysqli_query($dbcon, $sqlinsert)){
		die ("Error submitting into db!!");
		}
	$newrecord = 'submitted!!';
}
?>


<!--retrive data-->
<?php 

include_once 'db_connect.php';
$data_points = array();
$result = mysqli_query($dbcon,"SELECT * FROM graph");
    while($row = mysqli_fetch_array($result))
    {        
        $point = array("x" => $row['x_axis'] , "y" => $row['y_axis']);
        array_push($data_points, $point);        
    }
    
    $json = json_encode($data_points, JSON_NUMERIC_CHECK);

mysqli_close($dbcon);

?>



<!DOCTYPE HTML>
<html>

<head>
<title>PHP Graph | CanvasJS</title>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.canvasjs.min.js"></script>
<script type="text/javascript">
$(function () {
	//Better to construct options first and then pass it as a parameter
	var options = {
		title: {
			text: "Graph Data"
		},
                animationEnabled: true,
		data: [
		{
			type: "spline", //change it to line, area, column, pie, etc
			dataPoints: <?php echo json_encode($data_points, JSON_NUMERIC_CHECK);?>
				
		}
		]
	};

	$("#chartContainer").CanvasJSChart(options);

});
</script>
</head>

<body style="background-color:#2c3e50;">
	<form action="index.php" method="post" style="text-align:center">
	<input type="hidden" name="submitted" value="true"/>
	<label style="color: #fff;">x-axis: </label><input type="number" name="xaxis" /><br>
	<label style="color: #fff;">y-axis: </label><input type="number" name="yaxis" /><br>
	<input type="submit" value="submit">
	</form>
<p style="color:#fff;"><span style="color:#e67e22">Data in JSON format: </span><span style="color:#f1c40f"><?php echo $json ?></span></p>
	<div class="space" style="padding:120px;"></div>
	<div id="chartContainer" style="height: 300px; width: 100%;"></div>
</body>

</html>
