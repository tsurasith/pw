﻿
<link rel="stylesheet" type="text/css" href="module_discipline/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_discipline/js/calendar.js"></script>
<SCRIPT language="Javascript" type="text/javascript">
	  
	  function checkFormValue()
	  {
			if(document.getElementById('sanc_detail').value == "" && document.getElementById('sanc_detail').value.length < 5 )
				{ alert('กรุณาป้อนรายละเอียดการทำกิจกรรมปรับเปลี่ยนพฤติกรรม'); document.getElementById('sanc_detail').focus(); return;}
			
			if(document.getElementById('sanc_time').value == "")
				{ alert('กรุณาป้อนข้อมูลเวลาที่ปฏิบัติกิจกรรมก่อน'); document.getElementById('sanc_time').focus(); return;}
				
			if(document.getElementById('sanc_teacher').value == "")
				{ alert('กรุณาระบุครูผู้ควบคุม ตรวจสอบ การทำกิจกรรมปรับเปลี่ยนพฤติกรรมก่อน'); document.getElementById('sanc_teacher').focus(); return;}
			
			if(document.getElementById('sanc_date').value == "" || document.getElementById('sanc_date').value.length < 10)
				{ alert('กรุณาระบุวันที่นักเรียนทำกิจกรรมปรับเปลี่ยนพฤติกรรมก่อน'); document.getElementById('sanc_date').focus(); return;}
			else { document.myform.submit();}
	  }
</SCRIPT>

<div id="content">
	<? $_disID = isset($_POST['dis_id'])?$_POST['dis_id']:$_REQUEST['dis_id']; ?>
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr>
			<td width="6%" align="center"><a href="index.php?option=module_discipline/index"><img src="../images/discipline.png" alt="" width="48" height="48" border="0"/></a></td>
			<td >
				<strong><font color="#990000" size="4">งานวินัยนักเรียน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>1.4 บันทึกการทำกิจกรรมปรับเปลี่ยนพฤติกรรม</strong></font></span>
			</td>
			<td >
				<?php
					if(isset($_REQUEST['acadyear'])){ $acadyear = $_REQUEST['acadyear'];}
					if(isset($_REQUEST['acadsemester'])){ $acadsemester = $_REQUEST['acadsemester'];}
				?>
				ปีการศึกษา<?php  
							echo "<a href=\"index.php?option=module_discipline/disciplineActivate&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo '<font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_discipline/disciplineActivate&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
				ภาคเรียนที่   <?php 
							if($acadsemester == 1){ echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_discipline/disciplineActivate&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2){ echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_discipline/disciplineActivate&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
						?>
				<br/>
				<font color="#000000"   size="2">
					<form action="" method="post" autocomplete="off">
						หมายเลขคดี <input type="text" name="dis_id" value="<?=$_disID?>" maxlength="6" size="5" class="inputboxUpdate" onkeypress="return isNumberKey(event)" /> 
					<input type="submit" name="search" value="เรียกดู" class="button"/>
					</form>
				</font>
			</td>
		</tr>
	</table>

	<? if(isset($_POST['search']) && $_disID == ""){?>
			<br/><center><font color="#FF0000">กรุณาป้อนหมายเลขคดีก่อน</font></center>
	<? } ?>

	<form name="myform" method="post">
<?php
	$_timeCount = 0;
	$_timeAll = 0;		

	if($_disID != "" && !isset($_POST['save']))
	{
		$_sql = "select * from student_discipline left outer join student_disciplinestatus
						on student_discipline.dis_id = student_disciplinestatus.dis_id
						left outer join student_investigation on student_discipline.dis_id = student_investigation.dis_id
						where student_discipline.dis_id = '" . $_disID . "' 
						and acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester . "' and dis_status in(3,4)";
		$_result = mysqli_query($_connection,$_sql);
		if(mysqli_num_rows($_result)>0)
		{	$_dat = mysqli_fetch_assoc($_result); ?>
		
			<input type="hidden" name="student_id" value="<?=$_dat['dis_studentid']?>" />
			<input type="hidden" name="dis_id" value="<?=$_dat['dis_id']?>" />
			<input type="hidden" name="dis_type" value="<?=$_dat['dis_type']?>" />
			<table width="100%" align="center" cellspacing="1" class="admintable" border="0" cellpadding="3">
			<tr>
				<td colspan="2" class="key">รายละเอียดพฤติกรรมไม่พึงประสงค์ [ส่วนงานบันทึกการทำกิจกรรมปรับเปลี่ยนพฤติกรรม]</td>
			</tr>
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
				<td><?=$_dat['dis_detail']?><br/>
					[ผู้แจ้ง : <?=$_dat['dis_inform']?>]<br/>
					[ผู้รับแจ้ง :<?=$_dat['dis_reciever']?>] 
				</td>
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
		

		<? $_sqlSanc = "select * from student_sanction where dis_id = '" . $_dat['dis_id'] . "' order by id ";?>
		<?  $_resSanc = mysqli_query($_connection,$_sqlSanc);	 ?>
		<? if(mysqli_num_rows($_resSanc) > 1) { ?>
				<table width="100%" class="admintable">
					<tr>
						<td align="center" class="key">ครั้งที่</td>
						<td align="center" class="key">กิจกรรมที่ปฏิบัติ</td>
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
						<td align="center"><?=++$_i?></td>
						<td><?=$_datSanc['sanc_detail']?></td>
						<td align="center"><?=displayTime($_datSanc['sanc_time'])?></td>
						<td><?=$_datSanc['sanc_teacher']?></td>
						<td align="center"><?=displayDate($_datSanc['sanc_date'])?></td>
					</tr>
					<? }//end while ?>
					<tr bgcolor="lightblue">
						<td align="center" colspan="2"><b>รวมระยะเวลาที่ปฏิบัติกิจกรรมไปแล้ว</b></td>
						<td align="center" ><b><?=displayTime($_timeCount)?></b></td>
						<td align="center"><b> เหลือ </b></td>
						<td align="center"><b><font color="red"><?=displayTime($_timeAll-$_timeCount)?></font></b></td>
					</tr>
				</table>
		<? }///---end if ?>
				<br/>
				<table width="70%" class="admintable">
					<tr>
						<td colspan="2" class="key">รายการบันทึกกิจกรรมปรับเปลี่ยนพฤติกรรม</td>
					</tr>
					<tr>
						<td  width="340px" valign="top" align="right">กิจกรรม</td>
						<td>
							<textarea id="sanc_detail" name="sanc_detail" class="inputboxUpdate" cols="40" rows="3"></textarea>
						</td>
					</tr>
					<tr>
						<td align="right">ระยะเวลา</td>
						<td><input type="text" name="sanc_time" class="inputboxUpdate" size="3" maxlength="3" onkeypress="return isNumberKey(event)" id="sanc_time"/> นาที(ตัวเลขเท่านั้น)</td>
					</tr>
					<tr>
						<td align="right">ครูผู้ควบคุม</td>
						<td>
							<? $_sqlTeacher = "select teaccode,prefix,firstname,lastname from teachers where type in ('admin','teacher') order by firstname"; ?>
							<? $_resTeacher = mysqli_query($_connection,$_sqlTeacher); ?>
							<select id="sanc_teacher" name="sanc_teacher" class="inputboxUpdate">
								<option value=""></option>
							<? while($_dat = mysqli_fetch_assoc($_resTeacher)){ ?>
								<option value="<?=$_dat['prefix'].$_dat['firstname'].' '.$_dat['lastname']?>" ><?=$_dat['prefix'].$_dat['firstname'].' '.$_dat['lastname']?></option>
							<? } mysqli_free_result($_resTeacher);//end while ?>
							</select>
							<font color="#FF0000">*</font>
						</td>
					</tr>
					<tr>
						<td align="right">วันที่ทำกิจกรรม</td>
						<td>
							<input type="text" name="sanc_date" id="sanc_date" class="inputboxUpdate" size="10" onClick="showCalendar(this.id)" />
							<input type="checkbox" name="sanc_finish" /> ดำเนินกิจกรรมครบ
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="hidden" name="save" value="save" />
							<input type="button" class="button" value="บันทึก" onclick="checkFormValue()"  />
							<input type="button" class="button" value="ยกเลิก" onclick="location.href='index.php?option=module_discipline/index'"  />
						</td>
					</tr>
				</table>
	<?	} //end check submit data
		else { echo "<center><font color='red'><br/>ไม่พบข้อมูลคดีตามหมายเลขคดีที่ค้นหา กรุณาลองใหม่อีกครั้ง</font></center>"; }
	}
