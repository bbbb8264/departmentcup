<?php
	require_once __DIR__ . '/vendor/autoload.php';
	
	session_start();
	/*unset($_SESSION["fb_access_token"]);
	session_destroy();*/
	
	$fb = new Facebook\Facebook([
	  'app_id' => '138411683228452', // Replace {app-id} with your app id
	  'app_secret' => '0ae4c23d1df483251a822ebf96f85bb1',
	  'default_graph_version' => 'v2.2',
	  ]);

	try {
	  // Returns a `Facebook\FacebookResponse` object
	  $response = $fb->get('/me?fields=id,name', $_SESSION['fb_access_token']);
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}

	$user = $response->getGraphUser();

	echo 'Name: ' . $user['id'];
?>