﻿
<link rel="stylesheet" type="text/css" href="module_discipline/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_discipline/js/calendar.js"></script>
<SCRIPT language="Javascript" type="text/javascript">
	  function checkFormValue(){
	  		if(document.getElementById('dec_detail').value == "" || document.getElementById('dec_detail').value.length < 5 )
				{alert('กรุณาป้อนรายละเอียดการพิจารณาก่อน');document.getElementById('dec_detail').focus();return;}
			if(document.getElementById('plus').checked && document.getElementById('point').value == "")
				{alert('กรุณาระบุคะแนนความประพฤติที่ต้องการหักก่อน');document.getElementById('point').focus();return;}
			if(document.getElementById('dec_date').value == "")
				{alert('กรุณาป้อนข้อมูลวันที่พิจารณาก่อน');document.getElementById('dec_date').focus();return;}
			if(document.getElementById('minus').checked){document.myform.submit();}
			else { document.myform.submit();};
	  }
	  function radioCheck()
	  {
	  	if(document.getElementById('minus').checked)
				{document.getElementById('point').disabled = "disabled"; return;}
		if(document.getElementById('plus').checked)
				{document.getElementById('point').disabled = false; return;}
	  }
</SCRIPT>

<div id="content">
	<? $_disID = isset($_POST['dis_id'])?$_POST['dis_id']:$_REQUEST['dis_id']; ?>
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_discipline/index"><img src="../images/discipline.png" alt="" width="48" height="48" border="0"/></a></td>
			<td><strong><font color="#990000" size="4">งานวินัยนักเรียน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>1.5 บันทึกการดำเนินการหัวหน้าฝ่ายกิจการนักเรียน</strong></font></span></td>
				<td>
					<?php
						if(isset($_REQUEST['acadyear'])){ $acadyear = $_REQUEST['acadyear']; }
						if(isset($_REQUEST['acadsemester'])){ $acadsemester = $_REQUEST['acadsemester']; }
					?>
					ปีการศึกษา
					<?php  
						echo "<a href=\"index.php?option=module_discipline/disciplineDecision&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
						echo '<font color=\'blue\'>' .$acadyear . '</font>';
						echo " <a href=\"index.php?option=module_discipline/disciplineDecision&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
					?>
					ภาคเรียนที่
					<?php 
						if($acadsemester == 1){ echo "<font color='blue'>1</font> , "; }
						else {
							echo " <a href=\"index.php?option=module_discipline/disciplineDecision&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
						}
						if($acadsemester == 2){ echo "<font color='blue'>2</font>"; }
						else {
							echo " <a href=\"index.php?option=module_discipline/disciplineDecision&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
						}
					?>
					<form method="post" autocomplete="off">
						<font color="#000000" size="2"  >
						หมายเลขคดี <input type="text" value="<?=$_disID?>" name="dis_id" maxlength="6" size="5" class="inputboxUpdate" onkeypress="return isNumberKey(event)" /> 
						<input type="submit" name="search" value="เรียกดู" class="button"/>
						</font>
					</form>
			</td>
		</tr>
	</table>

	<? if(isset($_POST['search']) && $_disID == ""){?>
			<br/><center><font color="#FF0000">กรุณาป้อนหมายเลขคดีก่อน</font></center>
	<? } ?>

