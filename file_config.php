<?
if(!$db=mysqli_connect('10.5.5.5','nas_web','nas_web'))
{
	echo "<p>数据库连接失败！".mysqli_error()."</p>";
	exit(1);
}
//设置连接数据库的字符集
mysqli_query($db,"set names utf8");
echo "\n";
//连接数据库
mysqli_select_db($db,"nas_web");
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
$istb[0]="delete from pbfl";
$istb[1]="drop table pbfl";
$istb[2]=<<<CTBD
	create table pbfl (
				flid int not null unique comment '发布ID',
				flnm varchar(100) not null comment '发布文件名称',
				flif varchar(2000) comment '发布的文件说明',
				fltp int not null comment '文件类型,1网站分享，2公司分享，3个人分享，4其他分享',
				flmp varchar(200) comment '发布的公司名称',
				flps varchar(100) not null comment '发布用户的昵称',
				flgs varchar(2) not null comment '发布权限,u个人,g公司,p公开',
				flnk varchar(5000) not null comment '文件或网址的发布地址',
				flur varchar(100) not null comment '发布者的用户名或ID号',
				fltm datetime not null comment '发布时间',
				flop int not null comment '发布者设置的开关',
				flbk varchar(2000) comment '发布的备注'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户发布文件信息表'
CTBD;

$istb[3]=<<<ITBD1
	insert into pbfl values(
				10001,
				'微软相关激活工具',
				'支持windows7专业旗舰版、windows8全部、windows10全部、winsrv2008等等<br/>还支持office2010以上的VL版本激活',
				1,
				'lyclub',
				'管理员',
				'p',
				'http://lyclub.imwork.net:82/download/microKMS_v17.06.25.exe',
				'system',
				'2017-07-16 18:01:00',
				1,
				''
		)
ITBD1;

$istb[4]=<<<ITBD2
	insert into pbfl values(
				10002,
				'windows工具-设备调试工具',
				'支持RAW、TELNET、SSH、RLOGIN、SERIAL类型端口的链接<br/>远程调试各种服务器交换机等，串口调试设备',
				1,
				'lyclub',
				'管理员',
				'p',
				'http://lyclub.imwork.net:82/download/tools/putty.exe',
				'system',
				'2017-07-16 18:02:00',
				1,
				''
		)
ITBD2;

$istb[5]="delete from pbur";
$istb[6]="drop table pbur";
/**
		-------------------------------------------------------------------------------------------------------------------------------------------------
		| 用户ID号 | 用户账号名 | 用户密码 | 用户昵称 |所在公司 | 用户权限 | 用户电话 | 用户QQ  | 用户邮箱 | 创建时间 | 用户状态 | 状态说明 | 用户备注  |
		-------------------------------------------------------------------------------------------------------------------------------------------------
插入参数|   fuid   |    fusr    |fups(sha1)|    fual  |   fucp  |   futp   |   futl   |   fuqq  |   fuml   |   ctime  |   fuop   |   opif   |   fubk    |
		-------------------------------------------------------------------------------------------------------------------------------------------------
插入提示| 程序自加 | 表单rgurn  |表单rgurp |表单rgura |表单rgcmp|  默认vip |表单rgurt |表单rgurq|表单rgurm | sql_now()|   默认1  |  默认空  |  默认空   |
		-------------------------------------------------------------------------------------------------------------------------------------------------
数据类型|    int   | varchar100 |varchar100|varchar100|varchar100|varchar10| varchar11|varchar20|varchar100| datetime |    int   |varchar500|varchar1000|
		-------------------------------------------------------------------------------------------------------------------------------------------------
**/
$istb[7]=<<<CTUD
	create table pbur (
				fuid int not null unique comment '发布授权用户的账号ID',
				fusr varchar(100) not null comment '发布授权用户的账号名',
				fups varchar(100) not null comment '发布授权用户的账号密码sha1模式',
				fual varchar(100) not null comment '发布授权用户的账号昵称',
				fucp varchar(100) not null comment '发布授权用户的授权模式',
				futp varchar(100) not null comment '发布授权用户的公司名称',
				futl varchar(11) not null comment '发布授权用户的账号电话',
				fuqq varchar(20) not null comment '发布授权用户的账号QQ号',
				fuml varchar(100) not null comment '发布授权用户的账号邮箱',
				ctime datetime not null comment '发布授权用户的创建时间',
				fuop int not null comment '发布授权用户的异常0或者正常1',
				opif varchar(500) comment '发布授权用户的异常说明，正常为空',
				fubk varchar(1000) comment '发布授权用户的其他信息'
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户发布文件信息表'
CTUD;

$istb[8]=<<<ITUD
	insert into pbur values(
				5000,
				'system',
				'7112aec1217761f1d85436a7acebc947c6a2654c',
				'超级管理员',
				'super',
				'lyclub',
				'18562221224',
				'351188949',
				'liubingjie771@live.cn',
				'2017-07-17 21:41:05',
				1,
				'',
				''
		)
ITUD;

$istb[9]="delete from pbcp";
$istb[10]="drop table pbcp";
/**
	-------------------------------------------------------------------------------------------------------------------------
pbcp| 公司记录编号 | 公司记录代码 | 公司显示标题 | 公司注册时间 | 公司注册用户 | 公司注册状态 | 公司异常说明 | 公司备注信息 |
	-------------------------------------------------------------------------------------------------------------------------
	|     cpid     |     cpnm     |     cptl     |     ctme     |      cusr    |     cpop     |      opif    |    cpbk      |
	-------------------------------------------------------------------------------------------------------------------------
**/
$istb[11]=<<<CTCD
	create table pbcp (
				cpid int not null unique comment '公司记录编号',
				cpnm varchar(100) not null comment '公司记录代码',
				cptl varchar(1000) not null comment '公司网页显示标题',
				ctme datetime not null comment '公司记录注册时间',
				cusr varchar(100) not null comment '公司记录注册者',
				cpop int not null comment '公司发布状态',
				opif varchar(1000) comment '公司异常说明信息',
				cpkb varchar(5000) comment '公司相关补充信息'
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户发布文件信息表'
CTCD;

$istb[12]=<<<ITCD
	insert into pbcp values(
				10001,
				'lyclub',
				'星云龙韵信息',
				'2017-07-18 22:25:00',
				'system',
				1,
				'',
				''
	)
ITCD;

for($i=0;$i<count($istb);$i++)
{
	echo "<p>执行语句【".$istb[$i]."】";
	if(mysqli_query($db,$istb[$i]))
		echo "成功！";
	else
		echo "失败!";
	echo "</p>";
}
?>