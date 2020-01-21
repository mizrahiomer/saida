$(document).ready(function () {
	$.ajax({
		type: "GET",
		url: "../includes/leadboard.php",
		datatype: "JSON",
		success: function (data) {
			var data = JSON.parse(data);
			for (var i = 0; i < data.length; i++) {
				var jsonObj = data[i];
				var tr = $("<tr>");
				tr.addClass("text-center");
				var num = $("<td>");
				var pictd = $("<td>");
				var pic = $('<img>');
				pictd.append(pic);
				var fname = $("<td>");
				var lname = $("<td>");
				var score = $("<td>");
				var star = $("<td>");
				var fa = $("<i>");
				fa.addClass("fas fa-star mt-1");
				star.append(fa);
				num.append(++i);
				i--;
				pic.attr('src', "../sidebar/img/" + jsonObj.image);
				pic.addClass("rounded-circle mt-1 mb-1");
				pic.attr('width', 40);
				pic.attr('height', 40);
				fname.append(jsonObj.fname);
				lname.append(jsonObj.lname);
				score.append(jsonObj.score);
				if (i == 0) {
					tr.addClass("table-secondary");
					tr.css("opacity", "0.9");
				} else if (i == 1) {
					tr.addClass("table-warning");
					tr.css("opacity", "0.9");
				} else if (i == 2) {
					tr.addClass("table-info");
					tr.css("opacity", "0.9");
				}
				if (i < 3) {
					tr.append(star);
				} else {
					tr.append(num);
				}
				tr.append(pic);
				tr.append(fname);
				tr.append(lname);
				tr.append(score);
				$('tbody').append(tr);
			}
		}
	});
});