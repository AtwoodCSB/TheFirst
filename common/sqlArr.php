<?php
function sqlArr_userRole($p) {
	$p=(int)$p;
	return $p==10 ? '个人会员' : '培训机构会员';
}
?>