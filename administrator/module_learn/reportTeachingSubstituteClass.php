

<link rel="stylesheet" type="text/css" href="module_learn/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_learn/js/calendar.js"></script>

<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.6 สรุปรายงานการสอนแทน (ครูผู้สอนแทนเป็นหลัก)</strong></font></span></td>
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
			$_teacher_id = isset($_POST['teacher_id'])?$_POST['teacher_id']:"";

		?>
        ปีการศึกษา
        <?php  
					echo "<a href=\"index.php?option=module_learn/reportTeachingSubstituteClass&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportTeachingSubstituteClass&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportTeachingSubstituteClass&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportTeachingSubstituteClass&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				<br/>
			
		<font  size="2" color="#000000">
			<form action="index.php?option=module_learn/reportTeachingSubstituteClass" method="post" autocomplete="off">
				เลือกเดือน 
				<select name="month" class="inputboxUpdate">
			 	<option value="">ทั้งหมด</option>
				<?php
					$_sqlMonth = "select distinct month(teaching_date)as m,year(teaching_date)+543 as y
									from teaching_substitute where acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester . "'
									order by year(teaching_date),month(teaching_date)";
					$_resMonth = mysqli_query($_connection,$_sqlMonth);
					while($_datMonth = mysqli_fetch_assoc($_resMonth))
					{
						$_select = (isset($_POST['month'])&&$_POST['month'] == $_datMonth['m']?"selected":"");
						echo "<option value=\"" . $_datMonth['m'] . "\" $_select>" . displayMonth($_datMonth['m']) . ' ' . $_datMonth['y'] . "</option>";
					} mysqli_free_result($_resMonth);
				?>
			 </select>
				<input type="submit" name="search" value="เรียกดู" class="button" /><br/>
				<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
				<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
			</form>
		</font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if((isset($_POST['search']))) { ?>

		<?php
			$_sql_display = "";

			if($_POST['month']!=""){
				$_sql_display = "
					select 
						u.user_account_firstname,
						u.user_account_lastname,
						concat(u.user_account_firstname,' ',u.user_account_lastname) as 'teacher_name',
						t.teaching_date,
						(
							select count(*) 
							from teaching_substitute 
							where 
								month(teaching_date) = '" . $_POST['month'] ."' and 
								teacher_id = t.teacher_id and
								acadyear = '" . $acadyear . "' and 
								acadsemester = '" . $acadsemester . "'
						) as 'all_total',
						(
							select count(*) 
							from teaching_substitute 
							where 
								teaching_date = t.teaching_date and 
								teacher_id = t.teacher_id and 
								acadyear = '" . $acadyear . "' and 
								acadsemester = '" . $acadsemester . "'
						) as 'total_day',
						concat(o.user_account_firstname,' ',o.user_account_lastname) as 'substitue_teacher',
						count(t.teacher_id) as 'count_period',
						t.SubjectCode,
						t.room_id	
					from
						teaching_substitute t left join users_account u on (t.teacher_id = u.user_account_id and t.teacher_id is not null)
						left join users_account o on (t.owner_teacher_id = o.user_account_id and t.owner_teacher_id is not null)
					where
						month(t.teaching_date) = '" . $_POST['month'] ."' and
						t.acadyear = '" . $acadyear . "' and 
						t.acadsemester = '" . $acadsemester . "'
					group by
						teacher_name,
						t.teaching_date,
						substitue_teacher,
						room_id
					order by
						convert(teacher_name using tis620),
						t.teaching_date
					";
			}else {
				// all semester
				$_sql_display = "
					select 
						u.user_account_firstname,
						u.user_account_lastname,
						concat(u.user_account_firstname,' ',u.user_account_lastname) as 'teacher_name',
						t.teaching_date,
						(
							select count(*) 
							from teaching_substitute 
							where 
								teacher_id = t.teacher_id and
								acadyear = '" . $acadyear . "' and 
								acadsemester = '" . $acadsemester . "'
						) as 'all_total',
						(
							select count(*) 
							from teaching_substitute 
							where 
								teaching_date = t.teaching_date and 
								teacher_id = t.teacher_id and 
								acadyear = '" . $acadyear . "' and 
								acadsemester = '" . $acadsemester . "'
						) as 'total_day',
						concat(o.user_account_firstname,' ',o.user_account_lastname) as 'substitue_teacher',
						count(t.teacher_id) as 'count_period',
						t.SubjectCode,
						t.room_id	
					from
						teaching_substitute t left join users_account u on (t.teacher_id = u.user_account_id and t.teacher_id is not null)
						left join users_account o on (t.owner_teacher_id = o.user_account_id and t.owner_teacher_id is not null)
					where
						t.acadyear = '" . $acadyear . "' and 
						t.acadsemester = '" . $acadsemester . "'
					group by
						teacher_name,
						t.teaching_date,
						substitue_teacher,
						room_id
					order by
						convert(teacher_name using tis620),
						t.teaching_date
					";
			}
			

			$_res_display = mysqli_query($_connection,$_sql_display);
			$_count_display = mysqli_num_rows($_res_display);

			$_no = 1;
			$_previous_owner 	= "";
			$_previous_total    = 0;
			$_previous_date 	= "";
			$_previous_total_day= 0;

		?>
		<?	if($_count_display>0){ ?>
				<div align="center">
					<br/>
					<table class="admintable">
						<tr>
							<th align="center" colspan="8">
							<img src="../images/school_logo.png" width="120px"><br/>
							สรุปข้อมูลการถูกสอนแทน<br/>
							<?php
								if(trim($_POST['month'])!=""){
									echo "เดือน" . displayMonth($_POST['month']);
								}
							?>
							ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?>
							<br/><br/>
							</th>
						</tr>
						<tr>
							<td class="key" align="center" width="25px">ที่</td>
							<td class="key" align="center" width="150px">ครูเจ้าของวิชา</td>
							<td class="key" align="center" width="65px">จำนวนคาบ<br/>(ทั้งหมด)</td>
							<td class="key" align="center" width="105px">วันที่ถูกสอนแทน</td>
							<td class="key" align="center" width="65px">จำนวนคาบ<br/>(แต่ละวัน)</td>
							<td class="key" align="center" width="150px">ครูผู้สอนแทน</td>
							<td class="key" align="center" width="45px">จำนวน<br/>คาบ</td>
							<td class="key" align="center" width="65px">รหัสวิชา</td>
							<td class="key" align="center" width="65px">ห้อง</td>
							<td class="key" align="center" width="50px">-</td>
						</tr>
						<? while($_dat = mysqli_fetch_assoc($_res_display)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF" >
							<td align="center" valign="top">
							<?
								if($_previous_owner!=$_dat['teacher_name']){
										echo $_no++;
									}else{ echo ""; }
								?>
							</td>
							<td valign="top">
								<?
									if($_previous_owner!=$_dat['teacher_name']){
										echo "ครู" . $_dat['teacher_name'];
									}else{ echo ""; }
								?>
							</td>
							<td align="center" valign="top">
								<?
									if($_previous_owner!=$_dat['teacher_name']){
										echo $_dat['all_total'];
									}else{ echo ""; }
								?>
							</td>
							<td align="center" valign="top">
								<?
									if($_previous_date != $_dat['teaching_date'] || $_previous_owner!=$_dat['teacher_name']){
										echo displayDateShortMonth($_dat['teaching_date']);
									}else{ echo ""; }
								?>
							</td>
							<td align="center" valign="top">
								<?php
									if(($_previous_date != $_dat['teaching_date'] || $_previous_owner!=$_dat['teacher_name']) && $_previous_total_day != ['total_day']){
										echo $_dat['total_day'];
									}else{ echo ""; }
								?>
							</td>
							<td><?="ครู".$_dat['substitue_teacher']?></td>
							<td align="center" valign="top"><?=$_dat['count_period']?></td>
							<td align="center" valign="top"><?=$_dat['SubjectCode']?></td>
							<td align="center" valign="top"><?=getFullRoomFormat($_dat['room_id'])?></td>
						</tr>
						<?php
							$_previous_total     = $_dat['all_total'];
							$_previous_owner     = $_dat['teacher_name'];
							$_previous_date      = $_dat['teaching_date'];
							$_previous_total_day = $_dat['total_day'];
						?>
						<? } mysqli_free_result($_res_display);?>
					</table>
				</div>
		<?	} else { ?>
			<div align="center">
					<br/><br/>
					<font color="red">ยังไม่มีการบันทึกข้อมูลในส่วนนนี้</font>
					<br/>
			</div>
		<? } ?>

	
	<? } //end if-check search ?>
</div>

