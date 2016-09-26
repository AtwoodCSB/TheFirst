<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT.'/common/pic.php');

function showUploadErr($err) {
	$errArr=array('err'=>$err,'msg'=>'');
	echo json_encode($errArr);
	closeConn();
	die();
}

$uploadCfg=Array(
	'savePath' => '/upload/mEditor/{y}-{m}/',
	'fileSize' => '200',
	'fileType' => '_img'
);

$flag=f('flag','g');
$formHash=f('_formHash','g');
if(isne($flag)) {
	showUploadErr('no found flag');
}

if($flag=='test') {
	$cfg=Array(
		'savePath' => '/upload/test/{y}-{m}/',
	);
	$uploadCfg=array_merge($uploadCfg,$cfg);
}

if($flag=='file') {
	$cfg=Array(
		'fileSize' => '2000',
		'fileType' => 'rar,zip,txt'
	);
	$uploadCfg=array_merge($uploadCfg,$cfg);
}

if($flag=="swf") {
	$cfg=Array(
		'fileSize' => '2000',
		'fileType' => 'swf'
	);
	$uploadCfg=array_merge($uploadCfg,$cfg);
}

if($flag=="avi") {
	$cfg=Array(
		'fileSize' => '2000',
		'fileType' => 'avi,mp3'
	);
	$uploadCfg=array_merge($uploadCfg,$cfg);
}

$uCfg=f("uCfg","g");
if($uCfg=='frLink') {
	$cfg=array('savePath'=>'/upload/frLink/{y}-{m}/');
	$uploadCfg=array_merge($uploadCfg,$cfg);
}

$elName='filedata';
$uFile=$_FILES[$elName];

$errArr=array();
if($uFile['error']!=0) {
	$errDesc=array(
		'1'=>'文件大小超过了系统限制',
		'2'=>'文件大小超过了HTML表单中 MAX_FILE_SIZE 选项指定的值',
		'3'=>'只有部分被上传',
		'4'=>'没有文件被上传',
		'6'=>'找不到临时文件夹',
		'7'=>'文件写入失败'
	);
	showUploadErr($errDesc[$uFile['error']]);
}
$savePath2=is_null($uploadCfg['savePath']) ? $savePath : $uploadCfg['savePath'];
$savePath2=str_replace(array('{y}','{m}','{d}'),explode('-',date('Y-m-d')),$savePath2);
$fileSize2=is_null($uploadCfg['fileSize']) ? $fileSize : $uploadCfg['fileSize'];
$fileSize2=$fileSize2*1000;
$fileType2=is_null($uploadCfg['fileType']) ? $fileType : $uploadCfg['fileType'];
$fileType2=str_replace('_img','gif,jpg,jpeg,png',$fileType2);
$fileType2Arr=explode(',',$fileType2);
$imgSize2=is_null($uploadCfg['imgSize']) ? $imgSize : $uploadCfg['imgSize'];
$simgFlag2=is_null($uploadCfg['simgFlag']) ? $simgFlag : $uploadCfg['simgFlag'];
$simgSize2=is_null($uploadCfg['simgSize']) ? $simgSize : $uploadCfg['simgSize'];
$simgZoomType2=is_null($uploadCfg['simgZoomType']) ? $simgZoomType : $uploadCfg['simgZoomType'];
$markType2=is_null($uploadCfg['markType']) ? $markType : $uploadCfg['markType'];
$markTrans2=is_null($uploadCfg['markTrans']) ? $savePah : $uploadCfg['markTrans'];
$markQuality2=is_null($uploadCfg['markQuality']) ? $markTrans : $uploadCfg['markQuality'];
$markWidth2=is_null($uploadCfg['markWidth']) ? $markWidth : $uploadCfg['markWidth'];
$markHeight2=is_null($uploadCfg['markHeight']) ? $markHeight : $uploadCfg['markHeight'];
$markPos2=is_null($uploadCfg['markPos']) ? $markPos : $uploadCfg['markPos'];
$markImg2=is_null($uploadCfg['markImg']) ? $markImg : $uploadCfg['markImg'];
$markFontSize2=is_null($uploadCfg['markFontSize']) ? $markFontSize : $uploadCfg['markFontSize'];
$markFontColor2=is_null($uploadCfg['markFontColor']) ? $markFontColor : $uploadCfg['markFontColor'];
$markWaterText2=is_null($uploadCfg['markWaterText']) ? $markWaterText : $uploadCfg['markWaterText'];
if($uFile['size']>$fileSize2) {
	$temp=$fileSize2 / 1000;
	showUploadErr('文件大小不能超过'.$temp.'k');
}
$fileExt=strtolower(pathinfo($uFile['name'], PATHINFO_EXTENSION));
if(!in_array($fileExt,$fileType2Arr)) {
	showUploadErr('文件类型只能为'.$fileType2);
}
if(!is_uploaded_file($uFile['tmp_name'])) {
	showUploadErr('上传文件非法');
}
mkdirs(SITEROOT.$savePath2);
$fileNameNum=time().rand(1000,9999);
$fileLink=$savePath2.$fileNameNum.'.'.$fileExt;
$saveFileName=SITEROOT.$fileLink;
if(!move_uploaded_file($uFile['tmp_name'],$saveFileName)) {
	showUploadErr('上传文件保存失败');
}else{
	uploadLog($formHash,$saveFileName);
	if(!isne($imgSize2) and in_array($fileExt,array('gif','jpg','jpeg','png'))) {
		$sizeArr=explode(',',$imgSize2);
		imageResize($saveFileName,$sizeArr[0],$sizeArr[1]);
	}
}

if (in_array($fileExt,array('gif','jpg','jpeg','png'))) {
	if(!isne($simgFlag2)) {
		$fileLink_s=$savePath2.$fileNameNum.$simgFlag.'.'.$fileExt;
		$saveFileName_s=SITEROOT.$fileLink_s;
		$sizeArr=explode(',',$simgSize2);
		imageResize($saveFileName,$sizeArr[0],$sizeArr[1],$saveFileName_s);
		uploadLog($formHash,$saveFileName_s);
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

$errArr=array('err'=>'','msg'=>$fileLink);
echo json_encode($errArr);
closeConn();
?>