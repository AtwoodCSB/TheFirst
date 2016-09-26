<?php
require_once('cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT . '/common/form.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$g_head['css']=array('/css/common.css');
$g_head['js']=array('/js/jquery-1.4.2.min.js','/js/chkForm.js');
echo pageHead();
?>
<script type="text/javascript">

</script>
<style type="text/css">

.pageTi{height:40px;line-height:40px;text-align:center;font-size:20px;font-weight:bold;color:green;}
.brandul .hli{width:78px;height:16px;line-height:16px;margin:1px;border:1px #ccc solid;float:left;overflow:hidden;}
.brandul .hli a{color:#2C69A0;}
.brandul .curli{border:1px red solid;}
.brandul .curli a{color:red;font-weight:bold;}

.produl{margin-top:12px;border-top:1px #ccc dashed;}
.produl li div{float:left;height:24px;line-height:24px;text-align:left;}
.produl li .prodName{width:300px;text-indent:12px;color:#2C69A0;}
.produl li .price{width:80px;color:red;font-size:14px;}
.produl li .ha{width:60px;font-weight:bold;color:blue;}
</style>
</head>
<body>
<?php
	$cid=f('cid','g');
	if(isne($cid)) {
		echo "<div class='pageTi'>请点击上面配件，选择相应的产品</div>";
	}else{
		$brandid=f('brandid','g');
		$computerArr=unserialize(file_get_contents(SITEROOT.'/data/computer.arr'));
		$brandArr=unserialize(file_get_contents(SITEROOT.'/data/brand'.$cid.'.arr'));
		$pjName=getCNameByArr($computerArr,$cid);
		echo "<div class='pageTi'>请选用{$pjName}</div>";
		echo "<ul class='brandul'>";
		foreach($brandArr as $k=>$row){
			if($row['id']==(int)$brandid) {
				$className=' curli';
			}else{
				$className='';
			}
			echo "<li class='hli{$className}'><a href='?cid={$cid}&brandid={$row['id']}'>{$row['cName']}</a></li>";
		}
		echo "<li class='clear'></li></ul>";
		$pageSize=25;
		$sqlArr=array (
			'asql' =>
			array (
				'type' => 'select',
				'tbl' => 'computer',
				'fld' => 'title,price',
				'where' =>
				array (
					'cid' => array(),
					'brandid' => array(),
				),
			),
			'sp'=>array(
				'key'=>'id',
				'size'=>$pageSize,
			),
			'stmt' =>
			array (
				'cid' => array(f('cid','g'),'i'),
				'brandid' => array(f('brandid','g'),'i'),
			),
		);
		$arr=exeCmd($sqlArr,'array');
		$rsArr=$arr['rs'];
		if(!is_array($rsArr)) {
			$rsArr=array();
		}
		$rsCount=$arr['rsCount'];
		$spBar=spBar(array('rsCount'=>$rsCount,'pageSize'=>$pageSize));
		echo "<ul class='produl'>";
		foreach($rsArr as $k=>$row){
			echo "<li class='clearfix'>
			<div class='prodName'>{$row['title']}</div>
			<div class='price'>￥ {$row['price']}</div>
			<div class='ha'><a href=\"javascript:parent.fillProd('{$row['title']}','{$row['price']}')\">选用</a></div>
			</li>";
		}
		echo "<li class='spBar'>{$spBar}</li></ul>";
	}
?>
</body>
</html>