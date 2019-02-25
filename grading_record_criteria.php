<? session_start(); ?>
<? ob_start();?>
<?
// setup specific content(s) that require authorization
require_once("../user/Config.inc.php");
require_once("../classes/Utils.php");
require_once("../translate.php");
// get the ticket of current user
$translate = init_translate("./grading_record_criteria.lang.json")
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
$gradetype	= $_REQUEST["gradetype"];
$grade_max	= $_REQUEST["grade_max"];
$grade_min	= $_REQUEST["grade_min"];
$dontlog		= $_REQUEST["dontlog"];
$msg				= $_REQUEST["msg"];

if(!$year||!$semester||!$subject_id||!$keys_id)
{
	$msg = urlencode($translate["STUDENT_INFORMATION_NOT_FOUND_FOR_SAVE_THE_SPECIFIED_GRADE_"]);
	header("Location: ../u_teacher_v20/grading_record.php?msg=$msg");
	die();
}

// establish the link of mysql connection
//mysql_connect(HOST, USERNAME, PASSWORD) or die( mysql_error() );// die ("ไม่สามารถเชื่อมต่อไปยังเซิฟเวอร์ได้");
//mysql_select_db(DB_NAME) or die( mysql_error() );// die( "ไม่สามารถติดต่อฐานข้อมูลได้");
set_time_limit(30*60);

