<html>
<head>
<script type="text/ecmascript" src="data/browser_check.js"></script>
<meta http-equiv='content-type' content='text/html;charset=UTF-8' />
<title>网站留言板</title>
<style type='text/css'>
#ifrm0 {
	top:0%;
	left:10%;
	width:80%;
	height:10%;
	border:0;
	position:fixed;
	text-align:center;
	z-index:1;
}
#ifrm1 {
	top:10%;
	left:10%;
	width:80%;
	height:70%;
	border:0;
	position:fixed;
	z-index:1;
}
#ifrm2 {
	top:80%;
	left:10%;
	width:80%;
	height:20%;
	border:0;
	position:fixed;
	z-index:1;
	background:#00FF55;
}

</style>
<script type="text/ecmascript" src="data/sha1.js"></script>
<script language='javascript'>
<?php
//忽略错误信息
error_reporting(0);
//开启数据库连接
if(!$db=mysqli_connect('10.5.5.5','nas_web','nas_web'))
{
	echo "window.alert('数据库连接失败！".mysqli_error()."');";
	exit(1);
}
//连接数据库
mysqli_select_db($db,"nas_web");
if($_POST['submit']=="留言")
{
	$uslt="select uid from users where uname='".$_POST['aduser']."' and usha1='".sha1($_POST['adpass'])."' and upass='".$_POST['adpass']."' ";
	//验证用户登录
	if($uid=mysqli_fetch_array(mysqli_query($db,$uslt)))
	{
		//执行发布留言
		$lids="select max(yid) from adinfo";
		$lds=mysqli_query($db,$lids);
		$lyd=mysqli_fetch_array($lds);
		$lyid=$lyd[0]+1;
		$lyit="insert into adinfo values($lyid,NULL,".$uid[0].",'".$_POST['adinfo']."',1,now(),'','')";
		if(mysqli_query($db,$lyit))
			echo "window.alert('留言发布成功！');";
		else
			echo "window.alert('留言发布失败！');";
	}
	else
	{
		echo "window.alert('用户验证失败！');";
	}
}
if($_GET['del']!=""&&$_GET['upass']!="")
{
	function delk($bs,$ac,$din)
	{
		if($ac!="")
		{
			if($_GET['info']=="ad")
				$ddsql="delete from adinfo where yid=".$din;
			elseif($_GET['info']=="ev")
				$ddsql="delete from evinfo where vid=".$din;
			if(mysqli_query($bs,$ddsql))
				return "信息删除成功！";
			else
				return "信息删除失败！";
		}
		else
		{
			return "window.alert('删除信息获取用户信息失败！');";
		}
	}
	if($_GET['info']=="ad")
		$ds1=mysqli_query($db,"select * from adinfo where yid=".$_GET['del']." and '".$_GET['upass']."'=(select usha1 from users where uid='".$_GET['user']."')");
	elseif($_GET['info']=="ev")
		$ds1=mysqli_query($db,"select * from evinfo where vid=".$_GET['del']." and '".$_GET['upass']."'=(select usha1 from users where uid='".$_GET['user']."')");
	$ds2=mysqli_fetch_array($ds1);
	echo "yns=prompt(\"请输入留言者的用户密码：\",\"\");";
	echo "if(hex_sha1(yns)=='".$_GET['upass']."'){window.alert('".delk($db,$ds2[0],$_GET['del'])."')}else{window.alert('用户信息获取成功，但输入的密码有误！');}";
	echo "window.location.href='".$_SERVER['SCRIPT_NAME']."';";
}
?>
window.setInterval(flashdiv,1000);
function flashdiv()
{
	ifrm1.window.location.reload();
}
</script>
</head>
<body>
<div id='ifrm0' name='ifrm0'>
<h1>网站留言板</h1>
</div>
<div id='ifrm1' name='ifrm1'>
<?php
//根据留言ID获取评价信息
function eview($bs,$eid)
{
	$st="select a.vuif,a.vctime,b.ualias,a.vid,b.usha1,a.vuid from evinfo as a,users as b where a.vsid=".$eid." and a.vuid=b.uid group by a.vid";
	$v1=mysqli_query($bs,$st);
	$rv="";
	while($v2=mysqli_fetch_array($v1))
	{
		$rv=$rv."<tr><td align='right'></td><td>".$v2[2]."：</td><td><font style='background:#CCFF88'>".nl2br($v2[0])."</font></td><td><font style='background:#CCFF88'>".$v2[1]."</font><a href='?deltion=yes&ysid=".$v2[3]."&yst=evinfo&uas=".$v2[2]."' title='删除'>X</a></td></tr>";
	}
	return $rv;
}
//设置连接数据库的字符集
mysqli_query("set names utf8");
//选择数据库
mysqli_select_db($db,"advices");
//获取完整留言信息
$fullinfo=mysqli_query($db,"select a.yid,a.ysid,b.ualias,a.yuif,a.yctime,a.yutime,a.ybak,a.rstatus,b.usha1,a.yuid from adinfo as a ,users as b where a.yuid=b.uid");
while($dif=mysqli_fetch_array($fullinfo))
{
	echo "<!--";
	print_r($dif);
	echo "-->";
	echo "<table width='100%' align='center' border='0' style='background:#00FF55'>";
	echo "<tr><td rowspan='3'  width='5%' align='center' valign='middle'>".$dif[0];
	if($dif[7]==0)
		echo "<br/>[未读]";
	echo "</td><td align='left' rowspan='3' colspan='2' style='background:#00EEEE'>&nbsp;&nbsp;&nbsp;&nbsp;".nl2br($dif[3])."</td><td width='20%' align='right'>".$dif[2]."</td></tr>";
	echo "<tr><td align='right'>".$dif[4]."</td></tr>";
	echo "<tr><td align='right'><a href='?evaluation=yes&ysid=".$dif[0]."'>评价</a>&nbsp;&nbsp;&nbsp;<a href='?deltion=yes&ysid=".$dif[0]."&yst=adinfo&uas=".$dif[2]."'>删除</a>&nbsp;&nbsp;&nbsp;</td></tr>";
	echo eview($db,$dif[0]);
	echo "</table>\n";
	echo "</br>\n";
}
?>
</div>
<div  id='ifrm2' name='ifrm2'>
<?php
//处理评论信息和发布信息的表单
if($_GET['evaluation']=="yes")
{
	if($_POST['evys']=="发布")
	{
		echo "<script>";
		$uslt="select uid from users where uname='".$_POST['yyusr']."' and usha1='".sha1($_POST['yyps'])."' and upass='".$_POST['yyps']."' ";
		//验证用户登录
		if($uid=mysqli_fetch_array(mysqli_query($db,$uslt)))
		{
			//执行发布留言
			$lvds="select max(vid) from evinfo";
			$vds=mysqli_query($db,$lvds);
			$lvd=mysqli_fetch_array($vds);
			$lvid=$lvd[0]+1;
			$lvit="insert into evinfo values($lvid,".$_POST['yyiidd'].",".$uid[0].",'".$_POST['yyinf']."',1,now(),'','')";
			if(mysqli_query($db,$lvit))
				echo "window.alert('评价发布成功！');";
			else
				echo "window.alert('评价发布失败！');";
			echo "window.location.href='".$_SERVER['SCRIPT_NAME']."';";
		}
		else
		{
			echo "window.alert('用户验证失败！');history.go(-1);";
		}
		echo "</script>";
	}
	elseif($_POST['evno']=="返回")
	{
		echo "<script>window.location.href='".$_SERVER['SCRIPT_NAME']."';</script>";
	}
	else
	{
		echo "<script>window.alert('请在下方评价！');</script>";
		echo "<form action='?evaluation=yes' method='post'>";
		echo "<input type='hidden' id='yyiidd' name='yyiidd' value='".$_GET['ysid']."' />";
		echo <<<EVFM
				<table width='100%'>
				<tr><td rowspan='3'>发布<br/>留言<br/>评价<br/>信息</td><td  rowspan='3'><textarea id='yyinf' name='yyinf' rows='8' cols='60'></textarea></td><td align='right'>评论用户账户名</td><td><input type='text' id='yyusr' name='yyusr' value='' /></td></tr>
				<tr><td align='right'>评论用户密码</td><td><input type='password' name='yyps' id='yyps' value='' /></td></tr>
				<tr><td colspan='2' align='right'><a href='javascript:window.open(\"nas_user.php?create\",\"用户注册\",\"height=480, width=600, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no\")' title='没有用户请注册'>用户注册</a>&nbsp;&nbsp;&nbsp;<input type='submit' name='evys' id='evys' value='发布' />&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='submit' id='evno' name='evno' value='返回' />&nbsp;&nbsp;</td></tr>
EVFM;
		echo "</form>";
	}
}
elseif($_GET['deltion']=="yes")
{
	if($_POST['dlys']=="确认")
	{
		echo "<script>";
		//用户名dluname密码dlpass别名dlualias
		$uname=$_POST['dluname'];
		$upass=$_POST['dlpass'];
		$ulias=$_POST['dlualias'];
		if($uname!=""&&$upass!="")
		{
			$ufs1="select uid from users where uname='$uname' and usha1='".sha1($upass)."' and ualias='$ulias' ";
			$ufs2=mysqli_query($db,$ufs1);
			$ufs3=mysqli_fetch_array($ufs2);
			if($ufs3[0]>0)
			{
				//获取数据表名
				$tbname=$_POST['yysstt'];
				//获取记录id
				$jlid=$_POST['yyiidd'];
				if($tbname=="evinfo")
					$dfs1="delete from evinfo where vid=".$jlid;
				elseif($tbname=="adinfo")
				{
					$dfs1="delete from adinfo where yid=".$jlid;
					$dfs2="delete from evinfo where vsid=".$jlid;
				}
				else
					echo "window.alert('系统删除信息失败！请重试……');history.go(-1);";
				if(mysqli_query($db,$dfs1))
				{
					echo "window.alert('删除信息成功！');";
					mysqli_query($db,$dfs2);
					echo "window.location.href='".$_SERVER['SCRIPT_NAME']."';";
				}
				else
				{
					echo "window.alert('系统删除信息失败！请重试……');history.go(-1);";
				}
			}
			else
			{
				echo "window.alert('用户验证失败！');history.go(-1);";
			}
		}
		else
		{
			echo "window.alert('用户账户名或密码不能为空！！');history.go(-1);";
		}
		echo "</script>";
	}
	elseif($_POST['dlno']=="取消")
	{
		echo "<script>window.location.href='".$_SERVER['SCRIPT_NAME']."';</script>";
	}
	else
	{
		$fsart="<form action='?deltion=yes' method='post'>";
		$fsart=$fsart."<input type='hidden' id='yyiidd' name='yyiidd' value='".$_GET['ysid']."' />";
		$fsart=$fsart."<input type='hidden' id='yysstt' name='yysstt' value='".$_GET['yst']."' />";
		$fsart=$fsart."<input type='hidden' id='dlulias' name='dlualias' value='".$_GET['uas']."' />";
		$fsart=$fsart."<table width='100%'>";
		$fsart=$fsart."<tr>";
		$fsart=$fsart."<td rowspan='2' valign='middle' bgcolor='lightgreen' align='center'><font style='font-size:30px'>确认<br/>删除<br/>信息</font></td>";
		if($_GET['yst']=="adinfo")
		{
			$fsart=$fsart."<script>window.alert('请在下方确认删除留言信息！');</script>";
			$dads="select * from ".$_GET['yst']." where yid=".$_GET['ysid']." ";
			$davs=mysqli_query($db,$dads);
			$dazs=mysqli_fetch_array($davs);
			$fsart=$fsart."<td>[".$_GET['uas']."]发布的留言信息：</td>";
			$fsart=$fsart."<td>发布时间[".$dazs['yctime']."]</td>";
			$fsart=$fsart."</tr>";
			$fsart=$fsart."<tr>";
			$fsart=$fsart."<td colspan='2'><textarea id='yyinf' name='yyinf' rows='8' cols='80' readonly>".$dazs['yuif']."</textarea></td>";
			$fsart=$fsart."</tr>";
		}
		elseif($_GET['yst']=="evinfo")
		{
			$fsart=$fsart."<script>window.alert('请在下方确认删除评论信息！');</script>";
			$deds="select * from ".$_GET['yst']." where vid=".$_GET['ysid']."";
			$devs=mysqli_query($db,$deds);
			$dezs=mysqli_fetch_array($devs);
			$fsart=$fsart."<td>[".$_GET['uas']."]发布的评价信息：</td>";
			$fsart=$fsart."<td>发布时间[".$dezs['vctime']."]</td>";
			$fsart=$fsart."</tr>";
			$fsart=$fsart."<tr>";
			$fsart=$fsart."<td colspan='2'><textarea id='yyinf' name='yyinf' rows='8' cols='80' readonly>".$dezs['vuif']."</textarea></td>";
			$fsart=$fsart."</tr>";
		}
		else
		{
			$fsart="";
			echo "<script>window.alert('删除信息状态获取错误！请刷新页面再删除');</script>";
		}
		if($fsart!="")
		{
			echo $fsart;
			echo "<tr>";
			echo "<td align='right'>[".$_GET['uas']."]的</td>";
			echo "<td>账号名：<input type='text' id='dluname' name='dluname' value='' />密码：<input type='password' id='dlpass' name='dlpass' value='' /></td>";
			echo "<td><input type='submit' id='dlys' name='dlys' value='确认' />&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' id='dlno' name='dlno' value='取消' /></td>";
			echo "</tr>";
			echo "</table>";
			echo "<form>";
			
		}
	}
}
else
{
	//创建留言发布表单
	echo "<form action=' ' method='post' id='adform' name='adform'>";
	echo "<table width='100%' align='center' border='0'>";
	echo "<tr><td rowspan='3'  width='5%' align='center' valign='middle'>请<br/>留</br>言</td><td align='left' rowspan='3'><textarea cols='70' rows='8' id='adinfo' name='adinfo'></textarea></td><td align='left'>用户账号：<input type='text' id='aduser' name='aduser' value='' /></td></tr>";
	echo "<tr><td align='left'>用户密码：<input type='password' id='adpass' name='adpass' value='' /></td></tr>";
	echo "<tr><td align='right'><a href='javascript:window.open(\"nas_user.php?create\",\"用户注册\",\"height=480, width=600, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no\")' title='没有用户请注册'>用户注册</a>&nbsp;&nbsp;&nbsp;<input type='submit' id='submit' name='submit' value='留言' />&nbsp;&nbsp;&nbsp;<input type='reset' id='reset' name='reset' value='清空' />&nbsp;&nbsp;&nbsp;</tr>";
	echo "</table>";
	echo "</form>\n";
}
?>
</div>
</body>
<?php
//关闭数据库连接
mysqli_close($db);
?>
</html>