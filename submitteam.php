<?php
	$leader = json_decode($_POST['leader']);
	$contact = json_decode($_POST['contact']);
	$member = array();
	for($i = 1; $i <= $_POST['memberamount'];$i++){
		$member['member'.$i] = json_decode($_POST['member'.$i]);
	}
	require_once __DIR__ . '/vendor/autoload.php';
	include 'mysql_config.php';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	$response = array();
	if($_POST['teamid'] == "null"){
		$sql = "INSERT INTO team (depart) VALUES ('".$_POST['teamname']."')";
		$conn->query($sql);
		$response['teamid'] = mysqli_insert_id($conn);
		$sql = "INSERT INTO fbaccountteam (FBaccountid, teamid) VALUES ('".$_POST['fbid']."',".$response['teamid'].")";
		$conn->query($sql);
	}else{
		$sql = "update team set depart='".$_POST['teamname']."' where id=".$_POST['teamid'];
		$conn->query($sql);
		$response['teamid'] = $_POST['teamid'];
	}
	$sql = "select id from teamleader where teamid=".$response['teamid'];
	$result = $conn->query($sql);
	if($result->num_rows == 0){
		if($contact->isleader){
			if(!$leader->participate1){
				$leader->participate1 = 0;
				$leader->field1 = 0;
			}
			if(!$leader->participate2){
				$leader->participate2 = 0;
				$leader->field2 = 0;
			}
			$sql = "INSERT INTO teamleader (name, teamid, number, departlevel, iscontacter, FBaccount, email, phone, participate1, participate2, field1, field2) VALUES ('".$leader->name."','".$response['teamid']."','".$leader->number."','".$leader->departlevel."',".$contact->isleader.",'".$contact->fb."','".$contact->email."','".$contact->mobile."',".$leader->participate1.",".$leader->participate2.",".$leader->field1.",".$leader->field2.")";
			$conn->query($sql);
		}else{
			if(!$leader->participate1){
				$leader->participate1 = 0;
				$leader->field1 = 0;
			}
			if(!$leader->participate2){
				$leader->participate2 = 0;
				$leader->field2 = 0;
			}
			$sql = "INSERT INTO teamleader (name, teamid, number, departlevel, iscontacter, participate1, participate2, field1, field2) VALUES ('".$leader->name."','".$response['teamid']."','".$leader->number."','".$leader->departlevel."',0,".$leader->participate1.",".$leader->participate2.",".$leader->field1.",".$leader->field2.")";
			$conn->query($sql);
			$sql = "INSERT INTO contacter (teamid, name, departlevel, FBaccount, email, phone) VALUES ('".$response['teamid']."','".$contact->name."','".$contact->departlevel."','".$contact->fb."','".$contact->email."','".$contact->mobile."')";
			$conn->query($sql);
		}
	}else{
		if($contact->isleader){
			if(!$leader->participate1){
				$leader->participate1 = 0;
				$leader->field1 = 0;
			}
			if(!$leader->participate2){
				$leader->participate2 = 0;
				$leader->field2 = 0;
			}
			$sql = "update teamleader set name='".$leader->name."', number='".$leader->number."', departlevel='".$leader->departlevel."', iscontacter=1, FBaccount='".$contact->fb."', email='".$contact->email."', phone='".$contact->mobile."', participate1=".$leader->participate1.", participate2=".$leader->participate2.", field1=".$leader->field1.", field2=".$leader->field2." where teamid=".$response['teamid'];
			$conn->query($sql);
		}else{
			if(!$leader->participate1){
				$leader->participate1 = 0;
				$leader->field1 = 0;
			}
			if(!$leader->participate2){
				$leader->participate2 = 0;
				$leader->field2 = 0;
			}
			$sql = "update teamleader set name='".$leader->name."', number='".$leader->number."', departlevel='".$leader->departlevel."', iscontacter=0, participate1=".$leader->participate1.", participate2=".$leader->participate2.", field1=".$leader->field1.", field2=".$leader->field2." where teamid=".$response['teamid'];
			$conn->query($sql);
			$sql = "select id from contacter where teamid=".$response['teamid'];
			$conn->query($sql);
			$result = $conn->query($sql);
			if($result->num_rows == 0){
				$sql = "INSERT INTO contacter (teamid, name, departlevel, FBaccount, email, phone) VALUES ('".$response['teamid']."','".$contact->name."','".$contact->departlevel."','".$contact->fb."','".$contact->email."','".$contact->mobile."')";
				$conn->query($sql);
			}else{
				$sql = "update contacter set name='".$contact->name."', departlevel='".$contact->departlevel."', FBaccount='".$contact->fb."', email='".$contact->email."', phone='".$contact->mobile."' where teamid=".$response['teamid'];
				$conn->query($sql);
			}
		}
	}
	if(isset($_FILES['leaderpic'])){
		$sql = "select id from teamleaderpicture where teamid=".$response['teamid'];
		$result = $conn->query($sql);
		if($result->num_rows == 0){
			$filename = substr($_FILES['leaderpic']['name'], 0,strrpos($_FILES['leaderpic']['name'], "."));
			$extend = substr($_FILES['leaderpic']['name'], strrpos($_FILES['leaderpic']['name'], ".")+1,strlen($_FILES['leaderpic']['name'])-strrpos($_FILES['leaderpic']['name'], ".")-1);
			$sql = "insert into teamleaderpicture (teamid, filename, fileextend) values (".$response['teamid'].",'".$filename."','".$extend."')";
			$conn->query($sql);
			$dir = 'leaderpictures';
			$filename = mysqli_insert_id($conn).'.'.$extend;
			//move_uploaded_file($_FILES['leaderpic']['tmp_name'], $_ENV['OPENSHIFT_REPO_DIR'].'leaderpictures/'.$filename)
			move_uploaded_file($_FILES['leaderpic']['tmp_name'], $_ENV['OPENSHIFT_DIR_DIR'].'leaderpictures/'.$filename);
		}else{
			$filename = substr($_FILES['leaderpic']['name'], 0,strrpos($_FILES['leaderpic']['name'], "."));
			$extend = substr($_FILES['leaderpic']['name'], strrpos($_FILES['leaderpic']['name'], ".")+1,strlen($_FILES['leaderpic']['name'])-strrpos($_FILES['leaderpic']['name'], ".")-1);
			$row = $result->fetch_assoc();
			$sql = "update teamleaderpicture set filename='".$filename."',fileextend='".$extend."' where id=".$row['id'];
			$conn->query($sql);
			$dir = 'leaderpictures';
			$filename = $row['id'].'.'.$extend;
			//move_uploaded_file($_FILES['leaderpic']['tmp_name'], $_ENV['OPENSHIFT_REPO_DIR'].'leaderpictures/'.$filename);
			move_uploaded_file($_FILES['leaderpic']['tmp_name'], $_ENV['OPENSHIFT_DATA_DIR'].'leaderpictures/'.$filename);
			move_uploaded_file($_FILES['leaderpic']['tmp_name'], $_ENV['OPENSHIFT_REPO_DIR'].'leaderpictures/'.$filename);
		}
	}
	for($i = 0;$i < $_POST['memberamount'];$i++){
		$memberid = [];
		$current = $member['member'.($i+1)];
		if(!$current->participate1){
				$current->participate1 = 0;
				$current->field1 = 0;
			}
			if(!$current->participate2){
				$current->participate2 = 0;
				$current->field2 = 0;
			}
		if($current->id == "null"){
			$sql = "INSERT INTO teammember (teamid, name, number, departlevel, participate1, participate2, field1, field2) VALUES (".$response['teamid'].",'".$current->name."','".$current->number."','".$current->departlevel."','".$current->participate1."','".$current->participate2."','".$current->field1."','".$current->field2."')";
			$conn->query($sql);
			$response['member'.($i+1)] = mysqli_insert_id($conn);
			array_push($memberid, mysqli_insert_id($conn));
			$filename = substr($_FILES['memberpic'.($i+1)]['name'], 0,strrpos($_FILES['memberpic'.($i+1)]['name'], "."));
			$extend = substr($_FILES['memberpic'.($i+1)]['name'], strrpos($_FILES['memberpic'.($i+1)]['name'], ".")+1,strlen($_FILES['memberpic'.($i+1)]['name'])-strrpos($_FILES['memberpic'.($i+1)]['name'], ".")-1);
			$sql = "insert into teammemberpicture (teammemberid, filename, fileextend) values (".$response['member'.($i+1)].",'".$filename."','".$extend."')";
			$conn->query($sql);
			$dir = 'memberpictures';
			$filename = mysqli_insert_id($conn).'.'.$extend;
			//move_uploaded_file($_FILES['memberpic'.($i+1)]['tmp_name'], $_ENV['OPENSHIFT_REPO_DIR'].$dir.'/'.$filename);
			move_uploaded_file($_FILES['memberpic'.($i+1)]['tmp_name'], $_ENV['OPENSHIFT_DATA_DIR'].$dir.'/'.$filename);
			move_uploaded_file($_FILES['leaderpic']['tmp_name'], $_ENV['OPENSHIFT_REPO_DIR'].'leaderpictures/'.$filename);
		}else{
			$sql = "update teammember set name='".$current->name."', number='".$current->number."', departlevel='".$current->departlevel."', participate1='".$current->participate1."', participate2='".$current->participate2."', field1='".$current->field2."' where id=".$current->id;
			$conn->query($sql);
			$response['member'.($i+1)] = $current->id;
			array_push($memberid, $current->id);
			if(isset($_FILES['memberpic'.($i+1)])){
				$sql = "select id from teammemberpicture where teammemberid=".$response['member'.($i+1)];
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				$picid = $row['id'];
				$filename = substr($_FILES['memberpic'.($i+1)]['name'], 0,strrpos($_FILES['memberpic'.($i+1)]['name'], "."));
				$extend = substr($_FILES['memberpic'.($i+1)]['name'], strrpos($_FILES['memberpic'.($i+1)]['name'], ".")+1,strlen($_FILES['memberpic'.($i+1)]['name'])-strrpos($_FILES['memberpic'.($i+1)]['name'], ".")-1);
				$sql = "update teammemberpicture set filename='".$filename."', fileextend='".$extend."' where id=".$picid;
				$conn->query($sql);
				$dir = 'memberpictures';
				$filename = $picid.'.'.$extend;
				//move_uploaded_file($_FILES['memberpic'.($i+1)]['tmp_name'], $_ENV['OPENSHIFT_REPO_DIR'].$dir.'/'.$filename);
				move_uploaded_file($_FILES['memberpic'.($i+1)]['tmp_name'], $_ENV['OPENSHIFT_DATA_DIR'].$dir.'/'.$filename);
			}
		}
	}
	$response['status'] = "success";
	$response['member'] = $memberid;
	echo json_encode($response);
?>