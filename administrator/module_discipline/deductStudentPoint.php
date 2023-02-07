<script language="javascript" type="text/javascript">
	function checkDeduct()
	{
			if(document.getElementById('point_comment').value.trim()==''){
				alert('กรุณาระบุสาเหตุของการหักคะแนนพฤติกรรม');
				document.getElementById('point_comment').focus();
				return;
			}
			if(document.getElementById('deduct').value.trim()!='')
			{
				if(Number(document.getElementById('deduct').value.trim())){
					document.myform.submit();
				}else{
					alert('กรุณาระบุคะแนนพฤติกรรมที่ต้องการหักให้ถูกต้อง');
					document.getElementById('deduct').focus();
				}
				
			}
			else
			{
				alert('กรุณาระบุคะแนนพฤติกรรมที่ต้องการหักให้ถูกต้อง');
				document.getElementById('deduct').focus();
			}
	}
</script>


<?php
	if(isset($_POST['delete'])){
		$_sql_del = "
			DELETE from student_point_history
			WHERE
				point_id = '" . $_POST['point_id'] . "' ";
		$_res_del = mysqli_query($_connection,$_sql_del);
		if($_res_del){
			$_sql_return_point = "";

			if($_POST['point_type']==1){
				$_sql_return_point = "
					UPDATE students
					set
						points = points - " . $_POST['point_changed'] . " 
					WHERE
						ID = '" . $_POST['student_id'] . "' 
						and xedbe = '" . $_POST['acadyear'] . "' 
				";
			}else{
				$_sql_return_point = "
				UPDATE students
				set
					points = points + " . $_POST['point_changed'] . " 
				WHERE
					ID = '" . $_POST['student_id'] . "' 
					and xedbe = '" . $_POST['acadyear'] . "' 
				";
			}
			$_res_return = mysqli_query($_connection,$_sql_return_point);

		}
	}
?>


<? $_flag = 2; ?>
<? if(isset($_POST['deduct'])){
	$_sql = "update students 
				set points = '" . ($_POST['oldPoints'] + $_POST['deduct']) . "' 
					where id = '" . $_POST['studentid'] . "' and 
			 	  xedbe = '" . $acadyear . "'";
	 if(mysqli_query($_connection,$_sql)){
		 $_flag = 1;
		 $_point_changed = 0;
		 $_point_type = 0;
		 if(trim($_POST['deduct'])>=0){
			$_point_type = 1;
			$_point_changed = trim($_POST['deduct']);
		 }else{
			$_point_changed = trim($_POST['deduct']) * -1;
		 }

		 $_sql_insert_history = "
					INSERT INTO `student_point_history`(
						`student_id`,
						`point_type`,
						`point_changed`,
						`acadyear`,
						`acadsemester`,
						`point_comments`,
						`created_datetime`,
						`created_user`,
						`updated_datetime`,
						`updated_user`
					)
					VALUES(
						'". $_POST['studentid'] . "',
						'" . $_point_type . "',
						'" . $_point_changed . "', 
						'" . $acadyear ."',
						'" . $acadsemester ."',
						'" . trim($_POST['point_comment']) ."',
						CURRENT_TIMESTAMP,
						'" . $_SESSION['user_account_id'] . "',
						CURRENT_TIMESTAMP,
						'" . $_SESSION['user_account_id'] . "'
					)
		 	";
		$_res_insert_his = mysqli_query($_connection,$_sql_insert_history);
	 }
	 else $_flag = 0;
	 //echo $_sql;
	}
?>

<div id="content">

<table width="100%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_discipline/index"><img src="../images/discipline.png" alt="" width="48" height="48" border="0"/></a></td>
      <td ><strong><font color="#990000" size="4">งานวินัยนักเรียน</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>1.7 ตัดคะแนนความประพฤติ</strong></font></span></td>
      <td align="right">
		<form method="post" autocomplete="off"> 
			ปีการศึกษา <?=$acadyear?> ภาคเรียนที่ <?=$acadsemester?> <br/>
			<font color="#000000" size="2"  > 
			เลขประจำตัวนักเรียน
			<input type="text" name="studentid" maxlength="5" value="<?=isset($_POST['studentid'])?$_POST['studentid']:""?>" size="5" class="inputboxUpdate" onKeyPress="return isNumberKey(event)"/>
			<input type="submit" name="action" value="เรียกดู" class="button" />
			</font>
		</form>
	  </td>
    </tr>
  </table>

