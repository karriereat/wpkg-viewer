$(document).ready(function() {
	$("#status").bind("change", function() {
		var currentValue = parseInt($(this).val());
		if(currentValue == -1) {
			$(".profiles tr").removeClass("hidden");
			return;
		}
		$(".profiles tbody tr").each(function() {
			if(parseInt($(this).attr("data-status")) != currentValue) {
				$(this).addClass("hidden");
			} else {
				$(this).removeClass("hidden");
			}
		});
	});
});

function filterIt() {
	var pr0grammId = $("#programmId").val();
	var daysSinceLastUpdate = $("#daysSinceLastUpdate").value();
	var status = $("#status").val();
	filter(pr0grammId, daysSinceLastUpdate, status);
}



function filter(programmId, daysSinceLastUpdate, status) {
	var $rowList = document.querySelectorAll(".profiles tbody tr");
	var now = Math.floor(Date.now() / 1000);

	var currentpProgrammId;
	var lastUpdate;
	var currentStatus;

	for(var i = 0; i < $rowList.length; i++) {

		lastUpdate = $rowList[i].querySelector(".lastUpdate").attributes.sorttable_customkey.value;
		lastUpdate = parseInt(lastUpdate);
		lastUpdate = lastUpdate + daysSinceLastUpdate * 86400
		currentStatus = $rowList[i].dataset.status;

		currentId = $rowList[i].querySelector(".id");

		if(programmId == -1) {
		    if(daysSinceLastUpdate == -1) {
		        $rowList[i].classList.remove("hidden");
		    } else {
    			if(now < lastUpdate && (currentStatus == status || status == -1)) {
    				$rowList[i].classList.remove("hidden");
    			} else {
    				$rowList[i].classList.add("hidden");
    			}
		    }
		} else {
			if(daysSinceLastUpdate == -1) {
				if(currentId.innerText == programmId && (currentStatus == status || status == -1)) {
					$rowList[i].classList.remove("hidden");
				} else {
					$rowList[i].classList.add("hidden");
				}
			} else {
				if( (now < lastUpdate) && (currentId.innerText == programmId)  && (currentStatus == status || status == -1)) {
					$rowList[i].classList.remove("hidden");
				} else {
					$rowList[i].classList.add("hidden");
				}
			}
		}
	}
}