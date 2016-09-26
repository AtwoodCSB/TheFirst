<?php
function pFormCfg($pArr){
	if(is_array($pArr)){
		$arrFile = $pArr['arrFile'];
		if(!is_null($arrFile)){
			if(substr($arrFile, 0,1)=='/'){
				 $arrFile = SITEROOT . $arrFile;
				 }
			$formCfg = unserialize(file_get_contents($arrFile));
			}else{
				$formCfg = $pArr['formCfg'];
			}
		}else{
			$formCfgFile = $pArr;
			if(substr($formCfgFile,0,1)=='/') $formCfgFile=SITEROOT.$formCfgFile;
			$formCfg = unserialize(file_get_contents($formCfgFile));
		}
	//------------------------------

	$errElArr = array();
	$errArr = array();
	$defaultVal_f = $formCfg['defaultVal'];
	if($defaultVal_f!=null) {
		foreach($defaultVal_f as $k => $v){
			$curVal = f($k);
			if($curVal == $v[$val] and $v[$clear] == "1"){
				$_POST[$k] = '';
			}
			if(isne($curVal)) {
				$_POST[$k] = $v['val'];
			}
			}
	}

	$chkEls_f = $formCfg['chkEls'];
	if($chkEls_f!=null) {
		$arrLen = count($chkEls_f);
		for($i = 0;$i < $arrLen;$i++){
			$els = $chkEls_f[$i]['els'];
			$elArr = explode(',', $els);
			for($j = 0;$j < count($elArr);$j++){
				$k = str_replace('[]', '', $elArr[$j]);
				if (is_array($_POST[$k])){
					 $_POST[$k] = join(',', $_POST[$k]);
					 }
				}
			}
	}
	//---
	$uploadArr=array();
	$uploadReplArr=array();
	//处理上传
	if(count($_FILES)>0) {
		$upload_f=$formCfg['upload'];
		extract($upload_f[0]);
		//echo $fileSize.'==';die();
		$fldArr=array();
		for($i=1;$i<count($upload_f);$i++) {
			$els=$upload_f[$i]['els'];
			$elsArr=explode(',',$els);
			$attrArr=array();
			foreach($upload_f[$i] as $k=>$v){
				if($k!='els') {
					$attrArr=array_merge($attrArr,array($k=>$v));
				}
			}
			for($j=0;$j<count($elsArr);$j++) {
				//echo $elsArr[$j];
				$fldArr=array_merge($fldArr,array($elsArr[$j]=>$attrArr));
			}
		}
		//var_dump($fldArr);
		foreach($_FILES as $curFld=>$uFile){
			$curHidFld=str_replace('f_','',$curFld);
			$curFld=$curHidFld;
			if($uFile['name']=='') continue;
			if($uFile['error']!=0) {
				$errElArr[] = $curHidFld;
				$errDesc=array(
					'1'=>'的文件大小超过了系统限制',
					'2'=>'的文件大小超过了HTML表单中 MAX_FILE_SIZE 选项指定的值',
					'3'=>'只有部分被上传',
					'4'=>'没有文件被上传',
					'6'=>'找不到临时文件夹',
					'7'=>'文件写入失败'
				);
				$errArr[] = $formCfg['elLabel'][$curHidFld]['elName'] . $errDesc[$uFile['error']];
				continue;
			}
			$savePath2=is_null($fldArr[$curFld]['savePath']) ? $savePath : $fldArr[$curFld]['savePath'];
			$savePath2=str_replace(array('{y}','{m}','{d}'),explode('-',date('Y-m-d')),$savePath2);
			$fileSize2=is_null($fldArr[$curFld]['fileSize']) ? $fileSize : $fldArr[$curFld]['fileSize'];
			$fileSize2=$fileSize2*1000;
			$fileType2=is_null($fldArr[$curFld]['fileType']) ? $fileType : $fldArr[$curFld]['fileType'];
			$fileType2=str_replace('_img','gif,jpg,jpeg,png',$fileType2);
			$fileType2Arr=explode(',',$fileType2);
			$imgSize2=is_null($fldArr[$curFld]['imgSize']) ? $imgSize : $fldArr[$curFld]['imgSize'];
			$simgFlag2=is_null($fldArr[$curFld]['simgFlag']) ? $simgFlag : $fldArr[$curFld]['simgFlag'];
			$simgSize2=is_null($fldArr[$curFld]['simgSize']) ? $simgSize : $fldArr[$curFld]['simgSize'];
			$simgZoomType2=is_null($fldArr[$curFld]['simgZoomType']) ? $simgZoomType : $fldArr[$curFld]['simgZoomType'];
			$markType2=is_null($fldArr[$curFld]['markType']) ? $markType : $fldArr[$curFld]['markType'];
			$markTrans2=is_null($fldArr[$curFld]['markTrans']) ? $savePah : $fldArr[$curFld]['markTrans'];
			$markQuality2=is_null($fldArr[$curFld]['markQuality']) ? $markTrans : $fldArr[$curFld]['markQuality'];
			$markWidth2=is_null($fldArr[$curFld]['markWidth']) ? $markWidth : $fldArr[$curFld]['markWidth'];
			$markHeight2=is_null($fldArr[$curFld]['markHeight']) ? $markHeight : $fldArr[$curFld]['markHeight'];
			$markPos2=is_null($fldArr[$curFld]['markPos']) ? $markPos : $fldArr[$curFld]['markPos'];
			$markImg2=is_null($fldArr[$curFld]['markImg']) ? $markImg : $fldArr[$curFld]['markImg'];
			$markFontSize2=is_null($fldArr[$curFld]['markFontSize']) ? $markFontSize : $fldArr[$curFld]['markFontSize'];
			$markFontColor2=is_null($fldArr[$curFld]['markFontColor']) ? $markFontColor : $fldArr[$curFld]['markFontColor'];
			$markWaterText2=is_null($fldArr[$curFld]['markWaterText']) ? $markWaterText : $fldArr[$curFld]['markWaterText'];
			if($uFile['size']>$fileSize2) {
				$errElArr[] = $curHidFld;
				$temp=$fileSize2 / 1000;
				$errArr[] = $formCfg['elLabel'][$curHidFld]['elName'].'的大小不能超过'.$temp.'k';
				continue;
			}
			$fileExt=strtolower(pathinfo($uFile['name'], PATHINFO_EXTENSION));
			if(!in_array($fileExt,$fileType2Arr)) {
				$errElArr[] = $curHidFld;
				$errArr[] = $formCfg['elLabel'][$curHidFld]['elName'] . '的类型只能为'.$fileType2;
				continue;
			}
			if(!is_uploaded_file($uFile['tmp_name'])) {
				$errElArr[] = $curHidFld;
				$errArr[] = $formCfg['elLabel'][$curHidFld]['elName'] . '上传文件非法';
				continue;
			}
			mkdirs(SITEROOT.$savePath2);
			$fileNameNum=time().rand(1000,9999);
			$fileLink=$savePath2.$fileNameNum.'.'.$fileExt;
			$saveFileName=SITEROOT.$fileLink;
			if(!move_uploaded_file($uFile['tmp_name'],$saveFileName)) {
				$errElArr[] = $curHidFld;
				$errArr[] = $formCfg['elLabel'][$curHidFld]['elName'] . '保存失败';
				continue;
			}else{
				if(!isne($imgSize2) and in_array($fileExt,array('gif','jpg','jpeg','png'))) {
					$sizeArr=explode(',',$imgSize2);
					imageResize($saveFileName,$sizeArr[0],$sizeArr[1]);
				}
				$uploadArr=array_merge($uploadArr,array($curFld=>$fileLink));
				//----------------
				$oldFile=f($curFld);
				if(strpos($oldFile,'upload/')) {
					$uploadReplArr[]=$oldFile;
				}
				//-----------------
				$_POST[$curFld]=$fileLink;
			}

			if (!in_array($fileExt,array('gif','jpg','jpeg','png'))) continue;

			if(!isne($simgFlag2)) {
				$oldFile_s=substr($oldFile,0,strrpos($oldFile,'.')).$simgFlag.'.jpg';
				if(file_exists($oldFile_s)) {
					$uploadReplArr[]=$oldFile_s;
				}
				$fileLink_s=$savePath2.$fileNameNum.$simgFlag.'.'.$fileExt;
				$saveFileName_s=SITEROOT.$fileLink_s;
				$sizeArr=explode(',',$simgSize2);
				imageResize($saveFileName,$sizeArr[0],$sizeArr[1],$saveFileName_s);
				$uploadArr=array_merge($uploadArr,array($curFld.$simgFlag=>$fileLink_s));
			}
			if(!isne($markType2)) {
				$pArr=array(
					'srcFile'=>$saveFileName,
					'markType'=>$markType2,
					'wwidth'=>$markWidth2,
					'wheight'=>$markHeight2,
					'waterpos'=>$markPos2,
					'watertext'=>$markWaterText2,
					'fontsize'=>$markFontSize2,
					'fontcolor'=>$markFontColor2,
					'marktrans'=>$markTrans2,
					'markquality'=>$markQuality2,
					'markImg'=>$markImg2,
				);
				waterImg($pArr);
			}
		}
	}

	$require_f = $formCfg['require'];
	if($require_f!=null) {
		foreach($require_f as $k => $v){
			$curVal = f($k);
			if(is_null($curVal) && $v['nullSkip'] == '1') {
				continue;
			}
			if ((is_null($curVal) && $v['nullSkip'] == "0") || ($curVal == '')){
				 $errElArr[] = $k;
				 $tErrTips = $formCfg['errTips'][$k]['val'];
				 if(!is_null($tErrTips)){
					 $errArr[] = $tErrTips;
					 }else{
					 $errArr[] = $formCfg['elLabel'][$k]['elName'] . '是必填项';
					 }
				 }
			}
	}

	$teaList_f = $formCfg['teaList'];
	//---------------------
	if(is_null($teaList_f)) {
		$teaArr=array();
	}else{
		$teaArr=array_keys($teaList_f);
	}
	htmlEncodePost($_POST,$teaArr);
	//---------------------
	$mEditorUpload=array();
	$noUsedFileArr=array();

	if($teaList_f!=null) {
		foreach($teaList_f as $k => $v){
			$elType = $v['elType'];
			$curVal = f($k, 'p', 1);
			//--------------
			if($elType == 'mEditor'){
				$oldVal=f($k.'_old');
				preg_match_all('/\/upload\/[\w,\.,\/,-]+/',$oldVal,$oldMats);
				preg_match_all('/\/upload\/[\w,\.,\/,-]+/',$curVal,$mats);
				//var_dump($oldMats,$mats);
				$noUsedFileArr=array_merge($noUsedFileArr,array_diff($oldMats[0],$mats[0]));
			}
			//----------------
			if(isne($curVal)) {
				continue;
			}
			$safe = $v['safe'];
			$minlen = $v['minlen'];
			$maxlen = $v['maxlen'];
			if($elType == 'html'){
				if($safe==1) {
					$curVal=htmlspecialchars($curVal);
				}
				$curVal = pTa($curVal);
			}
			if($safe == '1' and $elType == 'mEditor'){
				$curVal = removeXSS($curVal);
			}
			$_POST[$k] = $curVal;
			if(!in_array($k, $errElArr)){
				if(!is_null($minlen)){
					 if(strlen($curVal) < $minlen){
						 $errElArr[] = $k;
						 $tErrTips = $formCfg['errTips'][$k]['val'];
						 if(!is_null($tErrTips)){
							 $errArr[] = $tErrTips;
							 }else{
							 $errArr[] = $formCfg['elLabel'][$k]['elName'] . '不能少于' . $minlen . '个字符';
							 }
						 }
					 }
				if(!is_null($maxlen)){
					 if(strlen($curVal) > $maxlen){
						 $errElArr[] = $k;
						 $tErrTips = $formCfg['errTips'][$k]['val'];
						 if(!is_null($tErrTips)){
							 $errArr[] = $tErrTips;
							 }else{
							 $errArr[] = $formCfg['elLabel'][$k]['elName'] . '不能多于' . $maxlen . '个字符';
							 }
						 }
					 }
				}
			}
		}
		$mEditorUpload['noUsedFileArr']=$noUsedFileArr;

	$dataTypeRegxName = array(
		"email" => "/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/",
		"phone" => "/^[0-9,\-, ]+$/",
		"tel" => "/^\d{8,11}$/",
		"zip" => "/^\d+(\.\d+)?$/",
		"qq" => "/^\d*$/",
		"int" => "/^[-\+]?\d+$/",
		"float" => "/^[-\+]?\d+(\.\d+)?$/",
		"chinese" => "/^[\x{4e00}-\x{9fa5}]+$/u",
		"userName" => "/^\w{4,16}$/",
		"pwd" => "/^\w{4,16}$/",
		"url"=>"/^(?:http:\/\/)*[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/"
		);

	$dataType_f = $formCfg['dataType'];
	if($dataType_f!=null) {
		foreach($dataType_f as $k => $v){
			$curVal = f($k);
			if(!in_array($k, $errElArr) and !isne($curVal)){
				$regx = $v['regx'];
				$regxName = $v['regxName'];
				if(!is_null($regxName)){
					 $regx = $dataTypeRegxName[$regxName];
					 }
				//var_dump($regx,$curVal);
				if(!preg_match($regx, $curVal)){
					 $errElArr[] = $k;
					 $tErrTips = $formCfg['errTips'][$k]['val'];
					 if(!is_null($tErrTips)){
						 $errArr[] = $tErrTips;
						 }else{
						 $errArr[] = $formCfg['elLabel'][$k]['elName'] . '的格式不正确';
						 }
					 }
				}
			}
	}

	$compare_f = $formCfg['compare'];
	if($compare_f!=null) {
		$arrLen = count($compare_f);
		for($i = 0;$i < $arrLen;$i++){
			$el1 = $compare_f[$i]['el1'];
			$el2 = $compare_f[$i]['el2'];
			$dt = $compare_f[$i]['dt'];
			$flag = $compare_f[$i]['flag'];
			$el1Val = f($el1);
			$el2Val = f($el2);
			if($dt == 'int'){
				$el1Val = (float)$el1Val;
				$el2Val = (float)$el2Val;
				}
			if($dt == 'date'){
				$el1Val = strtotime($el1Val);
				$el2Val = strtotime($el2Val);
				}

			if($flag == "="){
				if($el1Val != $el2Val){
					 $errElArr[] = $el1;
					 $errElArr[] = $el2;
					 $tErrTips = $compare_f[$i]['err'];
					 if(!is_null($tErrTips)){
							 $errArr[] = $tErrTips;
						 }else{
							 $el1Label = $formCfg['elLabel'][$el1];
							 $el2Label = $formCfg['elLabel'][$el2];
							 $errArr[] = $el1Label . '必须等于' . $el2Label;
						 }
					 }
				}

			if($flag == ">"){
				if($el1Val <= $el2Val){
					 $errElArr[] = $el1;
					 $errElArr[] = $el2;
					 $tErrTips = $compare_f[$i]['err'];
					 if(!is_null($tErrTips)){
						 $errArr[] = $tErrTips;
						 }else{
						 $el1Label = $formCfg['elLabel'][$el1];
						 $el2Label = $formCfg['elLabel'][$el2];
						 $errArr[] = $el1Label . '必须大于' . $el2Label;
						 }
					 }
				}

			if($flag == ">="){
				if($el1Val < $el2Val){
					 $errElArr[] = $el1;
					 $errElArr[] = $el2;
					 $tErrTips = $compare_f[$i]['err'];
					 if(!is_null($tErrTips)){
						 $errArr[] = $tErrTips;
						 }else{
						 $el1Label = $formCfg['elLabel'][$el1];
						 $el2Label = $formCfg['elLabel'][$el2];
						 $errArr[] = $el1Label . '必须大于等于' . $el2Label;
						 }
					 }
				}

			if($flag == "<"){
				if($el1Val >= $el2Val){
					 $errElArr[] = $el1;
					 $errElArr[] = $el2;
					 $tErrTips = $compare_f[$i]['err'];
					 if(!is_null($tErrTips)){
						 $errArr[] = $tErrTips;
						 }else{
						 $el1Label = $formCfg['elLabel'][$el1];
						 $el2Label = $formCfg['elLabel'][$el2];
						 $errArr[] = $el1Label . '必须小于' . $el2Label;
						 }
					 }
				}

			if($flag == "<="){
				if($el1Val > $el2Val){
					 $errElArr[] = $el1;
					 $errElArr[] = $el2;
					 $tErrTips = $compare_f[$i]['err'];
					 if(!is_null($tErrTips)){
						 $errArr[] = $tErrTips;
						 }else{
						 $el1Label = $formCfg['elLabel'][$el1];
						 $el2Label = $formCfg['elLabel'][$el2];
						 $errArr[] = $el1Label . '必须小于等于' . $el2Label;
						 }
					 }
				}

			}
		}

	$chooseRequire_f = $formCfg['chooseRequire'];
	if($chooseRequire_f!=null) {
		$arrLen = count($chooseRequire_f);
		for($i = 0;$i < $arrLen;$i++){
			$curCfg = $chooseRequire_f[$i];
			$els = $curCfg['els'];
			$err = $curCfg['err'];
			$elArr = explode(',', $els);
			$hasVal = 0;
			for($j = 0;$j < count($elArr);$j++){
				if(!isne(f($elArr[$j]))){
					 $hasVal = 1;
					 break;
					 }
				}
			if($hasVal == 0){
				for($j = 0;$j < count($elArr);$j++){
					 $errElArr[] = $elArr[$j];
					 }
				$errArr[] = $err;
				}
			}
		}

	$relateRequire_f = $formCfg['relateRequire'];
	if($relateRequire_f!=null) {
		$arrLen = count($relateRequire_f);
		for($i = 0;$i < $arrLen;$i++){
			$curCfg = $relateRequire_f[$i];
			$els = $curCfg['els'];
			$elArr = explode(',', $els);
			$emptyArr = array();
			$noEmptyArr = array();
			for($j = 0;$j < count($elArr);$j++){
				if(isne(f($elArr[$j]))){
					 $emptyArr[] = $elArr[$j];
					 }else{
					 $noEmptyArr[] = $elArr[$j];
					 }
				}
			if(count($emptyArr) > 0 && count($noEmptyArr) > 0){
				$tLabelArr = array();
				for($j = 0;$j < count($emptyArr);$j++){
					 $tLabelArr[] = $formCfg['elLabel'][$emptyArr[$j]]['elName'];
					 $errElArr[] = $emptyArr[$j];
					 }
				$errArr[] = join(',', $tLabelArr) . '是必填项';
				}
			}
		}

	$ifRequire_f = $formCfg['ifRequire'];
	if($ifRequire_f!=null) {
		$arrLen = count($ifRequire_f);
		for($i = 0;$i < $arrLen;$i++){
			$curCfg = $ifRequire_f[$i];
			$el = $curCfg['el'];
			$val = $curCfg['val'];
			$requireEls = $curCfg['requireEls'];
			$requireElsArr = explode(',', $requireEls);
			$emptyArr = array();
			if(f($el) == $val){
				for($j = 0;$j < count($requireElsArr);$j++){
					 if(isne(f($requireElsArr[$j]))){
						 $emptyArr[] = $requireElsArr[$j];
						 }
					 }
				if(count($emptyArr) > 0){
					 $tLabelArr = array();
					 for($j = 0;$j < count($emptyArr);$j++){
						 $tLabelArr[] = $formCfg['elLabel'][$emptyArr[$j]]['elName'];
						 $errElArr[] = $emptyArr[$j];
						 }
					 $errArr[] = join(',', $tLabelArr) . '是必填项';
					 }
				}
			}
		}

	if(count($errArr) == 0) {
		$arrFld_f = $formCfg['arrFld'];
		if(is_array($arrFld_f)) {
			foreach($arrFld_f as $fldName => $fldArr){
				foreach($fldArr as $k => $v){
					 if($v != ''){
							 $fldArr[$k] = f($v);
						}
				}
				foreach($fldArr as $k => $v){
					 if(isne($v)){
							unset($fldArr[$k]);
						}
				}
				$_POST[$fldName] = $fldArr;
				}
		}
	}
	//var_dump($_POST);die();
	if(count($uploadArr)>0) {
		$formHash=f("_formHash");
		foreach($uploadArr as $k=>$v){
			uploadLog($formHash,$v);
		}
	}
	//die();
	return array('err'=>$errArr,'upload'=>$uploadArr,'uploadReplArr'=>$uploadReplArr,'formId'=>$formCfg['formId'],'mEditorUpload'=>$mEditorUpload);
}

