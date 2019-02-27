<? session_start(); ?>
<? ob_start();?>
<?
//ini_set('error_reporting', E_ALL);
// setup specific content(s) that require authorization
require_once("../user/Config.inc.php");
require_once("../classes/Utils.php");

// get the ticket of current user
$ticket 	= $_SESSION["ticket"];
$privilege= $ticket["privilege"];
$user_id	= $ticket["user_id"];
$password	= $ticket["password"];
if( !isset($ticket) || (($privilege!=PRIV_ADMIN) && ($privilege!=PRIV_OFFICER) && ($privilege!=PRIV_TEACHER)) )
{
	$txt = encodeText("../u_teacher_v20/index.php", $password);
	$params = 'return_page='.urlencode($txt);//'../u_student/index.php');
	header("Location: ../user/index.php?$params");
	exit;
}

// prepate some variables
$year				= $_REQUEST["year"];
$semester		= $_REQUEST["semester"];
$subject_id	= $_REQUEST["subject_id"];
$keys_id		= $_REQUEST["keys_id"];
$msg				= $_REQUEST["msg"];
$dontlog		= $_REQUEST["dontlog"];
$false_count = $_REQUEST['false_count'];

$crp	= $_REQUEST["crp"];//current_page
$lsp	= $_REQUEST["lsp"];//starting_row
$rpp	= $_REQUEST["rpp"];//row_per_page
if(!$crp || $crp<1)	$crp = 0;
if(!$lsp || $lsp<0)	$lsp = 0;
if(!$rpp || $rpp<1)	$rpp = 35;

$false = $_REQUEST["false"];
$falseX= explode("!",$false);
$num_false = count($falseX);
$falses= NULL;
for($i=0; $i<$num_false; $i++)
	$falses[$falseX[$i]] = 1;

if(!$year||!$semester||!$subject_id||!$keys_id)
{
	$msg = urlencode("".$translate["STUDENT_INFORMATION_WAS_NOT_FOUND_FOR_RECORDING_THE_SPECIFIED_GRADE."]."...");
	header("Location: ../u_teacher_v20/grading_record.php?msg=$msg");
	die();
}

set_time_limit(30*60);

// prepare the header of this section
require_once("grading_record_header.php");

$lsp = floor($num_std/$rpp);
$lsp-= ($num_std%$rpp)==0? 1 : 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>www.reg.kmitl.ac.th</title>
<META http-equiv=Content-Language content=th>
<META http-equiv=Content-Type content="text/html; charset=tis-620">
<link rel="stylesheet" type="text/css" href="../css/facebox.css"  />
<link href="../css/jquery-bubble-popup-v3.css" rel="stylesheet" type="text/css" />
<LINK href="../css/registrar.css" type="text/css" rel="stylesheet">
<LINK href="../css/leftmenu.css" type="text/css" rel="stylesheet">
<SCRIPT language=JavaScript src="../scripts/allscript.js"></SCRIPT>
<script language="javascript" src="../js/jquery-1.7.1.min.js"></script>
<script language="javascript" src="../js/facebox.js"></script>
<script src="../js/jquery-bubble-popup-v3.min.js" type="text/javascript"></script>

<script language="JavaScript" type="text/JavaScript">
function go2Criteria()
{
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_criteria.php";
	document.save.submit();
}
function go2Factor()
{
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_factor.php";
	document.save.submit();
}
function go2Score()
{
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_score.php";
	document.save.submit();
}
function go2Show()
{
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_show.php";
	document.save.submit();
}
function go2Table()
{
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_table.php";
	document.save.submit();
}
function go2SaveScore()
{
	if(!validateScore())
		return false;
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_score_save.php";
	document.save.submit();
}
function validateScore()
{
/*
	var f1_del = document.getElementById('f1_del').checked; 
	var f2_del = document.getElementById('f2_del').checked; 
	var f3_del = document.getElementById('f3_del').checked; 
	var f4_del = document.getElementById('f4_del').checked; 
		
	var percent= 0;
	percent += f1_del==true ? 0 : eval(document.getElementById('f1_per').value); 
	percent += f2_del==true ? 0 : eval(document.getElementById('f2_per').value); 
	percent += f3_del==true ? 0 : eval(document.getElementById('f3_per').value); 
	percent += f4_del==true ? 0 : eval(document.getElementById('f4_per').value); 

	if(percent!=100)
	{
		alert("�������ૹ����������� ("+percent+"%) ��ͧ��ҡѺ 100% ���ҹ�� \n\n���ҧ���·���ش��è��ա�û����Թ���Ẻ Final Exam\n��ӴѺ�ش����");
		return false;
	}
*/	
	return true;
}

