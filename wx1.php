<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
if(!$db=mysqli_connect('10.5.5.5','nas_web','nas_web'))
{
	echo "<p>0、数据库连接失败！".mysqli_error();
	exit(1);
}
//设置连接数据库的字符集
mysqli_query($db,"set names utf8");
//连接数据库
mysqli_select_db($db,"nas_web");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>维修登记</title>
<style type='text/css'>
input {
	border:0px;
}
textarea {
	border:0px;
}
#sbrst {
position:fixed;
background:#66FFCC;
top:0px;
bottom:0px;
right:0px;
left:95%;
}
#bdif{
position:fixed;
background:white;
width:95%;
height:95%;
overflow:auto; 
}
#errlog
{
	position:fixed;
	background:red;
	color:white;
	width:40%;
	height:20%;
	top:40%;
	left:30%;
	font-size:40px;
	z-index:2;
}
</style>
<script language="javascript">
 function imgframePrint(objs)  
 {  
		var turl = document.getElementById(objs).src;
        var newW = window.open(turl);
        newW.print(); 
 }
  function imgframeView(objs)  
 {  
		var turl = document.getElementById(objs).src;
        var newW = window.open(turl,'new','height=400px,width=400px,top=100px,left=100px,');
        //newW.print(); 
 }
//打印图层
 function divPrint(objs)  
 {  
	//var obj=document.getElementById(objs);
	//var newWindow=window.open("打印窗口","_blank");//打印窗口要换成页面的url
    //var docStr = obj.innerHTML;
    //newWindow.document.write(docStr);
    //newWindow.document.close();
    //newWindow.print();
    //newWindow.close();
	var headstr = "<html><head><title></title></head><body>"; 
	var footstr = "</body>"; 
	var newstr = document.all.item(objs).innerHTML; 
	var oldstr = document.body.innerHTML; 
	document.body.innerHTML = headstr+newstr+footstr; 
	window.print(); 
	document.body.innerHTML = oldstr; 
	return false; 
 }
 function exportToWord(controlId) {
             var control = document.getElementById(controlId);
             try {
                 var oWD = new ActiveXObject("Word.Application");
                var oDC = oWD.Documents.Add("", 0, 1);
                var oRange = oDC.Range(0, 1);
               var sel = document.body.createTextRange();
               try {
                   sel.moveToElementText(control);
               } catch (notE) {
                    alert("导出数据失败，没有数据可以导出。");
                    window.close();
                   return;
               }
                sel.select();
                sel.execCommand("Copy");
                oRange.Paste();
                oWD.Application.Visible = true;
                //window.close();
            }
            catch (e) {
                alert("导出数据失败，需要在客户机器安装Microsoft Office Word(不限版本)，将当前站点加入信任站点，允许在IE中运行ActiveX控件。");
                try { oWD.Quit(); } catch (ex) { }
                //window.close();
            }
        }
  function hide(objs){  
		view_file.style.visibility="hidden";  
		view_button.style.visibility="hidden"; 
		view_title.style.visibility="hidden";
		view_store.style.visibility="visible"; 		
		document.getElementById(objs).pause();
  } 
 </script>