<form name="myform" method="post">
<? $_timeCount = 0; ?>
<? $_timeAll = 0; ?>
	<? 	if($_disID != "" && !isset($_POST['save'])) { ?>
	<?	$_sql = "select * from student_discipline left outer join student_disciplinestatus
						on student_discipline.dis_id = student_disciplinestatus.dis_id
						left outer join student_investigation on student_discipline.dis_id = student_investigation.dis_id
						where student_discipline.dis_id = '" . $_disID . "' 
						and acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester . "' and dis_status in(5)"; ?>
		<? $_result = mysqli_query($_connection,$_sql); ?>
		<? if(mysqli_num_rows($_result)>0){ ?>
				<? $_dat = mysqli_fetch_assoc($_result); ?>
				<input type="hidden" name="student_id" value="<?=$_dat['dis_studentid']?>" />
				<input type="hidden" name="dis_id" value="<?=$_dat['dis_id']?>" />
				<input type="hidden" name="dis_type" value="<?=$_dat['dis_type']?>" />
				<table width="100%" class="admintable">
					<tr><td class="key" colspan="2">รายละเอียดคดีทั้งหมด</td></tr>
					<tr>
						<td align="right" width="250px"><b>หมายเลขคดี</b></td>
						<td><?=$_dat['dis_id']?></td>
					</tr>
					<tr>
						<td align="right" ><b>วัน/เวลา ที่เกิดเหตุ</b></td>
						<td><?=displayDate($_dat['dis_date']) . " / " . $_dat['dis_time']?> น. </td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>ข้อมูลนักเรียน</b></td>
						<td ><?=studentData($_dat['dis_studentid'],$acadyear)?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>พฤติกรรมที่ไม่พึงประสงค์</b></td>
						<td><?=$_dat['dis_detail']?><br/>[ผู้แจ้ง : <?=$_dat['dis_inform']?>]<br/>[ผู้รับแจ้ง : <?=$_dat['dis_reciever']?>]</td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>รายละเอียดการสอบสวน</b></td>
						<td><?=$_dat['dis_investdetail']?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>ประเภทการกระทำความผิด</b></td>
						<td><?=disType($_dat['dis_type'])?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>ระดับของบทลงโทษ</b></td>
						<td><?=disLevel($_dat['dis_level'])?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>ครูผู้สอบสวน</b></td>
						<td><?=$_dat['dis_investor']?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>วันที่สอบสวน</b></td>
						<td><?=displayDate($_dat['dis_investdate'])?></td>
					</tr>
					<tr>
						<td align="right" valign="top"><b>บันทึกบทลงโทษ</b></td>
						<td><?=disSanction($_dat['dis_id'])?></td>
					</tr>
				</table>
		<?	$_sqlSanc = "select * from student_sanction where dis_id = '" . $_dat['dis_id'] . "' order by id "; ?>
		<?	$_resSanc = mysqli_query($_connection,$_sqlSanc);	?>
		<?	if(mysqli_num_rows($_resSanc) > 1){ ?>
				<table width="100%" class="admintable">
					<tr>
						<td align="center" class="key">ครั้งที่</td>
						<td align="center" class="key" width="300px">กิจกรรมที่ปฏิบัติ</td>
						<td align="center" class="key">ระยะเวลา<br/>ที่ปฏิบัติ(นาที)</td>
						<td align="center" class="key">ครูผู้ดูแล</td>
						<td align="center" class="key">วันที่ทำกิจกรรม</td>
					</tr>
					<? $_i = 0; ?>
					<? while($_datSanc = mysqli_fetch_assoc($_resSanc)){ ?>
					<? $_timeAll =  $_timeAll + $_datSanc['sanc_alltime'] ;	?>
					<? if($_i == 0){ $_datSanc = mysqli_fetch_assoc($_resSanc); } ?>
					<? $_timeCount = $_timeCount + $_datSanc['sanc_time'] ; ?>
					<tr>
						<td align="center" valign="top"><?=++$_i?></td>
						<td  width="300px"><?=$_datSanc['sanc_detail']?></td>
						<td align="center"><?=displayTime($_datSanc['sanc_time'])?></td>
						<td><?=$_datSanc['sanc_teacher']?></td>
						<td align="center"><?=displayDate($_datSanc['sanc_date'])?></td>
					</tr>
					<? } //end while ?>
					<tr bgcolor="lightblue">
						<td align="center" colspan="2"><b>รวมระยะเวลาที่ปฏิบัติกิจกรรมไปแล้ว</b></td>
						<td align="center" ><b><?=displayTime($_timeCount)?></b></td>
						<td align="center"><b> เหลือ </b></td>
						<td align="center"><b><font color="red"><?=displayTime($_timeAll-$_timeCount)?></font></b></td>
					</tr>
				</table><br/>
			<? } //end if ตรวจสอบข้อมูลการดำเนินกิจกรรมปรับเปลี่ยนพฤติกรรม ?>
			<table width="70%" class="admintable" >
				<tr height="35px">
					<td colspan="2" class="key">รายการบันทึกการพิจารณาของหัวหน้าฝ่าย</td>
				</tr>
				<tr>
					<td  width="340px" valign="top" align="right"><b>ความเห็น</b></td>
					<td><textarea id="dec_detail" name="dec_detail" cols="40" rows="3" class="inputboxUpdate"></textarea></td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>คะแนนความประพฤติ</b></td>
					<td>
						<input type="radio" id="minus" checked="checked" name="dec_point" value="ไม่หักคะแนน" onclick="radioCheck()" /> ไม่หักคะแนนความประพฤติ <br/>
						<input type="radio" id="plus" name="dec_point" value="หักคะแนน" onclick="radioCheck()" /> หักคะแนนความประพฤติ  <br/>
						<input type="text" name="point" size="3" maxlength="2" onkeypress="return isNumberKey(event)" id="point" class="noborder2"/> คะแนน
					</td>
				</tr>
				<tr>
					<td align="right"><b>วันที่พิจารณา</b></td>
					<td ><input type="text" name="dec_date" id="dec_date" onClick="showCalendar(this.id)" class="noborder2" /></td>
				</tr>
				<tr>
					<td></td>
					<td><br/>
						<input type="hidden" name="save" value="save" />
						<input type="button" class="button" value="บันทึก" onclick="checkFormValue()"> 
						<input type="button" class="button" value="ยกเลิก" onclick="location.href='index.php?option=module_discipline/index'" />
					</td>
				</tr>
			</table>
		<? } else { echo "<font color='red'><center><br/>ไม่พบข้อมูลคดีตามหมายเลขคดีที่ค้นหา กรุณาลองใหม่อีกครั้ง</center></font>"; } // end if-else ตรวจสอบว่ามีคดีนี้อยู่ในสถานะนี้หรือไม่ ?>
	<? } //end if ตรวจสอบว่ามีการเรียกดูข้อมูลหรือไม่ ?>
</form>