?>
</form>

<?php
	if(isset($_POST['save']))
	{
			//update student_disciplinestatus -> status = 4 && sanc_status = 02;
			//insert student_sanction-> 1 Record;
			$_sql = "update student_disciplinestatus set dis_status = 4 , sanc_status = '02' where dis_id = '" . $_POST['dis_id'] . "'";
			$_sql2 = "insert into student_sanction values(null,'" . $_POST['dis_id'] . "',
																'" . $_POST['sanc_detail'] . "',
																'" . $_POST['sanc_time'] . "',
																'0',
																'" . $_POST['sanc_teacher'] . "',
																'" . $_POST['sanc_date'] . "')";
			$_result1 = mysqli_query($_connection,$_sql);
			$_result2 = mysqli_query($_connection,$_sql2);				
			if($_result1 && $_result2)
			{
				echo "<br/><center>";
				echo "<font color=\"blue\">บันทึกข้อมูลการทำกิจกรรมเรียบร้อยแล้ว<br/>";
				echo "</font></center>";
				if(isset($_POST['sanc_finish']))
				{
					$_sql3 = "update student_disciplinestatus set dis_status = 5 , sanc_status = '02' where dis_id = '" . $_POST['dis_id'] . "'";
					$_result3 = mysqli_query($_connection,$_sql3);
					if(isset($_POST['sanc_finish']))
					{
						echo "<center><br/>";
						echo "<font color='green'> นักเรียนได้ทำกิจกรรมปรับเปลี่ยนพฤติกรรมครบกำหนดแล้ว !!! <br/>
								ดำเนินการต่อคลิกที่ : <a href='index.php?option=module_discipline/disciplineDecision&dis_id=" .  $_POST['dis_id'] . "&acadyear=". $acadyear . "&acadsemester=". $acadsemester . "'>"
								.  $_POST['dis_id'] ."</a>
								</font></center>";
					}
				}
			}else { echo "<center><font color=\"red\"><br/>เกิดข้อผิดพลาดเนื่องจาก - " . mysqli_error() . "</font></center>";}
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
				. "[ระยะเวลาการลงโทษทั้งหมด : " 
				. (($_dat['sanc_alltime']/60)>=1?floor($_dat['sanc_alltime']/60):"-") . " ชั่วโมง "
				. (($_dat['sanc_alltime']%60)>0?($_dat['sanc_alltime']%60):"-") . " นาที] <br/>"
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