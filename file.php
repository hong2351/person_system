<html>
<head>
<title>发布文件区域</title>
<style type='text/css'>
#usr_logo
{
	background: black;
	line-height: 30px;
	font-size: 25px;
	color: #225599;
}
#rg_div
{
	position:fixed;
	top:0%;
	left:0%;
	bottom: 0%;
	right: 0%;
	filter:alpha(opacity:80);opacity:0.8;  
	background: gray;
	z-index: 2;
}
#usr_reg
{
	position:fixed;
	top:0%;
	left:20%;
	background: #333333;
	width:60%;
	height:80%;
	z-index: 3;
}
#nwcp
{
	position:fixed;
	top:10%;
	left:20%;
	background: #555555;
	width:60%;
	height:60%;
	z-index: 3;
}
#nwlk
{
	position:fixed;
	top:10%;
	left:20%;
	background: #005599;
	width:60%;
	height:60%;
	z-index: 3;
}
.rg_td
{
	color: red;
}
.marq
{
	behavior:scroll;
	color: red;
	font-weight: 1000;
	line-height: 30px;
}
</style>
<?php
$s1=sha1(date('Ymd'));
$GLOBALS['s1']=$s1;
//发布权限字符转中文
function chli1($ca)
{
	if($ca=='u')
		return "个人私有";
	elseif($ca=='g')
		return "公司所有";
	elseif($ca=='p')
		return "完全开放";
}
//发布类型字符转中文
function chli2($na)
{
	if($na==1)
		return "网站分享";
	elseif($na==2)
		return "公司分享";
	elseif($na==3)
		return "个人分享";
	elseif($na==4)
		return "其他分享";
}
//发布的公司代码转成中文名称
function funcomp($a)
{
/**
	-------------------------------------------------------------------------------------------------------------------------
pbcp| 公司记录编号 | 公司记录代码 | 公司显示标题 | 公司注册时间 | 公司注册用户 | 公司注册状态 | 公司异常说明 | 公司备注信息 |
	-------------------------------------------------------------------------------------------------------------------------
	|     cpid     |     cpnm     |     cptl     |     ctme     |      cusr    |     cpop     |      opif    |    cpbk      |
	-------------------------------------------------------------------------------------------------------------------------
**/
	$scmp="select cptl from pbcp where cpnm='$a'";
	$b=mysqli_query($GLOBALS['db'],$scmp);
	$c=mysqli_fetch_array($b);
	return $c['cptl'];
}
//权限控制验证打开或直接打开
function opfun($a)
{
	if($a['flgs']=="p")
		return "<a href=\"javascript:window.open('".$a['flnk']."');\">打开</a>";
	elseif($a['flur']==$_COOKIE['lyflurn'])
		return "<a href=\"javascript:window.open('".$a['flnk']."');\">打开</a>";
	elseif($a['flgs']=="g"&&$a['flmp']==$_COOKIE['lyflgpn'])
		return "<a href=\"javascript:window.open('".$a['flnk']."');\">打开</a>";
	else
		return "<a href=\"javascript:window.open('?gosearch=".$GLOBALS['s1']."&yesno=".$a['flid']."');\">验证打开</a>";
}
//用户控制操作删除链接
function dtfun($a,$b,$c,$d)
{
	if($d==$_COOKIE['lyflurn'])
		return "<a href='?gosearch=".$a."&delete=".$b."&srht=".$c."'>删除</a>";
}
//用户操作链接变换
function upfun($a,$b,$c,$d,$e)
{
	if($d==$_COOKIE['lyflurn']&&$e==1)
		return "<a href='?gosearch=".$a."&srht=".$c."&update=flop&flop=0&fid=".$b."'>暂停</a>";
	elseif($d==$_COOKIE['lyflurn']&&$e==0)
		return "<a href='?gosearch=".$a."&srht=".$c."&update=flop&flop=1&fid=".$b."'>启用</a>";
}
?>
<script type="text/javascript">
function fix(num, length) {
  return ('' + num).length < length ? ((new Array(length + 1)).join('0') + num).slice(-length) : '' + num;
}
window.onload = function() {  
	var date = new Date();  
	var y = date.getFullYear();
	var m = fix(date.getMonth()+1,2);
	var d = fix(date.getDate(),2);  
	var h=fix(date.getHours(),2);
	var m1=fix(date.getMinutes(),2);
	var s=fix(date.getSeconds(),2);
	document.getElementById('curtime').innerText=y+"年"+m+"月"+d+"日 "+h+":"+m1+":"+s;
}
//每秒执行一次
setInterval("window.onload()","1000");

