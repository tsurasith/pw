
<?php
/* echo $_SESSION['name'] . "<br/>";
echo $_POST['room_id'] . "<br/>";
echo date('Y-m-d') . "<br/>";
echo $_POST['date'] . "<br/>"; */
	$a = false;
	for($i =0 ;$i < $_POST['count'] ;$i ++)
	{
		$row_id = '';
		if($i < 10)
		{
			$row_id = $_POST['date'] . $_POST['room_id'] . '0' .$i;
		}
		else
		{
			$row_id = $_POST['date'] . $_POST['room_id'] .$i;
		}
		$sql_insert_student = 'REPLACE INTO student_800 VALUES (\'' . $row_id . '\', \'' . $_POST['student_id'][$i]  . '\', \''. $_POST['room_id'] .'\', \'' . timecheck_id($_POST['check'][$i]) .'\', \''. $_POST['date'] . '\',\'' . date('Y-m-d') . '\', ' .$_POST['acadyear'] .', ' . $_POST['acadsemester'] . ', \''. $_SESSION['name']  . '\');'; 
		$a = mysqli_query($_connection,$sql_insert_student) or die ('Error - ' . mysqli_error($_connection));  // บันทึกข้อมูลการเช็ค
		if($_POST['check'][$i] == "yellow" || $_POST['check'][$i] == "blue" || $_POST['check'][$i] == "red")
		{
			$sql = "replace into student_discipline values (null,'" . $_POST['student_id'][$i] . "','" . $_POST['date'] . "','08.00',' ',' ','". $_SESSION['name'] . "',curdate(),
								'ครู','" . $_SESSION['name'] . "','" . $_POST['date'] . "','เห็นควรมอบให้หัวหน้าระดับดำเนินการสอบสวน',
								'" . disDetail($_POST['check'][$i]) . "','" . $_SESSION['name'] . "')";
			$sql_status = "insert into student_disciplinestatus values('" . $_POST['student_id'][$i]  . "',null,'1','00','0','" . $acadyear . "','" . $acadsemester . "')";
			mysqli_query($_connection,$sql);
			mysqli_query($_connection,$sql_status);
		}
	}
	
	
	$sql_insert_teacher = 'replace into  teachers_800 VALUES (\'' . $_POST['date'] . '-' . $_POST['room_id'] .'\',\'' . $_POST['room_id'] .'\',\'' . $_POST['teacherSign'] .'\' , \'' . $_POST['date'] .'\', ' .$_POST['acadyear'] .', ' . $_POST['acadsemester'] . ') ';
	$b = mysqli_query($_connection,$sql_insert_teacher) or die ('Error - '. mysqli_error($_connection)); // บันทึกการเข้าใช้งานของครู
	updateTask($_connection,$_POST['date'],$_POST['room_id']); // อัพเดทสถานะงานเป็น "บันทึก" แล้ว

	// add line notification
	if($a && $b){

		$_verifySQL = "
						select class_id,
							sum(if(timecheck_id = '00',timecheck_id,null)+1) as a,
							sum(if(timecheck_id = '01',timecheck_id,null)) as b,
							sum(if(timecheck_id = '02',timecheck_id,null))/2 as c,
							sum(if(timecheck_id = '03',timecheck_id,null))/3 as d,
							sum(if(timecheck_id = '04',timecheck_id,null))/4 as e,
							count(class_id) as sum
						from student_800
						where check_date = '" . $_POST['date'] . "' and class_id = '" . $_POST['room_id'] . "' 
						group by class_id ";
		$_text = "ผลการมาเข้าร่วมกิจกรรมหน้าเสาธงนักเรียนชั้น " . getFullRoomFormat($_POST['room_id']);
		$_text .= "\n" . reportHeader($_POST['date']);

		$result = mysqli_query($_connection,$_verifySQL);
		if($result){
		$dat = mysqli_fetch_assoc($result);

		$_text .= "\n" . "มา: " .      ($dat['a']>0?$dat['a']:"-");
		$_text .= "\n" . "กิจกรรม: " .  ($dat['b']>0?$dat['b']:"-");
		$_text .= "\n" . "สาย: " .     ($dat['c']>0?$dat['c']:"-");
		$_text .= "\n" . "ลา: " .      ($dat['d']>0?$dat['d']:"-");
		$_text .= "\n" . "ขาด: " .     ($dat['e']>0?$dat['e']:"-");

		$_sum = 0;
		$_sum = ($dat['a']+$dat['b']+$dat['c']+$dat['d']+$dat['e']);

		$_text .= "\n" . "รวมทั้งหมด " . $_sum . " คน ";
		$_text .= "\n" . "บันทึกข้อมูลโดย - " . $_SESSION['shortname'];

		$message = $_text;
			SendLineMessage($message,$_line_token);
		}
		
	}
