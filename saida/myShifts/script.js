$(document).ready(function () {
	createCards(new Date().getMonth() + 1, new Date().getFullYear());
});
$("#submit").click(function () {
	createCards(new Date().getMonth() + 1, new Date().getFullYear());
	if ($('div.checkbox-group.required :checkbox:checked').length == 0) {
		swal({
			title: "אוי...",
			text: "אי אפשר לא להגיש אף משמרת, אל תהיה מצחיקול",
			icon: "error",
			button: "אה סבבה",
			dangerMode: true,
		});
	} else {
		var uid = document.getElementById("uid").value;
		var sunday = document.getElementById("sunday").checked;
		if (sunday == true) {
			sunday = "checked";
		} else {
			sunday = "";
		}
		var monday = document.getElementById("monday").checked;
		if (monday == true) {
			monday = "checked";
		} else {
			monday = "";
		}
		var tuesday = document.getElementById("tuesday").checked;
		if (tuesday == true) {
			tuesday = "checked";
		} else {
			tuesday = "";
		}
		var wednesday = document.getElementById("wednesday").checked;
		if (wednesday == true) {
			wednesday = "checked";
		} else {
			wednesday = "";
		}
		var thursday = document.getElementById("thursday").checked;
		if (thursday == true) {
			thursday = "checked";
		} else {
			thursday = "";
		}
		var friday = document.getElementById("friday").checked;
		if (friday == true) {
			friday = "checked";
		} else {
			friday = "";
		}
		var saturday = document.getElementById("saturday").checked;
		if (saturday == true) {
			saturday = "checked";
		} else {
			saturday = "";
		}

		$.ajax({
			type: "POST",
			url: "../includes/shifts.php",
			data: {
				sunday: sunday,
				monday: monday,
				tuesday: tuesday,
				wednesday: wednesday,
				thursday: thursday,
				friday: friday,
				saturday: saturday
			},
			success: function (data) {
				if (data != 0) {
					swal({
						title: "בוצע בהצלחה!",
						text: 'הגשת משמרות בהצלחה',
						icon: "success",
						button: "מגניב!"
					}).then((ok) => {
						if (ok) {
							window.location.reload();
						}
					});
				}
			}
		});
	}
});
$("#showMyShifts").click(function () {
	var month = $('#chosenMonth').val();
	var year = $('#chosenYear').val();
	createCards(month, year);
});