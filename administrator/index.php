<?php
    session_start();

	if(!isset($_SESSION['pw-logined']) || $_SESSION['pw-type'] != 'admin') {

        $_host  = $_SERVER['HTTP_HOST'];
        $_uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');

        $_endpoint = "";
        $_uris = explode("/",$_uri);
        for($_i=0;$_i<count($_uris)-1;$_i++){
            $_endpoint .= $_uris[$_i] . "/";

        }

        if(isset($_SESSION['deploy_folder'])){
            header("Location: http://" . $_host . "/" . $_SESSION['deploy_folder'] . "");
            exit;
        }else{
            header("Location: http://" . $_host . $_endpoint);
            exit;
        }

	}
    
    
    else if ($_SESSION['pw-type'] == "admin"){

        
	include("../include/config.inc.php");
    include("../include/config.upload.php");
    include("../include/mysqli.php");
	include("../include/shareFunction.php");
    include("../include/system_log.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Mr.Surasith Taokok" />
	<meta name="keywords" content="โรงเรียนเพชรวิทยาคาร" />
	<meta name="description" content="Student Information Project" />	
	<link rel="shortcut icon" href="../images/favicon.ico" />
    <link href="../css/main.css" type=text/css rel=stylesheet>
    <link href="../css/calendar-mos.css" type=text/css rel=stylesheet>
    <script language="javascript" src="../js/calendar.js"></script>
    <script language="javascript" src="../js/main.js"></script>

	<?php
		include("../fusionII/FusionCharts.php");
		include("../fusionII/FC_Colors.php");
	?>
    <script type="text/javascript" src="../fusionII/charts/FusionCharts.js"></script>
    <script type="text/javascript" language="Javascript" src="../fusionII/assets/ui/js/jquery.min.js"></script>
    <script type="text/javascript" language="Javascript" src="../fusionII/assets/ui/js/lib.js"></script>

	<title>ระบบสารสนเทศกิจการนักเรียนโรงเรียนเพชรวิทยาคาร</title>
</head>
<body>
	<div id="header-bar">
		<div id="header-logoff" style="width:500px;">ยินดีต้อนรับ <?= $_SESSION['name'] ?> 
        &raquo; <a href="index.php">หน้าแรก</a> | <a href="index.php?option=module_profile/changepassword">เปลี่ยนรหัสผ่าน</a>
        | <a href="../logoff.php">ออกจากระบบ</a></div>
    </div>
    <div id="body">
    	<h3><a href="index.php">ระบบสารสนเทศกิจการนักเรียนโรงเรียนเพชรวิทยาคาร</a></h3>
        <div id="left">
        <? if(!isset($_REQUEST['option'])) { ?>
            <div id="slogan">ระบบจัดการสารสนเทศกิจการนักเรียนเพื่อกำกับ ติดตาม ดูแลช่วยเหลือนักเรียน เพื่อเป้าหมายนักเรียนมีคุณลักษณะที่พึงประสงค์</div>
			<div id="cpanel">
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_800/index">
                        <img src="../images/modules/800_clock2.png" alt="กิจกรรมหน้าเสาธง" align="middle" border="0" />
                        <span>กิจกรรมหน้าเสาธง</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_learn/index">
                        <img src="../images/modules/classroom.png" alt="บันทึกการเข้าชั้นเรียนและการเข้าสอน" align="middle" border="0" />
                        <span>บันทึกการเข้าเรียน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_history/index">
                        <img src="../images/users.png" alt="สืบค้นประวัติ" align="middle" border="0" />
                        <span>สืบค้นประวัติ</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_gpa/index">
                        <img src="../images/gpa.png" alt="ผลสัมฤทธิ์ทางการเรียน" align="middle" border="0" />
                        <span>ผลการเรียน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_discipline/index">
                        <img src="../images/discipline.png" alt="งานวินัยนักเรียน" align="middle" border="0" />
                        <span>งานวินัยนักเรียน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                         <a href="index.php?option=module_maps/index">
                        <img src="../images/add.png" alt="แผนที่ติดตามที่อยู่" align="middle" border="0" height="48px" />
                        <span>แผนที่ติดตามที่อยู่</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_qa/index">
                        <img src="../images/qa.png" alt="งานประกันคุณภาพ/งานประเมิน" align="middle" border="0" />
                        <span>QA &amp; SAR</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_reports/index">
                        <img src="../images/chart.png" alt="สถิติ/รายงาน" align="middle" border="0" />
                        <span>สถิติ/รายงาน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_projects/index">
                        <img src="../images/computer.png" alt="กิจกรรม/โครงการ" align="middle" border="0" />
                        <span>กิจกรรม/โครงการ</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_assets/index">
                        <img src="../images/asset.png" alt="ครุภัณฑ์" align="middle" border="0" />
                        <span>ครุภัณฑ์</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_tcss/index">
                        <img src="../images/tcss.png" alt="ระบบดูแลช่วยเหลือนักเรียน" align="middle" border="0" />
                        <span>TCSS</span>
                        </a>
                    </div>
                </div>
                
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_moral/index">
                        <img src="../images/objects.png" alt="ระบบสารสนเทศและดูแลธนาคารความดี" align="middle" border="0" />
                        <span>ธนาคารความดี</span>
                        </a>
                    </div>
                </div>

                <? if(
                        $_SESSION['username'] == "admin" || 
                        $_SESSION['username'] == "tc100" || 
                        $_SESSION['username'] == "tc101" || 
                        $_SESSION['username'] == "tc102" || 
                        $_SESSION['username'] == "tc103" ||
                        $_SESSION['username'] == "tc137" || $_SESSION['username'] == "tc111" ||
                        $_SESSION['username'] == "tc144" || $_SESSION['username'] == "tc113" 
                    ) { ?>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_curriculum/index">
                        <img src="../images/graduation.png" width="49px" alt="Curriculum and Instruction" align="middle" border="0" />
                        <span>บริหารหลักสูตร</span>
                        </a>
                    </div>
                </div>
                <? } ?>

                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_hr/index">
                        <img src="../images/fingerprint.png" width="49px" alt="HR" align="middle" border="0" />
                        <span>HRM</span>
                        </a>
                    </div>
                </div>

                <? if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc100" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") { ?>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_eis/index">
                        <img src="../images/eis2.png" width="49px" alt="EIS" align="middle" border="0" />
                        <span>EIS</span>
                        </a>
                    </div>
                </div>
                <? } ?>

                <div style="float:left;display:none;">
                    <div class="icon">
                        <a href="index.php?option=module_color/index">
                        <img src="../images/color.png" alt="กิจกรรมคณะสี" align="middle" border="0" />
                        <span>กิจกรรมคณะสี</span>
                        </a>
                    </div> 
                </div>
                
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_profile/index">
                        <img src="../images/profile.png" alt="ข้อมูลส่วนตัว" align="middle" border="0" height="48px" />
                        <span>ข้อมูลส่วนตัว</span>
                        </a>
                    </div>
                </div>
                
				<? if(isset($_SESSION['superAdmin'])) { ?>
				<div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_config/index">
                        <img src="../images/config.png" alt="ปรับแต่งระบบ/ข้อมูลพื้นฐาน" align="middle" border="0" />
                        <span>ปรับแต่งระบบ</span>
                        </a>
                    </div>
                </div>
				<? } //end-check_superAdmin ?>
				
                <div style="float:left;display:none;">
                    <div class="icon">
                        <a href="#">
                        <img src="../images/download.png" alt="คู่มือการใช้งาน" align="middle" border="0" />
                        <span>คู่มือการใช้งาน</span>
                        </a>
                    </div>
                </div>


                <div style="clear:both;"> </div>
            </div>
            <?php
				 } else { 
            		include($_REQUEST['option'] . ".php"); 
                 } 
            ?>
        </div>
    <div id="footer">
            สงวนลิขสิทธิ์ &copy; ระบบจัดการสารสนเทศกิจการนักเรียน<br />
			System developed by: Mr.Surasith Taokok, email:taokok@gmail.com, Tel:087 370 8079
    </div>
    </div>
</body>
</html>
<?php
	}// end-else
?>