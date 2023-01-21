

<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>3.4 เพิ่ม/แก้ไข ฝ่ายงาน</strong></font></span></td>
			<td >&nbsp;</td>
			</tr>
		</table>
	  


		<div align="center">
		<?php
			$_sql_cur = "
					select
						*
						
					from
						departments
					order by
						department_id		
				";
			$_res_curr = mysqli_query($_connection,$_sql_cur);
			$_rows = mysqli_num_rows($_res_curr);
			$_no = 1;
		?>
		<? if($_rows>0){  ?>
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
				<tr> 
				<th colspan="10" align="center">
						<!-- <img src="../images/school_logo.png" width="120px"><br/> -->
						<br/>
					การบริหารงานฝ่ายโรงเรียนเพชรวิทยาคาร <br/><br/>
				</th>
				</tr>
				<tr height="35px"> 
					<td class="key" width="25px" align="center">ที่</td>
					<td class="key" width="180px" align="center">ฝ่าย</td>
					<td class="key" width="180px" align="center">แก้ไขล่าสุด</td>
					<td class="key" align="center">-</td>
				</tr>
				<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
					<tr>
						<td align="center"><?=$_no++?></td>
						<td align="left" style="padding-left:25px"><?=$_dat['department_name']?></td>
						<td align="center"><?=$_dat['updated_datetime']?></td>
						<td align="center">
							แก้ไข
						</td>
					</tr>
				<? } ?>
			</table>
		<? } ?>
	</div>
</div>

