﻿<div id="content">
  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_learn/index"><img src="../images/modules/classroom.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Room Tracking</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.4 รายงานการไม่ลงชื่อเข้าสอนของครู<br/>สรุปตามห้องเรียนตลอดปีการศึกษา</strong></font></span></td>
      <td >
	  	 ปีการศึกษา
        <?php  
			if(isset($_REQUEST['acadyear']))
			{
				$acadyear = $_REQUEST['acadyear'];
			}
			if(isset($_REQUEST['acadsemester']))
			{
				$acadsemester = $_REQUEST['acadsemester'];
			}
					echo "<a href=\"index.php?option=module_learn/reportUnsignSemesterRoom&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_learn/reportUnsignSemesterRoom&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
        ภาคเรียนที่ 
        <?php 
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_learn/reportUnsignSemesterRoom&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_learn/reportUnsignSemesterRoom&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>	<br/>
		 <font  size="2" color="#000000">
		 <form action="" method="post">
		 	เลือกห้องเรียน
			<? $sql_Room = "select room_id from rooms where acadyear = '". $acadyear . "' and acadsemester = '" . $acadsemester . "'  order by room_id";
			   $resRoom = mysqli_query($_connection,$sql_Room);	?>
			<select name="roomID" class="inputboxUpdate">
		  	<option> &nbsp; &nbsp; &nbsp; </option>
			<?php
				while($dat = mysqli_fetch_assoc($resRoom))
				{
					$_select = (isset($_POST['roomID'])&&$_POST['roomID'] == $dat['room_id']?"selected":"");
					echo "<option value=\"" . $dat['room_id'] . "\" $_select>";
					echo getFullRoomFormat($dat['room_id']);
					echo "</option>";
				}
				mysqli_free_result($resRoom);
			?>
			</select> <input type="submit" name="search" value="เรียกดู" class="button" />
		 </form>
		 </font>
	  </td>
    </tr>
  </table>

<? if(isset($_POST['search'])){ ?>
<?	$_sql = "select check_date,
			  sum(if(period = 1 and stutus = 'unsign',1,null)) as p1,
			  sum(if(period = 2 and stutus = 'unsign',1,null)) as p2,
			  sum(if(period = 3 and stutus = 'unsign',1,null)) as p3,
			  sum(if(period = 4 and stutus = 'unsign',1,null)) as p4,
			  sum(if(period = 5 and stutus = 'unsign',1,null)) as p5,
			  sum(if(period = 6 and stutus = 'unsign',1,null)) as p6,
			  sum(if(period = 7 and stutus = 'unsign',1,null)) as p7,
			  sum(if(period = 8 and stutus = 'unsign',1,null)) as p8
			from teachers_learn
			where acadyear = '" . $acadyear ."' and acadsemester = '" . $acadsemester ."' and room_id = '" . $_POST['roomID'] ."'
			group by check_date order by check_date";
	$_res = mysqli_query($_connection,$_sql); ?>
	<? if(@mysqli_num_rows($_res)>0) { ?>
    	<div style="width:100%;" align="center">
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
			<tr>
			<th colspan="10" align="center">
				<img src="../images/school_logo.png" width="120px">
				<br/>
				การไม่ลงชื่อเข้าสอนของครูผู้สอนจากแบบบันทึกการเข้าเรียนฝ่ายกิจการนักเรียน<br/>
				ชั้นมัธยมศึกษาปีที่ <?=getFullRoomFormat($_POST['roomID'])?> ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?>
			  </th>
			</tr>
			<tr> 
				<td class="key" align="center" rowspan="2" width="200px">วันที่</td>
				<td class="key" align="center" colspan="8">คาบเรียนที่</td>
				<td class="key" align="center" width="70px" rowspan="2">รวม</td>
			</tr>
			<tr align="center">
				<td class="key" width="25px">1</td>
				<td class="key" width="25px">2</td>
				<td class="key" width="25px">3</td>
				<td class="key" width="25px">4</td>
				<td class="key" width="25px">5</td>
				<td class="key" width="25px">6</td>
				<td class="key" width="25px">7</td>
				<td class="key" width="25px">8</td>
			</tr>
			<? $p1=0;$p2=0;$p3=0;$p4=0;$p5=0;$p6=0;$p7=0;$p8=0;?>
			<? while($_dat = mysqli_fetch_assoc($_res)){ ?>
			<tr>
				<td align="center"><?=displayFullDate($_dat['check_date'])?></td>
				<td align="center"><b><?=$_dat['p1']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p2']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p3']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p4']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p5']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p6']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p7']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=$_dat['p8']!=0?"<font color='red'>X</font>":"-"?></b></td>
				<td align="center"><b><?=($_dat['p1']+$_dat['p2']+$_dat['p3']+$_dat['p4']+$_dat['p5']+$_dat['p6']+$_dat['p7']+$_dat['p8'])==0?"-":($_dat['p1']+$_dat['p2']+$_dat['p3']+$_dat['p4']+$_dat['p5']+$_dat['p6']+$_dat['p7']+$_dat['p8'])?></b></td>
			</tr>
			<?	$p1+=$_dat['p1']; $p2+=$_dat['p2']; $p3+=$_dat['p3']; $p4+=$_dat['p4']; $p5+=$_dat['p5']; $p6+=$_dat['p6']; $p7+=$_dat['p7']; $p8+=$_dat['p8'];?>
			<? } mysqli_free_result($_res); //end while ?>
			<tr height="30px">
			  <td class="key" align="center"><font color="#990066"><b>รวม</b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p1==0?"-":$p1?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p2==0?"-":$p2?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p3==0?"-":$p3?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p4==0?"-":$p4?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p5==0?"-":$p5?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p6==0?"-":$p6?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p7==0?"-":$p7?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p8==0?"-":$p8?></b></font></td>
			  <td class="key" align="center"><font color="#990066"><b><?=$p1+$p2+$p3+$p4+$p5+$p6+$p7+$p8?></b></font></td>
			  </tr>
		 </table>
       </div>
<?	} else { //end chekc_rows	 ?>
		<table width="100%" align="center">
			<tr>
				<td align="center"><font color="#FF0000"><br/>ไม่พบข้อมูลที่ค้นตามเงื่อนไข กรุณาทดลองใหม่อีกครั้ง</font></td>
			</tr>
		</table>
<?	} //end else ?>
<? } //end if($_POST[''] ?>  

</div>