function go2ScorePage(P)
{
	if(!validateScore())
		return false;
	if(P>-1)
	document.getElementById('crp').value = P;
	window.status	= "$translate["PLEASE_WAIT"]...$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]...";
	document.save.action="grading_record_score_save.php";
	document.save.submit();
}

function cTrig(box, student_id) {
  if (box.checked) {
    if (confirm(''.$translate["YOU_WANT_TO_CANCEL_THE_RESULT_OF_FILLING_OUT_THE_SCORE."].' '.$translate["SCHOOL"].'. '.$translate["CODE"].' '+student_id+'\n '.$translate["TO_FILL_OUT_ALL_NEW_POINTS"].'\n\n**'.$translate["NOTE"].'*** '.$translate["MUST_RECORD"].'')) {
        box.checked = true;
    }else
		box.checked=false;
  }
}


function FTrig(box, student_id) {
  if (box.checked) {
    if (confirm(''.$translate["YOU_WANT_TO_GIVE_GRADE"].' '.$translate["SCHOOL"].'. '.$translate["CODE"].' '+student_id+' '.$translate["IS_A_GRADE"].' "F" \n\n**'.$translate["NOTE"].'*** '.$translate["MUST_RECORD"].'')) {
        box.checked = true;
    }else
		box.checked=false;
  }
}
</script>
</head>
<body bgColor="#ffffff" leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<center>
<form name="save" method="post" action="grading_record_score.php" target="_top" enctype="multipart/form-data">
<input name="dontlog" type="hidden" id="dontlog" value="<?=$dontlog?>">
<input name="year" type="hidden" id="year" value="<?=$year?>">
<input name="semester" type="hidden" id="semester" value="<?=$semester?>">
<input name="subject_id" type="hidden" id="subject_id" value="<?=$subject_id?>">
<input name="keys_id" type="hidden" id="keys_id" value="<?=$keys_id?>">
<!--input type="hidden" name="crp" value="<?=$crp?>"-->
<input name="lsp" type="hidden" id="lsp" value="<?=$lsp?>">
<input name="rpp" type="hidden" id="rpp" value="<?=$rpp?>">
<input name="false" type="hidden" id="false" value="<?=$false?>">
<table cellspacing="0" cellpadding="0" width="750" border="0">
  <TBODY>
  <TR>
    <TD height="10"></TD>
  </TR>
  <TR>
    <TD align="center" vAlign=middle><a href="../index.php"><img src="images/u_teacher2.jpg" alt="".$translate["BACK_TO_"]."".$translate["FIRST_PAGE"]."" width="750" height="40" border="0"></a></TD>
  </TR>
  <TR>
    <TD height="10" align="left"></TD>
  </TR>
  <TR>
    <TD height=21 align="center" background="../images/label_m21.jpg"><strong><?=$translate["SCORING_SYSTEM_AND_CUTTING_GRADES"]?></strong></TD>
  </TR>
	<tr>
		<td height="1" align="center"></td>
	</tr>
	<tr>
		<td height="18" align="center" bgcolor="#E1E1E1"><strong><?=$translate["ON"]?><?=$translate["STUDY"]?><?=$translate["AT_"]?>&nbsp;<?=$semester?>&nbsp;&nbsp;&nbsp;<?=$translate["YEAR"]?>&nbsp;<?=$year?></strong></td>
	</tr>
	<tr>
		<td height="1" align="center" bgcolor="#E1E1E1"></td>
	</tr>
	<tr>
		<td height="18" align="center" bgcolor="#E1E1E1"><strong><?=$title.$title2?></strong></td>
	</tr>
	<tr>
		<td height="1" align="center" bgcolor="#E1E1E1"></td>
	</tr>
	<tr>
		<td height="18" align="center" bgcolor="#E1E1E1"><?=$teacher?></td>
	</tr>

	<tr>
		<td height="1"></td>
	</tr>
  <TR>
    <TD height=21 align="center" background="../images/horline_c750o.jpg"><a href="#" onClick="return go2Criteria()"><?=$translate["SET_THE_SCORE_CRITERIA"]?>"</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Factor()"><?$translate["SET_THE_SCORE_RANGE"]?></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?$translate["SCORE_RECORD"]?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Show()"><?$translate["CHECK_THE_SCORE"]?></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Table()"><?=$translate["SCATTER_TABLE"]?></a></TD>
  </TR>
	<tr>
		<td height="1"></td>
	</tr>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"><table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="25%">
		<a href="../u_teacher_v20/grading_record_score_xls.php?subject_id=<?=urlencode($subject_id)?>&year=<?=urlencode($year)?>&semester=<?=urlencode($semester)?>&keys_id=<?=urlencode($keys_id)?>&dontlog=<?=urlencode($dontlog)?>"><img src="../images/xls16.gif" alt="".$translate["DOWNLOAD_THE_CLASS_SCHEDULE_AND_GRADE_FILES"]." <?=$title.$title2?>" width="16" height="16" border="0"></a>
		<a href="../u_teacher_v20/grading_record_score_xls_upload.php?subject_id=<?=urlencode($subject_id)?>&year=<?=urlencode($year)?>&semester=<?=urlencode($semester)?>&keys_id=<?=urlencode($keys_id)?>&dontlog=<?=urlencode($dontlog)?>&crp=<?=urlencode($crp)?>"><img src="../images/doc_check.gif" alt="".$translate["UPLOAD_FILES_TABLE_GRADES_AND_EACH_SUBJECT_GRADES"]." <?=$title.$title2?>" width="16" height="16" border="0"></a></td>
		    <td width="50%">&nbsp;</td>
		    <td width="25%"><a href="grading_faq.php" target="_blank" ><img src="../images/more_info.png" width="16" height="16"> <?=$translate["FREQUENTLY__ASKED__QUESTIONS"]?> (FAQ)</a></td>
		    </tr>
            <?php
			if(!$system_cut)
			{
			?>
		  <tr>
		    <td colspan="3" bgcolor="#FF0000"><table width="100%" border="0" cellspacing="1" cellpadding="1">
		      <tr>
		        <td bgcolor="#FACCCB" align="center"><img src="../images/more_info.png" width="16" height="16"  alt=""/> <?=$translate["IN_CASE_OF_CHOOSING_NOT_TO_USE_THE_GRADE_CUTTING_SYSTEM"]?> <strong><?=$translate["USERS_MUST_FILL_OUT_THE_SCORE."]?> ".$translate["AND"]." ".$translate["ALL_GRADES"]."</strong> <?=$translate["FOR_THE_SYSTEM_TO_SAVE_DATA"]?></td>
		        </tr>
		      </table></td>
		    </tr>
            <?php
			}
			?>
		  </table>
		</td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
  <tr>
		<td><table width="750" border=0 align="center" cellpadding=0 cellspacing=0>
			<tr>
				<td rowspan="5" width="30" align="center" bgcolor="#E1E1E1"><strong><?=$translate["ORDER"]?></strong></td>
				<td rowspan="5" width="1"></td>
				<td rowspan="5" width="60" height="21" align="center" bgcolor="#E1E1E1"><strong><?=$translate["CODE"]?> <?=$translate["STUDENT"]?></strong></td>
				<td rowspan="5" width="1"></td>
				<td rowspan="5" width="*" align="center" bgcolor="#E1E1E1"><strong>".$translate["NAME"]."-<?=$translate["LAST_NAMES"]?></strong></td>
				<td rowspan="5" width="1" ></td>
