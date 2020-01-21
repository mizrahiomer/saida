function createShiftsBoard() {
	var value = document.getElementById("role").value;
	$.post('showShifts.php', {
		dropdownValue: value
	}, function (data) {
		console.log(data);
		var obj = JSON.parse(data);
		console.log(value);
		console.log(obj);
		var shifts = document.getElementById("shifts");
		var div = document.createElement("div");
		div.id = "external-events";
		shifts.appendChild(div);
		for (var i in obj) {
			var newDiv = document.createElement("div");
			newDiv.className = obj[i].backgroundColor + " fc-event draggable text-center p-2 mb-3 mr-5 ";
			newDiv.innerHTML = obj[i].fname + " " + obj[i].lname;
			var newSpan = document.createElement("span");
			newSpan.innerHTML = obj[i].uid;
			newSpan.style.display = "none";
			var newTooltip = document.createElement("ul");
			newTooltip.classList.add("customTooltip");
			var days = ["ראשון", "שני", "שלישי", "רביעי", "חמישי", "שישי", "שבת"];
			var x = 0;
			for (var j = 4; j < 11; j++) {
				var newLi = document.createElement("li");
				newLi.innerHTML = days[x++];
				var newI = document.createElement("i");
				newI.className = "mx-1 fas";
				if (Object.values(obj[i])[j] == "checked") {
					newI.classList.add("fa-check");
				} else {
					newI.classList.add("fa-times");
				}
				newLi.appendChild(newI);
				newTooltip.appendChild(newLi);
			}
			newDiv.appendChild(newSpan);
			newDiv.appendChild(newTooltip);
			if (shifts.firstChild) {
				shifts.removeChild(shifts.firstChild);
				shifts.appendChild(div);
			}
			div.append(newDiv);
		}
		$('.fc-event').data("event", {
			overlap: false
		});
		$(".fc-event").mousedown(function () {
			$(this).children(".customTooltip").toggle();

		});

		$('#external-events .fc-event').each(function () {
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true, // will cause the event to go back to its
				revertDuration: 0 //  original position after the drag
			});
		});
	});
}