
<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.5 ตรวจสอบ/ติดตามบันทึกการสอน</strong></font></span></td>
      <td align="right">
	  <? if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

			//==============$_POST value===========//
			if(isset($_POST['acadyearX'])) { $acadyear = $_POST['acadyearX']; }
			if(isset($_POST['acadsemesterX'])) { $acadsemester = $_POST['acadsemesterX']; }


			$_date = "";
			$_date = isset($_POST['date'])?$_POST['date']:"";

			if($_date == ""){
				$_date = isset($_REQUEST['date'])?$_REQUEST['date']:"";
			}

			$_date = "";
			$_date = isset($_POST['date'])?$_POST['date']:"";
	
			$_teacher_id = "";
			if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc100" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") {
				$_teacher_id = isset($_POST['teacher_id'])?$_POST['teacher_id']:"";
			} else{
				$_teacher_id = $_SESSION['user_account_id'];
			}
			
			

		?>
        ปีการศึกษา
        <?php  
					echo "<a href=\"index.php?option=module_learn/teachingRecord&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/teachingRecord&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/teachingRecord&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/teachingRecord&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				
		
           <font color="#000000" size="2">
           		<form name="sSelect" method="post" action="index.php?option=module_learn/teachingRecord">
				   <? if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc100" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") { ?>
				   <font  size="2" color="#000000">เลือกครูผู้สอน
					<?php 
							$sql_teacher = " 
									select
										u.user_account_prefix,
										u.user_account_firstname,
										u.user_account_lastname,
										u.user_account_id,
										count(*) as c
									from
										users_account u
										inner join (select teacher_id,room_id,SubjectCode,count(*)
													from
														teaching_schedule t
													where
														t.room_id != '000' and 
														acadyear = '" . $acadyear . "' and
														acadsemester = '" . $acadsemester . "'
													group by
														subjectcode,room_id) as t
										on t.teacher_id = u.user_account_id
									group by
										u.user_account_id,
										u.user_account_prefix,
										u.user_account_firstname,
										u.user_account_lastname
									order by convert(u.user_account_firstname using tis620)
								";
							//echo $sql_Room ;
							$resTeacher = mysqli_query($_connection,$sql_teacher);	
							$_submit_teacher_name = "";		
					?>
				
					<select name="teacher_id" class="inputboxUpdate" onChange="document.sSelect.submit();">
						<option value=""></option>
						<?php
							$_select = "";
							while($dat = mysqli_fetch_assoc($resTeacher))
							{
								$_select = (isset($_POST['teacher_id'])&&$_POST['teacher_id'] == $dat['user_account_id']?"selected":"");
								echo "<option value=\"" . $dat['user_account_id'] . "\" $_select>";
								echo $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $dat['c'] . ' วิชา)';
								echo "</option>";
							}
							
						?>
					</select><br/>
					<? } else { ?> 
						<input type="hidden" name="teacher_id" value=<?=$_SESSION['user_account_id']?> />
					<? } ?>
					เลือกห้องเรียนและวิชา 
						<select name="subject_id" class="inputboxUpdate" >
						<option value=""></option>
					<?php
					$sql_subjects = "
							select t.teacher_id,t.room_id,t.SubjectCode,count(r.teacher_id) as 'record'
							from
								teaching_schedule t left join 
								teaching_record r 
									on (
										t.teacher_id = r.teacher_id and 
										t.SubjectCode = r.SubjectCode and
										t.acadyear = r.acadyear and
										t.acadsemester = r.acadsemester and
										t.room_id = r.room_id and 
										t.weekday = r.weekday
										)
							where
								t.room_id != '000' 
								and t.teacher_id = '" . $_teacher_id . "' and
								t.acadyear = '" . $acadyear . "' and
								t.acadsemester = '" . $acadsemester . "' 
							group by
								t.teacher_id,t.SubjectCode,t.room_id 	
							order by t.room_id,t.SubjectCode								
							" ;
					$_select_subject = "";

					$result = mysqli_query($_connection,$sql_subjects);

					while($data = mysqli_fetch_assoc($result)) {
						$_select_subject = (isset($_POST['subject_id'])&&$_POST['subject_id']==$data['room_id'].'-'.$data['SubjectCode']?"selected":"");
						 ?>
						<option value="<?=$data['room_id'].'-'.$data['SubjectCode']?>" <?=$_select_subject?>>
							<?=getFullRoomFormat($data['room_id']) . ' ' . $data['SubjectCode']?> (<?=$data['record']?>)
						</option>
					<? }  ?>
						</select>
						<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
						<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
						<input type="hidden" name="search" value="search"> <br/>
						<input type="submit" name="search" value="เรียกดู" class="button">
                </form>
           </font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if(isset($_POST['search']) && $_POST['subject_id'] != ""){ ?>
	<div align="center">
	<?
		$_sub = explode("-",$_POST['subject_id']);

		$_totalRows = 0;
		$_sql_teaching_record = "
				select * 
				from
					teaching_record t
				where
					1=1
					and t.room_id = '" . $_sub[0] . "' 
					and t.SubjectCode = '" . $_sub[1] . "'
					and t.teacher_id = '" . $_teacher_id . "'
				order by
					teaching_date,weekday,period		
				" ;
		$_resTeaching = mysqli_query($_connection,$_sql_teaching_record);
		$_totalRows = mysqli_num_rows($_resTeaching);
		$_i = 1;
	?>
	<? if($_totalRows >0){  ?>
	<table class="admintable" align="center">
		<tr>
			<th colspan="10" align="center">
				<img src="../images/school_logo.png" width="120px"> <br/>
				บันทึกหลังการสอน รหัสวิชา <?=$_sub[1]?> ห้อง <?=getFullRoomFormat($_sub[0])?><br/>
				ของคุณครู <?=getUserAccountName($_connection,$_POST['teacher_id'])?> <br/>
			</th>
		</tr>
		<tr height="35px"> 
			<td class="key" width="30px" align="center">ที่</td>
			<td class="key" align="center" colspan="2">วัน/เดือน/ปี</td>
			<td class="key" width="30px" align="center">คาบ</td>
			<td class="key" width="70px" align="center">ใบงาน/<br/>การบ้าน</td>
			<td class="key" width="440px" align="center">ข้อมูลบันทึกการสอน</td>
			<td class="key" withd="100px" align="center">การกับติดตาม</td>
		</tr>
			<? while($_dat = mysqli_fetch_assoc($_resTeaching)){ ?>
				<tr>
					<td valign="top" align="center"><?=$_i++?></td>
					<td valign="top" width="60px"><?=displayDayofWeek($_dat['weekday'])?></td>
					<td valign="top" width="100px"><?=displayDateShortMonth($_dat['teaching_date'])?></td>
					<td valign="top" align="center"><?=$_dat['period']?></td>
					<td valign="top" align="center"><?=($_dat['has_assignment']==0?"ไม่มี":"มี")?></td>
					<td valign="top"><?=$_dat['teaching_details']?></td>
					<td valign="top"><?=$_dat['approved_datetime']?></td>
				</tr>
			<? } //end while ?>
		 </table>
		<? } else { ?> 
			<div align="center"><br/><br/><font color='red'>ไม่พบข้อมูลที่ค้นหา</font></div>
		<? } ?>
	</div>
	<? } ?>
</div>

