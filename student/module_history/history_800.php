﻿

<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_history/index"><img src="../images/users.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">สืบค้นประวัติ</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.2 สืบค้นประวัติการเข้าร่วมกิจกรรมหน้าเสาธง</strong></font></span></td>
      <td >
	  	<?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_history/history_800&studentID=" . $_SESSION['username'] . "&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_history/history_800&studentID=" . $_SESSION['username'] . "&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_history/history_800&studentID=" . $_SESSION['username'] . "&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					} if($acadsemester == 2) {
						echo "<font color='blue'>2</font>";
					} else {
						echo " <a href=\"index.php?option=module_history/history_800&studentID=" . $_SESSION['username'] . "&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
		</td>
    </tr>
  </table>
  </form>
  <?
		$sql = "select a.student_id,a.check_date,b.check_description,timecheck_id
				from student_800 AS a right join ref_timecheck as b
				on a.timecheck_id = b.check_id
				where a.student_id =  '" . $_SESSION['username'] . "' and acadyear = '" . $acadyear . "' 
					and acadsemester = '" . $acadsemester . "' order by a.check_date";
		$_res800 = mysqli_query($_connection,$sql);

		$sqlStudent = "select id,prefix,firstname,lastname,xlevel,xyearth,room from students where id = '" . $_SESSION['username'] . "' order by xedbe desc limit 0,1";
		$resStudent = mysqli_query($_connection,$sqlStudent);
		$datStudent = mysqli_fetch_assoc($resStudent);	
  ?>
  <? if(mysqli_num_rows($resStudent)>0) { ?>
  <table class="admintable" >
    <tr> 
      <td  class="key" colspan="4">รายละเอียดการเข้าร่วมกิจกรรมหน้าเสาธง ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?></td>
    </tr>
	<tr>
		<td align="center" valign="top" >
			<? if(mysqli_num_rows($_res800) > 0) { ?>
			<table>
				<tr bgcolor="#BEDED0">
					<th align="center" width="60px">ลำดับที่</th>
					<th align="center" width="165px">วัน เดือน ปี</th>
					<th align="center" width="80px">สถานะ</th>
				</tr>
				<? $i = 1; ?>
				<? while($dat = mysqli_fetch_assoc($_res800)) { ?>
				<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF">
					<td align="center"><?=$i++?></td>
					<td align="center"><?=displayDate($dat['check_date'])?></td>
					<td ><?=displayTimecheckColor($dat['timecheck_id'])?></td>
				</tr>
				<? } //end while ?>
			</table>
			<? } else { //end if ?>
				<font color="#FF0000">ไม่มีรายละเอียดการบันทึกในภาคเรียนและปีการศึกษานี้ </font>
			<? } //end else ?>
		</td>
		
		<td valign="top">
			<? $_sql = "select acadyear,acadsemester,
					  sum(if(timecheck_id = '00',timecheck_id,null)+1) as a,
					  sum(if(timecheck_id = '01',timecheck_id,null)) as b,
					  sum(if(timecheck_id = '02',timecheck_id,null))/2 as c,
					  sum(if(timecheck_id = '03',timecheck_id,null))/3 as d,
					  sum(if(timecheck_id = '04',timecheck_id,null))/4 as e ,
					  count(class_id) as sum
					from  student_800 where student_id = '" . $_SESSION['username'] . "' group by acadsemester,acadyear,student_id order by acadyear,acadsemester ";
				$_result = mysqli_query($_connection,$_sql); ?>
			<table width="100%" cellspacing="1" cellpadding="1" bgcolor="#FFEEDD">
				<tr bgcolor="#FFFFFF">
					<td colspan="5">เลขประจำตัว : <?=displayText($datStudent['id'])?><br/>
									ชื่อ - สกุล : <?=displayText($datStudent['prefix'] . $datStudent['firstname'] . ' '. $datStudent['lastname'])?><br/>
									-------------------------------------
									<br/><b>ประวัติการเข้าร่วมกิจกรรมหน้าเสาธง</b>
					</td>
					<td align='center' valign='top' colspan='2'><img src='../images/studphoto/id<?=$datStudent['id']?>.jpg' width='120px' height='160px' border='1' alt='รูปนักเรียน'/></td>
				</tr>
				<tr >
					<td align="center">ภาคเรียน/ปีการศึกษา</td>
					<td width="40px" align="center">มา</td>
					<td width="40px" align="center">กิจกรรม</td>
					<td width="40px" align="center">สาย</td>
					<td width="40px" align="center">ลา</td>
					<td width="40px" align="center">ขาด</td>
					<td align="center" >รวม</td>
				</tr>
				<? $_xmlPie = "<?xml version='1.0' encoding='UTF-8' ?>"; ?>
				<? $_xmlPie .="<graph caption='' decimalPrecision='0' showPercentageValues='1' showNames='1'  showValues='1'  pieYScale='45' pieSliceDepth='25' pieRadius='100'>"; ?>
				<? $_ix = 0;?>
				<? while($_dat = mysqli_fetch_assoc($_result)) { ?>
				<tr bgcolor="#FFFFFF">
					<td align="center"><?=$_dat['acadsemester'] . '/' . $_dat['acadyear']?></td>
					<td align="right"><?=displayText($_dat['a'])?></td>
					<td align="right"><?=displayText($_dat['b'])?></td>
					<td align="right"><?=displayText($_dat['c'])?></td>
					<td align="right"><?=displayText($_dat['d'])?></td>
					<td align="right"><?=displayText($_dat['e'])?></td>
					<td align="right"><?=displayText($_dat['sum'])?></td>
				</tr>
				<? $_text[$_ix] = $_dat['acadsemester'] . '/' . $_dat['acadyear'];
				   $_pie[$_ix] = $_xmlPie;
				   if($_dat['a'] != '') { $_pie[$_ix] .="<set value='" . ($_dat['a']==''?0:$_dat['a']) . "' name='มา' color='FF99FF'/>"; }
				   if($_dat['b'] != '') { $_pie[$_ix] .="<set value='" . ($_dat['b']==''?0:$_dat['b']) . "' name='กิจกรรม' color='00FF00'/>";}
				   if($_dat['c'] != '') { $_pie[$_ix] .="<set value='" . ($_dat['c']==''?0:$_dat['c']) . "' name='สาย' color='FFFF00'/>";}
				   if($_dat['d'] != '') { $_pie[$_ix] .="<set value='" . ($_dat['d']==''?0:$_dat['d']) . "' name='ลา' color='0000FF'/>";}
				   if($_dat['e'] != '') { $_pie[$_ix] .="<set value='" . ($_dat['e']==''?0:$_dat['e']) . "' name='ขาด' color='FF0000'/>";}
				   $_pie[$_ix] .= "</graph>";
				   $_ix++;
				    ?>
				<? }//end while ?>
				<? for($_y = 0 ;$_y < $_ix ; $_y++) { ?>
					<tr bgcolor="#FFFFFF">
						<td colspan="7" align="center">
							<div id="<?="chart".$_y?>" align="center" ></div>
							<b>ภาคเรียนที่ <?=$_text[$_y]?></b>
						</td>
					</tr>
				<? } //end for ?>
				
				<? for($_x = 0 ; $_x < $_ix ; $_x++) { ?>
					<script language="javascript" type="text/javascript">						
						FusionCharts.setCurrentRenderer('JavaScript');
						var myPie<?=$_x?> = new FusionCharts("../fusionII/charts/Pie3D.swf", "myPie<?=$_x?>", "360", "200"); 
						myPie<?=$_x?>.setDataXML("<?=$_pie[$_x]?>");
						myPie<?=$_x?>.render("chart<?=$_x?>");
						myPie<?=$_x?>.addEventListener( "nodatatodisplay", function() { 
									if ( window.windowIsReady ){
										notifyLocalAJAXSecurityRestriction(); 
									}else
									{
										$(document).ready (function(){
											notifyLocalAJAXSecurityRestriction();
										});
									}
								});
					</script>
				<? } //end for ?>
			</table>
		</td>
	</tr>
  </table>
  <? } else { echo "<br/><center><font color='red'>ไม่พบข้อมูลนักเรียน กรุณาตรวจสอบเลขประจำตัวนักเรียนอีกครั้ง</font></center>";} ?>

</div>


