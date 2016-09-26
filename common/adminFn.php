<?php
function opTreeCache($xmlFile,$pArr) {
	$xml2File=str_replace('_data.xml','.xml',$xmlFile);
	$arrFile=str_replace('.xml','.arr',$xml2File);
	$jsonFile=str_replace('.xml','.json',$xml2File);

	$xmlAttrs=$pArr['xmlAttrs'];
	if(!isne($xmlAttrs)) {
		$xmlAttrs=','.$xmlAttrs.',';
	}
	$arrFileAttrs=$pArr['arrFileAttrs'];
	if(!isne($arrFileAttrs)) {
		$arrFileAttrs=','.$arrFileAttrs.',';
	}
	$jsonFileAttrs=$pArr['jsonFileAttrs'];
	if(!isne($jsonFileAttrs)) {
		$jsonFileAttrs=','.$jsonFileAttrs.',';
	}

	if(!isne($xmlAttrs)) {
		$pArr=array(
			'xmlFile'=>$xmlFile,
			'xslFile'=>'/xsl/tree_p.xsl',
			'xArr'=>array('attrs'=>$xmlAttrs),
			'clear'=>'1'
		);
		$str=xslt($pArr);
		file_put_contents($xml2File,$str);
	}

	if(!isne($jsonFileAttrs)) {
		$pArr=array(
			'xmlFile'=>$xml2File,
			'xslFile'=>'/xsl/tree_json.xsl',
			'xArr'=>array('attrs'=>$jsonFileAttrs),
			'clear'=>'1'
		);
		$str=xslt($pArr);
		$str=str_replace(array(',}',',]'),array('}',']'),$str);
		file_put_contents($jsonFile,$str);
	}

	if(!isne($arrFileAttrs)) {
		$pArr=array(
			'xmlFile'=>$xml2File,
			'xslFile'=>'/xsl/tree_arr.xsl',
			'xArr'=>array('attrs'=>$arrFileAttrs),
			'clear'=>'1'
		);
		$xmlStr=xslt($pArr);
		$arr=array();
		$xml=new DOMDocument();
		$xml->loadXml($xmlStr);
		$nodes=$xml->getElementsByTagName('item');
		$len=$nodes->length;
		for($i=0;$i<$len;$i++) {
			$tArr=array();
			$node=$nodes->item($i);
			$attrs=$node->attributes;
			foreach($attrs as $v){
				$tArr[$v->name]=$v->value;
			}
			$arr[]=$tArr;
		}
		file_put_contents($arrFile,serialize($arr));
	}
}

function updateJsonVerCache($key,$ver=null) {
	$fileName=SITEROOT.'/data/jsonVerCache.arr';
	if(file_exists($fileName)) {
		$verArr=unserialize(file_get_contents($fileName));
	}else{
		$verArr=array();
	}
	if($ver==null) {
		$ver=time();
	}
	$verArr[$key]=$ver;
	file_put_contents($fileName,serialize($verArr));
}
?>