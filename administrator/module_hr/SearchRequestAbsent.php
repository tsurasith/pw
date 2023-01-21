<link rel="stylesheet" type="text/css" href="module_moral/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_moral/js/calendar.js"></script>

<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>1.3 ค้นหา/ติดตาม การยื่นคำขอ</strong></font></span></td>
			<td >&nbsp;</td>
			</tr>
		</table>
		<br/>

		<form action="" method="post" autocomplete="off">
			<table align="center" class="admintable" >
			<tr height="30px"> 
				<td  class="key" colspan="2">โปรดใส่รายละเอียดที่ต้องการสืบค้น</td>
			</tr>
			<tr>
				<td align="right" width="160px" valign="top">วันที่ลา/ไปราชการ :</td>
				<td>
					<input	type="text" name="start_absent_date" 
							value="<?=isset($_POST['start_absent_date'])?$_POST['start_absent_date']:""?>" 
							maxlength="10" size="10" class="inputboxUpdate" 
							id="start_absent_date"
							onClick="showCalendar(this.id)" />
				</td>
			</tr>
			<tr>
				<td align="right">ชื่อ :</td>
				<td>
					<input type="text" name="firstname" value="<?=isset($_POST['firstname'])?$_POST['firstname']:""?>" class="inputboxUpdate"/> 
					สกุล 
					<input type="text" name="lastname" value="<?=isset($_POST['lastname'])?$_POST['lastname']:""?>" class="inputboxUpdate" />
				</td>
			</tr>
			<tr>
				<td align="right">ปีการศึกษา </td>
				<td>
					<?php
						$_sql = "select distinct acadyear from hr_staff_absent order by 1";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="acadyear" class="inputboxUpdate">
					<option value="" <?=(isset($_POST['acadyear']) && $_POST['acadyear']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['acadyear']?>" <?=(isset($_POST['acadyear']) && $_POST['acadyear']==$_dat['acadyear']?"selected":"")?> >
								<?=$_dat['acadyear']?>
							</option>
						<? } ?>
					</select>

					&nbsp; &nbsp; 
					ภาคเรียน :
					<?php
						$_sql = "select distinct acadsemester from hr_staff_absent order by 1";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="acadsemester" class="inputboxUpdate">
						<option value="" <?=(isset($_POST['acadsemester']) && $_POST['acadsemester']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['acadsemester']?>" <?=(isset($_POST['acadsemester']) && $_POST['acadsemester']==$_dat['acadsemester']?"selected":"")?> >
								<?=$_dat['acadsemester']?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">การลา/ไปราชการ :</td>
				<td>
					<?php
						$_sql = "select distinct absent_subtype from hr_staff_absent order by convert(absent_subtype using tis620) ";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="absent_subtype" class="inputboxUpdate">
						<option value="" <?=(isset($_POST['absent_subtype']) && $_POST['absent_subtype']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['absent_subtype']?>" <?=(isset($_POST['absent_subtype']) && $_POST['absent_subtype']==$_dat['absent_subtype']?"selected":"")?> >
								<?=$_dat['absent_subtype']?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">สถานะคำขอ :</td>
				<td>
					<?php
						$_sql = "select distinct request_status from hr_staff_absent order by convert(request_status using tis620) ";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="request_status" class="inputboxUpdate">
					<option value="" <?=(isset($_POST['request_status']) && $_POST['request_status']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['request_status']?>" <?=(isset($_POST['request_status']) && $_POST['request_status']==$_dat['request_status']?"selected":"")?> >
								<?=$_dat['request_status']?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">จำนวนรายการที่แสดง :</td>
				<td>
					<select name="list" class="inputboxUpdate">
						<option <?=isset($_POST['list'])&&$_POST['list']=="10"?"selected":""?> value="10">10</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="25"?"selected":""?> value="25">25</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="50"?"selected":""?> value="50">50</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="100"?"selected":""?> value="100">100</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="all"?"selected":""?> value="all">ทั้งหมด</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="radio" name="order_by" value="date"   <?=isset($_POST['order_by'])&&$_POST['order_by']=="date"?"checked":""?>> เรียงตามวันที่ยื่นคำร้อง  &nbsp; 
					<input type="radio" name="order_by" value="name"   <?=isset($_POST['order_by'])&&$_POST['order_by']=="name"?"checked":""?>> เรียงตามชื่อ-นามสกุล
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

				$_sql = "
						select 
							s.prefix,
							s.firstname,
							s.lastname,
							s.position,
							h.*
						from
							hr_staff_absent h inner join hr_staff s
							on (
								h.staff_id = s.staff_id
							) 
						where
							1=1
					";

				$_sql .= trim($_POST['start_absent_date'])==""?"":" and start_absent_date = '" . $_POST['start_absent_date'] . "'";
				$_sql .= trim($_POST['firstname'])==""?"":" and firstname like '%" . $_POST['firstname'] . "%'";
				$_sql .= trim($_POST['lastname'])==""?"":" and lastname like '%" . $_POST['lastname'] . "%'";
				$_sql .= trim($_POST['acadyear'])==""?"":" and h.acadyear = '" . $_POST['acadyear'] . "'";
				$_sql .= trim($_POST['acadsemester'])==""?"":" and h.acadsemester = '" . $_POST['acadsemester'] . "' ";
				$_sql .= trim($_POST['absent_subtype'])==""?"":" and absent_subtype = '" . $_POST['absent_subtype'] . "' ";
				$_sql .= trim($_POST['request_status'])==""?"":" and request_status = '" . $_POST['request_status'] . "' ";
				
				if(!isset($_POST['order_by'])){
					$_sql .= " order by start_absent_date";
				}
				else if($_POST['order_by']=="name"){
					$_sql .= " order by convert(firstname using tis620),convert(lastname using tis620) ";
				}else{
					$_sql .= " order by start_absent_date ";
				}

				//echo $_sql;
				
				if($_POST['list'] != "all")	$_sql .= " limit 0," . $_POST['list'] ;

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
							<td class="key" width="140px">วันที่ลา/ไปราชการ</td>
							<td class="key" width="50px">ลา<br/>(วัน)</td>
							<td class="key" width="80px">คำร้องขอ</td>
							<td class="key" width="130px">สถานะคำร้องขอ</td>
							<td class="key" width="80px">ปีการศึกษา</td>
							<td class="key" width="140px">วันที่ยื่นคำร้อง</td>
							<td class="key">-</td>
						</tr>
					
						<? $_i = 1; ?>
						<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_i++?></td>
							<td ><?=$_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname']?></td>
							<td align="left"><?=displayDateShortMonth($_dat['start_absent_date']) . ' (' . displayDayofWeek(date('w',strtotime($_dat['start_absent_date']))) . ')'?></td>
							<td align="center"><?=$_dat['total_absent']?></td>
							<td align="center"><?=$_dat['absent_subtype']?></td>
							<td align="center"><?=$_dat['request_status']?></td>
							<td align="center"><?=$_dat['acadsemester'].'/'.$_dat['acadyear']?></td>
							<td align="center"><?=$_dat['created_datetime']?></td>
							<td align="center">
								<a href="index.php?option=module_hr/RequestAbsentDetail&absent_id=<?=$_dat['absent_id']?>" target="_blank">
									รายละเอียด
								</a>
							</td>
						</tr>
						<? } //end while?>
					</table>
			<?	} else {?>
					<center><font color="red"><br/><br/>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา โปรดลองระบุเงื่อนไขในการค้นหาใหม่อีกครั้ง</font></center><br/><br/>
			<? } //end if-else ค้นหาข้อมูลจากฐานข้อมูล ?>
		<? }//end check value ในการค้นหา ?>
	</div>
</div>