function opFormUploadClear($resultArr) {
	$uploadReplArr=$resultArr['uploadReplArr'];
	$mEditorUploadArr=$resultArr['mEditorUpload'];
	if(is_array($uploadReplArr)) {
		if(count($uploadReplArr)>0) {
			delFile($uploadReplArr);
		}
	}
	if(is_array($mEditorUploadArr)) {
		$noUsedFileArr=$mEditorUploadArr['noUsedFileArr'];
		delFile($noUsedFileArr);
	}
	$formHash=f("_formHash");
	$sql="delete from uploadLog where formHash=$formHash";
	exeCmd($sql);
}

function formReturnJson($arr) {
	if(count($arr['err'])==0) return;
	$errStr=join(chr(10).chr(13),$arr['err']);
	if($errStr!='') {
		$jsonArr=array();
		$jsonArr=array_merge($jsonArr,array('alert'=>$errStr));
		if(count($arr['upload'])>0) {
			$uploadArr=array();
			foreach($arr['upload'] as $k=>$v){
				$uploadArr[]=array($k=>$v);
			}
			$jsonArr=array_merge($jsonArr,array('upload'=>$uploadArr));
		}
		$jsonArr=array_merge($jsonArr,array('formId'=>$arr['formId']));
		//file_put_contents('t.txt',json_encode($jsonArr));
		echo json_encode($jsonArr);die();
	}
}

