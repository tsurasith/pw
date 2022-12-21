
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

	<?php
		$_sql_summary = "
				SELECT 
				    sum(if(task_status=1,1,0)) as 'checked',
					sum(if(task_status=0,1,0)) as 'unchecked'
				FROM
					student_800_task
				WHERE
					acadyear = '" . $acadyear . "' and
					acadsemester = '" . $acadsemester . "' 
			";
		$_resSummary = mysqli_query($_connection,$_sql_summary);
		$_total_rows = mysqli_num_rows(mysqli_query($_connection,"select * from student_800_task where acadyear='" .$acadyear."' and acadsemester='" .$acadsemester. "' limit 10"));


		$_sql_summary_date = "
				select
					t.acadyear,t.acadsemester,count(distinct task_date) as 'total',
					un.uncompleted
				from student_800_task t
					left join 
					(
						select
						acadyear,acadsemester,count(distinct task_date) as 'uncompleted'
						from student_800_task
						where
							acadyear = '" . $acadyear . "'
							and acadsemester = '" . $acadsemester . "'
							and task_status = 0
						group by 
						acadyear,acadsemester
					) as un on (t.acadyear = un.acadyear and t.acadsemester = un.acadsemester)
				where
					t.acadyear = '" . $acadyear . "'
					and t.acadsemester = '" . $acadsemester . "'
				group by 
					t.acadyear,t.acadsemester	
		"; 

		$_resSummaryDate = mysqli_query($_connection,$_sql_summary_date);

	?>
	<div align="center">
		<? if($_total_rows>2){ ?> 
			<table align="center" class="admintable">
				<tr>
					<td colspan="2" class="key">รายละเอียดสรุปการบันทึกข้อมูลกิจกรรมหน้าเสาธง</td>
				</tr>
				<tr>
					<td>
						<? $_datX = mysqli_fetch_assoc($_resSummaryDate); ?>
						<table width="350px" border="0" cellspacing="1" cellpadding="1">
							<tr>
								<td align="right">วันที่บันทึกแล้วเสร็จ</td>
								<td width="60px" align="right">
									<?=$_datX['total']-$_datX['uncompleted']?>
								</td>
							<td width="40px" align="center">วัน</td>
							</tr>
							<tr>
								<td align="right">วันที่บันทึกไม่เรียบร้อย</td>
								<td align="right">
									<?=$_datX['uncompleted']?>
								</td>
								<td align="center">วัน</td>
							</tr>
							<tr>
								<td align="right">รวม</td>
								<td align="right">
								<?=$_datX['total']?>
								</td>
								<td align="center">วัน</td>
							</tr>
						</table>

					</td>
					<td>
						<? $_dat = mysqli_fetch_assoc($_resSummary); ?>
						<table width="350px" border="0" cellspacing="1" cellpadding="1">
							<tr>
								<td align="right">จำนวนห้องเรียนที่บันทึกแล้วเสร็จ</td>
								<td width="60px" align="right">
									<?=$_dat['checked']?>
								</td>
							<td width="40px" align="center">ห้อง</td>
							</tr>
							<tr>
								<td align="right">จำนวนห้องเรียนที่บันทึกไม่เรียบร้อย</td>
								<td align="right">
									<?=$_dat['unchecked']?>
								</td>
								<td align="center">ห้อง</td>
							</tr>
							<tr>
								<td align="right">รวม</td>
								<td align="right">
									<?=number_format($_dat['checked']+$_dat['unchecked'],0,'.',',')?>
								</td>
								<td align="center">ห้อง</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="key" colspan="2">รายการแสดงงานบันทึกข้อมูลในแต่ละวัน</td>
				</tr>
				<tr>
					<?php
						$_sql_task_details = "
						select
							weekday(task_date)+1 as 'weekday',
							task_date,
								SUM(if(task_status=1,1,0)) as 'checked',
								SUM(if(task_status=0,1,0)) as 'unchecked'
						from student_800_task
						where
							acadyear = '" . $acadyear ."'
							and acadsemester = '". $acadsemester . "'
						group by 
							task_date
						order by 
							task_date desc	
						";
						$_resDetails = mysqli_query($_connection,$_sql_task_details);
						$_i = 1;
					?>
					<td valign="top" colspan="2" align="center">
						<table>
							<tr>
								<td align="center" class="key" rowspan="2">-</td>
								<td align="center" class="key" rowspan="2"width="50px">วัน</td>
								<td align="center" class="key" rowspan="2" width="140px">ที่</td>
								<td align="center" class="key" colspan="2">ผลการบันทึก</td>
								<td align="center" class="key" rowspan="2" width="75px">-</td>
							</tr>
							<tr>
								<td align="center" class="key">บันทึกแล้วเสร็จ</td>
								<td align="center" class="key">ยังไม่บันทึก</td>
							</tr>
							<? while($_dd = mysqli_fetch_assoc($_resDetails)){ ?>
							<tr>
								<td><?=$_i++?></td>
								<td align="right"><?=displayDayOfWeek($_dd['weekday'])?></td>
								<td align="right"><?=displayDate($_dd['task_date'])?></td>
								<td align="center"><?=($_dd['checked']  ==0?"-":$_dd['checked'])?></td>
								<td align="center"><?=($_dd['unchecked']==0?"-":$_dd['unchecked'])?></td>
								<td align="center">
								<a href="index.php?option=module_800/dateTaskDetail&date=<?=$_dd['task_date']?>" >
									รายละเอียด
								</a>
								</td>
							</tr>
							<? } // end while ?>
						</table>
					</td>
				</tr>
		</table>
		<? } else { ?>
			<div align="center"><br/><br/><font color='red'>ยังไม่มีการบันทึกข้อมูล</font></div>
		<? } ?>
	</div>
</div>
	
	<?php
		/* 
			day of week in PHP
			0 = Sunday
			1 = Monday
			2 = Tuesday
			3 = Wednesday
			4 = Thursday
			5 = Friday
			6 = Saturday

		*/

	?>