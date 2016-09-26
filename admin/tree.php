<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT . '/common/form.php');
require_once(SITEROOT . '/common/adminFn.php');

$act=f("act","g");
$id=f("id","g");
$reUrl=$_SERVER['HTTP_REFERER'];

$dataFlag=f("dataFlag","g");
if($dataFlag=='') {
	echo '请指定dataFlag参数';die();
}

switch($dataFlag){
	case 'computer':
			$pageTitle='电脑配件分类';
			break;
	case 'cfgType':
			$pageTitle='配置单类型分类';
			break;
}

if(strpos($dataFlag,'brand')!== false) {
	$cid=str_replace('brand','',$dataFlag);
	$computerArr=unserialize(file_get_contents(SITEROOT.'/data/computer.arr'));
	$computerCname=getCNameByArr($computerArr,$cid);
	$pageTitle=$computerCname.'配件品牌管理';
}

$dataCfg=array();

$attrEx=array();

$xslFile=$dataCfg[$dataFlag]['xsl'] ? $dataCfg[$dataFlag]['xsl'] : SITEROOT.'/xsl/showTreeTbl.xsl';
$formFile=$dataCfg[$dataFlag]['form'] ? $dataCfg[$dataFlag]['form'] : SITEROOT.'/admin/form/catalog.html';
$xmlAttrs=$dataCfg[$dataFlag]['xmlAttrs'] ? $dataCfg[$dataFlag]['xmlAttrs'] : 'id,cName';
$arrFileAttrs=$dataCfg[$dataFlag]['arrFileAttr'] ? $dataCfg[$dataFlag]['arrFileAttr'] : 'id,cName';
$jsonFileAttrs=$dataCfg[$dataFlag]['jsonFileAttr'] ? $dataCfg[$dataFlag]['jsonFileAttr'] : 'id,cName';

$xmlFile=SITEROOT.'/data/'.$dataFlag.'_data.xml';

function updateCache($xmlFile,$dataFlag,$pArr) {
	opTreeCache($xmlFile,$pArr);
	updateJsonVerCache($dataFlag);
}

if($act=="saveAdd" || $act=="saveEdit") {
	$formCfgFile=str_replace('.html','.formCfg',$formFile);
	$arr=pFormCfg($formCfgFile);
	if(count($arr['err'])>0){
		$errStr=join('\n',$arr['err']);
		echo alert(array('reUrl'=>$reUrl,'msg'=>$errStr));
	}
}

if($act=="saveAdd") {
	$xmlFile=SITEROOT.'/data/'.$dataFlag.'_data.xml';
	if(file_exists($xmlFile)) {
		$xmlStr=file_get_contents($xmlFile);
	}else{
		$xmlStr='<list />';
	}
	$xml = new DOMDocument();
	$xml->loadXml($xmlStr);
	$listNode=$xml->getElementsByTagName('list')->item(0);
	$maxId=$listNode->getAttribute('maxId');

	$xpath = new DOMXPath($xml);
	if(!isne($id)) {
		$appendNode = $xpath->query("/list//item[@id={$id}]")->item(0);
	}else{
		$appendNode=$listNode;
	}
	$maxSortId=$appendNode->getAttribute('maxSortId');

	$maxId++;
	$maxSortId++;
	$nNode=$xml->createElement("item");
	$nNode->setAttribute('id',$maxId);
	$nNode->setAttribute('sortId',$maxSortId);
	$nNode->setAttribute('maxSortId',0);
	$xmlAttrsArr=explode(',',$xmlAttrs);
	foreach($xmlAttrsArr as $attrName){
		if(!isne(f($attrName))) {
			$nNode->setAttribute($attrName,f($attrName));
		}
	}
	$appendNode->appendChild($nNode);

	$appendNode->setAttribute('maxSortId',$maxSortId);
	$listNode->setAttribute('maxId',$maxId);
	$xml->save($xmlFile);
	echo alert(array('reUrl'=>'tree.php?dataFlag='.$dataFlag,'target'=>'parent'));
}

