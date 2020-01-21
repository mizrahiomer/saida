$("#loading-image").html("blabla");
$(document).ready(function () {
	$.ajax({
		type: "POST",
		url: "../includes/report.php",
		data: {
			report: true
		},
		dataType: "JSON",
		success: function (data) {
			if (data != 0) {
				sweetAlertItems(data, 0);
			}
		}
	});
	createCards(new Date().getMonth() + 1, new Date().getFullYear());
});

function sweetAlertItems(data, index) {
	var fname = data[index].fname;
	var lname = data[index].lname;
	var uid = data[index].uid;
	var date = data[index].date;
	swal({
			title: "הורדת עובד",
			icon: "info",
			dangerMode: true,
			button: "הורד ממשמרת",
			text: fname + ' ' + lname + ' שכח/ה לרדת מהמשמרת',
			content: {
				element: "input",
				attributes: {
					placeholder: "הכנס שעת ירידה",
					type: "text",
				},
			},
		})
		.then((value) => {
			$.ajax({
				type: "POST",
				url: "../includes/inshift.php",
				data: {
					end: value,
					uid: uid,
					date: date,
				}
			});
			index++;
			if (index < data.length) {
				sweetAlertItems(data, index);
			}
		});
}
$("#submit").click(function () {
	var nowDate = new Date();
	var date = document.getElementById("date").value;
	var uid = document.getElementById("uid").value;
	var total = document.getElementById("total").value;
	var cash = document.getElementById("cash").value;
	var credit = document.getElementById("credit").value;
	var customers = document.getElementById("customers").value;
	var cancellations = document.getElementById("cancellations").value;
	var discounts = document.getElementById("discounts").value;
	var tips = document.getElementById("tips").value;
	var summary = document.getElementById("summary").value;
	var formDate = Date.parse(date);
	var diff = Math.floor((formDate - nowDate) / (1000 * 60 * 60 * 24) + 1);
	if (diff != -1) {
		swal({
			title: 'הלו הלו !',
			text: ' התאריך שהזנת אינו תקין',
			icon: "error",
			button: "אופסי",
		});

	} else{
		$.ajax({
			type: "POST",
			url: "../includes/report.php",
			data: {
				submit: true,
				date: date,
				uid: uid,
				total: total,
				cash: cash,
				credit: credit,
				customers: customers,
				cancellations: cancellations,
				discounts: discounts,
				tips: tips,
				summary: summary
			},
			success: function (data) {
				if (data != 0) {
					swal({
						title: 'הדו"ח נשמר בהצלחה',
						text: 'סה"כ טיפ לשעה: ' + data + ' ש"ח',
						icon: "success",
						button: "מגניב!",
					}).then((ok) => {
						if (ok) {
							window.location.href = "index.php";
						}
					});
				} else {
					swal({
						title: "!איזה באסה",
						text: 'קיים דו"ח סיכום משמרת עם התאריך שהוזן, אנא נסה שנית',
						icon: "error",
						button: "הבנתי",
						dangerMode: true,
					});
				}
			}
		});
	}
});
$('#showReports').click(function () {
	var month = $('#chosenMonth').val();
	var year = $('#chosenYear').val();
	createCards(month, year);
});

function createCards(month, year) {
	$('#reports').empty();
	$.ajax({
		type: "GET",
		url: "../includes/report.php",
		data: {
			reports: true,
			year: year,
			month: month
		},
		success: function (data2) {
			var data = JSON.parse(data2);
			if (data != 0) {
				var count = 1;
				var row = $('<div class = "row">');
				for (var x = 0; x < data.length; x++, count++) {
					var col = $('<div class="col-lg-4">');
					var center = $('<div class="mt-3 text-center">');
					var accordion = $('<div id="accordion" role="tablist" aria-multiselectable="true">');
					var card = $('<div class="card m-1 p-0 shadow">');
					var h5 = $('<h5 class="card-header" role="tab" id="heading' + count + '">');
					var a = $('<a data-toggle="collapse" data-parent="#accordion" href="#collapse' + count + '" aria-expanded="true" aria-controls="collapse' + count + '" class="d-block text-center text-primary">');
					var i = $('<i class="fa fa-chevron-down pull-right text-left"></i>');
					var cardbody1 = $('<div id="collapse' + count + '" class="collapse" role="tabpanel" aria-labelledby="heading' + count + '">');
					var cardbody2 = $('<div class="card-body">');
					var div = $('<div></div>');
					var span = $('<span>לצפייה לחץ כאן </span>');
					var aInside = $('<a href="../includes/makepdf.php?date=' + data[x].date + '" target="_blank" class="btn bg-light"><i class="fas fa-file-pdf fa-2x text-secondary"></i></a>')
					div.append(span);
					div.append(aInside);
					cardbody2.append(div);
					cardbody1.append(cardbody2);
					a.append(i);
					var days = ["ראשון", "שני", "שלישי", "רביעי", "חמישי", "שישי", "שבת"];
					var date = new Date(data[x].date);
					a.append(' ' + days[date.getDay()] + ', ' + date.toLocaleDateString("en-GB"));
					h5.append(a);
					card.append(h5);
					card.append(cardbody1);
					accordion.append(card);
					center.append(accordion);
					col.append(center);
					row.append(col);
					$('#reports').append(row);
				}
			} else {
				swal({
					title: 'אין דו"חות סיכום לחודש זה',
					icon: "info",
					dangerMode: true,
					button: "סבבה",
				});
			}
		}
	});
	$('#pinuk').click(function () {
		var uid = $('#exceptional').val();
		$.ajax({
			type: "POST",
			url: "../includes/report.php",
			data: {
				exceptional: true,
				uid: uid
			},
			success: function (data) {
				if (data != 0) {
					$('#pinuk').addClass('disabled');
					$('#pinuk').html('פונק בכיף');
				}
			}
		});
	});
}