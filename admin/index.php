<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
$userName=s('userName',1);
if(isne($userName)) {
	echo alert(array('reUrl'=>'login.php'));
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>后台管理--POWER BY 冬宇建站</title>
		<link rel="stylesheet" href="css/common.css" type="text/css" />
		<style type="text/css">
		body,html{width:100%;height:100%;overflow:hidden;}
		.top{height:65px;background:url(images/bgx.png) repeat-x;}
		#logo{height:65px;background:url(images/logo.png) no-repeat -15px -8px;width:350px;float:left;}
		#support{float:right;margin-right:24px;font-size:14px;}
		#support span{font-weight:bold;color:red;}
		#menuWrap{overflow-y:auto;overflow-x:hidden;}
		.menu dt{height:30px;line-height:30px;font-size:14px;text-align:center;background:url(images/bgx.png) repeat-x 13px -493px;color:white;font-weight:bold;margin-top:3px;}
		.menu dd a{height:24px;line-height:24px;display:block;background:url(images/bgx.png) repeat-x 50px -783px;text-align:left;text-indent:12px;color:#115180;}
		</style>
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
</head>
<body>
<div class="top">
	<div id="logo"></div>
	<div id="support">
		<span>技术支持：</span>QQ: 690001440 手机: 15060415786 邮箱: <a href="mailto:dongyu360@126.com">dongyu360@126.com</a>
	</div>
	<div class="clear"></div>
</div>
 <table width="99%" height="100%" style="table-layout:fixed;">
		<tr>
			<td width="150" valign="top">
			<div id="menuWrap">
			<?php
			$pArr=array(
				'xmlFile'=>'xml/leftMenu.xml',
				'xslFile'=>'xsl/leftMenu.xsl'
			);
			echo xslt($pArr);
			?>
			</div>
			</td>
			<td height="100%" valign="top">
			<div id="dIframe">
				<iframe name="iframePage" style="width:100%;height:100%;" src="adminList.php"></iframe>
			</div>
			</td>
		</tr>
 </table>
 <script type="text/javascript">
 $('#menuWrap').height(screen.availHeight-230);
 $('#dIframe').height(screen.availHeight-240);
 </script>
</body>
</html>