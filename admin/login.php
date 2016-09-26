<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');

$act=f("act","g");

if($act=='login') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'select',
			'tbl' => 'admin',
			'fld' => 'userName,pwd',
			'where' =>
			array (
				'userName' => array(),
				'pwd' => array(),
			)
		),
		'stmt' =>
		array (
			'userName' => f('userName'),
			'pwd' => f('pwd'),
		),
	);
	$rs=exeCmd($sqlArr,'array');
	if(count($rs)==0) {
		$msg='账号密码有误,请重新输入';
		$reUrl=$g_reUrl;
	}else{
		loginSession($rs[0]['userName'],$rs[0]['pwd'],0);
		$msg='';
		$reUrl='index.php';
	}
	echo alert(array('msg'=>$msg,'reUrl'=>$reUrl));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="冬宇建站" />
    <meta name="description" content="冬宇建站" />
    <title>后台管理--POWER BY 冬宇建站</title>
		<link rel="stylesheet" href="css/common.css" type="text/css" />
		<style type="text/css">
		#logo{height:74px;background:url(images/logo.png) no-repeat 1px 1px;}
		#dForm .form{border-left:1px #8DA0AE solid;border-right:1px #8DA0AE solid;height:180px;}
		#dForm .bTop{height:74px;background:url(images/login.png) no-repeat -7px -2px;}
		#dForm .bTop .fr{width:12px;height:74px;background:url(images/login.png) no-repeat -960px -2px;float:right;}
		#dForm .bbot{height:12px;background:url(images/login.png) no-repeat -7px -73px;}
		#dForm .bbot .fr{width:12px;height:12px;background:url(images/login.png) no-repeat -960px -73px;float:right;}
		.inp{border:1px #0F5A93 solid;}
		.formTbl th{color:#333;font-size:14px;letter-spacing:6px;}
		.formTbl .btn{border:0;width:116px;height:42px;background:url(images/login.png) no-repeat -9px -99px;color:white;font-size:14px;}
		</style>
</head>
<body>
	<div class="container_24">
		<div id="logo" class="grid_24"></div>
		<div id="dForm" class="grid_24">
			<div class="bTop"><div class="fr"></div></div>
			<div class="form">
			<form action="?act=login" method="post">
				<table class="formTbl" align="center" width="700">
					<tr>
						<th>账号：</th>
						<td><input type="text" name="userName" class="inp" size="50"/></td>
					</tr>
					<tr>
						<th>密码：</th>
						<td><input type="password" name="pwd" class="inp" size="50"/></td>
					</tr>
					<tr>
						<th></th>
						<td><input type="submit" class="btn" value="登 录"/></td>
					</tr>
				</table>
			</form>
			</div>
			<div class="bbot"><div class="fr"></div></div>
		</div>
	</div>
</body>
</html>