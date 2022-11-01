<html>
<head>
 <title>ผลการดำเนินงานการสร้างงานบันทึกข้อมูล</title>
</head>
<body>

<?php require_once('../../Connections/bn.php'); ?>
<?php
mysqli_select_db($database_nn, $nn);
mysqli_query($_connection,"SET Character set utf8");
mysqli_query($_connection,"set collation_connection = 'utf8'");
$sql = 'SELECT distinct room_id FROM rooms';
$sql2 = "select distinct task_date from student_color_task where task_date = '" . $_POST['date'] . "'" ;
?>
<table width="75%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#CC99FF">
  <tr bgcolor="#CCCCCC"> 
    <td colspan="2"><font size="2" face="Tahoma"><strong>ผลการดำเนินงาน</strong></font></td>
  </tr>
  <tr bgcolor="white"> 
    <td width="150px"><font size="2" face="Tahoma">ตรวจสอบรายชื่อห้อง</font></td>
    <td><font color="#009900" size="2" face="Tahoma"><strong>เรียบร้อย</strong></font></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td><font size="2" face="Tahoma">ตรวจสอบวันที่ทำการบันทึก</font></td>
    <?php

$c_date = mysqli_query($_connection,$sql2,$nn);
$rows = mysqli_num_rows($c_date);
if($_POST['date'] != "")
{
	if($rows == 0)
	{
		$result = mysqli_query($_connection,$sql,$nn);
		while ($data = mysqli_fetch_assoc($result))
		{
			$sql_insert = "insert into student_color_task values ( null,'". $_POST['date'] ."','" . $data['room_id'] . "','0') ";
			mysqli_query($_connection,$sql_insert) or die ('Error - ' . mysqli_error());
			//echo $sql_insert . "<br/>";
		}
		echo "<td><font color=\"#009900\" size=\"2\" face=\"Tahoma\"><strong>บันทึกข้อมูลเรียบร้อยแล้ว</strong></font></td>";
	}
	else
	{
		echo "<td><font color=\"red\" size=\"2\" face=\"Tahoma\"><strong>คุณไม่สามารถบันทึกข้อมูลวันที่ทำการเช็คการเข้าแถวซ้ำได้</strong></font></td>";
	}
}
else
{
	echo "<td><font color=\"red\" size=\"2\" face=\"Tahoma\"><strong>ผิดพลาดเนื่องจากคุณยังไม่ได้เลือกวันที่จะบันทึกข้อมูล !</strong></font></td>";
}
		mysqli_close($nn);
?>
  </tr>
</table>
<table align="center">
	<tr>
		<td><input type="button" value="ดำเินินการต่อไป" onClick="history.go(-1)"/></td>
	</tr>
</table>
</body>
</html>