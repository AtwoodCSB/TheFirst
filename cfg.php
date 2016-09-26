<?php
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
//error_reporting(E_ALL);
//error_reporting(E_ALL || ~E_NOTICE);
define('SITEROOT', preg_replace("/[\/\\\\]{1,}/",'/',dirname(__FILE__)));

$g_folder=array (
  'data' => 'data',
  'admin' => 'admin',
);

define('SITEDATA', SITEROOT.'/'.$g_folder['data']);
define('SITEADMIN', SITEROOT.'/'.$g_folder['admin']);

$g_siteInfo=array (
  'siteName' => '在线攒机',
  'siteTitle' => '【模拟攒机－模拟装机】在线攒电脑－ZOL中关村在线',
  'siteKey' => '攒机,在线攒机,中关村攒机,攒机方案,电脑攒机,模拟攒机,组装电脑,diy组装电脑',
  'siteDesc' => '中关村在线模拟攒机,在线攒机,中关村组装电脑',
);

$g_head=array();

$g_db=array (
  'host' => 'localhost',
  'user' => 'root',
  'pwd' => '',
  'dbName' => 'php',
  'dbPre' => '',
  'charset' => 'utf8',
);

$g_siteKey='OcM2Nbh8X196r1n475Nbh8X';

$g_cookie=array (
  'pre' => 'dy_',
  'path' => '/',
  'domain' => '',
);

define('PREV_URL', isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '');
$g_siteDomain=$_SERVER['SERVER_NAME'];

$g_sessionFld='userName,userRole';
$g_adminSessionFld='userName';
?>