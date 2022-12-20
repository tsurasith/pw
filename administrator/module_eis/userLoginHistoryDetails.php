

<?php

/*

select
   trim(user_logon_by) as 'logon_as',
   u.user_ip,
   u.user_logon_result,
   convert_tz(u.user_logon_datetime,'+00:00','+07:00'),
   left(user_browser,locate(" ",user_browser)) as 'browser',
   left(right(user_browser,length(user_browser)-locate(" ",user_browser)),locate(")",right(user_browser,length(user_browser)-locate(" ",user_browser)))) as 'os' ,
   right(user_browser,length(user_browser)-locate(" ",user_browser)) as 'engine'
from users_logon_history u
where 
	user_account_id = 'D6806031-6121-4BA3-913E-DF80EDD06932' and
    year(convert_tz(u.user_logon_datetime,'+00:00','+07:00')) = '2022' and
    month(convert_tz(u.user_logon_datetime,'+00:00','+07:00')) = '12'
order by user_logon_datetime desc
;



*/

?>


<div id="content">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>3.1 ประวัติการ Login เข้าใช้งาน (รายละเอียดเพิ่มเติม)</strong></font></span></td>
    <td>
		<form method="post" action="index.php?option=module_eis/userLoginCount">
			<input type="hidden" name="period" value="<?=$_POST['year'].'-'.$_POST['month']?>" />
			<input type="hidden" name="order_by" value="<?=$_POST['order_by']?>" />
			<input type="submit" name="search" value="ย้อนกลับ" />
		</form>
	   </td>
    </tr>
  </table>
  
<? if(isset($_POST['details']) && $_POST['year'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือก เดือนที่ต้องการทราบข้อมูล !</font></center>
<? } else if (isset($_POST['details']) && $_POST['year'] != "") { ?>
<div align="center">
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="21" align="center">
	  		<!--<img src="../images/school_logo.png" width="120px"><br/> -->
			ประวัติการเข้าใช้งานระบบ<br/>
			ของผู้ใช้งาน: <?=getUserAccountName($_connection,$_POST['user_id'])?> <br/>
			เดือน<?=displayMonth($_POST['month'])?> <?=(int) $_POST['year']+543?>
			<br/>
	  </th>
    </tr>
    <tr height="35px"> 
      	<td class="key" width="30px" align="center">ที่</td>
		<td class="key" width="80px" align="center">ชื่อเข้าใช้งาน</td>
		<td class="key" width="50px" align="center">วัน</td>
		<td class="key" width="140px" align="center">เวลา</td>
		<td class="key" width="80px" align="center">Device</td>
		<td class="key" width="280px" align="center">Operting System & Agent</td>
		<td class="key" width="80px" align="center">IP address</td>
    </tr>
	<?php

		$_sql_logon_history = "
		select
			trim(user_logon_by) as 'logon_as',
			u.user_ip,
			u.user_logon_result,
			convert_tz(u.user_logon_datetime,'+00:00','+07:00') as 'user_logon_datetime',
			left(user_browser,locate(' ',user_browser)) as 'browser',
			left(right(user_browser,length(user_browser)-locate(' ',user_browser)),locate(')',right(user_browser,length(user_browser)-locate(' ',user_browser)))) as 'os' ,
			right(user_browser,length(user_browser)-locate(' ',user_browser)) as 'engine'
		from users_logon_history u
		where 
			user_account_id = '" . $_POST['user_id'] . "' and
			year(convert_tz(u.user_logon_datetime,'+00:00','+07:00')) = '" . $_POST['year'] . "' and
			month(convert_tz(u.user_logon_datetime,'+00:00','+07:00')) = '". $_POST['month'] . "'
		order by user_logon_datetime desc	 
		"; 
		
		$_resTable = mysqli_query($_connection,$_sql_logon_history);
		$_i = 1;
	?>
	<? while($_dat = mysqli_fetch_assoc($_resTable)) { ?>
	<tr>
		<td valign="top" align="center"><?=$_i++?></td>
		<td valign="top" align="center"><?=$_dat['logon_as']?></td>
		<td valign="top" align="left"><?=displayDayofWeek(date('w',strtotime($_dat['user_logon_datetime'])))?></td>
		<td valign="top" align="center"><?=$_dat['user_logon_datetime']?></td>
		<td valign="top" align="center">
			<?php
				if(strpos($_dat['os'],'Windows NT') || strpos($_dat['os'],'Macintosh') || strpos($_dat['os'],'X11;')){
					echo "Desktop";
				}else if(strpos($_dat['os'],'Linux; A') || strpos($_dat['os'],'Linux; U') || strpos($_dat['os'],'iPhone')){
					echo "Mobile";
				}else if(strpos($_dat['os'],'iPad')){
					echo "Tablet";
				}else{
					echo "Unknown";
				}
			?>
		</td>
		<td valign="top" align="left"><?=$_dat['os']?></td>
		<td valign="top" align="left">
			<?php
				if(strlen($_dat['user_ip'])>16){
					echo substr($_dat['user_ip'],0,16);
				}else{
					echo $_dat['user_ip'];
				}
			?>
		</td>
	</tr>
	<? } ?>
</table>
</div>
  <? } //end else-if ?>

</div>
