<?php
	include 'mysql_config.php';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "delete from teammember where id=".$_POST['id'];
	$conn->query($sql);
	echo "success";
?>