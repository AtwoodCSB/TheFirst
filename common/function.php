<?php
function cutStr($str, $max_len){
	 $n = 0;
	 $noc = 0;
	 $len = strlen($str);
	 while ($n < $len)
	{
		 $t = ord($str[$n]);
		 if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126))
			{
			 $tn = 1;
			 $n++;
			 $noc++;
			 }
		else if (194 <= $t && $t <= 223)
		{
			 $tn = 2;
			 $n += 2;
			 $noc += 2;
			 }
		else if (224 <= $t && $t < 239)
		{
			 $tn = 3;
			 $n += 3;
			 $noc += 2;
			 }
		else if (240 <= $t && $t <= 247)
		{
			 $tn = 4;
			 $n += 4;
			 $noc += 2;
			 }
		else if (248 <= $t && $t <= 251)
		{
			 $tn = 5;
			 $n += 5;
			 $noc += 2;
			 }
		else if ($t == 252 || $t == 253)
		{
			 $tn = 6;
			 $n += 6;
			 $noc += 2;
			 }
		else{
			 $n++;
			 }

		 if ($noc >= $max_len)
		{
			 break;
			 }

		 }
	 if ($noc > $max_len)
	{
		 $n -= $tn;
		 }
	 $result = substr($str, 0, $n);
	 //$result[] = substr($str, $n, strlen($str));
	 return $result;
}

function left($str, $len) {
	$arr = str_split($str);
	$i = 0;
	foreach ($arr as $chr) {
		if (ord($chr) > 128)
			$add = $add ? 0 : 1;
		$i++;
		if ($i == $len)
			break;
	}
	return substr($str, 0, $len + $add);
}

function sub_s(&$array){
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				sub_s($array[$key]);
			} else {
				$temp = stripslashes($value);
				$replArr=array(chr(0),chr(1),chr(2),chr(3),chr(4),chr(5),chr(6),chr(7),chr(8),chr(11),chr(12),chr(14),chr(15),chr(16),chr(17),chr(18),chr(19),chr(20),chr(21),chr(22),chr(23),chr(24),chr(25),chr(26),chr(27),chr(28),chr(29),chr(30),chr(31));
				$temp=str_replace($replArr, '', $temp);
				$temp=trim($temp);
				$array[$key] =$temp;
			}
		}
	}
}

function htmlEncodePost(&$elArr,$noProcessArr=array()) {
	foreach($elArr as $k=>$v){
		if(is_array($v)) {
			htmlEncodePost($v,$noProcessArr);
		}else{
			if(!isne($v) && strpos($v,'<')!==false && strpos($v,'>')!==false && !in_array($k,$noProcessArr)) {
				$elArr[$k]=htmlspecialchars($v,ENT_COMPAT,'UTF-8');
			}
		}
	}
}

