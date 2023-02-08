
<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_discipline/index"><img src="../images/discipline.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานวินัยนักเรียน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>1.2 คะแนนพฤติกรรม</strong></font></span></td>
			<td >&nbsp;</td>
			</tr>
		</table>
		<br/>

		<form action="" method="post" autocomplete="off">
			<table align="center" class="admintable" >
			<tr height="30px"> 
				<td  class="key" colspan="2">โปรดใส่รายละเอียดที่ต้องการสืบค้นประวัติการเพิ่ม/ลด คะแนนพฤติกรรมนักเรียน</td>
			</tr>
			<tr>
				<td align="right">ปีการศึกษา </td>
				<td>
					<?php
						$_sql = "select distinct xedbe from students order by 1";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="acadyear" class="inputboxUpdate">
					<option value="" <?=(isset($_POST['acadyear']) && $_POST['acadyear']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['xedbe']?>" <?=(isset($_POST['acadyear']) && $_POST['acadyear']==$_dat['xedbe']?"selected":"")?> >
								<?=$_dat['xedbe']?>
							</option>
						<? } ?>
					</select>


					คะแนน :
					<select name="point_type" class="inputboxUpdate">
						<option value="">ทั้งหมด</option>
						<option value="1" <?=isset($_POST['point_type'])&&$_POST['point_type']=="1"?"SELECTED":""?> >เพิ่ม</option>
						<option value="0" <?=isset($_POST['point_type'])&&$_POST['point_type']=="0"?"SELECTED":""?>>หัก</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">จำนวนรายการที่แสดง :</td>
				<td>
					<select name="list" class="inputboxUpdate">
						<option <?=isset($_POST['list'])&&$_POST['list']=="50"?"selected":""?> value="50">50</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="100"?"selected":""?> value="100">100</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="all"?"selected":""?> value="all">ทั้งหมด</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="checkbox" name="group" value="group" <?=isset($_POST['group'])&&$_POST['group']=="group"?"checked":""?> /> ไม่แสดงประวัติคะแนน 
					<br/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td><input type="submit" name="search" value="สืบค้น"/> <input type="reset" value="ล้างข้อมูลสืบค้น" /></td>
			</tr>
			</table>
			</form>

		<? if(isset($_POST['search'])) {?>
		<?		
				$_sql_more = "";
				$_sql_2 = "
							select 
								h.student_id,
								h.dis_id,
								sum(if(h.point_type=1,h.point_changed,-h.point_changed)) as point_changed,
								h.point_comments,
								h.acadyear,
								h.acadsemester,
								h.updated_user,
								s.prefix,
								s.firstname,
								s.lastname,
								s.nickname,
								s.xlevel,
								s.xyearth,
								s.xedbe,
								s.room,
								s.points,
								s.id,
								s.sex,
								s.studstatus,
								t.PREFIX as t_prefix,
								t.FIRSTNAME as t_firstname,
								t.LASTNAME as t_lastname
							from 
							student_point_history h inner join students s 
							on (h.student_id = s.ID) left join teachers t
							on (h.updated_user = t.teacher_id)
							where 1=1 
						";

				$_sql = "
							select 
								h.student_id,
								h.dis_id,
								h.point_type,
								if(h.point_type='1','เพิ่ม','หัก') as display,
								h.point_changed,
								h.point_comments,
								h.acadyear,
								h.acadsemester,
								h.updated_user,
								s.prefix,
								s.firstname,
								s.lastname,
								s.nickname,
								s.xlevel,
								s.xyearth,
								s.xedbe,
								s.room,
								s.points,
								s.id,
								s.sex,
								s.studstatus,
								t.PREFIX as t_prefix,
								t.FIRSTNAME as t_firstname,
								t.LASTNAME as t_lastname
							from 
							student_point_history h inner join students s 
							on (h.student_id = s.ID) left join teachers t
							on (h.updated_user = t.teacher_id)
							where 1=1 ";
						$_sql_more .= " and h.student_id = '" . $_SESSION['username'] ."' ";
						$_sql_more .= trim($_POST['point_type'])==""?"":" and point_type = '" . $_POST['point_type'] . "'";
						$_sql_more .= trim($_POST['acadyear'])==""?"":" and xedbe = '" . $_POST['acadyear'] . "' ";
				
				if(isset($_POST['group'])){
					$_sql_2 .= $_sql_more;
					$_sql_2 .= " group by h.student_id ";
					$_sql   = $_sql_2;
				}else{
					$_sql .= $_sql_more;
				}
				
				if($_POST['list'] != "all")	$_sql .= " limit 0," . $_POST['list'] ;

				$_total_rows = 0;

				//echo $_sql;

				$_result = mysqli_query($_connection,$_sql);
				$_total_rows = mysqli_num_rows($_result);
				
				if(mysqli_num_rows($_result) > 0) { ?>
					<table class="admintable" align="center">
						<tr height="35px">
							<td colspan="11" align="right">
								<font color='green'><b>
									จำนวนข้อมูลที่ตรงกับเงื่อนไขที่สืบค้นจำนวน <?=$_total_rows?> รายการ</b>
								</font>
							</td>
						</tr>
						<tr align="center" height="30px">
							<td class="key" width="35px">ที่</td>
							<td class="key" width="65px">เลขประจำตัว</td>
							<td class="key" width="190px">ชื่อ - สกุล</td>
							<td class="key" width="55px">ชื่อเล่น</td>
							<td class="key" width="40px">ห้อง</td>
							<td class="key" width="60px">คะแนน<br/>คงเหลือ</td>
							<td class="key" width="50px">ประวัติ</td>
							<td class="key" width="180px">สาเหตุ</td>
							<td class="key" width="80px">สถานะภาพ</td>
							<td class="key" width="75px">ปีการศึกษา</td>
							<td class="key" width="150px">แก้ไขโดย</td>
						</tr>
					
						<?php
							$_init_student_id = "";
						?>

						<? $_i = 1; ?>
						<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td valign="top" align="center"><?=$_i++?></td>
							<td valign="top" align="center">
								<?
									if($_init_student_id != $_dat['id']){
										echo $_dat['id'];
									}
								?>
							</td>
							<td valign="top" >
								<?php
									if($_init_student_id != $_dat['id']){
										echo $_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname'];
									}
								?>
							</td>
							<td valign="top" align="left">
								<?php
									if($_init_student_id != $_dat['id']){
										echo $_dat['nickname'];
									}
								?>
							</td>
							<td valign="top" align="center">
								<?php
									if($_init_student_id != $_dat['id']){
										echo displayXyear($_dat['xlevel'] . '/' . $_dat['xyearth']) . '/' . $_dat['room'];
									}
								?>
							</td>
							<td valign="top" align="center">
								<?php
									if($_init_student_id != $_dat['id']){
										if($_dat['points']<=0){
											echo "<font color='red'>";
											echo $_dat['points'];
											echo "</font>";
										}else if($_dat['points']>=100){
											echo "<font color='green'>";
											echo $_dat['points'];
											echo "</font>";
										}else{
											echo $_dat['points'];
										}
									}
								?>
							</td>
							<td valign="top" align="center">
								<?php
									if(isset($_POST['group'])){
										if($_dat['point_changed']>0){

											echo "<font color='green'>+";
											echo $_dat['point_changed'];
											echo "</font>";
										}else{
											echo "<font color='red'>";
											echo $_dat['point_changed'];
											echo "</font>";
										}

									}else{

										if($_dat['point_type']==1){
											echo "<font color='green'>+";
											echo $_dat['point_changed'];
											echo "</font>";
										}else{
											echo "<font color='red'>-";
											echo $_dat['point_changed'];
											echo "</font>";
										}
									}
								?>
							</td>
							<td valign="top" align="left"><?=$_dat['point_comments']?></td>
							<td valign="top" align="center">
								<?php
									if($_init_student_id != $_dat['id']){
										echo displayStudentStatusColor($_dat['studstatus']);
									}
								?>
							</td>
							<td valign="top" align="center">
								<?php
									if($_init_student_id != $_dat['id']){
										echo $_dat['acadsemester'].'/'.$_dat['acadyear'];
									}
								?>
							</td>
							<td valign="top" ><?='ครู'. $_dat['t_firstname'] . ' ' . $_dat['t_lastname']?></td>
						</tr>
						<? $_init_student_id = $_dat['id']; ?>
						<? } //end while?>
					</table>
			<?	} else {?>
					<center><font color="#008000"><br/><br/>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา โปรดลองระบุเงื่อนไขในการค้นหาใหม่อีกครั้ง</font></center>
			<? } //end if-else ค้นหาข้อมูลจากฐานข้อมูล ?>
		<? }//end check value ในการค้นหา ?>
	</div>
</div>