<? if($system_cut){?>
				<td colspan="7" height="21" align="center" bgcolor="#E1E1E1"><strong><?=$translate["SCORE"]?></strong></td>
				<td width="1" ></td>
<? }?>
				<td colspan="3" height="21" align="center" bgcolor="#E1E1E1"><strong><?=$translate["SUMMARY"]?></strong></td>
				<td rowspan="5" width="1" ></td>
				<td rowspan="5" width="40" align="center" bgcolor="#E1E1E1"><strong><?=$translate["CATEGORY"]?></strong></td>
                <td rowspan="5" width="1" align="center"></td>
				<td colspan="3" rowspan="4" align="center" bgcolor="#E1E1E1"><a href="F_Reset.php" target="_blank"><img src="../images/more_info.png" width="16" height="16"> <?=$translate["MANUAL"]?></a></td>
                <td rowspan="5" width="1" align="center"></td>
				<td rowspan="5" width="60" align="center" bgcolor="#E1E1E1"><strong><?=$translate["STATUS"]?></strong></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr>
<? if($system_cut){?>
				<td width="50" height="18" align="center" bgcolor="#E1E1E1"><strong><?=!$f1_del?NULL:$f1_txt?></strong></td>
				<td width="1" ></td>
				<td width="50" align="center" bgcolor="#E1E1E1"><strong><?=!$f2_del?NULL:$f2_txt?></strong></td>
				<td width="1" ></td>
				<td width="50" align="center" bgcolor="#E1E1E1"><strong><?=!$f3_del?NULL:$f3_txt?></strong></td>
				<td width="1" ></td>
				<td width="50" align="center" bgcolor="#E1E1E1"><strong><?=!$f4_del?NULL:$f4_txt?></strong></td>
				<td width="1" ></td>
<? }?>
				<td width="50" height="18" align="center" bgcolor="#E1E1E1"><strong><?=$translate["TOTAL"]?> % </strong></td>
				<td rowspan="3" width="1" ></td>
				<td rowspan="3" width="50" align="center" bgcolor="#E1E1E1"><strong><?=$translate["GRADE"]?></strong></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr>
