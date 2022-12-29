<div id="content">

<table width="98%"  align="center" border="0" cellspacing="10" cellpadding="0"  class="header">
    <tr> 
      <td width="6%" align="center"><a href="index.php?option=module_gpa/index"><img src="../images/gpa.png" alt="" width="48" height="48" border="0" /></a></td>
      <td ><strong><font color="#990000" size="4">Learning Achievement</font></strong><br />
        <span class="normal"><font color="#0066FF"><strong>รายละเอียดผลการศึกษาของนักเรียนรายบุคคล</strong></font></span></td>
      <td align="right">
        <?php
          $_previospage = "index.php?option=module_gpa/";
          $_previospage .= isset($_REQUEST['previouspage'])?$_REQUEST['previouspage']:"";

          //$_previospage = "index.php?option=module_800/studentList";

        ?>
        <form name="back" method="post" action="index.php?option=module_gpa/index">
        	<input type="button" class="button" value="ย้อนกลับ" name="search" onclick="location.href='index.php?option=module_gpa/index';" >
        </form>
      </td>
    </tr>
  </table>

  <?php

	  $_resSemester = mysqli_query($_connection,"select distinct concat(acadsemester,'/',acadyear) as display,acadsemester,acadyear,level 
									from learn_grades where studentCode = '" . $_SESSION['username'] . "' order by acadyear ,acadsemester ");
  ?>

<div align="center">
      <table class="admintable"  cellpadding="1" cellspacing="1" border="0" align="center">
        <tr> 
          <th colspan="5" align="left">
                <img src="../images/school_logo.png" width="120px"><br/>
          </th>
          <th colspan="5" align="right">
          	<?="<img src='../images/studphoto/id" . $_SESSION['username'] . ".jpg' height='140px' width='100px' alt='รูปถ่ายนักเรียน' style='border:1px solid #000000'/>"?>
          </th>
        </tr>
        <tr>
          <th colspan="10">
            <?php

              $_sqlStudent = "select id,prefix,firstname,lastname,xlevel,xyearth,room,xedbe from students where id = '" . $_SESSION['username'] . "' order by xedbe desc";
              $_datStudent = mysqli_fetch_assoc(@mysqli_query($_connection,$_sqlStudent));
              $xlevel = $_datStudent['xlevel'];
              $xyearth = $_datStudent['xyearth'];
              $room = $_datStudent['room'];
            ?>
                ผลสัมฤทธิ์ทางการเรียน<br/>
                ภาคเรียนที่ <?=$acadsemester;?> ปีการศึกษา <?=$acadyear;?><br/>
                ของ <?=$_SESSION['name']?> เลขประจำตัว <?=$_SESSION['username']?> นักเรียนชั้นมัธยมศึกษาปีที่ <?=$xlevel==3?$xyearth:($xyearth+3)?>/<?=$room?><br/>
          </th>
        </tr>
        <tr> 
            <td class="key" width="70px" align="center">ปีการศึกษา</td>
            <td class="key" width="45px" align="center">ภาคเรียน</td>
            <td class="key" width="90px" align="center" >รหัสวิชา</td>
            <td class="key" width="200px" align="center" >รายวิชา</td>
            <td class="key" width="90px"  align="center" >หนวยการเรียน</td>
            <td class="key" width="70px" align="center">คะแนน</td>
            <td class="key" width="90px" align="center" >ผลการเรียน</td>
            <td class="key" width="90px" align="center" >ผลการเรียนเดิม</td>
            <td class="key" width="80px" align="center" >หมายเหตุ</td>            
        </tr>
        <? while($_Term = mysqli_fetch_assoc($_resSemester)){ ?>
        <? 	$_sql =   "select 
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
						  a.acadsemester = '" . $_Term['acadsemester'] . "' and
						  a.acadyear = '" . $_Term['acadyear'] . "' and
              a.level = '" . $_Term['level'] . "' 
					order by a.acadyear desc,a.acadsemester desc, b.firstgroup ,a.subjectcode"; 
		  			// echo $_sql;
		 			 @$_result = mysqli_query($_connection,$_sql);
					 
					 $_credit = 0.0;
					 $_unitPoint = 0.0;
					 $_gpa = "";

                     $_xYear = "";
                     $_xSemester = "";
                                ?>
                                <? while($_dat = mysqli_fetch_assoc($_result)) { ?>
                                    <tr>
                                        <td align="center"><?=$_dat['acadyear']==$_xYear?"":$_dat['acadyear']?></td>
                                        <td align="center"><?=$_dat['acadsemester']==$_xSemester?"":$_dat['acadsemester']?></td>
                                        <td style="padding-left:20px;"><?=$_dat['subjectcode']?></td>
                                        <td><?=$_dat['subjectname']?></td>
                                        <td align="center"><?=$_dat['subjectunit']?></td>
                                        <td align="right" style="padding-right:25px;"><?=displayP100($_dat['score100'])?></td>
                                        <td align="center"><b><?=$_dat['regrade']!=""?displayGrade($_dat['regrade']):displayGrade($_dat['grade'])?></b></td>
                                        <td align="center"><b><?=$_dat['regrade']==""?"":displayGrade($_dat['grade'])?></b></td>
                                        <td align="center">
                                            <? 	$_fileAttached = $_SERVER["DOCUMENT_ROOT"] . "/pk/grades/"; ?>
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
                                        <?
											$_credit += $_dat['subjectunit'];
											if(is_numeric($_dat['grade']) && $_dat['grade'] > 0) { $_unitPoint += $_dat['grade'] * $_dat['subjectunit']; }
											else if (is_numeric($_dat['regrade']) && $_dat['regrade'] > 0) { $_unitPoint += $_dat['regrade'] * $_dat['subjectunit'];}
										?>
                                    </tr>
                                <? } ?>
                                <tr>
                                    <td class="key2"></td>
                                    <td class="key2"></td>
                                    <td class="key2"></td>
                                    <td class="key2" align="center" height="40px">ผลการเรียนเฉลี่ย</td>
                                    <td class="key2" align="center"><?=$_credit?></td>
                                    <td class="key2" align="center"><?="-"?></td>
                                    <td class="key2" align="center">
                                        <? @$_gpa = substr($_unitPoint/$_credit,0,4); ?>
                                        <?=@number_format($_gpa,2,'.',',')?>
                                    </td>
                                    <td class="key2" colspan="2"></td>
                                </tr>
          <? } // fetch each acadsemester?>
    </table>
</div>
</div>
