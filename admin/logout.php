<?php
require_once('../cfg.php');
require_once(SITEROOT . '/common/function.php');
$userName=s('userName',1,1);
logoutSession($userName,1);
echo alert(array('reUrl'=>'login.php','target'=>'top'));
?>