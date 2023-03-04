<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<SCRIPT language="Javascript" type="text/javascript">
      function isNumberKey2(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode;
         if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57 ))
            return false;

         return true;
      }
	  function checkFormValue()
	  {
	  	if(document.getElementById('activity_number').value == '' )
		{ 
			alert('กรุณาป้อนข้อมูล รหัสกิจกรรมให้ถูกต้อง'); 
			document.getElementById('activity_number').focus(); 
			return;
		}

		if(document.getElementById('activity_name').value == '' || document.getElementById('activity_name').value.length < 6)
		{ 
			alert('กรุณาป้อนข้อมูล ชื่อกิจกรรมก่อน'); 
			document.getElementById('activity_name').focus(); 
			return;
		}

		/*
		if(document.getElementById('activity_details').value == '' || document.getElementById('activity_details').value.length < 6)
		{ 
			alert('กรุณาป้อนข้อมูล วัตถุประสงค์ก่อน'); 
			document.getElementById('activity_details').focus(); 
			return;
		}
		*/
		
		if(document.getElementById('start_date').value == '' || document.getElementById('start_date').value.length < 10)
		{ 
			alert('กรุณาป้อนข้อมูล วันที่เริ่มกิจกรรมก่อน'); 
			document.getElementById('start_date').focus(); 
			return;
		}
		
		if(document.getElementById('finish_date').value == '' || document.getElementById('finish_date').value.length < 10)
		{ 
			alert('กรุณาป้อนข้อมูล วันที่สิ้นสุดกิจกรรมก่อน'); 
			document.getElementById('finish_date').focus(); 
			return;
		}

		const start_date   = new Date(document.getElementById('start_date').value);
		const finish_date  = new Date(document.getElementById('finish_date').value);

		if(start_date > finish_date)
		{
			alert('คุณป้อนข้อมูลไม่ถูกต้อง วันที่เริ่มกิจกรรม มากกว่า วันสิ้นสุดกิจกรรม กรุณาตรวจสอบอีกครั้ง'); 
			document.getElementById('start_date').focus(); return;
		}


		if(document.getElementById('income_id').value == '')
		{ 
			alert('กรุณาเลือก ประเภทเงินงบประมาณก่อน'); 
			document.getElementById('income_id').focus(); 
			return;
		}

		if(document.getElementById('acadyear').value == '')
		{ 
			alert('กรุณาป้อนข้อมูล ปีงบประมาณ'); 
			document.getElementById('acadyear').focus(); 
			return;
		}
		
		if(document.getElementById('approve_budget').value == '' || document.getElementById('approve_budget').value*0 != 0)
		{ 
			alert('กรุณาป้อนข้อมูล เงินงบประมาณที่อนุมัติก่อน'); 
			document.getElementById('approve_budget').focus(); 
			return;
		}

		if(document.getElementById('department_id').value == '')
		{ 
			alert('กรุณาเลือก ฝ่ายที่รับผิดชอบกิจกรรมก่อน'); 
			document.getElementById('department_id').focus(); 
			return;
		}

		if(document.getElementById('activity_owner').value == '')
		{ 
			alert('กรุณาเลือก ผู้รับผิดชอบกิจกรรมก่อน'); 
			document.getElementById('activity_owner').focus(); 
			return;
		} 
		else
		{ 
			document.myform.submit();
		}
	  }

</SCRIPT>

<link rel="stylesheet" type="text/css" href="module_projects/css/calendar-mos2.css"/>
<script language="JavaScript" type="text/javascript" src="module_projects/js/calendar.js"></script>


<?php

	$_activity_id = "";
	if(isset($_POST['action'])){
		$_activity_id = $_POST['activity_id'];
	}else{
		$_activity_id = gen_uuid();
	}

	if(isset($_GET['project_number_search']) && isset($_GET['activity_id'])){
		$_activity_id = $_GET['activity_id'];
	}

	$_project_id = "";
?>


