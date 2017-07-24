<html>
<head>
<link rel="shortcut icon" href="/web_images/16X16.ico" />
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<title>通讯录</title>
</head>
<body>
<?php
//忽略错误信息
error_reporting(0);
//开启数据库连接
if(!$con=mysqli_connect('10.5.5.5','nas_web','nas_web'))
{
	echo "window.alert('数据库连接失败！".mysqli_error()."');";
	exit(1);
}
//连接数据库
mysqli_select_db($con,"nas_web");
function txl_add_data($cn,$dt)
{
	/*if(mysqli_query($cn,"drop table TongXunLu"))
	{
		echo "<font style='color:red'><b>通讯录数据表删除成功</b></font><!--$ctb-->";
	}
	else
	{
		echo "<script>window.alert(\"通讯录数据表删除失败/n/n$ctb/n/n".mysqli_error()."\");history.go(-1);</script>";
	}*/
	if(!mysqli_query($cn,"select * from TongXunLu"))
	{
		$ctb="create table TongXunLu(tid int unique not null comment '通讯录序号',tnm varchar(20) not null comment '当时在校的姓名',ttl varchar(20) not null comment '现在的手机号',twq varchar(20) not null comment '现在的腾讯QQ微信号',tml varchar(200) not null comment '现在的邮箱地址',twz varchar(1000) not null comment '现在的职业描述',tbk varchar(2000) comment '该信息的备注')";
		if(mysqli_query($cn,$ctb))
		{
			echo "<font style='color:red'><b>通讯录数据表创建成功</b></font><!--$ctb-->";
		}
		else
		{
			echo "<script>window.alert(\"通讯录数据表创建失败/n/n$ctb/n/n".mysqli_error()."\");history.go(-1);</script>";
		}
	}
	else
	{
		if($mxm=mysqli_query($cn,"select max(tid) from TongXunLu"))
		{
			$mxd=mysqli_fetch_array($mxm);
			$mi=$mxd[0]+1;
		}
		else
		{
			$mi=10001;
		}
		$itb="insert into TongXunLu value($mi,'".$dt[0]."','".$dt[1]."','".$dt[2]."','".$dt[3]."','".$dt[4]."','')";
		if(mysqli_query($cn,$itb))
		{
			echo "<font style='color:red'><b>通讯记录创建成功</b></font><!--$itb-->";
		}
		else
		{
			echo "<script>window.alert(\"通讯记录创建失败\n$itb\n".mysqli_error()."\");history.go(-1)</script>";
		}
	}
}

if($_SERVER['QUERY_STRING']=="view")
{
	if($_POST['txl_nm']!=""&&$_POST['txl_tl']!=""&&$_POST['txl_wq']!=""&&$_POST['txl_ml']!="")
	{
		$adds[0]=$_POST['txl_nm'];
		$adds[1]=$_POST['txl_tl'];
		$adds[2]=$_POST['txl_wq'];
		$adds[3]=$_POST['txl_ml'];
		$adds[4]=$_POST['txl_zw'];
		txl_add_data($con,$adds);
	}
	else
	{
		if($_POST['txl_sbmt']!="")
			echo "<script>window.alert('表单中有未填写的');history.go(-1)</script>";
	}
}
else
{
	echo <<<FMTB
			<fieldset>
			<legend>添加通讯录信息：</legend>
			<form action=" ?view" method="post" />
			<table border="0">
			<tr><th align="right">学生用名：</th><td><input type="text" id="txl_nm" name="txl_nm" value="" placeholder="请输入当时的姓名"  size="60" /></td></tr>
			<tr><th align="right">手机号码：</th><td><input type="text" id="txl_tl" name="txl_tl" value="" placeholder="请输入现在的手机号"  size="60" /></td></tr>
			<tr><th align="right">微信QQ号：</th><td><input type="text" id="txl_wq" name="txl_wq" value="" placeholder="请输入常用的QQ号码"  size="60" /></td></tr>
			<tr><th align="right">邮箱地址：</th><td><input type="text" id="txl_ml" name="txl_ml" value="" placeholder="请输入经常使用的Email地址" size="60" /></td></tr>
			<tr><th align="right">职业描述：</th><td><textarea id="txl_zw" name="txl_zw" placeholder="请输入你是干什么的，比如：维修电脑、水电维修等等" size="60"></textarea></td></tr>
			<tr><td></td><td><input type="submit" value="发布信息" id="txl_sbmt" name="txl_sbmt" /></td></tr>
			</table>
			</form>
			</fieldset>
FMTB;
}
if($vdt=mysqli_query($con,"select * from TongXunLu"))
{
	echo <<<VWTB_HEAD
		<fieldset>
		<legend>通讯录列表:</legend>
		<table border="1" >
		<tr><th>姓名</th><th>电话</th><th>微信QQ</th><th>邮箱</th><th>职业</th></tr>
VWTB_HEAD;
}
while($vdl=mysqli_fetch_array($vdt))
{
	
	echo "<tr>";
	for($i=1;$i<6;$i++)
		echo "<td>".$vdl[$i]."</td>";
	echo "</tr>";
}
	echo "</table></fieldset>";
mysqli_close($con);
?>
</body>
</html>