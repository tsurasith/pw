﻿<div id="content">

<table width="98%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>รายละเอียดผลการศึกษาที่มีผลการประเมินไม่ผ่าน</strong></font></span></td>
      <td align="right">
      <?php
          $_previospage = "index.php?option=module_gpa/";
          $_previospage .= isset($_REQUEST['previouspage'])?$_REQUEST['previouspage']:"";

          //$_previospage = "index.php?option=module_800/studentList";

          $_roomID = "";
          $_roomID = isset($_REQUEST['roomID'])?$_REQUEST['roomID']:"";


        ?>
        <form name="back" method="post" action="index.php?option=module_gpa/index">
        	<input type="button" class="button" value="ย้อนกลับ" name="search" onclick="location.href='index.php?option=module_gpa/index';" >
        </form>
      </td>
    </tr>
  </table>

  <?php

          $_sqlStudent = "select id,prefix,firstname,lastname,xlevel,xyearth,room,xedbe from students where id = '" . $_SESSION['username'] . "' order by xedbe desc";
          $_datStudent = mysqli_fetch_assoc(@mysqli_query($_connection,$_sqlStudent));
          $xlevel = $_datStudent['xlevel'];
          $xyearth = $_datStudent['xyearth'];
          $room = $_datStudent['room'];
	  
	  
	  $_sql =   "select 
              a.acadyear,
              a.acadsemester,
              a.subjectcode,
              b.subjectname,
              b.subjectunit,
              a.score100,
              a.grade,
              a.regrade,
              b.firstgroup
          from learn_grades a left outer join learn_subjects b 
              on (a.subjectcode = b.subjectcode and a.acadyear = b.acadyear and a.acadsemester = b.acadsemester
              and a.RegisterClass = b.SubjectClass )
          where a.studentcode = '" . $_SESSION['username'] . "' and 
				      trim(grade) in ('0','ร','มส','') 
				order by a.acadyear desc,a.acadsemester desc,b.firstgroup";

        

	  // echo $_sql;
	  @$_result = mysqli_query($_connection,$_sql);
	  $_no = 1;
  ?>

<div align="center">
      <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
        <tr> 
          <th colspan="5" align="left">
                <img src="../images/school_logo.png" width="120px"><br/>
          </th>
          <th colspan="5" align="right">
          	<?="<img src='../images/studphoto/id" . $_SESSION['username'] . ".jpg' height='140px' width='100px' alt='รูปถ่ายนักเรียน' style='border:1px solid #CC0CC0'/>"?>
          </th>
        </tr>
        <tr>
          <th colspan="10">
                ผลสัมฤทธิ์ทางการเรียนที่ไม่พึงประสงค์<br/>
                ภาคเรียนที่ <?=$acadsemester-1;?> ปีการศึกษา <?=$acadyear;?><br/>
                ของ <?=$_SESSION['name']?> เลขประจำตัว <?=$_SESSION['username']?> นักเรียนชั้นมัธยมศึกษาปีที่ <?=$xlevel==3?$xyearth:($xyearth+3)?>/<?=$room?><br/>
          </th>
        </tr>
        <tr> 
            <td class="key2" width="70px" align="center">ปีการศึกษา</td>
            <td class="key2" width="45px" align="center">ภาคเรียน</td>
            <td class="key2" width="80px" align="center" >รหัสวิชา</td>
            <td class="key2" width="200px" align="center" >รายวิชา</td>
            <td class="key2" width="80px"  align="center" >หนวยการเรียน</td>
            <td class="key2" width="80px" align="center">คะแนน</td>
            <td class="key2" width="80px" align="center" >ผลการเรียน</td>
            <td class="key2" width="80px" align="center" >ผลการแก้ไข</td>
            <td class="key2" width="80px" align="center" >หมายเหตุ</td>
        </tr>
        <?
			$_xYear = "";
			$_xSemester = "";
      $_check_rows = mysqli_num_rows($_result);
		?>
    <? if($_check_rows>0){ ?>
        <? while($_dat = mysqli_fetch_assoc($_result)) { ?>
	        <tr>
        		<td align="center"><?=$_dat['acadyear']==$_xYear?"":$_dat['acadyear']?></td>
                <td align="center"><?=$_dat['acadsemester']==$_xSemester && $_dat['acadyear']==$_xYear?"":$_dat['acadsemester']?></td>
                <td style="padding-left:20px;"><?=$_dat['subjectcode']?></td>
                <td><?=$_dat['subjectname']?></td>
                <td align="center"><?=$_dat['subjectunit']?></td>
                <td align="right" style="padding-right:25px;"><?=displayP100($_dat['score100'])?></td>
                <td align="center"><b><?=displayGrade($_dat['grade'])?></b></td>
                <td align="center"><b><?=$_dat['regrade']==""?"":displayGrade($_dat['regrade'])?></b></td>
                <td align="center">
                	<? 	$_fileAttached = $_SERVER["DOCUMENT_ROOT"] . "/bn/grades/"; ?>
					<? 	$_fileAttached .= $_dat['acadyear'].$_dat['acadsemester'].$_SESSION['username'].$_dat['firstgroup'].substr($_dat['subjectcode'],-5).".jpg"; ?>
                    <?  	 if($_dat['regrade']!="") {
								if(file_exists($_fileAttached)) {
									echo "<a target='_blank' href='module_gpa/displayFileAttached.php?id=" . substr($_fileAttached,-20) . "'>";
									echo "หลักฐาน";
									echo "</a>";
								}else { echo "ไม่มีหลักฐาน";}
							}
							else {}
					?>
                </td>
                
                
                <? $_xYear = $_dat['acadyear']; ?>
                <? $_xSemester = $_dat['acadsemester']; ?>
    	    </tr>
        <? } ?>
      <? } else { ?>
        <tr>
          <td colspan="10" align="center">
            <br/><br/>
            <font color='green'>
              นักเรียนเก่งมาก ไม่มีผลการเรียนที่ไม่พึงประสงค์
            </font>
            <br/></br>
          </td>
        </tr>
     <? } ?>
    </table>
</div>
</div>


