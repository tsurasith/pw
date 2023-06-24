<link rel="stylesheet" type="text/css" href="module_moral/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_moral/js/calendar.js"></script>
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
<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>2.2 ติดตามการบันทึกการเข้าเรียน</strong></font></span></td>
			<td >
			
				ปีการศึกษา
				<?php  
					echo "<a href=\"index.php?option=module_learn/followUpLearningTask&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/followUpLearningTask&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
				ภาคเรียนที่   
				<?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_learn/followUpLearningTask&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_learn/followUpLearningTask&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
			</td>
			</tr>
		</table>
		<br/>

		<form action="" method="post" autocomplete="off">
			<table align="center" class="admintable" >
			<tr height="30px"> 
				<td  class="key" colspan="2">โปรดใส่รายละเอียดที่ต้องการสืบค้น</td>
			</tr>
			<tr>
				<td align="right">เลือกห้องเรียน :</td>
				<td>
					<?php
						$_sql = "select room_id from rooms where acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester ."' order by 1";
						$_resRoom = mysqli_query($_connection,$_sql);
						//echo $_sql;
					?> 
					<select name="room_id" class="inputboxUpdate">
						<option value="" <?=(isset($_POST['room_id']) && $_POST['room_id']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resRoom)) { ?> 
							<option value="<?=$_dat['room_id']?>" <?=(isset($_POST['room_id']) && $_POST['room_id']==$_dat['room_id']?"selected":"")?> >
								<?=getFullRoomFormat($_dat['room_id'])?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">เลือกครูผู้สอน :</td>
				<td>
					<?php
						$_sql = "
							select distinct t.teacher_id,t.firstname,t.lastname
							from 
								teachers t inner join teaching_schedule s
								on (t.teacher_id = s.teacher_id and acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester . "')
							order by convert(t.firstname using tis620) ";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="teacher_id" class="inputboxUpdate">
					<option value="" <?=(isset($_POST['teacher_id']) && $_POST['teacher_id']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['teacher_id']?>" <?=(isset($_POST['teacher_id']) && $_POST['teacher_id']==$_dat['teacher_id']?"selected":"")?> >
								<?=$_dat['firstname'].' '.$_dat['lastname']?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<!--
			<tr>
				<td></td>
				<td>
					<input type="radio" name="order_by" value="checked"   <?=isset($_POST['order_by'])&&$_POST['order_by']=="date"?"checked":""?>> เรียงตามการบันทึก  &nbsp; 
					<input type="radio" name="order_by" value="uncheck"   <?=isset($_POST['order_by'])&&$_POST['order_by']=="name"?"checked":""?>> เรียงตามชื่อ-นามสกุล
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="show_overall" value="show_overall"   <?=isset($_POST['show_overall'])&&$_POST['show_overall']=="show_overall"?"checked":""?>> ไม่แสดงรหัสวิชาและห้องเรียน  &nbsp; 
				</td>
			</tr>
						-->
			<tr>
				<td>&nbsp;</td><td><input type="submit" name="search" value="สืบค้น"/> <input type="reset" value="ล้างข้อมูลสืบค้น" /></td>
			</tr>
			</table>
			</form>

		<? if(isset($_POST['search'])) {?>
		<?		

				$_sql = "
					select 
						c.prefix,c.firstname,c.lastname, t.teacher_id,t.SubjectCode,s.task_roomid, 
						sum(if(s.task_status = 0,1,0)) as 'uncheck',
						sum(if(s.task_status = 1,1,0)) as 'checked',
						count(*) as 'total'
					from student_learn_task s left join teaching_schedule t 
					on (
							s.period = t.period and 
							s.acadyear = t.acadyear and 
							s.acadsemester = t.acadsemester and 
							s.weekday = t.weekday and
							s.task_roomid = t.room_id 
						)
					inner join teachers c 
					on (t.teacher_id = c.teacher_id)
					WHERE
						s.acadyear     = '" . $acadyear ."' AND
						s.acadsemester = '" . $acadsemester ."'
					";

				$_sql .= trim($_POST['teacher_id'])==""?"":" and c.teacher_id = '" . $_POST['teacher_id'] . "'";
				$_sql .= trim($_POST['room_id'])==""?"":" and room_id = '" . $_POST['room_id'] . "' ";
				
				$_sql .= " 					
					GROUP BY 
						t.teacher_id,t.SubjectCode,s.task_roomid
					ORDER BY 
						convert(c.firstname using tis620),
						t.SubjectCode,
						s.task_roomid
					";

				//echo $_sql . "<br/>";
				

				$_total_rows = 0;
				$_result = mysqli_query($_connection,$_sql);
				$_total_rows = mysqli_num_rows($_result);
				
				if(mysqli_num_rows($_result) > 0) { ?>
					<table class="admintable" align="center">
						<tr height="35px">
							<td colspan="9" align="right">
								<font color='green'><b>
									จำนวนข้อมูลที่ตรงกับเงื่อนไขที่สืบค้นจำนวน <?=$_total_rows?> รายการ</b>
								</font>
							</td>
						</tr>
						<tr align="center" height="30px">
							<td class="key" width="35px">ที่</td>
							<td class="key" width="180px">ชื่อ - สกุล</td>
							<td class="key" width="100px">รหัสวิชา</td>
							<td class="key" width="60px">ห้องเรียน</td>
							<td class="key" width="80px">บันทึกแล้ว</td>
							<td class="key" width="80px">ยังไม่บันทึก</td>
							<td class="key" width="100px">รวม</td>
							<td class="key">-</td>
						</tr>
					
						<? $_i = 1; ?>
						<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_i++?></td>
							<td ><?=$_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname']?></td>
							<td align="center"><?=$_dat['SubjectCode']?></td>
							<td align="center"><?=getFullRoomFormat($_dat['task_roomid'])?></td>
							<td align="center"><?=$_dat['checked']?></td>
							<td align="center"><?=$_dat['uncheck']?></td>
							<td align="center"><?=$_dat['total']?></td>
						</tr>
						<? } //end while?>
					</table>
			<?	} else {?>
					<center><font color="red"><br/><br/>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา โปรดลองระบุเงื่อนไขในการค้นหาใหม่อีกครั้ง</font></center><br/><br/>
			<? } //end if-else ค้นหาข้อมูลจากฐานข้อมูล ?>
		<? }//end check value ในการค้นหา ?>
	</div>
</div>
