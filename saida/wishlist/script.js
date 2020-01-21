$(document).ready(function () {
	getScore();
});

function getScore() {
	$.ajax({
		type: "GET",
		data: {
			score: 1
		},
		url: "../includes/wishlist.php",
		success: function (data) {
			console.log("bla" + data);
			if (data == 0) {
				$('#myScore').html('אין לך נקודות');
			} else {
				$('#myScore').html('איזה יופי יש לך כבר ' + data + ' נקודות');
			}
		}
	});
}

function deleteFromWishlist(id) {
	var gid = id;
	var action = "delete";
	$.ajax({
		type: "GET",
		url: "../includes/wishlist.php",
		data: {
			gid: gid
		},
		success: function (data) {
			$.ajax({
				type: "POST",
				url: "../includes/wishlist.php",
				data: {
					gid: gid,
					action: action
				},
				success: function (data) {
					$("#" + id).toggleClass('text-secondary');
					window.location.reload();
				}
			});
		}
	});
}

function buyGift(id) {
	var id = id;
	if ($('#' + id).hasClass('disabled')) {
		swal({
			title: "אין לך מספיק נקודות",
			icon: "error",
			dangerMode: true,
			buttons: 'באסה'
		});
	} else {
		var gid = id.substring(1);
		$.ajax({
			type: "GET",
			url: "../includes/manageGifts.php",
			data: {
				gid: gid,
				buy: true
			},
			success: function (data) {
				swal({
					title: "תתחדש מותק",
					text: "פנה למנהל לקבלת המתנה",
					icon: "success",
					buttons: 'תודה תודה '
				}).then(() => {
					getScore();
					window.location.reload();

				});
			}
		});
	}
}