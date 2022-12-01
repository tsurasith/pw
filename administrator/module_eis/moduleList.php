<div id="content">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>2.1 เพิ่ม/แก้ไข รายการโมดูลการทำงานหลัก</strong></font></span></td>
    <td>&nbsp;</td>
  </tr>
</table>

  <?php
  	 
	  $_sql = "select * from event_module order by convert(event_module_name using tis620) ";
      $_resModule = mysqli_query($_connection,$_sql);
      $_ordinal = 1;
  ?>
    <div align="center">
	<table class="admintable" align="center">
		<tr>
			<th colspan="5" align="center">
				<img src="../images/school_logo.png" width="120px"><br/>
				การแบ่งโมดูลการทำงาน
			</th>
		</tr>
		<tr>
			<td class="key" width="45px" align="center">ลำดับที่</td>
			<td class="key" width="45px" align="center">รหัส</td>
			<td class="key" width="160px" align="center">โมดูล/ส่วนงานหลัก</td>
			<td class="key" width="400px" align="center">คำอธิบายเพิ่มเติม</td>
            <td class="key" align="center">แก้ไขล่าสุด</td>
			<td class="key" width="80px" align="center">-</td>

		</tr>
		<? while($_dat = mysqli_fetch_assoc($_resModule)) { ?>
			<tr>
				<td align="center" valign="top"><?=$_ordinal++?></td>
				<td align="center" valign="top"><?=$_dat['event_module_code']?></td>
				<td valign="top"><?=$_dat['event_module_name']?></td>
				<td valign="top"><?=$_dat['event_module_description']?></td>
                <td valign="top"><?=$_dat['updated_datetime']?></td>
                <? if($_SESSION['superAdmin']) { ?>
				<td valign="top" align="center"><a href='index.php?option=module_eis/editModule&eventModuleCode=<?=$_dat['event_module_code']?>'>แก้ไข</a></td><? }
				else { echo "<td align='center'>" . $_dat['event_module_code'] . "</td>"; } ?>
			</tr>
		<? } //end while ?>
		<tr>
			<td colspan="4">&nbsp;</td>
			<td align="center"><b><a href='index.php?option=module_eis/addModule'>เพิ่มโมดูล</a></b></td>

				</tr>
</table>
                    </div>
</div>

