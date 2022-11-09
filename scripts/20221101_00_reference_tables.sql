-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 08, 2022 at 10:16 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pnk`
--

-- --------------------------------------------------------

--
-- Table structure for table `drug_level`
--

DROP TABLE IF EXISTS `drug_level`;
CREATE TABLE IF NOT EXISTS `drug_level` (
  `level_id` varchar(2) NOT NULL,
  `level_description` varchar(255) NOT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `XLEVEL` varchar(1) NOT NULL,
  `ACADYEAR` varchar(4) NOT NULL,
  `ACADSEMESTER` varchar(1) NOT NULL,
  `PSUBJECTCODE` varchar(20) NOT NULL,
  `CLASS_ID` varchar(3) NOT NULL,
  `STUDENT_ID` varchar(10) NOT NULL,
  `P100` varchar(4) DEFAULT NULL,
  `GRADE` varchar(10) NOT NULL,
  `REGRADE` varchar(10) DEFAULT NULL,
  `TEACHER_ID` varchar(50) DEFAULT NULL,
  `UPDATE_USER` varchar(255) DEFAULT NULL,
  `UPDATE_DATETIME` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`XLEVEL`,`ACADYEAR`,`ACADSEMESTER`,`PSUBJECTCODE`,`STUDENT_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `project`
-- 

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` varchar(10) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  `detail` text NOT NULL,
  `purpose` varchar(1) NOT NULL,
  `start_date` varchar(10) NOT NULL,
  `finish_date` varchar(10) NOT NULL,
  `budget_type` varchar(2) NOT NULL,
  `budget_academic` varchar(255) NOT NULL,
  `budget_income` int(11) NOT NULL,
  `user_create` varchar(50) NOT NULL,
  PRIMARY KEY  (`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_budget`
--

DROP TABLE IF EXISTS `project_budget`;
CREATE TABLE IF NOT EXISTS `project_budget` (
  `budget_id` int(11) NOT NULL,
  `project_id` varchar(10) NOT NULL,
  `budget_type` varchar(2) NOT NULL,
  `budget_detail` varchar(255) NOT NULL,
  `prize_per_unit` float(12,2) NOT NULL,
  `amount` int(11) NOT NULL,
  `money` float(24,2) NOT NULL,
  PRIMARY KEY (`budget_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_comment`
--

DROP TABLE IF EXISTS `project_comment`;
CREATE TABLE IF NOT EXISTS `project_comment` (
  `id` int(11) NOT NULL,
  `project_id` varchar(10) NOT NULL,
  `comment_type` varchar(1) NOT NULL,
  `detail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_qa`
--

DROP TABLE IF EXISTS `project_qa`;
CREATE TABLE IF NOT EXISTS `project_qa` (
  `project_id` varchar(10) NOT NULL,
  `organize` varchar(2) NOT NULL,
  `standard` int(11) NOT NULL,
  `indexof` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`organize`,`standard`,`indexof`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_academic`
--