</script>
</head>
<body>
<center>
<?php
/****************************************************************************/
/****************************************************************************/
/**		1、  	未开发“发布新链接信息处理”			       **/
/**		2、  优化“搜索功能的问题，空时没有结果应该公开的显示……”			   **/
/****************************************************************************/
/****************************************************************************/
if(!$db=mysqli_connect('10.5.5.5','nas_web','nas_web'))
{
	echo "<p>0、数据库连接失败！".mysqli_error();
	exit(1);
}
//设置连接数据库的字符集
mysqli_query($db,"set names utf8");
echo "\n";
//连接数据库
mysqli_select_db($db,"nas_web");
$GLOBALS['db']=$db;
//cookie写入
function cookwr($arr)
{
	setCOOKIE('lyclub',date('Ymd'),time()+900);
	setCOOKIE('lyflurn',$arr['fusr'],time()+900);
	setCOOKIE('lyflual',$arr['fual'],time()+900);
	setCOOKIE('lyflgpn',$arr['fucp'],time()+900);
	setCOOKIE('lyflutp',$arr['futp'],time()+900);
	setCOOKIE('lyfltel',$arr['futl'],time()+900);
	setCOOKIE('lyfluqq',$arr['fuqq'],time()+900);
	setCOOKIE('lyflmil',$arr['fuml'],time()+900);
	echo "<script>location.href='".$_POST['urlstr']."'</script>";
}
//处理注册的用户信息
function regusr()
{
/**
		-------------------------------------------------------------------------------------------------------------------------------------------------
		| 用户ID号 | 用户账号名 | 用户密码 | 用户昵称 |所在公司 | 用户权限 | 用户电话 | 用户QQ  | 用户邮箱 | 创建时间 | 用户状态 | 状态说明 | 用户备注  |
		-------------------------------------------------------------------------------------------------------------------------------------------------
插入参数|   fuid   |    fusr    |fups(sha1)|    fual  |   fucp  |   futp   |   futl   |   fuqq  |   fuml   |   ctime  |   fuop   |   opif   |   fubk    |
		-------------------------------------------------------------------------------------------------------------------------------------------------
插入提示| 程序自加 | 表单rgurn  |表单rgurp |表单rgura |表单rgcmp|  默认vip |表单rgurt |表单rgurq|表单rgurm | sql_now()|   默认1  |  默认空  |  默认空   |
		-------------------------------------------------------------------------------------------------------------------------------------------------
数据类型|    int   | varchar100 |varchar100|varchar100|varchar100|varchar10| varchar11|varchar10|varchar100| datetime |    int   |varchar500|varchar1000|
		-------------------------------------------------------------------------------------------------------------------------------------------------
**/
	$urnm1=mysqli_query($GLOBALS['db'],"select fuid from pbur where fusr='".$_POST['rgurn']."'");
	$urnm2=mysqli_fetch_array($urnm1);
	if($_POST['rgurn']=="")
		return "登陆用户名不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgurp']=="")
		return "登陆新密码不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgqrp']=="")
		return "确认新密码不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgurp']!=$_POST['rgqrp'])
		return "新密码两遍不一致……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgura']=="")
		return "用户昵称不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgcmp']=="")
		return "所在公司没有选择……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgurt']=="")
		return "联系电话不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgurq']=="")
		return "联系QQ号不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($_POST['rgurm']=="")
		return "联系邮箱不能为空……<a href='javascript:history.go(-1)'>请返回</a>";
	elseif($urnm2['fuid']>=5000)
		return "注册用户名已经存在，请修改注册信息……<a href='javascript:history.go(-1)'>请返回</a>";
	
	$ids1=mysqli_query($GLOBALS['db'],"select max(fuid) as maxid from pbur");
	$ids2=mysqli_fetch_array($ids1);
	$rgsr['fuid']=$ids2['maxid'];
	$rgsr['fuid']=$rgsr['fuid']+1;
	$rgsr['fusr']=$_POST['rgurn'];
	$rgsr['fups']=sha1($_POST['rgqrp']);
	$rgsr['fual']=$_POST['rgura'];
	$rgsr['futp']="vip";
	$rgsr['fucp']=$_POST['rgcmp'];
	$rgsr['futl']=$_POST['rgurt'];
	$rgsr['fuqq']=$_POST['rgurq'];
	$rgsr['fuml']=$_POST['rgurm'];
	$insur="insert into pbur values(".$rgsr['fuid'].",'".$rgsr['fusr']."','".$rgsr['fups']."','".$rgsr['fual']."','".$rgsr['fucp']."','".$rgsr['futp']."','".$rgsr['futl']."','".$rgsr['fuqq']."','".$rgsr['fuml']."',now(),1,'','')";
	if(mysqli_query($GLOBALS['db'],$insur))
	{
		cookwr($rgsr);
		return "注册\"".$rgsr['fual']."\"成功！";
	}
	else
		return "注册出现错误，请重新确认信息是否正确……<a href='javascript:history.go(-1)'>请返回</a>";
		
	//return "此功能等待开发……<a href='".$_POST['urlstr']."'>请返回</a>";
}
function lgnusr()
{
/**
		-------------------------------------------------------------------------------------------------------------------------------------------------
		| 用户ID号 | 用户账号名 | 用户密码 | 用户昵称 |所在公司 | 用户权限 | 用户电话 | 用户QQ  | 用户邮箱 | 创建时间 | 用户状态 | 状态说明 | 用户备注  |
		-------------------------------------------------------------------------------------------------------------------------------------------------
查询参数|   fuid   |    fusr    |fups(sha1)|    fual  |   fucp  |   futp   |   futl   |   fuqq  |   fuml   |   ctime  |   fuop   |   opif   |   fubk    |
		-------------------------------------------------------------------------------------------------------------------------------------------------
数据类型|    int   | varchar100 |varchar100|varchar100|varchar100|varchar10| varchar11|varchar10|varchar100| datetime |    int   |varchar500|varchar1000|
		-------------------------------------------------------------------------------------------------------------------------------------------------
		（不显示在网页中）用户权限： 管理员admin，一般用户vip，访客guest（只有系统初始化system用户有super权限）；
		（不显示在网页中）用户状态： 是使用开关，正常为1，异常为0；
		（不显示在网页中）状态说明： 异常时管理员都会有故障说明，并且异常状态登陆时无法登陆提示此说明。
		cookie写入fuid,fusr,fual,fucp,futp,futl,fuqq,fuml
		cookie必须写入setCOOKIE('lyclub',date('Ymd'),time()+600);
**/
	//setCOOKIE('lyflurn',"system",time()+600);
	//setCOOKIE('lyflual',"管理员",time()+600);
	//setCOOKIE('lyflgpn',"lyclub",time()+600);
	//setCOOKIE('lyflutp',"super",time()+600);
	//setCOOKIE('lyfltel',"18562221224",time()+600);
	//setCOOKIE('lyfluqq',"351188949",time()+600);
	//setCOOKIE('lyflmil',"liubingjie771@live.cn",time()+600);
	setCOOKIE('lyclub',"",time()+900);
	setCOOKIE('lyflurn',"",time()+900);
	setCOOKIE('lyflual',"",time()+900);
	setCOOKIE('lyflgpn',"",time()+900);
	setCOOKIE('lyflutp',"",time()+900);
	setCOOKIE('lyfltel',"",time()+900);
	setCOOKIE('lyfluqq',"",time()+900);
	setCOOKIE('lyflmil',"",time()+900);
	/**下面if语句获取用户名和密码是否为空**/
	if($_POST['lgura']==""&&$_POST['lgups']=="")
		return "<font style='color:red'>用户名不能为空，请重新确认再登陆……</font><a href='javascript:history.go(-1)'>请返回</a>";
	else
		$ursct="select * from pbur where fusr='".$_POST['lgura']."'";
	/**下面if语句获取用户所有信息**/
	$ursqlut=mysqli_query($GLOBALS['db'],$ursct);
	if($ursqlut->num_rows>0)
	{
		//查询用户所有信息
		$ursqldb=mysqli_fetch_array($ursqlut);
	}
	else
	{
		return "<font style='color:red'>登陆名不存在，请注册或重新确认再登陆……</font><a href='javascript:history.go(-1)'>请返回</a>";
	}
	if($ursqldb['fuop']==0)
		return "\"".$ursqldb['fusr']."\"用户登录成功，不能正常使用此账户，原因是<font style='color:red'>".$ursqldb['opif']."</font>……<a href='javascript:history.go(-1)'>请返回</a>";
	if(sha1($_POST['lgups'])==$ursqldb['fups'])
	{
		cookwr($ursqldb);
		return "登陆\"".$ursqldb['fual']."\"成功！";
	}
	else
	{
		return "<font style='color:red'>登陆密码错误，请重新确认再登陆……</font><a href='javascript:history.go(-1)'>请返回</a>";
	}
}
//发布新连接(第一步)的表单
if($_GET['new']=="yes"&&$_GET['link']=="new")
{
	echo "<div id='rg_div'></div><div id='nwlk'>";
	echo "<h1>发布新链接1</h1>";
	if(sha1($_COOKIE['lyclub'])==$s1)
	{
		echo "<form action='?new=yes&link=newone' method='post'>";
		//公司类型select项目
		echo "<input type='hidden' id='urlstr' name='urlstr' value='".$_SERVER['QUERY_STRING']."' />";
		echo "<select id='cpone' name='cpone'>";
		echo "<option value=''>请选择公司名称</option>";
		$cist=mysqli_query($db,"select cpnm,cptl from pbcp where cpop=1 order by ctme desc ");
		while($clt=mysqli_fetch_array($cist))
		{
			echo "<option value='".$clt['cpnm']."'>";
			echo $clt['cptl'];
			echo "</option>";
		}
		echo "</select>";
		echo "<input type='submit' id='lksbt' name='lksbt' value='下一步'/>";
		echo "&nbsp;";
		echo "<input type='button' onclick='javascript:history.go(-1);' value='关闭' />";
		echo "</form>";
	}
	else
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>用户未登陆或未注册，请登陆再发布新信息吧！<a href=' ?'>返回首页<a></font>";
	}
	echo "</div>";
}
//发布新连接(第二步)的表单
elseif($_GET['new']=="yes"&&$_GET['link']=="newone")
{
	echo "<div id='rg_div'></div><div id='nwlk'>";
	echo "<h1>发布新链接2</h1>";
	if(sha1($_COOKIE['lyclub'])==$s1)
	{
		if($_POST['cpone']!="")
		{
			echo "<form action='?new=yes&link=reg' method='post'>";
			echo "<table width='100%'><input type='hidden' id='urlstr' name='urlstr' value='".$_SERVER['QUERY_STRING']."' />";
			echo "<tr><th>链接新的标题：</th><td><input type='text' id='nlknm' name='nlknm' value='' /></td></tr>";
			if($ct201701=mysqli_query($db,"select cptl from pbcp where cpnm='".$_POST['cpone']."'"))
			{
				echo "<tr><th>选择链接公司：</th><td><input type='hidden' id='nlkcp' name='nlkcp' value='".$_POST['cpone']."' />";
				$ct201702=mysqli_fetch_array($ct201701);
				echo $ct201702[0];
				if($_COOKIE['lyflgpn']!=$_POST['cpone'])
				{
					echo "&nbsp;&nbsp;<input type='checkbox' id='cpsbm' name='cpsbm' />用户公司与选择的公司不一致，确认要用此公司名吗？";
				}
				else
				{
					echo "<input type='hidden' id='cpsbm' name='cpsbm' value='yes' />";
				}
				echo "</td></tr>";
			}
			echo "<tr><th>选择链接类型：</th><td>";
			echo <<<TPLK
				<select id='nlktp' name='nlktp'>
				<option value='0' selected>请选择链接类型</option>
				<option value='1'>网站分享</option>
				<option value='2'>公司分享</option>
				<option value='3'>个人分享</option>
				<option value='4'>其他分享</option>
				</select>
TPLK;
			echo "</td></tr>";
			//链接权限select：公司/用户/公开
			echo <<<QXLK
			<tr><th>选择链接权限：</th><td>
				<select id='nlkqx' name='nlkqx'>
				<option value=''></option>
				<option value='p'>公开发布</option>
				<option value='g'>公司发布</option>
				<option value='u'>个人私有</option>
				</select>
			</td></tr>
QXLK;
			//发布链接text
			echo "<tr><th>新链接请输入：</th><td><input type='text' id='nlkrl' name='nlkrl' value='http://' placeholder='请输入完整的公网网址' /></td></tr>";
			//文件说明textarea
			echo "<tr><th>编注链接说明：</th><td><textarea id='nlkif' name='nlkif' rows='5' cols='50'></textarea></td></tr>";
			echo "</table>";
			echo "<input type='submit' id='nlkst' name='nlkst' value='发布并提交'/>";
			echo "&nbsp;";
			echo "<input type='submit' id='nlkst' name='nlkst' value='取消'/>";
			echo "</form>";
		}
		else
		{
			echo "<font style='color:red;font-family:黑体;font-size:30px;'>发布链接时需要添加公司名称！&nbsp;<a href=' javascript:history.go(-1);'>返回首页<a></font>";
		}
	}
	else
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>用户未登陆或未注册，请登陆再发布新信息吧！&nbsp;<a href=' ?'>返回首页<a></font>";
	}
	echo "</div>";
}
//发布新公司名称的表单
elseif($_GET['new']=="yes"&&$_GET['comp']=="new")
{
	echo "<div id='rg_div'></div><div id='nwcp'>";
	echo "<h1>发布新公司名称</h1>";
	echo "<form action='?new=yes&comp=reg' method='post'>";
	if(sha1($_COOKIE['lyclub'])==$s1)
	{
	echo <<<NWCPFM
	<form action='?new=yes&comp=reg' method='post'>
	<table>
		<tr>
			<th>公司代码：</th>
			<th><input type='text' id='ncpnm' name='ncpnm' value='' /></th>
		</tr>
		<tr>
			<th>公司名称：</th>
			<th><input type='text' id='ncptl' name='ncptl' value='' /></th>
		</tr>
	</table>
	<input type='submit' id='cpsbt' name='cpsbt' value='发布并提交' />&nbsp;
	<input type='button' onclick='javascript:history.go(-1);' value='关闭' />
	</form>
NWCPFM;
	}
	else
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>用户未登陆或未注册，请登陆再发布新信息吧！&nbsp;<a href=' ?'>返回首页<a></font>";
	}
	echo "</div>";
}
//发布新连接的确认处理结果表单
elseif($_GET['new']=="yes"&&$_GET['link']=="reg")
{
	if($_POST['nlkst']=="发布并提交")
	{
		echo "<div id='rg_div'></div><div id='nwlk'>";
		echo "<h1>发布新链接3</h1>";
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>";
		//$_POST['cpsbm']获取的checkbox复选框，未选中为空，选中为on的值
		//链接新的标题：nlknm
		//选择链接公司：nlkcp
		//判断是否发布别的公司链接：on是，空 否
		//选择链接类型：nlktp
		//输入的链接地址：nlkrl
		//选择链接权限： nlkqx
		//输入的链接说明：nlkif
		//nlkst 发布并提交 or 取消
		if($_POST['nlknm']=="")
			echo "请正确填写\"链接新的标题\"~&nbsp;<a href=\"javascript:history.go(-1);\">返回<a>";
		elseif($_POST['cpsbm']!="on"&&$_POST['cpsbm']!="yes")
			echo $_POST['cpsbm']."用户公司与选择公司不一致~&nbsp;<a href=\"javascript:history.go(-1);\">返回勾选<a>或者<a href=\"javascript:location.href='?gosearch=".$s1."&new=yes&link=new'\">重新选择与发布</a>";
		elseif($_POST['nlktp']=="")
			echo "请选择\"链接类型\"~&nbsp;<a href=\"javascript:history.go(-1);\">返回<a>";
		elseif($_POST['nlkqx']=="")
			echo "请选择\"链接权限\"~&nbsp;<a href=\"javascript:history.go(-1);\">返回<a>";
		elseif($_POST['nlkrl']=="")
			echo "请正确填写\"新链接请输入\"~&nbsp;<a href=\"javascript:history.go(-1);\">返回<a>";
		elseif($_POST['nlkif']=="")
			echo "请填写\"编注链接说明\"~&nbsp;<a href=\"javascript:history.go(-1);\">返回<a>";
		else
		{
			echo "<form action='?new=yes&link=write' method='post'>";
			echo "<table width='100%'><input type='hidden' id='urlstr' name='urlstr' value='".$_SERVER['QUERY_STRING']."' />";
			echo "<tr><th>链接标题：</th><td><input type='hidden' id='ljbt' name='ljbt' value='".$_POST['nlknm']."' />".$_POST['nlknm']."</td></tr>";
			echo "<tr><th>对应公司：</th><td><input type='hidden' id='ljgs' name='ljgs' value='".$_POST['nlkcp']."' />".funcomp($_POST['nlkcp'])."</td></tr>";
			echo "<tr><th>分享类型：</th><td><input type='hidden' id='ljlx' name='ljlx' value='".$_POST['nlktp']."' />".chli2($_POST['nlktp'])."</td></tr>";
			echo "<tr><th>发布权限：</th><td><input type='hidden' id='ljqx' name='ljqx' value='".$_POST['nlkqx']."' />".chli1($_POST['nlkqx'])."</td></tr>";
			echo "<tr><th>链接地址：</th><td><input type='hidden' id='ljdz' name='ljdz' value='".$_POST['nlkrl']."' />".$_POST['nlkrl']."</td></tr>";
			echo "<tr><th>链接说明：</th><td><textarea id='ljsm' name='ljsm' rows='5' cols='50' readonly='readonly'>".$_POST['nlkif']."</textarea></td></tr>";
			echo "</table>";
			echo "<input type='submit' id='kst' name='kst' value='发布并提交'/>";
			echo "&nbsp;";
			echo "<input type='submit' id='kst' name='kst' value='取消'/>";
			echo "&nbsp;";
			echo "<input type='button' onclick='javascript:history.go(-1);' value='返回'/>";
			echo "</form>";
		}
		echo "</font>";
		echo "</div>";
	}
	elseif($_POST['nlkst']=="取消")
	{
		echo "<script>location.href='?gosearch=".$s1."';</script>";
	}
}
elseif($_GET['new']=="yes"&&$_GET['link']=="write")
{
	/**
		------------------------------------------------------------------------------
		|  发布编号 |  文件名称  |  文件说明  |  文件类型  |  公司名称  |  作者名称  |
		------------------------------------------------------------------------------
		|   flid    |    flnm    |    flif    |  fltp(1-4) |    flmp    |    flps    |
		------------------------------------------------------------------------------
		| 发布权限  |  发布用户  |  发布链接  |  发布时间  |  发布开关  |  链接备注  |
		------------------------------------------------------------------------------
		|flgs(u/g/p)|    flur    |    flnk    |    fltm    | flop(0/1)  |    flbk    |
		------------------------------------------------------------------------------
	**/
	if($_POST['kst']=="取消")
	{
		echo "<script>location.href='?gosearch=".$s1."';</script>";
	}
	elseif($_POST['kst']=="发布并提交")
	{
		echo "<div id='rg_div'></div><div id='nwlk'>";
		echo "<h1>发布新链接4</h1>";
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>";
		$lkid=mysqli_query($db,"select max(flid) from pbfl");
		$lkidst=mysqli_fetch_array($lkid);
		$lkidmax=$lkidst[0];
		$lkidx=$lkidmax+1;
		$likin="insert into pbfl values($lkidx,'".$_POST['ljbt']."','".$_POST['ljsm']."','".$_POST['ljlx']."','".$_POST['ljgs']."','".$_COOKIE['lyflual']."','".$_POST['ljqx']."','".$_POST['ljdz']."','".$_COOKIE['lyflurn']."',now(),1,'')";
		$lkpd=mysqli_query($db,"select * from pbfl where flnm='".$_POST['ljbt']."'");
		$dzpd=mysqli_query($db,"select * from pbfl where flnk='".$_POST['ljdz']."'");
		if($lkpd->num_rows>0)
			echo "表单提交出现\"链接标题\"有重复，请<a href='javascript:history.go(-2);'>返回重新确认表单</a>";
		elseif($dzpd->num_rows>0)
			echo "表单提交出现\"链接地址\"有重复，请<a href='javascript:history.go(-2);'>返回重新确认表单</a>";
		else
			if(mysqli_query($db,$likin))
			{
				echo "表单提交成功，<a href='?gosearch=".$s1."'>返回首页</a>";
			}
			else
			{
				echo "表单提交出现错误，请<a href='javascript:history.go(-1);'>返回重新确认表单</a>";
			}
		echo "</font></div>";
	}
}
//
//发布新公司名称的处理结果
elseif($_GET['new']=="yes"&&$_GET['comp']=="reg")
{
	echo "<div id='rg_div'></div><div id='nwcp'>";
	echo "<h1>发布新公司名称</h1>";
	$cpdnm=mysqli_query($db,"select * from pbcp where cpnm='".$_POST['ncpnm']."' ");
	$cpdtl=mysqli_query($db,"select * from pbcp where cptl='".$_POST['ncptl']."' ");
	if($_POST['ncpnm']=="")
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>公司代码不能为空，请确认！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
	}
	elseif($_POST['ncptl']=="")
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>公司名称不能为空，请确认！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
	}
	elseif(!preg_match("/^[a-zA-Z1-9\s]+$/",$_POST['ncpnm']))
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>公司代码只能全是字母数字，请确认！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
	}
	elseif(preg_match("/^[a-zA-Z1-9\s]+$/",$_POST['ncptl']))
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>公司名称不能是字母或数字，请确认！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
	}
	elseif($cpdnm->num_rows>0)
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>公司代码与已有的公司代码有重复，请修改！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
	}
	elseif($cpdtl->num_rows>0)
	{
		echo "<font style='color:red;font-family:黑体;font-size:30px;'>公司名称与已有的公司名称有重复，请修改！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
	}
	else
	{
		$ncp1=mysqli_query($db,"select max(cpid) as maxid from pbcp");
		$ncp2=mysqli_fetch_array($ncp1);
		$npd=$ncp2['maxid']+1;
		$incp="insert into pbcp values($npd,'".$_POST['ncpnm']."','".$_POST['ncptl']."',now(),'".$_COOKIE['lyflurn']."',1,'','')";
		if(mysqli_query($db,$incp))
		{
			echo "<script>window.alert('发布新的公司名称添加成功');location.href='?gosearch=".$s1."';</script>";
		}
		else
		{
			echo "<font style='color:red;font-family:黑体;font-size:30px;'>发布新的公司名称添加失败！~&nbsp;<a href=' javascript:history.go(-1);'>返回<a></font>";
		}
	}
	echo "</div>";
}
if($_POST['qusr']=="退出")
{
	setCOOKIE('lyclub',"",time()+900);
	setCOOKIE('lyflurn',"",time()+900);
	setCOOKIE('lyflual',"",time()+900);
	setCOOKIE('lyflgpn',"",time()+900);
	setCOOKIE('lyflutp',"",time()+900);
	setCOOKIE('lyfltel',"",time()+900);
	setCOOKIE('lyfluqq',"",time()+900);
	setCOOKIE('lyflmil',"",time()+900);
	echo "<script>location.href='".$_POST['urlstr']."'</script>";
}
echo "<div id='usr_logo'>";
echo "<table width='100%'>";
echo "<tr>";
echo "<td width='15%' class='marq'><span id='curtime'></span></td>";
echo "<th width='25%'><marquee class='marq' onmouseout='this.start()' onmouseover='this.stop()'>此网页目前正在处于开发阶段，没有进入测试阶段和运营阶段，请大家不要发布新链接和公司名，数据会经常初始化的，谢谢合作！</marquee></th>";
echo "<th width='20%'><input type='button' onclick=\"javascript:location.href='?new=yes&link=new';\" value='发布新的链接' />&nbsp;&nbsp;<input type='button' onclick=\"javascript:location.href='?new=yes&comp=new';\" value='公司名新发布' /></th>";
echo "<th>";
if($_POST['rgsmt']=="注册")
{
	//处理注册的用户信息
	echo regusr();
}
elseif($_POST['lgsmt']=="登陆")
{
	//处理用户登录的权限
	echo lgnusr();
}
elseif($_POST['lgsmt']=="注册")
{
	echo "<div id='rg_div'>";
	echo "</div>";
	echo "<div id='usr_reg'>";
	echo "<p></p>";
	echo <<<USREG1
	<form method='post'>
		<center>
		<table>
		<tr>
			<td class='rg_td' align='right'>登陆用户名：</td>
			<td><input type='text' id='rgurn' name='rgurn' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>登陆新密码：</td>
			<td><input type='password' id='rgurp' name='rgurp' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>确认新密码：</td>
			<td><input type='password' id='rgqrp' name='rgqrp' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>显示的昵称：</td>
			<td><input type='text' id='rgura' name='rgura' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>选所在公司：</td>
			<td><select id='rgcmp' name='rgcmp'>
	<option value=''>请选择公司名称</option>
USREG1;
	$cist=mysqli_query($db,"select cpnm,cptl from pbcp where cpop=1 order by ctme desc ");
	while($clt=mysqli_fetch_array($cist))
	{
		echo "<option value='".$clt['cpnm']."'>";
		echo $clt['cptl'];
		echo "</option>";
	}
echo <<<USREG1
	</select>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>联&nbsp;系&nbsp;电&nbsp;话：</td>
			<td><input type='text' id='rgurt' name='rgurt' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>联&nbsp;系QQ号：</td>
			<td><input type='text' id='rgurq' name='rgurq' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<td class='rg_td' align='right'>联&nbsp;系&nbsp;邮&nbsp;箱：</td>
			<td><input type='text' id='rgurm' name='rgurm' value='' /></td>
			<td class='rg_td'>必填，</td>
		</tr>
		<tr>
			<th></th>
			<th><input type='submit' id='rgsmt' name='rgsmt' value='注册' /></th>
			<th>
USREG1;
echo "<input type='button' onclick=\"javascript:location.href='".$_POST['urlstr']."'\" value='关闭' />";
echo <<<USREG2
			</th>
		</tr>
		</table>
		</center>
	</form>
USREG2;
	echo "</div>";
}
else
{
	if($_COOKIE['lyclub']!=date('Ymd'))
	{
		echo "<form method='post'><input type='hidden' id='urlstr' name='urlstr' value='?".$_SERVER['QUERY_STRING']."' />";
		echo <<<USRFORM
			<th width='15%' bgcolor='#666666'>登录名：<input type='text' id='lgura' name='lgura' value='' /></th>
			<th width='15%' bgcolor='#666666'>登录密码：<input type='password' id='lgups' name='lgups' value='' /></th>
			<th width='5%' bgcolor='#666666'><input type='submit' id='lgsmt' name='lgsmt' value='登陆' /></th>
			<th width='5%' bgcolor='#666666'><input type='submit' id='lgsmt' name='lgsmt' value='注册' /></th> 
	</form>
USRFORM;
	}
	else
	{
		echo "<form method='post'><font style='background:white;color:blue;'>你好，&nbsp;".$_COOKIE['lyflual']."&nbsp;</font>&nbsp;&nbsp;<input type='hidden' id='urlstr' name='urlstr' value='?".$_SERVER['QUERY_STRING']."' /><input type='submit' id='qusr' name='qusr' value='退出' /><font color='red'>（ 注意：此网页用户登录后15分钟后自动退出） </font></form>";
	}
}
echo "</th></tr></table>";
echo "</div>"; 

