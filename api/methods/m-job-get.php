<?php
$route = '/job/';
$app->get($route, function ()  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
	if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
	if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 250;}
	if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'Title';}
	if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'DESC';}

	// Pull from MySQL
	if($query!='')
		{
		$Query = "SELECT * FROM job WHERE Title LIKE '%" . $query . "%'";
		}
	else
		{
		$Query = "SELECT * FROM job";
		}
	$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
	//echo $Query . "<br />";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$job_id = $Database['ID'];
		$post_date = $Database['Post_Date'];
		$title = $Database['Title'];
		$author = $Database['Author'];
		$summary = $Database['Summary'];
		$body = $Database['Body'];
		$footer = $Database['Footer'];
		$status = $Database['Status'];
		$buildpage = $Database['Build_Page'];
		$githubbuild = $Database['Github_Build'];
		$twitterbuild = $Database['Twitter_Build'];
		$linkedinbuild = $Database['LinkedIn_Build'];
		$showonsite = $Database['Show_On_Site'];
		$image = $Database['Feature_Image'];

		$TagQuery = "SELECT t.tag_id, t.tag from tags t";
		$TagQuery .= " INNER JOIN job_tag_pivot btp ON t.tag_id = btp.tag_id";
		$TagQuery .= " WHERE btp.Job_ID = " . $job_id;
		$TagQuery .= " ORDER BY t.tag DESC";
		$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

		// manipulation zone
		$host = $_SERVER['HTTP_HOST'];
		$job_id = prepareIdOut($job_id,$host);

		$F = array();
		$F['job_id'] = $job_id;
		$F['post_date'] = $post_date;
		$F['title'] = $title;
		$F['author'] = $author;
		$F['summary'] = $summary;
		$F['body'] = $body;
		$F['footer'] = $footer;
		$F['status'] = $status;
		$F['image'] = $image;
		$F['build_page'] = $buildpage;
		$F['github_build'] = $githubbuild;
		$F['twitter_build'] = $twitterbuild;
		$F['linkedin_build'] = $linkedinbuild;
		$F['show_on_site'] = $showonsite;

		$F['tags'] = array();

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
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