?>
	  <div id="content">
	  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_800/index"><img src="../images/modules/800_clock2.png" alt="" width="48" height="48" border="0"/></a></td>
      <td width="45%"><strong><font color="#990000" size="4">8.00 O' Clock</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>ระบบบันทึกการเข้าร่วมกิจกรรมหน้าเสาธง</strong></font></span></td>
      <td >&nbsp;</td>
    </tr>
  </table>
  <br/>
<table width="75%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#CC99FF">
  <tr bgcolor="#CCCCCC"> 
    <td colspan="2"><font size="2"  ><strong>ผลการดำเนินงาน</strong></font></td>
  </tr>
  <tr bgcolor="white"> 
    <td width="200px"><font size="2"  >ตรวจสอบรายชื่อห้อง</font></td>
    <td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
  </tr>
  <tr bgcolor="white"> 
    <td><font size="2"  >บันทึกรายการนัดพบ</font></td>
    <td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
  </tr>
  <tr bgcolor="white"> 
    <td><font size="2"  >บันทึกการเข้าแถว</font></td>
    <td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
  </tr>
  <tr bgcolor="white"> 
    <td><font size="2"  >บันทึกการเข้าร่วมของครูที่ปรึกษา</font></td>
    <td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
  </tr>
  <tr bgcolor="white"> 
    <td><font size="2"  >ส่งข้อความแจ้งทางไลน์</font></td>
    <td>
		<?php
			if(isset($_SESSION['line-success']) && $_SESSION['line-success'] != "") { ?>	
				<font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font>
		<? } else { ?>
				<font color="red" size="2"  ><?=$_SESSION['line-error']?></font>
		<? } ?>
	</td>
  </tr>
  <tr bgcolor="white"> 
    <td><font size="2"  >บันทึกการเข้าร่วมของครูที่ปรึกษา</font></td>
    <td><font color="#009900" size="2"  ><strong>เรียบร้อย</strong></font></td>
  </tr>
  <tr bgcolor="white">
    <td colspan="2" align="center">
    	<br/>
		<form action="index.php?option=module_800/dateTaskList" method="post">
			<input type="hidden" name="date" value="<?=$_POST['date']?>"/>
			<input type="submit" value="ดำเนินการต่อไป" />
		</form>
	</td>
    <br/>
  </tr>
</table>
</div>
<?php

//-------------------------------
function timecheck_id($value)
{
	if($value == 'white') return '00';
	if($value == 'lightgreen') return '01';
	if($value == 'yellow') return '02';
	if($value == 'blue') return '03';
	if($value == 'red') return '04';
	else return 9;
}

function disDetail($value)
{
	if($value == 'yellow') return 'สายการเข้าร่วมกิจกรรมหน้าเสาธง';
	if($value == 'blue') return 'ลาการเข้าร่วมกิจกรรมหน้าเสาธง';
	if($value == 'red') return 'ขาดการเข้าร่วมกิจกรรมหน้าเสาธง';
	else return "ผิดพลาด";
}

function updateTask($_connection,$date,$room_id)
{
	
	$sql = "update student_800_task set task_status = '1' where task_date = '" . $date . "' and task_roomid = '" . $room_id . "'" ;
	//echo $sql . "<br/>";
	mysqli_query($_connection,$sql) or die ('Error - ' . mysqli_error($_connection));

}

function SendLineMessage($message,$token)
{
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);
	//date_default_timezone_set("Asia/Bangkok");

	$sToken = "";
	$sToken = $token;
	$sMessage = "";
	$sMessage = $message;

	
	$chOne = curl_init(); 
	curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt( $chOne, CURLOPT_POST, 1); 
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
	$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
	curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
	$result = curl_exec( $chOne ); 
	
	$_SESSION['line-success'] = "";
	$_SESSION['line-error'] = "";

	if($result){
		$_SESSION['line-success'] = 'ส่งข้อความแจ้งในไลน์เรียบร้อยแล้ว';
	}else{
		$_SESSION['line-error'] = 'ระบบไม่สามารถส่งข้อความแจ้งทางไลน์ได้';
	}
	curl_close( $chOne );   
}

function reportHeader($date)
{
	$txt = "ประจำวันที่ " ;

	$_x = explode('-',$date,3);
	switch ($_x[1]) {
		case "01" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน มกราคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "02" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน กุมภาพันธ์  พ.ศ. " . ($_x[0] + 543) ;break;
		case "03" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน มีนาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "04" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน เมษายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "05" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน พฤษภาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "06" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน มิถุุนายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "07" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน กรกฎาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "08" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน สิงหาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "09" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน กันยายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "10" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน ตุลาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		case "11" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน พฤศจิกายน  พ.ศ. " . ($_x[0] + 543) ;break;
		case "12" : $txt = $txt . number_format($_x[2],0,'.','') . " เดือน ธันวาคม  พ.ศ. " . ($_x[0] + 543) ;break;
		default : $txt = $txt . "ผิดพลาด";
	}
	
	return $txt  ;
}

?>