
<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>2.3 ค้นหา/ตรวจสอบ ข้อมูลบุคลากรและภาระงาน</strong></font></span></td>
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
				<td align="right">ชื่อ :</td>
				<td>
					<input type="text" name="firstname" value="<?=isset($_POST['firstname'])?$_POST['firstname']:""?>" class="inputboxUpdate"/> 
					สกุล 
					<input type="text" name="lastname" value="<?=isset($_POST['lastname'])?$_POST['lastname']:""?>" class="inputboxUpdate" />
				</td>
			</tr>
			<tr>
				<td align="right">ประเภทบุคลากร :</td>
				<td>
					<?php
						$_sql = "select distinct staff_type from hr_staff order by 1 ";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="staff_type" class="inputboxUpdate">
						<option value="" <?=(isset($_POST['staff_type']) && $_POST['staff_type']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['staff_type']?>" <?=(isset($_POST['staff_type']) && $_POST['staff_type']==$_dat['staff_type']?"selected":"")?> >
								<?=$_dat['staff_type']==""?"ไม่ระบุ":$_dat['staff_type']?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">สถานภาพ :</td>
				<td>
					<?php
						$_sql = "select distinct staff_status from hr_staff order by 1 ";
						$_resSemester = mysqli_query($_connection,$_sql);
					?> 
					<select name="staff_status" class="inputboxUpdate">
					<option value="" <?=(isset($_POST['staff_status']) && $_POST['staff_status']==""?"selected":"")?> >ทั้งหมด</option>
						<? while($_dat = mysqli_fetch_assoc($_resSemester)) { ?> 
							<option value="<?=$_dat['staff_status']?>" <?=(isset($_POST['staff_status']) && $_POST['staff_status']==$_dat['staff_status']?"selected":"")?> >
								<?=$_dat['staff_status']=="ACTIVE"?"ปฏิบัติหน้าที่อยู่":"ไม่ปฏิบัติหน้าที่"?>
							</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">จำนวนรายการที่แสดง :</td>
				<td>
					<select name="list" class="inputboxUpdate">
						<option <?=isset($_POST['list'])&&$_POST['list']=="100"?"selected":""?> value="100">100</option>
						<option <?=isset($_POST['list'])&&$_POST['list']=="all"?"selected":""?> value="all">ทั้งหมด</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="radio" name="order_by" value="date"   <?=isset($_POST['order_by'])&&$_POST['order_by']=="date"?"checked":""?>> รหัสสแกนลายนิ้วมือ  &nbsp; 
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
							s.staff_id,
							s.finger_code,
							s.prefix,
							s.firstname,
							s.lastname,
							s.position,
							s.staff_type,
							s.staff_status,
							s.mobile_number,
							s.email,
							m.acadyear,
							m.acadsemester,
							m.department_id,
							d.department_name,
							m.sub_department_id,
							t.department_subtype_name,
							m.updated_datetime,
							m.updated_user
						from 
							hr_staff s left join hr_staff_department_mapping m 
							on (s.staff_id = m.staff_id) left join departments d 
							on (m.department_id = d.department_id) left join department_subtypes t
							on (m.sub_department_id = t.department_subtype_id)
						where
							s.staff_id != '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16' 			
					";

				$_sql .= trim($_POST['firstname'])==""?"":" and firstname like '%" . $_POST['firstname'] . "%'";
				$_sql .= trim($_POST['lastname'])==""?"":" and lastname like '%" . $_POST['lastname'] . "%'";
				$_sql .= trim($_POST['staff_type'])==""?"":" and staff_type = '" . $_POST['staff_type'] . "' ";
				$_sql .= trim($_POST['staff_status'])==""?"":" and staff_status = '" . $_POST['staff_status'] . "' ";
				
				if(!isset($_POST['order_by'])){
					$_sql .= " order by convert(firstname using tis620),convert(lastname using tis620) ";
				}
				else if($_POST['order_by']=="name"){
					$_sql .= " order by convert(firstname using tis620),convert(lastname using tis620) ";
				}else{
					$_sql .= " order by finger_code";
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
							<td class="key" width="25px">ที่</td>
							<td class="key" width="170px">ชื่อ - สกุล</td>
							<td class="key" width="60px">รหัสสแกน<br/>ลายนิ้วมือ</td>
							<td class="key" width="190px">ตำแหน่ง</td>
							<td class="key" width="160px">ประเภทบุคลากร</td>
							<td class="key" width="90px">สถานภาพ</td>
							<td class="key" width="190px">อีเมล</td>
							<td class="key" width="100px">เบอร์มือถือ</td>
						</tr>
					
						<? $_i = 1; ?>
						<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
						<tr onMouseOver="this.style.backgroundColor='#E5EBFE'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
							<td align="center"><?=$_i++?></td>
							<td ><?=$_dat['prefix'] . $_dat['firstname'] . ' ' . $_dat['lastname']?></td>
							<td align="center"><?=$_dat['finger_code']?></td>
							<td align="left"><?=$_dat['position']?></td>
							<td align="left"><?=$_dat['staff_type']?></td>
							<td align="center"><?=$_dat['staff_status']=="ACTIVE"?"ปฏิบัติหน้าที่":"ไม่ปฏิบัติหน้าที่"?></td>
							<td align="left"><?=$_dat['email']?></td>
							<td align="center"><?=$_dat['mobile_number']?></td>
						</tr>
						<? } //end while?>
					</table>
			<?	} else {?>
					<center><font color="red"><br/><br/>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา โปรดลองระบุเงื่อนไขในการค้นหาใหม่อีกครั้ง</font></center><br/><br/>
			<? } //end if-else ค้นหาข้อมูลจากฐานข้อมูล ?>
		<? }//end check value ในการค้นหา ?>
	</div>
</div>