if($act=='saveEdit') {
	$xml = new DOMDocument();
	$xml->load($xmlFile);
	$xpath = new DOMXPath($xml);
	$node=$xpath->query("//item[@id='{$id}']")->item(0);
	$node->setAttribute('sortId',f('sortId'));
	$xmlAttrs=str_replace('id,','',$xmlAttrs);
	$xmlAttrsArr=explode(',',$xmlAttrs);
	foreach($xmlAttrsArr as $attrName){
		$node->setAttribute($attrName,f($attrName));
	}
	$xml->save($xmlFile);
	echo alert(array('reUrl'=>'tree.php?dataFlag='.$dataFlag,'target'=>'parent'));
}

if($act=='saveDel') {
	$xml = new DOMDocument();
	$xml->load($xmlFile);
	$xpath = new DOMXPath($xml);
	$node=$xpath->query("//item[@id='{$id}']")->item(0);
	$node->parentNode->removeChild($node);
	$xml->save($xmlFile);
	echo alert(array('reUrl'=>$reUrl));
}

if($act=='updateCache') {
	updateCache($xmlFile,$dataFlag,array('xmlAttrs'=>$xmlAttrs,'arrFileAttrs'=>$arrFileAttrs,'jsonFileAttrs'=>$jsonFileAttrs));
	echo alert(array('reUrl'=>$reUrl));
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
	echo "<div class=\"pageTitle\">$pageTitle</div>";
	$btn=<<<eot
		<div style="height:24px;line-height:24px;width:400px;">
			<a href="?act=add&dataFlag={$dataFlag}" class="aLink" popbox="1" boxTi="添加一级分类">添加一级分类</a>&nbsp;
			<a href="###" class="aLink" onclick="opTreeTbl(0,'dTree')">收缩全部</a>&nbsp;
			<a href="###" class="aLink" onclick="opTreeTbl(1,'dTree')">展开全部</a>&nbsp;
			<a href="?act=updateCache&dataFlag={$dataFlag}" class="aLink">更新缓存</a>
		</div>
eot;
	echo $btn;
	$xmlFile=SITEROOT.'/data/'.$dataFlag.'_data.xml';
	if(file_exists($xmlFile)) {
		$xmlStr=file_get_contents($xmlFile);
	}else{
		$xmlStr='<list />';
	}
	$pArr=array(
		'xmlStr'=>$xmlStr,
		'xslFile'=>$xslFile,
		'xArr'=>array('dataFlag'=>$dataFlag),
		'clear'=>'1'
	);
	echo "<div id='dTree'>".xslt($pArr)."</div>";
}

if($act=='add' || $act=='edit') {
	$exts='';
	if(!isne($attrEx[$dataFlag])) {
		$exts='<tr><th>属性：</th><td>';
		foreach($attrEx[$dataFlag] as $k=>$v){
			$exts.="<label><input type=\"checkbox\" name=\"$k\" value=\"{$v[1]}\"/> {$v[0]}</label>";
		}
		$exts.='</td></tr>';
	}
}

if($act=='add') {
	$formStr=file_get_contents($formFile);
	$cName=f('cName','g');
	$cName=isne($cName) ? '添加根目录分类' : "添加({$cName})子分类";
	$formStr=str_replace(array('action=""','<caption></caption>','{%exts%}'),array("action=\"?act=saveAdd&id={$id}&dataFlag={$dataFlag}\"","<caption>{$cName}</caption>",$exts),$formStr);
	$formStr=preg_replace('/<tr tag="sortIdTr">.+?<\/tr>/ims',"",$formStr);
	echo $formStr;
}

if($act=='edit') {
	$rsArr=array();
	$xml = simplexml_load_file($xmlFile);
	$nodes = $xml->xpath("//item[@id=$id]");
	foreach($nodes as $node){
		$attrs=$node->attributes();
		foreach($attrs as $k=>$v){
			$rsArr=array_merge($rsArr,array((string)$k=>(string)$v));
		}
	}
	$formStr=file_get_contents($formFile);
	$formStr=str_replace(array('{%exts%}'),array($exts),$formStr);
	$formStr=fillForm(null,$rsArr,$formStr);
	$formStr=str_replace(array('action=""','<caption></caption>','{%exts%}'),array("action=\"?act=saveEdit&id={$id}&dataFlag={$dataFlag}\"","<caption>编辑分类</caption>",$exts),$formStr);
	echo $formStr;
}
?>

<script type="text/javascript" defer="1">
$(function(){
	pTreeTbl($("#dTree"));
	iframepop($("a[popBox='1']"));
});
</script>

</body>
</html>

<?php closeConn();?>