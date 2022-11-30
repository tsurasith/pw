<SCRIPT language="javascript" type="text/javascript">
      function isNumberKey(evt) {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
         return true;
      }
</SCRIPT>

<div id="content">
  <table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
       <td width="6%" align="center">
	  	<a href="index.php?option=module_assets/index">
			<img src="../images/asset.png" alt="" width="48" height="48" border="0"/>
		</a>
	  </td>
    <td ><strong><font color="#990000" size="4">ระบบสารสนเทศครุภัณฑ์</font></strong><br />
	<span class="normal"><font color="#0066FF"><strong>สืบค้นครุภัณฑ์</strong></font></span></td>
      <td align="right">
	  	<?php
			if(isset($_REQUEST['acadyear'])) { $acadyear = $_REQUEST['acadyear']; }
			if(isset($_REQUEST['acadsemester'])) { $acadsemester = $_REQUEST['acadsemester']; }
		?>
		<!--ปีการศึกษา--><?php  
					//echo "<a href=\"index.php?option=module_assets/searchAllAssets&acadyear=" . ($acadyear - 1) . "\"><img src=\"../images/pull_left.gif\" border=\"0\" /></a> " ;
					//echo '<font color=\'blue\'>' .$acadyear . '</font>';
					//echo " <a href=\"index.php?option=module_assets/searchAllAssets&acadyear=" . ($acadyear + 1) . "\"><img src=\"../images/pull_right.gif\" border=\"0\" /></a> " ;
				?>
		<!--ภาคเรียนที่ -->  <?php 
					/*
					if($acadsemester == 1)
					{
						echo "<font color='blue'>1</font> , ";
					}
					else
					{
						echo " <a href=\"index.php?option=module_assets/searchAllAssets&acadyear=" . ($acadyear) . "&acadsemester=1 \"> 1</a> , " ;
					}
					if($acadsemester == 2)
					{
						echo "<font color='blue'>2</font>";
					}
					else
					{
						echo " <a href=\"index.php?option=module_assets/searchAllAssets&acadyear=" . ($acadyear) . "&acadsemester=2 \"> 2</a> " ;
					}
					*/
				?>
	  </td>
    </tr>
  </table>
  <? 
	$_totalRows = 0; 
	$_disPlay = 200;
	$_currentPage = (isset($_REQUEST['page'])?$_REQUEST['page']:1);
	$_num = (($_currentPage - 1) * $_disPlay ) + 1;
