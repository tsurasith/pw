

<div id="content">
<form action="" method="post">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
  <tr>
    <td width="6%" align="center"><a href="index.php?option=module_eis/index"><img src="../images/eis2.png" alt="" width="48px" border="0" /></a></td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศสำหรับผู้บริหาร</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>3.1 ประวัติการ Login เข้าใช้งาน</strong></font></span></td>
    <td>

		<font  size="2" color="#000000">ช่วงเวลา
			<?php 
					$sql_period = " 
						select distinct
							year(user_logon_datetime) as 'y',
							month(user_logon_datetime) as 'm',
							concat(year(user_logon_datetime),'-',month(user_logon_datetime)) as 'period'
						from 
							users_logon_history
						order by 1 desc,2 desc" ;
					//echo $sql_Room ;
					$resPeriod = mysqli_query($_connection,$sql_period);		
			?>
			<select name="period" class="inputboxUpdate">
				<option value=""></option>
				<?php

					while($dat = mysqli_fetch_assoc($resPeriod))
					{
						$_select = (isset($_POST['period'])&&$_POST['period'] == $dat['period']?"selected":"");
						echo "<option value=\"" . $dat['period'] . "\" $_select>";
						echo ((int) $dat['y']+543). ' ' . displayMonth($dat['m']) ;
						echo "</option>";
					}
					
				?>
			</select>
			
	  		<input type="submit" value="สืบค้น" class="button" name="search"/> <br/>
			<input type="checkbox" value="3" name="order_by" <?=isset($_POST['order_by'])?"checked":""?> /> เรียงลำดับตามจำนวนการเข้าใช้งาน
			</font>
		<br/>
	   </td>
    </tr>
  </table>
  </form>
  
<? if(isset($_POST['search']) && $_POST['period'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือก เดือนที่ต้องการทราบข้อมูล !</font></center>
<? } else if (isset($_POST['search']) && $_POST['period'] != "") { ?>
<div align="center">
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="21" align="center">
	  		<!--<img src="../images/school_logo.png" width="120px"><br/> -->
			ประวัติการเข้าใช้งานระบบ<br/>
			ของผู้ใช้งาน เดือน<?=displayMonth(substr($_POST['period'],5,2))?> <?=(int)substr($_POST['period'],0,4)+543?>
			<br/>
	  </th>
    </tr>
    <tr height="35px"> 
      	<td class="key" width="40px" align="center">ที่</td>
		<td class="key" width="200px" align="center">ชื่อ-สกุล</td>
		<td class="key" width="70px" align="center">จำนวนครั้ง<br/>ที่ล็อกอิน</td>
		<td class="key" align="center">-</td>
    </tr>
	<?php

		$_period = explode("-",$_POST['period']);
		// $_period[0] = 2022;
		// $_period[1] = 12 -- December

		$_sqlTable = "
			select u.user_account_id,u.user_account_prefix,u.user_account_firstname,u.user_account_lastname,count(user_logon_result) as 'c'
			from 
				users_account u left join users_logon_history h
				on (u.user_account_id = h.user_account_id and h.user_logon_result = 'success' and 
					year(h.user_logon_datetime) = '" . $_period[0] . "' and 	
					month(h.user_logon_datetime) = '" . $_period[1] . "') 
			where
				u.user_account_logon != 'admin' 
			group by
				u.user_account_id,
				u.user_account_prefix,
				u.user_account_firstname,
				u.user_account_lastname ";

		if(isset($_POST['order_by'])){
			$_sqlTable .= " order by 5 desc";
		}
		else{
			$_sqlTable .= "	order by
			convert(u.user_account_firstname using tis620),
			convert(u.user_account_lastname using tis620)	";
		}
		
		$_resTable = mysqli_query($_connection,$_sqlTable);
		$_i = 1;
	?>
	<? while($_dat = mysqli_fetch_assoc($_resTable)) { ?>
	<tr>
		<td align="center"><?=$_i++?></td>
		<td >
				<?=$_dat['user_account_prefix'].$_dat['user_account_firstname'].' '.$_dat['user_account_lastname']?>
		</td>
		<td align="right"><?=$_dat['c']?> &nbsp; &nbsp;  </td>
		<td align="right">
			<? if($_dat['c']>0){ ?>
			<form method="post" action="index.php?option=module_eis/userLoginHistoryDetails">
				<input type="hidden" name="user_id" value="<?=$_dat['user_account_id']?>" />
				<input type="hidden" name="month" value="<?=$_period[1]?>" />
				<input type="hidden" name="year" value="<?=$_period[0]?>" />
				<input type="hidden" name="order_by" value="<?=$_POST['order_by']?>" />
				<input type="submit" name="details" value="เพิ่มเติม" />
			</form>
			<? } else { echo "";} ?>
		</td>
	</tr>
	<? } ?>
</table>
</div>
  <? } //end else-if ?>

</div>
