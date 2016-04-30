<?php

	require_once __DIR__ . '/vendor/autoload.php';
	$fb = new Facebook\Facebook([
	  'app_id' => '138411683228452', // Replace {app-id} with your app id
	  'app_secret' => '0ae4c23d1df483251a822ebf96f85bb1',
	  'default_graph_version' => 'v2.6',
	  ]);

	$helper = $fb->getJavaScriptHelper();
	try {		
	  	$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}

	if (! isset($accessToken)) {
	  echo 'No cookie set or no OAuth data could be obtained from cookie.';
	  exit;
	}
	print_r($accessToken);
	session_start();
	$_SESSION['fb_access_token'] = (string) $accessToken;
?>