function formReturn($arr,$reUrl=null) {
	$reUrl=isne($reUrl) ? $g_reUrl : $reUrl;
	if(count($arr['err'])==0) return;
	$errStr=join('\n',$arr['err']);
	echo alert(array('msg'=>$errStr,'reUrl'=>$reUrl));
	die();
}

function expandFld(&$rsArr,$expandFldArr) {
	foreach($expandFldArr as $fldName){
		if(is_array($rsArr[$fldName])) {
			foreach($rsArr[$fldName] as $k=>$v){
				$elName=$formCfg['arrFld'][$fldName][$k];
				$rsArr[$elName]=$v;
			}
		}
	}
}

function fillForm($formFile,$rsArr,$formStr=null,$replaceArr=null,$formCfg=null) {
	if(!isne($formFile)) {
		if($formFile[0]=="/") {
			$formFile=SITEROOT.$formFile;
		}
		$formStr=file_get_contents($formFile);
	}
	if(!is_array($rsArr) || count($rsArr)==0) {
		echo $formStr;
		return;
	}
	if($formCfg==null) {
		$formCfg=unserialize(file_get_contents(str_replace('.html','.formCfg',$formFile)));
	}
	$mEditorArr=array();
	if(is_array($formCfg['teaList'])) {
		foreach($formCfg['teaList'] as $fldName=>$fldValArr){
			if($fldValArr['elType']=='mEditor') {
				$mEditorArr[]=$fldName;
			}
		}
	}
	if(is_array($formCfg['arrFld'])) {
		foreach($formCfg['arrFld'] as $fldName=>$fldValArr){
			if(count($rsArr[$fldName])>0) {
				$rsArr[$fldName]=unserialize($rsArr[$fldName]);
				foreach($fldValArr as $k=>$v){
					$elVal=$rsArr[$fldName][$k];
					if(!isne($elVal)) {
						$rsArr=array_merge($rsArr,array($v=>$elVal));
					}
				}
			}
		}
	}
	//var_dump($rsArr);//die();
	$chkElArr=array();
	if(!is_null($formCfg['chkEls'][0])) {
		$chkElArr=explode(',',$formCfg['chkEls'][0]['els']);
	}

	$xml = new DOMDocument();
	$xml->preserveWhiteSpace = false;
	$xml->formatOutput = true;
	$xml->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$formStr);
	$xpath = new DOMXPath($xml);
	//--
	foreach($mEditorArr as $el){
		$elName=$el.'_old';
		$nodes=$xpath->query("//input[@name='{$elName}']");
		if($nodes->length==0) continue;
		//echo $rsArr[$el];
		$nodes->item(0)->setAttribute("value",$rsArr[$el]);
	}
	//--
	//var_dump($chkElArr);
	foreach($rsArr as $k=>$v){
		if(is_array($v)) continue;
		$checkboxFlag=0;
		if(in_array($k,$chkElArr)) {
			$k=$k.'[]';
			$v=explode(',',$v);
			$checkboxFlag=1;
		}
		if($checkboxFlag==1) {
			$nodes=$xpath->query("//input[@name='{$k}']");
			if($nodes->length==0) continue;
			$len=$nodes->length;
			for($i=0;$i<$len;$i++) {
				if(in_array($nodes->item($i)->getAttribute("value"),$v)) {
					$nodes->item($i)->setAttribute("checked","1");
				}
			}
		}else{
			$nodes=$xpath->query("//node()[@name='{$k}']");
			if($nodes->length==0) continue;
			$tagName=strtolower($nodes->item(0)->tagName);
			$elType=strtolower($nodes->item(0)->getAttribute('type'));
			if($tagName=='input' && in_array($elType,array('text','hidden','password'))) {
				$nodes->item(0)->setAttribute("value",$v);
			}
			if($tagName=='input' && $elType=='checkbox') {
				if($nodes->item(0)->getAttribute('value')==$v) {
					$nodes->item(0)->setAttribute('checked','1');
				}
			}
			if($tagName=='input' && $elType=='radio') {
				$elLen=$nodes->length;
				for($i=0;$i<$elLen;$i++) {
					if($nodes->item($i)->getAttribute('value')==$v) {
						$nodes->item($i)->setAttribute('checked','1');
						break;
					}
				}
			}
			if($tagName=='textarea') {
				$elType=$formCfg['teaList'][$k]['elType'];
				if($elType=='html') {
					$v=pTa($v,1);
				}
				$nodes->item(0)->nodeValue=$v;
			}
			if($tagName=='select') {
				$optNodes=$xpath->query("option[@value='{$v}']",$nodes->item(0));
				if($optNodes->length==0) {
					$nodes->item(0)->setAttribute('elVal',$v);
				}else{
					$optNodes->item(0)->setAttribute('selected','1');
				}
			}
		}
	}

	$nodes=$xpath->query("//a[@upfile!='']");
	$len=$nodes->length;
	for($i=0;$i<$len;$i++) {
		$upFile=$nodes->item($i)->getAttribute("upfile");
		$hidElNodes=$xpath->query("//input[@name='{$upFile}']");
		$hidElVal=$hidElNodes->item(0)->getAttribute("value");
		if($hidElVal!=='') {
			$nodes->item($i)->setAttribute("href",$hidElVal);
			$nodes->item($i)->nodeValue='查看文件';
		}
	}
	if($fn!=null) {
		$fn($xml,$rsArr);
	}
	$htmlStr=$xml->saveHTML();
	$replArr=array(
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
		'<html>',
		'<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>',
		'<body>',
		'</body>',
		'</html>'
		);
	$htmlStr=str_replace($replArr,'',$htmlStr);
	if(is_array($replaceArr)) {
		$htmlStr=str_replace($replaceArr[0],$replaceArr[1],$htmlStr);
	}
	return $htmlStr;
}
?>