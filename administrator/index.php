<?php
	include("../include/class.mysqldb.php");
	include("../include/config.inc.php");
	include("../include/shareFunction.php");
	if(!isset($_SESSION['pw-logined']) && $_SESSION['pw-type'] != 'admin') {
		echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
	} else {
?>

<!DOCTYPE html>
<html xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Mr.Surasith Taokok" />
	<meta name="keywords" content="โรงเรียนเพชรวิทยาคาร" />
	<meta name="description" content="Student Information Project" />	
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
	<link rel="shortcut icon" href="../images/favicon.ico" />
    <link href="../css2/bootstrap/css/bootstrap.css" type=text/css rel=stylesheet>
    <link href="../css2/bootstrap/css/bootstrap-module.css" type=text/css rel=stylesheet>
    
    <script language="javascript" src="../css2/bootstrap/js/jquery-2.1.1.js"></script>
	<script language="javascript" src="../css2/bootstrap/js/bootstrap.js"></script>
    
    
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
<body style="padding-bottom:32px">


        <nav class="navbar navbar-default" role="navigation">
          <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php">SAAIS - โรงเรียนเพชรวิทยาคาร</a>
            </div>
        
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><?=@$_SESSION['name']?></a></li>
                <li><a href="index.php?option=module_profile/changepassword">เปลี่ยนรหัสผ่าน</a></li>
                <li><a href="../logoff.php">ออกจากระบบ</a></li>
              </ul>
              <!--
              <form class="nav navbar-form navbar-right" role="search">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="เลขประจำตัวนักเรียน" >
                </div>
                <button type="submit" class="btn btn-success">ค้นหา</button>
              </form>
            -->

            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>



    
    <div class="container">
        <? if(!isset($_REQUEST['option'])) { ?>
			<div id="cpanel">
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_800/index">
                        <img class="img-module" src="../images/modules/800_clock2.png" alt="กิจกรรมหน้าเสาธง"/>
                        <span>กิจกรรมหน้าเสาธง</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_learn/index">
                        <img class="img-module" src="../images/modules/classroom.png" alt="บันทึกการเข้าชั้นเรียนและการเข้าสอน" align="middle" border="0" />
                        <span>บันทึกการเข้าเรียน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_history/index">
                        <img class="img-module" src="../images/modules/users.png" alt="สืบค้นประวัติ" align="middle" border="0" />
                        <span>สืบค้นประวัติ</span>
                        </a>
                    </div>
                </div>
                <!--
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_gpa/index">
                        <img class="img-module" src="../images/modules/gpa.png" alt="ผลสัมฤทธิ์ทางการเรียน" align="middle" border="0" />
                        <span>ผลการเรียน</span>
                        </a>
                    </div>
                </div>
        -->
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_discipline/index">
                        <img class="img-module" src="../images/modules/discipline.png" alt="งานวินัยนักเรียน" align="middle" border="0" />
                        <span>งานวินัยนักเรียน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                         <a href="index.php?option=module_maps/index">
                        <img class="img-module" src="../images/modules/map.png" alt="แผนที่ติดตามที่อยู่"/>
                        <span>แผนที่ติดตามที่อยู่</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_qa/index">
                        <img class="img-module" src="../images/modules/qa.png" alt="งานประกันคุณภาพ/งานประเมิน" align="middle" border="0" />
                        <span>QA &amp; SAR</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_reports/index">
                        <img class="img-module" src="../images/modules/reports.png" alt="สถิติ/รายงาน" align="middle" border="0" />
                        <span>สถิติ/รายงาน</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_projects/index">
                        <img class="img-module" src="../images/modules/computer.png" alt="กิจกรรม/โครงการ" align="middle" border="0" />
                        <span>กิจกรรม/โครงการ</span>
                        </a>
                    </div>
                </div>
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_tcss/index">
                        <img class="img-module" src="../images/modules/tcss.png" alt="ระบบดูแลช่วยเหลือนักเรียน" align="middle" border="0" />
                        <span>TCSS</span>
                        </a>
                    </div>
                </div>
                
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_moral/index">
                        <img class="img-module" src="../images/modules/objects.png" alt="ระบบสารสนเทศและดูแลธนาคารความดี" align="middle" border="0" />
                        <span>ธนาคารความดี</span>
                        </a>
                    </div>
                </div>

                <div style="float:left;display:none;">
                    <div class="icon">
                        <a href="index.php?option=module_color/index">
                        <img class="img-module" src="../images/modules/color.png" alt="กิจกรรมคณะสี" align="middle" border="0" />
                        <span>กิจกรรมคณะสี</span>
                        </a>
                    </div> 
                </div>
                
                <div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_profile/index">
                        <img class="img-module" src="../images/modules/profile.png" alt="ข้อมูลส่วนตัว" align="middle" border="0" height="48px" />
                        <span>ข้อมูลส่วนตัว</span>
                        </a>
                    </div>
                </div>
                
				<? if($_SESSION['superAdmin']) { ?>
				<div style="float:left;">
                    <div class="icon">
                        <a href="index.php?option=module_config/index">
                        <img class="img-module" src="../images/modules/setting.png" alt="ปรับแต่งระบบ/ข้อมูลพื้นฐาน" align="middle" border="0" />
                        <span>ปรับแต่งระบบ</span>
                        </a>
                    </div>
                </div>
				<? } //end-check_superAdmin ?>
				
                <div style="float:left;display:none;">
                    <div class="icon">
                        <a href="#">
                        <img class="img-module" src="../images/modules/download.png" alt="คู่มือการใช้งาน" align="middle" border="0" />
                        <span>คู่มือการใช้งาน</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php
				 } else { 
            		include($_REQUEST['option'] . ".php"); 
                 } 
            ?>
	</div>


        <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
          <div class="container-fluid text-center">
                สงวนลิขสิทธิ์ &copy; ระบบจัดการสารสนเทศกิจการนักเรียน 
                พัฒนาโดย: Mr.Surasith Taokok, taokok@gmail.com
          </div>
        </nav>


                <!--
        <nav class="navbar sticky-bottom bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Sticky bottom</a>
  </div>
</nav>
                -->

</body>
</html>
<?php
	}// end-else
	$link->closeConnect();
?>

