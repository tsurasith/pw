<?php
require_once("../../include/config.inc.php");
require_once("../../include/mysqli.php");
require_once("../../include/SimpleXLSXGen.php");

// export data
if(isset($_POST["go_to_export"]))
{
   
	$_file_name  = "LC_";
	$_file_name .= $_POST['acadyear'] . "_" . $_POST['acadsemester'] . "_";
	$_file_name .= $_POST['roomID'] . "_" . $_POST['SubjectCode'];
	$_file_name .= ".xls";


	$_roomID = $_POST['roomID'];
	$xlevel  = 0;
	$xyearth = 0;
	$_year   = (int) substr($_roomID,0,1);

	if($_year<4){
		$xlevel = 3;
		$xyearth = $_year;
	}else{
		$xlevel = 4;
		$xyearth = $_year-3;
	}

	$room = substr($_roomID,-1);

	// set header
    //header("Content-Type: application/vnd.ms-excel");
    //header("Content-Disposition: attachment; filename=\"$_file_name\"");

	// excel columns header
	$_columns_header = array('StudentID','รหัส','คำนำหน้า','ชื่อ','นามสกุล','คะแนน','เกรด');

	// data
	$_data = array();
	$_data[] = $_columns_header;

	$_sql_export = "
			select
				t.id,
				s.student_id,
				t.prefix,
				t.firstname,
				t.lastname,
				s.point_100,
				s.grade
			from 
				register_students s inner join students t
				on (
					 s.student_id = t.id and 
					 s.acadyear   = t.xedbe
					)
			where 
				s.SubjectCode  = '" . $_POST['SubjectCode'] . "' and 
				s.acadyear     = '" . $_POST['acadyear'] . "' and 
				s.acadsemester = '" . $_POST['acadsemester'] . "' and 
				t.xlevel       = '" . $xlevel . "' and
				t.xyearth      = '" . $xyearth . "' and 
				t.room         = '" . $room  . "'
		";
	$_res = mysqli_query($_connection,$_sql_export);
	
	while($_dat = mysqli_fetch_assoc($_res)){
		$_data[] = $_dat;
	}

	/*
	//test data
	$books = [
		['ISBN', 'title', 'author', 'publisher', 'ctry' ],
		[618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
		[908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
	];
	*/
	
	$xlsx = SimpleXLSXGen::fromArray($_data);
	$xlsx->saveAs("../../local_school_export_files" . "/" . $_file_name); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 

	echo "Done";


}
?>