<? if($system_cut){?>
				<td width="50" height="21" align="center" bgcolor="#E1E1E1" title="<?=!$f1_del?NULL:"".$translate["RAW_SCORE"]." $f1_tot ".$translate["SCORE"]." ".$translate["TO_THINK"]." $f1_per%"?>"><strong><?=!$f1_del?NULL:$f1_tot?></strong></td>
				<td width="1" ></td>
				<td width="50" align="center" bgcolor="#E1E1E1" title="<?=!$f2_del?NULL:"".$translate["RAW_SCORE"]." $f2_tot ".$translate["SCORE"]." ".$translate["TO_THINK"]." $f2_per%"?>"><strong><?=!$f2_del?NULL:$f2_tot?></strong></td>
				<td width="1" ></td>
				<td width="50" align="center" bgcolor="#E1E1E1" title="<?=!$f3_del?NULL:"".$translate["RAW_SCORE"]." $f3_tot ".$translate["SCORE"]." ".$translate["TO_THINK"]." $f3_per%"?>"><strong><?=!$f3_del?NULL:$f3_tot?></strong></td>
				<td width="1" ></td>
				<td width="50" align="center" bgcolor="#E1E1E1" title="<?=!$f4_del?NULL:"".$translate["RAW_SCORE"]." $f4_tot ".$translate["SCORE"]." ".$translate["TO_THINK"]." $f4_per%"?>"><strong><?=!$f4_del?NULL:$f4_tot?></strong></td>
				<td width="1" ></td>
<? }?>
				<td width="50" height="21" align="center" bgcolor="#E1E1E1"><strong>100 %</strong></td>
				<td width="30" align="center" bgcolor="#FF0000"><strong><?=$translate["GIVE_GRADE"]?><br>
                <font color="#FFFFFF">F</font></strong></td>
                <td width="1" ></td>
				<td width="30" align="center" bgcolor="#CCCCCC"><strong><?=$translate["RESET"]?><br>
reset</strong></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
if($group && $keys_ids)
{
	$keys_wcs	= NULL;
	for($i=0; $i<$num_keys_id; $i++)
		$keys_wcs[$i] = "grading_score.keys_id = '$keys_ids[$i]'";
	$wc_add = "and (".implode(" or ",$keys_wcs).")";
}
else
$wc_add	= $format==1 ? NULL : "and grading_score.keys_id = '$keys_id'";

$factor1 	= NULL;
$factor2 	= NULL;
$factor3 	= NULL;
$factor4 	= NULL;

$str	= $crp*$rpp;
$kid	= NULL;
$sql	= "select 
grading_score.student_id, 
'', 
'', 
'', 
'', 
grading_score.score, 
grading_score.grade,
grading_score.factor1, 
grading_score.factor2, 
grading_score.factor3, 
grading_score.factor4,
grading_score.keys_id,
grading_score.else,
grading_score.subject_type
from grading_score
where 1 $wc_add 
and grading_score.year='$year' 
and grading_score.semester='$semester' 
and grading_score.subject_id='$subject_id' 
order by grading_score.student_id 
limit $str, $rpp";
//echo $sql."<br>";exit();
//order by grading_score.keys_id, student.student_id ajslayer
$rst	= mysql_query($sql); 
//echo $sql;
//$total= mysql_num_rows($rst);
if($year < 2557)
{
	$gcfg_sql = "select `grade` from `subject_grade_config` where `subject_id`='$subject_id';";
}else
{
	$gcfg_sql = "select `grade` from `subject_grade_config` where `subject_id`='$subject_id' and `grade` not like 'G,P%';";
}


$gcfg_rst = mysql_query($gcfg_sql);
if(mysql_num_rows($gcfg_rst))
{
	$gcfg_row = mysql_fetch_assoc($gcfg_rst);
	if($gcfg_row['grade'] != 'I')
	{
		$gcfg_range = explode(',', $gcfg_row['grade']);
		$gcfg_I=0;
	}
	else
	{
		$gcfg_range = NULL;
		$gcfg_I = 1;
	}
}else
{
	$gcfg_range = NULL;
	$gcfg_I=0;
}

