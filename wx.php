<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>维修登记系统</title>
<style type='text/css'>
#logo {
position:fixed;
background:#66FFCC;
top:0px;
left:0px;
width:100%;
height:10%;
}
#wbif {
position:fixed;
background:#66FFCC;
bottom:0px;
left:0px;
width:100%;
height:5%;
}
#zbfm {
position:fixed;
top:10%;
left:20%;
right:0%;
width:80%;
height:85%;
background:white;
frameborder:0px;
border:0px;
scrolling:auto;
}
#idlist
{
position:fixed;
background:#66CCFF;
top:20%;
left:0px;
width:20%;
height:60%;
}
body {
	background-color: #66FFCC;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script language='javascript'>
//打印图片或者iframe
 function doPrintsrc(objs)  
 {  
		var turl = document.getElementById(objs).src;
        var newW = window.open(turl);
        newW.print(); 
 }
 //临时添加iframe文件的信息
 function b() {
   zbfm.document.designMode = "On";
   zbfm.document.body.innerHTML = "请单击<button style='background:green;color:white;'><b>新建登记表</b></button>或者<font style='color:lightgreen'>输入要查询的登记编号、客户名称及联系电话点击<input type='button' value='查询' /></font>";
}
</script>
</head>

<body onload="b()">
<center>
<?php
if($_SERVER['QUERY_STRING']=="search")
{
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
	$sce=" where ";
	if($_POST['djid']!=NULL)
		$sce=$sce."fltid=".$_POST['djid']." and ";
	if($_POST['djnm']!=NULL)
		$sce=$sce."fname like '%".$_POST['djnm']."%' and ";
	if($_POST['djtl']!=NULL)
		$sce=$sce."ftelp like '%".$_POST['djtl']."%'  and ";
	$sce=$sce."0<1";
	if($bh1=mysqli_query($db,"select fltid,fname,ftime from wxfault".$sce))
	{
			echo "<div id='idlist' name='idlist'><center><table>";
			while($ar=mysqli_fetch_array($bh1))
			{
				echo "<tr><td><a href='wx1.php?djid=".$ar['fltid']."' target='zbfm'><button>".$ar['fltid']."<br/>".$ar['fname']."<br/>".$ar['ftime']."</button></a></td></tr>";
			}
			echo "</table></center></div>";
			echo  "window.alert(\"默认不显示维修单，\n请单击左侧的查询结果\");";
	}
	else
	{
		echo "<script>window.alert('您输入的信息有误！');history.go(-1);</script>";
	}
	mysqli_close($db);
}
?>
<div id='logo' name='logo'>
<p></p>
<table border="0" width="100%">
<tr>
<td><a href='wx1.php' target='zbfm'><button style='background:green;color:white;'><b>新建登记表</b></button></a></td>
<form action='?search' method='post'>
<td>查询登记编号<input type='text' id='djid' name='djid' value='' /></td>
<td>查询客户名称<input type='text' id='djnm' name='djnm' value='' /></td>
<td>查询联系电话<input type='text' id='djtl' name='djtl' value='' /></td>
<td><input type='submit' value='查询' /></td>
<td><input type='button' value='退出' onclick="javascript:window.close();" /></td>
</form>
</tr>
</table>
</div>
<iframe id='zbfm' name='zbfm'  ></iframe>
<div id='wbif' name='wbif'></div>
</center>
</body>
</html>