<? if(isset($_POST['action']) && $_POST['studentid'] == ""){ ?>
	<center><br/><font color="#FF0000">กรุณาป้อน เลขประจำตัวนักเรียนก่อน !</font></center>
<? } ?>


<? if(isset($_POST['action']) && $_POST['studentid'] != ""){ ?>
	<? $sql = "select id,prefix,firstname,lastname,nickname,xlevel,xyearth,room,sex,f_name,m_name,a_name,a_mobile,studstatus,points from students where id = '" . $_POST['studentid'] . "' and xedbe = '" . $acadyear . "'" ; ?>
	<? $result = mysqli_query($_connection,$sql);?>
	<? if($result && mysqli_num_rows($result) > 0) { ?>
    	 <? $_dat = mysqli_fetch_assoc($result); ?>
		 <div align="center">
			<form name="myform"  method="post" autocomplete="off">
			<table  align="center" cellspacing="1" class="admintable" border="0" cellpadding="0">
				<tr>
					<td colspan="3" height="30px" class="key">ข้อมูลนักเรียนและรายการหักคะแนนความประพฤตินักเรียน </td>
				</tr>
				<tr>
					<td width="200px" align="right">เลขประจำตัว :</td>
					<td width="320px"><?=$_dat['id']?></td>
					<td rowspan="8" align="right">
						<img src="../images/studphoto/id<?=$_POST['studentid']?>.jpg" width="140px" 
							 height="200px" alt="รูปนักเรียน" align="top" style="border:solid 1px #003399"/>
					</td>
				</tr>
				<tr>
					<td align="right">ชื่อ - สกุล :</td>
					<td><?=$_dat['prefix'].$_dat['firstname'].' '. $_dat['lastname']?></td>
				</tr>
				<tr>
					<td align="right">ระดับชั้นมัธยมศึกษาปีที่ :</td>
					<td><?=displayRoomTable($_dat['xlevel'],$_dat['xyearth']).'/'.$_dat['room']?></td>
				</tr>
				<tr>
					<td align="right">คะแนนความประพฤติปัจจุบัน :</td>
					<td><?=displayPoint($_dat['points'])?></td>
				</tr>
				<tr>
					<td align="right">สถานภาพปัจจุบัน :</td>
					<td><?=displayStudentStatusColor($_dat['studstatus'])?></td>
				</tr>
						<? if($_flag==1){ ?>
								<tr>
									<td></td>
									<td colspan="2">
										<b>รายละเอียดผลการดำเนินการ</b><br/>
										คะแนนเดิม <?=displayPoint($_POST['oldPoints'])?> คะแนน <br/>
										คะแนนที่เพิ่ม/ลด <?=displayPoint($_POST['deduct'])?> คะแนน <br/>
										คงเหลือ <?=displayPoint($_dat['points'])?> คะแนน
									</td>
								</tr>
						<? } else if ($_flag==0) { ?>
								<tr>
									<td></td>
									<td colspan="2">
										<font color="#cc0000">
											เกิดข้อผิดพลาดระหว่างประมวลผลเนื่องจาก : <?=mysqli_error($_connection);?>
										</font>
									</td>
								</tr>
						<? } ?>            
				<tr>
					<td align="right">คะแนนพฤติกรรมที่เพิ่ม/ลด :</td>
					<td>
						<input type="text" id="deduct" name="deduct" size="5" maxlength="3" class="noborder" style="color:#cc0000 !important;font-size::14px !important;font-weight:bold !important;" />
						<font color='red'>*</font>
						(+10 เพิ่ม, -10 ลด)
						<input type="hidden" name="studentid" value="<?=$_dat['id']?>" />
						<input type="hidden" name="oldPoints" value="<?=$_dat['points']?>" />
						<input type="hidden" name="action" />
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">สาเหตุ :</td>
					<td>
						<textarea id="point_comment" name="point_comment" class="inputboxUpdate"></textarea><font color='red'>*</font>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<br/>
						<input type="button" name="save" value="บันทึก" class="button" onclick="checkDeduct()" />
						<input type="reset" value="ยกเลิก" class="button" />
					</td>
				</tr>
			</table>
			</form>
		</div>
		<?php
			$_sql_history = "
					select 
						s.*,
						t.prefix,
						t.firstname,
						t.lastname
					from 
						student_point_history s left join teachers t 
						on (s.created_user = t.teacher_id)
					where 
						s.student_id = '" . $_POST['studentid'] ."'
						and s.acadyear = '" . $acadyear . "'
					order by s.acadyear,s.created_datetime
				";
			$_res_his = mysqli_query($_connection,$_sql_history);
			$_ii = 1;

		?>
		<? if(mysqli_num_rows($_res_his)>0){  ?>
			<div align="center">
				<table class="admintable">
					<tr>
						<td colspan="7">
							<b>ประวัติคะแนนพฤติกรรมของนักเรียน</b>
						</td>
					</tr>
					<tr>
						<td class="key" align="center" width="30px">ที่</td>
						<td class="key" align="center" width="120px">วันที่</td>
						<td class="key" align="center" width="65px">ภาคเรียน</td>
						<td class="key" align="center" width="50px">คะแนน</td>
						<td class="key" align="center" width="250px">สาเหตุ</td>
						<td class="key" align="center" width="60px">คดีอ้างอิง</td>
						<td class="key" align="center" width="160px">โดย</td>
						<td class="key" align="center" colspan="2">-</td>
					</tr>
					<? while($_datH = mysqli_fetch_assoc($_res_his)) { ?> 
					<tr>
						<td valign="top" align="center"><?=$_ii++?></td>
						<td valign="top" align="center"><?=substr($_datH['updated_datetime'],0,16)?></td>
						<td valign="top" align="center"><?=$_datH['acadsemester'].'/'.$_datH['acadyear']?></td>
						<td valign="top" align="center">
							<?php
								if($_datH['point_type']==1){
									echo "<font color='green'>";
									echo $_datH['point_changed'];
									echo "</font>";
								}else{
									echo "<font color='red'>";
									echo $_datH['point_changed'];
									echo "</font>";
								}
							?>
						</td>
						<td valign="top"><?=$_datH['point_comments']?></td>
						<td valign="top"><?=$_datH['dis_id']?></td>
						<td valign="top"><?=$_datH['firstname']. ' ' .$_datH['lastname']?></td>
						<td valign="top" align="center">
							<? 
								$_permission = array("admin","tc100","tc101","tc102","tc103","tc111");

							?>
							<? if($_datH['dis_id']=="" && in_array(strtolower($_SESSION['username']),$_permission)){ ?>
								<form method="post">
									<input type="hidden" name="student_id"    value="<?=$_datH['student_id']?>" />
									<input type="hidden" name="point_id"      value="<?=$_datH['point_id']?>" />
									<input type="hidden" name="point_type"    value="<?=$_datH['point_type']?>" />
									<input type="hidden" name="point_changed" value="<?=$_datH['point_changed']?>" />
									<input type="hidden" name="studentid"     value="<?=$_datH['student_id']?>" />
									<input type="hidden" name="acadyear"      value="<?=$_datH['acadyear']?>" />
									<input type="hidden" name="action" />
									<input type="submit" name="delete" value="ลบ" />
								</form>
							<? } else { echo "-"; } ?>
						</td>
					</tr>
					<? } ?>
				</table>
			</div>
		<? } ?>

	<?	} //end if - check_data
		else { ?>
				<center><br/><font color="red">ผิดพลาด ไม่พบข้อมูลที่ค้นหา กรุณาตรวจสอบเลขประจำตัวนักเรียนอีกครั้ง</font></center>
	<?	} //end-else?>	
<?	} // end_submit_search?>


</div>