for($i=0; $row=mysql_fetch_array($rst, MYSQL_NUM); $i++) 
{
	$bgcolor			= ($i % 2) ? "#FDF3E7" : "#ECECEC";
	$bgcolorX			= ($i % 2) ? "#FEF7EE" : "#F2F2F2";
	$student_id		= $row[0];
	//$student_name	= $row[1].$row[2]."&nbsp;&nbsp;".$row[3];
	//$status		= $row[4];
	$score		= $row[5]<0? NULL : $row[5];
	$grade		= $row[6];
	$factor1	= $row[7]<0? NULL : $row[7];
	$factor2	= $row[8]<0? NULL : $row[8];
	$factor3	= $row[9]<0? NULL : $row[9];
	$factor4	= $row[10]<0? NULL : $row[10];
	$else			= $row[12];
	
	$subject_type = (strtolower($row[13])=="au" || strtolower($row[13])=="audit") ? "<font color='red'>Audit</font>" : "Credit" ;
	if($row[13] == 'Tr')
		$subject_type = "<font color='blue'>".$translate["TRANSFER_GRADE"]."</font>";
	
	$sql2	= "select `t_prename`, `t_name`, `t_surname`, `status` from `student` where `student_id`='$student_id'";
	$rst2	= mysql_query($sql2); 
	$row2 = $rst2 ? mysql_fetch_array($rst2, MYSQL_NUM) : NULL;
	if($rst2)mysql_free_result($rst2);
	$student_name	= $row2[0].$row2[1]."&nbsp;&nbsp;".$row2[2];
	$status				= $row2[3];
	$_grade = '';
	if($system_cut)
	{
		$bgcolor1 = !$f1_del ? $bgcolorX : $bgcolor;
		$bgcolor2 = !$f2_del ? $bgcolorX : $bgcolor;
		$bgcolor3 = !$f3_del ? $bgcolorX : $bgcolor;
		$bgcolor4 = !$f4_del ? $bgcolorX : $bgcolor;

		$factor1 	= !$f1_del ? NULL : "<input type='text' name='factor1s[]' value='$factor1' size='4' maxlength='5'>";
		$factor2 	= !$f2_del ? NULL : "<input type='text' name='factor2s[]' value='$factor2' size='4' maxlength='5'>";
		$factor3 	= !$f3_del ? NULL : "<input type='text' name='factor3s[]' value='$factor3' size='4' maxlength='5'>";
		$factor4 	= !$f4_del ? NULL : "<input type='text' name='factor4s[]' value='$factor4' size='4' maxlength='5'>";
		$_grade_ = $grade;

		
		if($grade)
		{
			$grade_chk = $else ? $else : $grade;
			$opt	=	NULL;
			if($row[13] != 'Tr')
				$opt.=	"<option value='$grade'>$grade</option>";
			else
			{
				if($else)
					$opt.=	"<option value='$else'>$else</option>";
				else
					$opt.=	"<option value='$grade'>$grade</option>";
			}
			if(!$gcfg_I && ($grade=='I' || $else=='I'))
			{
				$countI++;
			}
			if(count($gcfg_range))
			{
				foreach($gcfg_range as $gcfg_grade)
				{
					$opt.=	"<option value='".$gcfg_grade."'".($else=="$gcfg_grade" ? " selected":NULL)." style='color:#aaaaaa'>".$gcfg_grade."</option>";
				}
			}else
			{				
				if($year < 2557)
				{
					$opt.=	"<option value='FA'".($else=='FA'?" selected":NULL)." style='color:#aaaaaa'>FA</option>";
					$opt.=	"<option value='FE'".($else=='FE'?" selected":NULL)." style='color:#aaaaaa'>FE</option>";
					$opt.=	"<option value='G'".($else=='G'?" selected":NULL)." style='color:#aaaaaa'>G</option>";
					$opt.=	"<option value='P'".($else=='P'?" selected":NULL)." style='color:#aaaaaa'>P</option>";
				}else
				{
					if($grade != 'F')
						$opt.=	"<option value='F'".($else=='F'?" selected":NULL)." style='color:#aaaaaa'>F</option>";
				}
				if($gcfg_I )
				{
					$opt.=	"<option value='I' ".($else=='I' ?" selected":NULL)." style='color:#aaaaaa'>I</option>";
				}
				$opt.=	"<option value='S'".($else=='S'?" selected":NULL)." style='color:#aaaaaa'>S</option>";
				
				$opt.=	"<option value='U'".($else=='U'?" selected":NULL)." style='color:#aaaaaa'>U</option>";
				$opt.=	"<option value='' style='color:#aaaaaa'></option>";
			}
			$grade	=	"<select name='grades[]' style='width:40px;'>$opt</select>";
		}
		else
		{
			$grade = "<input type='hidden' name='grades[]' value=''>"; 
		}
	}
	else//<?=$translate["NOT_USING_THE_SYSTEM"]
	{
		
		$score = "<input type='text' name='scores[]' value='$score' size='4' maxlength='5'>";
		$grade = "<input type='text' name='grades[]' value='".($else?$else:$grade)."' size='3' maxlength='2'>";
	}
	
	if($status != '$translate["STUDY"]')
	{
		if($system_cut)
		{
			$score = "<input type='hidden' name='score_".$student_id."' value='-' >-";
			$grade = "<input type='hidden' name='grades[]' value='-' >-";
			$factor1 	= !$f1_del ? NULL : "<input type='hidden' name='factor1s[]' value='-' >-";
			$factor2 	= !$f2_del ? NULL : "<input type='hidden' name='factor2s[]' value='-' >-";
			$factor3 	= !$f3_del ? NULL : "<input type='hidden' name='factor3s[]' value='-' >-";
			$factor4 	= !$f4_del ? NULL : "<input type='hidden' name='factor4s[]' value='-' >-";
		}
		else
		{
			$score = "<input type='hidden' name='scores[]' value='-' >-";			
			$grade = "<input type='hidden' name='grades[]' value='-' >-";
			/*<?=$translate["NOT_USING_THE_SYSTEM"]?> factor1-4 <?=$translate["DO_NOT_MIND"]?>
			$factor1 	= "<input type='hidden' name='factor1s[]' value='-' >-";
			$factor2 	=  "<input type='hidden' name='factor2s[]' value='-' >-";
			$factor3 	=  "<input type='hidden' name='factor3s[]' value='-' >-";
			$factor4 	= "<input type='hidden' name='factor4s[]' value='-' >-";
			*/
		}
		$grade_dash = 1;
	}



	//$status =  NULL;

	$sec_name = NULL;
	if(false &&	(($group && $keys_ids)||$format==1) && (!$i || $kid!=$row[11]) )
//	if($format==1 && (!$i || $kid!=$row[11]))
	{
		if($row[11][0]=='x')
		{
			$sec_name = "".$translate["STUDENTS_FROM_OTHER_DISCIPLINES"]." ".$translate["NOT_SPECIFIED_IN_THE_SCHEDULE"]."";
		}
		else
		{
			$kid	= $row[11];
			$kids	= explode("#",$kid);
			$sql2	= "select `curr2_tname` from `curriculum2` where `faculty_id`='$kids[0]' and `curr2_id`='$kids[2]'";
			$rst2	= mysql_query($sql2); 
			$row2 = $rst2 ? mysql_fetch_array($rst2, MYSQL_NUM) : NULL;
			if($rst2)mysql_free_result($rst2);
			$sec_name = !$row2 ? NULL : "".$translate["BRANCH"]."$row2[0]";
		}
?>
			<tr align="left">
				<td height="21" colspan="25" background="../images/horline_750.jpg"><strong>&nbsp;<?=$sec_name?></strong></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
	}	
	$kid= $row[11];
	$f 	= $falses["$student_id"];
	$f_balloon = !$f ? NULL : "".$translate["PLEASE_CHECK_THE_STUDENT'S_CODE."]." $student_id ".$translate["AGAIN"]."...";
	
?>			
<input type="hidden" name="student_ids[]" value="<?=$student_id?>">
			<tr title="<?=$f_balloon?>">
				<td height="21" align="center" valign="middle" bgcolor="<?=$bgcolor?>"><? if($f){?><font color='#FF9900'>*<?=($i+1)?></font><? }else{?><?=(++$str)?><? }?></td>
				<td></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor?>"><? if($f){?><font color='#FF9900'><?=$student_id?></font><? }else{?><?=$student_id?><? }?></td>
				<td></td>
				<td align="left" valign="middle" bgcolor="<?=$bgcolor?>">&nbsp;<? if($f){?><font color='#FF9900'><?=$student_name?></font><? }else{?><?=$student_name?><? }?></td>
				<td></td>
<? if($system_cut){?>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor1?>"><?=$factor1?></td>
				<td></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor2?>"><?=$factor2?></td>
				<td></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor3?>"><?=$factor3?></td>
				<td></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor4?>"><?=$factor4?></td>
				<td></td>
<? }?>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor?>"><?=$score?></td>
				<td></td>
				<td align="center" valign="middle" bgcolor="<?=$_grade_=='F' ? '#FF6600' : $bgcolor?>"><?=$grade?></td>
				<td></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor?>">
                 <?php
				if($status == ''.$translate["STUDY"].'')
				{
				?>
				<?=$subject_type?>
                <?php
				}
				?>
                </td>
                <td></td>
				<td align="center" valign="middle" bgcolor="<?=$else=='F' ? '#FF0000' : $bgcolor?>">
                <?php
				if($status == ''.$translate["STUDY"].'')
				{
				?>
                <input name="else_<?=$student_id?>" type="checkbox"  value="F"
                 <?=$else=='F'? 'checked' : ''?> onchange="FTrig(this,'<?=$student_id?>');">
                 <?php
				}
				 ?>
                 </td>
                  <td></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor?>">
                   <?php
				if($status == ''.$translate["STUDY"].'')
				{
				?>
                <input name="reset_<?=$student_id?>" type="checkbox"  value="1" onchange="cTrig(this,'<?=$student_id?>');">
                <?php
				}
				 ?>
                </td>
                
				<td align="center" valign="middle" ></td>
				<td align="center" valign="middle" bgcolor="<?=$bgcolor?>"><font color='<?=(($status != ''.$translate["STUDY"].'') ?"#ff0000":"#000000")?>'><?php
				if($status != ''.$translate["STUDY"].'')
				{
					echo $status;
				}else
				{
					if($grade_chk != "S" && $grade_chk != "U" && $subject_type != "Credit" && $row[13] != 'Tr')
					{
						echo "<font color=red> ".$translate["GRADE_SHOULD_BE"]." S ".$translate["OR"]." U</font>";
					}					
				}?></font></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
}
if($rst)mysql_free_result($rst);
?>			
			<!--tr>
				<td colspan="19" align="center" bgcolor="#E1E1E1" height="21"><strong><?=$translate["TOTAL_NUMBER_OF_STUDENTS"]?>&nbsp;<?=$total?>&nbsp;&nbsp;&nbsp;<?=$translate["PERSON"]?></strong></td>
			</tr-->
    </table></td>
  </tr>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
	<tr>
		<td><table width="750" border=0 align="center" cellpadding=0 cellspacing=0>
			<tr>
				<td width="25%" height="18" align="left">
					<input type="button"  value="  ".$translate["RECORD"]."   " title="".$translate["COURSE_SCORE_RECORD"]." <?=$subject_id?>" onClick="return go2SaveScore()" id ="btsave" class="bubblepopup" 
					<?=$grade_dash ? 'style= "height:25px; background-color:#FF6600"' : '' ?> >
				</td>
				<td height="18" align="right">
					'.$translate["PAGE"].':&nbsp;<select name='crp' id='crp' onchange="return go2ScorePage('-1')">
