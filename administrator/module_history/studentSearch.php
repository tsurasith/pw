﻿
<div id="content">

 <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_history/index"><img src="../images/users.png" alt="" width="48" height="48" /></a></td>
      <td><strong><font color="#990000" size="4">สืบค้นประวัติ</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1. รายการสืบค้นประวัติ [ค้นหานักเรียน]</strong></font></span></td>
      <td >&nbsp;</td>
    </tr>
  </table>
  <br/>

  <form action="" method="post" autocomplete="off">
	<table width="100%" align="center" cellspacing="1" class="admintable" cellpadding="3">
      <tr height="30px"> 
        <td  class="key" colspan="2">โปรดใส่รายละเอียดที่ต้องการสืบค้นข้อมูลนักเรียน</td>
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
		</td>
	  </tr>
	  <tr>
	  	<td align="right">ชื่อเล่น :</td>
		<td>
			<input  type="text"  name="nickname" value="<?=isset($_POST['nickname'])?$_POST['nickname']:""?>" size="10" class="inputboxUpdate" />
		</td>
	  </tr>
	  <tr>
	  	<td align="right">หมู่บ้านที่อาศัย :</td>
		<td>
			<input type="text" name="p_village" value="<?=isset($_POST['p_village'])?$_POST['p_village']:""?>" size="40" class="inputboxUpdate" />
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
	  	<td>&nbsp;</td><td><input type="submit" name="search" value="สืบค้น"/> <input type="reset" value="ล้างข้อมูลสืบค้น" /></td>
	  </tr>
	</table>
	</form>


<? if(isset($_POST['search']) && $_POST['student_id'] == "" && $_POST['firstname'] == "" && $_POST['lastname'] == "" && $_POST['nickname'] == ""  && $_POST['p_village'] == "") {?>
		<center><font color="#FF0000"><br/><br/>โปรดใส่รายละเอียดที่ต้องการสืบค้นข้อมูลนักเรียนก่อนค้นหา
		<br/><br/><input type="button" value="กลับไปหน้าค้นหา" onClick="history.go(-1)"/>
		</font></center>
  <? } ?>
  
<? if(isset($_POST['search']) && ($_POST['student_id'] != "" || $_POST['firstname'] != "" || $_POST['lastname'] != "" || $_POST['nickname'] != ""  || $_POST['p_village'] != "")) {?>
<?		$_sql = "select distinct id from students 
				where 1=1 ";
				$_sql .= trim($_POST['student_id'])==""?"":" and id like '" . $_POST['student_id'] . "'";
				$_sql .= trim($_POST['firstname'])==""?"":" and firstname like '%" . $_POST['firstname'] . "%'";
				$_sql .= trim($_POST['lastname'])==""?"":" and lastname like '%" . $_POST['lastname'] . "%'";
				$_sql .= trim($_POST['nickname'])==""?"":" and nickname like '%" . $_POST['nickname'] . "%'";
				$_sql .= trim($_POST['p_village'])==""?"":" and p_village like '%" . $_POST['p_village'] . "%' ";
				$_sql .= " order by id";
		//echo $_sql;
		
		if($_POST['list'] != "all")	$_sql .= " limit 0," . $_POST['list'] ;

		$_total_rows = 0;
		$_result = mysqli_query($_connection,$_sql);
		$_total_rows = mysqli_num_rows($_result);
		
		if(mysqli_num_rows($_result) > 0) { ?>
			<table class="admintable" align="center">
				<tr height="35px">
					<td colspan="7" align="right">
						<font color='green'><b>
							จำนวนข้อมูลที่ตรงกับเงื่อนไขที่สืบค้นจำนวน <?=$_total_rows?> รายการ</b>
						</font>
					</td>
				</tr>
				<tr align="center" height="30px">
					<td class="key" width="35px">ที่</td>
					<td class="key" width="75px">เลขประจำตัว</td>
					<td class="key" width="200px">ชื่อ - สกุล</td>
					<td class="key" width="50px">ชื่อเล่น</td>
					<td class="key" width="40px">ห้อง</td>
					<td class="key" width="120px">สถานะภาพ</td>
					<td class="key" width="180px">หมู่บ้าน</td>
				</tr>
			
				<? $_i = 1; ?>
				<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
				<tr>
					<td align="center"><?=$_i++?></td>
					<? $_sqlx = " select prefix,firstname,lastname,nickname,xedbe,xyearth,xlevel,room,p_village,studstatus from students
									where id = '" . $_dat['id'] . "' order by xedbe desc limit 0,1 ";
						$_resx = mysqli_query($_connection,$_sqlx);
						$_datx = mysqli_fetch_assoc($_resx); ?>
					<td align="center"><a href="index.php?option=module_history/history&studentid=<?=$_dat['id']?>&acadyear=<?=$_datx['xedbe']?>"><?=$_dat['id']?></a></td>
					<td ><?=$_datx['prefix'] . $_datx['firstname'] . ' ' . $_datx['lastname']?></td>
					<td align="left"><?=$_datx['nickname']?></td>
					<td align="center"><?=displayXyear($_datx['xlevel'] . '/' . $_datx['xyearth']) . '/' . $_datx['room']?></td>
					<td align="center"><?=displayStudentStatusColor($_datx['studstatus'])?></td>
					<td >
						<?php
							if(isset($_datx['p_village'])){
								echo $_datx['p_village'];
							}else{
								echo "-";
							}
						?>
					</td>
				</tr>
				<? } //end while?>
			</table>
	<?	} else {?>
			<center><font color="#008000"><br/><br/>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา โปรดลองระบุเงื่อนไขในการค้นหาใหม่อีกครั้ง
			<br/><br/><br/><input type="button" value="กลับไปหน้าค้นหา" onClick="history.go(-1)"/></font></center>
  	<? } //end if-else ค้นหาข้อมูลจากฐานข้อมูล ?>
<? }//end check value ในการค้นหา ?>

</div>
