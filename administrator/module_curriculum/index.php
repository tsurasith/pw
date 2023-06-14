<div id="content">
	<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
		<tr> 
			<td width="6%" align="center"><img src="../images/graduation.png" alt="" width="48" height="48" /></td>
			<td width="64%"><strong><font color="#990000" size="4">การจัดการหลักสูตรและการสอน</font></strong><br />
				<span class="normal"><font color="#0066FF"><strong>การจัดการหลักสูตรและการสอน การลงทะเบียนเรียน (Curriculum and Instructions, Enrollment)</strong></font></span></td>
			<td >&nbsp;</td>
		</tr>
  </table>
    <table width="100%" align="center" cellspacing="1" class="admintable">
	  <tr> 
		<td class="key" colspan="2">1. รายงานผล สารสนเทศ หลักสูตรและโครงสร้าง</td></tr>
		<tr>
            <td width="20px">&nbsp;</td>
            <td><a href="index.php?option=module_curriculum/ReportGroupBySubjectGroup">1.1 รายงานสรุปหน่วยการเรียน แยกตามกลุ่มสาระการเรียนรู้</a></td>
		</tr>
        <tr>
            <td width="20px">&nbsp;</td>
            <td><a href="index.php?option=module_curriculum/ReportEnrollmentSubjects">1.2 รายวิชาที่เปิดลงทะเบียนรายภาคเรียน</a></td>
		</tr>
		<tr>
            <td width="20px">&nbsp;</td>
            <td>
				&nbsp; &nbsp; &nbsp;
				<a href="index.php?option=module_curriculum/ReportEnrollmentSubjectGroups">1.2.1 รายวิชาที่เปิดลงทะเบียนรายภาคเรียน (กลุ่มสาระการเรียนรู้)</a>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ReportEnrollmentClubs">1.3 รายชื่อชุมนุม/กิจกรรมที่เปิดลงทะเบียน</a</td>
		</tr>
        <tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ReportTeachingSubjects">1.4 รายชื่อครูผู้สอนและภาระการสอน</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ReportStudentRegisteredIndividaul">1.5 ตรวจสอบการลงทะเบียนเรียนของนักเรียน</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ReportStudentRegisteredClub">1.6 รายชื่อนักเรียนตามชุมนุม/กิจกรรม</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ReportStudentGradeResult">1.7 ผลการเรียนของนักเรียน</a></td>
		</tr>
	   <tr>
		<td class="key" colspan="2">2. สืบค้นข้อมูล </td>
	   </tr>
		<tr><td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/SearchCurriculumSubject">2.1 สืบค้นรายวิชา</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ShowCurriculumSubjectMapping">2.2 แสดงรายวิชาตามโครงสร้างแผนการเรียน</a></td> 
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ShowCurriculumSubjectMappingWithCredit">2.3 แสดงรายวิชาตามโครงสร้างแผนการเรียนและสรุปหน่วยการเรียน</td> 
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ShowCurriculumSubjectMappingWithCredit2">2.4 แสดงรายวิชาตามโครงสร้างแผนการเรียนและสรุปหน่วยการเรียน (รูปแบบ 2)</td> 
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>2.5 สืบค้นข้อมูลการลงทะเบียนเรียน</td>
		</tr>
		<? if(
                        $_SESSION['username'] == "admin" || 
                        $_SESSION['username'] == "tc100" || 
                        $_SESSION['username'] == "tc101" || 
                        $_SESSION['username'] == "tc102" || 
                        $_SESSION['username'] == "tc103" ||
						$_SESSION['username'] == "tc114" ||
						$_SESSION['username'] == "tc110" ||
                        $_SESSION['username'] == "tc137" || $_SESSION['username'] == "tc111" ||
                        $_SESSION['username'] == "tc144" || $_SESSION['username'] == "tc113" ||
						$_SESSION['username'] == "tc154" || $_SESSION['username'] == "tc112"
                    ) { ?>
       <tr>
			<td class="key" colspan="2">3. การจัดการหลักสูตรรายวิชา และ โครงสร้างการหลักสูตร </td>
	   </tr>
	   <tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/CreateSubject">3.1 เพิ่ม รายวิชาที่เปิดสอน</a></td>
        </tr> 
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/ModifySubject">3.2 แก้ไข รายวิชาที่เปิดสอน</a></td>
        </tr> 
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/DeleteSubject">3.3 ลบ รายวิชาที่เปิดสอน</a></td>
        </tr>  
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/CurriculumMapping">3.4 จัดการรายวิชาที่บรรจุในแผนการเรียน</a></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/CreateCurriculum">3.5 เพิ่ม/แก้ไข แผนการเรียน</a></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/CreateClub">3.6 เพิ่ม/แก้ไข ชุมนุม</a></td>
        </tr>  
		<tr>
			<td class="key" colspan="2">4. การจัดการลงทะเบียนเรียน </td>
	   </tr>
	   <tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/CurriculumRegisterMapping">4.1 จัดการหลักสูตรสำหรับลงทะเบียนเรียน</a></td>
        </tr> 
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/CurriculumRegisterSubjectMapping">4.2 จัดการรายวิชาสำหรับลงทะเบียนเรียน</a></td>
        </tr> 
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/RegisterStudentByLevel">4.3 จัดการลงทะเบียนเรียนวิชาเรียนแบบคละห้อง</a></td>
        </tr>  
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/RegisterClubMapping">4.4 เตรียมชุมนุม/กิจกรรม สำหรับการลงทะเบียน</a></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
			<td><a href="index.php?option=module_curriculum/RegisterStudentClub">4.5 จัดการลงทะเบียนเรียนกิจกรรมพัฒนาผู้เรียนแบบคละห้อง</a></td>
        </tr>  
		<? } ?>
	</table>
				  
</div>