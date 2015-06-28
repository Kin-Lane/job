<?php		
$route = '/job/:job_id/';
$app->delete($route, function ($job_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$job_id = prepareIdIn($job_id,$host);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM job WHERE ID = " . $job_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
