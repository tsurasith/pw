﻿
	  <div id="content">
	  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_800/index"><img src="../images/modules/800_clock2.png" alt="" width="48" height="48" border="0"/></a></td>
      <td width="45%"><strong><font color="#990000" size="4">8.00 O' Clock</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.2 ตรวจสอบวันที่สร้างงานบันทึกข้อมูลแล้ว</strong></font></span></td>
      <td >
	  	<?php
			if(isset($_REQUEST['acadyear']))
			{
				$acadyear = $_REQUEST['acadyear'];
			}
			if(isset($_REQUEST['acadsemester']))
			{
				$acadsemester = $_REQUEST['acadsemester'];
			}
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_800/dateTaskCreated&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_800/dateTaskCreated&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_800/dateTaskCreated&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_800/dateTaskCreated&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
	  </td>
    </tr>
  </table>
	<form method="post" action="index.php?option=module_800/dateTaskDetail">
		<table width="100%" align="center" class="admintable">
			<tr>
				<td colspan="2" class="key">รายละเอียดสรุปการสร้างงานบันทึกข้อมูลหน้าเสาธง</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="400px" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td align="right">วันที่บันทึกแล้วเสร็จ</td>
    <td width="80px" align="right">
		<?php
			$sql1 = "select distinct task_date from student_800_task where task_status = '1' and acadyear='" .$acadyear . "' and acadsemester='" . $acadsemester . "' order by task_date";
			$res1 = mysqli_query($_connection,$sql1);
			echo mysqli_num_rows($res1);
		?>
	</td>
    <td width="80px" align="center">วัน</td>
  </tr>
  <tr>
    <td align="right">วันที่บันทึกไม่เรียบร้อย</td>
    <td align="right">
		<?php
			$sql2 = "select distinct task_date from student_800_task where task_status = '0' and acadyear='" .$acadyear . "' and acadsemester='" . $acadsemester . "' order by task_date";
			$res2 = mysqli_query($_connection,$sql2);
			echo mysqli_num_rows($res2);
		?>
	</td>
    <td align="center">วัน</td>
  </tr>
  <tr>
    <td align="right">รวม</td>
    <td align="right">
		<?php
			$x = mysqli_num_rows($res1) + mysqli_num_rows($res2);
			echo $x ;
		?>
	</td>
    <td align="center">วัน</td>
  </tr>
</table>

				</td>
			</tr>
			<tr>
				<td class="key" colspan="2">รายการแสดงงานบันทึกข้อมูลในแต่ละวัน</td>
			</tr>
			<tr>
				<td width="50%" class="key">รายการวันที่บันทึกข้อมูลแล้วเสร็จ</td>
				<td class="key">รายการบันทึกข้อมูลไม่เรียบร้อย</td>
			</tr>
			<tr>
				<td valign="top">
					<?php echo gentDateDetail($res1,"1"); ?>
				</td>
				<td valign="top">
					<?php echo gentDateDetail($res2,"0"); ?>
				</td>
			</tr>
		</table>
 	</form>
	<? mysqli_free_result($res1); mysqli_free_result($res2); ?>
</div>
	
	<?php
		function gentDateDetail($result,$status)
		{
			$text = "<table bgcolor=\"lightpink\" cellspacing=\"1\" >";
			$_count = 1;
			while($dat = mysqli_fetch_assoc($result))
			{
				$text .= "<tr bgcolor=\"white\"><td align=\"center\">" . $_count++ . "</td>";
				$text .= "<td>" . displayDate($dat['task_date']) . "</td>";
				$text .= "<td>" . "<a href=\"index.php?option=module_800/dateTaskDetail&date=". $dat['task_date'] . "\" >
							รายละเอียด</a></td></tr>";
			}
			$text = $text . "</table>";
			return $text;
		}
	?>