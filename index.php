<?php 
	include("include/class.mysqldb.php");
	include("include/config.inc.php");
	$username = "";
	$password = "";
	$type = "";

	if($_SESSION['pw-logined']) {	
		if($_SESSION['pw-type'] == 'admin')
					{
						?><meta http-equiv="refresh" content="0;url=administrator/index.php"><?
						exit(0);
					}
					if($_SESSION['pw-type'] == 'teacher')
					{
						$_SESSION['teacher_id'] = $data->TeacCode;
						?><meta http-equiv="refresh" content="0;url=teacher/index.php"><?
						exit(0);
					}
					if($_SESSION['pw-type'] == 'student')
					{
						$_SESSION['name'] = $data->PREFIX . $data->FIRSTNAME ." ".  $data->LASTNAME;
						?><meta http-equiv="refresh" content="0;url=student/index.php"><?
						exit(0);
					}
	} else if(!@$_SESSION['pw-logined']) {
		$classtext = array("", "");
		$classbox = array("noborder2", "noborder2");
		$message = "";
		if(isset($_REQUEST['action'])) {
			$username = $_REQUEST['username'];
			$password = $_REQUEST['password'];
			if(empty($_REQUEST['username']) && empty($_REQUEST['password'])) {
				$message = "กรุณากรอกชื่อผู้ใช้และรหัสผ่านของท่านด้วย";
			} else if(empty($_REQUEST['username']) && !empty($_REQUEST['password'])) {
				$message = "กรุณากรอกชื่อผู้ใช้ของท่านด้วย";
			} else if(!empty($_REQUEST['username']) && empty($_REQUEST['password'])) {
				$message = "กรุณากรอกรหัสผ่านของท่านด้วย";
			} else {
				$sql = "";
				switch ($_REQUEST['type']) {
					case 'admin' :	$sql = "select * from teachers where username = '".$_REQUEST['username']."' and password = '".($_REQUEST['password'])."' and type= 'admin'"; break;
					case 'teacher' : $sql = "select * from teachers where username = '".$_REQUEST['username']."' and password = '".($_REQUEST['password'])."' and type= 'teacher'"; break;
					case 'student' : $sql = "select * from students where id = '".$_REQUEST['username']."' and id = '".($_REQUEST['password'])."' order by xedbe desc" ; break;
				}
				$result = $link->query($sql);
				if($link->num_rows() == 0) {
					$message = "ข้อมูลที่ใช้ล็อกอินของท่านไม่ถูกต้อง";
				} else {
					$data = mysql_fetch_object($result);
					$_SESSION['pw-logined'] 	= true;
					$_SESSION['username'] 		= $_REQUEST['username'];
					$_SESSION['fullname'] 		= $data->PREFIX . $data->FIRSTNAME ." ".  $data->LASTNAME;
					$_SESSION['user-account'] 	= $data->FIRSTNAME . " ".  $data->LASTNAME;
					$_SESSION['pw-type'] = $_REQUEST['type'] ;
						if($data->TeacCode == "999" || $data->superuser == "1")
						{ $_SESSION['superAdmin'] = true; }
					//$sql = "update administrator set lastlogin = '".date("Y-m-d H:i:s")."' where username = '".$_REQUEST['username']."'";
					//$link->query($sql);
					if($_SESSION['pw-type'] == 'admin')
					{
						?><meta http-equiv="refresh" content="0;url=administrator/index.php"><?
						exit(0);
					}
					if($_SESSION['pw-type'] == 'teacher')
					{
						$_SESSION['teacher_id'] = $data->TeacCode;
						?><meta http-equiv="refresh" content="0;url=teacher/index.php"><?
						exit(0);
					}
					if($_SESSION['pw-type'] == 'student')
					{
						$_SESSION['name'] = $data->PREFIX . $data->FIRSTNAME ." ".  $data->LASTNAME;
						?><meta http-equiv="refresh" content="0;url=student/index.php"><?
						exit(0);
					}
					mysql_free_result($result);
					mysql_close($link);
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
    <link href="css2/bootstrap/css/bootstrap.css" type=text/css rel=stylesheet>
    <link href="css2/bootstrap/css/bootstrap-login.css" type=text/css rel=stylesheet>
    
	<link rel="shortcut icon" href="images/favicon.ico" />
	
	<title>-:- ระบบสารสนเทศกิจการนักเรียนโรงเรียนเพชรวิทยาคาร -:-</title>
    
    
    
</head>
<body >

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <!--<h1 class="text-center login-title">Welcome to</h1>-->
                <div class="account-wall">
                    <img class="profile-img" src="images/school_logo.png"alt="">
                    <p class="profile-name">ระบบสารสนเทศกิจการนักเรียน</p>
                    <span class="profile-email">โรงเรียนเพชรวิทยาคาร</span>
                    <form class="form-signin" id="login" method="post" action="" autocomplete="off">
                        <div class="login-errorMessage text-center"><?=$message?></div>
                        <input type="hidden" name="action" id="action" value="login"> 
                        <input type="text"     class="form-control" placeholder="Username" required autofocus name="username" id="username" value="<?=$username;?>" />
                        <input type="password" class="form-control" placeholder="Password" required autofocus name="password" id="password">
                        
                        <select name="type" class="form-control" id="type">
                            <option value="admin">ผู้ดูแลระบบ</option>
                            <option value="teacher">ครูที่ปรึกษา</option>
                            <option value="student">นักเรียน</option>
                        </select>
                        <button class="btn btn-lg btn-primary btn-block" name="button" type="submit">
                        เข้าสู่ระบบ</button>
                        <!--<a href="#" class="need-help">Need help? </a><span class="clearfix"></span>-->
                        <span class="clearfix"></span>
                    </form>
                </div>
                <div class="text-center new-account">พัฒนาระบบโดย: <a href="mailto:taokok@gmail.com">Mr.Surasith Taokok</a>, Tel:+66873708079</div>
            </div>
        </div>
    </div>


</body>
</html>
<?php
	} else {
		?><meta http-equiv="refresh" content="0;url=../pk/logoff.php"><?
	}
?>