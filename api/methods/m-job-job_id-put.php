<?php
$route = '/blog/:blog_id/';
$app->put($route, function ($blog_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$blog_id = prepareIdIn($blog_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['post_date'])){ $post_date = mysql_real_escape_string($params['post_date']); } else { $post_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = ''; }
	if(isset($params['author'])){ $author = mysql_real_escape_string($params['author']); } else { $author = ''; }
	if(isset($params['summary'])){ $summary = mysql_real_escape_string($params['summary']); } else { $summary = ''; }
	if(isset($params['body'])){ $body = mysql_real_escape_string($params['body']); } else { $body = ''; }
	if(isset($params['build_page'])){ $build_page = mysql_real_escape_string($params['build_page']); } else { $build_page = ''; }
	if(isset($params['github_build'])){ $github_build = mysql_real_escape_string($params['github_build']); } else { $github_build = ''; }
	if(isset($params['twitter_build'])){ $twitter_build = mysql_real_escape_string($params['twitter_build']); } else { $twitter_build = ''; }
	if(isset($params['linkedin_build'])){ $linkedin_build = mysql_real_escape_string($params['linkedin_build']); } else { $linkedin_build = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  	$Query = "SELECT * FROM blog WHERE ID = " . $blog_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{

		$query = "UPDATE blog SET";

		$query .= " Flag = 1";

		if($title!='') { $title .= ", Title = '" . mysql_real_escape_string($title) . "'"; }
		if($body!='') { $query .= ", Body = '" . $body . "'"; }
		if($author!='') { $query .= ", Author = '" . $author . "'"; }
		if($summary!='') { $query .= ", Summary = '" . $summary . "'"; }
		if($body!='') { $query .= ", Body = '" . $body . "'"; }

		if($build_page!='') { $query .= ", Build_Page = '" . mysql_real_escape_string($build_page) . "'"; }
		if($github_build!='') { $query .= ", Github_Build = '" . mysql_real_escape_string($github_build) . "'"; }
		if($twitter_build!='') { $query .= ", Twitter_Build = '" . mysql_real_escape_string($twitter_build) . "'"; }
		if($linkedin_build!='') { $query .= ", LinkedIn_Build = '" . mysql_real_escape_string($linkedin_build) . "'"; }

		if($footer!='') { $query .= ", Footer = '" . $footer . "'"; }

		$query .= " WHERE ID = " . $blog_id;

		echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$blog_id = prepareIdOut($blog_id,$host);

	$F = array();
	$F['blog_id'] = $blog_id;
	$F['post_date'] = $post_date;
	$F['title'] = $title;
	$F['author'] = $author;
	$F['summary'] = $summary;
	$F['body'] = $body;
	$F['footer'] = $footer;

	$F['tags'] = array();

	$TagQuery = "SELECT t.tag_id, t.tag from tags t";
	$TagQuery .= " INNER JOIN blog_tag_pivot btp ON t.tag_id = btp.tag_id";
	$TagQuery .= " WHERE btp.Job_ID = " . $blog_id;
	$TagQuery .= " ORDER BY t.tag DESC";
	$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

	while ($Tag = mysql_fetch_assoc($TagResult))
		{
		$thistag = $Tag['tag'];

		$T = array();
		$T = $thistag;
		array_push($F['tags'], $T);
		//echo $thistag . "<br />";
		if($thistag=='Archive')
			{
			$archive = 1;
			}
			}

	array_push($ReturnObject, $F);

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
