<?php
if(!$db=mysqli_connect('10.5.5.5','nas_web','nas_web'))
{
	echo "<p>0、数据库连接失败！".mysqli_error();
	exit(1);
}

//设置连接数据库的字符集
mysqli_query($db,"set names utf8");
echo "\n";

//初始化删除数据库
/*
$dpt="drop database nas_web";
if(!mysqli_query($db,$dpt))
{
	echo "<p>1、数据库($dpt)删除失败！</p>";
	exit(1);
}
else
	echo "<p>1、数据库($dpt)删除成功！</p>";
echo "\n";
*/

//创建数据库
/*
$cdt="create database if not exists nas_web default charset utf8";
if(!mysqli_query($db,$cdt))
{
	echo "<p>2、数据库($cdt)创建失败！</p>";
	exit(1);
}
else
	echo "<p>2、数据库($cdt)创建成功！</p>";
echo "\n";
*/

//连接数据库
mysqli_select_db($db,"nas_web");
echo "\n";
//创建用户数据表
$ctb1=<<<CUTB
CREATE TABLE users (
  uid int(11) NOT NULL DEFAULT '0' COMMENT '用户自动编码五位数',
  uname varchar(30) NOT NULL DEFAULT '' COMMENT '用户登录名',
  ualias varchar(30) NOT NULL DEFAULT '' COMMENT '用户显示昵称',
  usha1 varchar(64) NOT NULL DEFAULT '' COMMENT '用户登录密码转为sha1',
  upass varchar(32) NOT NULL DEFAULT '' COMMENT '用户登录的明文，网站不调用此段',
  utel varchar(20) NOT NULL DEFAULT '' COMMENT '用户的手机号',
  udate date NOT NULL DEFAULT '0000-00-00' COMMENT '用户的生日日期',
  uqq varchar(20) DEFAULT NULL COMMENT '用户的QQ号码',
  umail varchar(100) DEFAULT NULL COMMENT '用户的邮箱地址',
  uctime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '用户注册时的日期时间',
  untime datetime DEFAULT NULL COMMENT '用户最后一次修改的时间，注册时为空',
  ubak varchar(1000) DEFAULT NULL COMMENT '可选，用户的相关信息',
  UNIQUE KEY `uid` (`uid`,`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站注册的用户信息';
CUTB;
if(!mysqli_query($db,$ctb1))
{
	echo "<p>3、数据表($ctb1)创建失败！</p>";
	exit(1);
}
else
	echo "<p>3、数据表($ctb1)创建成功！</p>";

echo "\n";
//插入用户liubingjie771到数据表
$cti1="INSERT INTO users VALUES (10001,'liubingjie771','刘星云-管理员','7112aec1217761f1d85436a7acebc947c6a2654c','lbj*891021','18562221224','1989-10-21','351188949','liubingjie771@live.cn',now(),NULL,'')";
if(!mysqli_query($db,$cti1))
{
	echo "<p>4、数据表($cti1)插入失败！</p>";
	exit(1);
}
else
	echo "<p>4、数据表($cti1)插入成功！</p>";

echo "\n";
//创建第一条留言到数据表
$ctb2=<<<CYTB
CREATE TABLE adinfo (
		  yid int(11) NOT NULL DEFAULT '0' COMMENT '留言ID，删除后ID就空余。',
		  ysid int(11) DEFAULT NULL COMMENT '评论接收留言ID，留言不是评论此段为空',
		  yuid int(11) NOT NULL DEFAULT '0' COMMENT '留言时登录用户的ID',
		  yuif varchar(2000) NOT NULL DEFAULT '' COMMENT '留言的信息也可以是html段',
		  rstatus int(11) NOT NULL DEFAULT '0' COMMENT '留言是否为已读',
		  yctime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '新建留言的时间',
		  yutime datetime DEFAULT NULL COMMENT '修改留言最后一次的时间',
		  ybak varchar(1000) DEFAULT NULL COMMENT '留言的其他相关信息',
		  UNIQUE KEY yid (yid)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户留言的信息'
CYTB;
if(!mysqli_query($db,$ctb2))
{
	echo "<p>5、数据表($ctb2)创建失败！</p>";
	exit(1);
}
else
	echo "<p>5、数据表($ctb2)创建成功！</p>";

echo "\n";
//插入第一条留言数据信息
$cti2="INSERT INTO adinfo VALUES (1,NULL,10001,'欢迎使用山东星云龙韵信息俱乐部的留言板！',1,now(),NULL,'')";
if(!mysqli_query($db,$cti2))
{
	echo "<p>6、数据表($cti2)插入失败！</p>";
	exit(1);
}
else
	echo "<p>6、数据表($cti2)插入成功！</p>";
echo "\n";
//插入第二条留言数据信息
/*
$cti2="INSERT INTO adinfo VALUES (2,NULL,10001,'快快使用留言板吧！',1,now(),NULL,'')";
if(!mysqli_query($db,$cti2))
{
	echo "<p>7、数据表($cti2)插入失败！</p>";
	exit(1);
}
else
	echo "<p>7、数据表($cti2)插入成功！</p>";
echo "\n";
*/
//创建评价数据表
$ctb2=<<<CYTB
CREATE TABLE evinfo (
		  vid int(11) NOT NULL DEFAULT '0' COMMENT '评价ID，删除后ID就空余。',
		  vsid int(11) DEFAULT NULL COMMENT '评论接收留言ID，留言不是评论此段为空',
		  vuid int(11) NOT NULL DEFAULT '0' COMMENT '评价时登录用户的ID',
		  vuif varchar(2000) NOT NULL DEFAULT '' COMMENT '评论的信息也可以是html段',
		  rstatus int(11) NOT NULL DEFAULT '0' COMMENT '评价是否为已读',
		  vctime datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '新建评论的时间',
		  vutime datetime DEFAULT NULL COMMENT '修改留言最后一次的时间',
		  vbak varchar(1000) DEFAULT NULL COMMENT '评价的其他相关信息',
		  UNIQUE KEY vid (vid)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户评论的信息'
CYTB;
if(!mysqli_query($db,$ctb2))
{
	echo "<p>8、数据表($ctb2)创建失败！</p>";
	exit(1);
}
else
	echo "<p>8、数据表($ctb2)创建成功！</p>";

echo "\n";
//插入第二条留言的评价1到数据表
/*
$cti2="INSERT INTO evinfo VALUES (1,2,10002,'给第二条留言评价1！',1,now(),NULL,'')";
if(!mysqli_query($db,$cti2))
{
	echo "<p>9、数据表($cti2)插入失败！</p>";
	exit(1);
}
else
	echo "<p>9、数据表($cti2)插入成功！</p>";
echo "\n";
*/
//插入第二条留言的评价2到数据表
/*
$cti2="INSERT INTO evinfo VALUES (2,2,10001,'给第二条留言评价2！',1,now(),NULL,'')";
if(!mysqli_query($db,$cti2))
{
	echo "<p>10、数据表($cti2)插入失败！</p>";
	exit(1);
}
else
	echo "<p>10、数据表($cti2)插入成功！</p>";
echo "\n";
*/
//插入用户test到数据表
/*
$cti1="INSERT INTO users VALUES (10002,'test','网站测试员','7112aec1217761f1d85436a7acebc947c6a2654c','lbj*891021','18562221224','351188949',now(),NULL,'')";
if(!mysqli_query($db,$cti1))
{
	echo "<p>11、数据表($cti1)插入失败！</p>";
	exit(1);
}
else
	echo "<p>11、数据表($cti1)插入成功！</p>";

echo "\n";
*/


//显示用户信息
echo "<p>";
$urs1=mysqli_query($db,"select * from users");
while($urs2=mysqli_fetch_array($urs1))
{
	print_r($urs2);
}
print_r();
echo "</p>";
echo "\n";
//显示留言信息
echo "<p>";
$ads1=mysqli_query($db,"select * from adinfo");
while($ads2=mysqli_fetch_array($ads1))
{
	print_r($ads2);
}
echo "</p>";
//显示评价信息
echo "<p>";
$evs1=mysqli_query($db,"select * from evinfo");
while($evs2=mysqli_fetch_array($evs1))
{
	print_r($evs2);
}
echo "</p>";
?>