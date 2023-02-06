
<?php
	include("../include/config.upload.php");
?>


<div id="content">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
		<tr>
			<td width="6%" align="center"><img src="../images/profile.png" alt="" height="48" /></td>
			<td >Profile - ข้อมูลส่วนตัว<br />
			<span class="normal"> 
					ส่วนจัดการข้อมูลส่วนตัวผู้ใช้งานระบบ
			<td width="100px">&nbsp;</td>
		</tr>
	</table>
<?php
	$_res = mysqli_query($_connection,"select * from teachers where username = '" . $_SESSION['username'] . "'");
	$_dat = mysqli_fetch_assoc($_res);
?>

	<div align="center">
		<table  align="center" class="admintable">
			<tr>
				<td colspan="2" class="key">
					1. จัดการข้อมูลส่วนตัว
				</td>
				<td class="key" align="center" colspan="2">
					-:- ข้อมูลส่วนตัว -:-
				</td>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td width="500px">
					<a href="index.php?option=module_profile/changeprofile">1.1 แก้ไขข้อมูลส่วนตัว</a>
				</td>
				<td rowspan="12" align="right" valign="top">
					<table width="300px" cellspacing="1" class="admintable">
						<tr>
							<td width="100px" align="right">TeacherCode : </td>
							<td><b><?=$_dat['TeacCode']?></b></td>
						</tr>
						<tr>
							<td align="right">ชื่อ-สกุล : </td>
							<td><b><?=$_dat['PREFIX'] . $_dat['FIRSTNAME'] . ' ' . $_dat['LASTNAME'] ?></b></td>
						</tr>
						<tr>
							<td align="right">ชื่อเล่น : </td>
							<td><b><?=$_dat['NICKNAME']?></b></td>
						</tr>
						<tr>
							<td align="right">ตำแหน่ง : </td>
							<td><b><?=$_dat['POSITION']?></b></td>
						</tr>
						<tr>
							<td align="right">โทรศัพท์ :</td>
							<td><b><?=$_dat['T_PHONE']?></b></td>
						</tr>
						<tr>
							<td align="right">มือถือ : </td>
							<td><b><?=$_dat['t_mobile']?></b></td>
						</tr>
						<tr>
							<td align="right">อีเมล : </td>
							<td><b><?=$_dat['t_email']?></b></td>
						</tr>
					</table>
				</td>
				<td class="key" rowspan="12" align="right" width="145px">
					<img src="../images<?=$_img_teacher_folder;?>/TC<?=$_dat['TeacCode']?>.jpg" 
						 width="140px" height="190px" alt="รูปภาพที่ใช้ในระบบ" />
				</td>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td>
					<a href="index.php?option=module_profile/AdminEditPic&teacher_code=<?=$_dat['TeacCode']?>">1.2 อัพโหลดรูปโปรไฟล์</a> 
				</td>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td>
					<a href="index.php?option=module_profile/changepassword">1.3 เปลี่ยนรหัสผ่าน</a>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="key">
					2. สรุปรายงาน/สารสนเทศ &nbsp; &nbsp;
				</td>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td>
					2.1 ประวัติการล็อกอินเข้าสู่ระบบ
				</td>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td>
					2.2 ตรวจสอบภาระงาน
				</td>
			</tr>
			<tr>
				<td width="20px">&nbsp;</td>
				<td>
					2.3 ตรวจสอบสิทธิการใช้งาน
				</td>
			</tr>
		</table>
	</div>
</div>

