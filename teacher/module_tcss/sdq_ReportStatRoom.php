﻿

<div id="content">
<form action="" method="post">
<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center" valign="top"><a href="index.php?option=module_tcss/index&content=sdq"><img src="../images/tcss.png" alt="" width="48" height="48" border="0"/></a></td>
      <td valign="top"><strong><font color="#990000" size="4">TCSS</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>3.3 รายงานสถิติการคัดกรองรายห้อง</strong></font></span></td>
      <td valign="top">
	  <?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีการศึกษา<?php  
					echo "<a href=\"index.php?option=module_tcss/sdq_ReportStatRoom&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_tcss/sdq_ReportStatRoom&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		ภาคเรียนที่   <?php 
					if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
					else {
						echo " <a href=\"index.php?option=module_tcss/sdq_ReportStatRoom&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
					else {
						echo " <a href=\"index.php?option=module_tcss/sdq_ReportStatRoom&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
				?>
		</font>
		<br/>
		<font color="#000000" size="2">
			ชุดประเมิน
			<select name="questioner" class="inputboxUpdate">
				<option value=""></option>
				<option value="student" <?=isset($_POST['questioner'])&&$_POST['questioner']=="student"?"selected":""?>>นักเรียนประเมินตนเอง</option>
				<option value="parent" <?=isset($_POST['questioner'])&&$_POST['questioner']=="parent"?"selected":""?>>ผู้ปกครองประเมิน</option>
				<option value="teacher" <?=isset($_POST['questioner'])&&$_POST['questioner']=="teacher"?"selected":""?>>ครูที่ปรึกษาประเมิน</option>
			</select><br/>
			แปลผลด้าน  
			<select name="type" class="inputboxUpdate">
		  		<option value=""></option>
				<option value="type1" <?=isset($_POST['type'])&&$_POST['type']=="type1"?"selected":""?>>อารมณ์</option>
				<option value="type2" <?=isset($_POST['type'])&&$_POST['type']=="type2"?"selected":""?>>ความประพฤติ/เกเร</option>
				<option value="type3" <?=isset($_POST['type'])&&$_POST['type']=="type3"?"selected":""?>>อยู่ไม่นิ่ง/สมาธิสั้น</option>
				<option value="type4" <?=isset($_POST['type'])&&$_POST['type']=="type4"?"selected":""?>>ความสัมพันธ์กับเพื่อน</option>
				<option value="type5" <?=isset($_POST['type'])&&$_POST['type']=="type5"?"selected":""?>>สัมพันธภาพทางสังคม</option>
				<option value="all" <?=isset($_POST['type'])&&$_POST['type']=="all"?"selected":""?>>รวมทุกด้าน</option>
			</select><br/>
	  		เลือกห้อง 
			<?php 
					$sql_Room = "select room_id from rooms where acadyear = '". $acadyear . "' and acadsemester = '" . $acadsemester . "'  order by room_id";
					$resRoom = mysqli_query($_connection,$sql_Room);			
			?>
			<select name="roomID" class="inputboxUpdate">
				<option value=""></option>
				<? while($dat = mysqli_fetch_assoc($resRoom)) {
						$_select = (isset($_POST['roomID'])&&$_POST['roomID'] == $dat['room_id']?"selected":"");
						echo "<option value=\"" . $dat['room_id'] . "\" $_select>";
						echo getFullRoomFormat($dat['room_id']);
						echo "</option>";
					}
				?>
			</select>
	  		<input type="submit" value="เรียกดู" class="button" name="search"/><br/>
			<input type="checkbox" name="studstatus" value="1,2"  <?=isset($_POST['studstatus'])=="1,2"?"checked='checked'":""?> />
			 เฉพาะนักเรียนสถานะปกติหรือสำเร็จการศึกษา </font>
	   </td>
    </tr>
  </table>
  </form>

<? if(isset($_POST['search']) && ($_POST['questioner'] =="" || $_POST['type'] == "" || $_POST['roomID'] == "")) { ?>
	<? echo "<br/><br/><center><font color='red'>กรุณาเลือก ห้องเรียน ด้านประเมิน และ ชุดประเมิน ที่ต้องการทราบข้อมูลก่อน</font></center>";} ?>
<?  
  $xlevel = getXlevel($_POST['roomID']);
  $xyearth= getXyearth($_POST['roomID']);
  $room = getRoom($_POST['roomID']);
?>

<? if(isset($_POST['search']) && $_POST['questioner'] != "" && $_POST['type'] != "" && $_POST['roomID'] != ""){ ?>
	<? $_sql = makeSQL($_POST['questioner'],$_POST['type']); ?>
	
	<? $_sql .= " where xedbe = '" . $acadyear . "' and acadyear = '" . $acadyear . "' and acadsemester = '" . $acadsemester . "' and questioner = '" . $_POST['questioner'] . "' "; ?>
	<? $_sql .= " and xlevel = '" . $xlevel . "' and xyearth = '" . $xyearth . "' and room = '" . $room . "' "; ?>
	<? $_sql .= ($_POST['studstatus']=="1,2"?" and studstatus in (1,2) ":"") ; ?>
	<? $_sql .=	" group by sex order by sex"; ?>
	<? //echo $_sql; ?>
	<? $_result = mysqli_query($_connection,$_sql); ?>
	<? if(mysqli_num_rows($_result)>0){ ?>
	<? $_xmlColumn = "<?xml version='1.0' encoding='UTF-8' ?>";
		$_xmlPie = "<?xml version='1.0' encoding='UTF-8' ?>";
		$_xmlColumn .= "<graph caption='' xAxisName='' yAxisName='Person' formatNumberScale='0' decimalPrecision='0'>";
		$_xmlPie .="<graph caption='' decimalPrecision='0' showPercentageValues='1' showNames='1'  showValues='1'  pieYScale='50' pieSliceDepth='25' pieRadius='100'>";
		
		$_catXML = "<categories>";
		$_setA = "<dataset seriesname='ปกติ'  showValue='1' color = '#009900' >";
		$_setB = "<dataset seriesname='เสี่ยง' showValue='1'  color = '#FFCC33'>";
		$_setC = "<dataset seriesname='มีปัญหา' showValue='1' color = '#FF0000'>"; $_t = 0; ?>
		
		<table class="admintable" width="100%" align="center" >
			<tr>
				<th colspan="2" align="center">
					<img src="../images/school_logo.png" width="120px">
					<br/>
					สารสนเทศผลการประเมิน SDQ นักเรียนชั้นมัธยมศึกษาปีที่ <?=getFullRoomFormat($_POST['roomID'])?> <br/>
					โดย : <?=$_POST['questioner']=="parent"?"ผู้ปกครอง":($_POST['questioner']=="teacher"?"ครูที่ปรึกษา":"นักเรียน")?>
					เป็นผู้ประเมิน  ด้าน : <?=displayType($_POST['type'])?><br/>
					ภาคเรียนที่ <?=$acadsemester?> ปีการศึกษา <?=$acadyear?>
					<br/>
				</th>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<table align="center" >
						<tr>
							<td class="key" rowspan="2" align="center" width="100px">เพศ</td>
							<td class="key" colspan="3" align="center">ผลการประเมิน</td>
							<td class="key" rowspan="2" align="center" width="70px">ยังไม่<br/>ประเมิน</td>
							<td class="key" rowspan="2" align="center" width="80px">รวม</td>
						</tr>
						<tr>
							<td class="key" align="center" width="60px">ปกติ</td>
							<td class="key" align="center" width="60px">เสี่ยง</td>
							<td class="key" align="center" width="60px">มีปัญหา</td>
						</tr>
						<? $_na=0; $_n4=0; $_r4=0; $_d4=0; ?>
							<? while($_dat = mysqli_fetch_assoc($_result)) { ?>
								<? $_catXML .= "<category name='" . ($_dat['sex']=="1"?"ชาย":"หญิง") . "' hoverText=''/>"; ?>
								<? $_setA .= "<set value='" . ($_dat['normal3']+$_dat['normal4']) . "' />"; ?>
								<? $_setB .= "<set value='" . ($_dat['risk3']+$_dat['risk4']) . "'  />"; ?>
								<? $_setC .= "<set value='" . ($_dat['damage3']+$_dat['damage4']) . "' />"; ?>
								<? $_t += $_dat['total']; ?>
								<tr>
									<td align="left" style="padding-left:15px;"><?=$_dat['sex']=="1"?"ชาย":"หญิง"?></td>
									<td align="right" style="padding-right:15px;"><?=$_dat['normal4']==""?"-":number_format($_dat['normal4'],0,'',',')?></td>
									<td align="right" style="padding-right:15px;"><?=$_dat['risk4']==""?"-":number_format($_dat['risk4'],0,'',',')?></td>
									<td align="right" style="padding-right:15px;"><?=$_dat['damage4']==""?"-":number_format($_dat['damage4'],0,'',',')?></td>
									<td align="right" style="padding-right:15px;"><?=$_dat['na']==""?"-":number_format($_dat['na'],0,'',',')?></td>
									<td align="right" style="padding-right:15px;"><?=$_dat['total']==""?"-":number_format($_dat['total'],0,'',',')?></td>
								</tr>
								<? $_n4+=$_dat['normal4']; $_r4+=$_dat['risk4']; $_d4+=$_dat['damage4']; $_na+=$_dat['na']; ?>
							<? } ?>
						<tr>
							<td class="key" align="center">รวม</td>
							<td class="key" align="right" style="padding-right:15px;"><?=number_format($_n4,0,'',',')?></td>
							<td class="key" align="right" style="padding-right:15px;"><?=number_format($_r4,0,'',',')?></td>
							<td class="key" align="right" style="padding-right:15px;"><?=number_format($_d4,0,'',',')?></td>
							<td class="key" align="right" style="padding-right:15px;"><?=number_format($_na,0,'',',')?></td>
							<td class="key" align="right" style="padding-right:15px;"><?=number_format($_t,0,'',',')?></td>
						</tr>
					</table>
				</td>
			</tr>
		<?	$_catXML .= "</categories>";
			$_setA .= "</dataset>";
			$_setB .= "</dataset>";
			$_setC .= "</dataset>";
			$_xmlColumn .= $_catXML . $_setA . $_setB . $_setC . "</graph>";
			if($_n4>0){ $_xmlPie .= "<set value='" . $_n4 . "' name='ปกติ' color='#009900'/>";}
			if($_r4>0){ $_xmlPie .= "<set value='" . $_r4 . "' name='เสี่ยง' color='#FFCC33'/>";}
			if($_d4>0){ $_xmlPie .= "<set value='" . $_d4 . "' name='มีปัญหา' color='#FF0000'/>";}
			if($_na>0){ $_xmlPie .= "<set value='" . $_na . "' name='ยังไม่ประเมิน' color='#ABCDEF'/>";}
			$_xmlPie .= "</graph>"; ?>
		
			<tr>
			  <td><div id="chart2" align="center" ></div></td>
		  </tr>
			<tr>
				<td><div id="chart1" align="center" ></div></td>
				
			</tr>
		</table>
		<script language="javascript" type="text/javascript">
			FusionCharts.setCurrentRenderer('JavaScript');
			var myColumn = new FusionCharts("../fusionII/charts/MSColumn3D.swf", "myColumn", "400", "300"); 
			myColumn.setDataXML("<?=$_xmlColumn?>"); 
			myColumn.render("chart1");
			myColumn.addEventListener( "nodatatodisplay", function() { 
									if ( window.windowIsReady ){
										notifyLocalAJAXSecurityRestriction(); 
									}else
									{
										$(document).ready (function(){
											notifyLocalAJAXSecurityRestriction();
										});
									}
								});
								
			var myPie = new FusionCharts("../fusionII/charts/Pie3D.swf", "myPie", "500", "250"); 
			myPie.setDataXML("<?=$_xmlPie?>");
			myPie.render("chart2");
			myPie.addEventListener( "nodatatodisplay", function() { 
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
	<? } else {echo "<br/><br/><center><font color='red'>ไม่พบข้อมูลในรายการที่ท่านเลือก</font></center>";} ?>
<? } //end if select data?>
</div>

<?
	
	function makeSQL($_questioner,$_type){
		$_para = 6;
		$_para2 = "";
		if($_questioner == "student" && $_type != "all"){
			if($_type == "type1") { $_para = 6; }
			if($_type == "type2") { $_para = 5; }
			if($_type == "type3") { $_para = 6; }
			if($_type == "type4") { $_para = 4; }
			if($_type == "type5") { 
				$_para = 3; 
				$_sql = "select sex,
						  sum(if($_type>=0 and $_type<=" . $_para . ",1,null)) as 'damage4',
						  sum(if($_type>" . $_para . ",1,null)) as 'normal4',
						  sum(if($_type<0,1,null)) as 'na',
						  count(*) as 'total'
						from students right outer join sdq_result
						on (id = student_id)";
				return $_sql;
			}
			$_sql = "select sex,
					  sum(if($_type>=0 and $_type<" . $_para . ",1,null)) as 'normal4',
					  sum(if($_type=" . $_para . ",1,null)) as 'risk4',
					  sum(if($_type>" . $_para . ",1,null)) as 'damage4',
					  sum(if($_type<0,1,null)) as 'na',
					  count(*) as 'total'
					from students right outer join sdq_result
					on (id = student_id)";
			return $_sql;
		}else if ($_type != "all"){
			if($_type == "type1") { $_para = 4; }
			if($_type == "type2") { $_para = 4; }
			if($_type == "type3") { $_para = 6; }
			if($_type == "type4") { $_para = 6; }
			if($_type == "type5") { 
				$_para = 3; 
				$_sql = "select sex,
						  sum(if($_type>=0 and $_type<" . $_para . ",1,null)) as 'damage4',
						  sum(if($_type=" . $_para . ",1,null)) as 'risk4',
						  sum(if($_type>" . $_para . ",1,null)) as 'normal4',
						  sum(if($_type<0,1,null)) as 'na',
						  count(*) as 'total'
						from students right outer join sdq_result
						on (id = student_id)";
				return $_sql;
			}
			$_sql = "select sex,
					  sum(if($_type>=0 and $_type<" . $_para . ",1,null)) as 'normal4',
					  sum(if($_type=" . $_para . ",1,null)) as 'risk4',
					  sum(if($_type>" . $_para . ",1,null)) as 'damage4',
					  sum(if($_type<0,1,null)) as 'na',
					  count(*) as 'total'
					from students right outer join sdq_result
					on (id = student_id)";
			return $_sql;
		}else if($_type=="all" && $_questioner == "student"){
			$_sql = "select sex,
						  sum(if(sdq_result.all >=0 and sdq_result.all < 17,1,null)) as 'normal4',
						  sum(if(sdq_result.all >=17 and sdq_result.all <=18,1,null)) as 'risk4',
						  sum(if(sdq_result.all > 18,1,null)) as 'damage4',
						  sum(if(sdq_result.all<0,1,null)) as 'na',
						  count(*) as 'total'
						from students right outer join sdq_result on (id = student_id) ";
			return $_sql;
		}else{
			$_sql = "select sex,
						  sum(if(sdq_result.all >=0 and sdq_result.all < 16,1,null)) as 'normal4',
						  sum(if(sdq_result.all >=16 and sdq_result.all <=17,1,null)) as 'risk4',
						  sum(if(sdq_result.all > 17,1,null)) as 'damage4',
						  sum(if(sdq_result.all<0,1,null)) as 'na',
						  count(*) as 'total'
						from students right outer join sdq_result on (id = student_id) ";
			return $_sql;
		}
		
	}
?>