$(document).ready(function() {
	$(".clickableTr").bind("click", function(){
		location.href = $(this).attr("data-link");
	});
});