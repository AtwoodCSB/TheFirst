<?php
function getSchoolId() {
	$sql='select id from school where userName=@userName';
	$sqlArr=array(
		'sql'=>$sql,
		'stmt'=>array('userName'=>s('userName'))
		);
	$id=exeCmd($sqlArr,'value');
	if(is_null($id)) {
		echo alert(array('msg'=>'请先填写培训机构基本信息','reUrl'=>'/member/schoolBase.php'));
		die();
	}
	return (int)$id;
}
?>