// prepare the header of this section
require_once("grading_record_header.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>www.reg.kmitl.ac.th</title>
<META http-equiv=Content-Language content=th>
<META http-equiv=Content-Type content="text/html; charset=tis-620">
<LINK href="../css/registrar.css" type="text/css" rel="stylesheet">
<SCRIPT language=JavaScript src="../scripts/allscript.js"></SCRIPT>

<script language="JavaScript" type="text/JavaScript">
function go2Criteria()
{
	window.status	= $translate["PLEASE_WAIT_THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_DATA_"];
	document.save.action="grading_record_criteria.php";
	document.save.submit();
}
function go2Factor()
{
	window.status	= $translate["PLEASE_WAIT_THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_DATA_"];
	document.save.action="grading_record_factor.php";
	document.save.submit();
}
function go2Score()
{
	window.status	= $translate["PLEASE_WAIT_THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_DATA_"];
	document.save.action="grading_record_score.php";
	document.save.submit();
}
function go2Show()
{
	window.status	= $translate["PLEASE_WAIT_THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_DATA_"];
	document.save.action="grading_record_show.php";
	document.save.submit();
}
function go2Table()
{
	window.status	= $translate["PLEASE_WAIT_THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_DATA_"];
	document.save.action="grading_record_table.php";
	document.save.submit();
}
function go2SaveCriteria()
{
	if(!validateScore())
		return false;
	window.status	= $translate["PLEASE_WAIT_THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_DATA_"];
	document.save.action="grading_record_criteria_save.php";
	document.save.submit();
}
function validateScore()
{
	var gradetype = document.getElementById('gt').value; 
	if(gradetype==0)
	{
		var a = eval(document.getElementById('a').value); 
		var bp= eval(document.getElementById('bp').value); 
		var b = eval(document.getElementById('b').value); 
		var cp= eval(document.getElementById('cp').value); 
		var c = eval(document.getElementById('c').value); 
		var dp= eval(document.getElementById('dp').value); 
		var d = eval(document.getElementById('d').value); 

		if(a <0||a >100||a <=bp){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." A ".$translate["MUST_BE_MORE_THAN_A_GRADE"]." B+ ".$translate["AND_LESS_THAN_OR_EQUAL_TO"]." 100"); return false;}
		if(bp<0||bp>100||bp<= b){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." B+ ".$translate["MUST_BE_OVER"].$translate["GRADE_SCORE"]." B ".$translate["AND_LESS_THAN"]."".$translate["GRADE_SCORE"]."  A"); return false;}
		if(b <0||b >100||b <=cp){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." B ".$translate["MUST_BE_OVER"].$translate["GRADE_SCORE"]." C+ ".$translate["AND_LESS_THAN"]."".$translate["GRADE_SCORE"]."  B+"); return false;}
		if(cp<0||cp>100||cp<= c){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." C+ ".$translate["MUST_BE_OVER"].$translate["GRADE_SCORE"]." C ".$translate["AND_LESS_THAN"]."".$translate["GRADE_SCORE"]."  B"); return false;}
		if(c <0||c >100||c <=dp){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." C ".$translate["MUST_BE_OVER"].$translate["GRADE_SCORE"]." D+ ".$translate["AND_LESS_THAN"]."".$translate["GRADE_SCORE"]."  C+"); return false;}
		if(dp<0||dp>100||dp<= d){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." D+ ".$translate["MUST_BE_OVER"].$translate["GRADE_SCORE"]." D ".$translate["AND_LESS_THAN"]."".$translate["GRADE_SCORE"]."  C"); return false;}
		if(d <0||d >100||d >=dp){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." D ".$translate["MUST_BE_OVER"].$translate["GRADE_SCORE"]." F ".$translate["AND_LESS_THAN"]."".$translate["GRADE_SCORE"]."  D+"); return false;}
	}
	else
	if(gradetype==1)//
	{
		var grade_max = document.getElementById('grade_max').value; 
		var grade_min = document.getElementById('grade_min').value; 
		if(!grade_max||!grade_min){alert($translate["YOU_HAVE_TO_CHOOSE_THE_HIGHEST_GRADE_AND_THE_LOWEST_GRADE_"]." \n".$translate["FOR_CALCULATING_THE_GRADE_RANGE_USING"]." T-Score"); return false;}
		var gx=0;
		switch(grade_max)
		{
			case 'A': gx=7; break;
			case 'B+':gx=6; break;
			case 'B': gx=5; break;
			case 'C+':gx=4; break;
			case 'C': gx=3; break;
			case 'D+':gx=2; break;
			case 'D': gx=1; break;
			case 'F':	gx=0; break;
		}
		var gn=0;
		switch(grade_min)
		{
			case 'A': gn=7; break;
			case 'B+':gn=6; break;
			case 'B': gn=5; break;
			case 'C+':gn=4; break;
			case 'C': gn=3; break;
			case 'D+':gn=2; break;
			case 'D':	gn=1; break;
			case 'F':	gn=0; break;
		}
		if(gn>gx){alert($translate["THE_HIGHEST_GRADE_SHOULD_BE_HIGHER_THAN_THE_LOWEST_GRADE_"]." \n".$translate["FOR_CALCULATING_THE_GRADE_RANGE_USING"]." T-Score"); return false;}
		
		var str = $translate["CALCULATION_OF_GRADE_RANGE_USING"]." T-Score ".$translate["TO_GET_THE_CORRECT_RESULT"]."\n".$translate["YOU_HAVE_TO_SAVE_ALL_STUDENTS_SCORES_FIRST_"]."\n ".$translate["AND_THEN_SAVE_THE_SCORE_RANGE_AS"]." T-Score ".$translate["BECAUSE"]." \n ".$translate["SCORE_OF_ALL_STUDENTS_WILL_BE_CALCULATED_TO_DIVIDE_THE_RANGE"]." \n ".$translate["SCORE_AS"]." T-Score  ".$translate["GRADING"];
		str += "\n\n".$translate["IF_YOU_HAVE_SAVED_STUDENT_SCORES_FIRST_CLICK"]." 'OK'   ";
		if(confirm(str))
			return true;
		else
			return false;
	}
	else
	{
		var s = eval(document.getElementById('s').value); 
		if(s <0||s >100){alert($translate["YOU_SET_THE_CRITERIA_INCORRECTLY_"]." \n\n".$translate["GRADE_SCORE"]." S ".$translate["MUST_BE_MORE_THAN_A_GRADE"]." U ".$translate["AND_LESS_THAN_OR_EQUAL_TO"]." 100"); return false;}
	}
	
	return true;
}
</script>
</head>
<body bgColor="#ffffff" leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<center>
<form name="save" id="save" method="post" action="grading_record_criteria.php" target="_top" enctype="multipart/form-data">
<input type="hidden" name="dontlog" id="dontlog" value="<?=$dontlog?>">
<input type="hidden" name="year" id="year" value="<?=$year?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="subject_id" id="subject_id" value="<?=$subject_id?>">
<input type="hidden" name="keys_id" id="keys_id" value="<?=$keys_id?>">
<input type="hidden" name="gt" id="gt" value="<?=$gradetype?>">
<table cellspacing="0" cellpadding="0" width="750" border="0">
  <TBODY>
  <TR>
    <TD height="10"></TD>
  </TR>
  <TR>
    <TD align="center" vAlign=middle><a href="../index.php"><img src="images/u_teacher2.jpg" alt=$translate["BACK_HOME"] width="750" height="40" border="0"></a></TD>
  </TR>
  <TR>
    <TD height="10" align="left"></TD>
  </TR>
  <TR>
    <TD height=21 align="center" background="../images/label_m21.jpg"><strong><?=$translate["SCORING_SYSTEM_AND_GRADING"]?></strong></TD>
  </TR>
	<tr>
		<td height="1" align="center"></td>
	</tr>
	<tr>
		<td height="18" align="center" bgcolor="#E1E1E1"><strong><?=$translate["TERM"]?>&nbsp;<?=$semester?>&nbsp;&nbsp;&nbsp;<?=$translate["YEAR"]?>&nbsp;<?=$year?></strong></td>
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
		<td height="20" align="center" bgcolor="#E1E1E1"></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
  <TR>
    <TD height=21 align="center" background="../images/horline_c750o.jpg">".$translate["SET_THE_SCORE_CRITERIA"]."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Factor()">".$translate["SET_THE_SCORE_RANGE"]."</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Score()">".$translate["SCORE_SAVE"]."</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Show()">".$translate["CHECK_THE_SCORE"]."</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Table()"><?=$translate["SCATTER_TABLE"]?></a></TD>
  </TR>
	<tr>
		<td height="1"></td>
	</tr>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
	<tr>
		<td bgcolor="#f8f8f8"><table width="750" border="1" cellpadding="0" cellspacing="0">
			<tr>
				<td height="32" align="left" bgcolor="#F0F0F0">&nbsp;<strong><?=$translate["GRADE_CUTTING_PATTERN"]?> :</strong>
				<input name="gradetype" type="radio" id="gradetype" value="0" <?=!$gradetype?"checked":"onClick='return go2Criteria()'"?>><?=$translate["CUSTOMIZE_THE_SCORE_RANGE"]?>
