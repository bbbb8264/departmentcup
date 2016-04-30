<?php
	require_once __DIR__ . '/vendor/autoload.php';
	include 'mysql_config.php';
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	session_start();
	if(isset($_SESSION['fb_access_token'])){
		$fb = new Facebook\Facebook([
		  'app_id' => '138411683228452', // Replace {app-id} with your app id
		  'app_secret' => '0ae4c23d1df483251a822ebf96f85bb1',
		  'default_graph_version' => 'v2.6',
		  ]);
		try {
		  $response = $fb->get('/me?fields=id,name', $_SESSION['fb_access_token']);
		  $user = $response->getGraphUser();
		  $isGetUser = true;
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  $isGetUser = false;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
	}else{
		$isGetUser = false;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>第五屆成大慢壘系聯盃</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="jquery-2.1.4.min.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Form-master/form.min.css">
	<script src="UI-Form-master/form.min.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Icon-master/icon.min.css">
	<link rel="stylesheet" type="text/css" href="UI-Transition-master/transition.min.css">
	<script src="UI-Transition-master/transition.min.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Dropdown-master/dropdown.min.css">
	<script src="UI-Dropdown-master/dropdown.min.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Checkbox-master/checkbox.min.css">
	<script src="UI-Checkbox-master/checkbox.min.js"></script>
	<link rel="stylesheet" type="text/css" href="UI-Loader-master/loader.min.css">
	<link rel="stylesheet" type="text/css" href="jquery-ui-1.11.4.custom/jquery-ui.css">
	<script src="jquery-ui-1.11.4.custom/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="apply.css">
	<script src="apply.js"></script>
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
			報名系聯盃
		</div>
	</div>
		<?php
			if($isGetUser){
				$sql = "select * from fbaccountteam where FBaccountid='".$user['id']."'";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$teamid = $row['teamid'];
					$sql = "select * from team where id='".$teamid."'";
					$result = $conn->query($sql);
					$row = $result->fetch_assoc();
					echo '<div class="content">
						<div class="subtitle">
							隊伍資訊
						</div>
						<form class="ui form teamform" data-id="'.$teamid.'">
							<div class="inline field">
								<label>系所名</label>
								<input type="text" value="'.$row['depart'].'">
							</div>
						</form>';
					$sql = "select * from teamleaderpicture where teamid=".$teamid;
					$result = $conn->query($sql);
					$row = $result->fetch_assoc();
					echo '<div class="leader">
						<div class="leadertitle">
							隊長、領隊(領隊負責所有聯絡事項)
						</div>
						<div class="member">
							<div class="memberpic">
								<img src="leaderpictures/'.$row['id'].'.'.$row['fileextend'].'" style="width:100px;height:140px;">
								<div>
								點選更換照片
								<br>
								建議使用一吋照片
								</div>
							</div>';
					$sql = "select * from teamleader where teamid=".$teamid;
					$result = $conn->query($sql);
					$row = $result->fetch_assoc();
					echo '<form class="ui form textinput">
								<div class="field">
									<label>姓名</label>
									<input type="text" value="'.$row['name'].'">
								</div>
								<div class="field">
									<label>學號</label>
									<input type="text" value="'.$row['number'].'">
								</div>
								<div class="field">
									<label>系級</label>
									<input type="text" value="'.$row['departlevel'].'">
								</div>
							</form>';

					echo 	'<form class="ui form starinput">
								<div class="inline field">
								    <div class="ui checkbox">
								    	<input type="checkbox" tabindex="0" class="hidden" '.($row['participate1']?'checked':'').'>
								    	<label>是否參加明星賽</label>
								    </div>
								</div>
								<div class="field" '.($row['participate1']?'':'style="opacity: 0;"').'>
								    <label>明星賽守備位置</label>
								    <select class="ui dropdown '.($row['participate1']?'':'disabled').'">
								        <option value="0" '.($row['field1']==0?'selected':'').'>內野</option>
										<option value="1" '.($row['field1']==1?'selected':'').'>外野</option>
										<option value="2" '.($row['field1']==2?'selected':'').'>投手</option>
								    </select>
								</div>';
					echo 		'<div class="inline field">
								    <div class="ui checkbox">
								    	<input type="checkbox" tabindex="0" class="hidden" '.($row['participate2']?'checked':'').'>
								    	<label>是否參加明日之星賽<br>(大二以下非校隊隊員可參加)</label>
								    </div>
								</div>
								<div class="field" '.($row['participate2']?'':'style="opacity: 0;"').'>
								    <label>明日之星賽守備位置</label>
								    <select class="ui dropdown '.($row['participate1']?'':'disabled').'">
								        <option value="0" '.($row['field2']==0?'selected':'').'>投手</option>
										<option value="1" '.($row['field2']==1?'selected':'').'>一壘手</option>
										<option value="2" '.($row['field2']==2?'selected':'').'>二壘手</option>
										<option value="3" '.($row['field2']==3?'selected':'').'>三壘手</option>
										<option value="4" '.($row['field2']==4?'selected':'').'>游擊手</option>
										<option value="5" '.($row['field2']==5?'selected':'').'>捕手</option>
										<option value="6" '.($row['field2']==6?'selected':'').'>外野手</option>
								    </select>
								</div>
							</form>
							';
					if($row['iscontacter']){
						echo '<form class="ui form contact">
								<div class="inline field">
								    <div class="ui checkbox">
								    	<input type="checkbox" tabindex="0" class="hidden" checked>
								    	<label>隊長是否兼職領隊</label>
								   	</div>
								</div>
								<div class="field" style="display:none;">
									<label>領隊姓名</label>
									<input type="text">
								</div>
								<div class="field" style="display:none;">
									<label>領隊系級</label>
									<input type="text">
								</div>
								<div class="field">
									<label>FB帳號</label>
									<input type="text" value="'.$row['FBaccount'].'">
								</div>
								<div class="field">
									<label>手機號碼</label>
									<input type="text" value="'.$row['phone'].'">
								</div>
								<div class="field">
									<label>電子郵件</label>
									<input type="text" value="'.$row['email'].'">
								</div>
							</form>
							<input type="file" data-exist="1" accept="image/*" style="display:none;">
						</div>
					</div>';
					}else{
						$sql = "select * from contacter where teamid=".$teamid;
						$result = $conn->query($sql);
						$row = $result->fetch_assoc();
						echo '<form class="ui form contact">
							<div class="inline field">
							    <div class="ui checkbox">
							    	<input type="checkbox" tabindex="0" class="hidden">
							    	<label>隊長是否兼職領隊</label>
							   	</div>
							</div>
							<div class="field">
								<label>領隊姓名</label>
								<input type="text" value="'.$row['name'].'">
							</div>
							<div class="field">
								<label>領隊系級</label>
								<input type="text" value="'.$row['departlevel'].'">
							</div>
							<div class="field">
								<label>FB帳號</label>
								<input type="text" value="'.$row['FBaccount'].'">
							</div>
							<div class="field">
								<label>手機號碼</label>
								<input type="text" value="'.$row['phone'].'">
							</div>
							<div class="field">
								<label>電子郵件</label>
								<input type="text" value="'.$row['email'].'">
							</div>
						</form>
						<input type="file" accept="image/*" data-exist="1" style="display:none;">
					</div>
				</div>';
					}
					echo '<div class="subtitle">隊員</div><div class="membercontainer">';
					$sql = "select * from teammember where teamid=".$teamid;
					$result = $conn->query($sql);
					while($row = $result->fetch_assoc()){
						$sql2 = "select * from teammemberpicture where teammemberid=".$row['id'];
						$result2 = $conn->query($sql2);
						$row2 = $result2->fetch_assoc();
						echo '<div class="member2" data-id="'.$row['id'].'">
								<div class="memberpic">
									<img src="memberpictures/'.$row2['id'].'.'.$row2['fileextend'].'" style="width:100px;height:140px;">
									<div>
										點選更換照片
										<br>
										建議使用一吋照片
									</div>
								</div>';
						echo	'<form class="ui form textinput">
									<div class="field">
										<label>姓名</label>
										<input type="text" value="'.$row['name'].'">
									</div>
									<div class="field">
										<label>學號</label>
										<input type="text" value="'.$row['number'].'">
									</div>
									<div class="field">
										<label>系級</label>
										<input type="text" value="'.$row['departlevel'].'">
									</div>
								</form>
								<form class="ui form starinput">
									<div class="inline field">
										<div class="ui checkbox">
										    <input type="checkbox" tabindex="0" class="hidden" '.($row['participate1']?'checked':'').'>
										    <label>是否參加明星賽</label>
										</div>
									</div>
									<div class="field" '.($row['participate1']?'':'style="opacity: 0;"').'>
										<label>明星賽守備位置</label>
										<select class="ui dropdown '.($row['participate1']?'':'disabled').'">
											<option value="0" '.($row['field1']==0?'selected':'').'>內野</option>
											<option value="1" '.($row['field1']==1?'selected':'').'>外野</option>
											<option value="2" '.($row['field1']==2?'selected':'').'>投手</option>
										</select>
									</div>
									<div class="inline field">
										<div class="ui checkbox">
											<input type="checkbox" tabindex="0" class="hidden" '.($row['participate2']?'checked':'').'>
											<label>是否參加明日之星賽<br>(大二以下非校隊隊員可參加)</label>
										</div>
									</div>
									<div class="field" '.($row['participate2']?'':'style="opacity: 0;"').'>
										<label>明日之星賽守備位置</label>
										<select class="ui dropdown '.($row['participate2']?'':'disabled').'">
											<option value="0" '.($row['field2']==0?'selected':'').'>投手</option>
											<option value="1" '.($row['field2']==1?'selected':'').'>一壘手</option>
											<option value="2" '.($row['field2']==2?'selected':'').'>二壘手</option>
											<option value="3" '.($row['field2']==3?'selected':'').'>三壘手</option>
											<option value="4" '.($row['field2']==4?'selected':'').'>游擊手</option>
											<option value="5" '.($row['field2']==5?'selected':'').'>捕手</option>
											<option value="6" '.($row['field2']==6?'selected':'').'>外野手</option>
										</select>
									</div>
								</form>
								<i class="big remove icon"></i>
								<input type="file" data-exist="1" style="display:none;" accept="image/*">
							</div>';
					}
					echo '</div><div class="buttonwrapper">
							<img id="addplayerbutton" src="addplayer.png"/>
							<div class="savebutton">
								存檔
							</div>
						</div>
					</div>';
				}else{
					echo '<div class="content" data-id="'.$user['id'].'">
							<div class="logintext">
								此帳號尚未報名任何隊伍，請點選下方按鈕進行報名。
							</div>
							<div id="registerbutton">
								開始報名
							</div>
						</div>';
				}
			}else{
				echo '<div class="content">
						<div class="notlogintext">
							使用FB帳號報名隊伍
						</div>
						<div class="notloginbuttonwrapper">
							<div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" onlogin="login();"></div>
						</div>
					</div>';
			}
		?>
	<!--<div class="content">
		<div class="subtitle">
			隊伍資訊
		</div>
		<form class="ui form teamform">
			<div class="inline field">
				<label>系所名</label>
				<input type="text">
			</div>
		</form>
		<div class="leader">
			<div class="leadertitle">
				隊長、領隊(領隊負責所有聯絡事項)
			</div>
			<div class="member">
				<div class="memberpic">
					<img src="noperson.png" style="width:100px;height:140px;">
					<div>
					點選更換照片
					<br>
					建議使用一吋照片
					</div>
				</div>
				<form class="ui form textinput">
					<div class="field">
						<label>姓名</label>
						<input type="text">
					</div>
					<div class="field">
						<label>學號</label>
						<input type="text">
					</div>
					<div class="field">
						<label>系級</label>
						<input type="text">
					</div>
				</form>
				<form class="ui form starinput">
					<div class="inline field">
					    <div class="ui checkbox">
					    	<input type="checkbox" tabindex="0" class="hidden">
					    	<label>是否參加明星賽</label>
					    </div>
					</div>
					<div class="field" style="opacity: 0;">
					    <label>明星賽守備位置</label>
					    <select class="ui dropdown disabled">
					        <option value="0">內野</option>
					        <option value="1">外野</option>
					        <option value="2">投手</option>
					    </select>
					</div>
					<div class="inline field">
					    <div class="ui checkbox">
					    	<input type="checkbox" tabindex="0" class="hidden">
					    	<label>是否參加明日之星賽<br>(大二以下非校隊隊員可參加)</label>
					    </div>
					</div>
					<div class="field" style="opacity: 0;">
					    <label>明日之星賽守備位置</label>
					    <select class="ui dropdown disabled">
					        <option value="0">投手</option>
					        <option value="1">一壘手</option>
					        <option value="2">二壘手</option>
					        <option value="3">三壘手</option>
					        <option value="4">游擊手</option>
					        <option value="5">捕手</option>
					        <option value="6">外野手</option>
					    </select>
					</div>
				</form>
				<form class="ui form contact">
					<div class="inline field">
					    <div class="ui checkbox">
					    	<input type="checkbox" tabindex="0" class="hidden" checked>
					    	<label>隊長是否兼職領隊</label>
					   	</div>
					</div>
					<div class="field" style="display:none;">
						<label>領隊姓名</label>
						<input type="text">
					</div>
					<div class="field" style="display:none;">
						<label>領隊系級</label>
						<input type="text">
					</div>
					<div class="field">
						<label>FB帳號</label>
						<input type="text">
					</div>
					<div class="field">
						<label>手機號碼</label>
						<input type="text">
					</div>
					<div class="field">
						<label>電子郵件</label>
						<input type="text">
					</div>
				</form>
			</div>
		</div>
		<div class="subtitle">
		隊員
		</div>
		<div class="membercontainer">
			<div class="member2">
				<div class="memberpic">
					<img src="noperson.png" style="width:100px;height:140px;">
					<div>
						點選更換照片
						<br>
						建議使用一吋照片
					</div>
				</div>
				<form class="ui form textinput">
					<div class="field">
						<label>姓名</label>
						<input type="text">
					</div>
					<div class="field">
						<label>學號</label>
						<input type="text">
					</div>
					<div class="field">
						<label>系級</label>
						<input type="text">
					</div>
				</form>
				<form class="ui form starinput">
					<div class="inline field">
						<div class="ui checkbox">
						    <input type="checkbox" tabindex="0" class="hidden">
						    <label>是否參加明星賽</label>
						</div>
					</div>
					<div class="field" style="opacity: 0;">
						<label>明星賽守備位置</label>
						<select class="ui dropdown disabled">
							<option value="0">內野</option>
							<option value="1">外野</option>
							<option value="2">投手</option>
						</select>
					</div>
					<div class="inline field">
						<div class="ui checkbox">
							<input type="checkbox" tabindex="0" class="hidden">
							<label>是否參加明日之星賽<br>(大二以下非校隊隊員可參加)</label>
						</div>
					</div>
					<div class="field" style="opacity: 0;">
						<label>明日之星賽守備位置</label>
						<select class="ui dropdown disabled">
							<option value="0">投手</option>
							<option value="1">一壘手</option>
							<option value="2">二壘手</option>
							<option value="3">三壘手</option>
							<option value="4">游擊手</option>
							<option value="5">捕手</option>
							<option value="6">外野手</option>
						</select>
					</div>
				</form>
				<i class="big remove icon"></i>
				<input type="file" style="display:none;" accept="image/*">
			</div>
		</div>
		<div class="buttonwrapper">
			<img id="addplayerbutton" src="addplayer.png"/>
			<div class="savebutton">
				<div class="ui active mini inline loader"></div>
				存檔
			</div>
		</div>
	</div>-->
	<div id="dialog-confirm" title="刪除投影片?" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>投影片被刪除將無法復原，請問是否繼續執行</p>
</div>
	<div id="dialog" title="提示" style="display:none;">
	  	<p>必須上傳檔案才可以製作投影片</p>
	</div>
</body>
</html>
