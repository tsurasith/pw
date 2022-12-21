
<?php
	if(isset($_POST['delete']))
	{
		$delSql = "delete from student_800_task where task_date = '" . $_POST['delete'] . "'" ;
		$resDel = mysqli_query($_connection,$delSql);
		if($resDel)
		{
			echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?option=module_800/dateTaskCreated\">";
		}
	}
?>
	  <div id="content">
	  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_800/index"><img src="../images/modules/800_clock2.png" alt="" width="48" height="48" border="0"/></a></td>
      <td ><strong><font color="#990000" size="4">8.00 O' Clock</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.2 ตรวจสอบวันที่สร้างงานบันทึกข้อมูลแล้ว&gt;&gt;รายละเอียดการบันทึก</strong></font></span></td>
      <td >&nbsp;</td>
    </tr>
  </table>
	<form method="post" action="">
		<table width="100%" align="center" class="admintable">
			<tr>
				
        <td colspan="2" class="key" align="center">รายละเอียดการบันทึกข้อมูลวันที่  <?=displayFullDate($_REQUEST['date']) . ' - [' . $_REQUEST['date'] . ']'?></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
                	<div>
					<table border="0" cellspacing="1" cellpadding="1" bgcolor="lightpink">
						<tr bgcolor="#CCCCCC">
							<td align="center" width="50px">ห้อง</td>
							<td align="center" width="150px">สถานะ</td>
							<td align="center">ครูที่ปรึกษาคนที่ 1</td>
							<td align="center">ครูที่ปรึกษาคนที่ 2</td>
						</tr>
						<?php
							$sign = 0;
							$sql = "
							select 
								t.* ,
								r.teacher_id,
								r.teacher_id2,
								r.student_id,
								t1.FIRSTNAME as t1f,
								t1.LASTNAME as t1l,
								t2.FIRSTNAME as t2f,
								t2.LASTNAME as t2l
							from
							student_800_task t 
							inner join rooms r on (t.task_roomid = r.room_id and t.acadyear = r.acadyear and t.acadsemester = r.acadsemester)
							left join teachers t1 on (r.teacher_id  = t1.teaccode)
							left join teachers t2 on (r.teacher_id2 = t2.teaccode)
							where
								t.task_date = '" . $_REQUEST['date'] ."'
							order by t.task_roomid
						" ;
							$res = mysqli_query($_connection,$sql);
							while($dat = mysqli_fetch_assoc($res))
							{
								echo "<tr bgcolor=\"white\">";
								echo "<td align=\"center\">" . getFullRoomFormat($dat['task_roomid']) . "</td>";
								
								if($dat['task_status'] == "1")
								{
									echo "<td align=\"center\">บันทึกแล้ว</td>";
									$sign++;
								}
								else
								{
									echo "<td align=\"center\"><font color=\"red\">ยังไม่บันทึก</font></td>";
								}
								echo "<td >" . $dat['t1f'] . ' ' . $dat['t1l'] . "</td>";
								echo "<td >" . $dat['t2f'] . ' ' . $dat['t2l'] . "</td>";
								echo "</tr>";
							} mysqli_free_result($res);
						?>
					</table>
                    </div>
				</td>
			</tr>
			<tr>
				<td class="key" colspan="2" align="center">
				<?php
					if($sign == 0)
					{
						echo "<input type=\"hidden\" value=\"" . $_REQUEST['date'] . "\" name=\"delete\" />";
						echo "<input type=\"submit\" value=\"ลบ\" class=\"button\" /> งานบันทึกข้อมูลวันนี้";
					}
					else
					{
						echo "<input type=\"button\" value=\"ย้อนกลับ\" onClick='history.go(-1)'  >";
					}
				?>
				</td>
			</tr>
		</table>
 	</form>
</div>

		