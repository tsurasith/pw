<?php 
	session_start();
	
	include("include/config.inc.php");
	include("include/mysqli.php");
	include("include/shareFunction.php");

	$username = "";
	$password = "";
	$type = "";

	$_protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
	$_host = $_SERVER['HTTP_HOST'];
	$_uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');


	$_SESSION['deploy_folder'] = $_uri;

	$_page = "";


	if(isset($_SESSION['pw-logined'])) {

		if($_SESSION['pw-type'] == 'admin')
		{		
			$_page = 'administrator/index.php';
			header("Location: $_page");
			exit(0);
		}
		if($_SESSION['pw-type'] == 'teacher')
		{
			$_page = 'teacher/index.php';
			header("Location: $_page");
			exit(0);
		}
		if($_SESSION['pw-type'] == 'student')
		{
			$_page = 'student/index.php';
			header("Location: $_page");
			exit(0);
		}
	}
	

	if(!@$_SESSION['pw-logined']) {
		$classtext = array("", "");
		$classbox = array("noborder2", "noborder2");
		$message = "กรุณาป้อนข้อมูลชื่อผู้ใช้งาน รหัสผ่านและสิทธิการใช้งาน";
		
		if(isset($_REQUEST['action'])) {
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			if(empty($_REQUEST['username']) && empty($_REQUEST['password'])) {
				$message = "<span class=\"red\">กรุณากรอกชื่อผู้ใช้และรหัสผ่านของท่านด้วย</span>";
			} else if(empty($_REQUEST['username']) && !empty($_REQUEST['password'])) {
				$message = "<span class=\"red\">กรุณากรอกชื่อผู้ใช้ของท่านด้วย</span>";
			} else if(!empty($_REQUEST['username']) && empty($_REQUEST['password'])) {
				$message = "<span class=\"red\">กรุณากรอกรหัสผ่านของท่านด้วย</span>";
			} else {
				$sql = "";
				switch ($_REQUEST['type']) {
					case 'admin' :	$sql = "select * from teachers where username = '".$_REQUEST['username']."' and password = '".($_REQUEST['password'])."' and type= 'admin'"; break;
					case 'teacher' : $sql = "select * from teachers where username = '".$_REQUEST['username']."' and password = '".($_REQUEST['password'])."' and type= 'teacher'"; break;
					case 'student' : $sql = "select * from students where id = '".$_REQUEST['username']."' and id = '".($_REQUEST['password'])."' order by xedbe desc" ; break;
				}
				$result = mysqli_query($_connection,$sql);

				$_guid_logon_history = "";
				$_guid_logon_history = gen_uuid();

				$_user_account_id = "";

				$_sql_history_logon = "
				INSERT INTO `users_logon_history`(
					`user_logon_history_id`,
					`user_account_id`,
					`user_logon_by`,
					`user_ip`,
					`user_browser`,
					`user_logon_result`
				)
				VALUES(
					'" . $_guid_logon_history . "',
					'" . $_user_account_id . "',
					'" . $username . "',
					'" . $_SERVER['REMOTE_ADDR'] . "',
					'" . $_SERVER['HTTP_USER_AGENT'] . "',
					'failed'
				)";

				
				$_res_logon_history = mysqli_query($_connection,$_sql_history_logon);


				if(mysqli_num_rows($result) == 0) {

					$message = "<span class=\"red\">ข้อมูลของท่านไม่ถูกต้อง กรุณาตรวจสอบข้อมูลด้วย</span>";
				} else {

					$_user_account_id = "";
					$_his_dat = mysqli_fetch_assoc(mysqli_query($_connection,"select user_account_id from users_account where user_account_logon = '". $username . "'"));
					$_user_account_id = $_his_dat['user_account_id'];

					$_SESSION['user_account_id'] = $_user_account_id;
					$_sql_update_logon_history = "update 
													users_logon_history 
												set 
													user_account_id = '" . $_user_account_id . "',
													user_logon_result = 'success'
												where user_logon_history_id = '". $_guid_logon_history ."'";

					$_res_logon_history = mysqli_query($_connection,$_sql_update_logon_history);


					$data = mysqli_fetch_object($result);
					$_SESSION['pw-logined'] = true;
					$_SESSION['username'] = $_REQUEST['username'];
					$_SESSION['name'] = $data->PREFIX . $data->FIRSTNAME ." ".  $data->LASTNAME;
					$_SESSION['shortname'] =  $data->FIRSTNAME ." ".  $data->LASTNAME;
					$_SESSION['pw-type'] = $_REQUEST['type'] ;
					if($_REQUEST['type']=='student'){
						$_level = 0;
						if($data->xLevel==3){
							$_level = $data->xYearth;
						}else{
							$_level = $data->xYearth+3;
						}
						$_SESSION['line_level'] = $_level;
					}
					if($data->TeacCode == "999" || $data->superuser == "1"){ $_SESSION['superAdmin'] = true; }
				
					mysqli_free_result($result);
					mysqli_close($_connection);	

					$_login_notification_message  = $_SESSION['shortname'];
					$_login_notification_message .= " ได้ทำการล็อกอินเข้าใช้งานระบบ";

					if($_SESSION['username']!="admin" && ($_REQUEST['type']=="admin" || $_REQUEST['type']=="teacher")){
						SendLineMessage($_login_notification_message,$_line_token);
					}else{
						SendLineMessage($_login_notification_message,$_token[$_level]);
					}

					if($_SESSION['pw-type'] == 'admin')
					{		
						$_page = 'administrator/index.php';
						$_SESSION['page'] = $_page;
						header("Location: http://$_host$_uri/$_page");
						exit(0);
					}
					if($_SESSION['pw-type'] == 'teacher')
					{
						$_page = 'teacher/index.php';

						$_SESSION['page'] = $_page;
						$_SESSION['teacher_id'] = $data->TeacCode;
						
						header("Location: http://$_host$_uri/$_page");
						exit(0);
					}
					if($_SESSION['pw-type'] == 'student')
					{
						
						$_page = 'student/index.php';
						$_SESSION['page'] = $_page;

						header("Location: http://$_host$_uri/$_page");
						exit(0);
					}
				}
			}
		}
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="author" content="Mr.Surasith Taokok" />
			<meta name="keywords" content="โรงเรียนเพชรวิทยาคาร" />
			<meta name="description" content="Student Information Project" />	
			<link href="css/main.css" type=text/css rel=stylesheet>
			<link rel="shortcut icon" href="images/favicon.ico" />
			
			<title>-:- ระบบสารสนเทศโรงเรียนเพชรวิทยาคาร -:-</title>
			
			
			
		</head>
		<body onLoad="document.login.username.focus();">
		<div id="maincontent">
			<div id="loginform">
				<h2>ระบบสารสนเทศ<span class="green">กิจการนักเรียน</span></h2>
				<form name="login" id="login" method="post" action="" autocomplete="off">
					<?php echo $message; ?>
					<table align="right">
						<tr>
							<td><label for="username">ชื่อผู้ใช้ :</label></td>
							<td><input type="text" name="username" id="username" class="<?php echo $classbox[0]; ?>"  value="<?php echo $username; ?>"  onclick="this.value=''" /></td>
						</tr>
						<tr>
							<td><label for="password">รหัสผ่าน :</label></td>
							<td><input type="password" name="password" id="password" class="<?php echo $classbox[1]; ?>"  value="<?php echo $password; ?>"  onclick="this.value=''" /></td>
						</tr>
						<tr>
							<td><label for="type">สิทธิเข้าใช้งาน :</label></td>
							<td align="left">
								<input type="hidden" name="action" id="action" value="login"> 
								<select name="type" class="loginSelect" id="type">
									<option value="admin">ผู้ดูแลระบบ</option>
									<option value="teacher">ครูที่ปรึกษา</option>
									<option value="student">นักเรียน</option>
								</select>
							</td>
					</tr>
					<tr>
							<td>&nbsp;</td>
							<td>
								<input name="button" type="submit" class="button" id="button" value="เข้าสู่ระบบ"   />
								<input name="button2" type="button" class="button" id="button2" value="ยกเลิก" onClick="window.location='index.php'" /><br />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div style="line-height: 18px">
									<br />
									ระบบจัดการสารสนเทศ<br />
									ออกแบบและพัฒนาระบบ: <a href="mailto:taokok@gmail.com">นายสุรสิทธิ์  ท้าวกอก</a>
								</div>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>

		</body>
		</html>
<?php
} 
?>