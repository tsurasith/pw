


<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.2 จัดการรายวิชาที่สอน</strong></font></span></td>
      <td align="right">
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php 
					echo '<font color=\'blue\'>' .$acadyear . '</font>'; 	?>
		ภาคเรียนที่   <?php echo "<font color='blue'>1</font> , ";	?>
		</font>
	   </td>
    </tr>
  </table>
  <?php
	$_xx = explode("|",$_REQUEST['subject']);

  ?>
<div align="center">
	<br/><br/><br/>
	<font color='red'>
		คุณต้องการที่จะลบรหัสวิชา <b><?=$_xx[0]?></b> 
		สอนวัน<b><?=displayWeekday($_REQUEST['weekday'])?></b> 
		คาบเรียนที่ <b><?=$_REQUEST['period']?> </b>
		ออกจากตารางสอนของท่าน "ใช่" หรือ "ไม่ใช่"<br/>หากต้องการลบให้กด "ยืนยันลบ" หากไม่ต้องการให้กด "ย้อนกลับ"</font>
	<br/><br/>
	<table align="center">
		<tr>
			<td>
				<form method="post" action="index.php?option=module_gpa/<?=$_REQUEST['page']?>">
					<input type="submit" name="delete" 		value="ยืนยัน" />
					<input type="hidden" name="teacher_id" 	value="<?=$_REQUEST['teacher_id']?>" />
					<input type="hidden" name="acadyear" 	value="<?=$_REQUEST['acadyear']?>" />
					<input type="hidden" name="acadsemester" value="<?=$_REQUEST['acadsemester']?>" />
					<input type="hidden" name="weekday" 	value="<?=$_REQUEST['weekday']?>" />
					<input type="hidden" name="period" 		value="<?=$_REQUEST['period']?>" />
					<input type="hidden" name="subject_code" value="<?=$_xx[0]?>" />
					<input type="hidden" name="search" />
				</form>
			</td>
			<td>
				<form method="post" action="index.php?option=module_gpa/<?=$_REQUEST['page']?>">
					<input type="submit" name="search" 		value="ย้อนกลับ" />
					<input type="hidden" name="teacher_id" 	value="<?=$_REQUEST['teacher_id']?>" />
					<input type="hidden" name="acadyear" 	value="<?=$_REQUEST['acadyear']?>" />
					<input type="hidden" name="acadsemester" value="<?=$_REQUEST['acadsemester']?>" />
					<input type="hidden" name="weekday" 	value="<?=$_REQUEST['weekday']?>" />
					<input type="hidden" name="period" 		value="<?=$_REQUEST['period']?>" />
				</form>
			</td>
		</tr>
	</table>
</div>
</div>

<?php
	function displayWeekday($_text){
		switch ($_text){
			case "1" : return "จันทร์"; break;
			case "2" : return "อังคาร"; break;
			case "3" : return "พุธ"; break;
			case "4" : return "พฤหัสบดี"; break;
			case "5" : return "ศุกร์"; break;
			default: return "";
		}
	}

?>