Array.prototype.in_array = function(e){
	for(var i=0;i<this.length;i++)
	{
		if(this[i] == e)
		return true;
	}
	return false;
}
String.prototype.trim=function(){
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
Date.prototype.dateAdd = function(interval,number){
	var d = this;
	var k={'y':'FullYear', 'q':'Month', 'm':'Month', 'w':'Date', 'd':'Date', 'h':'Hours', 'n':'Minutes', 's':'Seconds', 'ms':'MilliSeconds'};
	var n={'q':3, 'w':7};
	eval('d.set'+k[interval]+'(d.get'+k[interval]+'()+'+((n[interval]||1)*number)+')');
	return d;
}

Date.prototype.toStringByPattern = function(pattern) {
	pattern = pattern.replace(/yyyy/g, this.getFullYear());
	pattern = pattern.replace(/MM/g,this.getUTCMonth()+1);
	pattern = pattern.replace(/dd/g, this.getUTCDate());
	pattern = pattern.replace(/hh/g, this.getHours());
	pattern = pattern.replace(/mm/g, this.getMinutes());
	pattern = pattern.replace(/ss/g, this.getSeconds());
	return pattern;
}

var g_myRegEx={
	email:/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
	phone:/^[0-9,\-, ]+$/,
	phoneTel:/^[0-9,\-, ]+$/,
	tel:/^\d{8,11}$/,
	currency:/^\d+(\.\d+)?$/,
	number:/^\d*$/,
	zip:/^[1-9]\d{5}$/,
	QQ:/^[1-9]\d{4,8}$/,
	int:/^[-\+]?\d+$/,
	float:/^[-\+]?\d+(\.\d+)?$/,
	english:/^[A-Za-z]+$/,
	chinese:/^[\u0391-\uFFE5]+$/,
	userName:/^\w{4,16}$/i,
	pwd:/^\w{4,16}$/i,
	url:/^(?:http:\/\/)*[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/
};

function getValByJSON(json,exps) {
	var tArr=exps.split(',');
	var temp=json;
	for (var i=0; i<tArr.length; i++) {
		temp=temp[tArr[i]];
		if (temp==undefined) {
			return undefined;
		}
	}
	return temp;
}

function isne(str) {
	if (str==undefined || str==null || str=='') {
		return true;
	}else{
		return false;
	}
}

function chkForm(oThis,tipsType) {
	var getErrTips
	var formId=$(oThis).attr("id");
	var json=eval(formId+'JSON');
	var elArr,len,len2,usedElList,curElName,curElTagName,curElType,curEl,curElVal,elNodes;
	var errElArr=new Array();
	var errArr=new Array();
	var i,j;
	usedElList="";

	var chkEls="";
	if (json.chkEls) {
		chkEls=","+json.chkEls[0].els+",";
	}

	var getEl=function (elName) {
		if (chkEls.indexOf(","+elName+",")!=-1) {
			elName=elName+"[]";
		}
		return $(":input[name='"+elName+"']",oThis);
	}
	var trimElFn=function(str){
		if (str==null) {
			return '';
		}
		return str.trim();
	};
	$(':submit,:image').attr('disabled','1');

	$(":file",oThis).each(function(){
		var hidEl=$(":input[name='"+$(this).attr('name').replace('f_','')+"']",oThis);
		if (hidEl.val()=="") {
			hidEl.val($(this).val());
		}
	});
	//-------------------------------------
	elNodes=json.defaultVal;
	var jsonVal,jsonClear;
	if (elNodes){
		$.each(elNodes,function(el,obj){
		curEl=getEl(el);
		curVal=curEl.val().trim();
		jsonVal=obj.val;
		jsonClear=parseInt(obj.clear,10);
		if (jsonClear==1 && jsonVal==curVal) {
			curEl.val("");
		}
	});
	};

	var require=json.require;
	if (require!=undefined) {
		elArr=require.split(",");
		len=elArr.length;
		for (i=0; i<len;i++) {
			curElName=elArr[i];
			curEl=getEl(curElName);
			if (curEl.length==0) {
				continue;
			}
			curElTagName=curEl.attr("tagName").toLowerCase();
			curElType=curEl.attr("type").toLowerCase();
			if (curElTagName=="select" || curElTagName=="textarea" || curElType=="text" || curElType=="password" || curElType=="hidden") {
				 curElVal=trimElFn(curEl.val());
				 if (curElVal=="") {
					errElArr.push(curElName);
					curElErr=getValByJSON(json,"errTips,"+curElName+",val");
					if (curElErr!=undefined) {
						errArr.push(new Array(curElName,curElErr));
					}else{
						curElLabel=getValByJSON(json,"elLabel,"+curElName+",elName");
						errArr.push(new Array(curElName,"请填写"+curElLabel));
					}
				}
			}
			if (curElType=="radio" || curElType=="checkbox") {
				curEl=getEl(curElName);
				curElVal=parseInt(curEl.filter(":checked").length,10);
				if (curElVal==0) {
					errElArr.push(curElName);
					curElErr=getValByJSON(json,"errTips,"+curElName+",val");
					if (curElErr!=undefined) {
						errArr.push(new Array(curElName,curElErr));
					}else{
						curElLabel=getValByJSON(json,"elLabel,"+curElName+",elName");
						errArr.push(new Array(curElName,"请填写"+curElLabel));
					}
				}
			}
		}
	}

	elNodes=json.dataType;
	var curRegx;
	if (elNodes){
		$.each(elNodes,function(el,obj){
			curEl=getEl(el);
			curVal=curEl.val();
			if (curVal!="" && curVal!=undefined && !errElArr.in_array(el)) {
				if (obj["regx"]!=undefined) {
					curRegx=eval(obj["regx"]);
				}else{
					curRegx=eval("g_myRegEx."+obj["regxName"]);
				}
				if (!curRegx.test(curVal)) {
					errElArr.push(el);
					curElErr=getValByJSON(json,"errTips,"+el+",val");
					if (curElErr!=undefined) {
						errArr.push(new Array(el,curElErr));
					}else{
						curElLabel=getValByJSON(json,"elLabel,"+el+",elName");
						errArr.push(new Array(el,curElLabel+"格式不正确"));
					}
				}
			}
	});
	};

	var tempArr,tempArr2,hasValFlag;

	var jsonMinlen,jsonMaxlen,jsonElType,tErrStr;
	elNodes=json.teaList;
	if (elNodes){
		$.each(elNodes,function(el,obj){
		curEl=getEl(el)
		curVal=curEl.val();
		jsonMinlen=obj.minlen;
		jsonMaxlen=obj.maxlen;
		jsonElType=obj.elType;
		tErrStr="";
		if ((jsonMinlen!=undefined || jsonMaxlen!=undefined) && curVal!="") {
			if (jsonElType=="html") {
				if (navigator.userAgent.indexOf("Mozilla")==-1) {
					curVal=curVal.split("\n")
				}else{
					curVal=curVal.split("\r\n")
				}
				curVal=curVal.join("<br/>");
				curVal=curVal.replace(/ /g,"&nbsp;");
			}
			if (!errElArr.in_array(el)) {
				if (jsonMinlen!=undefined) {
					jsonMinlen=parseInt(jsonMinlen,10);
					if (curVal.length<jsonMinlen) {
						tErrStr="不能少于"+jsonMinlen+"个字符"
					}
				}
				if (jsonMaxlen!=undefined) {
					jsonMaxlen=parseInt(jsonMaxlen,10);
					if (curVal.length>jsonMaxlen) {
						tErrStr="不能多于"+jsonMaxlen+"个字符"
					}
				}
				if (tErrStr!="") {
					errElArr.push(el);
					curElErr=getValByJSON(json,"errTips,"+el+",val");
					if (curElErr!=undefined) {
						errArr.push(new Array(el,curElErr));
					}else{
						curElLabel=getValByJSON(json,"elLabel,"+el+",elName");
						errArr.push(new Array(el,curElLabel+tErrStr));
					}
				}
			}
		}
	});
	};

	elNodes=json.compare;
	var el1,el2,el1Val,el2Val,dt,flag,tStr,jsonErrAttr,el1Label,el2Label;
	if (elNodes){
		len=elNodes.length;
		for (i=0; i<len; i++) {
			el1Label=getValByJSON(json,"elLabel,"+elNodes[i]["el1"]+",elName");
			el2Label=getValByJSON(json,"elLabel,"+elNodes[i]["el2"]+",elName");
			el1=getEl(elNodes[i]["el1"]);
			el2=getEl(elNodes[i]["el2"]);
			dt=elNodes[i].dt;
			flag=elNodes[i].flag;
			jsonErrAttr=elNodes[i].err;
			el1Val=el1.val();
			el2Val=el2.val();
			if (el1Val!="" && el2Val!="" && !errElArr.in_array(el1) && !errElArr.in_array(el2)) {
				if (dt=="int") {
					el1=parseFloat(el1Val);
					el2=parseFloat(el2Val);
				}
				if (dt=="date") {
					el1=Date.parse(el1Val.replace('-','/'));
					el2=Date.parse(el2Val.replace('-','/'));
				}
				tStr="";
				if (flag=="=" && el1Val!=el2Val) {
					tStr="等于";
				}
				if (flag==">" && el1Val<=el2Val) {
					tStr="大于";
				}
				if (flag=="<" && el1Val>=el2Val) {
					tStr="小于";
				}
				if (flag==">=" && el1Val<el2Val) {
					tStr="大于等于";
				}
				if (flag=="<=" && el1Val>el2Val) {
					tStr="小于等于";
				}
				if (tStr!="") {
					errElArr.push(el1);
					errElArr.push(el2);
					if (jsonErrAttr!=undefined) {
						errArr.push(new Array(elNodes[i].el1,jsonErrAttr));
					}else{
						errArr.push(new Array(elNodes[i].el1,el1Label+"必须"+tStr+el2Label));
					}
				}
			}
		}
	}

	elNodes=json.chooseRequire;
	if (elNodes) {
		len=elNodes.length;
		var jsonEls,jsonErr,jsonElsArr,allEmptyFlag,arrEl;
		for (i=0; i<len; i++) {
			jsonEls=elNodes[i].els;
			jsonErr=elNodes[i].err;
			jsonElsArr=jsonEls.split(",");
			len2=jsonElsArr.length;
			allEmptyFlag=1;
			for (j=0; j<len2; j++) {
				curEl=getEl(jsonElsArr[j]);
				curVal=trimElFn(curEl.val());
				if (curVal!=""){
					allEmptyFlag=0;
					break;
				}
			}
			if (allEmptyFlag==1) {
				for (j=0; j<len2; j++) {
					errElArr.push(jsonElsArr[j]);
				}
				errArr.push(new Array(jsonElsArr[0],jsonErr));
			}
		}
	}

	elNodes=json.relateRequire;
	if (elNodes) {
		len=elNodes.length;
		for (i=0; i<len; i++) {
			jsonEls=elNodes[i].els;
			jsonErr=elNodes[i].err;
			jsonElsArr=jsonEls.split(",");
			len2=jsonElsArr.length;
			tempArr=new Array();
			tempArr2=new Array();
			hasValFlag=0;
			for (j=0; j<len2; j++) {
				curEl=getEl(jsonElsArr[j]);
				curVal=trimElFn(curEl.val());
				if (curVal!="") {
					hasValFlag=1;
				}
				if (curVal==""){
					tempArr.push(jsonElsArr[j]);
					tempArr2.push(getValByJSON(json,"elLabel,"+jsonElsArr[j]+",elName"));
					errElArr.push(jsonElsArr[j]);
				}
			}
			if (tempArr2.join(",")!="" && hasValFlag==1) {
				errArr.push(new Array(jsonElsArr[len2-1],"请填写"+tempArr2.join(",")));
			}
		}
	}

	elNodes=json.ifRequire;
	if (elNodes) {
		len=elNodes.length;
		var jsonEl,jsonRequireEls;
		for (i=0; i<len; i++) {
			jsonEl=elNodes[i].el;
			jsonVal=elNodes[i].val;
			jsonRequireEls=elNodes[i].requireEls;
			jsonElsArr=jsonRequireEls.split(",");
			curEl=getEl(jsonEl);
			curElType=curEl.attr("type").toLowerCase();
			if (curElType=="radio") {
				curVal=$(":radio[name='"+jsonEl+"'][checked]",oThis).val();
			}else{
				curVal=trimElFn(curEl.val());
			}
			tempArr=new Array();
			tempArr2=new Array();
			if (curVal==jsonVal) {
				len2=jsonElsArr.length;
				for (j=0; j<len2; j++) {
					curEl=$(":input[name='"+jsonElsArr[j]+"']",oThis);
					curVal=trimElFn(curEl.val());
					if (curVal==""){
						tempArr.push(jsonElsArr[j]);
						tempArr2.push(getValByJSON(json,"elLabel,"+jsonElsArr[j]+",elName"));
						errElArr.push(jsonElsArr[j]);
					}
				}
				if (tempArr2.join(",")!="") {
					errArr.push(new Array(errElArr[errElArr.length-1],tempArr2.join(",")+"必须填写"));
				}
			}
		}
	}
	if (errArr.length==0) {
		return true;
	}else{
		$(':submit,:image').removeAttr('disabled');
		if (tipsType==1) {
			var errStr="";
			for (i=0; i<errArr.length; i++) {
				errStr+=errArr[i][1]+"\n";
			}
			alert(errStr);
		}
		if (tipsType==2) {
			$(".s_formTips").html('');
			var tipsEl;
			for (i=0; i<errArr.length; i++) {
				el=getEl(errArr[i][0]);
				if (el.length>1) {
					el=el.last();
				}

				var spanTag=undefined;
				var tipsSpanAddClass=undefined;
				var tipsCfg=json.tipsCfg;
				if (tipsCfg) {
					for (j=0; j<tipsCfg.length; j++) {
						if ((","+tipsCfg[j].els+",").indexOf(","+errArr[i][0]+",")!=-1) {
							spanTag=tipsCfg[j].tag;
							tipsSpanAddClass=tipsCfg[j].addClass;
							break;
						}
					}
				}
				if (spanTag==undefined) spanTag=errArr[i][0];

				tipsEl=$("span[tag='"+spanTag+"']",oThis);
				if (tipsEl.length==0) {
					el.after("<span class='s_formTips' tag='"+spanTag+"'></span>");
					tipsEl=$("span[tag='"+spanTag+"']",oThis);
				}
				tipsEl.html("<div class='s_tipsErr'>"+errArr[i][1]+"</div>");
				if (tipsSpanAddClass!=undefined) {
					tipsSpanAddClass=" "+tipsSpanAddClass;
					$("span[tag='"+spanTag+"'] .s_tipsErr",oThis).attr("class","s_tipsErr"+tipsSpanAddClass);
				}
			}
		}
		$(":input[name='"+errArr[0][0]+"']",oThis).focus();
		return false;
	}
}

function getRq(url,pName) {
	var mats=eval("/"+pName+"=([^&]+)/").exec(url);
	if (mats!=null) {
		return mats[1];
	}else{
		return null;
	}
}

function formDataBind(formId) {
	var oForm=$("#"+formId);
	var json=eval(formId+'JSON');
	var verJSON=eval(formId+"_verJSON");
	var dataBind=json.dataBind;
	var oItem;
	if (dataBind) {
		for (elName in dataBind){
			var dSrc=eval('dataBind.'+elName+'.src');
			var fn=eval('dataBind.'+elName+'.fn');
			var showLeaf=eval('dataBind.'+elName+'.showLeaf');
			var elType=eval('dataBind.'+elName+'.elType');
			dSrc='/data/'+dSrc+'.json';
			var pa={"_v": eval('verJSON.'+elName),"formId": formId,"el": elName,"fn":fn,"showLeaf":showLeaf,"elType":elType};
			if (showLeaf!=undefined) {
				pa.showLeaf=showLeaf;
			}
			$.getJSON(dSrc,pa,function(json){
				var qs=this.data;
				var fn=getRq(qs,'fn');
				var formId=getRq(qs,'formId');
				var el=getRq(qs,'el');
				var showLeaf=getRq(qs,'showLeaf');
				var elType=getRq(qs,'elType');
				if (showLeaf==null) {
					showLeaf=0;
				}
				if (fn=='jsonToOpt') {
					var oForm=$("#"+formId);
					var selObj=$(":input[name='"+el+"']",oForm)[0];
					jsonToOpt(json,selObj,{'showLeaf':showLeaf});
				}
				if (fn=='mulSel') {
					mulSel(json,{'elHidden':el,'formId':formId});
				}
				//console.log(fn);
				if (fn=='formElList') {
					formElList(json,el,elType,formId);
				}
			});
		}
	}
}

function formElList(json,el,elType,formId) {
	var oForm=$("#"+formId);
	var hidEl=$(":input[name='"+el+"']",oForm);
	var hidVal=hidEl.val();
	hidVal=","+hidVal+",";
	var htmls='<div class="elGroup">';
	var len=json.length;
	for (var i=0; i<len; i++) {
		elName=el;
		if (elType=="checkbox") {
			elName=el+"[]";
		}
		chked="";
		if (hidVal.indexOf(json[i]['id'])!=-1) {
			chked=' checked="1"';
		}
		htmls+="<label><input class=\"inprc\" type=\""+elType+"\" name=\""+elName+"\" value=\""+json[i]['id']+"\""+chked+"/> "+json[i]['cName']+"</label>";
	}
	htmls+="</div>";
	hidEl.before(htmls);
	hidEl.remove();
}

var cfg_form={
	dataType:"json",
	beforeSubmit: function(formData,jqForm, cfg_form){
		var actions=jqForm.attr("action");
		var tipsType=jqForm.attr("tipsType");
		if (isne(tipsType)) tipsType=1;
		actions=actions.replace(/&_s=\d+$/,'');
		jqForm.attr("action",actions+"&_s="+Date.parse(new Date()));
		var result=chkForm(jqForm,tipsType);
		return result;
	},
	success:function(data){
		$(":submit").removeAttr("disabled");
		if (!isne(data.alert)) {
			alert(data.alert);
		}
		if (!isne(data.reUrl)) {
			location.href=decodeURIComponent(data.reUrl);
		}
		if (!isne(data.updateElId)) {
			$("#"+data.updateElId).html(data.html);
		}
		if (!isne(data.reload)) {
			location.reload(true);
		}
		if (data.upload!=undefined) {
			var len=data.upload.length;
			var el;
			var formId=data.formId;
			var oForm=$("#"+formId);
			for (var i=0; i<len; i++) {
				for(k in data.upload[i]){
					var fileName=eval("data.upload[i]."+k);
					$(":input[name='"+k+"']",oForm).val(fileName);
					$(":file[name='f_"+k+"']").replaceWith("<a href='"+fileName+"' target='_blank'>已上传</b>");
				}
			}
		}
	}
};

//jsonToOpt(ojson,document.getElementById('catalogId'),{'showLeaf':'1'});
function jsonToOpt(ojson,selObj,pCfg) {
	showLeaf=0;
	if (pCfg!=undefined) {
		showLeaf=parseInt(pCfg.showLeaf,10);
	}
	var selLen=0;
	if ($('option:first',selObj).val()=="") {
		selLen=1;
	}
	selObj.length=selLen;
	var num=ojson.length
	var curOpt;
	for (var i=0; i<num; i++) {
		var tStr='┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝┝';
		var dp=parseInt(ojson[i].dp,10);
		var optTxt=tStr.slice(0,dp)+' '+ojson[i].cName;
		var optVal=ojson[i].id;
		var nextDp=0;
		if (showLeaf==1) {
			if (ojson[i+1]!=undefined) {
				nextDp=parseInt(ojson[i+1].dp,10);
				if (nextDp>dp) {
					optVal='';
				}
			}
		}
		var newOpt=new Option(optTxt,optVal);
		selObj.options[selObj.length]=newOpt;
		if (optVal=='') {
			$('option:last',selObj).attr('class','s_noSel');
		}
	}
	elVal=$(selObj).attr('elVal');
	$('option[value='+elVal+']',selObj).attr('selected','1');
}

//mulSel(ojson,{'elHidden':'catalogId','formId':'search'});
function mulSel(ojson,pCfg) {
	var elHidden=pCfg.elHidden;
	var formId=pCfg.formId;
	var elHidVal="";
	var valArr=new Array();
	var jsonlen=ojson.length;
	var i;
	var oForm=$('#'+formId);

	elHidObj=$(":input[name='"+elHidden+"']",oForm);
	elHidVal=elHidObj.val();
	if (elHidVal!='') {
		var findFlag=0;
		for (i=0; i<jsonlen; i++) {
			valArr[ojson[i].dp]=ojson[i].id;
			if (ojson[i].id==elHidVal) {
				findFlag=1;
				break;
			}
		}
		if (findFlag==0) {
			valArr.length=0;
		}
	}

	var fnChange=function(){
		var mat=$(this).attr("name").match('(.+?)([0-9]+)');
		var elName=mat[1];
		var elIndx=parseInt(mat[2],10);
		var curVal=$(this).val();

		elHidObj.val("");
		var tempIndx=elIndx+1;
		var selObj=$(":input[name='"+elHidden+tempIndx+"']",oForm);
		while(selObj.length>0){
			selObj.css("display","none");
			selObj[0].length=1;
			tempIndx++;
			selObj=$(":input[name='"+elHidden+tempIndx+"']",oForm);
		}
		if (curVal=='') {
			return;
		}else{
			curVal=parseInt(curVal,10);
		}
		var jsonlen=ojson.length;
		var findFlag=0;
		var dp=-1;
		var curInd;
		for (var i=0; i<jsonlen; i++) {
			if (dp==-1) {
				if (parseInt(ojson[i].id,10)==curVal) {
					dp=parseInt(ojson[i].dp,10);
					curInd=i;
				}
			}
		}
		if (dp!=-1) {
			dp++;
			curInd++;
			var optArr=new Array();
			for (i=curInd; i<jsonlen; i++) {
				if (parseInt(ojson[i].dp,10)==dp) {
					optArr.push(new Array(ojson[i].id,ojson[i].cName));
				}
				if (parseInt(ojson[i].dp,10)<dp) {
					break;
				}
			}
			if (optArr.length>0) {
				var insertSel=$(":input[name='"+elHidden+(elIndx+1)+"']",oForm);
				if (insertSel.length==0) {
					elHidObj.before("<select name='"+elHidden+(elIndx+1)+"'><option value=''>-请选择-</option></select>");
					insertSel=$(":input[name='"+elHidden+(elIndx+1)+"']",oForm);
				}else{
					insertSel.css("display","");
				}
				for (i=0; i<optArr.length; i++) {
					insertSel.append("<option value='"+optArr[i][0]+"'>"+optArr[i][1]+"</option>");
				}
				insertSel.change(fnChange);
			}else{
				elHidObj.val(curVal);
			}
		}
	};

	var optArr=new Array();
	for (i=0; i<jsonlen; i++) {
		if (parseInt(ojson[i].dp,10)==0) {
			optArr.push(new Array(ojson[i].id,ojson[i].cName));
		}
	}

	var sel1Obj=$(":input[name='"+elHidden+"1']",oForm);
	if (sel1Obj.length==0) {
		elHidObj.before("<select name='"+elHidden+"1'><option value=''>-请选择-</option></select>");
		sel1Obj=$(":input[name='"+elHidden+"1']",oForm);
	}
	for (i=0; i<optArr.length; i++) {
		sel1Obj.append("<option value='"+optArr[i][0]+"'>"+optArr[i][1]+"</option>");
	}
	sel1Obj.change(fnChange);

	if (valArr.length>0) {
		for (i=0; i<valArr.length; i++) {
			selObj=$(":input[name='"+elHidden+(i+1)+"']",oForm);
			$("option[value='"+valArr[i]+"']",selObj).attr("selected","1");
			selObj.change();
		}
	}
}

function cTree(ojson) {
	var len=ojson.length;
	var htmls="";
	var curDp,curId,curCName,prevDp=-1,nextDp,pos;
	htmls+='<dl tag="sysTree" class="tree">';
	for (var i=0; i<len; i++) {
		curDp=ojson[i].dp;
		if (ojson[i+1]!=undefined) {
			nextDp=ojson[i+1].dp;
		}else{
			nextDp=-1;
		}
		curId=ojson[i].id;
		curCName=ojson[i].cName;
		pos=ojson[i].pos;

		var tag="";
		var treeBgClass="nPad ";
		if (pos=='first' && nextDp>curDp && curDp==0) {
			treeBgClass+="treeBg_branch_open_first";
			tag=' tag="control"';
		}
		if ((pos!='last' && nextDp>curDp && curDp>0) || (pos!='first' && pos!='last' && nextDp>curDp && curDp==0)) {
			treeBgClass+="treeBg_branch_open";
			tag=' tag="control"';
		}
		if (pos=='last' && nextDp>curDp) {
			treeBgClass+="treeBg_branch_open_last";
			tag=' tag="control"';
		}
		if (pos=='first' && nextDp<=curDp && curDp==0) {
			treeBgClass+="treeBg_leaf_first";
		}
		if ((pos!='last' && nextDp<=curDp && curDp>0) || (pos!='first' && pos!='last' && nextDp<=curDp && curDp==0)) {
			treeBgClass+="treeBg_leaf";
		}
		if (pos=='last' && nextDp<=curDp) {
			treeBgClass+="treeBg_leaf_last";
		}
		var treeClass="nPad ";
		if (nextDp>curDp) {
			treeClass+="tree_branch_open";
		}else{
			treeClass+="tree_leaf";
		}
		var treeTextClass="nText ";
		if (nextDp>curDp) {
			treeTextClass+="tree_text_branch";
		}else{
			treeTextClass+="tree_text_leaf";
		}

		if (curDp<prevDp) {
			for (var j=0; j<prevDp-curDp; j++) {
				htmls+="</dl></dd>";
			}
		}

		var tStr='<div class="'+treeBgClass+'"'+tag+'>&nbsp;</div><div class="'+treeClass+'">&nbsp;</div><div class="'+treeTextClass+'"><label>'+curCName+'</label></div><br class="clearFix"/>';

		htmls+="<dt>"+tStr+"</dt>";

		if (curDp<nextDp) {
			htmls+='<dd class="line"><dl>';
		}
		if (ojson[i+1]==undefined) {
			for (var j=0; j<curDp; j++) {
				htmls+="</dl></dd>";
			}
		}
		prevDp=curDp;
	}
	htmls+="</dl>";
	return htmls;
}

function pTree(elObj) {
	$("div[tag='control']",elObj).click(function(){
		var sibling=$(this).parent().next(":eq(0)");
		var myThis=$(this);
		var curClass;
		if (sibling[0] && sibling[0].tagName.toLowerCase()=="dd"){
			if (sibling.css("display")=="none"){
				sibling.css("display","block");
				myThis.attr("class",myThis.attr("class").replace("open","close"));
				myThis.next().attr("class","nPad tree_branch_close");
			}else{
				sibling.css("display","none");
				myThis.attr("class",myThis.attr("class").replace("close","open"));
				myThis.next().attr("class","nPad tree_branch_open");
			}
		}
	});

	$("dl[tag='sysTree'] dt",elObj).hover(
		function () {
			$(this).css("background","#FFFD98");
		},
		function () {
			$(this).css("background","transparent");
		}
	);

	$("dl[tag='sysTree'] input",elObj).click(function(){
		var myThis=$(this);
		if (myThis.attr("checked")==true){
			$("input",myThis.parent().parent().parent().next("dd")).attr("checked","true");
		}
		else{
			$("input",myThis.parent().parent().parent().next("dd")).removeAttr("checked");
		}
	});
}

function pTreeTbl(elObj) {
	$("table[tag='sysTree'] tr",elObj).hover(
		function () {
			$('td',this).css("background","#FFFD98");
		},
		function () {
			$('td',this).css("background","transparent");
		}
	);

	$(".treeTbl[tag='sysTree'] div[class*='control']",elObj).click(function(){
		var oTr=$(this).parent().parent();
		var curDp=parseInt(oTr.attr("dp"),10);
		var curClass=$(this).attr("class");
		if (curClass.indexOf('_open')!=-1) {
			curClass=curClass.replace(/_open/,'_close');
			$(this).attr("class",curClass);
			oTr.nextUntil("tr[dp='"+curDp+"']").css("display","none");
			oTr.nextUntil("tr[dp='"+curDp+"']").each(function(){
				var myClass=$("div[class*='control']",$(this)).attr("class");
				if (myClass!=undefined) {
					if (myClass.indexOf("_open")!=-1) {
						$("div[class*='control']",$(this)).attr("class",myClass.replace(/_close/,'_open'));
					}
				}
			});
		}else{
			curClass=curClass.replace(/_close/,'_open');
			$(this).attr("class",curClass);

			oTr.nextUntil("tr[dp='"+curDp+"']").each(function(){
				var curDp2=parseInt($(this).attr("dp"),10);
				if (curDp2==curDp+1) {
					$(this).css("display","");
				}else{
					var myClass=$("div[class*='control']",$(this)).attr("class");
					if (myClass!=undefined) {
						if (myClass.indexOf("_open")!=-1) {
							$("div[class*='control']",$(this)).attr("class",myClass.replace(/_open/,'_close'));
						}
					}
				}
			});
		}
	});
}

function chkLink(url,oThis,msg) {
	var flag=1;
	if (msg!=undefined) {
		flag=confirm(msg);
	}
	if (flag) {
		$(oThis).attr('href',url)
	}
}

function opTreeTbl(act,elId) {
	if (act==0) {
		$("#"+elId+" tr[dp!='0']").css("display","none");
		$("#"+elId+" th").parent().css("display","");
		$("#"+elId+" tr div[class*='control']").each(function(){
			var curClass=$(this).attr("class");
			if (curClass.indexOf("_open")!=-1) {
				curClass=curClass.replace(/_open/,"_close");
				$(this).attr("class",curClass);
			}
		});
	}
	if (act==1) {
		$("#"+elId+" tr[dp!='0']").css("display","");
		$("#"+elId+" tr div[class*='control']").each(function(){
			var curClass=$(this).attr("class");
			if (curClass.indexOf("_close")!=-1) {
				curClass=curClass.replace(/_close/,"_open");
				$(this).attr("class",curClass);
			}
		});
	}
}

function iframepop(obj) {
	obj.click(function(){
	var iframeSrc=$(this).attr('href');
	var boxTi=$(this).attr('boxTi');
	var wv=$(this).attr('wv');
	var hv=$(this).attr('hv');
	if (wv=="") wv="auto";
	if (hv=="") hv="auto";
	opop.box({iframe:iframeSrc,title:boxTi,width:wv,height:hv});
	return false;
});
}

function chkOrders(oThis) {
	var result=chkForm(oThis,1);
	if (result) {
		var pjObj={};
		$(".pjItem .itemul li[cid]").each(function(){
			var cid=$(this).attr('cid');
			var cName=$('.col1',$(this)).text();
			cName=cName.replace("*","");
			var title=$('.col2',$(this)).text();
			var prodNum=$('.col3 select',$(this)).val();
			var price=$('.col4',$(this)).text();
			if (price!=undefined && price!='') {
				pjObj['cid'+cid]={};
				pjObj['cid'+cid]['id']=cid;
				pjObj['cid'+cid]['cName']=cName;
				pjObj['cid'+cid]['title']=title;
				pjObj['cid'+cid]['prodNum']=prodNum;
				pjObj['cid'+cid]['price']=price;
			}
		});
		$("input[name='prodjson']",$(oThis)).val($.toJSON(pjObj));
	}
	return result;
}