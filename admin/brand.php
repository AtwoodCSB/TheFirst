<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>后台管理</title>
		<link rel="stylesheet" href="css/common.css" type="text/css" />
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/chkForm.js"></script>
		<script type="text/javascript" src="/js/pop.js"></script>
		<link rel="stylesheet" href="/js/popSkin/facebook/facebook.css" type="text/css" />
		<style type="text/css">
		.brandItem{font-size:14px;}
		.brandItem .hli{width:200px;line-height:24px;float:left;}
		</style>
</head>
<body>
<?php
if($act=='') {
	echo "<div class=\"pageTitle\">配件品牌管理--请选择配件</div>";
	$computerArr=unserialize(file_get_contents(SITEROOT.'/data/computer.arr'));
	echo '<ul class=\'brandItem\'>';
	foreach($computerArr as $k=>$v){
		echo "<li class='hli'><a href='tree.php?dataFlag=brand{$v['id']}'>{$v['cName']}</a></li>";
	}
	echo '<li class="clear"></li>';
	echo '</ul>';
}
?>
</body>
</html>

<?php closeConn();?>