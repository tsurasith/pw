
<div id="content">
  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
       <td width="6%" align="center">
	  	<a href="index.php?option=module_projects/index">
			<img src="../images/computer.png" alt="" width="48" height="48" border="0"/>
		</a>
	  </td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศแผน งบประมาณ โครงการและกิจกรรม</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>1.รายชื่อกิจกรรม/โครงการทั้งหมด</strong></font></span></td>
      <td align="right">
	  	<?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		ปีงบประมาณ <?php  
					echo "<a href=\"index.php?option=module_projects/projectListAll&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					echo '<font color=\'blue\'>' .$acadyear . '</font>';
					echo " <a href=\"index.php?option=module_projects/projectListAll&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
	  </td>
    </tr>
  </table>
<? 
	$_totalRows; 
	$_disPlay = 50;
	$_currentPage = (isset($_REQUEST['page'])?$_REQUEST['page']:1);
	$_num = (($_currentPage - 1) * $_disPlay ) + 1;
 
	$_sql = "
				select 
					p.acadyear,
					p.approve_budget,
					p.project_name,
					p.project_number,
					p.start_date, p.finish_date,
					p.department_id, d.department_name,
					p.income_id , i.income_name ,
					concat(t.firstname, ' ', t.lastname) as project_owner_name
					
				from
					projects p inner join departments d 
					on (p.department_id = d.department_id) 
					inner join incomes i 
					on (p.income_id = i.income_id) left join teachers t 
					on (p.project_owner = t.teacher_id)
				where
					p.acadyear = '" .$acadyear . "'
				order by
					convert(p.project_name using tis620)
			";
	
	$_sqlAll = $_sql; // นับจำนวนแถวทั้งหมด
	$_sql .= " limit " . ($_num-1)  . "," . ($_disPlay);
	$_result = mysqli_query($_connection,$_sql);
	//echo $_sql ;
	@$_totalRows = mysqli_num_rows(mysqli_query($_connection,$_sqlAll));
?>
	<div align="center">
		<table align="center" cellspacing="1" class="admintable" border="0" cellpadding="3">
			<tr> 
				<th colspan="6" align="left">
						รายการแสดงข้อมูลกิจกรรมโครงการ ปีงบประมาณ <?=$acadyear?>
				</th>
			</tr>
			<tr height="30px">
				<td class="key" width="40px"  align="center" >ที่</td>
				<td class="key" width="100px" align="center" >รหัสโครงการ</td>
				<td class="key" width="300px" align="center" >ชื่อกิจกรรม/โครงการ</td>
				<td class="key" width="90px"  align="center" >งบประมาณ (บาท)</td>
				<td class="key" width="130px"  align="center" >งบประมาณ (ที่มา)</td>
				<td class="key" width="110px" align="center" >ฝ่าย</td>
				<td class="key" width="110px" align="center" >ผู้รับผิดชอบ</td>
			</tr>
		<? if(mysqli_num_rows($_result) > 0) { ?>
			<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
			<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF" >
				<td align="center" valign="top"><?=$_num++?></td>
				<td align="center" valign="top"><?=$_dat['project_number']?></td>
				<td valign="top">
					<?=$_dat['project_name']?>
				</td>
				<td align="right" valign="top" style="padding-right:10px"><?=number_format($_dat['approve_budget'],2,'.',',')?></td>
				<td align="left" valign="top"><?=$_dat['income_name']?></td>
				<td align="left" valign="top"><?=$_dat['department_name']?></td>
				<td align="left" valign="top"><?=$_dat['project_owner_name']?></td>
			<tr>
			<? } mysqli_free_result($_result); //end while ?>
			<tr>
				<td>&nbsp;</td><td>&nbsp;</td>
				<td colspan="4" align="center">
					<br/><br/>
					หน้าที่ <?=displayText($_currentPage)?> จากจำนวนทั้งหมด <?=displayText(ceil($_totalRows/$_disPlay))?> หน้า<br/>
					รายการที่ <?=displayText((($_currentPage - 1) * $_disPlay ) + 1)?>
					ถึง <?=displayText(($_currentPage * $_disPlay))?> 
					จากจำนวนทั้งหมด <?=displayText($_totalRows)?> รายการ <br/>
					<? if($_currentPage != 1){ ?>
						<a href="index.php?option=module_projects/projectListAll&page=<?=$_currentPage-1?>&acadyear=<?=$acadyear?>&acadsemester=<?=$acadsemester?>">&lt;&lt;หน้าก่อนหน้า</a>
					<? } //end if ?>
					
					<? if($_currentPage != (ceil($_totalRows/$_disPlay))) { ?>
						<a href="index.php?option=module_projects/projectListAll&page=<?=$_currentPage+1?>&acadyear=<?=$acadyear?>&acadsemester=<?=$acadsemester?>">หน้าถัดไป&gt;&gt;</a>
					<? } //end if ?>
				</td>
			</tr>
		<? }else { //end if check rows ?>
			
			<tr><td>&nbsp;</td><td colspan="5">ไม่พบข้อมูลที่ต้องการ</td></tr>
		<? } //end else check rows ?> 
		</table>
	</div>

</div>

