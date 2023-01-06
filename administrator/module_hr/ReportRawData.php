

<div id="content">
<form action="" method="post">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
		<tr>
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48px" border="0" /></a></td>
			<td ><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>2.1 รายงานผลการนำเข้าข้อมูล</strong></font></span></td>
			<td>

				<font  size="2" color="#000000">วันที่
					<?php 
							$sql_period = " 
								select distinct
									trim(check_date) as check_date
								from 
									hr_fingerprint_tmp
								order by right(check_date,4) desc " ;
							//echo $sql_Room ;
							$resPeriod = mysqli_query($_connection,$sql_period);		
					?>
					<select name="check_date" class="inputboxUpdate">
						<option value=""></option>
						<?php

							while($dat = mysqli_fetch_assoc($resPeriod))
							{
								$_select = (isset($_POST['check_date'])&&$_POST['check_date'] == $dat['check_date']?"selected":"");
								echo "<option value=\"" . $dat['check_date'] . "\" $_select>";
								echo $dat['check_date'] ;
								echo "</option>";
							}
							
						?>
					</select>
					
					<input type="submit" value="สืบค้น" class="button" name="search"/> <br/>
					<input type="checkbox" value="3" name="order_by" <?=isset($_POST['order_by'])?"checked":""?> /> เรียงตามเวลาสแกน
					</font>
				<br/>
			</td>
    	</tr>
  	</table>
  </form>
  
<? if(isset($_POST['search']) && $_POST['check_date'] == "") { ?>
	<center><br/><font color="#FF0000">กรุณาเลือก เดือนที่ต้องการทราบข้อมูล !</font></center>
<? } else if (isset($_POST['search']) && $_POST['check_date'] != "") { ?>
<div align="center">
  <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
    <tr> 
      <th colspan="21" align="center">
	  		<!--<img src="../images/school_logo.png" width="120px"><br/> -->
			ข้อมูลการสแกนลายนิ้วมือ วันที่ <?=$_POST['check_date']?><br/>
			<br/>
	  </th>
    </tr>
    <tr height="35px"> 
      	<td class="key" width="40px" align="center">ที่</td>
		<td class="key" width="200px" align="center">ชื่อ-สกุล</td>
		<td class="key" width="70px" align="center">เวลาลงชื่อเข้า</td>
		<!--<td class="key" width="70px" align="center">แปรผล</td>-->
		<td class="key" width="70px" align="center">เวลาลงชื่อออก</td>
		<!--<td class="key" width="70px" align="center">แปรผล</td>-->
		<td class="key" align="center">-</td>
    </tr>
	<?php

		$_sqlTable = "
			select 
				user_id,
				user_name,
				check_date,
				clock_in,
				clock_out,
				replace(replace(replace(replace(replace(replace(user_name,'นาย',''),'นางสาว',''),'นาง',''),'น.ส.',''),'.',' '),'  ',' ') as name
			from 
				hr_fingerprint_tmp 
			where
				check_date = '" . $_POST['check_date'] . "' and
				user_id not in 
					(
						100,10,52,69
					)
			";
		
		if(isset($_POST['order_by'])){
			$_sqlTable .= " order by clock_in";
		}
		else{
			$_sqlTable .= "	order by
			convert(name using tis620) ";
		}
		
		$_resTable = mysqli_query($_connection,$_sqlTable);
		$_i = 1;
	?>
	<? while($_dat = mysqli_fetch_assoc($_resTable)) { ?>
	<tr>
		<td align="center"><?=$_i++?></td>
		<td >
				<?=$_dat['name'] . ' (' . $_dat['user_id'] . ') '?>
		</td>
		<td align="center"><?=$_dat['clock_in']?></td>
		
		<td align="center"><?=$_dat['clock_out']?></td>
		
		<td align="right">
			<form method="post" action="">
				<input type="hidden" name="user_id" value="" />
				<input type="hidden" name="month" value="" />
				<input type="hidden" name="year" value="" />
				<input type="hidden" name="order_by" value="" />
				<input type="submit" name="details" value="เพิ่มเติม" disabled/>
			</form>
		</td>
	</tr>
	<? } ?>
</table>
</div>
  <? } //end else-if ?>

</div>

<?php
	function displayClockInMeaning($_data){

		return "";
	}
?>