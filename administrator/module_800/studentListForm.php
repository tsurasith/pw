
<script type="text/javascript" language="javascript">
	function check(name,value){ document.getElementById(name).bgColor=value; }
</script>
<?php
	$_roomID = "";
	$_roomID = isset($_POST['roomID'])?$_POST['roomID']:"";
	
	$room_id = getRoom($_REQUEST['room']);
	$xyear = getXyearth($_REQUEST['room']);
	$xlevel  = getXLevel($_REQUEST['room']);

	$sql = "
			SELECT 
				id, prefix , firstname , lastname 
			FROM 
				students 
			WHERE 
				xLevel     = '" . $xlevel . "' and 
				xYearth    = '" . $xyear . "' and 
				room       = '" . $room_id  .  "' and 
				studstatus = '1'                  and 
				xedbe      = '" . $acadyear . "' 
			order by 
				sex,
				convert(firstname using tis620),
				convert(lastname using tis620)
	";

	//echo $sql;
	$result = mysqli_query($_connection,$sql) or die ('ผิดพลาดเนื่องจาก  - ' .mysqli_error($_connection));
	$i = 1;
	$j = 0;
	$rows = mysqli_num_rows($result);

	/*
		$date = ????-??-??
		$room_id = ?
		$room = ?0?
		$xlevel = 3|4
		$xyear = 1-6
	*/
?>

<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr>
			<td width="6%" align="center"><a href="index.php?option=module_800/index">
				<img src="../images/modules/800_clock2.png" alt="" width="48" height="48" border="0"/></a>
			</td>
			<td width="45%">
				<strong><font color="#990000" size="4">8.00 O' Clock</font></strong><br /> 
				<span class="normal"><font color="#0066FF"><strong>1.2.1 หน้าแบบฟอร์มบันทึกข้อมูลการเข้าร่วมกิจกรรมหน้าเสาธง</strong></font></span>
			</td>
			<td >
				ปีการศึกษา <?=$acadyear?>  ภาคเรียนที่	<?=$acadsemester?>	
			</td>
		</tr>
	</table>

	
	<div align="center">
		<table width="800" align="center" border="0" cellspacing="10" cellpadding="0"  class="admintable">
			<tr>
				<th align="center">
					<img src="../images/school_logo.png" width="120px"><br/>
					บันทึกการมาเข้าร่วมกิจกรรมหน้าเสาธง<br />
					ภาคเรียนที่ <?=$_REQUEST['acadsemester']?>  ปีการศึกษา <?=$_REQUEST['acadyear']?><br />
					ประจำวันที่
					<font color="red"><?=displayFullDate($_REQUEST['date']); ?></font>
					ห้อง  <font color="red"><?=getFullRoomFormat($_REQUEST['room']); ?> </font>
				</th>
			</tr>
			<tr>
				<td align="center">
					<form method="post" action="index.php?option=module_800/insertStudentCheck">
						<input type="hidden" name="room_id" value="<?php echo $_REQUEST['room']; ?>" />
						<input type="hidden" name="acadyear" value="<?=$_REQUEST['acadyear']?>"/>
						<input type="hidden" name="acadsemester" value="<?=$_REQUEST['acadsemester']?>"/>
						<input type="hidden" name="date" value="<?php echo $_REQUEST['date']; ?>"/>
						
						<table width="638px" cellspacing="1" cellpadding="1">
							<tr>
								<td>
									<input type="radio" name="teacherSign" checked value="sign" id="teacherSign">
              						<label for="teacherSign"><strong><font color="#009900" face="Tahoma" size="2"> มี</font></strong><font face="Tahoma" size="2">ลายเซ็นต์อาจารย์ที่ปรึกษา </font> </label><br/>
									<input type="radio" name="teacherSign" value="unsign" id="teacherUnsign">
									<label for="teacherUnsign"><strong><font color="#FF0000" face="Tahoma" size="2">ไม่มี</font></strong><font face="Tahoma" size="2">ลายเซ็นต์อาจารย์ที่ปรึกษา  </font></label><br/>
									<font face="Tahoma" size="2">ผู้บันทึกข้อมูล :</font><font color="blue" face="Tahoma" size="2"> <?= $_SESSION['name'] ?></font>
								</td>
							</tr>
						</table>
						
						<table border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#3366FF">
							<tr bgcolor="#CCCCFF">
								<td width="35px"  rowspan="2"><div align="center"><font color="#990066"><strong>เลขที่</strong></font></div></td>
								<td width="100px" rowspan="2"><div align="center"><font color="#990066"><strong>เลขประจำตัว</strong></font></div></td>
								<td width="190px" rowspan="2"><div align="center"><font color="#990066"><strong>ชื่อ - สกุล</strong></font></div></td>
								<td colspan="5"><div align="center"><font color="#990066"><strong>การมาเข้าแถว</strong></font></div></td>
							</tr>
							<tr>
								<td width="45px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>มา</strong></font></div></td>
								<td width="45px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>กิจกรรม</strong></font></div></td>
								<td width="45px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>สาย</strong></font></div></td>
								<td width="45px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>ลา</strong></font></div></td>
								<td width="45px" bgcolor="#CCCCFF"><div align="center"><font color="#990066"><strong>ขาด</strong></font></div></td>
							</tr>
							<? while($data = mysqli_fetch_array($result)) { ?>
								<tr class="hover"   id="check[<?=$j?>]"  bgcolor='#FFFFFF' >
									<td align="center"><font size="2" face="Tahoma"><?=$i?></font></td>
									<td align="center">
										<input type="hidden" name="student_id[<?=$j?>]" value="<?=$data[0]?>" />
										<font size="2" face="Tahoma"><?=$data[0]?></font>
									</td>
									<td>
										<font size="2" face="Tahoma"><?=$data[1].$data[2].' '.$data[3]?></font>
									</td>
									<td align="center"><input type='radio' name="check[<?=$j?>]" value='white'      onclick="check(this.name,this.value)"   checked /></td>
									<td align="center"><input type='radio' name="check[<?=$j?>]" value='lightgreen' onclick="check(this.name,this.value)" /></td>
									<td align="center"><input type='radio' name="check[<?=$j?>]" value='yellow'     onclick="check(this.name,this.value)" ></td>
									<td align="center"><input type='radio' name="check[<?=$j?>]" value='blue'       onclick="check(this.name,this.value)" ></td>
									<td align="center"><input type='radio' name="check[<?=$j?>]" value='red'        onclick="check(this.name,this.value)" ></td>
								</tr>
							<?  $j++; $i++;
								} ?> 
							<tr bgcolor="#FFFFFF">
								<td colspan="8" align="center"> 
									<input type="hidden" name="count" value="<?=$j?>" />
									<input class="button" type="submit" value="บันทึก"/> 
									<input class="button" type="button" value="ยกเลิก" onClick="location.href='index.php?option=module_800/dateTaskList&date=<?=$_REQUEST['date']?>'" /> 
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>
	</div>
</div>
<? mysqli_free_result($result);  ?>			