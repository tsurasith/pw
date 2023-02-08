
<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_discipline/index"><img src="../images/discipline.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานวินัยนักเรียน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>2.1 รายงานแสดงการประวัติเพิ่ม/ลด คะแนนความประพฤติ</strong></font></span></td>
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
				<td align="right" width="140px" valign="top">เลขประจำตัวนักเรียน :</td>
				<td>
					<input type="text" name="student_id" value="<?=isset($_POST['student_id'])?$_POST['student_id']:""?>" maxlength="5" size="10" class="inputboxUpdate" />
					<br/>ถ้าต้องการสืบค้นนักเรียนที่มีเลขประจำตัวขึ้นต้นด้วย 07 ทั้งหมดสามารถป้อน 07% เพื่อสืบค้นได้
					<br/>ถ้าหากต้องการค้นหานักเรียนที่มีเลขประจำตัวลงด้วยด้วย 123 สามารถป้อน %123 เพื่อสืบค้นได้
				</td>
			</tr>
			<tr>
				<td align="right">ชื่อ :</td>
				<td>
					<input type="text" name="firstname" value="<?=isset($_POST['firstname'])?$_POST['firstname']:""?>" class="inputboxUpdate"/> 
					สกุล 
					<input type="text" name="lastname" value="<?=isset($_POST['lastname'])?$_POST['lastname']:""?>" class="inputboxUpdate" />
					&nbsp; 
					ชื่อเล่น :
					<input  type="text"  name="nickname" value="<?=isset($_POST['nickname'])?$_POST['nickname']:""?>" size="5" class="inputboxUpdate" />
				</td>
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
				<td align="right">ห้องเรียน :</td>
				<td>
					<select name="room" class="inputboxUpdate">
						<option <?=isset($_POST['room'])&&$_POST['room']==""?"selected":""?> value=""> ทั้งหมด </option>
						<?php
							$_sql_room = "
							
									select 
										DISTINCT
										concat(xlevel,'|',xyearth,'|',room) as 'room' ,
										concat(((xlevel-3)*3)+xyearth ,'/',room) as 'class_display'
									FROM students
									order by class_display
								
								";
							$_resStatus = mysqli_query($_connection,$_sql_room);
							while($_datRoom = mysqli_fetch_assoc($_resStatus))
							{  ?>
								<option value="<?=$_datRoom['room']?>" <?=isset($_POST['room'])&&$_POST['room']==$_datRoom['room']?"SELECTED":""?>>
									<?=$_datRoom['class_display']?>
								</option>
						<?	} mysqli_free_result($_resStatus); ?>
					</select>

					&nbsp; &nbsp; 
					สถานภาพ :

					<select name="studstatus" class="inputboxUpdate">
						<option <?=isset($_POST['studstatus'])&&$_POST['studstatus']==""?"selected":""?> value=""> ทั้งหมด </option>
						<?php
							$_resStatus = mysqli_query($_connection,"SELECT * FROM ref_studstatus ORDER BY FIELD(studstatus,0) ASC ");
							while($_datStatus = mysqli_fetch_assoc($_resStatus))
							{  ?>
								<option value="<?=$_datStatus['studstatus']?>" <?=isset($_POST['studstatus'])&&$_POST['studstatus']==$_datStatus['studstatus']?"SELECTED":""?>>
									<?=$_datStatus['studstatus_description']?>
								</option>
						<?	} mysqli_free_result($_resStatus); ?>
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
					<input type="radio" name="order_by" value="id"   <?=isset($_POST['order_by'])&&$_POST['order_by']=="id"?"checked":""?>> เรียงตามเลขประจำตัว  &nbsp; 
					<input type="radio" name="order_by" value="name" <?=isset($_POST['order_by'])&&$_POST['order_by']=="name"?"checked":""?>> เรียงตามใบรายชื่อ (เพศ, ชื่อ, เลขประจำตัว)
					<br/><br/>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td><td><input type="submit" name="search" value="สืบค้น"/> <input type="reset" value="ล้างข้อมูลสืบค้น" /></td>
			</tr>
			</table>
			</form>

		<? if(isset($_POST['search'])) {?>
		<?		
				$_class = array();
				if($_POST['room'] != ""){
					$_class = explode("|",$_POST['room']);
				}

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
						$_sql .= trim($_POST['point_type'])==""?"":" and point_type = '" . $_POST['point_type'] . "'";
						$_sql .= trim($_POST['student_id'])==""?"":" and id like '" . $_POST['student_id'] . "'";
						$_sql .= trim($_POST['firstname'])==""?"":" and s.firstname like '%" . $_POST['firstname'] . "%'";
						$_sql .= trim($_POST['lastname'])==""?"":" and s.lastname like '%" . $_POST['lastname'] . "%'";
						$_sql .= trim($_POST['nickname'])==""?"":" and s.nickname like '%" . $_POST['nickname'] . "%'";
						$_sql .= trim($_POST['acadyear'])==""?"":" and xedbe = '" . $_POST['acadyear'] . "' ";
						$_sql .= trim($_POST['studstatus'])==""?"":" and studstatus = '" . $_POST['studstatus'] . "' ";
						$_sql .= trim($_POST['room'])==""?"":" and xlevel = '" . $_class[0] . "' and xyearth = '" . $_class[1]. "' and room = '" . $_class[2]. "' ";
				
				if(!isset($_POST['order_by'])){
					$_sql .= " order by id";
				}
				else if($_POST['order_by']=="id"){
					$_sql .= " order by id";
				}else{
					$_sql .= " order by xlevel,xyearth,room,s.sex,convert(s.firstname using tis620),convert(s.lastname using tis620),id ";
				}

				//echo $_sql;
				
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
							<td class="key" width="80px">เลขประจำตัว</td>
							<td class="key" width="190px">ชื่อ - สกุล</td>
							<td class="key" width="50px">ชื่อเล่น</td>
							<td class="key" width="50px">ห้อง</td>
							<td class="key" width="50px">คะแนน</td>
							<td class="key" width="180px">สาเหตุ</td>
							<td class="key" width="100px">สถานะภาพ</td>
							<td class="key" width="50px">คะแนน<br/>คงเหลือ</td>
							<td class="key" width="75px">ปีการศึกษา</td>
							<td class="key" width="130px">แก้ไขโดย</td>
						</tr>
					
						<? $_i = 1; ?>
						<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_i++?></td>
							<td align="center"><?=$_dat['id']?></td>
							<td ><?=$_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname']?></td>
							<td align="left"><?=$_dat['nickname']?></td>
							<td align="center"><?=displayXyear($_dat['xlevel'] . '/' . $_dat['xyearth']) . '/' . $_dat['room']?></td>
							<td align="center">
								<?php
									if($_dat['point_type']==1){
										echo "<font color='green'>+";
										echo $_dat['point_changed'];
										echo "</font>";
									}else{
										echo "<font color='red'>-";
										echo $_dat['point_changed'];
										echo "</font>";
									}
								?>
							</td>
							<td align="left"><?=$_dat['point_comments']?></td>
							<td align="center"><?=displayStudentStatusColor($_dat['studstatus'])?></td>
							<td align="center"><?=$_dat['points']?></td>
							<td align="center"><?=$_dat['acadsemester'].'/'.$_dat['acadyear']?></td>
							<td ><?='ครู'. $_dat['t_firstname'] . ' ' . $_dat['t_lastname']?></td>
						</tr>
						<? } //end while?>
					</table>
			<?	} else {?>
					<center><font color="#008000"><br/><br/>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา โปรดลองระบุเงื่อนไขในการค้นหาใหม่อีกครั้ง</font></center>
			<? } //end if-else ค้นหาข้อมูลจากฐานข้อมูล ?>
		<? }//end check value ในการค้นหา ?>
	</div>
</div>