</head>
<?php
if($_POST['submit']=="保存")
{
	//echo "<div id='errlog' name='errlog'>此功能未建设，本网页只能查看数据！<p><a href='?".$_SERVER['QUERY_STRING']."'><button>关闭</button></a></p></div>";
	$cinfo[0]=$_POST['cinfo1'];
	$cradio[0]=$_POST['cradio1'];
	$cmony[0]=$_POST['cmony1'];
	$cnm[0]=$_POST['cnm1'];
	
	$cinfo[1]=$_POST['cinfo2'];
	$cradio[1]=$_POST['cradio2'];
	$cmony[1]=$_POST['cmony2'];
	$cnm[1]=$_POST['cnm2'];
	
	$cinfo[2]=$_POST['cinfo3'];
	$cradio[2]=$_POST['cradio3'];
	$cmony[2]=$_POST['cmony3'];
	$cnm[2]=$_POST['cnm3'];
	
	$cinfo[3]=$_POST['cinfo4'];
	$cradio[3]=$_POST['cradio4'];
	$cmony[3]=$_POST['cmony4'];
	$cnm[3]=$_POST['cnm4'];
	
	$cinfo[4]=$_POST['cinfo5'];
	$cradio[4]=$_POST['cradio5'];
	$cmony[4]=$_POST['cmony5'];
	$cnm[4]=$_POST['cnm5'];
	//print_r($cinfo);
	//print_r($cradio);
	//print_r($cmony);
	//print_r($cnm);
	$hx=mysqli_query($db,"select * from wxfault where fltid=".$_POST['xdjid']);
	if($hxid=mysqli_fetch_array($hx))
	{
		if($_POST['clnum']!="")
		{
			$hd1=mysqli_query($db,"select max(fsvid) from wxsolve where fltid=".$_POST['xdjid']);
			$hd2=mysqli_fetch_array($hd1);
			$hd3=$hd2[0];
			$hd4=$hd3+1;
			if($_POST['xdjid']!=""&&$cinfo[$hd3]!=""&&$cnm[$hd3]!=""&&$cmony[$hd3]!=""&&$hd3<5)
			{
				$sisql="insert into wxsolve value(".$_POST['xdjid'].",$hd4,'".$cinfo[$hd3]."',".$cradio[$hd3].",".$cmony[$hd3].",'".$cnm[$hd3]."',now(),'')";
			}
			if(mysqli_query($db,$sisql))
			{
				echo "<script>window.alert('保存处理结果 $hd4 成功');window.location.href='wx1.php?djid=".$_POST['xdjid']."';</script>";
			}
			else
			{
				echo "<script>window.alert('保存处理结果 $hd4 失败，请修改信息');history.go(-1);</script>";
			}
		}
		else
		{
			if($_POST['xdjid']!=""&&$cinfo[0]!=""&&$cnm[0]!=""&&$cmony[0]!="")
			{
				$sisql="insert into wxsolve value(".$_POST['xdjid'].",1,'".$cinfo[0]."',".$cradio[0].",".$cmony[0].",'".$cnm[0]."',now(),'')";
			}
			if(mysqli_query($db,$sisql))
			{
				echo "<script>window.alert('保存处理结果成功');window.location.href='wx1.php?djid=".$_POST['xdjid']."';</script>";
			}
			else
			{
				echo "<script>window.alert('保存处理结果失败，请修改信息');history.go(-1);</script>";
			}
		}
	}
	else
	{
		if($_POST['djname']!=""&&$_POST['djtelp']!=""&&$_POST['djinfo']&&$_POST['djinfo']!="")
		{
			$djsql="insert into wxfault value(".$_POST['xdjid'].",'".$_SERVER['REMOTE_ADDR']."','".$_POST['djname']."','".$_POST['djtelp']."','".$_POST['djinfo']."',".$_POST['djbs'].",'".$_POST['djpj']."',now(),1,'')";
		}
		if(mysqli_query($db,$djsql))
		{
			echo "<script>window.alert('保存登记成功');window.location.href='wx1.php?djid=".$_POST['xdjid']."';</script>";
		}
		else
		{
			echo "<script>window.alert('保存登记失败，请修改信息');history.go(-1);</script>";
		}
	}
}
//设置登记编号
$disd=array("readonly","readonly","readonly","readonly","readonly");
$disr=array("disabled","disabled","disabled","disabled","disabled");
if($_GET['djid']!="")
{
	$jid=$_GET['djid'];
	echo "\n";
	$stbf="select * from wxfault where fltid=$jid";
	$stbs="select * from wxsolve where fltid=$jid";
	$f1=mysqli_query($db,$stbf);
	$s1=mysqli_query($db,$stbs);
	$fa=mysqli_fetch_array($f1);
	while($sp=mysqli_fetch_array($s1))
	{
		$i=$sp['fsvid']-1;
		$sa[$i]=$sp;
	}
	$djdis="readonly";
	$djrdo="disabled";
	for($j=0;$j<5;$j++)
	{
		if($i>=0&&$j==($i+1))
		{
			$disd[$j]="";
			$disr[$j]="";
		}
		if($i==NULL&&$i!=0&&$j==0)
		{
			$disd[$j]="";
			$disr[$j]="";
		}
	}
}
else
{
	$jid=date('YmdHis');
}
?>
<body>
<div id='bdif' name='bdif'>
<center>
<form action="<?php echo $_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'];?>" method='post'>
<table width="595" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5" height="71">&nbsp;</td>
<td width="80" valign="top"><img onclick="javascript:window.open('/qzcode.php?qrinfo=http://lyclub.imwork.net:82/wx1.php?djid=<?php echo $jid; ?>','维修登记单网址的二维码','height=400px,width=400px,top=50px, left=50px, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no')" src="/qzcode.php?qrinfo=http://lyclub.imwork.net:82/wx1.php?djid=<?php echo $jid; ?>" alt="维修登记单网址的二维码" title="维修登记单网址的二维码，单击可以放大" width="80" height="80" id="qrinfo" /></td>
<td width="18">&nbsp;</td>
<td width="249" align="center" nowrap="nowrap"><font style='font-size:32px;'>维修登记单</font><br/><font style='font-size:8px;'><?php echo "客户地址:".$fa['frtip']."<input type='hidden' id='djip' name='djip' value='".$fa['frtip']."' />"; ?></font></td>
<td width="13">&nbsp;</td>
<td width="211" valign="top">
		  <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="211" height="23" align="center" valign="middle">&#9698登记编号<?php echo $jid; ?>&#9699            
                <input name="xdjid" id="xdjid" type="hidden" value="<?php echo $jid; ?>"/></td>
                </tr>
            <tr>
              <td height="48" valign="top" align="right"><img src="code128.php?code=<?php echo $jid; ?>" alt="登记编号条形码" title="登记编号条形码" id="tiaoma" /></td>
                </tr>
            </table>