<?php
	$_processing_text = "";
	$_processing_result = false;
	$_text = "";

	// insert data
	if(isset($_POST['action']) && $_POST['action'] == "save"){

		$_sql_insert = "
			INSERT INTO `project_activity`(
				`activity_id`,
				`project_id`,
				`activity_number`,
				`activity_name`,
				`acadyear`,
				`activity_details`,
				`start_date`,
				`finish_date`,
				`income_id`,
				`department_id`,
				`approve_budget`,
				`used_budget`,
				`activity_owner`,
				`activity_status`,
				`created_datetime`,
				`created_user`,
				`updated_datetime`,
				`updated_user`
			)
			VALUES(
				'" . $_POST['activity_id'] . "',
				'" . $_POST['project_id'] . "',
				'" . $_POST['activity_number'] . "',
				'" . trim($_POST['activity_name']) . "',
				'" . $_POST['acadyear'] . "',
				'" . trim($_POST['activity_details']) . "',
				'" . $_POST['start_date'] . "',
				'" . $_POST['finish_date'] . "',
				'" . $_POST['income_id'] . "',
				'" . $_POST['department_id'] . "',
				'" . $_POST['approve_budget'] . "',
				'" . $_POST['used_budget'] . "',
				'" . $_POST['activity_owner'] . "',
				'" . $_POST['activity_status'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "',
				CURRENT_TIMESTAMP,
				'" . $_SESSION['user_account_id'] . "'
			)
		";

		$_res = mysqli_query($_connection,$_sql_insert);
		if($_res){
			$_processing_text = "บันทึกข้อมูลกิจกรรม " . trim($_POST['activity_name']) . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "กิจกรรม: ";
			$_text .= trim($_POST['activity_name']) . " ถูกเพิ่มเข้าในระบบแล้ว";
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

		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกกิจกรรมได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}
	}

	// - update data
	if(isset($_POST['action']) && $_POST['action'] == "update"){ 

		$_sql_update = "
			UPDATE `project_activity`
			SET
				`activity_number`  = '" . $_POST['activity_number'] . "',
				`activity_name`    = '" . trim($_POST['activity_name']) . "',
				`acadyear`         = '" . $_POST['acadyear'] . "',
				`activity_details` = '" . trim($_POST['activity_details']) . "',
				`start_date`       = '" . $_POST['start_date'] . "',
				`finish_date`      = '" . $_POST['finish_date'] . "',
				`income_id`        = '" . $_POST['income_id'] . "',
				`department_id`    = '" . $_POST['department_id'] . "',
				`approve_budget`   = '" . $_POST['approve_budget'] . "',
				`used_budget`      = '" . $_POST['used_budget'] . "',
				`activity_owner`   = '" . $_POST['activity_owner'] . "',
				`activity_status`  = '" . $_POST['activity_status'] . "',
				`updated_datetime` =  CURRENT_TIMESTAMP,
				`updated_user`     = '" . $_SESSION['user_account_id'] . "'
			WHERE
				`activity_id` =   '" . $_POST['activity_id'] . "'
		";

		$_res_update = mysqli_query($_connection,$_sql_update);
		if($_res_update){
			$_processing_text = "บันทึกแก้ไขข้อมูลกิจกรรม " . trim($_POST['activity_name']) . " เรียบร้อยแล้ว";
			$_processing_result = true;

			// line message here
			$_text .= "กิจกรรม: ";
			$_text .= trim($_POST['activity_name']) . " ถูกแก้ไขข้อมูล ";
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

		}else{
			$_processing_text  = "เกิดข้อผิดพลาด ไม่สามารถบันทึกกิจกรรมได้" . "<br/>";
			$_processing_text .= "อาจจะเกิดจากการต้องการบันทึกข้อมูลซ้ำ หรือ แจ้งข้อความนี้ต่อผู้ดูแลระบบ -" . mysqli_error($_connection);
		}
		
	}


	// search & project number textbox
	$_project_number = "";

	if(isset($_GET['project_number_search']) && trim($_GET['project_number_search'] != "")){
		$_project_number = $_GET['project_number_search'];
	}


	// project info
	$_sql_project = "
			select 
				p.*,
				d.department_name,
				concat(t.firstname,' ',t.lastname) as 'owner_name',
				s.status_name
			from 
				projects p left join departments d 
				on (p.department_id  = d.department_id) left join teachers t 
				on (p.project_owner  = t.teacher_id) left join project_status s
				on (p.project_status = s.status_id)
			where p.project_number = '" . $_project_number . "'";
	$_res_project = mysqli_query($_connection,$_sql_project);

	$_check_existing_activity = 0;
	$_check_existing = mysqli_num_rows($_res_project);

	if($_check_existing>0){
		$_project = mysqli_fetch_assoc($_res_project);
		$_project_id = $_project['project_id'];
	}


	// retreive a new project activity
	$_sql_new_act = "select * from project_activity where activity_id = '" . $_activity_id . "'";
	$_res_new_act = mysqli_query($_connection,$_sql_new_act);

	$_check_new_act = mysqli_num_rows($_res_new_act);

	if($_check_new_act>0){
		$_new_act = mysqli_fetch_assoc($_res_new_act);
	}

