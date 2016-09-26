<?php
require_once('cfg.php');
require_once(SITEROOT . '/common/function.php');
require_once(SITEROOT . '/common/form.php');
$act=f('act','g');
if($act=='save') {
	$formChkArr=pFormCfg('form/orders.formCfg');
	formReturn($formChkArr);
	$prodjson=f('prodjson');
	$prodArr=json_decode($prodjson,true);
	$pjArr=array();
	$pjidArr=array();
	$totalPrice=0;
	foreach($prodArr as $k=>$row){
		$prodId=(int)$row['id'];
		$cName=$row['cName'];
		$title=$row['title'];
		$prodNum=(int)$row['prodNum'];
		$price=(int)$row['price'];
		$pjArr[]=array('配置'=>$cName,'品牌型号'=>$title,'数量'=>$prodNum,'单价'=>$price);
		$totalPrice+=$prodNum*$price;
		$pjidArr[]=$prodId;
	}
	if(!in_array(1,$pjidArr) || !in_array(2,$pjidArr) || !in_array(3,$pjidArr) || !in_array(4,$pjidArr) || !in_array(11,$pjidArr)) {
		echo alert(array('msg'=>'CPU,主板,内存,硬盘,机箱是必选配件','reUrl'=>$g_reUrl));
	}
	$ordersDesc=serialize($pjArr);
	$sqlArr=array (
		'asql' =>
		array (
			'type' => 'insert',
			'tbl' => 'orders',
			'fld' =>
			array (
				'cid' => '',
				'title' => '',
				'content' => '',
				'ordersDesc' => '',
				'totalPrice' => '',
			),
		),
		'stmt' =>
		array (
			'cid' => array(f('cid'),'i'),
			'title' => f('title'),
			'content' => f('content'),
			'ordersDesc' => $ordersDesc,
			'totalPrice' => $totalPrice,
		),
	);
	exeCmd($sqlArr);
	echo alert(array('msg'=>'配置单提交成功','reUrl'=>$g_reUrl));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$g_head['css']=array('/css/common.css');
$g_head['js']=array('/js/jquery-1.4.2.min.js','/js/chkForm.js','/js/jquery.json-2.2.min.js');
echo pageHead();
?>
<script type="text/javascript">

</script>
<style type="text/css">
.computerCatalog{border:1px #C5D7ED solid;background:#E8F0FA;padding:6px;}
.computerCatalog ul{border:1px #C5D7ED solid;background:white;}
.computerCatalog .hli{width:100px;height:30px;line-height:30px;border:2px #ADBDD4 solid;float:left;margin:6px;}
.computerCatalog a{color:#2C69A0;font-weight:bold;font-size:14px;}
.computerCatalog .curli a{color:red;}
.computerCatalog .selected a{color:red;background:#FFF0D1;}

.pjItem{border:4px #E3EAF4 solid;}
.pjItem .ti{height:32px;line-height:32px;text-align:center;background:#447AB6;color:white;font-size:18px;}
.pjItem .th{border:1px #ADCCED solid;}
.pjItem .th .col1,.pjItem .th .col2,.pjItem .th .col3,.pjItem .th .col4,.pjItem .th .col5{height:24px;line-height:24px;background:#CEE0F4;float:left;}
.pjItem .col1{width:80px;}
.pjItem .col2{width:180px;}
.pjItem .col3{width:70px;}
.pjItem .col4{width:60px;}
.pjItem .col5{width:30px;}

.pjItem .itemul .col1,.pjItem .itemul .col2,.pjItem .itemul .col3,.pjItem .itemul .col4,.pjItem .itemul .col5{height:35px;line-height:35px;float:left;overflow:hidden;text-align:left;text-indent:3px;}
.pjItem .odd{background:#E8F0FA;}
.pjItem .fChk{margin-top:12px;}
.pjItem select{margin-top:6px;}
.pjItem .curli{border:1px #5898DE solid;background:#EAFAFA;}

.pjItem itemul .col4{font-size:14px;font-weight:bold;color:red;}

.iframePage{border:2px #E3EAF4 solid;}

#totalPrice{padding:6px;font-size:22px;font-weight:bold;color:red;border-top:1px #438EF2 dashed;}
</style>
</head>

<?php require_once('top.php'); ?>
<div class="grid_24">
	<div class="computerCatalog">
	<?php
		$computerArr=unserialize(file_get_contents(SITEROOT.'/data/computer.arr'));
		echo '<ul>';
		foreach($computerArr as $k=>$row){
			echo "<li class='hli' cid='{$row['id']}'><a href='javascript:void(0)'>{$row['cName']}</a></li>";
		}
		echo '<li class="clear"></li></ul>';
	?>
	</div>
</div>
<div class="clear"></div><div class="blankBar">&#160;</div>

<div class="grid_11">
	<div class="pjItem">
		<div class="ti">装机配置单</div>
		<ul class="th clearfix">
			<li class="col1">配置</li>
			<li class="col2">品牌型号</li>
			<li class="col3">数量</li>
			<li class="col4">单价</li>
			<li class="col5">选用</li>
		</ul>
		<ul class="itemul">
			<?php
			$requireItem=array(1,2,3,4,11);
			$index=1;
			foreach($computerArr as $k=>$row){
				if(in_array($row['id'],$requireItem)) {
					$require='<span class="require">*</span>';
				}else{
					$require='';
				}
				if($index % 2==0) {
					$className='odd';
				}else{
					$className='';
				}
				echo "<li class='clearfix {$className}' cid='{$row['id']}'>
					<div class='col1'>{$require}{$row['cName']}</div>
					<div class='col2 dItem'></div>
					<div class='col3 dItem'></div>
					<div class='col4 dItem'></div>
					<div class='col5 dItem'></div>
				</li>";
				$index++;
			}
			?>
		</ul>
		<div id="totalPrice">
			合计金额：<span>0</span>元
		</div>
		<div class="ordersDiv">
		<?php
		echo jsonVer(SITEROOT.'/form/orders.formCfg');
		echo file_get_contents(SITEROOT.'/form/orders.html');
		?>
		</div>
	</div>
</div>
<div class="grid_13">
	<div class="iframePage">
		<iframe src="prodList.php" id="prodList" width="500" height="1054" frameborder="no" align="center" scrolling="0" marginheight="0" marginwidth="0" border="0"></iframe>
	</div>
</div>
<div class="clear"></div><div class="blankBar">&#160;</div>

<script type="text/javascript">

$('.computerCatalog .hli').click(function(){
	$(this).parent().find(".curli").removeClass('curli');
	$(this).addClass('curli');
	var cid=$(this).attr('cid');
	$('.pjItem .itemul .curli').removeClass('curli');
	$('.pjItem .itemul li[cid="'+cid+'"]').addClass('curli');
	$('#prodList').attr('src','prodList.php?cid='+cid);
	});

$('.pjItem .itemul li').click(function(){
	$(this).parent().find('.curli').removeClass('curli');
	$(this).addClass('curli');
	var cid=$(this).attr('cid');
	$('.computerCatalog .curli').removeClass('curli');
	$('.computerCatalog .hli[cid="'+cid+'"]').addClass('curli');
	$('#prodList').attr('src','prodList.php?cid='+cid);
});

$("select[name='prodNum']").live('change',function(){
	getTotalPrice();
});

$("input[name='isSelProd']").live('click',function(){
	$(this).parent().parent().find('.dItem').html('');
	getTotalPrice();
});

function getTotalPrice() {
	var totalPrice=0;
	$(".pjItem .itemul .col4").each(function(){
		var curPrice=$(this).text();
		var curProdNum=$(this).prev().find('select').val();
		if (curPrice!='') {
			totalPrice+=parseInt(curPrice)*parseInt(curProdNum);
		}
	});
	$("#totalPrice span").html(totalPrice);
}

function fillProd(title,price) {
	var curli=$('.pjItem .itemul .curli');
	$('.col2',curli).html(title);
	$('.col3',curli).html("<select name='prodNum'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option></select>");
	$('.col4',curli).html(price);
	$('.col5',curli).html("<input type='checkbox' name='isSelProd' class='fChk'/>");
	getTotalPrice();
}
</script>
<?php require_once('bot.php'); ?>