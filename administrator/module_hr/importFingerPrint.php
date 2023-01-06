<?php


	$_target = $_target . $_hr_raw_folder . "/";

	$_processing_text = "";
	$_processing_result = false;

	$_text = "";

	$_import_date = "";

	if(isset($_POST['upload']))
	{
		$_allowFileType = [
			'application/vnd.ms-excel',
			'text/xls',
			'text/xlsx'
		];

		if(in_array($_FILES["hr_file"]["type"],$_allowFileType)) {

			$_target_file = $_target . $_FILES["hr_file"]["name"];
			move_uploaded_file($_FILES["hr_file"]["tmp_name"],$_target_file);

			if(file_exists($_target_file)){
				$_processing_text = "อัพโหลดไฟล์เรียบร้อยแล้ว";

				// start process XLS here

				require_once "../include/SimpleXLS.php";
				//use Shuchkin\SimpleXLS;

				if ($xls = SimpleXLS::parse($_target_file)) {
					
					//print_r($xls->rows());
					$header_values = $data = array();

					$dd = array();

					foreach ($xls->rows() as $k => $r) {
						if ($k === 0) {
							$header_values = $r;
							continue;
						}
						$data[] = array_combine($header_values, $r);
						array_push($dd,$r);
						/*
						   $dd[][0] = 'รหัสพนักงาน'
						   $dd[][1] = 'ชื่อ'
						   $dd[][2] = 'วันที่'
						   $dd[][3] = 'เวลาเข้างาน'
						   $dd[][4] = 'เวลาออกงาน'
						*/
					}
					//print_r($data);
					for($_i=0;$_i<count($data);$_i++){
						$_sql_insert = "
						REPLACE INTO `hr_fingerprint_tmp`(
							`user_id`,
							`user_name`,
							`check_date`,
							`clock_in`,
							`clock_out`,
							`imported_user_id`
						)
						VALUES (
							'" . $data[$_i]['รหัสพนักงาน'] . "',
							'" . $data[$_i]['ชื่อ'] . "',
							'" . $data[$_i]['วันที่'] . "',
							'" . $data[$_i]['เวลาเข้างาน'] . "',
							'" . $data[$_i]['เวลาออกงาน'] . "',
							'" . $_SESSION['user_account_id'] . "' 
						)
							";
						//echo $_sql_insert . "<br>";
						$_import_date = $data[$_i]['วันที่'];

						$_res = mysqli_query($_connection,$_sql_insert);
						if($_res){
							$_processing_result = true;
							$_processing_text = "ระบบนำเข้าข้อมูลจากไฟล์ MS-Excel สู่ฐานข้อมูลเรียบร้อยรอการประมวลผลต่อไป";

						}else{
							$_processing_result = false;
							$_processing_text = "การนำเข้าข้อมูลจากไฟล์ MS-Excel สู่ฐานข้อมูลผิดพลาด เกิดจาก - " . mysqli_error($_connection);
						}
					}

				} else {
					//echo SimpleXLS::parseError();
					$_processing_text = "ระบบไม่สามารถนำเข้าข้อมูลได้ กรุณาตรวจสอบรูปแบบไฟล์ที่นำเข้าอีกครั้ง หรือตรวจสอบให้มั่นใจว่าได้ทำการจัดการไฟล์ข้อมูลให้อยู่ในรูปแบบที่ถูกต้อง";
				}
			}
			if($_processing_result){
				// line message here
				$_text .= "งานบุคลากร ได้นำเข้าข้อมูลการสแกนลายนิ้วมือ วันที่ " . $_import_date;
				$_text .= " แล้ว ";
				$_text .= "" . "โดย - " . $_SESSION['shortname'];

				$message = $_text;
				SendLineMessage($message,$_line_token);

				$_event_details = "";
				$_event_details .= $_text;

				$_event_key = hash("sha256",$_event_details);

				$_event_user_id = $_SESSION['user_account_id'];
				if(checkDuplicateEventLog($_connection,$_event_key)){
					event_log($_connection,1,1,2,
					$_event_key,
					$_event_details,
					$_event_user_id,$acadyear,$acadsemester);
				}
			}
		}else{
			$_processing_text = "คุณไม่ได้อัพโหลดไฟล์นามสกุล xls หรือ xlsx อย่างถูกต้อง กรุณาส่งไฟล์ข้อมูลให้ผู้ดูแลระบบตรวจสอบ";
		}
		
	}// --- end upload file
?>


<div id="content">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="header">
		<tr>
			<td width="6%" align="center"><a href="index.php?option=module_hr/index"><img src="../images/fingerprint.png" alt="" width="48px" border="0" /></a></td>
			<td ><strong><font color="#990000" size="4">งานบริหารบุคลากร</font></strong><br />
			<span class="normal"><font color="#0066FF"><strong>3.1 นำเข้าไฟล์สแกนลายนิ้วมือ</strong></font></span></td>
			<td>
				ปีการศึกษา<?=$acadyear?> <br/>
			</td>
		</tr>
	</table>

	<div align="center">
		<form method="post" enctype="multipart/form-data" action=""> 
			<table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center" width="500px">
				<tr height="35px">  
					<td colspan="2" align="center" class="key">
							<!-- <img src="../images/school_logo.png" width="120px"><br/> -->
							อัพโหลดไฟล์สแกนลายนิ้วมือ
							<br/>
					</td>
				</tr>
				<tr>
					<td>เลือกไฟล์</td>
					<td>
							<input type="file" name="hr_file" accept=".xls, .xlsx" size="60px"/><br/>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="upload" value="อัพโหลด"/></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
							if(isset($_POST['upload'])){
								if($_processing_result){
									echo "<font color='green'>";
									echo $_processing_text;
									echo "</font>";
								}else{
									echo "<font color='red'>";
									echo $_processing_text;
									echo "</font>";
								}
								
							}
						?>
					</td>
				</tr>
			</table>
		</form> 


		<?php

			/*
			require_once "../include/SimpleXLS.php";
			//use Shuchkin\SimpleXLS;


			$_file = $_target . "test.xls";

			echo "<h1> books.xls </h1><pre>";
			if ($xls = SimpleXLS::parse($_file)) {
				print_r($xls->rows());
			} else {
				echo SimpleXLS::parseError();
			}
			echo '<pre>';
			*/
		?>

	</div>

</div>
