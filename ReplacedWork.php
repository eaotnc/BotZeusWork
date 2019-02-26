<? session_start(); ?>
<? ob_start();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>www.reg.kmitl.ac.th</title>
<META http-equiv=Content-Language content=th>
<META http-equiv=Content-Type content="text/html; charset=tis-620">
<LINK href="../css/registrar.css" type="text/css" rel="stylesheet">
<SCRIPT language=JavaScript src="../scripts/allscript.js"></SCRIPT>
<?
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

if(!$year||!$semester||!$subject_id||!$keys_id)
{
	$msg = urlencode("".$translate["STUDENT_INFORMATION_WAS_NOT_FOUND_FOR_RECORDING_THE_SPECIFIED_GRADE."]."...");
	header("Location: ../u_teacher_v20/grading_record.php?msg=$msg");
	die();
}

// establish the link of mysql connection
/*
mysql_connect(HOST, USERNAME, PASSWORD) or die( mysql_error() );// die ("".$translate["CAN'T_CONNECT_TO_THE_SERVER"]."");
mysql_select_db(DB_NAME) or die( mysql_error() );// die( "".$translate["UNABLE_TO_CONTACT_THE_DATABASE."]."");
*/
set_time_limit(30*60);

// prepare the header of this section
require_once("grading_record_header.php");
?>
<script language="JavaScript" type="text/JavaScript">
function go2Criteria()
{
	window.status	= "".$translate["PLEASE_WAIT"]."...".$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]."...";
	document.save.action="grading_record_criteria.php";
	document.save.submit();
}
function go2Factor()
{
	window.status	= "".$translate["PLEASE_WAIT"]."...".$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]."...";
	document.save.action="grading_record_factor.php";
	document.save.submit();
}
function go2Score()
{
	window.status	= "".$translate["PLEASE_WAIT"]."...".$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]."...";
	document.save.action="grading_record_score.php";
	document.save.submit();
}
function go2Show()
{
	window.status	= "".$translate["PLEASE_WAIT"]."...".$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]."...";
	document.save.action="grading_record_show.php";
	document.save.submit();
}
function go2Table()
{
	window.status	= "".$translate["PLEASE_WAIT"]."...".$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]."...";
	document.save.action="grading_record_table.php";
	document.save.submit();
}
function go2SaveFactor()
{
	if(!validateFactor())
		return false;
	window.status	= "".$translate["PLEASE_WAIT"]."...".$translate["THE_PROGRAM_IS_CURRENTLY_SEARCHING_FOR_INFORMATION."]."...";
	document.save.action="grading_record_factor_save.php";
	document.save.submit();
}
function validateFactor()
{
	var f1_del = document.getElementById('f1_del').checked; 
	var f2_del = document.getElementById('f2_del').checked; 
	var f3_del = document.getElementById('f3_del').checked; 
	var f4_del = document.getElementById('f4_del').checked; 
		
	var percent= 0;
	percent += f1_del==false ? 0 : eval(document.getElementById('f1_per').value); 
	percent += f2_del==false ? 0 : eval(document.getElementById('f2_per').value); 
	percent += f3_del==false ? 0 : eval(document.getElementById('f3_per').value); 
	percent += f4_del==false ? 0 : eval(document.getElementById('f4_per').value); 

	if(percent!=100)
	{
		alert("".$translate["TOTAL_PERCENTAGE_VALUE"]." ("+percent+"%) ".$translate["MUST_BE_EQUAL_TO"]." 100% ".$translate["ONLY_THAT"]." \n\n".$translate["SHOULD_AT_LEAST_HAVE_AN_EVALUATION_IN_THE_FORM"]." Final Exam\n".$translate["IN_THE_LAST_ORDER"]."");
		return false;
	}
	return true;
}
function disableInput(I)
{
//	document.getElementById('f'+I+'_num').style.visibility = "hidden";
	document.getElementById('f'+I+'_txt').style.visibility = "hidden";
	document.getElementById('f'+I+'_tot').style.visibility = "hidden";
	document.getElementById('f'+I+'_per').style.visibility = "hidden";
//	document.getElementById('f'+I+'_pos').style.visibility = "hidden";
//	document.getElementById('f'+I+'_exm').style.visibility = "hidden";
//	document.getElementById('f'+I+'_txt').disabled = 1;
//	document.getElementById('f'+I+'_tot').disabled = 1;
//	document.getElementById('f'+I+'_per').disabled = 1;
	document.getElementById('f'+I+'_pos').disabled = 1;
	document.getElementById('f'+I+'_exm').disabled = 1;
}
function enableInput(I)
{
//	document.getElementById('f'+I+'_num').style.visibility = "visible";
	document.getElementById('f'+I+'_txt').style.visibility = "visible";
	document.getElementById('f'+I+'_tot').style.visibility = "visible";
	document.getElementById('f'+I+'_per').style.visibility = "visible";
//	document.getElementById('f'+I+'_pos').style.visibility = "visible";
//	document.getElementById('f'+I+'_exm').style.visibility = "visible";
//	document.getElementById('f'+I+'_txt').disabled = 0;
//	document.getElementById('f'+I+'_tot').disabled = 0;
//	document.getElementById('f'+I+'_per').disabled = 0;
	document.getElementById('f'+I+'_pos').disabled = 0;
	document.getElementById('f'+I+'_exm').disabled = 0;
}
</script>
</head>
<body bgColor="#ffffff" leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<center>
<form name="save" method="post" action="../u_teacher_v20/grading_record_facor.php" target="_top" enctype="multipart/form-data">
<input type="hidden" name="dontlog" id="dontlog" value="<?=$dontlog?>">
<input type="hidden" name="year" id="year" value="<?=$year?>">
<input type="hidden" name="semester" id="semester" value="<?=$semester?>">
<input type="hidden" name="subject_id" id="subject_id" value="<?=$subject_id?>">
<input type="hidden" name="keys_id" id="keys_id" value="<?=$keys_id?>">
<table cellspacing="0" cellpadding="0" width="750" border="0">
  <TBODY>
  <TR>
    <TD height="10"></TD>
  </TR>
  <TR>
    <TD align="center" vAlign=middle><a href="../index.php"><img src="images/u_teacher2.jpg" alt="".$translate["BACK__HOME"]."" width="750" height="40" border="0"></a></TD>
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
		<td height="18" align="center" bgcolor="#E1E1E1"><strong>".$translate["TERM"]."&nbsp;<?=$semester?>&nbsp;&nbsp;&nbsp;<?=$translate["YEAR"]?>&nbsp;<?=$year?></strong></td>
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
    <TD height=21 align="center" background="../images/horline_c750o.jpg"><a href="#" onClick="return go2Criteria()">".$translate["SET_THE_SCORE_CRITERIA"]."</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$translate["SET_THE_SCORE_RANGE"]."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Score()">".$translate["SCORE_RECORD"]."</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Show()">".$translate["CHECK_THE_SCORE"]."</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="return go2Table()"><?=$translate["SCATTER_TABLE"]?></a></TD>
  </TR>
	<tr>
		<td height="1"></td>
	</tr>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