?>

<div id="content">
	
		<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
			<tr> 
				<td width="6%" align="center">
					<a href="index.php?option=module_projects/index">
						<img src="../images/computer.png" alt="" width="48" height="48" border="0"/>
					</a>
				</td>
				<td >
					<strong><font color="#990000" size="4">ระบบสารสนเทศแผน งบประมาณ โครงการและกิจกรรม</font></strong><br />
					<span class="normal"><font color="#0066FF"><strong>1.4 บันทึกเป้าหมายโครงการ/กิจกรรม</strong></font></span>
				</td>
				<td >
					ปีการศึกษา <?=$acadyear?>
					ภาคเรียนที่ <?=$acadsemester?>
					<?php
						$_value = "";
						if(isset($_GET['project_number_search'])){
							$_value = $_GET['project_number_search'];
						}
					?>

					<form method="get" autocomplete="off" action="">
						<input type="hidden" name="option" value="module_projects/addGoals" />
						<font size="2" color="#000000">รหัสโครงการ</font>
						<input  type="text" name="project_number_search" id="project_number_search" 
								value="<?=$_value?>" 
								size="15" maxlength="20" class="inputboxUpdate" />

						<input  type="submit" name="search" value="เรียกดู" class="button" />
					</form>
				</td>
			</tr>
		</table>

		<? if($_check_existing >0){ ?>
			<div align="center">
				<table class="admintable">
					<tr>
						<td class="key" align="center" rowspan="2" width="100px">รหัสโครงการ</td>
						<td class="key" align="center" rowspan="2" width="300px">ชื่อโครงการ</td>
						<td class="key" align="center" colspan="2">งบประมาณ (บาท)</td>
						<td class="key" align="center" rowspan="2" width="120px"> ฝ่าย</td>
						<td class="key" align="center" rowspan="2" width="120px">ผู้รับผิดชอบ</td>
						<td class="key" align="center" rowspan="2" width="150px">สถานะโครงการ</td>
					</tr>
						<td class="key" align="center" width="80px">อนุมัติ</td>
						<td class="key" align="center" width="80px">คงเหลือ</td>
					</tr>
					<tr>
						<td valign="top" align="center"><?=$_project['project_number']?></td>
						<td valign="top" align="left"><?=$_project['project_name']?></td>
						<td valign="top" align="right"><?=number_format($_project['approve_budget'],2,'.',',')?></td>
						<td valign="top" align="right"><?=number_format($_project['approve_budget']-$_project['used_budget'],2,'.',',')?></td>
						<td valign="top" align="center"><?=$_project['department_name']?></td>
						<td valign="top" align="center"><?=$_project['owner_name']?></td>
						<td valign="top" align="center"><?=$_project['status_name']?></td>
					</tr>
				</table>

				<?php
					$_sql_activity = "
							select 
								p.*,
								d.department_name,
								concat(t.firstname,' ',t.lastname) as 'owner_name',
								s.status_name
							from 
								project_activity p left join departments d 
								on (p.department_id  = d.department_id) left join teachers t 
								on (p.activity_owner  = t.teacher_id) left join project_status s
								on (p.activity_status = s.status_id)
							where p.project_id = '" . $_project['project_id'] . "'
							order by
								p.sort_order,
								p.activity_number
							";
					$_res_activity = mysqli_query($_connection,$_sql_activity);

					$_check_existing_activity = mysqli_num_rows($_res_activity);
				?>
				
				<? if($_check_existing_activity>0){ ?> 
					<table class="admintable">
						<tr>
							<td class="key" align="center" width="100px">รหัสกิจกรรม</td>
							<td class="key" align="center" width="300px">ชื่อกิจกรรม</td>
							<td class="key" align="center" width="80px">อนุมัติ</td>
							<td class="key" align="center" width="80px">คงเหลือ</td>
							<td class="key" align="center" width="120px"> ฝ่าย</td>
							<td class="key" align="center" width="120px">ผู้รับผิดชอบ</td>
							<td class="key" align="center" width="150px">สถานะกิจกรรม</td>
						</tr>
							
						</tr>
						<? while($_existing_act = mysqli_fetch_assoc($_res_activity)) { ?>
							<tr>
								<td valign="top" align="center">
									<a href="index.php?option=module_projects/addGoals&project_number_search=<?=$_project_number?>&activity_id=<?=$_existing_act['activity_id']?>">
									<?=$_existing_act['activity_number']?>
									</a>
								</td>
								<td valign="top" align="left"><?=$_existing_act['activity_name']?></td>
								<td valign="top" align="right"><?=number_format($_existing_act['approve_budget'],2,'.',',')?></td>
								<td valign="top" align="right"><?=number_format($_existing_act['approve_budget']-$_existing_act['used_budget'],2,'.',',')?></td>
								<td valign="top" align="left"><?=$_existing_act['department_name']?></td>
								<td valign="top" align="left"><?=$_existing_act['owner_name']?></td>
								<td valign="top" align="left"><?=$_existing_act['status_name']?></td>
							</tr>
						<? } ?>
					</table>
				<? } ?> 
			</div>
		

			<div align="center">
				<table class="admintable">
					<tr height="55px">
						<td colspan="2" align="center">
							<?php
									if($_processing_result){
										echo "<font color='green'>";
										echo $_processing_text;
										echo "</font>";
									}else{
										echo "<font color='red'>";
										echo $_processing_text;
										echo "</font>";
									}
							?>
						</td>
					</tr>
				</table>
			</div>


			<div id="app" align="center">
				<div v-if="goals[0] != 'NO'">
					<table class="admintable">
						<tr>
							<td class="key" align="center" width="30px">-</td>
							<td class="key" align="center" width="400px">เป้าหมาย</td>
							<td class="key" align="center" width="100px">ผลการประเมิน</td>
							<td class="key" align="center" width="185px">ผู้ตรวจสอบ/ประเมิน</td>
							<td class="key" align="center" width="115px"></td>
						</tr>
						<tr v-for="(row,index) in goals" :key="row.project_goal_id">
							<td align="center">{{index+1}}</td>
							<td align="left"  >{{row.goal_details}}</td>
							<td align="center">{{row.goal_evaluate}}</td>
							<td align="left"  >{{row.checker_name}}</td>
							<td align="center">
								<input type="button" @click="editProjectGoal(row.project_goal_id)" value="แก้ไข" /> &nbsp; 
								<input type="button" @click="delProjectGoal(row.project_goal_id)" value="ลบ" />
							</td>
						</tr>
					</table>
				</div>
				<form @submit="submitData" method="POST">
					<table class="admintable">
							<tr>
							<td class="key" align="center" width="30px">-</td>
							<td class="key" align="center" width="400px">เป้าหมาย</td>
							<td class="key" align="center" width="100px">ผลการประเมิน</td>
							<td class="key" align="center" width="185px">ผู้ตรวจสอบ/ประเมิน</td>
							<td class="key" align="center" width="115px"></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="text"   v-model="form.goal_details" size="45" class="inputboxUpdate"></td>
							<td align="center">
								<select v-model="form.goal_evaluate" class="inputboxUpdate">
									<option value=""></option>
									<option>ผ่าน</option>
									<option>ไม่ผ่าน</option>
								</select>
							</td>
							<td align="center">
								<select v-model="form.teacher_id" class="inputboxUpdate">
									<option value=""></option>
									<option v-for="teacher in teachers" v-bind:value="teacher.teacher_id">{{teacher.firstname + ' ' + teacher.lastname}}</option>
								</select>
							</td>
							<td colspan="2" align="center"><input type="submit" v-model="form.status" class="button"  /></td>
						</tr>
					</table>
				</form>
				<div align="center" style="display:none;">
					{{ form }}<br/>
				</div>
			</div>
			<script>
				var app = new Vue({
					el: "#app",
					data:{
						goals:"",
						teachers:"",
						form:{
							teacher_id:"",
							project_goal_id:"",
							goal_details :"",
							goal_evaluate:"",
							goal_checker :"",
							isEdit : false,
							status : "บันทึก"
						}
					},
					methods:{
						submitData(e){
							e.preventDefault();
							check = (this.form.goal_details != "" && this.form.teacher_id != "");

							//console.log(this.form.teacher_id);
							const project_id_from_php = "<?=$_project_id?>";
							//insert
							if (check && !this.form.isEdit){
								axios.post("./module_projects/addGoalsActions.php",{
									teacher_id    : this.form.teacher_id,
									goal_details  : this.form.goal_details,
									goal_evaluate : this.form.goal_evaluate,
									goal_checker  : this.form.goal_checker,
									project_id    : project_id_from_php,
									action        : "insert"
								}).then(function(res){
									console.log(res.data);
									app.resetInputForm();
									app.getAllGoals();
								})
							}

							//update
							if(check && this.form.isEdit){
								// console.log(this.form.teacber_id);
								axios.post("./module_projects/addGoalsActions.php",{
									teacher_id   : this.form.teacher_id,
									project_goal_id: this.form.project_goal_id,
									goal_details : this.form.goal_details,
									goal_evaluate: this.form.goal_evaluate,
									goal_checker : this.form.goal_checker,
									action: "update"
								}).then(function(res){
									//console.log(res.data);
									app.resetInputForm();
									app.getAllGoals();
								})
							}
						},
						editProjectGoal(id){
							//alert('edit' + project_goal_id);
							this.form.status = "แก้ไข";
							this.form.isEdit = true;
							axios.post("./module_projects/addGoalsActions.php",{
								project_goal_id:id,
								action: "get_project_goal"
							}).then(function(res){
								//console.log(res.data);
								app.form.goal_details    = res.data.goal_details;
								app.form.goal_checker    = res.data.goal_checker;
								app.form.goal_evaluate   = res.data.goal_evaluate;
								app.form.project_goal_id = res.data.project_goal_id;
								app.form.teacher_id      = res.data.goal_checker;

							})
						},
						delProjectGoal(id){
							if(confirm("คุณต้องการลบข้อมูล " + id + " ใช่หรือไม่ ")){
								axios.post("./module_projects/addGoalsActions.php",{
									project_goal_id:id,
									action: "delete"
								}).then(function(res){
									app.resetInputForm();
									app.getAllGoals();
								})
							}
						},
						getAllGoals(){
							axios.post("./module_projects/addGoalsActions.php",{
								action:"get_all",
								project_id: "<?=$_project_id?>"
							}).then(function(res){
								app.goals = res.data;
								console.log(res.data)
							})
						},
						resetInputForm(){
							this.form.teacher_id    = "";
							this.form.goal_details  = "";
							this.form.goal_evaluate = "";
							this.form.goal_checker  = "";
							this.form.status        = "บันทึก";
							this.form.isEdit        = false;
						},
						getAllTeacher(){
							axios.post("./module_projects/addGoalsActions.php",{
								action: "get_all_teacher"
							}).then(function(res){
								app.teachers = res.data;
								//console.log(app.teachers);
							})
						}
					},
					mounted(){
						this.getAllTeacher();
						this.getAllGoals();
					}
				});
			</script>
		<? } ?>
		
</div>

