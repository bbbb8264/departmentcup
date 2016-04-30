<?php
	include 'mysql_config.php';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>第五屆成大慢壘系聯盃</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="jquery-2.1.4.min.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Table-master/table.min.css">
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="team.css">
</head>
<body>
	<div id="headerwrapper">
		<div id="header">
			<div id="logo">
				第五屆成大慢壘系聯盃
			</div>
			<div id="headermenu">
				<div class="headermenuitem">
					<a href="index.html"><span class="headermenuitemtext">關於系聯盃</span></a>
				</div>
				<div class="headermenuitem">
					<a href="apply.php"><span class="headermenuitemtext">報名系聯盃</span></a>
				</div>
				<div class="headermenuitem">
					<a href="team.php"><span class="headermenuitemtext">參賽隊伍一覽</span></a>
				</div>
			</div>
		</div>
	</div>
	<div id="sublogowrapper">
		<div id="sublogo">
			參賽隊伍一覽
		</div>
	</div>
	<div class="content">
		<?php
			$sql = "select * from team";
			$treturn = $conn->query($sql);
			if($treturn->num_rows == 0){
				echo '<div class="contenttitle">目前沒有隊伍報名</div>';
			}else{
				echo '<div class="contenttitle">目前報名隊伍總數：'.$treturn->num_rows.'隊</div>';
				echo '<table class="ui celled structured table">
						  <thead>
						    <tr>
						      <th style="width:140px">系所</th>
						      <th style="width:110px">領隊姓名</th>
						      <th style="width:125px">領隊系級</th>
						      <th style="width:125px">領隊FB</th>
						      <th style="width:300px">領隊電子郵件</th>
						      <th style="width:200px">領隊手機號碼</th>
						    </tr>
						  </thead>
						  <tbody>';
				while($trow = $treturn->fetch_assoc()){
					$teamid = $trow['id'];
					echo '<tr><td>'.$trow['depart'].'</td>';
					$sql = "select * from teamleader where teamid=".$teamid;
					$return = $conn->query($sql);
					$row = $return->fetch_assoc();
					if($row['iscontacter']){
						echo '<td>'.$row['name'].'</td>';
						echo '<td>'.$row['departlevel'].'</td>';
						echo '<td>'.$row['FBaccount'].'</td>';
						echo '<td>'.$row['email'].'</td>';
						echo '<td>'.$row['phone'].'</td>';
					}else{
						$sql = "select * from contacter where teamid=".$teamid;
						$return = $conn->query($sql);
						$row = $return->fetch_assoc();
						echo '<td>'.$row['name'].'</td>';
						echo '<td>'.$row['departlevel'].'</td>';
						echo '<td>'.$row['FBaccount'].'</td>';
						echo '<td>'.$row['email'].'</td>';
						echo '<td>'.$row['phone'].'</td>';
					}
					echo '</tr>';
				}
				echo	  '</tbody>
						</table>';
						echo $_ENV['OPENSHIFT_DATA_DIR'].'1.jpg';
			}
		?>
	</div>
</body>
</html>
