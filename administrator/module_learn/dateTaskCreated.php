
	  <div id="content">
	  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
       <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.2 ตรวจสอบวันที่สร้างงานบันทึกแล้ว</strong></font></span></td>
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

			$_sql_progress = "
			select 
				task_date,weekday,
				sum(if(task_status=0,1,0)) as 'unsuccess',
				sum(if(task_status=1,1,0)) as 'success' 
			from student_learn_task
			where
				acadyear = '" . $acadyear . "' and
				acadsemester = '". $acadsemester . "' 
			group by task_date,weekday
			order by task_date ";

			$_res_progress = mysqli_query($_connection,$_sql_progress);
			$_counter = 1;

		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_learn/dateTaskCreated&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/dateTaskCreated&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_learn/dateTaskCreated&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_learn/dateTaskCreated&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
	  </td>
    </tr>
  </table>

<? if($_res_progress){ ?>
	<div align="center">
		<table align="center" class="admintable">
			<tr>
				<td colspan="6" class="key">รายละเอียดวันที่สร้างงานบันทึกข้อมูลการเข้าเรียนและจำนวนคาบที่บันทึกและยังไม่บันทึกข้อมูลการเข้าเรียน</td>
			</tr>
			<tr>
				<td class="key" align="center">ลำดับที่</td>
				<td class="key" align="center">วัน</td>
				<td class="key" align="center" width="120px">วันที่</td>
				<td class="key" align="center">บันทึกแล้ว</td>
				<td class="key" align="center">ยังไม่บันทึก</td>
				<td class="key" align="center">-</td>
			</tr>
			<? while($_dat = mysqli_fetch_assoc($_res_progress)){  ?>
			<tr>
				<td align="center"><?=$_counter++?></td>
				<td><?=displayDayOfWeek($_dat['weekday'])?></td>
				<td align="center"><?=displayDateShortMonth($_dat['task_date'])?></td>
				<td align="right"><?=$_dat['success']?> &nbsp; </td>
				<td align="right"><?=$_dat['unsuccess']?> &nbsp; </td>
				<td align="center">
					<a href="index.php?option=module_learn/dateTaskDetail&date=<?=$_dat['task_date']?>" >
						รายละเอียด
					</a>
				</td>
			</tr>
			<? } ?>
		</table>
	</div>
<? } ?>


</div>
	
	