//搜索表单
echo "<h1>发布的文件搜索</h1>";
echo "<form method='get' action='?search'>";
//链接类型select项目
echo <<<SLT1
	<input type='hidden' id='gosearch' name='gosearch' value='$s1' />
	<select id='srht' name='srht'>
	<option value='0' selected>请选择链接类型</option>
	<option value='1'>网站分享</option>
	<option value='2'>公司分享（需要用户登录才可以进行）</option>
	<option value='3'>个人分享（需要用户登录才可以进行）</option>
	<option value='4'>其他分享</option>
	</select>
	&nbsp;&nbsp;
SLT1;
//公司类型select项目
	echo "<select id='srcp' name='srcp'>";
	echo "<option value=''>请选择公司名称</option>";
	$cist=mysqli_query($db,"select cpnm,cptl from pbcp where cpop=1 order by ctme desc ");
	while($clt=mysqli_fetch_array($cist))
	{
		echo "<option value='".$clt['cpnm']."'>";
		echo $clt['cptl'];
		echo "</option>";
	}
	echo "</select>";
	echo "&nbsp;&nbsp;";
echo "<input type='text' id='srif' name='srif' value='' />";
echo "<input type='submit' id='srsm' name='srsm' value='搜索' />";
echo "</form>";
	
/**
	------------------------------------------------------------------------------
	|  发布编号 |  文件名称  |  文件说明  |  文件类型  |  公司名称  |  作者名称  |
	------------------------------------------------------------------------------
	|   flid    |    flnm    |    flif    |  fltp(1-4) |    flmp    |    flps    |
	------------------------------------------------------------------------------
	| 发布权限  |  发布用户  |  发布链接  |  发布时间  |  发布开关  |  链接备注  |
	------------------------------------------------------------------------------
	|flgs(u/g/p)|    flur    |    flnk    |    fltm    | flop(0/1)  |    flbk    |
	------------------------------------------------------------------------------
**/
if($_GET['update']!="")
{
	$$_GET['update']=$_GET[$_GET['update']];
	$sqlupd="update pbfl set ".$_GET['update']."=".$$_GET['update']." where flid=".$_GET['fid'];
	if(mysqli_query($db,$sqlupd))
		echo "<script>window.alert('发布信息屏蔽或启用完成！');</script>";
}
if($_GET['delete']!="")
{
	$sqldt="delete from pbfl where flur='".$_COOKIE['lyflurn']."' and flid=".$_GET['delete'];
	if(mysqli_query($db,$sqldt))
		echo "<script>window.alert('发布信息删除完成！');</script>";
}
if($_GET["gosearch"]==$s1)
{
	$sqlsch="select * from pbfl";
	//控制查询的文件权限
	if($_COOKIE['lyflurn']=="")
		$sqlsch=$sqlsch." where flgs='p'";
	$sqldt1=mysqli_query($db,$sqlsch);
	echo "<p></p>";
	if($sqldt1->num_rows>0)
		while($note1=mysqli_fetch_array($sqldt1))
		{
			echo "<table border='1' width='80%'>";
			echo "<tr bgcolor='#ABCEFD'><th>文件名称</th><th>公司名称</th><th>文件权限</th><th>发布类型</th><th>发布作者</th><th>发布时间</th><th>文件操作</th></tr>";
			echo "<tr><th>".$note1[1]."</th><th>".funcomp($note1[4])."</th><th>".chli1($note1[6])."</th><th>".chli2($note1[3])."</th><th>".$note1[5]."</th><th>".$note1[9]."</th><th rowspan='2'>".opfun($note1)."<br/>".upfun($s1,$note1[0],$st,$note1['flur'],$note1['flop'])."&nbsp;&nbsp;".dtfun($s1,$note1[0],$st,$note1['flur'])."</th></tr>";
			echo "<tr><th bgcolor='#ABCEFD'>文件说明</th><td colspan='5'>".$note1[2]."</td>";
			echo "</table>";
			echo "<p></p>";
		}

	if($sqldt1->num_rows<=0)
		echo "网站搜索结果中，没有符合您的发布记录……";
}

mysqli_close($db);
?>
</center>
</body>
</html>