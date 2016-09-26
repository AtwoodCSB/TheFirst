function changeChkCode(oThis) {
	oThis.src="/chkCodeImg.php?timer="+new Date().getUTCMilliseconds();
}

function zoomImage(Img,MaxW,MaxH,altstr){
	var image=new Image();
	image.src=Img.src;
	image.alt=altstr;
	if(image.width>0 && image.height>0){
		if(image.width/image.height>=MaxW/MaxH){
			if(image.width>MaxW){
				Img.width=MaxW;
				Img.height=(image.height*MaxW)/image.width;
			}else{
				Img.width=image.width;
				Img.height=image.height;
			}
		}
		else{
			if(image.height>MaxH){
				Img.height=MaxH;
				Img.width=(image.width*MaxH)/image.height;
			}else{
				Img.width=image.width;
				Img.height=image.height;
			}
		}
	}
}

(function($){$.fn.bgIframe=$.fn.bgiframe=function(s){if($.browser.msie&&/6.0/.test(navigator.userAgent)){s=$.extend({top:'auto',left:'auto',width:'auto',height:'auto',opacity:true,src:'javascript:false;'},s||{});var prop=function(n){return n&&n.constructor==Number?n+'px':n;},html='<iframe class="bgiframe"frameborder="0"tabindex="-1"src="'+s.src+'"'+'style="display:block;position:absolute;z-index:-1;'+(s.opacity!==false?'filter:Alpha(Opacity=\'0\');':'')+'top:'+(s.top=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+\'px\')':prop(s.top))+';'+'left:'+(s.left=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+\'px\')':prop(s.left))+';'+'width:'+(s.width=='auto'?'expression(this.parentNode.offsetWidth+\'px\')':prop(s.width))+';'+'height:'+(s.height=='auto'?'expression(this.parentNode.offsetHeight+\'px\')':prop(s.height))+';'+'"/>';return this.each(function(){if($('> iframe.bgiframe',this).length==0)this.insertBefore(document.createElement(html),this.firstChild);});}return this;};})(jQuery);

(function($){
	$.fn.extend({
		menu: function(options) {
			var defaults = {
				menuObj: null,
				zindex:false,
				pos:3
			};
			var options = $.extend(defaults, options);
			return this.each(function() {
				var o =options;
				var obj = $(this);
				var menuBody=o.menuObj;
				var pos=o.pos;
				if (menuBody==undefined) {
					menuBody=$(this).next();
				}
				var posAttr=$(this).attr("pos");
				var xfix=0,yfix=0;
				if (posAttr!=undefined) {
					var posArr=posAttr.split(",");
					pos=parseInt(posArr[0]);
					if (posArr.length>0) {
						xfix=parseInt(posArr[1]);
					}
					if (posArr.length>1) {
						yfix=parseInt(posArr[2]);
					}
					if (isNaN(xfix)) xfix=0;
					if (isNaN(yfix)) yfix=0;
				}
				var cwidth=$(this).width();
				var cheight=$(this).height();
				menuBody.bgiframe();
				var timer;
				obj.mouseover(function() {
					if (timer!=undefined) {
						clearTimeout(timer);
					}
					var offset=$(this).offset();
					var cleft=offset.left;
					var ctop=offset.top;
					if (pos==3) {
						menuBody.css({left:cleft+xfix,top:ctop+cheight+yfix,position:"absolute","z-index":"9999"});
					}
					if (pos==2) {
						menuBody.css({left:cleft+cwidth+xfix,top:ctop+yfix,position:"absolute","z-index":"9999"});
					}
					menuBody.css("display","block");
				});
				obj.add(menuBody).mouseout(function(){
					timer=setTimeout(function(){hidDiv(menuBody)},100);
				});
				var hidDiv=function(obj){
					obj.css("display","none");
				}
				menuBody.mouseover(function() {
					clearTimeout(timer);
				});
			});
		}
	});
})(jQuery);

$(function(){
	$("._menu").menu();
});

function dtTab(elExp) {
	$("dt",elExp).click(function(){
		var oThis=$(this);
		if (oThis.next().css("display")=="none") {
			oThis.next().css("display","block");
		}else{
			oThis.next().css("display","none");
		}
	});
}