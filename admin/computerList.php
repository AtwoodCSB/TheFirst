<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT . '/common/pic.php');
require_once(SITEROOT . '/common/form.php');
require_once(SITEROOT . '/common/adminFn.php');

$act=f('act','g');
$act=isne($act) ? 'list' : $act;

if($act=='saveAdd' || $act=='saveEdit') {
	$formChkArr=pFormCfg('form/computer.formCfg');
	formReturn($formChkArr);
}

if($act=='saveAdd') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'insert',
			'tbl' => 'computer',
			'fld' =>
			array (
				'cid' => '',
				'brandid' => '',
				'title' => '',
				'price' => '',
			),
		),
		'stmt' =>
		array (
			'cid' => array(f('cid'),'i'),
			'brandid' => array(f('brandid'),'i'),
			'title' => f('title'),
			'price' => array(f('price'),'i'),
		),
	);
	exeCmd($sqlArr);
	echo alert(array('reUrl'=>'computerList.php'));
	die();
}

if($act=='saveEdit') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'update',
			'tbl' => 'computer',
			'fld' =>
			array (
				'cid' => '',
				'brandid' => '',
				'title' => '',
				'price' => '',
			),
			'where' =>
			array (
				'id' => array(),
			),
		),
		'stmt' =>
		array (
			'cid' => array(f('cid'),'i'),
			'brandid' => array(f('brandid'),'i'),
			'title' => f('title'),
			'price' => array(f('price'),'i'),
			'id' => array(f('id','g'),'i'),
		),
	);
	exeCmd($sqlArr);
	echo alert(array('reUrl'=>f('reUrl')));
	die();
}

if($act=='saveDel') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'delete',
			'tbl' => 'computer',
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
		</style>
</head>
<body>
<?php
echo "<div class=\"pageTitle\">电脑配件列表</div>";
if($act=='list') {
	$computerArr=unserialize(file_get_contents(SITEROOT.'/data/computer.arr'));
	$pageSize=20;
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'select',
			'tbl' => 'computer',
			'fld' => 'id,cid,brandid,title,price',
		),
		'sp'=>array(
			'key'=>'id',
			'size'=>$pageSize,
			),
	);

	$fnVal=sqlToXml($sqlArr,array('cid'=>array('getCNameByArr',array($computerArr),'cName')));
	$xmlStr=$fnVal['xmlStr'];
	$rsCount=$fnVal['rsCount'];
	$spbar=spBar(array('rsCount'=>$rsCount,'pageSize'=>$pageSize));
	$pArr=array(
		'xmlStr'=>$xmlStr,
		'xslFile'=>'xsl/computerList.xsl'
	);
	$html=xslt($pArr);
	$html=str_replace('{%spbar%}',$spbar,$html);
	echo $html;
}

if($act=='add') {
	echo jsonVer(SITEADMIN.'/form/computer.formCfg');
	$formStr=file_get_contents(SITEADMIN.'/form/computer.html');
	echo $formStr;
}

if($act=='edit') {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'select',
			'tbl' => 'computer',
			'fld' => 'id,cid,brandid,title,price',
			'where' =>
			array (
				'id' => array(),
			),
		),
		'stmt' =>
		array (
			'id' => f('id','g'),
		),
	);
	$arr=exeCmd($sqlArr,'array');
	$rsArr=$arr[0];
	echo jsonVer(SITEADMIN.'/form/computer.formCfg');
	$replaceArr=array(array('?act=saveAdd','添加','{%reUrl%}'),array('?act=saveEdit&amp;id='.$rsArr['id'],'编辑',$g_reUrl));
	echo fillForm(SITEADMIN.'/form/computer.html',$rsArr,'',$replaceArr);
}
?>

</body>
</html>

<?php closeConn();?>