DROP TABLE IF EXISTS `ref_academic`;
CREATE TABLE IF NOT EXISTS `ref_academic` (
  `academic_id` varchar(1) NOT NULL,
  `academic_description` varchar(255) NOT NULL,
  PRIMARY KEY (`academic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_color`
--

DROP TABLE IF EXISTS `ref_color`;
CREATE TABLE IF NOT EXISTS `ref_color` (
  `color_id` varchar(2) NOT NULL,
  `color_description` varchar(255) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_cripple`
--

DROP TABLE IF EXISTS `ref_cripple`;
CREATE TABLE IF NOT EXISTS `ref_cripple` (
  `cripple_id` int(11) NOT NULL,
  `cripple_description` varchar(255) NOT NULL,
  PRIMARY KEY (`cripple_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_discipline`
--

DROP TABLE IF EXISTS `ref_discipline`;
CREATE TABLE IF NOT EXISTS `ref_discipline` (
  `dis_id` int(11) NOT NULL,
  `dis_type` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`dis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ref_disciplinelevel`
--

DROP TABLE IF EXISTS `ref_disciplinelevel`;
CREATE TABLE IF NOT EXISTS `ref_disciplinelevel` (
  `dis_levelid` int(11) NOT NULL,
  `dis_leveldetail` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`dis_levelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `ref_disciplinestatus`
--

DROP TABLE IF EXISTS `ref_disciplinestatus`;
CREATE TABLE IF NOT EXISTS `ref_disciplinestatus` (
  `status` int(11) NOT NULL,
  `status_detail` varchar(255) NOT NULL,
  PRIMARY KEY (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_drug`
--

DROP TABLE IF EXISTS `ref_drug`;
CREATE TABLE IF NOT EXISTS `ref_drug` (
  `drug_id` varchar(2) NOT NULL,
  `drug_description` varchar(255) NOT NULL,
  PRIMARY KEY (`drug_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_fmstatus`
--

DROP TABLE IF EXISTS `ref_fmstatus`;
CREATE TABLE IF NOT EXISTS `ref_fmstatus` (
  `fmstatus_id` int(11) NOT NULL,
  `fmstatus_description` varchar(255) NOT NULL,
  PRIMARY KEY (`fmstatus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_inservice`
--

DROP TABLE IF EXISTS `ref_inservice`;
CREATE TABLE IF NOT EXISTS `ref_inservice` (
  `service_id` int(11) NOT NULL,
  `service_description` varchar(255) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_moral`
--

DROP TABLE IF EXISTS `ref_moral`;
CREATE TABLE IF NOT EXISTS `ref_moral` (
  `moral_id` varchar(2) NOT NULL,
  `moral_description` varchar(255) NOT NULL,
  PRIMARY KEY (`moral_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_moraljoin`
--

DROP TABLE IF EXISTS `ref_moraljoin`;
CREATE TABLE IF NOT EXISTS `ref_moraljoin` (
  `moraljoin_id` varchar(2) NOT NULL,
  `moraljoin_description` varchar(255) NOT NULL,
  PRIMARY KEY (`moraljoin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_morallevel`
--

DROP TABLE IF EXISTS `ref_morallevel`;
CREATE TABLE IF NOT EXISTS `ref_morallevel` (
  `morallev_id` varchar(2) NOT NULL,
  `morallev_description` varchar(255) NOT NULL,
  PRIMARY KEY (`morallev_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_nationality`
--

DROP TABLE IF EXISTS `ref_nationality`;
CREATE TABLE IF NOT EXISTS `ref_nationality` (
  `nation_id` int(11) NOT NULL,
  `nation_description` varchar(255) NOT NULL,
  PRIMARY KEY (`nation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_nextacademic`
--

DROP TABLE IF EXISTS `ref_nextacademic`;
CREATE TABLE IF NOT EXISTS `ref_nextacademic` (
  `id` varchar(2) NOT NULL,
  `next_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_nextregion`
--

DROP TABLE IF EXISTS `ref_nextregion`;
CREATE TABLE IF NOT EXISTS `ref_nextregion` (
  `id` varchar(2) NOT NULL,
  `next_region` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_occupation`
--

DROP TABLE IF EXISTS `ref_occupation`;
CREATE TABLE IF NOT EXISTS `ref_occupation` (
  `occ_id` varchar(2) NOT NULL,
  `occ_description` varchar(255) NOT NULL,
  `occg_id` varchar(2) NOT NULL,
  `occg_description` varchar(255) NOT NULL,
  PRIMARY KEY (`occ_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_prefix`
--

DROP TABLE IF EXISTS `ref_prefix`;
CREATE TABLE IF NOT EXISTS `ref_prefix` (
  `prefix_id` varchar(2) NOT NULL,
  `prefix_detail` varchar(255) NOT NULL,
  PRIMARY KEY (`prefix_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_province`
--

DROP TABLE IF EXISTS `ref_province`;
CREATE TABLE IF NOT EXISTS `ref_province` (
  `prov_id` varchar(2) NOT NULL,
  `prov_description` varchar(255) NOT NULL,
  PRIMARY KEY (`prov_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_race`
--

DROP TABLE IF EXISTS `ref_race`;
CREATE TABLE IF NOT EXISTS `ref_race` (
  `race_id` int(11) NOT NULL,
  `race_description` varchar(255) NOT NULL,
  PRIMARY KEY (`race_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_relation`
--

DROP TABLE IF EXISTS `ref_relation`;
CREATE TABLE IF NOT EXISTS `ref_relation` (
  `relation_id` varchar(2) NOT NULL,
  `relation_description` varchar(255) NOT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_religion`
--

DROP TABLE IF EXISTS `ref_religion`;
CREATE TABLE IF NOT EXISTS `ref_religion` (
  `religion_id` int(11) NOT NULL,
  `religion_description` varchar(255) NOT NULL,
  PRIMARY KEY (`religion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_retire`
--

DROP TABLE IF EXISTS `ref_retire`;
CREATE TABLE IF NOT EXISTS `ref_retire` (
  `retire_id` int(11) NOT NULL,
  `retire_description` varchar(255) NOT NULL,
  PRIMARY KEY (`retire_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_sanction`
--

DROP TABLE IF EXISTS `ref_sanction`;
CREATE TABLE IF NOT EXISTS `ref_sanction` (
  `sanc_id` varchar(2) NOT NULL,
  `sanc_detail` varchar(255) NOT NULL,
  PRIMARY KEY (`sanc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_scout`
--

DROP TABLE IF EXISTS `ref_scout`;
CREATE TABLE IF NOT EXISTS `ref_scout` (
  `scout_id` int(11) NOT NULL,
  `scout_description` varchar(255) NOT NULL,
  PRIMARY KEY (`scout_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_studabsent`
--

DROP TABLE IF EXISTS `ref_studabsent`;
CREATE TABLE IF NOT EXISTS `ref_studabsent` (
  `absent_id` int(11) NOT NULL,
  `absent_description` varchar(255) NOT NULL,
  PRIMARY KEY (`absent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_studjudge`
--

DROP TABLE IF EXISTS `ref_studjudge`;
CREATE TABLE IF NOT EXISTS `ref_studjudge` (
  `judge_id` int(11) NOT NULL,
  `judge_description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_studstatus`
--

DROP TABLE IF EXISTS `ref_studstatus`;
CREATE TABLE IF NOT EXISTS `ref_studstatus` (
  `studstatus` int(11) NOT NULL,
  `studstatus_description` varchar(255) NOT NULL,
  PRIMARY KEY (`studstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_tambol`
--

DROP TABLE IF EXISTS `ref_tambol`;
CREATE TABLE IF NOT EXISTS `ref_tambol` (
  `tambol_id` varchar(6) NOT NULL,
  `tumbol_description` varchar(255) NOT NULL,
  `amphur_id` varchar(4) NOT NULL,
  `amphur_description` varchar(255) NOT NULL,
  `prov_id` varchar(2) NOT NULL,
  `prov_description` varchar(255) NOT NULL,
  PRIMARY KEY (`tambol_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ref_timecheck`
--

DROP TABLE IF EXISTS `ref_timecheck`;
CREATE TABLE IF NOT EXISTS `ref_timecheck` (
  `CHECK_ID` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'รหัสเวลาเข้าเช็คชื่อ',
  `CHECK_DESCRIPTION` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT 'คำอธิบาย',
  `CHECK_POINTMINUS` int(10) DEFAULT NULL COMMENT 'ระดับการหักคะแนนความประพฤติ',
  PRIMARY KEY (`CHECK_ID`),
  KEY `TIMEID` (`CHECK_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=tis620;

-- --------------------------------------------------------

--
-- Table structure for table `ref_travel`
--

DROP TABLE IF EXISTS `ref_travel`;
CREATE TABLE IF NOT EXISTS `ref_travel` (
  `travel_id` varchar(2) NOT NULL,
  `travel_description` varchar(255) NOT NULL,
  PRIMARY KEY (`travel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` varchar(3) NOT NULL,
  `teacher_id` varchar(3) NOT NULL,
  `teacher_id2` varchar(3) NOT NULL DEFAULT '704',
  `student_id` varchar(10) DEFAULT NULL,
  `acadsemester` int(11) NOT NULL,
  `acadyear` int(11) NOT NULL,
  PRIMARY KEY (`room_id`,`acadsemester`,`acadyear`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sdq_parent`
--

DROP TABLE IF EXISTS `sdq_parent`;
CREATE TABLE IF NOT EXISTS `sdq_parent` (
  `student_id` varchar(5) NOT NULL,
  `c01` int(11) DEFAULT NULL,
  `c02` int(11) DEFAULT NULL,
  `c03` int(11) DEFAULT NULL,
  `c04` int(11) DEFAULT NULL,
  `c05` int(11) DEFAULT NULL,
  `c06` int(11) DEFAULT NULL,
  `c07` int(11) DEFAULT NULL,
  `c08` int(11) DEFAULT NULL,
  `c09` int(11) DEFAULT NULL,
  `c10` int(11) DEFAULT NULL,
  `c11` int(11) DEFAULT NULL,
  `c12` int(11) DEFAULT NULL,
  `c13` int(11) DEFAULT NULL,
  `c14` int(11) DEFAULT NULL,
  `c15` int(11) DEFAULT NULL,
  `c16` int(11) DEFAULT NULL,
  `c17` int(11) DEFAULT NULL,
  `c18` int(11) DEFAULT NULL,
  `c19` int(11) DEFAULT NULL,
  `c20` int(11) DEFAULT NULL,
  `c21` int(11) DEFAULT NULL,
  `c22` int(11) DEFAULT NULL,
  `c23` int(11) DEFAULT NULL,
  `c24` int(11) DEFAULT NULL,
  `c25` int(11) DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `finish_date` date DEFAULT NULL,
  PRIMARY KEY (`student_id`,`semester`,`acadyear`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ตารางประเมิน SDQ นักเรียนสำหรับผู้ปกครอง';

-- --------------------------------------------------------

--
-- Table structure for table `sdq_result`
--

DROP TABLE IF EXISTS `sdq_result`;
CREATE TABLE IF NOT EXISTS `sdq_result` (
  `student_id` varchar(5) NOT NULL,
  `type1` int(11) NOT NULL,
  `type2` int(11) NOT NULL,
  `type3` int(11) NOT NULL,
  `type4` int(11) NOT NULL,
  `type5` int(11) NOT NULL,
  `more_detail` text NOT NULL,
  `all` int(11) NOT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  `questioner` varchar(255) NOT NULL,
  PRIMARY KEY (`student_id`,`acadyear`,`acadsemester`,`questioner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sdq_student`
--

DROP TABLE IF EXISTS `sdq_student`;
CREATE TABLE IF NOT EXISTS `sdq_student` (
  `student_id` varchar(5) NOT NULL,
  `c01` int(11) DEFAULT NULL,
  `c02` int(11) DEFAULT NULL,
  `c03` int(11) DEFAULT NULL,
  `c04` int(11) DEFAULT NULL,
  `c05` int(11) DEFAULT NULL,
  `c06` int(11) DEFAULT NULL,
  `c07` int(11) DEFAULT NULL,
  `c08` int(11) DEFAULT NULL,
  `c09` int(11) DEFAULT NULL,
  `c10` int(11) DEFAULT NULL,
  `c11` int(11) DEFAULT NULL,
  `c12` int(11) DEFAULT NULL,
  `c13` int(11) DEFAULT NULL,
  `c14` int(11) DEFAULT NULL,
  `c15` int(11) DEFAULT NULL,
  `c16` int(11) DEFAULT NULL,
  `c17` int(11) DEFAULT NULL,
  `c18` int(11) DEFAULT NULL,
  `c19` int(11) DEFAULT NULL,
  `c20` int(11) DEFAULT NULL,
  `c21` int(11) DEFAULT NULL,
  `c22` int(11) DEFAULT NULL,
  `c23` int(11) DEFAULT NULL,
  `c24` int(11) DEFAULT NULL,
  `c25` int(11) DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `finish_date` date DEFAULT NULL,
  PRIMARY KEY (`student_id`,`semester`,`acadyear`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ตารางประเมิน SDQ นักเรียน';

-- --------------------------------------------------------

--
-- Table structure for table `sdq_teacher`
--

DROP TABLE IF EXISTS `sdq_teacher`;
CREATE TABLE IF NOT EXISTS `sdq_teacher` (
  `student_id` varchar(5) NOT NULL,
  `teacher_id` varchar(3) NOT NULL,
  `c01` int(11) DEFAULT NULL,
  `c02` int(11) DEFAULT NULL,
  `c03` int(11) DEFAULT NULL,
  `c04` int(11) DEFAULT NULL,
  `c05` int(11) DEFAULT NULL,
  `c06` int(11) DEFAULT NULL,
  `c07` int(11) DEFAULT NULL,
  `c08` int(11) DEFAULT NULL,
  `c09` int(11) DEFAULT NULL,
  `c10` int(11) DEFAULT NULL,
  `c11` int(11) DEFAULT NULL,
  `c12` int(11) DEFAULT NULL,
  `c13` int(11) DEFAULT NULL,
  `c14` int(11) DEFAULT NULL,
  `c15` int(11) DEFAULT NULL,
  `c16` int(11) DEFAULT NULL,
  `c17` int(11) DEFAULT NULL,
  `c18` int(11) DEFAULT NULL,
  `c19` int(11) DEFAULT NULL,
  `c20` int(11) DEFAULT NULL,
  `c21` int(11) DEFAULT NULL,
  `c22` int(11) DEFAULT NULL,
  `c23` int(11) DEFAULT NULL,
  `c24` int(11) DEFAULT NULL,
  `c25` int(11) DEFAULT NULL,
  `semester` int(11) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `finish_date` date DEFAULT NULL,
  PRIMARY KEY (`student_id`,`semester`,`acadyear`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ตารางประเมิน SDQ นักเรียนสำหรับครูที่ปรึกษา';

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `ID` varchar(5) NOT NULL DEFAULT '',
  `PREFIX` varchar(20) DEFAULT NULL,
  `FIRSTNAME` varchar(30) DEFAULT NULL,
  `LASTNAME` varchar(30) DEFAULT NULL,
  `SCH_ID` varchar(8) DEFAULT NULL,
  `xLevel` varchar(1) NOT NULL DEFAULT '',
  `xYearth` varchar(1) NOT NULL DEFAULT '',
  `xEDBE` varchar(4) NOT NULL DEFAULT '',
  `ExEDBE` varchar(4) DEFAULT NULL,
  `EnglishNAME` varchar(60) DEFAULT NULL,
  `NICKNAME` varchar(30) DEFAULT NULL,
  `SEX` varchar(1) DEFAULT NULL,
  `PROGRAM` varchar(30) DEFAULT NULL,
  `ROOM` smallint(5) DEFAULT NULL,
  `ORDINAL` smallint(5) DEFAULT NULL,
  `pin` varchar(13) DEFAULT NULL,
  `BIRTHDAY` varchar(10) DEFAULT NULL,
  `Race` smallint(5) DEFAULT NULL,
  `Nationality` smallint(5) DEFAULT NULL,
  `RELIGION` varchar(1) DEFAULT NULL,
  `TALENT` text,
  `ENT_YEAR` varchar(10) DEFAULT NULL,
  `ENT_DATE` varchar(10) DEFAULT NULL,
  `SCH_NAME` varchar(50) DEFAULT NULL,
  `SCH_PROVINCE` varchar(25) DEFAULT NULL,
  `SCH_LEVEL` varchar(30) DEFAULT NULL,
  `SCH_CAUSE` varchar(50) DEFAULT NULL,
  `SCH_Unit` float(24,2) DEFAULT NULL,
  `SCH_Pass` float(24,2) DEFAULT NULL,
  `SCH_GPA` float(24,2) DEFAULT NULL,
  `SUBJECT_LIKE` varchar(20) DEFAULT NULL,
  `SUBJECT_HATE` varchar(20) DEFAULT NULL,
  `F_NAME` varchar(50) DEFAULT NULL,
  `M_NAME` varchar(50) DEFAULT NULL,
  `A_NAME` varchar(50) DEFAULT NULL,
  `p_village` varchar(30) DEFAULT NULL,
  `P_HOME` varchar(30) DEFAULT NULL,
  `P_NO` varchar(15) DEFAULT NULL,
  `P_GROUP` varchar(6) DEFAULT NULL,
  `P_SOI` varchar(30) DEFAULT NULL,
  `P_STREET` varchar(30) DEFAULT NULL,
  `P_TUMBOL` varchar(30) DEFAULT NULL,
  `P_AMPHUR` varchar(30) DEFAULT NULL,
  `P_PROVINCE` varchar(25) DEFAULT NULL,
  `P_ZIP` varchar(5) DEFAULT NULL,
  `P_PHONE` varchar(30) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `e_mail` varchar(30) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `WEIGHT` float(24,2) DEFAULT NULL,
  `HEIGHT` float(24,2) DEFAULT NULL,
  `BMI` float(24,2) DEFAULT NULL,
  `CRIPPLE` smallint(5) DEFAULT NULL,
  `BTAMBOL` varchar(30) DEFAULT NULL,
  `BAMPHUR` varchar(30) DEFAULT NULL,
  `BPROVINCE` varchar(25) DEFAULT NULL,
  `BHOSPITAL` varchar(30) DEFAULT NULL,
  `F_EARN` float(24,2) DEFAULT NULL,
  `F_Occupation` smallint(5) DEFAULT NULL,
  `F_Mobile` varchar(30) DEFAULT NULL,
  `M_EARN` float(24,2) DEFAULT NULL,
  `M_Occupation` smallint(5) DEFAULT NULL,
  `M_Mobile` varchar(30) DEFAULT NULL,
  `All_EARN` float(24,2) DEFAULT NULL,
  `FM_Status` varchar(1) DEFAULT NULL,
  `A_EARN` float(24,2) DEFAULT NULL,
  `A_Occupation` smallint(5) DEFAULT NULL,
  `A_Mobile` varchar(30) DEFAULT NULL,
  `A_Relation` smallint(5) DEFAULT NULL,
  `GPA` float(24,2) DEFAULT NULL,
  `PR` float(24,2) DEFAULT NULL,
  `advisor11` varchar(3) DEFAULT NULL,
  `advisor12` varchar(3) DEFAULT NULL,
  `advisor21` varchar(3) DEFAULT NULL,
  `advisor22` varchar(3) DEFAULT NULL,
  `advisor31` varchar(3) DEFAULT NULL,
  `advisor32` varchar(3) DEFAULT NULL,
  `TrustTeacher1` varchar(3) DEFAULT NULL,
  `TrustTeacher2` varchar(3) DEFAULT NULL,
  `TrustTeacher3` varchar(3) DEFAULT NULL,
  `education_loan1` int(10) DEFAULT NULL,
  `education_loan2` int(10) DEFAULT NULL,
  `education_loan3` int(10) DEFAULT NULL,
  `Scout` smallint(5) DEFAULT NULL,
  `ISSUE` varchar(10) DEFAULT NULL,
  `LEAVE` varchar(10) DEFAULT NULL,
  `CAUSE` varchar(255) DEFAULT NULL,
  `studnote` varchar(255) DEFAULT NULL,
  `studstatus` varchar(1) DEFAULT NULL,
  `EPREFIX` varchar(20) DEFAULT NULL,
  `EFIRSTNAME` varchar(30) DEFAULT NULL,
  `ELASTNAME` varchar(30) DEFAULT NULL,
  `ENationality` varchar(25) DEFAULT NULL,
  `ERELIGION` varchar(25) DEFAULT NULL,
  `ESCH_NAME` varchar(50) DEFAULT NULL,
  `ESCH_COUNTRY` varchar(30) DEFAULT NULL,
  `ESCH_GRADE` varchar(30) DEFAULT NULL,
  `EF_NAME` varchar(50) DEFAULT NULL,
  `EM_NAME` varchar(50) DEFAULT NULL,
  `EA_NAME` varchar(50) DEFAULT NULL,
  `EP_ADDRESS1` varchar(50) DEFAULT NULL,
  `EP_ADDRESS2` varchar(50) DEFAULT NULL,
  `EP_PROVINCE` varchar(30) DEFAULT NULL,
  `EP_COUNTRY` varchar(30) DEFAULT NULL,
  `EPlaceOfBirth` varchar(50) DEFAULT NULL,
  `ECountryOfBirth` varchar(30) DEFAULT NULL,
  `ESTUDNOTE` text,
  `Start_Date` varchar(10) DEFAULT NULL,
  `Expire_Date` varchar(10) DEFAULT NULL,
  `InService` varchar(1) DEFAULT NULL,
  `InTR_14` varchar(1) DEFAULT NULL,
  `StudAbsent` int(10) DEFAULT NULL,
  `retirecause` int(10) DEFAULT NULL,
  `NextAcademic` int(10) DEFAULT NULL,
  `NextRegion` int(10) DEFAULT NULL,
  `StudJudge` int(10) DEFAULT NULL,
  `F_Cripple` int(10) DEFAULT NULL,
  `F_Status` varchar(10) DEFAULT NULL,
  `M_Cripple` int(10) DEFAULT NULL,
  `M_Status` varchar(10) DEFAULT NULL,
  `travelby` varchar(2) NOT NULL DEFAULT '01',
  `HowLong` int(10) DEFAULT NULL,
  `Dental` varchar(10) DEFAULT NULL,
  `Scholar_Status` varchar(10) DEFAULT NULL,
  `ScholarName1` varchar(255) DEFAULT NULL,
  `ScholarName2` varchar(255) DEFAULT NULL,
  `ScholarName3` varchar(255) DEFAULT NULL,
  `GPA1` float(24,2) DEFAULT NULL,
  `GPA2` float(24,2) DEFAULT NULL,
  `GPA3` float(24,2) DEFAULT NULL,
  `Change_Date` varchar(50) DEFAULT NULL,
  `OLD_FIRSTNAME` varchar(50) DEFAULT NULL,
  `OLD_LASTNAME` varchar(50) DEFAULT NULL,
  `F_PIN` varchar(13) DEFAULT NULL,
  `M_PIN` varchar(13) DEFAULT NULL,
  `A_PIN` varchar(13) DEFAULT NULL,
  `UTM_Coordinate_X` varchar(20) DEFAULT NULL,
  `UTM_Coordinate_Y` varchar(20) DEFAULT NULL,
  `UTM_Zone` varchar(10) DEFAULT '+7GMT',
  `Points` int(11) NOT NULL DEFAULT '100',
  `Color` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`,`xLevel`,`xYearth`,`xEDBE`),
  KEY `pin` (`pin`),
  KEY `SCH_ID` (`SCH_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_800`
--

DROP TABLE IF EXISTS `student_800`;
CREATE TABLE IF NOT EXISTS `student_800` (
  `row_id` varchar(20) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `class_id` varchar(3) NOT NULL,
  `timecheck_id` varchar(2) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  `user_create` varchar(50) NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_800_task`
--

DROP TABLE IF EXISTS `student_800_task`;
CREATE TABLE IF NOT EXISTS `student_800_task` (
  `task_id` int(11) NOT NULL,
  `task_date` varchar(20) NOT NULL,
  `task_roomid` varchar(3) NOT NULL,
  `task_status` varchar(2) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_color`
--

DROP TABLE IF EXISTS `student_color`;
CREATE TABLE IF NOT EXISTS `student_color` (
  `row_id` varchar(20) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `class_id` varchar(3) NOT NULL,
  `timecheck_id` varchar(2) NOT NULL,
  `color` varchar(20) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  `user_create` varchar(50) NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_color_task`
--

DROP TABLE IF EXISTS `student_color_task`;
CREATE TABLE IF NOT EXISTS `student_color_task` (
  `task_id` int(11) NOT NULL,
  `task_date` varchar(20) NOT NULL,
  `task_roomid` varchar(3) NOT NULL,
  `task_status` varchar(2) NOT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_decision`
--

DROP TABLE IF EXISTS `student_decision`;
CREATE TABLE IF NOT EXISTS `student_decision` (
  `dis_id` int(11) NOT NULL,
  `dec_detail` text NOT NULL,
  `dec_date` varchar(10) NOT NULL,
  `dec_point` varchar(20) NOT NULL,
  PRIMARY KEY (`dis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_discipline`
--

DROP TABLE IF EXISTS `student_discipline`;
CREATE TABLE IF NOT EXISTS `student_discipline` (
  `dis_id` int(11) NOT NULL,
  `dis_studentid` varchar(5) CHARACTER SET utf8 NOT NULL,
  `dis_date` varchar(10) CHARACTER SET utf8 NOT NULL,
  `dis_time` varchar(5) CHARACTER SET utf8 NOT NULL,
  `dis_village` varchar(255) CHARACTER SET utf8 NOT NULL,
  `dis_tumbol` varchar(255) CHARACTER SET utf8 NOT NULL,
  `dis_inform` varchar(255) CHARACTER SET utf8 NOT NULL,
  `dis_informdate` varchar(10) CHARACTER SET utf8 NOT NULL,
  `dis_informgroup` varchar(20) CHARACTER SET utf8 NOT NULL,
  `dis_reciever` varchar(255) CHARACTER SET utf8 NOT NULL,
  `dis_recievedate` varchar(255) CHARACTER SET utf8 NOT NULL,
  `dis_recievedetail` text CHARACTER SET utf8 NOT NULL,
  `dis_detail` text CHARACTER SET utf8 NOT NULL,
  `dis_user` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`dis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `student_disciplinestatus`
--

DROP TABLE IF EXISTS `student_disciplinestatus`;
CREATE TABLE IF NOT EXISTS `student_disciplinestatus` (
  `student_id` varchar(5) NOT NULL,
  `dis_id` int(11) NOT NULL,
  `dis_status` int(11) NOT NULL,
  `sanc_status` varchar(2) NOT NULL,
  `point` int(11) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  PRIMARY KEY (`dis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_drug`
--

DROP TABLE IF EXISTS `student_drug`;
CREATE TABLE IF NOT EXISTS `student_drug` (
  `row_id` varchar(20) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `class_id` varchar(3) NOT NULL,
  `drugType` varchar(2) NOT NULL,
  `drugLevel` varchar(2) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  `user_create` varchar(50) NOT NULL,
  PRIMARY KEY (`row_id`,`student_id`,`acadyear`,`acadsemester`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_drug_task`
--

DROP TABLE IF EXISTS `student_drug_task`;
CREATE TABLE IF NOT EXISTS `student_drug_task` (
  `task_id` int(11) NOT NULL,
  `task_date` varchar(20) NOT NULL,
  `task_roomid` varchar(3) NOT NULL,
  `task_status` varchar(2) NOT NULL,
  `drug_id` varchar(2) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_investigation`
--

DROP TABLE IF EXISTS `student_investigation`;
CREATE TABLE IF NOT EXISTS `student_investigation` (
  `dis_id` int(11) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `dis_type` varchar(2) NOT NULL,
  `dis_level` varchar(2) NOT NULL,
  `dis_investdetail` text NOT NULL,
  `dis_sanction` text,
  `dis_investor` varchar(255) NOT NULL,
  `dis_investdate` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`dis_id`,`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_learn`
--

DROP TABLE IF EXISTS `student_learn`;
CREATE TABLE IF NOT EXISTS `student_learn` (
  `row_id` varchar(20) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `class_id` varchar(3) NOT NULL,
  `period` varchar(2) NOT NULL,
  `timecheck_id` varchar(2) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL,
  `user_create` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_learn_task`
--

DROP TABLE IF EXISTS `student_learn_task`;
CREATE TABLE IF NOT EXISTS `student_learn_task` (
  `task_id` int(11) NOT NULL,
  `task_date` varchar(20) NOT NULL,
  `task_roomid` varchar(3) NOT NULL,
  `task_status` varchar(2) NOT NULL,
  `period` varchar(2) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_moral`
--

DROP TABLE IF EXISTS `student_moral`;
CREATE TABLE IF NOT EXISTS `student_moral` (
  `id` int(11) NOT NULL,
  `mdate` varchar(10) NOT NULL,
  `place` varchar(255) NOT NULL,
  `mlevel` varchar(2) NOT NULL,
  `mdesc` varchar(255) NOT NULL,
  `mtype` varchar(2) NOT NULL,
  `mprize` varchar(2) NOT NULL,
  `mteacher` varchar(255) NOT NULL,
  `student_id` varchar(5) NOT NULL,
  `academic` varchar(1) NOT NULL,
  `acadyear` varchar(4) NOT NULL,
  `acadsemester` varchar(1) NOT NULL,
  `point` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_sanction`
--

DROP TABLE IF EXISTS `student_sanction`;
CREATE TABLE IF NOT EXISTS `student_sanction` (
  `id` int(11) NOT NULL,
  `dis_id` int(11) NOT NULL,
  `sanc_detail` text CHARACTER SET utf8 NOT NULL,
  `sanc_time` int(11) NOT NULL,
  `sanc_alltime` int(11) NOT NULL,
  `sanc_teacher` varchar(255) CHARACTER SET utf8 NOT NULL,
  `sanc_date` varchar(10) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `ACADYEAR` int(4) NOT NULL,
  `PSUBJECTCODE` varchar(20) NOT NULL,
  `PSUBJECTNAME` varchar(255) NOT NULL,
  `ESUBJECTCODE` varchar(20) DEFAULT NULL,
  `ESUBJECTNAME` varchar(255) DEFAULT NULL,
  `PSUBTYPE` varchar(1) NOT NULL,
  `PSUBJECTCREDIT` double(4,1) NOT NULL,
  `PLEVEL` int(11) NOT NULL,
  `PPLAN` int(11) NOT NULL,
  `PSUBINCLASS` varchar(50) NOT NULL,
  `PSUBINTERM` int(11) NOT NULL,
  `GROUPSARA` int(11) NOT NULL,
  `PSUBJECTGROUP` int(11) NOT NULL,
  `PSUBJECTNO` int(11) DEFAULT NULL,
  `NHOURPERWEEK` int(11) NOT NULL,
  `SUBJECTDESCRIPTION` varchar(255) DEFAULT NULL,
  `STATUS` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `TeacCode` varchar(3) NOT NULL DEFAULT '',
  `PREFIX` varchar(20) DEFAULT NULL,
  `FIRSTNAME` varchar(25) DEFAULT NULL,
  `LASTNAME` varchar(25) DEFAULT NULL,
  `NICKNAME` varchar(30) DEFAULT NULL,
  `POSITION` varchar(255) NOT NULL,
  `T_PHONE` varchar(30) DEFAULT NULL,
  `t_mobile` varchar(30) DEFAULT NULL,
  `t_email` varchar(30) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'teacher',
  `superuser` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teachers_800`
--

DROP TABLE IF EXISTS `teachers_800`;
CREATE TABLE IF NOT EXISTS `teachers_800` (
  `roow_id` varchar(20) NOT NULL,
  `room_id` varchar(3) NOT NULL,
  `stutus` varchar(20) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teachers_learn`
--

DROP TABLE IF EXISTS `teachers_learn`;
CREATE TABLE IF NOT EXISTS `teachers_learn` (
  `roow_id` varchar(20) NOT NULL,
  `room_id` varchar(3) NOT NULL,
  `period` varchar(2) NOT NULL,
  `stutus` varchar(20) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `acadyear` int(11) NOT NULL,
  `acadsemester` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
