﻿
<link rel="stylesheet" type="text/css" href="module_800/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_800/js/calendar.js"></script>
<script type="text/javascript">
function check(name,value)
{
	document.getElementById(name).bgColor=value;
}
</script>
<SCRIPT language="Javascript">
      <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      //-->
   </SCRIPT>

<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
  <tr> 
    <td width="6%" align="center"><a href="index.php?option=module_800/index"><img src="../images/modules/800_clock2.png" alt="" width="48" height="48" border="0"/></a></td>
    <td ><strong><font color="#990000" size="4">8.00 O' Clock</font></strong><br /> 
      <span class="normal"><font color="#0066FF"><strong>2.2 แก้ไขการบันทึก(รายบุคคล)</strong></font></span></td>
    <td >&nbsp;</td>
  </tr>
</table>

  <table width="100%" align="center" cellspacing="1" class="admintable">
    <tr> 
      <td colspan="2" class="key">แก้ไขการบันทึกการเ้ข้าแถวหน้าเสาธงรายบุคคล</td>
    </tr>
    <tr> 
      <td width="180px" align="right">เลขประจำตัวนักเรียน :</td>
      <td><input type="text" name="studentid" maxlength="5" size="5" class="inputboxUpdate" onKeyPress="return isNumberKey(event)"/> (ตัวอย่าง เช่น 02363)
	  </td>
    </tr>
	    <tr> 
      <td align="right">วันที่แก้ไข :</td>
        <td><input type="text" id="date" name="date" size="20" class="inputboxUpdate" onClick="showCalendar(this.id)"/> 
          &nbsp; 
          <input type="submit" name="action" value="เรียกดูข้อมูล" />
        </td>
    </tr>
	<?php
		if(isset($_POST['action']))
		{
	?>
	<tr>
		<td class="key" colspan="2">
			ผลการค้นหา
		</td>
	</tr>
	<?php
		}
	?>
		<?php
		if(isset($_POST['saveedit']))
		{
	?>
	<tr>
		<td class="key" colspan="2">
			ผลการแก้ไขข้อมูล
		</td>
	</tr>
	<?php
		}
	?>
			<?php
				$sql = '';

				if(isset($_POST['action']))
				{
					$sql = "select * from student_800 where student_id = '" . $_POST['studentid'] . "' and check_date = '" . $_POST['date'] . "'" ;

					//echo $sql . '<br/>';
					$result = mysqli_query($_connection,$sql);
					if($result)
					{
						if(mysqli_num_rows($result) > 0)
						{
							echo "<tr><td align='right'>วันที่บันทึก :</td><td><font color=\"darkblue\"><b>" . displayDate($_POST['date']) . '- [' . $_POST['date'] ."]</b></font><input type=\"hidden\" value=\"" . $_POST['date'] . "\" name=\"date\"></td></tr>";
							echo '<tr>';
							$sql_student = "select prefix , firstname , lastname, studstatus from students where id = '" . $_POST['studentid'] . "'";
							$res_student = mysqli_query($_connection,$sql_student);
							$dat_student = mysqli_fetch_assoc($res_student);
							echo "<td align='right'>ชื่อ - สกุล :</td>";
							echo "<td><font color=\"darkblue\"><b>" . $dat_student['prefix'] . $dat_student['firstname'] . ' ' . $dat_student['lastname'] . "</b></font></td>";
							echo "</tr>";
							echo "<tr><td align='right'>เลขประจำตัว :</td><td><font color=\"darkblue\"><b>" . $_POST['studentid'] . "</b></font><input type=\"hidden\" value=\"" . $_POST['studentid'] . "\" name=\"studentid\"></td></tr>";
							echo "<tr>";
							echo "<td align='right'>รายการสำหรับตรวจสอบ :</td><td>&nbsp;</td>";
							echo "</tr>";
							$periodRows = 1;
							
							while($dat = mysqli_fetch_assoc($result))
							{	echo "<tr  >";
								echo "<td align=\"right\">การมาเข้าแถว</td>";
								echo "<td id=\"check[$periodRows]\">";
								$p0Check = "";
								$p1Check = "";
								$p2Check = "";
								$p3Check = "";
								$p4Check = "";
								switch($dat['timecheck_id'])
								{	case '00' : $p0Check = "checked"; break;
									case '01' : $p1Check = "checked"; break;
									case '02' : $p2Check = "checked"; break;
									case '03' : $p3Check = "checked"; break;
									case '04' : $p4Check = "checked"; break;
									default : echo "xx";
								}
								echo "<input type='radio' name=\"check[$periodRows]\" value='white'  $p0Check  onclick=\"check(this.name,this.value)\" /> มา | ";
								echo "<input type='radio' name=\"check[$periodRows]\" value='lightgreen'  $p1Check onclick=\"check(this.name,this.value)\" /> กิจกรรม | ";
								echo "<input type='radio' name=\"check[$periodRows]\" value='yellow'  $p2Check  onclick=\"check(this.name,this.value)\" > สาย | ";
								echo "<input type='radio' name=\"check[$periodRows]\" value='blue'  $p3Check  onclick=\"check(this.name,this.value)\" > ลา | ";
								echo "<input type='radio' name=\"check[$periodRows]\" value='red'  $p4Check onclick=\"check(this.name,this.value)\" > ขาด";
								echo "</tr>";
								$periodRows++;
							}//end while
							echo "</td>";
							echo "</tr>";
							echo "<tr>";
							echo "</tr>";
						}
						else
						{
							echo '<tr><td>&nbsp;</td>';
							echo '<td>';
							echo "<font color=\"red\">ผิดพลาด ไม่พบข้อมูลที่ค้นหา </font>";
							echo '</td></tr>';
						}
					}
				}
			?>
			
			<?php
				if(isset($_POST['saveedit']) && $_POST['studentid'] != '' && $_POST['date'] != '' && $_POST['check'][1] != '')
				{
					echo "<tr><td>&nbsp;</td>";
					echo "<td>";
					$sqlEdit = 'update student_800 set ';
					$sqlEdit = $sqlEdit . " timecheck_id = '" . timecheck_id($_POST['check'][1]) . "'";
					$sqlEdit = $sqlEdit . " where student_id = '" . $_POST['studentid'] . "'" ;
					$sqlEdit = $sqlEdit . " and check_date = '" . $_POST['date']  . "'" ;
					$update = mysqli_query($_connection,$sqlEdit) or die ('Error - ' . mysqli_error());
					if($update)
					{
						echo "<font color='green' >บันทึกการแก้ไขข้อมูลเรียบร้อยแล้ว</font>";
					}
					echo "</td>";
					echo "</tr>";
					
				}
				else
				{
					if(!isset($_POST['action']) && isset($_POST['saveedit']))
					{
						echo "<tr><td>&nbsp;</td>";
						echo "<td><font color='red'>ผิดพลาด! คุณยังไม่ได้เลือกข้อมูลนักเรียนที่แก้ไขกรุณาเรียกดูข้อมูลก่อน</font></td>";
						echo "</tr>";
					}
				}
			?>

    <tr> 
        <td colspan="2" class="key">
			<input type="submit" value="บันทึก"  name="saveedit" class="button"/>
			<input type="button" value="ยกเลิก" class="button" onClick="location.href='../administrator/index.php?option=module_800/index'"/>
		</td>
    </tr>
  </table>
</form>
</div>

<?php
function timecheck_id($value)
{
	if($value == 'white') return '00';
	if($value == 'lightgreen') return '01';
	if($value == 'yellow') return '02';
	if($value == 'blue') return '03';
	if($value == 'red') return '04';
	else return 9;
}
?>