?>
<?	if(!isset($_POST['search']) && !isset($_REQUEST['page'])) { ?>
	<form action="" method="post">
	<table width="100%"class="admintable">
		<tr><td class="key" colspan="4">เครื่องมือสืบค้นทรัพย์สิน พัสดุ</td></tr>
		<tr>
			<?php
				$_sqlAssetType = "SELECT a.asset_type,t.asset_type_name,count(*) as c 
									FROM assets a left join asset_type t on (a.asset_type = t.asset_type_code) 
									GROUP BY a.asset_type
									ORDER BY convert(t.asset_type_name using tis620) ";
				$_resAssetType = mysqli_query($_connection,$_sqlAssetType);
				$_asset_type = "";
				$_asset_type = isset($_POST['asset_type'])?$_POST['asset_type']:"all";

			?>

			<td align="right">ประเภททรัพย์สิน</td>
			<td>
				<select name="asset_type" class="inputboxUpdate">
					<option value="all" <?=$_asset_type=="all"?"selected":""?>> ทั้งหมด </option>
					<? while($_datAssetType = mysqli_fetch_assoc($_resAssetType)) { ?> 
						<option value="<?=$_datAssetType['asset_type']?>" <?=$_asset_type==$_datAssetType['asset_type']?"selected":""?>>
							<?=(trim($_datAssetType['asset_type_name'])!=""?$_datAssetType['asset_type_name']:"") . ' (' . $_datAssetType['c'] . ')'?>
						</option>
					<? } //end while ?>
				</select>

			<?php
				$_sqlAssetBudget = "SELECT 
											a.asset_budget_code,b.asset_budget_name,count(*) as c
									FROM 
											assets a left join asset_budget b 
											on (a.asset_budget_code = b.asset_budget_code) 
									GROUP BY a.asset_budget_code 
									ORDER BY CONVERT(asset_budget_name USING tis620); ";
				$_resAssetBudget = mysqli_query($_connection,$_sqlAssetBudget);
				$_asset_budget = "";
				$_asset_budget = isset($_POST['asset_budget'])?$_POST['asset_budget']:"all";

			?>
			&nbsp; &nbsp; &nbsp;
			วิธีการได้มา
				<select name="asset_budget" class="inputboxUpdate">
					<option value="all" <?=$_asset_budget=="all"?"selected":""?>> ทั้งหมด </option>
					<? while($_datAssetBudget = mysqli_fetch_assoc($_resAssetBudget)) { ?> 
						<option value="<?=$_datAssetBudget['asset_budget_code']?>" <?=$_asset_budget==$_datAssetBudget['asset_budget_code']?"selected":""?>>
							<?=(trim($_datAssetBudget['asset_budget_name'])!=""?$_datAssetBudget['asset_budget_name']:"-ยังไม่ระบุ-") . ' (' . $_datAssetBudget['c'] . ')'?>
						</option>
					<? } //end while ?>
				</select>
			</td>
		</tr>
		<tr>
			<?php
				$_sqlAssetYear = "SELECT DISTINCT LEFT(asset_received_date,4) as asset_year,count(*) as c FROM assets 
									WHERE length(asset_received_date)=10 
									GROUP BY LEFT(asset_received_date,4)
									ORDER BY 1 ";
				$_resAssetYear = mysqli_query($_connection,$_sqlAssetYear);
				$_asset_year = "";
				$_asset_year = isset($_POST['asset_year'])?$_POST['asset_year']:"all";

			?>

			<td align="right">ปีที่ได้ถือกรรมสิทธิ์</td>
			<td>
				<select name="asset_year" class="inputboxUpdate">
					<option value="all" <?=$_asset_year=="all"?"selected":""?>> ทั้งหมด </option>
					<option value="not_identify" <?=$_asset_year=="not_identify"?"selected":""?>> ยังไม่ระบุ </option>
					<? while($_datAssetYear = mysqli_fetch_assoc($_resAssetYear)) { ?> 
						<option value="<?=$_datAssetYear['asset_year']?>" <?=$_asset_year==$_datAssetYear['asset_year']?"selected":""?>>
							<?=$_datAssetYear['asset_year']+543 . ' ('. $_datAssetYear['c'] . ')'?>
						</option>
					<? } //end while ?>
				</select>


				<?php
					$_sqlAssetStatus = "SELECT * FROM asset_status 
										WHERE display_status = 'Y' 
										ORDER BY convert(asset_status_name using tis620) ";
					$_resAssetStatus = mysqli_query($_connection,$_sqlAssetStatus);
					$_asset_status = "";
					$_asset_status = isset($_POST['asset_status'])?$_POST['asset_status']:"all";

				?>

				&nbsp; &nbsp; &nbsp;
				สถานะ
			
				<select name="asset_status" class="inputboxUpdate">
					<option value="all" <?=$_asset_status=="all"?"selected":""?>> ทั้งหมด </option>
					<option value="not_indentify" <?=$_asset_status=="not_indentify"?"selected":""?>> ยังไม่ระบุ </option>
					<? while($_datAssetStatus = mysqli_fetch_assoc($_resAssetStatus)) { ?> 
						<option value="<?=$_datAssetStatus['asset_status_code']?>" <?=$_asset_status==$_datAssetStatus['asset_status_code']?"selected":""?>>
							<?=$_datAssetStatus['asset_status_name']?>
						</option>
					<? } //end while ?>
				</select>
			</td>
		</tr>
		
		<tr>
			<?php
				$_sqlAsseLocation = "SELECT * FROM asset_internal_location 
									WHERE display_status = 'Y' 
									ORDER BY convert(asset_internal_location_name using tis620) ";
				$_resAssetLocation = mysqli_query($_connection,$_sqlAsseLocation);
				$_asset_location = "";
				$_asset_location = isset($_POST['asset_internal_location'])?$_POST['asset_internal_location']:"all";

			?>

			<td align="right">ที่ตั้งทรัพย์สิน</td>
			<td>
				<select name="asset_internal_location" class="inputboxUpdate">
					<option value="all" <?=$_asset_location=="all"?"selected":""?>> ทั้งหมด </option>
					<? while($_datAssetLocation = mysqli_fetch_assoc($_resAssetLocation)) { ?> 
						<option value="<?=$_datAssetLocation['asset_internal_location_code']?>" <?=$_asset_location==$_datAssetLocation['asset_internal_location_code']?"selected":""?>>
							<?=$_datAssetLocation['asset_internal_location_name']?>
						</option>
					<? } //end while ?>
				</select>

			<?php
				$_sqlAssetOwneer = "SELECT
										a.asset_owner_user,
										u.user_account_prefix,
										u.user_account_firstname,
										u.user_account_lastname
									FROM
										assets a
									LEFT JOIN users_account u ON
										(
											a.asset_owner_user = u.user_account_id
										)
									WHERE
										a.asset_owner_user IS NOT NULL
									ORDER BY convert(u.user_account_firstname using tis620) ";
				$_resAsseOwner = mysqli_query($_connection,$_sqlAssetOwneer);
				$_asset_owner = "";
				$_asset_owner = isset($_POST['asset_owner'])?$_POST['asset_owner']:"all";

			?>
			&nbsp; &nbsp; &nbsp;
			ผู้ถือครอง
				<select name="asset_owner" class="inputboxUpdate">
					<option value="all" <?=$_asset_owner=="all"?"selected":""?>> ทั้งหมด </option>
					<? while($_datAssetOwner = mysqli_fetch_assoc($_resAsseOwner)) { ?> 
						<option value="<?=$_datAssetOwner['asset_owner_user']?>" <?=$_asset_owner==$_datAssetOwner['asset_owner_user']?"selected":""?>>
							<?=$_datAssetOwner['user_account_prefix'] . $_datAssetOwner['user_account_firstname'] . ' ' . $_datAssetOwner['user_account_lastname']?>
						</option>
					<? } //end while ?>
				</select>
			</td>
		</tr>


		<tr>
			<td align="right" width="130px">รหัสพัสดุ</td>
			<td valign="top">
					<input type="text" name="asset_number" maxlength="15" size="20" class="inputboxUpdate" onkeypress="return isNumberKey(event)" />
					<br/>สามารถระบุตัวเลขเพียงบางส่วนได้โดยไม่ต้องใส่เครื่องหมาย - เช่น พิมพ์ 090610165 เพื่อหาเลขพัสดุ 090-61-0165
			</td>
		</tr>
		<tr>
			<td align="right">ชื่อพัสดุ</td>
			<td><input type="text" name="asset_name" maxlength="40" size="50" class="inputboxUpdate" disabled /></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td>
				ในช่อง "ชื่อพัสดุ" สามารถพิมพ์เฉพาะบางส่วนของข้อความได้ เช่่น ถ้าต้องการสืบค้นหาพัสดุชื่อ "บ้านพักครู" <br/>
				สามารถใช้พิมพ์แค่คำว่า "บ้าน" ได้
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="search" value="สืบค้น"/> <input type="reset" value="ล้างข้อมูล" /></td>
		</tr>
	</table>
	</form>
<? }  ?>