<?php
	if(isset($_POST['save']))
	{
			//update student_disciplinestatus -> status = 6 && sanc_status = 02;
			//update student_discipline -> point = $_POST['point'];
			//insert student_decision-> 1 Record;
			$_sql = "";
			if($_POST['dec_point'] == "หักคะแนน")
			{
				$_sql = "update student_disciplinestatus set 
							dis_status = 6 , 
							sanc_status = '02',
							point = '" . $_POST['point'] . "' where dis_id = '" . $_POST['dis_id'] . "'";
			}
			else
			{
				$_sql = "update student_disciplinestatus set 
							dis_status = 6 , 
							sanc_status = '02',
							point = '0' where dis_id = '" . $_POST['dis_id'] . "'";
			}
			$_sql2 = "insert into student_decision values('" . $_POST['dis_id'] . "','" . $_POST['dec_detail'] . "','" . $_POST['dec_date'] . "','" . $_POST['dec_point'] . "')";
			$_sql3 = "update students set points = (points - '" .$_POST['point'] . "') where ID = '" . $_POST['student_id'] . "' and xEDBE = '" . $acadyear . "'";

			$_result1 = mysqli_query($_connection,$_sql);
			$_result2 = mysqli_query($_connection,$_sql2);				
			$_result3 = mysqli_query($_connection,$_sql3);
			if($_result1 && $_result2 && $_result3) { 
					echo "<center><font color='green'><br/>บันทึกข้อมูลการพิจารณาคดีเรียบร้อยแล้ว <br/>
							ดำเนินการต่อคลิกที่ : <a href='index.php?option=module_discipline/disciplineFinished&dis_id=" .  $_POST['dis_id'] . "&acadyear=". $acadyear . "&acadsemester=". $acadsemester . "'>"
								.  $_POST['dis_id'] ."</a>
							</font></center>"; 
			}
			else { echo "<center><font color='red'><br/>เกิดข้อผิดพลาดเนื่องจาก - " . mysqli_error(). "</font></center>"; }
	}
?>
</div>


<?php
	
	function studentData($_id,$acadyear)
	{
		$_sql = "select id,prefix,firstname,lastname,xlevel,xyearth,room,p_village from students where xedbe = '" . $acadyear  ."' and id = '". $_id . "'";
		$_result = mysqli_query($_connection,$_sql);
		$_dat = mysqli_fetch_assoc($_result);
		$str = "";
		$str = $str . "เลขประจำตัว: " . $_dat['id'] . "<br/>ชื่อ-สกุล: ". $_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname'] . "<br/>";
		$str = $str . "ระดับชั้น: " .($_dat['xlevel']==4?$_dat['xyearth']+3:$_dat['xyearth']) . "/" . $_dat['room'] . "<br/>";
		$str = $str . "หมู่บ้าน: " . $_dat['p_village'] ;
		return $str;
	}
	function disType($_value)
	{
		switch ($_value)
		{
			case "00": return "ไม่มีความผิด"; break;
			case "10": return "ตรงต่อเวลา"; break;
			case "11": return "การเข้าชั้นเรียน"; break;
			case "12": return "ทะเลาะวิวาท"; break;
			case "13": return "ลักขโมย"; break;
			case "14": return "สิ่งเสพติด"; break;
			case "15": return "อาวุธ"; break;
			case "16": return "สื่อลามกอนาจาร"; break;
			case "17": return "พฤติกรรม"; break;
			case "18": return "เครื่องแต่งกาย"; break;
			case "19": return "อุปกรณ์อิเล็กทรอนิกส์"; break;
			case "20": return "เรื่องทั่วไป"; break;
			default : return "ผิดพลาด";
		}
	}
	function disLevel($_value)
	{
		switch ($_value)
		{
			case "00": return "ไม่มีความผิด"; break;
			case "10": return "สถานเบา"; break;
			case "11": return "สถานปานกลาง"; break;
			case "12": return "สถานหนัก"; break;
			case "13": return "สถานหนักมาก"; break;
			default : return "ผิดพลาด";
		}
	}
	function disSanction($_disID)
	{
		$_sql = "select * from student_sanction where dis_id = '" . $_disID . "' order by id ";
		$_result = mysqli_query($_connection,$_sql);
		$_dat = mysqli_fetch_assoc($_result);
		
		$_str = "";
		$_str = $_str . $_dat['sanc_detail'] . "<br/>"
				. "[ผู้กำหนดบทลงโทษ : " . $_dat['sanc_teacher'] . "] <br/>" 
				. "[ระยะเวลาการลงโทษทั้งหมด : " . displayTime($_dat['sanc_alltime']) . "] <br/>"
				. "[วันที่กำหนดบทลงโทษ : " . displayDate($_dat['sanc_date']) . "]" ; 
		return $_str;
		
	}
	function displayTime($_value){
		if($_value != ""){
			$_textTime = "";
			if($_value/60 >= 1){ $_textTime .= (int)($_value/60) ." ชั่วโมง "; }
			if($_value%60 >  0){$_textTime .= (int)($_value%60) . " นาที"; }
			return $_textTime ;
		}else { return "-";}
	}
?>