</td>
</tr>
</table>
			
<table width="595" border="1" cellpadding="0" cellspacing="0">
<tr>
<td width="20%" height="24" valign="top" align="right">客户名称：</td>
<td width="20%">
<input name="djname" type="text" value="<?php echo $fa['fname']; ?>" <?php echo $djdis; ?>/>            </td>
<td width="20%" valign="top" align="right">联系电话：</td>
<td width="30%" valign="middle" align="left"><input name="djtelp" id="djtelp" type="text" value="<?php echo $fa['ftelp']; ?>" <?php echo $djdis; ?>/></td>
</tr>
<tr>
<td height="74" align="center" valign="middle">故障&nbsp;&nbsp;<br/>&nbsp;&nbsp;说明</td>
<td colspan="3" valign="top">
<textarea name="djinfo" cols="65" rows="5" <?php echo $djdis; ?>><?php echo $fa['finfo']; ?></textarea>
</td>
</tr>
<tr>
<td height="29" valign="middle" align="right">随机附件：</td>
<td colspan="2" align="left" valign="middle"><input name="djpj" id="djpj" type="text" size="35" value="<?php echo $fa['fware'] ?>" <?php echo $djdis; ?>/></td>
<td align="center" valign="middle">质保              
                  <label>
				<?php
				if($fa['fbao']==0)
                    echo "<input type=\"radio\" name=\"djbs\" id=\"djbs\" value=\"1\" checked $djrdo />";
				else	
					echo "<input type=\"radio\" name=\"djbs\" id=\"djbs\" value=\"1\" $djrdo />";
				?>
                    保外</label>
                  <label>
				<?php
                if($fa['fbao']==1)
                    echo "<input type=\"radio\" name=\"djbs\" id=\"djbs\" value=\"0\" checked $djrdo />";
				else	
					echo "<input type=\"radio\" name=\"djbs\" id=\"djbs\" value=\"0\" $djrdo />";
				?>
                  保内</label>              
</td>
</tr>
</table>
<hr/>
<?php echo "<input type='hidden' id='clnum' name='clnum' value='$i' />"; ?>
<table width="595" border="1" cellpadding="0" cellspacing="0">
<td width="20%" height="74" align="center" valign="middle">故障<br/>处理<br/>结果</td>
<td colspan="3" valign="top">
<textarea name="cinfo1" cols="65" rows="5"<?php echo $disd[0]; ?>><?php echo $sa[0]['sinfo']; ?></textarea>
</td>
</tr>
<tr>
<td width="20%" height="24" valign="top" align="right">是否解决：</td>
<td width="15%"><label>
				<?php
				if($sa[0]['fstus']==1)
                    echo "<input type=\"radio\" name=\"cradio1\" id=\"cradio1\" value=\"1\" checked ".$disr[0]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio1\" id=\"cradio1\" value=\"1\" ".$disr[0]." />";
				?>
                    是</label>
                  <label>
				<?php
                if($sa[0]['fstus']==0)
                    echo "<input type=\"radio\" name=\"cradio1\" id=\"cradio1\" value=\"0\" checked ".$disr[0]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio1\" id=\"cradio1\" value=\"0\" ".$disr[0]." />";
				?>
                  否</label>  </td>