<? for($i=0; $i<=$lsp; $i++){?>
					<option value='<?=$i?>' <?=$i==$crp?"selected":NULL?> ><?=$i+1?></option>
<? }?>
					</select>&nbsp;
<? if($crp>0){?>
					<input type="button" value="  <<  " title="'.$translate["FIRST_PAGE"].'" onClick="return go2ScorePage('0')">
<? $p=$crp-1; $p=$p<0?0:$p;?>
					<input type="button" value="   <   " title="'.$translate["PREVIOUS_PAGE"].'" onClick="return go2ScorePage('<?=$p?>')">
<? }if($crp<$lsp){ $p=$crp+1; $p=$p>$lsp?$lsp:$p;?>
					<input type="button" value="   >   " title="'.$translate["PAGE"].''.$translate["NEXT"].'" onClick="return go2ScorePage('<?=$p?>')">
<? $p=$lsp;?>
					<input type="button" value="  >>  " title="'.$translate["LAST_PAGE"].'" onClick="return go2ScorePage('<?=$p?>')">
<? }?>					
				</td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
	<TR>
		<td align="left" nowrap><font color="#FF9900"><div id="div_msg"><?=$msg?></div></font></td>
  </TR>
</TBODY>
</TABLE>
</form>
</center>
</body>
<?php
if(count($gcfg_range) || $countI)
{	
?>
<script language="javascript">
$(function(){
	var Str='<table width="700" border="0" cellspacing="1" cellpadding="1">';
	Str+='<tr>';
	Str+='<td height="22" align="center"><?=$title.$title2?></td>';
	Str+='</tr>';
	<?php
	if(count($gcfg_range))
	{
	?>
	Str+='<tr>';
	Str+='<td bgcolor="#FFECEA" height="30" align="center" >'.$translate["CAN_GIVE_GRADE"].' <b><?=implode(" , ", $gcfg_range)?></b> '.$translate["HAVE"].''.$translate["ONLY"].' !!!</td>';
	Str+='</tr>';
	<?php
	}
	if($countI)
	{
	?>
	Str+='<tr>';
	Str+='<td bgcolor="#FFECEA" height="30" align="center" >'.$translate["THIS_COURSE_CANNOT_PROVIDE_GRADES."].' <b> I </b> '.$translate["HAVE"].' <br>'.$translate["PLEASE_CHECK_THE_GRADE,_EDIT_AND_SAVE_THE_NEW_INFORMATION."].' !!!</td>';
	Str+='</tr>';
	<?php
	}
	?>
	Str+='</table>';
	MBox(Str);
});

</script>
<?php
}
if($grade_dash)
{
?>
<script language="javascript">
$(function(){
		$('.bubblepopup').CreateBubblePopup({
											innerHtml: ''.$translate["PLEASE_PRESS_THE_BUTTON"].' "'.$translate["RECORD"].'" '.$translate["TO_UPDATE_GRADES"].' '.$translate["SCHOOL"].'. '.$translate["RESIGN"].'',
											innerHtmlStyle: {
																color:'#333333', 
																'text-align':'center'
															},
											themeName: 	'orange',
											themePath: 	'../jquerybubblepopup-themes'
									  });
		$('#btsave').ShowBubblePopup();	
		$('#btsave').FreezeBubblePopup();
});
</script>
<?php
}
if($false_count)
{
?>
<script language="javascript">
$(function(){
	var Str='<table width="500" border="0" cellspacing="1" cellpadding="1">';
Str+='<tr>';
Str+='<td height="24" bgcolor="#CCCCCC"><div align="center"><b>'.$translate["FOUND_ERRORS_IN_RECORDING_STUDENT_SCORES,_PROBABLY_DUE_TO"].'</b></div></td>';
Str+='</tr>';
Str+='<tr>';
Str+='<td height="23" bgcolor="#ECECEC"> 1. '.$translate["SCORE_THAT_EXCEEDS_THE_MAXIMUM_SCORE_SET"].' '.$translate["SUCH_AS"].' '.$translate["DETERMINE_THE_FINAL_EXAM_SCORE"].' '.$translate["FULL"].' 60 '.$translate["SCORE"].' <br>';
Str+=''.$translate["BUT_HAVE_ENTERED_THE_TEST_SCORE_MORE_THAN"].' 60 '.$translate["SCORE"].' (61+ '.$translate["SCORE"].') '.$translate["THE_PROGRAM_WILL_NOT_SAVE_POINTS_FOR"].' '.$translate["ETC"].'</td>';
Str+='</tr>';
Str+='<tr>';
Str+='<td height="23" bgcolor="#ECECEC"> 2. '.$translate["USERS_CHOOSE_NOT_TO_USE_THE_GRADE_CUTTING_SYSTEM."].' '.$translate["THEREFORE,_ALL_GRADES_AND_GRADES_MUST_BE_ENTERED_INTO_THE_SYSTEM."].'</td>';
Str+='</tr>';
Str+='<tr>';
Str+='<td height="23" bgcolor="#ECECEC"> 3. '.$translate["FILL_OUT_THE_SCORE_LESS"].' 0 <font color="#FF0000">('.$translate["THE_LOWEST_SCORE_THAT_THE_SYSTEM_CAN_CALCULATE_GRADE"].' A-F '.$translate["HAVE"].''.$translate["IS_"].' 0.01)</font></td>';
Str+='</tr>';
Str+='<tr>';
Str+='<td height="23" bgcolor="#ECECEC"><font color="#FF0000">***</font>'.$translate["PLEASE_SEE_OTHER_DETAILS."].' '.$translate["MORE_FROM_THE_DOCUMENTATION"].' '.$translate["OR"].' '.$translate["FREQUENTLY__ASKED__QUESTIONS"].'</td>';
Str+='</tr>';
Str+='</table>';
MBox(Str);
	});
</script>
<?php
}
?>
<?php require_once('grading_popup_menu.php');?>
</html>
<? set_time_limit(30); ?>
<? mysql_close(); ?>
<? ob_end_flush(); ?>