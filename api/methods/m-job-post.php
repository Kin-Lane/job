<?php
$route = '/job/';
$app->post($route, function () use ($app){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['post_date'])){ $post_date = mysql_real_escape_string($params['post_date']); } else { $post_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['author'])){ $author = mysql_real_escape_string($params['author']); } else { $author = ''; }
	if(isset($params['body'])){ $body = mysql_real_escape_string($params['body']); } else { $body = ''; }

  	$Query = "SELECT * FROM job WHERE Title = '" . $title . "' AND Author = '" . $author . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$ThisJob = mysql_fetch_assoc($Database);
		$job_id = $ThisJob['ID'];
		}
	else
		{
		$Query = "INSERT INTO job(Post_Date,Title,Author,Summary,Body,Footer)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($post_date) . "',";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($author) . "',";
		$Query .= "'" . mysql_real_escape_string($body) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$job_id = mysql_insert_id();
		}

	$host = $_SERVER['HTTP_HOST'];
   $job_id = prepareIdOut($job_id,$host);

	$ReturnObject['job_id'] = $job_id;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
