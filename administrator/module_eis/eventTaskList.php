<div id="content">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>2.2 เพิ่ม/แก้ไข ส่วนงานย่อยของโมดูลการทำงาน</strong></font></span></td>
    <td>&nbsp;</td>
  </tr>
</table>

  <?php
  	 
	  $_sql = "select t.* , m.event_module_name , u.user_account_prefix,u.user_account_firstname,u.user_account_lastname
	  			from 
	  				event_task t 
					left join event_module m on (t.event_module_code = m.event_module_code)
					left join users_account u on (t.event_task_owner = u.user_account_id)
					order by convert(m.event_module_name using tis620), convert(t.event_task_name using tis620)  ";
      $_resModule = mysqli_query($_connection,$_sql);
      $_ordinal = 1;
  ?>
    <div align="center">
	<table class="admintable" align="center">
		<tr>
			<th colspan="9" align="center">
				<img src="../images/school_logo.png" width="120px"><br/>
				การแบ่งโมดูลการทำงาน
			</th>
		</tr>
		<tr>
			<td class="key" width="25px" align="center">ที่</td>
			<td class="key" width="150px" align="center">โมดูล/ส่วนงานหลัก</td>
			<td class="key" width="180px" align="center">โมดูลงานรอง</td>
			<td class="key" width="240px" align="center">คำอธิบายโมดูลงานรอง</td>
			<td class="key" width="50px" align="center">น้ำหนักคะแนน</td>
			<td class="key" width="50px" align="center">ค่าเฉลี่ยเวลา</td>
			<td class="key" width="170px" align="center">ผู้รับผิดชอบหลัก</td>
            <td class="key" align="center">แก้ไขล่าสุด</td>
			<td class="key" width="80px" align="center">-</td>

		</tr>
		<? while($_dat = mysqli_fetch_assoc($_resModule)) { ?>
			<tr>
				<td align="center" valign="top"><?=$_ordinal++?></td>
				<td valign="top"><?=$_dat['event_module_name']?></td>
				<td valign="top"><?=$_dat['event_task_name']?></td>
				<td valign="top"><?=$_dat['event_task_description']?></td>
				<td valign="top" align="center"><?=$_dat['event_task_point_count']?></td>
				<td valign="top" align="center"><?=$_dat['average_time_count']?></td>
                <td valign="top"><?=$_dat['user_account_prefix'].$_dat['user_account_firstname'].' '.$_dat['user_account_lastname']?></td>
				<td valign="top"><?=$_dat['updated_datetime']?></td>
                <? if($_SESSION['superAdmin']) { ?>
				<td valign="top" align="center"><a href='index.php?option=module_eis/editEventTask&eventTaskCode=<?=$_dat['event_task_code']?>'>แก้ไข</a></td><? }
				else { echo "<td align='center'>" . $_dat['event_task_code'] . "</td>"; } ?>
			</tr>
		<? } //end while ?>
		<tr>
			<td colspan="8">&nbsp;</td>
			<td align="center"><b><a href='index.php?option=module_eis/addEventTask'>เพิ่มโมดูล</a></b></td>

				</tr>
</table>
                    </div>
</div>

