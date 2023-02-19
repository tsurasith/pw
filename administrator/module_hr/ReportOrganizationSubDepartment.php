
<?php
	if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
	if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }

	if(isset($_POST['acadyear']))     { $acadyear = $_POST['acadyear'];}
	if(isset($_POST['acadsemester'])) { $acadsemester = $_POST['acadsemester'];}
?>



<div id="content">
	<div align="center">
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48" height="48" /></a></td>
			<td><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>2.3 แสดงโครงสร้างภาระงานและผู้รับผิดชอบ</strong></font></span></td>
			<td align="right">
				ปีการศึกษา
					<?php  
							echo "<a href=\"index.php?option=module_hr/ReportOrganizationSubDepartment&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
							echo ' <font color=\'blue\'>' .$acadyear . '</font>';
							echo " <a href=\"index.php?option=module_hr/ReportOrganizationSubDepartment&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
						?>
					ภาคเรียนที่
					<? if($acadsemester == 1) { echo "<font color='blue'>1</font> , "; }
							else {
								echo " <a href=\"index.php?option=module_hr/ReportOrganizationSubDepartment&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
							}
							if($acadsemester == 2) { echo "<font color='blue'>2</font>"; }
							else {
								echo " <a href=\"index.php?option=module_hr/ReportOrganizationSubDepartment&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
							}
					?>
			</td>
			</tr>
		</table>
	  


		<div align="center">
		<?php
			$_process = array();
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
						<img src="../images/school_logo.png" width="120px"><br/>
						ภาระงานโรงเรียนเพชรวิทยาคาร <br/><br/>
				</th>
				</tr>
				<tr height="35px"> 
					<? while($_dat = mysqli_fetch_assoc($_res_curr)){ ?> 
						<td class="key" width="220px" align="center"><?=$_dat['department_name']?></td>
						<? array_push($_process,$_dat['department_id']); ?>
					<? } ?>
				</tr>
				<tr>
					<? for($_i = 0; $_i<count($_process); $_i++){ ?>  
					<td valign="top">
						<?php
							$_num = 1;
							$_sql_sub = "
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
									s.staff_id != '446CF8EB-CCF3-4C5D-A4EF-2B8FBD001E16' and
								    m.department_id = '" . $_process[$_i] . "' and
									m.acadyear      = '" . $acadyear . "' and 
									m.acadsemester  = '" . $acadsemester . "' 
								order by 
									t.sort_order,
									convert(department_subtype_name using tis620),
									convert(s.firstname using tis620)
							";

							$_res = mysqli_query($_connection,$_sql_sub);

							$_init_dep_name = "";

							while($_dat = mysqli_fetch_assoc($_res)){
								if($_init_dep_name != $_dat['department_subtype_name']){
									echo "<br>";
									echo "<b>";
									echo $_dat['department_subtype_name'] . "<br/>";
									echo "</b>";
								}
								echo " - " . $_dat['prefix'].$_dat['firstname'].' ' .$_dat['lastname']. "<br/>";
								
								$_init_dep_name = $_dat['department_subtype_name'];
							}
						?>
					</td>
					<? } ?>
				</tr>
			</table>
		<? } ?>
	</div>
</div>