<?
if(!$system_cut)
{
?>
	<tr>
		<td bgcolor="#ECECEC"><table width="750" border="1" cellpadding="0" cellspacing="0">
			<tr>
				<td height="32" align="center" bgcolor="#F0F0F0">
				<?=$translate["YOU_DEFINE_THE_CUT_GRADE_MODEL_OF_THIS_GROUP_BY_CHOOSING_NOT_TO_USE_THE_GRADE_CUTTING_SYSTEM_TO_HELP_PROCESS."]?>...<?=$translate["THEREFORE_DO_NOT_HAVE_TO_ENTER_THE_SCORING_RANGE"]?> 
				</td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
<?
}
else
{
?>	
	<tr>
		<td height="1"></td>
	</tr>
  <tr>
		<td><table width="750" border=0 align="center" cellpadding=0 cellspacing=0>
			<tr>
				<td width="70" height="21" align="center" bgcolor="#E1E1E1"><strong><?=$translate["RANK"]?></strong></td>
				<td width="1" ></td>
				<td width="*" align="center" bgcolor="#E1E1E1"><strong><?=$translate["EVALUATION"]?></strong></td>
				<td width="1" ></td>
				<td width="100" align="center" bgcolor="#E1E1E1"><strong><?=$translate["FULL_SCORE"]?></strong></td>
				<td width="1" ></td>
				<td width="100" align="center" bgcolor="#E1E1E1"><strong><?=$translate["PERCENTAGE"]?></strong></td>
				<td width="1" ></td>
				<td width="120" align="center" bgcolor="#E1E1E1"><strong><?=$translate["CATEGORY"]?></strong></td>
				<td width="1" ></td>
				<td width="100" align="center" bgcolor="#E1E1E1"><strong><?=$translate["ANNOUNCED"]?></strong></td>
				<td width="1" ></td>
				<td width="120" align="center" bgcolor="#E1E1E1"><strong><?=$translate["USE_THIS_RATING_RANGE"]?></strong></td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
if($gradetype<3)
{
	$ac = "100.00";	
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
	$fc	= number_format($d-0.01, 2, '.', '');
	$ff	= "0.00";
?>			
<input type="hidden" name="s" id="s" value="<?=$s?>">
			<tr title="".$translate["THE_RESULT_OF_THIS_SCORE_WILL_BE_DISPLAYED_IN_THE_COLUMN_THAT"]." 1 ".$translate["OF_THE_SCORE_BOX_IN_THE_SCORE_RECORD_TABLE"]."">
				<td height="21" align="center" bgcolor="#ECECEC"><div id="f1_num">1</div></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="f1_txt" id="f1_txt" value="<?=$f1_txt?>" size="16" maxlength="16" <?=!$f1_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="f1_tot" id="f1_tot" value="<?=$f1_tot?>" size="5" maxlength="5" <?=!$f1_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="f1_per" id="f1_per" value="<?=$f1_per?>" size="5" maxlength="5" <?=!$f1_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">
				<!--input type="f1_exm" name="f1_exm" value="<?=$f1_exm?>" size="3" maxlength="3"-->
				<select name='f1_exm' id='f1_exm' style="width:100px" <?=!$f1_del?"disabled":NULL?>>
					<!--option value="0" <?= ($f1_exm==0)?"selected":NULL?>>Final Exam</option>
					<option value="1" <?= ($f1_exm==1)?"selected":NULL?>>Midtrem Exam</option-->
					<option value="2" <?= ($f1_exm==2)?"selected":NULL?>>Quiz</option>
          <option value="3" <?= ($f1_exm==3)?"selected":NULL?>>Other...</option>
				</select>				
				</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">
				<input type="checkbox" name="f1_pos" id="f1_pos" value="1" <?=!$f1_pos?NULL:"checked"?> <?=!$f1_del?"disabled":NULL?>><?=$translate["ANNOUNCE"]?>
				</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">
				<!--input type="checkbox" name="f1_del" value="<?=$f1_del?>" size="3" maxlength="3"-->
				<input name="f1_del" type="radio" id="f1_del" value="1" <?=$f1_del==1?"checked":NULL?> onClick="enableInput('1')"><?=$translate["USE"]?>&nbsp;
				<input name="f1_del" type="radio" id="f1_del" value="0" <?=!$f1_del?"checked":NULL?> onClick="disableInput('1')"><?=$translate["NOT_USED"]?> &nbsp;
				</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr title="".$translate["THE_RESULT_OF_THIS_SCORE_WILL_BE_DISPLAYED_IN_THE_COLUMN_THAT"]." 2 ".$translate["OF_THE_SCORE_BOX_IN_THE_SCORE_RECORD_TABLE"]."">
				<td height="21" align="center" bgcolor="#FDF3E7"><div id="f2_num">2</div></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><input type="text" name="f2_txt" id="f2_txt" value="<?=$f2_txt?>" size="16" maxlength="16" <?=!$f2_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><input type="text" name="f2_tot" id="f2_tot" value="<?=$f2_tot?>" size="5" maxlength="5" <?=!$f2_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><input type="text" name="f2_per" id="f2_per" value="<?=$f2_per?>" size="5" maxlength="5" <?=!$f2_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><select name='f2_exm' id='f2_exm' style="width:100px" <?=!$f2_del?"disabled":NULL?>>
          <!--option value="0" <?= ($f2_exm==0)?"selected":NULL?>>Final Exam</option>
          <option value="1" <?= ($f2_exm==1)?"selected":NULL?>>Midtrem Exam</option-->
          <option value="2" <?= ($f2_exm==2)?"selected":NULL?>>Quiz</option>
          <option value="3" <?= ($f2_exm==3)?"selected":NULL?>>Other...</option>
        </select></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">
				<input type="checkbox" name="f2_pos" id="f2_pos" value="1" <?=!$f2_pos?NULL:"checked"?> <?=!$f2_del?"disabled":NULL?>><?=$translate["ANNOUNCE"]?>
				</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">
				<input name="f2_del" type="radio" id="f2_del" value="1" <?=$f2_del==1?"checked":NULL?> onClick="enableInput('2')"><?=$translate["USE"]?>&nbsp;
				<input name="f2_del" type="radio" id="f2_del" value="0" <?=!$f2_del?"checked":NULL?> onClick="disableInput('2')"><?=$translate["NOT_USED"]?> &nbsp;
				</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr title="".$translate["THE_RESULT_OF_THIS_SCORE_WILL_BE_DISPLAYED_IN_THE_COLUMN_THAT"]." 3 ".$translate["OF_THE_SCORE_BOX_IN_THE_SCORE_RECORD_TABLE"]."">
				<td height="21" align="center" bgcolor="#ECECEC"><div id="f3_num">3</div></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="f3_txt" id="f3_txt" value="<?=$f3_txt?>" size="16" maxlength="16" <?=!$f3_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="f3_tot" id="f3_tot" value="<?=$f3_tot?>" size="5" maxlength="5" <?=!$f3_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><input type="text" name="f3_per" id="f3_per" value="<?=$f3_per?>" size="5" maxlength="5" <?=!$f3_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC"><select name='f3_exm'  id='f3_exm' style="width:100px" <?=!$f1_del?"disabled'":NULL?>>
          <!--option value="0" <?= ($f3_exm==0)?"selected":NULL?>>Final Exam</option-->
          <option value="1" <?= ($f3_exm==1)?"selected":NULL?>>Midterm Exam</option>
          <!--option value="2" <?= ($f3_exm==2)?"selected":NULL?>>Quiz</option>
          <option value="3" <?= ($f3_exm==3)?"selected":NULL?>>Other...</option-->
        </select></td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">
				<input type="checkbox" name="f3_pos" id="f3_pos" value="1" <?=!$f3_pos?NULL:"checked"?> <?=!$f3_del?"disabled":NULL?>><?=$translate["ANNOUNCE"]?>
				</td>
				<td></td>
				<td align="center" bgcolor="#ECECEC">
				<input name="f3_del" type="radio" id="f3_del" value="1" <?=$f3_del==1?"checked":NULL?> onClick="enableInput('3')"><?=$translate["USE"]?>&nbsp;
				<input name="f3_del" type="radio" id="f3_del" value="0" <?=!$f3_del?"checked":NULL?> onClick="disableInput('3')"><?=$translate["NOT_USED"]?> &nbsp;
				</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr title="".$translate["THE_RESULT_OF_THIS_SCORE_WILL_BE_DISPLAYED_IN_THE_COLUMN_THAT"]." 4 ".$translate["OF_THE_SCORE_BOX_IN_THE_SCORE_RECORD_TABLE"]."">
				<td height="21" align="center" bgcolor="#FDF3E7"><div id="f4_num">4</div></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><input type="text" name="f4_txt" id="f4_txt" value="<?=$f4_txt?>" size="16" maxlength="16" <?=!$f4_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><input type="text" name="f4_tot" id="f4_tot" value="<?=$f4_tot?>" size="5" maxlength="5" <?=!$f4_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><input type="text" name="f4_per" id="f4_per" value="<?=$f4_per?>" size="5" maxlength="5" <?=!$f4_del?"style='visibility:hidden'":NULL?>></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7"><select name='f4_exm' id='f4_exm' style="width:100px" <?=!$f4_del?"disabled":NULL?>>
          <option value="0" <?= ($f4_exm==0)?"selected":NULL?>>Final Exam</option>
          <!--option value="1" <?= ($f4_exm==1)?"selected":NULL?>>Midtrem Exam</option>
          <option value="2" <?= ($f4_exm==2)?"selected":NULL?>>Quiz</option>
          <option value="3" <?= ($f4_exm==3)?"selected":NULL?>>Other...</option-->
        </select></td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">
				<input type="checkbox" name="f4_pos" id="f4_pos" value="1" <?=!$f4_pos?NULL:"checked"?> <?=!$f4_del?"disabled":NULL?>><?=$translate["ANNOUNCE"]?>
				</td>
				<td></td>
				<td align="center" bgcolor="#FDF3E7">
				<input name="f4_del" type="radio" id="f4_del" value="1" <?=$f4_del==1?"checked":NULL?> onClick="enableInput('4')"><?=$translate["USE"]?>&nbsp;
				<input name="f4_del" type="radio" id="f4_del" value="0" <?=!$f4_del?"checked":NULL?> onClick="disableInput('4')"><?=$translate["NOT_USED"]?> &nbsp;
				</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
			<tr>
				<td height="21" align="center" bgcolor="#E1E1E1">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#E1E1E1">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#E1E1E1">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#E1E1E1">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#E1E1E1">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#E1E1E1">&nbsp;</td>
				<td></td>
				<td align="center" bgcolor="#E1E1E1">&nbsp;</td>
			</tr>
			<tr>
				<td height="1"></td>
			</tr>
<?
}
else
{
	$sc = ''.$translate['RECORD'].'';
	$sf = $s;//number_format($s, 2, '.', '');
	$uc	= number_format($s-0.01, 2, '.', '');
	$uf	= "0.00";
?>			
<input type='hidden' name="a" id="a" value="<?=$a?>">
<input type="hidden" name="bp" id="bp" value="<?=$bp?>">
<input type="hidden" name="b" id="b" value="<?=$b?>">
<input type="hidden" name="cp" id="cp" value="<?=$cp?>">
<input type="hidden" name="c" id="c" value="<?=$c?>">
<input type="hidden" name="dp" id="dp" value="<?=$dp?>">
<input type="hidden" name="d" id="d" value="<?=$d?>">
<?
}
?>
    </table></td>
  </tr>
	<tr>
		<td height="21" align="left" bgcolor="#ECECEC"></td>
	</tr>
	<tr>
		<td height="1"></td>
	</tr>
	
	<tr>
		<td height="18" align="left">
		<input type="button" value=''.$translate['RECORD'].''title="".$translate["RECORDING_CRITERIA_FOR_CUTTING_GRADES"]." <?=$subject_id?>" onClick="return go2SaveFactor()"></td>
	</tr>
<?
}
?>	
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