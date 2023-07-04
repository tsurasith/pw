
<div id="content">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.1 รายงานการเข้าเรียนตามรายวิชา</strong></font></span></td>
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
					echo "<a href=\"index.php?option=module_learn/reportSubjectSemester&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportSubjectSemester&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportSubjectSemester&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_learn/reportSubjectSemester&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
				
		
           <font color="#000000" size="2">
           		<form name="sSelect" method="post" action="index.php?option=module_learn/reportSubjectSemester">
					เลือกวิชาเรียน 
						<select name="subject_id" class="inputboxUpdate" >
						<option value=""></option>
					<?php
					$sql_subjects = "
						SELECT
							t.SubjectCode,
							COUNT(*) AS 'count'
						FROM
							student_learn t
						WHERE
							t.acadyear = '" . $acadyear . "' and
							t.acadsemester = '" . $acadsemester . "' and
							t.student_id = '" . $_SESSION['username'] . "' 
						group by
							t.SubjectCode 	
						order by
							t.SubjectCode								
							" ;
					$_select_subject = "";

					$result = mysqli_query($_connection,$sql_subjects);

					while($data = mysqli_fetch_assoc($result)) {
						$_select_subject = (isset($_POST['subject_id'])&&$_POST['subject_id']==$data['SubjectCode']?"selected":"");
						 ?>
						<option value="<?=$data['SubjectCode']?>" <?=$_select_subject?>>
							<?=$data['SubjectCode']?> (<?=$data['count']?>)
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

		$_date_array    = array();
		$_column_array  = array();

		$_sql_column = "
			select check_date,period from student_learn
			where
				acadyear     = '" . $acadyear . "' AND
				acadsemester = '" . $acadsemester . "' AND
				SubjectCode  = '" . $_POST['subject_id'] . "' AND
				student_id   = '" . $_SESSION['username'] . "'  
			order by
				check_date,period
		";

		$_res_column = mysqli_query($_connection,$_sql_column);
		$_row_column = mysqli_num_rows($_res_column);

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
									and t.student_id = '" . $_SESSION['username'] . "' 
									and t.SubjectCode = '" .$_POST['subject_id'] . "'
								group by student_id
						   ) as c
				on (s.id = c.student_id and s.xEDBE = '" . $acadyear . "')
			order by 
				sex,
				convert(firstname using tis620),
				convert(lastname  using tis620)
				" ;
		$_resClass = mysqli_query($_connection,$_sql_class_record);
		$_totalRows = mysqli_num_rows($_resClass);
		$_no = 1;
	?>
	<? if($_totalRows >0){  ?>
	<table class="admintable" align="center">
		<tr>
			<th colspan="10" align="center">
				<img src="../images/school_logo.png" width="120px"> <br/>
				บันทึกการเข้าเรียน รหัสวิชา <?=$_POST['subject_id']?> <br/>
			</th>
		</tr>
		<tr height="35px"> 
			<td class="key" width="25px"  align="center" rowspan="2">ที่</td>
			<td class="key" width="50px"  align="center" rowspan="2">ID</td>
			<td class="key" width="180px" align="center" rowspan="2">ชื่อ - นามสกุล</td>
			<td class="key" width="80px"  align="center" rowspan="2" >สถานภาพ<br/>ปัจจุบัน</td>
			<td class="key" align="center" colspan="<?=count($_date_array)?>">วันที่/คาบ</td>
			<td class="key" withd="100px" align="center" rowspan="2">รวม<br/>(<?=count($_date_array)?>)</td>
			<td class="key" withd="100px" align="center" rowspan="2">%</td>
		</tr>
		<tr height="110px">
			<? for($_i=0;$_i<count($_date_array);$_i++){ ?>
				<td class="key" width="15px" align="center">
					<div style="writing-mode: vertical-rl; transform:rotate(-180deg);">
						<?=$_date_array[$_i]?>
					</div>
				</td>
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

