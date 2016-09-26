<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT . '/common/form.php');
require_once(SITEROOT . '/common/adminFn.php');

$formFile='form/admin.html';
$act=f("act","g");
$id=f("id","g");

if($act=="saveAdd" || $act=="saveEdit") {
	$formCfgFile=str_replace('.html','.formCfg',$formFile);
	$arr=pFormCfg($formCfgFile);
	if(count($arr['err'])>0){
		$errStr=join('\n',$arr['err']);
		echo alert(array('reUrl'=>$g_reUrl,'msg'=>$errStr));
	}
}

if($act=="saveAdd") {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'insert',
			'tbl' => 'admin',
			'fld' =>
			array (
				'userName' => '',
				'pwd' => '',
			),
		),
		'stmt' =>
		array (
			'userName' => f('userName'),
			'pwd' => f('pwd'),
		),
	);
	exeCmd($sqlArr);
	echo alert(array('reUrl'=>'adminList.php'));
}

if($act=='saveEdit') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'update',
			'tbl' => 'admin',
			'fld' =>
			array (
				'userName' => '',
				'pwd' => '',
			),
			'where' =>
			array (
				'id' => array(),
			),
		),
		'stmt' =>
		array (
			'userName' => f('userName'),
			'pwd' => f('pwd'),
			'id' => $id,
		),
	);
	exeCmd($sqlArr);
	echo alert(array('reload'=>'1','target'=>'parent'));
}

if($act=='saveDel') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'delete',
			'tbl' => 'admin',
			'where' =>
			array (
				'id' => array()
			),
		),
		'stmt' =>
		array (
			'id' => $id
		),
	);
	exeCmd($sqlArr);
	echo alert(array('reUrl'=>$g_reUrl));
}
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
		</style>
</head>
<body>
<?php
if($act=='') {
	echo "<div class=\"pageTitle\">管理员列表</div>";
	$xmlStr=sqlToXml('select * from admin order by id desc');
	$pArr=array(
		'xmlStr'=>$xmlStr,
		'xslFile'=>'xsl/adminList.xsl',
		'clear'=>'1'
	);
	echo "<div id='dTree'>".xslt($pArr)."</div>";
}

if($act=='add') {
	$formStr=file_get_contents($formFile);
	echo $formStr;
}

if($act=='edit') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'select',
			'tbl' => 'admin',
			'fld' => 'id,userName,pwd',
			'where' =>
			array (
				'id' => array(),
			)
		),
		'stmt' =>
		array (
			'id' => $id,
		),
	);
	$rsArr=exeCmd($sqlArr,'array');
	$id=$rsArr[0]['id'];
	$formStr=fillForm($formFile,$rsArr[0]);
	$formStr=str_replace(array('action="?act=saveAdd"','<caption>添加管理员</caption>'),array("action=\"?act=saveEdit&id={$id}\"",'<caption>编辑管理员</caption>'),$formStr);
	echo $formStr;
}
?>

<script type="text/javascript" defer="1">
$(function(){
	iframepop($("a[popBox='1']"));
});
</script>

</body>
</html>

<?php closeConn();?>