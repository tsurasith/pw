
<?php
	

	$_absent_id = "";
	if(isset($_POST['absent_id'])){
		$_absent_id = $_POST['absent_id'];
	}

	if($_absent_id != ""){
		$_sql = "
			UPDATE hr_staff_absent
			SET
				request_status    = 'ส่งต่องานบุคลากรแล้ว',
				updated_datetime  = CURRENT_TIMESTAMP,
				updated_user      = '" . $_SESSION['user_account_id'] . "' 
			WHERE
				absent_id = '" . $_absent_id . "' 
		";


		$_res = mysqli_query($_connection,$_sql);
		if($_res){
			header("Location: index.php?option=module_hr/AddApproval&absent_id=".$_absent_id);
		}else{
			echo "เกิดข้อผิดพลาด เนื่องจาก " . mysqli_error($_connection);
		}
	}

?>