<? if($system_cut){?>
				&nbsp;&nbsp;&nbsp;
				<input name="gradetype" type="radio" id="gradetype" value="1" <?=$gradetype==1?"checked":"onClick='return go2Criteria()'"?>><?=$translate["CUT_GRADES_USING"]?> T-Score
				&nbsp;&nbsp;&nbsp;
				<input name="gradetype" type="radio" id="gradetype" disabled value="2" <?=$gradetype==2?"checked":"onClick='return go2Criteria()'"?>><?=$translate["CUT_GRADES_USING"]?> Mean-SD		
<? }?>				
				&nbsp;&nbsp;&nbsp;
				<input name="gradetype" type="radio" id="gradetype" value="3" <?=$gradetype==3?"checked":"onClick='return go2Criteria()'"?>><?=$translate["PASS_OR_NOT_PASS"]?> (S, U)
				</td>
			</tr>
		</table></td>
	</tr>
<?
if($gradetype==1 || $gradetype==2)
{
$opt	=	NULL;
$opt.=	"<option value='A' ".($grade_max=='A' ?" selected":NULL).">A</option>";
$opt.=	"<option value='B+'".($grade_max=='B+'?" selected":NULL).">B+</option>";
$opt.=	"<option value='B' ".($grade_max=='B' ?" selected":NULL).">B</option>";
$opt.=	"<option value='C+'".($grade_max=='C+'?" selected":NULL).">C+</option>";
$opt.=	"<option value='C' ".($grade_max=='C' ?" selected":NULL).">C</option>";
$opt.=	"<option value='D+'".($grade_max=='D+'?" selected":NULL).">D+</option>";
$opt.=	"<option value='D' ".($grade_max=='D' ?" selected":NULL).">D</option>";
$opt.=	"<option value='F' ".($grade_max=='F' ?" selected":NULL).">F</option>";
$combo_grade_max	=	"<select name='grade_max' id='grade_max' >$opt</select>";

$opt	=	NULL;
$opt.=	"<option value='A' ".($grade_min=='A' ?" selected":NULL).">A</option>";
$opt.=	"<option value='B+'".($grade_min=='B+'?" selected":NULL).">B+</option>";
$opt.=	"<option value='B' ".($grade_min=='B' ?" selected":NULL).">B</option>";
$opt.=	"<option value='C+'".($grade_min=='C+'?" selected":NULL).">C+</option>";
$opt.=	"<option value='C' ".($grade_min=='C' ?" selected":NULL).">C</option>";
$opt.=	"<option value='D+'".($grade_min=='D+'?" selected":NULL).">D+</option>";
$opt.=	"<option value='D' ".($grade_min=='D' ?" selected":NULL).">D</option>";
$opt.=	"<option value='F' ".($grade_min=='F' ?" selected":NULL).">F</option>";
$combo_grade_min	=	"<select name='grade_min' id='grade_min' >$opt</select>";
?>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
	<tr>
		<td bgcolor="#ECECEC"><table width="750" border="1" cellpadding="0" cellspacing="0">
			<tr>
				<td height="32" align="left" bgcolor="#F0F0F0">&nbsp;<strong><?=$translate["PREFERRED_GRADE_RANGE"]?> :</strong>
				&nbsp;
				<?=$translate["THE_HIGHEST_GRADE"]?>&nbsp;<?=$combo_grade_max?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?=$translate["MINIMAL_GRADE"]?>&nbsp;<?=$combo_grade_min?>
				</td>
			</tr>
		</table></td>
	</tr>
<?
}
else
{
?>	
<input type="hidden" name="grade_max" id="grade_max" value="<?=$grade_max?>">
<input type="hidden" name="grade_min" id="grade_min" value="<?=$grade_min?>">
<?
}
?>	
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
  <tr>
		<td><table width="750" border=0 align="center" cellpadding=0 cellspacing=0>
			<tr>
				<td width="80" height="21" align="center" bgcolor="#E1E1E1"><strong><?=$translate["GRADE"]?></strong></td>
				<td width="1" ></td>
				<td width="100" align="center" bgcolor="#E1E1E1"><strong><?=$translate["SCORE_FROM"]?></strong></td>
				<td width="1" ></td>
				<td width="100" align="center" bgcolor="#E1E1E1"><strong><?=$translate["UP_TO_THE_SCORE"]?></strong></td>
				<td width="1" ></td>
				<td width="*" align="center" bgcolor="#E1E1E1"><strong><?=$translate["MEANING"]?></strong></td>
				<td width="1" ></td>
				<td width="100" align="center" bgcolor="#E1E1E1"><strong><?=$translate["CREDITS_MULTIPLIER"]?></strong></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
