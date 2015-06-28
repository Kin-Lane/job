<?php
$route = '/job/:job_id/';
$app->get($route, function ($job_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$job_id = prepareIdIn($job_id,$host);

	$ReturnObject = array();

	$Query = "SELECT * FROM job WHERE ID = " . $job_id;

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

		// manipulation zone

		$TagQuery = "SELECT t.tag_id, t.tag from tags t";
		$TagQuery .= " INNER JOIN job_tag_pivot btp ON t.tag_id = btp.tag_id";
		$TagQuery .= " WHERE btp.Job_ID = " . $job_id;
		$TagQuery .= " ORDER BY t.tag DESC";
		$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

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

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
