<html>
<head>
</head>
<body>
<?php
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
echo "\n";
//删除数据表
$dtb[0]="drop table wxfault";
$dtb[1]="drop table wxsolve";
//创建故障登记数据表
$ctb[0]=<<<FAULT
CREATE TABLE wxfault (
		  fltid bigint(15) NOT NULL DEFAULT '0' COMMENT '登记表单编号',
		  frtip varchar(50) NOT NULL DEFAULT '' COMMENT '登记表单的IP地址',
		  fname varchar(100) NOT NULL DEFAULT '' COMMENT '登记客户名称',
		  ftelp varchar(12) NOT NULL DEFAULT '' COMMENT '登记客户联系电话',
		  finfo varchar(2000) NOT NULL DEFAULT '' COMMENT '登记故障的问题介绍',
		  fbao int(11) NOT NULL DEFAULT '0' COMMENT '1保内，0保外',
		  fware varchar(1000) DEFAULT '' COMMENT '维修附加的配件',
		  ftime datetime DEFAULT '0000-00-00 00:00:00' COMMENT '登记表单创建时间',
		  fstus int(11) NOT NULL DEFAULT '0' COMMENT '处理是否完成',
		  fbak varchar(1000) DEFAULT NULL COMMENT '故障其他信息',
		  UNIQUE KEY fltid (fltid)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='故障登记的信息'
FAULT;
//创建故障处理数据表
$ctb[1]=<<<SOLVE
CREATE TABLE wxsolve (
		  fltid bigint(15) NOT NULL DEFAULT '0' COMMENT '登记表单编号',
		  fsvid int(11) NOT NULL DEFAULT '0' COMMENT '处理次数编号1-5',
		  sinfo varchar(2000) NOT NULL DEFAULT '' COMMENT '处理描述',
		  fstus int(11) NOT NULL DEFAULT '0' COMMENT '处理是否完成',
		  fmony float NOT NULL DEFAULT '0.00' COMMENT '处理过程产生的费用',
		  svuse varchar(100) NOT NULL DEFAULT '' COMMENT '处理的维修工姓名',
		  stime datetime DEFAULT '0000-00-00 00:00:00' COMMENT '维修处理提交时间',
		  svbak varchar(1000) NULL DEFAULT '' COMMENT '处理的其他信息',
		  UNIQUE KEY fltid (fltid,fsvid)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='故障处理的信息'
SOLVE;
$itb[0]=<<<FONE
	insert into wxfault value(
							20161216221132,
							'10.5.55.220',
							'店内-管理员',
							'18562221224',
							'计算机提示升级游戏，升级不到一半蓝屏，关机关不死',
							0,
							'无',
							'2016-12-16 22:11:23',
							1,
							''
				)
FONE;

$itb[1]=<<<SONE
	insert into wxsolve value(
							20161216221132,
							1,
							'系统没有瘫痪，系统正常进入，发现D盘看不到空间大小，因为是固态硬盘无法找回数据直接格式化，重新安装游戏。',
							1,
							30.15,
							'管理员',
							now(),
							''
				)
SONE;
$stb[0]="select * from wxfault";
$stb[1]="select * from wxsolve";
for($i=0;$i<2;$i++)
{
	if(mysqli_query($db,$dtb[$i]))
		echo "<p>数据表【".$dtb[$i]."】删除成功</p>";
	else
		echo "<p>数据表【".$dtb[$i]."】删除失败</p>";
}
for($i=0;$i<2;$i++)
{
	if(mysqli_query($db,$ctb[$i]))
		echo "<p>数据表【".$ctb[$i]."】创建成功</p>";
	else
		echo "<p>数据表【".$ctb[$i]."】创建失败</p>";
}
for($i=0;$i<2;$i++)
{
	if(mysqli_query($db,$itb[$i]))
		echo "<p>数据【".$itb[$i]."】插入成功</p>";
	else
		echo "<p>数据【".$itb[$i]."】插入失败</p>";
}
for($i=0;$i<2;$i++)
{
	print_r(mysqli_fetch_array(mysqli_query($db,$stb[$i])));
}
mysqli_close($db);
?>
</body>
</html>