if($gradetype<3)
{
	$ac = $translate["UPPER"];//"100.00";	
	$af = "<input type='text' name='a' id='a'  value='$a' size='5' maxlength='5'>";//$a;//number_format($a, 2, '.', '');
	$bpc= number_format($a-0.01, 2, '.', '');
	$bpf= "<input type='text' name='bp' id='bp' value='$bp' size='5' maxlength='5'>";//$bp;//number_format($bp, 2, '.', '');
	$bc	= number_format($bp-0.01, 2, '.', '');
	$bf	= "<input type='text' name='b'  id='b' value='$b' size='5' maxlength='5'>";//$b;//number_format($b, 2, '.', '');
	$cpc= number_format($b-0.01, 2, '.', '');
	$cpf= "<input type='text' name='cp' id='cp' value='$cp' size='5' maxlength='5'>";//$cp;//number_format($cp, 2, '.', '');
	$cc	= number_format($cp-0.01, 2, '.', '');
	$cf	= "<input type='text' name='c' id='c' value='$c' size='5' maxlength='5'>";//$c;//number_format($c, 2, '.', '');
	$dpc= number_format($c-0.01, 2, '.', '');
	$dpf= "<input type='text' name='dp' id='dp' value='$dp' size='5' maxlength='5'>";//$dp;//number_format($dp, 2, '.', '');
	$dc	= number_format($dp-0.01, 2, '.', '');
	$df	= "<input type='text' name='d' id='d' value='$d' size='5' maxlength='5'>";//$d;//number_format($d, 2, '.', '');
	$fc	= number_format($d-0.01, 2, '.', '');
	$ff	= $translate["LOWER"];//"0.00";
	
	$gx=0;
	$gn=0;
	if($gradetype==1||$gradetype==2)
	{
		$af = $a;//number_format($a, 2, '.', '');
		$bpc= number_format($a-0.01, 2, '.', '');
		$bpf= $bp;//number_format($bp, 2, '.', '');
		$bc	= number_format($bp-0.01, 2, '.', '');
		$bf	= $b;//number_format($b, 2, '.', '');
		$cpc= number_format($b-0.01, 2, '.', '');
		$cpf= $cp;//number_format($cp, 2, '.', '');
		$cc	= number_format($cp-0.01, 2, '.', '');
		$cf	= $c;//number_format($c, 2, '.', '');
		$dpc= number_format($c-0.01, 2, '.', '');
		$dpf= $dp;//number_format($dp, 2, '.', '');
		$dc	= number_format($dp-0.01, 2, '.', '');
		$df	= $d;//number_format($d, 2, '.', '');
		switch($grade_max)
		{
			case 'A': $gx=7; $ac	= $translate["UPPER"]; break;
			case 'B+':$gx=6; $bpc	= $translate["UPPER"]; break;
			case 'B': $gx=5; $bc	= $translate["UPPER"]; break;
			case 'C+':$gx=4; $cpc	= $translate["UPPER"]; break;
			case 'C': $gx=3; $cc	= $translate["UPPER"]; break;
			case 'D+':$gx=2; $dpc	= $translate["UPPER"]; break;
			case 'D': $gx=1; $dc	= $translate["UPPER"]; break;
			case 'F':	$gx=0; $fc	= $translate["UPPER"]; break;
		}
		switch($grade_min)
		{
			case 'A': $gn=7; $af = $translate["LOWER"]; break;
			case 'B+':$gn=6; $bpf= $translate["LOWER"]; break;
			case 'B': $gn=5; $bf = $translate["LOWER"]; break;
			case 'C+':$gn=4; $cpf= $translate["LOWER"]; break;
			case 'C': $gn=3; $cf = $translate["LOWER"]; break;
			case 'D+':$gn=2; $dpf= $translate["LOWER"]; break;
			case 'D':	$gn=1; $df = $translate["LOWER"]; break;
			case 'F':	$gn=0; $ff = $translate["LOWER"]; break;
		}
	}
?>			
<input type="hidden" name="s" id="s" value="<?=$s?>">
<? if(!$gradetype || ($gn<=7 && $gx>=7)){?>
			<tr>
				<td height="21" align="center" bgcolor="#ECECEC">A&nbsp;&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$af?></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$ac?></td>
				<td></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Excellent</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;4.00</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=6 && $gx>=6)){?>
			<tr>
				<td height="21" align="center" bgcolor="#FDF3E7">B+</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$bpf?></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$bpc?></td>
				<td></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;Very Good</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">&nbsp;3.50</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=5 && $gx>=5)){?>
			<tr>
				<td height="21" align="center" bgcolor="#ECECEC">B&nbsp;&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$bf?></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$bc?></td>
				<td></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Good</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;3.00</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=4 && $gx>=4)){?>
			<tr>
				<td height="21" align="center" bgcolor="#FDF3E7">C+</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$cpf?></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$cpc?></td>
				<td></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;Fairly Good</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">&nbsp;2.50</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=3 && $gx>=3)){?>
			<tr>
				<td height="21" align="center" bgcolor="#ECECEC">C&nbsp;&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$cf?></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$cc?></td>
				<td></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Fair</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;2.00</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=2 && $gx>=2)){?>
			<tr>
				<td height="21" align="center" bgcolor="#FDF3E7">D+</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$dpf?></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$dpc?></td>
				<td></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;Poor</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">&nbsp;1.50</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=1 && $gx>=1)){?>
			<tr>
				<td height="21" align="center" bgcolor="#ECECEC">D&nbsp;&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$df?></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><?=$dc?></td>
				<td></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Very Poor </td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;1.00</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? } if(!$gradetype || ($gn<=0 && $gx>=0)){?>
			<tr>
				<td height="21" align="center" bgcolor="#FDF3E7">F&nbsp;&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$ff?></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><?=$fc?></td>
				<td></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;Failed</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">&nbsp;0.00</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<? }?>
			<!--tr>
				<td height="21" align="center" bgcolor="#ECECEC">FA</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;</td>
				<td></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Failed, Insufficient Attendance</td>
				<td></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr>
				<td height="21" align="center" bgcolor="#FDF3E7">FE</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">&nbsp;</td>
				<td></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;Failed, Absent from Examination</td>
				<td></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr-->
<?
}
else
{
	$sc = "100";//"100.00";
	$sf = $s;//number_format($s, 2, '.', '');
	$uc	= number_format($s-0.01, 2, '.', '');
	$uf	= "0";//"0.00";
?>			
<input type="hidden" name="a" id="a" value="<?=$a?>">
<input type="hidden" name="bp" id="bp" value="<?=$bp?>">
<input type="hidden" name="b" id="b" value="<?=$b?>">
<input type="hidden" name="cp" id="cp" value="<?=$cp?>">
<input type="hidden" name="c" id="c" value="<?=$c?>">
<input type="hidden" name="dp" id="dp" value="<?=$dp?>">
<input type="hidden" name="d"  id="d" value="<?=$d?>">
			<tr>
				<td height="21" align="center" bgcolor="#ECECEC">S</td>
				<td bgcolor="#ECECEC"></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="s" id="s" value="<?=$sf?>" size="5" maxlength="5"></td>
				<td bgcolor="#ECECEC"></td>
				<td align="center" bgcolor="#ECECEC"><?=$sc?></td>
				<td bgcolor="#ECECEC"></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Satisfactory</td>
				<td bgcolor="#ECECEC"></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr>
				<td height="21" align="center" bgcolor="#FDF3E7">U</td>
				<td bgcolor="#FDF3E7"></td>
				<td align="center" bgcolor="#FDF3E7"><?=$uf?></td>
				<td bgcolor="#FDF3E7"></td>
				<td align="center" bgcolor="#FDF3E7"><?=$uc?></td>
				<td bgcolor="#FDF3E7"></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;Unsatisfactory</td>
				<td bgcolor="#FDF3E7"></td>
				<td align="left" bgcolor="#FDF3E7">&nbsp;</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
}
?>			
			<tr>
				<td height="21" align="center" bgcolor="#ECECEC">I</td>
				<td bgcolor="#ECECEC"></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;</td>
				<td bgcolor="#ECECEC"></td>
				<td align="center" bgcolor="#ECECEC">&nbsp;</td>
				<td bgcolor="#ECECEC"></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;Incomplete</td>
				<td bgcolor="#ECECEC"></td>
				<td align="left" bgcolor="#ECECEC">&nbsp;</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
    </table></td>
  </tr>
	<tr>
		<td height="21" align="left" bgcolor="#FDF3E7"></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
	<tr>
		<td height="18" align="left">
		<input type="button" value=<?=$translate["SAVE"]?> title=<?=$translate["SAVE_CRITERIA_FOR_CUTTING_GRADES"]?> <?=$subject_id?> onClick="return go2SaveCriteria()"></td>
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
</html>
<? set_time_limit(30); ?>
<? mysql_close(); ?>
<? ob_end_flush(); ?>