function removeXSS($string, $allowedtags = '', $disabledattributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'))
{
	if ($allowedtags==''){
		$allowedtags='<a><p><br><hr><h1><h2><h3><h4><h5><h6><font><u><i><b><strong><div><span><ol><ul><li><img><table><tr><td><map>';
	}
	if(is_array($string))
	{
		foreach($string as $key => $val) $string[$key] = removeXSS($val,$allowedtags);
	}
	else
	{
		$string = preg_replace('/\s('.implode('|', $disabledattributes).').*?([\s\>])/', '\\2', preg_replace('/<(.*?)>/ie', "'<'.preg_replace(array('/javascript:[^\"\']*/i', '/(".implode('|', $disabledattributes).")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($string, $allowedtags)));
	}
	return $string;
}

function f($k, $var='p',$nulltoempty=0){
	static $isProcess;
	if(!isset($isProcess)) {
		if (get_magic_quotes_gpc()) {
			sub_s($_POST);
			sub_s($_GET);
			sub_s($_COOKIE);
		}
		$isProcess=1;
	}
	switch($var)
	{
	case 'g': $var = &$_GET; break;
	case 'p': $var = &$_POST; break;
	case 'c': $var = &$_COOKIE; break;
	case 'r': $var = &$_REQUEST; break;
	}
	if(isset($var[$k])) {
		return $var[$k];
	}else{
		if($nulltoempty==0) {
			return null;
		}else{
			return '';
		}
	}
}

function isne($str) {
	if($str=='' || is_null($str)) {
		return true;
	}else{
		return false;
	}
}

function pTa($str,$flag=0) {
	if($flag==0) {
		$str=str_replace(' ','&nbsp;',$str);
		$str=nl2br($str);
		$str=str_replace(chr(10),'',$str);
		$str=str_replace(chr(13),'',$str);
	}else{
		$str=str_replace('<br/>',chr(13),$str);
		$str=str_replace('<br />',chr(13),$str);
		$str=str_replace('&nbsp;',' ',$str);
	}
	return $str;
}

function alert($pArr) {
	$msg=$pArr['msg'];
	$reUrl=$pArr['reUrl'];
	$end=$pArr['end']==null ? 1 :0;
	$target=$pArr['target'];
	$reload=$pArr['reload'];
	if($target==null) {
		$target='';
	}else{
		$target.='.';
	}
	if(!isne($end)) $end=1;
	$str='<script type="text/javascript">';
	if(!isne($msg)) $str.="alert('{$msg}');";
	if(!isne($reUrl)) {
		$str.=$target."location.href='{$reUrl}';";
	}
	if(!isne($reload)) {
		$str.=$target."location.reload(true);";
	}
	$str.='</script>';
	echo $str;
	if($end) {
		closeConn();
		die();
	}
}

function arrToSql($vsql) {
	$sql='';
	$asql=$vsql['asql'];
	$type=$vsql['asql']['type'];
	if($type=='insert') {
		$fld=$asql['fld'];
		$fldStr='';
		$pStr='';
		$i=0;
		foreach($fld as $k=>$v){
			if($v=='') $v='@'.$k;
			if($i==0) {
				$fldStr=$k;
				$pStr=$v;
			}else{
				$fldStr.=','.$k;
				$pStr.=','.$v;
			}
			$i++;
		}
		$sql='insert into '.$vsql['asql']['tbl'].'('.$fldStr.') values('.$pStr.')';
		$stmt=$vsql['stmt'];
	}

	if($type=='select') {
		$tbl=$vsql['asql']['tbl'];
		$tblStr='';
		$i=0;
		if(is_array($tbl)) {
			$map=$vsql['asql']['map'];
			foreach($map as $k=>$v){
				$a1=explode(',',$k);
				$a2=explode(',',is_array($v)?$v[0]:$v);
				$linkFlag=is_array($v)?$v[1]:'inner';
				if($i==0)
					$tblStr.=' '.$tbl[$a1[0]].' '.$a1[0].' '.$linkFlag.' join '.$tbl[$a1[1]].' '.$a1[1].' on '.$a2[0].'='.$a2[1];
				else
					$tblStr.=' '.$linkFlag.' join '.$tbl[$a1[1]].' '.$a1[1].' on '.$a2[0].'='.$a2[1];
				$i++;
			}
		}else{
			if($tbl!='') $tblStr=$tbl;
		}

		$order=$vsql['asql']['order'];
		$oStr='';
		$i=0;
		if(is_array($order)) {
			$oStr=' order by ';
			foreach($order as $k=>$v){
				$oFlag=$v==''?'desc':$v;
				if($i!=0) $oStr.=',';
				$oStr.=$k.' '.$oFlag;
				$i++;
			}
		}
	}

	if($type!='insert') {
		$where=$vsql['asql']['where'];
		$stmt=$vsql['stmt'];
		if(is_array($stmt)) {
			foreach($stmt as $k=>$ael){
				if(isne($ael[0])) {
					unset($vsql['stmt'][$k]);
				}
			}
		}
		$stmt=$vsql['stmt'];
		$wStr='';
		$i=0;
		if(is_array($where)) {
			$wStr.=' where ';
			foreach($where as $k=>$v){
				if(is_array($v)) {
					if((!is_null($stmt[$k]) && $type=='select') || ($type!='select')) {
						$searchFlag=is_null($v[0])?'=':$v[0];
						if(is_null($v[1])) {
							$searchp='@'.$k;
						}else{
							$searchp=$v[1];
						}
						$searchLink=is_null($v[2])?'and':$v[2];
						if($i==0)
							$searchLink='';
						else
							$searchLink=' '.$searchLink.' ';
						if($searchFlag=='like') {
							$searchFlag=" $searchFlag ";
						}
						$wStr.=$searchLink.$k.$searchFlag.$searchp;
						$i++;
					}
					if($v[0]=='in' && !isne($v[1])) {
						$searchLink=is_null($v[2])?' and':$v[2];
						$wStr.=$searchLink.' '.$k.' in ('.$v[1].')';
						$i++;
					}
				}else{
					$wStr.=$v;
					if(stripos($v,'(')) $i=0;
				}
			}
			$wStr=str_replace(' and ()','',$wStr);
			$wStr=str_replace(' or ()','',$wStr);
			$wStr=str_replace('() and','',$wStr);
			$wStr=str_replace('() or','',$wStr);
			$wStr=str_replace('where  and','where',$wStr);
			if($wStr==' where ') $wStr='';
		}
	}
	if($type=='select') {
		$sp=$vsql['sp'];
		$limit=$vsql['asql']['limit'];
		if(is_null($sp)) {
			$sql='select '.$vsql['asql']['fld'].' from '.$tblStr.$wStr.$oStr;
			if(!is_null($limit)) {
				$sql.=" limit {$limit}";
			}
		}else{
			$key=is_null($sp['key'])?'id':$sp['key'];
			$pageSize=is_null($sp['size'])?10:$sp['size'];

			$spmap=$sp['map'];
			if(is_array($spmap)) {
				$tblSpStr="";
				foreach($spmap as $k=>$v){
					$a1=explode(',',$k);
					$a2=explode(',',is_array($v)?$v[0]:$v);
					$linkFlag=is_array($v)?$v[1]:'inner';
					if($i==0)
						$tblSpStr.=' '.$tbl[$a1[0]].' '.$a1[0].' '.$linkFlag.' join '.$tbl[$a1[1]].' '.$a1[1].' on '.$a2[0].'='.$a2[1];
					else
						$tblSpStr.=' '.$linkFlag.' join '.$tbl[$a1[1]].' '.$a1[1].' on '.$a2[0].'='.$a2[1];
					$i++;
				}
			}

			$sptbl=$sp['tbl'];
			if(!isne($sptbl)) {
				$tblSpStr=$sptbl;
			}

			if(isne($spmap) && isne($sptbl)) {
				$tblSpStr=$tblStr;
			}

			$sporder=$sp['order'];
			$oStr='';
			$i=0;
			if(is_array($sporder)) {
				$oStr=' order by ';
				foreach($sporder as $k=>$v){
					$oFlag=$v==''?'desc':$v;
					if($i!=0) $oStr.=',';
					$oStr.=$k.' '.$oFlag;
					$i++;
				}
			}
			$sql='select '.$key.' from '.$tblSpStr.$wStr.$oStr;
			//var_dump($sql);die();
			$sqlArr=array(
				"sql"=>$sql,
				"stmt"=>$stmt
			);
			$idArr=exeCmd($sqlArr,"array");
			$idList='';
			if(is_array($idArr)) {
				$totalRs=count($idArr);
				if($totalRs<=$pageSize) {
					foreach($idArr as $val){
						foreach($val as $k=>$v){
							if($idList=='')
								$idList=$v;
							else
								$idList.=','.$v;
						}
					}
				}else{
					$sp['curPage']=isne($sp['curPage']) ? f('p','g') : $sp['curPage'];
					$curPage=is_numeric($sp['curPage'])?(int)$sp['curPage']:1;
					$sIndex=$pageSize*($curPage-1);
					$eIndex=$sIndex+$pageSize;
					if($eIndex>$totalRs) $eIndex=$totalRs;
					for($i=$sIndex;$i<$eIndex;$i++) {
						foreach($idArr[$i] as $k=>$v){
							if($idList=='')
								$idList=$v;
							else
								$idList.=','.$v;
						}
					}
				}
			}
			if($idList!=''){
				$sql='select '.$vsql['asql']['fld'].' from '.$tblStr.' where '.$key.' in('.$idList.')'.$oStr;
			}else{
				$sql='';
			}
			return array('sql'=>$sql,'rsCount'=>$totalRs);
		}
	}

	if($type=='update') {
		$fld=$asql['fld'];
		$fldStr='';
		$i=0;
		foreach($fld as $k=>$v){
			if($v=='') $v='@'.$k;
			if($i==0) {
				$fldStr=$k.'='.$v;
			}else{
				$fldStr.=','.$k.'='.$v;
			}
			$i++;
		}
		$sql='update '.$vsql['asql']['tbl'].' set '.$fldStr.$wStr;
	}

	if($type=='delete') {
		$sql='delete from '.$vsql['asql']['tbl'].$wStr;
	}

	$sqlArr=array(
		"sql"=>$sql,
		"stmt"=>$stmt
	);
	return $sqlArr;
}


function openConn() {
	global $g_conn,$g_db;
	if(!isset($g_conn)){
		$g_conn = new mysqli($g_db['host'], $g_db['user'], $g_db['pwd'], $g_db['dbName']);
		if (mysqli_connect_errno()) {
			printf('Connect failed: %s\n', mysqli_connect_error());
			exit();
		}
		$g_conn->query('SET character_set_connection='.$g_db['charset'].', character_set_results='.$g_db['charset'].', character_set_client=binary');
	}
}

function closeConn($dieFlag=1) {
	global $g_conn;
	if(isset($g_conn)) $g_conn->close();
	if($dieFlag) die();
}

function addDbTblPre($sql) {
	global $g_db;
	$dbPre=$g_db['dbPre'];
	if(isne($dbPre)) {
		return $sql;
	}
	$sql=trim($sql);
	$tArr=explode(' ',$sql);
	$flag=$tArr[0];
	if($flag=='select') {
		$sql=preg_replace('/ from[ ]+(\w+)/'," from {$dbPre}\$1",$sql);
		$sql=preg_replace('/ join[ ]+(\w+)/'," join {$dbPre}\$1",$sql);
	}
	if($flag=='insert') {
		$sql=preg_replace('/ into[ ]+(\w+)/'," into {$dbPre}\$1",$sql);
	}
	if($flag=='update') {
		$sql=preg_replace('/update[ ]+(\w+)/'," update {$dbPre}\$1",$sql);
	}
	if($flag=='delete') {
		$sql=preg_replace('/ from[ ]+(\w+)/'," from {$dbPre}\$1",$sql);
	}
	return $sql;
}

function opSqlIn($str,$type='int') {
	if($type=='int') {
		$temp=preg_replace('/[^0-9,]/','',$str);
	}
	if($type=='str') {
		$temp=preg_replace('/[^\w,]/','',$str);
	}
	$temp=preg_replace(array('/^,+/','/,+$/','/,+/'),array('','',','),$temp);
	if($temp!='' && $type=='str') {
		$temp='\''.str_replace(',','\',\'',$temp).'\'';
	}
	return $temp;
}

function rsArrFn($parr) {
	if(!is_array($parr)) {
		return array();
	}
	$arr=array();
	foreach($parr as $row){
		foreach($row as $k=>$v){
			$arr[]=$v;
		}
	}
	return $arr;
}

/*
$returnFlag value,string,array
*/
function exeCmd($vsql,$returnFlag='',$arrCfg=null,$arrFld=null) {
	if($vsql=='') {
		return null;
	}
	global $g_conn;
	$rsCount=null;
	if(!isset($GLOBALS['g_conn'])) openConn();
	if(is_array($vsql)) {
		$asql=$vsql['asql'];
		if(!is_null($asql)) {
			$vatsql=arrToSql($vsql);
			if(is_array($vatsql)) {
				$sql=$vatsql['sql'];
				$stmtArr=$vatsql['stmt'];
				$rsCount=$vatsql['rsCount'];
			}else{
				$sql=$vatsql;
			}
		}else{
			$sql=$vsql['sql'];
			$stmtArr=$vsql['stmt'];
		}
	}else{
		$sql=$vsql;
	}

	if(isne($sql)) {
		if($returnFlag=='array') {
			return array();
		}
		return null;
	}

	if(is_array($stmtArr)) {
		$sql=preg_replace('/@[^ ,)]+/','?',$sql);
		$sql=addDbTblPre($sql);
		$stmt = $g_conn->prepare($sql);
		if(!$stmt) {
			var_dump('prepareErr:',$sql,$g_conn->error,$g_conn->errno);
			die();
		}
		$stmtTypeList='';
		foreach($stmtArr as $k=>$v){
			$varType=is_array($v)?$v[1]:'s';
			$stmtTypeList.=$varType;
		}
		$paraArr=array();
		$paraArr[]=&$stmt;
		$paraArr[]=$stmtTypeList;
		foreach($stmtArr as $k=>$v){
			$paraArr[]=is_array($v)?$v[0]:$v;
		}
		//var_dump($sql,$paraArr);
		call_user_func_array(mysqli_stmt_bind_param, $paraArr);
		$stmt->execute();
		if($stmt->errno!=0) {
			var_dump('stmtErr:',$sql,$paraArr,$stmt->error,$stmt->errno);
			die();
		}
		$tempArr=explode(' ',$sql);
		$firstWord=strtolower($tempArr[0]);
		if($firstWord=='insert' and $returnFlag=='value') {
			return $g_conn->insert_id;
		}
		if($returnFlag!='' && $firstWord=="select") {
			$meta=mysqli_stmt_result_metadata($stmt);
			$fieldMeta = mysqli_fetch_fields($meta);
			$fldCount = mysqli_num_fields($meta);
			$fields=array();
			for($i=0; $i < $fldCount; $i++){
				$fields[$i]= $fieldMeta[$i]->name;
			}
			$result = array();
			$arg = array($stmt);
			for ($i=0; $i < $fldCount; $i++) {
				$result[$i] = '';
				$arg[$i+1] = &$result[$i];
			}
			call_user_func_array('mysqli_stmt_bind_result',$arg);
			$rsArr=array();
			$rowArr=array();
			$i=0;
			while(mysqli_stmt_fetch($stmt)){
				for($i=0; $i < $fldCount; $i++){
					$rowArr[$fields[$i]]= $result[$i];
				}
				$rsArr[]=$rowArr;
			}

			mysqli_stmt_close($stmt);
			if($returnFlag=='array'){
				if(count($rsArr)==0) {
					return array();
				}
				if(is_array($arrCfg)) {
					$rsArr=sqlArrCfg($rsArr,$arrCfg);
				}
				if(!isne($arrFld)) {
					$rsArr=opArrFld($rsArr,$arrFld);
				}
				return $rsArr;
			}

			if($returnFlag=="value") {
				if(count($rsArr)>0) {
					foreach($rsArr[0] as $k=>$v){
						return $v;
					}
				}else{
					return null;
				}
			}
			if($returnFlag=="string") {
				$str='';
				foreach($rsArr as $val){
					foreach($val as $k=>$v){

						if($str=='')
							$str=$v;
						else
							$str.=','.$v;
					}
				}
				return $str;
			}
		}
	}else{
		$sql=addDbTblPre($sql);
		$tempArr=explode(' ',$sql);
		$firstWord=strtolower($tempArr[0]);
		$rs=$g_conn->query($sql);
		if ($g_conn->errno>0){
			var_dump('sqlqueryErr:',$sql,$g_conn->error);
			die();
		}

		if($firstWord=='insert' and $returnFlag=='value') {
			return $g_conn->insert_id;
		}

		if($returnFlag=='value'){
			if(!$rs) return null;
			$row = $rs->fetch_array(MYSQLI_NUM);
			if(is_null($row)) return null;
			return $row[0];
		}
		if($returnFlag=='string') {
			if(!$rs) return null;
			$str='';
			while($row = $rs->fetch_array(MYSQLI_NUM)){
				foreach ($row as $k=>$v){
					if($str=='')
						$str=$v;
					else
						$str.=','.$v;
				}
			}
			return $str;
		}
		if($returnFlag=='array') {
			$rsArr=array();
			if($rs) {
				while($row = $rs->fetch_array(MYSQLI_ASSOC)){
					$rsArr[]=$row;
				}
				if(is_array($arrCfg)) {
					$rsArr=sqlArrCfg($rsArr,$arrCfg);
				}
				if(!isne($arrFld)) {
					$rsArr=opArrFld($rsArr,$arrFld);
				}
			}
			if(isne($rsCount)) {
				return $rsArr;
			}else{
				return array('rs'=>$rsArr,'rsCount'=>$rsCount);
			}
		}
	}
}

/*
array('userRole'=>array(fnName,fnParr,newAttrName))
*/
function sqlArrCfg($arr,$arrCfg=null) {
	if(is_array($arrCfg) && count($arr)>0) {
		$arrLen=count($arr);
		foreach($arrCfg as $attrName=>$fn){
			if(is_array($fn)) {
				$fnName=$fn[0];
				$fnParr=$fn[1];
				$newAttrName=$fn[2];
			}else{
				$fnName=$fn;
				$newAttrName=$attrName;
			}
			for($i=0;$i<$arrLen;$i++) {
				$fnParr[]=$arr[$i][$attrName];
				$arr[$i][$newAttrName]=call_user_func_array($fnName,$fnParr);
			}
		}
	}
	return $arr;
}

function opArrFld($arr,$fldArr) {
	if(count($fldArr)>0 && count($arr)>0) {
		$fldNum=count($fldArr);
		$arrLen=count($arr);
		for($i=0;$i<$arrLen;$i++) {
			for($j=0;$j<$fldNum;$j++) {
				$tVal=$arr[$i][$fldArr[$j]];
				if(!isne($tVal)) {
					$arr[$i][$fldArr[$j]]=unserialize($tVal);
				}else{
					$arr[$i][$fldArr[$j]]='';
				}
			}
		}
	}
	return $arr;
}

function mulArrToXml($arr,$fldName,$isItemNode=0) {
	$arrLen=count($arr);
	if($arrLen>0) {
		$xmlStr='';
		$arrElType=getType($arr[0]);
		if(!is_null($arrElType)) {
			$xmlStr="<$fldName>";
			for($i=0;$i<$arrLen;$i++) {
				$xmlStr.='<item '.mulArrToXml($arr[$i],$fldName,1);
				$xmlStr.=' />';
			}
			$xmlStr.="</$fldName>";
		}
		if($arrElType=='NULL') {
			$xmlStr='';
			if($isItemNode==0) {
				$xmlStr.="<$fldName";
			}
			foreach($arr as $k=>$v){
				$xmlStr.=' '.$k.'="'.htmlspecialchars($v,ENT_COMPAT,'UTF-8').'"';
			}
			if($isItemNode==0) {
				$xmlStr.="/>";
			}
		}
	}
	return $xmlStr;
}

function rsArrToXml($rsArr,$xmlCfg=array()) {
	$rootName=$xmlCfg['rootName'];
	$rowName=$xmlCfg['rowName'];
	if (is_null($rootName)) $rootName="data";
	if (is_null($rowName)) $rowName="row";
	if(!is_array($rsArr)) {
		return '<'.$rootName.'></'.$rootName.'>';
	}
	$xml="<$rootName>";
	$arrFld=array();
	foreach($rsArr as $v){
		$xml.='<row';
		foreach($v as $attrName=>$attrVal){
			if(is_array($attrVal)) {
				$arrFld[]=$attrName;
			}else{
				$xml.=' '.$attrName.'="'.htmlspecialchars($attrVal,ENT_COMPAT,'UTF-8').'"';
			}
		}
		$xml.='>';
		foreach($arrFld as $fldName){
			$arrXml=mulArrToXml($v[$fldName],$fldName);
			$xml.=$arrXml;
		}
		$xml.='</row>';
	}
	$xml.='</'.$rootName.'>';
	return $xml;
}

function sqlToXml($sql,$arrCfg=null,$arrFld=null,$xmlCfg=array()) {
	$rsArr=exeCmd($sql,'array',$arrCfg,$arrFld);
	if(is_null($rsArr) || isne($rsArr['rsCount'])) {
		$xmlStr=rsArrToXml($rsArr,$xmlCfg);
		if(is_array($sql) && !is_null($sql['sp'])) {
			return array('xmlStr'=>$xmlStr,'rsCount'=>0);
		}
		return $xmlStr;
	}else{
		$xmlStr=rsArrToXml($rsArr['rs'],$xmlCfg);
		return array('xmlStr'=>$xmlStr,'rsCount'=>$rsArr['rsCount']);
	}
}

function xslt($pArr) {
	$xmlFile=$pArr['xmlFile'];
	$htmlFile=$pArr['htmlFile'];
	$xmlStr=$pArr['xmlStr'];
	$phpFn=$pArr['phpFn'];
	$xml=&$pArr['xmlObj'];
	$xml=&$pArr['htmlObj'];
	if(is_null($xml)) {
		$xmlFile=$pArr['xmlFile'];
		$xmlStr=$pArr['xmlStr'];
		$xml = new DOMDocument;
		$xml->preserveWhiteSpace = false;
		if(!is_null($xmlFile)) {
			if(subStr($xmlFile,0,1)=='/') {
				$xmlFile=SITEROOT.$xmlFile;
			}
			$xml->load($xmlFile);
		}
		if(!is_null($htmlFile)){
			if(subStr($htmlFile,0,1)=='/') {
				$htmlFile=SITEROOT.$htmlFile;
			}
			$xml->loadhtmlFile($htmlFile);
		}
		if(!isne($xmlStr)) $xml->loadXml($xmlStr);
	}
	$xslFile=$pArr['xslFile'];
	$xslStr=$pArr['xslStr'];
	$xsl=&$pArr['xslObj'];

	if(is_null($xsl)) {
		$xslFile=$pArr['xslFile'];
		$xslStr=$pArr['xslStr'];
		$xsl = new DOMDocument;
		if(!is_null($xslFile)) {
			if($xslFile[0]=='/') {
				$xslFile=SITEROOT.$xslFile;
			}
			$xsl->load($xslFile);
		}
		if(!is_null($xslStr)) $xsl->loadXml($xslStr);
	}
	$proc = new XSLTProcessor;
	if(!is_null($phpFn)) {
		$proc->registerPHPFunctions();
	}
	$proc->importStyleSheet($xsl);
	$xArr=$pArr['xArr'];
	if(is_array($xArr)) {
		foreach($xArr as $k=>$v){
			$proc->setParameter('',$k,$v);
		}
	}
	$saveFile=$pArr['saveFile'];
	$clear=$pArr['clear'];
	if(is_null($saveFile) || !is_null($clear)){
		$xmlStr=$proc->transformToXml($xml);
		if(!is_null($clear)) {
			$xmlStr=str_replace(array(chr(9),chr(10),chr(13)),'',$xmlStr);
		}
		if(is_null($saveFile)){
			return $xmlStr;
		}else{
			return file_put_contents(SITEROOT.$saveFile,$xmlStr);
		}
	}
	if(!is_null($saveFile) || is_null($clear)){
		return $proc->transformToURI($xml,SITEROOT.$saveFile);
	}
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	//md5($g_siteKey.$_SERVER['HTTP_USER_AGENT'])
	$key = md5($key!='' ? $key : md5($g_siteKey));
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function s($u,$isAdmin=0,$noReurl=0) {
	global $g_session,$g_adminSession,$g_sessionFld,$g_adminSessionFld;
	if($isAdmin==0) {
		if(!isset($g_session)) {
			$sessionName=$g_cookie['pre'].'session';
			$g_session=array();
			$session=f($sessionName,'c');
			if(isne($session)) return null;
			$user=authcode($session);
			//var_dump($user,$session);
			if(isne($user)) return null;
			$temp=explode('+',$user);
			$userName=$temp[0];
			$pArr=array(
				'sql'=>'select '.$g_sessionFld.' from session where userName=@userName and userRole>0',
				'stmt'=>array(
					'userName'=>$userName
				)
			);
			$uArr=exeCmd($pArr,'array');
			if(count($uArr)>0) {
				$g_session=$uArr[0];
			}else{
				if($noReurl==0) {
					echo alert(array('reUrl'=>'/logout.php'));
				}
			}
		}
		return $g_session[$u];
	}else{
		if(!isset($g_adminSession)) {
			$sessionName=$g_cookie['pre'].'adminSession';
			$g_adminSession=array();
			$adminSession=f($sessionName,'c');
			if(isne($adminSession)) return null;
			$user=authcode($adminSession);
			if(isne($user)) return null;
			$temp=explode('+',$user);
			$userName=$temp[0];
			$pArr=array(
				'sql'=>'select '.$g_adminSessionFld.' from session where userName=@userName and userRole=0',
				'stmt'=>array(
					'userName'=>$userName
				)
			);
			$uArr=exeCmd($pArr,'array');
			if(count($uArr)>0) {
				$g_adminSession=$uArr[0];
			}else{
				if($noReurl==0) {
					echo alert(array('reUrl'=>'/admin/logout.php'));
				}
			}
		}
		return $g_adminSession[$u];
	}
}

function loginSession($userName,$pwd,$userRole) {
	if($userRole>0) {
		$sessionName=$g_cookie['pre'].'session';
	}else{
		$sessionName=$g_cookie['pre'].'adminSession';
	}
	$session=authcode($userName.'+'.$pwd,'');
	setcookie($sessionName,$session);

	$pArr=array(
		'sql'=>'delete from session where userName=@userName and userRole=@userRole',
		'stmt'=>array(
			'userName'=>$userName,
			'userRole'=>$userRole
		)
	);
	exeCmd($pArr);

	$pArr=array(
		'sql'=>'insert into session(userName,userRole,dt) values(@userName,@userRole,@dt)',
		'stmt'=>array(
			'userName'=>$userName,
			'userRole'=>$userRole,
			'dt'=>time()
		)
	);
	exeCmd($pArr);
}

function logoutSession($userName,$isAdmin=0) {
	if($isAdmin==0) {
		$sessionName=$g_cookie['pre'].'session';
		$wStr='userRole>0';
	}else{
		$sessionName=$g_cookie['pre'].'adminSession';
		$wStr='userRole=0';
	}
	$session=f($sessionName,'c');
	$user=authcode($session);
	$temp=explode('+',$user);
	$userName=$temp[0];
	$pArr=array(
		'sql'=>'delete from session where userName=@userName and '.$wStr,
		'stmt'=>array(
			'userName'=>$userName
		)
	);
	exeCmd($pArr);

	$timeFlag=time()-60*60*40;
	exeCmd('delete from session where dt<'.$timeFlag);

	setcookie($sessionName,'');
}

function xmp($str,$dieFlag=0) {
	$str='<xmp>'.var_dump($str).'</xmp>';
	echo $str;
	if(dieFlag==1) die();
}

function mkdirs($dir){
	if(!is_dir($dir)){
		if(!mkdirs(dirname($dir))){
			return false;
		}
		if(!mkdir($dir,0777)){
			return false;
		}
	}
	return true;
}

function rmdirs($dir){
	$d = dir($dir);
	while (false !== ($child = $d->read()))
	{
		if ($child != '.' && $child != '..')
		{
			if (is_dir($dir . '/' . $child))
				rmdirs($dir . '/' . $child);
			else unlink($dir . '/' . $child);
		}
	}
	$d->close();
	rmdir($dir);
}

function delFile($pArr) {
	if($pArr==null) {
		return false;
	}
	if(!is_array($pArr)) {
		$pArr=array($pArr);
	}
	foreach($pArr as $file){
		if($file[0]=="/") {
			$file=SITEROOT.$file;
		}
		if(file_exists($file)) {
			unlink($file);
		}
	}
	return true;
}

function qstringToArr($str=null) {
	if($str==null) {
		$qs=$_SERVER['QUERY_STRING'];
	}
	$urlArr=array();
	$qsArr=explode('&',$qs);
	foreach($qsArr as $v){
		$tArr=explode('=',$v);
		if($tArr[1]!='' and $tArr[0]!=$pVarName) {
			$urlArr=array_merge($urlArr,array($tArr[0]=>$tArr[1]));
		}
	}
	return $urlArr;
}

function delqsItem($delqsName,$qstr=null) {
	$urlArr=qstringToArr($qstr);
	unset($urlArr[$delqsName]);
	$url=http_build_query($urlArr);
	if($url=='') {
		$url='?';
	}else{
		$url.='&';
	}
	return $url;
}

function spBar($pArr) {
	$rsCount=$pArr['rsCount']==null ? 0 :$pArr['rsCount'];
	$pageSize=$pArr['pageSize']==null ? 10 : $pArr['pageSize'];
	$curPage=$pArr['curPage'];
	$style=$pArr['style']==null ? 1 :$pArr['style'];
	$curUrl=$pArr['curUrl'];
	$pageUbound=$pArr['pageUbound'];
	$pVarName=$pArr['pVarName']==null ? 'p' : $pArr['pVarName'];

	if($pageUbound==null && $style==1) {
		$pageUbound=50;
	}else{
		$pageUbound=5;
	}

	$pageNum=ceil($rsCount / $pageSize);
	if ($curPage==null) $curPage=f($pVarName,"g");
	if (!is_numeric($curPage)) $curPage=1;
	$curPage=(int)$curPage;
	if ($curPage<1) $curPage=1;
	if ($curPage>$pageNum) $curPage=$pageNum;

	if($curUrl==null) {
		$qs=$_SERVER['QUERY_STRING'];
		$path='';
	}else{
		$tArr=parse_url($curUrl);
		$qs=$tArr['query'];
		$path=$tArr['path'];
	}

	$appendUrl='';
	if($qs!='') {
		$urlArr=array();
		$qsArr=explode('&',$qs);
		foreach($qsArr as $v){
			$tArr=explode('=',$v);
			if($tArr[1]!='' and $tArr[0]!=$pVarName) {
				$urlArr=array_merge($urlArr,array($tArr[0]=>$tArr[1]));
			}
		}
	$appendUrl=http_build_query($urlArr);
	}

	$appendUrl= $appendUrl!='' ? '?'.$appendUrl.'&' : $appendUrl;
	$appendUrl=$path.$appendUrl;
	if(isne($appendUrl)) {
		$appendUrl='?';
	}

	$start=$curPage-$pageUbound;
	$end=$curPage+$pageUbound;
	if($start<0) {
		$end+=$start*-1;
	}
	if($end>$pageNum) {
		$start-=($end-$pageNum);
	}
	if($start<1) {
		$start=1;
	}
	if($end>$pageNum) {
		$end=$pageNum;
	}
	$htmlBar='<ul class="g_spBar">';
	if($style==1) {
		$htmlBar.="<li class=\"item rsCount\">共有{$rsCount}条，每页{$pageSize}条</li>";
		$cssArr=array('','','','');
		$txtArr=array('首页','上一页','下一页','尾页');
		$urlArr=array($appendUrl.$pVarName.'=1',$appendUrl.$pVarName.'='.($curPage-1),$appendUrl.$pVarName.'='.($curPage+1),$appendUrl.$pVarName.'='.$pageNum);
		if($curPage==1 || $rsCount==0) {
			$cssArr[0]=$cssArr[1]=' class="curPage"';
			$urlArr[0]=$urlArr[1]='javascript:void(0);';
		}
		if($curPage==$pageNum) {
			$cssArr[2]=$cssArr[3]=' class="curPage"';
			$urlArr[2]=$urlArr[3]='javascript:void(0);';
		}
		$opt='';
		for($i=0;$i<count($cssArr);$i++) {
			$htmlBar.="<li class=\"item\"><a href=\"{$urlArr[$i]}\"{$cssArr[$i]}\">{$txtArr[$i]}</a></li>";
		}
		for($i=$start;$i<=$end;$i++) {
			$selected='';
			if($curPage==$i) $selected=' selected="1"';
			if($i>=$curPage-$pageUbound || $i<=$curPage+$pageUbound) {
				$opt.="<option value=\"{$i}\"{$selected}>{$i}</option>";
			}
		}
		if($opt!='') {
			$htmlBar.='<li class="item">转到第 <select onchange="location.href=\''.$appendUrl.$pVarName.'='.'\'+this.value">'.$opt.'</select> 页</li>';
		}
	}
	if($style==2) {
		$htmlList='';
		if($start!=1 && $rsCount!=1) {
			$htmlList.="<li class=\"item\"><a href=\"{$appendUrl}{$pVarName}=1\">首页</a></li>";
		}
		if($curPage!=1 && $rsCount!=1) {
			$p=$curPage-1;
			$htmlList.="<li class=\"item\"><a href=\"{$appendUrl}{$pVarName}={$p}\">上一页</a></li>";
		}
		for($i=$start;$i<=$end;$i++) {
			if($curPage==$i){
				$className=' class="curPage"';
				$curLink='javascript:void(0);';
			}else{
				$className='';
				$curLink=$appendUrl.$pVarName.'='.$i;
			}
			$htmlList.="<li class=\"item\"><a href=\"{$curLink}\"{$className}>{$i}</a></li>";
		}
		if($curPage!=$end) {
			$p=$curPage+1;
			$htmlList.="<li class=\"item\"><a href=\"{$appendUrl}{$pVarName}={$p}\">下一页</a></li>";
		}
		if($curPage!=$pageNum) {
			$htmlList.="<li class=\"item\"><a href=\"{$appendUrl}{$pVarName}={$pageNum}\">尾页</a></li>";
		}
		$htmlBar.=$htmlList;
	}
	$htmlBar.='<li class="clear"></li></ul>';
	return $htmlBar;
}

function createCheckCode($len){
	$cStr='ABCDEFGHKMNPQRSTUVWXYZ23456789abcdefghkmnpqrstuvwxyz';
	$chkCode='';
	for($i=0;$i<$len;$i++) {
		$chkCode.=$cStr[rand(0,strlen($cStr)-1)];
	}
	return $chkCode;
}

function getFormHash() {
	return time()+rand(1000,9999);
}

function getIp() {
	$ip="";
	if(isset($_SERVER['HTTP_CLIENT_IP'])){
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function getIpLocal($ip,$flag=0) {
	require_once(SITEROOT . '/common/ip.php');
	$QQWry=new QQWry;
	$QQWry->QQWry($ip);
	$country=$QQWry->Country;
	$local=$QQWry->Local;
	if($flag==0) {
		return $country.$local;
	}
	if($flag==1) {
		return $country;
	}
	if($flag==2) {
		return $local;
	}
}

function setChkCode() {
	global $g_siteKey;
	require_once(SITEROOT . '/common/chkCode.php');
	$codeStr=createCheckCode(4);
	$encodeStr=md5(strtolower($codeStr).$_SERVER['HTTP_USER_AGENT'].getIp().$g_siteKey);
	//echo $encodeStr;
	setcookie("chkCode",$encodeStr);
	$chkCode = new chkCodeCls();
	$chkCode->mCheckCode=$codeStr;
	$chkCode->OutCheckImage();
}

function chkSubmitChkCode($input) {
	global $g_siteKey;
	if(isne($input)) {
		return false;
	}
	$chkCodeCache='';
	require_once(SITEROOT . '/common/secache.php');
	$cache = new secache;
	$cache->workat(SITEROOT.'/cache/cachedata');
	if($cache->fetch(md5('chkCode'),$value)){
		$chkCodeCache=$value;
		$cacheArr=explode(',',$chkCodeCache);
		if(in_array($input,$cacheArr)) {
			return false;
		}
		if(count($cacheArr)>3000) {
			$cacheArr=array_slice($cacheArr,2000,3000);
			$chkCodeCache=join(',',$cacheArr);
		}
	}
	$curEncodeStr=$_COOKIE['chkCode'];
	if(md5(strtolower($input).$_SERVER['HTTP_USER_AGENT'].getIp().$g_siteKey)==$curEncodeStr) {
		if($chkCodeCache=='') {
			$chkCodeCache=$curEncodeStr;
		}else{
			$chkCodeCache.=$curEncodeStr;
		}
		$cache->store(md5('chkCode'),$chkCodeCache);
		return true;
	}
	return false;
}

function getArrTree($arr,$id) {
	for($i=0;$i<count($arr);$i++) {
		if($arr[$i]['id']==$id) {
			return $arr[$i];
		}
	}
}

function jsonVer($formCfgFile,$formId=null,$dataBind=null) {
	if(is_null($formId)) {
		$formCfg=unserialize(file_get_contents($formCfgFile));
		if($formCfg['dataBind']==null) {
			return;
		}
		$formId=$formCfg['formId'];
		$dataBind=$formCfg['dataBind'];
	}
	$verArr=unserialize(file_get_contents(SITEDATA.'/jsonVerCache.arr'));
	$tArr=array();
	foreach($dataBind as $el=>$v){
		$tArr=array_merge($tArr,array($el=>$verArr[$v['src']]));
	}
	return '<script type="text/javascript">var '. $formId .'_verJSON='. json_encode($tArr) .';</script>';
}

function uploadLog($formHash,$fileName) {
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'insert',
			'tbl' => 'uploadlog',
			'fld' =>
			array (
				'formHash' => '',
				'fileName' => '',
				'ediTime' => '',
			),
		),
		'stmt' =>
		array (
			'formHash' => $formHash,
			'fileName' => $fileName,
			'ediTime' => time(),
		),
	);
	exeCmd($sqlArr);
}

function utf8_to_pinyin($string)
{
	$utf8_to_pinyin_key = explode('|', 'a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo');
	$utf8_to_pinyin_value = explode('|', '-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274|-10270|-10262|-10260|-10256|-10254');
  static $_data;
  if ($_data === null)
  {
    $_data = array_combine($utf8_to_pinyin_key, $utf8_to_pinyin_value);
    arsort($_data);
    reset($_data);
  }
  $string = iconv('utf-8', 'gbk', $string);
  $ret = '';
  for($i = 0; $i < strlen($string); $i++)
  {
    $x = ord(substr($string, $i, 1));
    if ($x > 160)
    {
      $x = ($x << 8) + ord(substr($string, ++$i, 1)) - 65536;
    }
    $ret .= _pinyin($x, $_data);
  }
  return $ret;
}

function _pinyin($num, & $_data)
{
  if ($num > 0 && $num < 160)
    return chr($num);
  elseif ($num < -20319 || $num > -10247)
    return '';
  else
  {
    foreach ($_data as $k=>$v)
    {
      if ($v <= $num)
        break;
    }
    return $k;
  }
}

function pageHead() {
	global $g_siteInfo,$g_head;
	$title='';
	if($g_head['title']!=null) {
		$title=$g_head['title'];
	}
	$title.=$g_siteInfo['siteTitle'];
	$key=$g_siteInfo['siteKey'];
	$desc=$g_siteInfo['siteDesc'];
	if($g_head['key']!=null) {
		$key=$g_head['key'];
	}
	if($g_head['desc']!=null) {
		$desc=$g_head['desc'];
	}
	$headHtml="<title>$title</title>\n";
	$headHtml.="<meta name=\"keywords\" content=\"$key\" />\n";
	$headHtml.="<meta name=\"description\" content=\"$desc\" />\n";
	if($g_head['css']!=null) {
		foreach($g_head['css'] as $v){
			$headHtml.="<link rel=\"stylesheet\" href=\"{$v}\" type=\"text/css\" />\n";
		}
	}
	if($g_head['js']!=null) {
		foreach($g_head['js'] as $v){
			$headHtml.="<script type=\"text/javascript\" src=\"{$v}\"></script>\n";
		}
	}
	return $headHtml;
}

function chklogin($isAdmin=0,$userRole=null,$reUrl=1) {
	if($isAdmin==1) {
		$userName=s('userName',1);
		if(isne($userName)) {
			if($reUrl==1) {
				echo alert(array('reUrl'=>'login.php'));
				die();
			}
			return false;
		}
	}else{
		$userName=s('userName');
		if(isne($userName)) {
			if($reUrl==1) {
				echo alert(array('reUrl'=>'login.php'));
				die();
			}
			return false;
		}
		if(!isne($userRole)) {
			$curUserRole=(int)s('userRole',0);
			if($curUserRole!=$userRole) {
			if($reUrl==1) {
				echo alert(array('reUrl'=>'login.php'));
				die();
			}
			return false;
			}
		}
	}
	return true;
}

function unicode_decode($name){
	$pattern = '/([\w,{,},",:]+)|(\\\u([\w]{4}))/i';
	preg_match_all($pattern, $name, $matches);
	if (!empty($matches)){
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++){
					$str = $matches[0][$j];
					if (strpos($str, '\\u') === 0){
							$code = base_convert(substr($str, 2, 2), 16, 10);
							$code2 = base_convert(substr($str, 4), 16, 10);
							$c = chr($code) . chr($code2);
							$c = iconv('UCS-2', 'UTF-8', $c);
							$name .= $c;
					}else{
							$name .= $str;
					}
			}
	}
	return $name;
}

function json_encode2($str) {
	$str=json_encode($str);
	return unicode_decode($str);
}

function tabsHtml($arr,$pName,$ulClass=null) {
	$ulClass=isne($ulClass) ? '': ' class="'.$ulClass.'"';
	$html="<ul$ulClass>";
	$curTabTxt=$arr[f($pName,'g')];
	$preUrl=delqsItem('flag');
	foreach($arr as $k=>$v){
		$curClass=' class="hli"';
		if($curTabTxt==$v) {
			$curClass=' class="hli curItem"';
		}
		$html.="<li$curClass><a href='$preUrl$pName=$k'><span>$v</span></a></li>";
	}
	$html.='<li class="clear"></li></ul>';
	return $html;
}

function getCNameByArr($arr,$cid,$arrFile=null) {
	if(isne($cid)) {
		return '';
	}
	if(!isne($arrFile) and is_null($arr)) {
		$arr=unserialize(file_get_contents($arrFile));
	}
	foreach($arr as $v){
		if($v['id']==$cid) {
			return $v['cName'];
		}
	}
	return '';
}

function getNavPathByArr($arr,$cid,$arrFile=null) {
	if(isne($cid)) {
		return '';
	}
	if(!isne($arrFile)) {
		$arr=unserialize(file_get_contents($arrFile));
	}
	$cid=(int)$cid;
	$arrLen=count($arr);
	$tArr=array();
	$findIndex=-1;
	$curId=-1;
	for($i=0;$i<$arrLen;$i++) {
		$curId=(int)$arr[$i]['id'];
		if($curId==$cid) {
			$findIndex=$i;
			break;
		}
	}
	$curDp=-1;
	$prevDp=9999;
	for($i=$findIndex;$i>=0;$i--) {
		$curDp=(int)$arr[$i]['dp'];
		if($curDp<$prevDp) {
			$tArr[]=array('id'=>$arr[$i]['id'],'cName'=>$arr[$i]['cName']);
		}
		if($curDp==0) {
			break;
		}
		$prevDp=$curDp;
	}
	return array_reverse($tArr);
}

function getChildByTreeArr($arr,$cid,$fldArr=null,$arrFile=null) {
	if(isne($cid)) {
		return '';
	}
	if(!isne($arrFile)) {
		$arr=unserialize(file_get_contents($arrFile));
	}
	if(!is_array($arr)) {
		return array();
	}
	if(isne($fldArr)) {
		$fldArr=array('id');
	}
	$childArr=array();
	$rowArr=array();
	$curDp=-1;
	$curCid=-1;
	$findDp=-1;
	if($cid==-1) {
		foreach($arr as $arrEl){
			foreach($fldArr as $v){
				$rowArr=array_merge($rowArr,array($v=>$arrEl[$v]));
			}
			$childArr[]=$rowArr;
		}
		return $childArr;
	}
	foreach($arr as $arrEl){
		$curCid=$arrEl['id'];
		$curDp=$arrEl['dp'];
		if($findDp!=-1) {
			if($curDp<=$findDp) {
				return $childArr;
			}else{
				foreach($fldArr as $v){
					$rowArr=array_merge($rowArr,array($v=>$arrEl[$v]));
				}
				$childArr[]=$rowArr;
			}
		}
		if((int)$curCid==(int)$cid) {
			$findDp=$curDp;
			foreach($fldArr as $v){
				$rowArr=array_merge($rowArr,array($v=>$arrEl[$v]));
			}
			$childArr[]=$rowArr;
		}
	}
	return $childArr;
}

/*
$mode=1 只有下级
$mode=2 只有树叶
$mode=3 树叶和树技
*/
function opChildTreeArr($arr,$mode=1,$delFirst=1,$isRoot=0) {
	if(!is_array($arr)) return array();
	$arrLen=count($arr);
	if($arrLen==0) return array();
	if($arrLen==1) {
		if($delFist==1) {
			return array();
		}else{
			return $arr;
		}
	}
	$maxDp=-1;
	$tArr=array();
	if($isRoot==1) {
		$startDp=0;
	}else{
		$startDp=(int)$arr[1]['dp'];
	}
	$curDp=-1;
	$nextDp=-1;
	if($delFirst==0) {
		$tArr[]=$arr[0];
	}
	for($i=1;$i<$arrLen;$i++) {
		$curDp=(int)$arr[$i]['dp'];
		if($i<$arrLen) {
			$nextDp=(int)$arr[$i+1]['dp'];
		}else{
			$nextDp=-1;
		}
		if($mode==1) {
			if($startDp==$curDp) {
				$tArr[]=$arr[$i];
			}
		}
		if($mode==2) {
			if($nextDp<=$curDp) {
				$tArr[]=$arr[$i];
			}
		}
		if($mode==3) {
			$tArr[]=$arr[$i];
		}
	}
	return $tArr;
}

function getChildIdListByTreeArr($arr,$cid,$mode=1,$delFirst=1,$arrFile=null) {
	$fldArr=array('id','dp');
	$cArr=getChildByTreeArr($arr,$cid,$fldArr,$arrFile);
	$cArr=opChildTreeArr($cArr,$mode,$delFirst);
	$idArr=array();
	foreach($cArr as $k=>$v){
		$idArr[]=$v['id'];
	}
	return join(',',$idArr);
}

function delUploadFile($fileName,$hasSmallPic=0) {
	if($hasSmallPic) {
		$sFileName=str_replace('.','_s.',$fileName);
	}
	@unlink($fileName);
	@unlink($sFileName);
}

function formatMySqlDate($dateStr) {
	$arr=explode(' ',$dateStr);
	return $arr[0];
}
?>