<td width="20%" valign="top" align="right">费用<input type="text" id="cmony1" name="cmony1" value="<?php echo $sa[0]['fmony']; ?>" size="5" <?php echo $disd[0]; ?> />
元</td>
<td width="35%" valign="middle" align="left">维修员：<input id="cnm1" name="cnm1" type="text" value="<?php echo $sa[0]['svuse']; ?>" size='10' <?php echo $disd[0]; ?>/></td>
</tr>
</table>
<hr/>
<table width="595" border="1" cellpadding="0" cellspacing="0">
<td width="20%" height="74" align="center" valign="middle">二次<br/>故障<br/>处理<br/>结果</td>
<td colspan="3" valign="top">
<textarea name="cinfo2" id="cinfo2" cols="65" rows="5" <?php echo $disd[1]; ?>><?php echo $sa[1]['sinfo']; ?></textarea>
</td>
</tr>
<tr>
<td width="20%" height="24" valign="top" align="right">是否解决：</td>
<td width="15%"><label>
				<?php
				if($sa[1]['fstus']==1)
                    echo "<input type=\"radio\" name=\"cradio2\" id=\"cradio2\" value=\"1\" checked ".$disr[1]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio2\" id=\"cradio2\" value=\"1\" ".$disr[1]." />";
				?>
                    是</label>
                  <label>
				<?php
                if($sa[1]['fstus']==0)
                    echo "<input type=\"radio\" name=\"cradio2\" id=\"cradio2\" value=\"0\" checked ".$disr[1]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio2\" id=\"cradio2\" value=\"0\" ".$disr[1]." />";
				?>
                  否</label>  </td>
<td width="20%" valign="top" align="right">费用<input type="text" id="cmony2" name="cmony2" value="<?php echo $sa[1]['fmony']; ?>" size="5" <?php echo $disd[1]; ?> />
元</td>
<td width="35%" valign="middle" align="left">维修员：<input name="cnm2" id="cnm2" type="text" value="<?php echo $sa[1]['svuse']; ?>" size='10' <?php echo $disd[1]; ?>/></td>
</tr>
</table>
<hr/>
<table width="595" border="1" cellpadding="0" cellspacing="0">
<td width="20%" height="74" align="center" valign="middle">三次<br/>故障<br/>处理<br/>结果</td>
<td colspan="3" valign="top">
<textarea name="cinfo3" id="cinfo3" cols="65" rows="5" <?php echo $disd[2]; ?>><?php echo $sa[2]['sinfo']; ?></textarea>
</td>
</tr>
<tr>
<td width="20%" height="24" valign="top" align="right">是否解决：</td>
<td width="15%"><label>
				<?php
				if($sa[2]['fstus']==1)
                    echo "<input type=\"radio\" name=\"cradio3\" id=\"cradio3\" value=\"1\" checked ".$disr[2]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio3\" id=\"cradio3\" value=\"1\" ".$disr[2]." />";
				?>
                    是</label>
                  <label>
				<?php
                if($sa[2]['fstus']==0)
                    echo "<input type=\"radio\" name=\"cradio3\" id=\"cradio3\" value=\"0\" checked ".$disr[2]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio3\" id=\"cradio3\" value=\"0\" ".$disr[2]." />";
				?>
                  否</label>  </td>
