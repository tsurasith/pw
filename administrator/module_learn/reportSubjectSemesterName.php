
<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.7 สรุปรายงานการเข้าเรียนตามรายวิชา</strong></font></span></td>
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
			if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc001" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") {
				$_teacher_id = isset($_POST['teacher_id'])?$_POST['teacher_id']:"";
			} else{
				$_teacher_id = $_SESSION['user_account_id'];
			}
			
			

		?>
        ปีการศึกษา
        <?php  
					echo "<a href=\"index.php?option=module_learn/reportSubjectSemesterName&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportSubjectSemesterName&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportSubjectSemesterName&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportSubjectSemesterName&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				
		
           <font color="#000000" size="2">
           		<form name="sSelect" method="post" action="index.php?option=module_learn/reportSubjectSemesterName">
				   <? if($_SESSION['username'] == "admin" || $_SESSION['username'] == "tc001" || $_SESSION['username'] == "tc101" || $_SESSION['username'] == "tc102" || $_SESSION['username'] == "tc103") { ?>
				   <font  size="2" color="#000000">เลือกครูผู้สอน
					<?php 
							$sql_teacher = " 
									SELECT
										u.user_account_prefix,
										u.user_account_firstname,
										u.user_account_lastname,
										u.user_account_id,
										(
											select count(distinct SubjectCode,room_id) 
											from teaching_schedule t
											where 
												t.teacher_id   = u.user_account_id and
												t.acadyear     = '" . $acadyear . "' and 
												t.acadsemester = '" . $acadsemester ."' and 
												t.room_id      != '000' and 
												t.club_code    = '0000'
										) as a,
										(
											select count(distinct club_code) 
											from teaching_schedule t
											where 
												t.teacher_id   = u.user_account_id and
												t.acadyear     = '" . $acadyear . "' and 
												t.acadsemester = '" . $acadsemester ."' and 
												t.room_id      != '000' and 
												t.club_code    != '0000'
										) as b
									FROM
										users_account u inner join teachers tt 
										on (u.user_account_id = tt.teacher_id)
									ORDER BY
										CONVERT(
											u.user_account_firstname USING tis620
										)			
								";
							//echo $sql_teacher . "<br/>" ;
							$resTeacher = mysqli_query($_connection,$sql_teacher);	
							$_submit_teacher_name = "";		
					?>
				
					<select name="teacher_id" class="inputboxUpdate" onChange="document.sSelect.submit();">
						<option value=""></option>
						<?php
							$_select = "";
							while($dat = mysqli_fetch_assoc($resTeacher))
							{
								// not include club 
								$_sum_subjects = $dat['a'];

								$_select = (isset($_POST['teacher_id'])&&$_POST['teacher_id'] == $dat['user_account_id']?"selected":"");
								echo "<option value=\"" . $dat['user_account_id'] . "\" $_select>";
								echo $dat['user_account_firstname']. ' ' . $dat['user_account_lastname'] . ' (' . $_sum_subjects . ' วิชา)';
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
						select 
							t.teacher_id,
							t.room_id,
							t.SubjectCode,
							s.SubjectName,
							(
								select count(*)
								from
									student_learn_task l
								where
									l.teacher_id   = t.teacher_id and 
									l.SubjectCode  = t.SubjectCode and 
									l.task_roomid  = t.room_id and 
									l.acadyear     = '" . $acadyear . "' and 
									l.acadsemester = '" . $acadsemester . "'
							) as record
						from
							teaching_schedule t left join curriculum_subjects s 
							on (t.SubjectCode = s.SubjectCode)
						where
							t.teacher_id   = '" . $_teacher_id ."' and 
							t.room_id     != '000' and 
							t.club_code    = '0000' and 
							t.acadsemester = '" . $acadsemester . "' and 
							t.acadyear     = '" . $acadyear . "'
						group by
							t.teacher_id,
							t.room_id,
							t.SubjectCode
						order by t.room_id,t.SubjectCode								
							" ;
					$_select_subject = "";

					$result = mysqli_query($_connection,$sql_subjects);

					while($data = mysqli_fetch_assoc($result)) {
						$_select_subject = (isset($_POST['subject_id'])&&$_POST['subject_id']==$data['room_id'].'-'.$data['SubjectCode']?"selected":"");
						 ?>
						<option value="<?=$data['room_id'].'-'.$data['SubjectCode']?>" <?=$_select_subject?>>
							<?=getFullRoomFormat($data['room_id']) . ' ' . $data['SubjectCode'] . ' ' . $data['SubjectName']?> (<?=$data['record']?>)
						</option>
					<? }  ?>
						</select>
						<input type="hidden" name="acadyearX" value="<?=$acadyear?>">
						<input type="hidden" name="acadsemesterX" value="<?=$acadsemester?>">
						<input type="hidden" name="search" value="search"> <br/>
						<input type="submit" name="search" value="เรียกดู" class="button">
                </form>
				<? //echo $sql_subjects . "<br/>"; ?>
           </font>
	  </td>
    </tr>
  </table>
	  
    <br/>
<? if(isset($_POST['search']) && $_POST['subject_id'] != ""){ ?>
	<div id="div-overflow">
	<?
		// $_sub[0] = room_id
		// $_sub[1] = subject_code
		$_sub = explode("-",$_POST['subject_id']);

		$_date_array    = array();
		$_column_array  = array();

		$_sql_column = "
			select distinct check_date,period from student_learn
			where
				acadyear = '" . $acadyear . "' AND
				acadsemester = '" . $acadsemester . "' AND
				SubjectCode = '" . $_sub[1] . "' AND
				class_id = '" . $_sub[0] . "'  and
				teacher_id = '" . $_POST['teacher_id'] . "' 
			order by
				check_date,period
		";

		$_res_column = mysqli_query($_connection,$_sql_column);
		$_row_column = mysqli_num_rows($_res_column);

		//echo $_sql_column . "<br/>";

		$_sql_builder = "";
		$_t = 1;
		if($_row_column>0){
			while($_dat = mysqli_fetch_assoc($_res_column)){
				if($_t<10){
					$_column_array[$_t-1] = "t0" . $_t;
					$_date_array[$_t-1]   = $_dat['check_date'] . '/' . $_dat['period'];
				}
				else{
					$_column_array[$_t-1] = "t" . $_t;
					$_date_array[$_t-1]   = $_dat['check_date'] . '/' . $_dat['period'];
				}

				$_sql_builder .= " sum(if(check_date='" . $_dat['check_date'] . "' and period = '" . $_dat['period'] . "', timecheck_id,NULL)) as " . $_column_array[$_t-1] .", ";
				$_t++;
			}
		}

		$_totalRows = 0;
		$_sql_class_record = "
			select 
				prefix,firstname,lastname,studstatus,
				c.* 
			from
				students s
				inner join (
								select student_id, " . $_sql_builder . " 
								sum(1) as total
								from
									student_learn t
								where
									1=1
									and t.acadyear     = '" . $acadyear . "' 
									and t.acadsemester = '" . $acadsemester . "' 
									and t.class_id     = '" . $_sub[0] . "' 
									and t.SubjectCode  = '" . $_sub[1] . "'
								group by student_id
						   ) as c
				on (s.id = c.student_id and s.xEDBE = '" . $acadyear . "')
			where
				s.xedbe = '" . $acadyear . "' 
			order by 
				sex,
				convert(firstname using tis620),
				convert(lastname  using tis620)
				" ;
		$_resClass = mysqli_query($_connection,$_sql_class_record);
		$_totalRows = mysqli_num_rows($_resClass);
		$_no = 1;
		$_table_width = 10+25+50+200+80+100+100+(20*count($_date_array));
	?>
	<? if($_totalRows >0){  ?>
	<table class="admintable" align="center" width="<?=$_table_width?>px" style="line-height:12px !important;">
		<tr>
			<th colspan="<?=count($_date_array)+6?>" align="center">
				<img src="../images/school_logo.png" width="120px"> <br/>
				บันทึกการเข้าเรียน รหัสวิชา <?=$_sub[1]?> ห้อง <?=getFullRoomFormat($_sub[0])?><br/>
				ของคุณครู <?=getUserAccountName($_connection,$_POST['teacher_id'])?> <br/>
			</th>
		</tr>
		<tr height="35px"> 
			<td class="key" width="25px"  align="center" rowspan="2">ที่</td>
			<td class="key" width="50px"  align="center" rowspan="2">ID</td>
			<td class="key" width="200px" align="center" rowspan="2">ชื่อ - นามสกุล</td>
			<td class="key" width="80px"  align="center" rowspan="2" >สถานภาพ<br/>ปัจจุบัน</td>
			<td class="key" align="center" colspan="<?=count($_date_array)?>">วันที่/คาบ</td>
			<td class="key" withd="100px" align="center" rowspan="2">รวม<br/>(<?=count($_date_array)?>)</td>
			<td class="key" withd="100px" align="center" rowspan="2">%</td>
		</tr>
		<tr height="110px">
			<? if(count($_date_array)>40){ ?>
				<? for($_i=0;$_i<count($_date_array);$_i++){ ?>
					<td align="center" style="padding:0px">
						<div style="writing-mode: vertical-rl; transform:rotate(-180deg);font-size:x-small;">
							<?=$_date_array[$_i]?>
						</div>
					</td>
				<? } ?>
			<? }else{ ?>
				<? for($_i=0;$_i<count($_date_array);$_i++){ ?>
					<td class="key" width="15px" align="center">
						<div style="writing-mode: vertical-rl; transform:rotate(-180deg);">
							<?=$_date_array[$_i]?>
						</div>
					</td>
				<? } ?>
			<? } ?>
		</tr>
			<? while($_dat = mysqli_fetch_assoc($_resClass)){ ?>
				<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF" >
					<td valign="top" align="center"><?=$_no++?></td>
					<td valign="top" align="center"><?=$_dat['student_id']?></td>
					<td valign="top"><?=$_dat['prefix'].$_dat['firstname'].' '.$_dat['lastname']?></td>
					<td valign="top" align="center"><?=displayStudentStatusColor($_dat['studstatus'])?></td>
					<?
					  $_count_disappear = 0; 
					  for($_i=0;$_i<count($_date_array);$_i++){ ?>
						<td align="center"><?=$_dat[$_column_array[$_i]]=="0"?"/":displayTimecheckShortColor($_dat[$_column_array[$_i]])?></td>
						<?php
							if($_dat[$_column_array[$_i]] == 3 || $_dat[$_column_array[$_i]] == 4 || $_dat[$_column_array[$_i]] == 5){
								// ลา, ขาด, หนีเรียน ถือว่าไม่เข้าเรียน
								$_count_disappear++;
							}
						?>
					<? } ?>
					<td align="center"><?=($_dat['total']-$_count_disappear)?></td>
					<td align="right">
						<?php
							$_percent = ((($_dat['total']-$_count_disappear)*100)/$_dat['total']);
							echo number_format($_percent,0) . "% ";
						?>
					</td>
				</tr>
			<? } //end while 
			   mysqli_free_result($_resClass);
			   mysqli_free_result($_res_column);
			?>
		 </table>
		<? } else { ?> 
			<div align="center"><br/><br/><font color='red'>ไม่พบข้อมูลที่ค้นหา</font></div>
		<? } ?>
	</div>
	<? } ?>
</div>