<? if(isset($_POST['search'])) { // end ! isset($_POST['search']) ?>
	<? 

		$_sql_results = "SELECT
							`asset_id`,
							`asset_number`,
							`asset_name`,
							`asset_type`,
							t.asset_type_name,
							`asset_received_date`,
							`asset_suspend_date`,
							`asset_service_life`,
							a.`asset_budget_code`,
							b.asset_budget_name,
							`asset_price`,
							`asset_description`,
							`asset_amount`,
							a.`asset_unit_code`,
							u.asset_unit_name,
							a.`asset_status_code`,
							s.asset_status_name,
							`asset_status_internal`,
							a.`asset_internal_location_code`,
							i.asset_internal_location_name,
							a.`asset_owner_user`,
							`project_id`,
							`sub_project_id`,
							`asset_report_1`,
							`asset_report_2`,
							`asset_report_3`,
							`asset_report_internal`,
							a.`created_datetime`,
							a.`created_user`,
							a.`updated_datetime`,
							a.`updated_user`
						FROM
							assets a 
							left join asset_budget b on (a.asset_budget_code = b.asset_budget_code)
							left join asset_type t   on (a.asset_type = t.asset_type_code)
							left join asset_status s on (a.asset_status_code = s.asset_status_code)
							left join asset_unit u   on (a.asset_unit_code = u.asset_unit_code)
							left join asset_internal_location i on (a.asset_internal_location_code = i.asset_internal_location_code)
						WHERE
							1=1 ";

		if(isset($_POST['asset_type']))
		{
			if($_POST['asset_type'] != "all")
			$_sql_results .= " and a.asset_type = '" . $_POST['asset_type'] . "'";
		}

		if(isset($_POST['asset_budget']))
		{
			if($_POST['asset_budget'] != "all")
			$_sql_results .= " and a.asset_budget_code = '" . $_POST['asset_budget'] . "'";
		}

		if(isset($_POST['asset_year']))
		{
			if(strlen($_POST['asset_year'])==4)
			$_sql_results .= " and left(a.asset_received_date,4) = '" . $_POST['asset_year'] . "'";

			if($_POST['asset_year'] == "not_identify")
			$_sql_results .= " and a.asset_received_date is null ";
		}

		if(isset($_POST['asset_number']))
		{
			if(trim($_POST['asset_number']) != "")
			$_sql_results .= " and replace(a.asset_number,'-','') like '%" . $_POST['asset_number'] . "%' ";
		}


		$_sql_results .= "";
		$_sql_results .= " order by a.asset_number ";

		
		$_sqlAll = $_sql_results; // นับจำนวนแถวทั้งหมด
		@$_totalRows = mysqli_num_rows(mysqli_query($_connection,$_sqlAll));

		$_sql_results .= " limit " . ($_num-1)  . "," . ($_disPlay);

		
		$_result = mysqli_query($_connection,$_sql_results);
		//echo $_sql_results ;

		$_event_details = "สืบค้นข้อมูลสินทรัพย์";
		$_event_key = hash("sha256",$_sql_results.time());

		if(checkDuplicateEventLog($_connection,$_event_key)){
			event_log($_connection,4,0,0,$_event_key,$_event_details,$_SESSION['user_account_id'],$acadyear,$acadsemester);
		}

		
?>
	<table width="100%" align="center" cellspacing="1" class="admintable" border="0" cellpadding="3">
      <tr> 
        <td  class="key" colspan="10">ผลการสืบค้น <a href="index.php?option=module_assets/searchAllAssets&acadyear=<?=$acadyear?>&acadsemester=<?=$acadsemester?>">[กลับหน้าสืบค้น]</a></td>
	  </tr>
	  <tr bgcolor="#CCCCFF">
	  	<td width="30px" align="center">ที่</td>
	  	<td width="180px" align="center">ชื่อสินทรัพย์</td>
		<td width="85px" align="center">รหัสพัสดุ</td>
		<td width="180px" align="center">ประเภทสินทรัพย์</td>
		<td align="center">ลักษณะสินทรัพย์</td>
		<td align="center">วันที่ได้<br/>ถือกรรมสิทธิ์</td>
		<td align="center">ราคาต่อหน่วย</td>
		<td align="center">อายุ<br/>การใช้งาน</td>
		<td align="center">วิธีการ<br/>ได้มา</td>
		<td align="center">สภาพปัจจุบัน</td>
	  </tr>
	<? if(mysqli_num_rows($_result) > 0) { ?>
		<? while($_dat = mysqli_fetch_assoc($_result)){ ?>
		<tr onMouseOver="this.style.backgroundColor='#FFCCFF'; this.style.cursor='hand';" onMouseOut=this.style.backgroundColor="#FFFFFF" >
			<td align="center" valign="top">	<?=$_num++?></td>
			<td valign="top">					<?=$_dat['asset_name']?></td>
			<td align="center" valign="top">	<?=$_dat['asset_number']?></td>
			<td valign="top">					<?=$_dat['asset_type_name']?></td>
			<td valign="top">					<?=$_dat['asset_description']?></td>
			<td align="center" valign="top">	<?=$_dat['asset_received_date']?></td>
			<td align="right" valign="top">		<?=number_format($_dat['asset_price'],2,'.',',')?></td>
			<td align="center" valign="top">	<?=$_dat['asset_service_life']?> ปี</td>
			<td valign="top">					<?=$_dat['asset_budget_name']?></td>
			<td valign="top">					<?=$_dat['asset_status_name']?></td>
		<tr>
		<? } mysqli_free_result($_result); //end while ?>
		<tr>
	  	<td>&nbsp;</td><td>&nbsp;</td>
		<td colspan="4" align="center">
			หน้าที่ <?=displayText($_currentPage)?> จากจำนวนทั้งหมด <?=displayText(ceil($_totalRows/$_disPlay))?> หน้า<br/>
			รายการที่ <?=displayText((($_currentPage - 1) * $_disPlay ) + 1)?>
			ถึง <?=displayText(($_currentPage * $_disPlay))?> 
			จากจำนวนทั้งหมด <?=displayText($_totalRows)?> รายการ <br/>
		</td>
	  </tr>
	<? }else { //end if check rows ?>
		
		<tr><td colspan="6" align="center"><font color="#FF0000"><br/>ไม่พบข้อมูลที่ต้องการ</font></td></tr>
	<? } //end else check rows ?> 
	 </table>
<?php } // end-else  check submit search?>
</div>
