$(document).ready(function() {
	$("#search").bind("keyup", function() {
		var currentValue = $(this).val();
		currentValue = currentValue.toLowerCase();
		$(".hostname").each(function(index) {
			var hostname = $(this).html();
			if(hostname.search(currentValue) == -1) {
				$(this).closest("tr").addClass("hidden");
			} else {
				$(this).closest("tr").removeClass("hidden");
			}
		});
	});
	$("#profiles").bind("change", function() {
		var currentValue = $(this).val();
		if(currentValue == "All") {
			$(".profiles tr").removeClass("hidden");
			return;
		}
		var profiles = $(".hostname");
		$(".profiles").each(function(index) {
			var profiles = $(this).text();
			if(profiles.indexOf(currentValue) == -1) {
				$(this).closest("tr").addClass("hidden");
			} else {
				$(this).closest("tr").removeClass("hidden");
			}
		});
	});
});