<?php
	include 'mysql_config.php';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	function starpostion($pos) {
	    switch ($pos) {
		    case 0:
		        return "內野";
		        break;
		    case 1:
		        return "外野";
		        break;
		    case 2:
		        return "投手";
		        break;
		    default:
		        return "錯誤";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>第五屆成大慢壘系聯盃</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="showteam.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Table-master/table.min.css">
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="showteam.css">
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
				while($trow = $treturn->fetch_assoc()){
					echo '<div class="team">
							<div class="teamname">'.$trow['depart'].'</div>';
					$sql = "select * from teamleader where teamid=".$trow['id'];
					$return = $conn->query($sql);
					$row = $return->fetch_assoc();
					if($row['iscontacter']){
						echo '<table class="ui celled structured table">
								<thead>
									<tr>
										<th colspan="5">
											領隊
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>姓名</td>
										<td>系級</td>
										<td>FB</td>
										<td>Email</td>
										<td>手機號碼</td>
									</tr>
									<tr>
										<td>'.$row['name'].'</td>
										<td>'.$row['departlevel'].'</td>
										<td>'.$row['FBaccount'].'</td>
										<td>'.$row['email'].'</td>
										<td>'.$row['phone'].'</td>
									</tr>
								</tbody>
							</table>';
					}else{
						$sql = "select * from contacter where teamid=".$trow['id'];
						$return = $conn->query($sql);
						$row = $return->fetch_assoc();
						echo '<table class="ui celled structured table">
								<thead>
									<tr>
										<th colspan="5">
											領隊
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>姓名</td>
										<td>系級</td>
										<td>FB</td>
										<td>Email</td>
										<td>手機號碼</td>
									</tr>
									<tr>
										<td>'.$row['name'].'</td>
										<td>'.$row['departlevel'].'</td>
										<td>'.$row['FBaccount'].'</td>
										<td>'.$row['email'].'</td>
										<td>'.$row['phone'].'</td>
									</tr>
								</tbody>
							</table>';
					}
					echo '<div class="teaminformation">';
					$sql1 = "select * from teamleader where teamid=".$trow['id'];
					$return1 = $conn->query($sql1);
					$row1 = $return1->fetch_assoc();
					$sql2 = "select * from teamleaderpicture where teamid=".$trow['id'];
					$return2 = $conn->query($sql2);
					$row2 = $return2->fetch_assoc();
					echo '<table class="ui celled structured table">
							<thead>
								<tr>
									<th colspan="3">
										隊長
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td rowspan="6" width="250"><img class="memberpic" src="leaderpictures/'.$row2['id'].'.'.$row2['fileextend'].'"></td>
									<td>姓名</td>
									<td>明星賽</td>
								</tr>
								<tr>
									<td>'.$row1['name'].'</td>
									<td rowspan="2">'.($row1['participate1']?'有意願 '.starpostion($row1['field1']):'無意願').'</td>
								</tr>
								<tr>
									<td>學號</td>
								</tr>
								<tr>
									<td>'.$row1['number'].'</td>
									<td>明日之星賽</td>
								</tr>
								<tr>
									<td>系級</td>
									<td rowspan="2">'.($row1['participate2']?'有意願 '.starpostion($row1['field2']):'無意願').'</td>
								</tr>
								<tr>
									<td>'.$row1['departlevel'].'</td>
								</tr>
							</tbody>
						</table>';
					$sql = "select * from teammember where teamid=".$trow['id'];
					$return = $conn->query($sql);
					$count = 1;
					while($row = $return->fetch_assoc()){
						$sql2 = "select * from teammemberpicture where teammemberid=".$row['id'];
						$return2 = $conn->query($sql2);
						$row2 = $return2->fetch_assoc();
						echo '<table class="ui celled structured table">
							<thead>
								<tr>
									<th colspan="3">
										隊員'.($count++).'
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td rowspan="6" width="250"><img class="memberpic" src="memberpictures/'.$row2['id'].'.'.$row2['fileextend'].'"></td>
									<td>姓名</td>
									<td>明星賽</td>
								</tr>
								<tr>
									<td>'.$row['name'].'</td>
									<td rowspan="2">'.($row['participate1']?'有意願 '.starpostion($row['field1']):'無意願').'</td>
								</tr>
								<tr>
									<td>學號</td>
								</tr>
								<tr>
									<td>'.$row['number'].'</td>
									<td>明日之星賽</td>
								</tr>
								<tr>
									<td>系級</td>
									<td rowspan="2">'.($row['participate2']?'有意願 '.starpostion($row['field2']):'無意願').'</td>
								</tr>
								<tr>
									<td>'.$row['departlevel'].'</td>
								</tr>
							</tbody>
						</table>';
					}
					echo '</div></div>';
				}
			}
		?>
	</div>
</body>
</html>