<td width="20%" valign="top" align="right">费用<input type="text" name="cmony3" id="cmony3" value="<?php echo $sa[2]['fmony']; ?>" size="5" <?php echo $disd[2]; ?> />
元</td>
<td width="35%" valign="middle" align="left">维修员：<input name="cnm3" id="cnm3" type="text" value="<?php echo $sa[2]['svuse']; ?>" size='10' <?php echo $disd[2]; ?>/></td>
</tr>
</table>
<hr/>
<table width="595" border="1" cellpadding="0" cellspacing="0">
<td width="20%" height="74" align="center" valign="middle">四次<br/>故障<br/>处理<br/>结果</td>
<td colspan="3" valign="top">
<textarea name="cinfo4" id="cinfo4" cols="65" rows="5"<?php echo $disd[3]; ?> ><?php echo $sa[3]['sinfo']; ?></textarea>
</td>
</tr>
<tr>
<td width="20%" height="24" valign="top" align="right">是否解决：</td>
<td width="15%"><label>
				<?php
				if($sa[3]['fstus']==1)
                    echo "<input type=\"radio\" name=\"cradio4\" id=\"cradio4\" value=\"1\" checked ".$disr[3]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio4\" id=\"cradio4\" value=\"1\" ".$disr[3]." />";
				?>
                    是</label>
                  <label>
				<?php
                if($sa[3]['fstus']==0)
                    echo "<input type=\"radio\" name=\"cradio4\" id=\"cradio4\" value=\"0\" checked ".$disr[3]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio4\" id=\"cradio4\" value=\"0\" ".$disr[3]." />";
				?>
                  否</label>  </td>
<td width="20%" valign="top" align="right">费用<input type="text" id="cmony4" name="cmony4" value="<?php echo $sa[3]['fmony']; ?>" size="5" <?php echo $disd[3]; ?> />
元</td>
<td width="35%" valign="middle" align="left">维修员：<input name="cnm4" id="cnm4" type="text" value="<?php echo $sa[3]['svuse']; ?>" size='10' <?php echo $disd[3]; ?>/></td>
</tr>
</table>
<hr/>
<table width="595" border="1" cellpadding="0" cellspacing="0">
<td width="20%" height="74" align="center" valign="middle">五次<br/>故障<br/>处理<br/>结果</td>
<td colspan="3" valign="top">
<textarea name="cinfo5" id="cinfo5" cols="65" rows="5" <?php echo $disd[4]; ?>><?php echo $sa[4]['sinfo']; ?></textarea>
</td>
</tr>
<tr>
<td width="20%" height="24" valign="top" align="right">是否解决：</td>
<td width="15%"><label>
				<?php
				if($sa[4]['fstus']==1)
                    echo "<input type=\"radio\" name=\"cradio5\" id=\"cradio5\" value=\"1\" checked ".$disr[4]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio5\" id=\"cradio5\" value=\"1\" ".$disr[4]." />";
				?>
                    是</label>
                  <label>
				<?php
                if($sa[4]['fstus']==0)
                    echo "<input type=\"radio\" name=\"cradio5\" id=\"cradio5\" value=\"0\" checked ".$disr[4]." />";
				else	
					echo "<input type=\"radio\" name=\"cradio5\" id=\"cradio5\" value=\"0\" ".$disr[4]." />";
				?>
                  否</label>  </td>
<td width="20%" valign="top" align="right">费用<input type="text" id="cmony5" name="cmony5" value="<?php echo $sa[4]['fmony']; ?>" size="5" <?php echo $disd[4]; ?> />
元</td>
<td width="35%" valign="middle" align="left">维修员：<input id="cnm5" name="cnm5" type="text" value="<?php echo $sa[4]['svuse']; ?>" size='10' <?php echo $disd[4]; ?>/></td>
</tr>
</table>
</div>
<div id='sbrst'>
<center>
<p>
<input type="submit" id="submit" name="submit" value="保存" title="首次保存登记故障信息，其他的只保存故障处理信息"/><br/></br><input type="reset" id="reset" name="reset" value="重填" title="恢复到修改前的模式"/><br/></br><input type="button" id="close" name="close" value="取消" onclick="javascript:top.location.href='wx.php'" title="关闭此页"/><br/></br>
<input type="button" id="" name="" value="打印" onClick="javascript:divPrint('bdif');" title="打印登记表单"/><br/></br><input type="button" id="" name="" value="扫码" onClick="javascript:imgframeView('qrinfo');" title="放大二维码"/><br/></br>
<input type='button' value='导出' onClick="return exportToWord('bdif')" disabled title="导出登记表单到word" />
</p>
</center>
</div>
 </form>
</body>
<?php
mysqli_close($db);
?>
</html>
