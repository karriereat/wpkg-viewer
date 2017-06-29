$(document).ready(function() {
	$(".openDetails").bind("click", function() {
		var statusName = $(this).attr("data-statusName");
		$(this).next(".details").toggleClass("open");
		console.log($(this).next(".details"));
	});
});