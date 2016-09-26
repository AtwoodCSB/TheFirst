<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT . '/common/pic.php');
require_once(SITEROOT . '/common/form.php');
require_once(SITEROOT . '/common/adminFn.php');

$act=f('act','g');
$act=isne($act) ? 'list' : $act;

if($act=='saveDel') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'delete',
			'tbl' => 'orders',
			'where' =>
			array (
				'id' => array(),
			),
		),
		'stmt' =>
		array (
			'id' => array(f('id','g'),'i'),
		),
	);
	exeCmd($sqlArr);
	delUploadFile(SITEROOT.$imgFile,1);
	echo alert(array('reUrl'=>$g_reUrl));
	die();
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
		<script type="text/javascript" src="/js/ajaxForm.js"></script>
		<script type="text/javascript" src="/admin/js/common.js"></script>
		<style type="text/css">
		.pzdTbl th{text-align:center;color:#666;}
		</style>
</head>
<body>
<?php
echo "<div class=\"pageTitle\">配置单列表</div>";
if($act=='list') {
	$cfgTypeArr=unserialize(file_get_contents(SITEROOT.'/data/cfgType.arr'));
	$pageSize=20;
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'select',
			'tbl' => 'orders',
			'fld' => 'id,cid,title,content,ordersDesc,totalPrice',
		),
		'sp'=>array(
			'key'=>'id',
			'size'=>$pageSize,
			),
	);

	$fnVal=sqlToXml($sqlArr,array('cid'=>array('getCNameByArr',array($cfgTypeArr),'cName')));
	$xmlStr=$fnVal['xmlStr'];
	$rsCount=$fnVal['rsCount'];
	$spbar=spBar(array('rsCount'=>$rsCount,'pageSize'=>$pageSize));
	$pArr=array(
		'xmlStr'=>$xmlStr,
		'xslFile'=>'xsl/ordersList.xsl'
	);
	$html=xslt($pArr);
	$html=str_replace('{%spbar%}',$spbar,$html);
	echo $html;
}

if($act=='showCont') {
	$cfgTypeArr=unserialize(file_get_contents(SITEROOT.'/data/cfgType.arr'));
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'select',
			'tbl' => 'orders',
			'fld' => 'id,cid,title,content,ordersDesc,totalPrice',
			'where' =>
			array (
				'id' => array(),
			),
		),
		'stmt' =>
		array (
			'id' => array(f('id'),'i'),
		),
	);
	$xmlStr=sqlToXml($sqlArr,array('cid'=>array('getCNameByArr',array($cfgTypeArr),'cName')),array('ordersDesc'));
	$pArr=array(
		'xmlStr'=>$xmlStr,
		'xslFile'=>'xsl/orders.xsl',
	);
	echo xslt($pArr);
}
?>

</body>
</html>

<?php closeConn();?>