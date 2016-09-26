function tblListTrHover() {
	$(".tblList tr").hover(
		function () {
			$(this).css("background","#FFFD98");
		},
		function () {
			$(this).css("background","transparent");
		}
	);
}

$(function(){
	iframepop($("a[popBox='1']"));
	tblListTrHover();
});