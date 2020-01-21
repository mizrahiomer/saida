$("input:file").change(function () {
         var fileName = $(this).val();
         $("#image-label").html(fileName);
         });
function getScore() {
	$.ajax({
		type: "GET",
		data: {
			score: 1
		},
		url: "../includes/wishlist.php",
		success: function (data) {
			if (data == 0) {
				$('#myScore').html('אין לך נקודות');
			} else {
				$('#myScore').html('איזה יופי יש לך כבר ' + data + ' נקודות');
			}
		}
	});
}
function wishlist(id) {
	var gid = id.substring(1);
	var action = "add";
	$.ajax({
		type: "GET",
		url: "../includes/wishlist.php",
		data: {
			gid: gid
		},
		success: function (data) {
			if (data == 1) {
				action = "delete";
			}
			$.ajax({
				type: "POST",
				url: "../includes/wishlist.php",
				data: {
					gid: gid,
					action: action
				},
				success: function (data) {
					$("#" + id).toggleClass('text-secondary');
				}
			});
		}
	});
}

function deleteGift(id) {
	swal({
			reverseButtons: true,
			title: "מחיקת מתנה",
			text: "האם אתה בטוח?",
			icon: "warning",
			buttons: ["ביטול", "אישור"],
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				var gid = id.substring(1);
				$.ajax({
					type: "GET",
					url: "../includes/manageGifts.php",
					data: {
						gid: gid,
						delete: 1
					},
					success: function (data) {
						swal({
							title: "נמחק בהצלחה",
							icon: "success",
							buttons: 'סבבה'
						}).then(function (result) {
							window.location.reload();
						});
					}
				});


			} else {
				swal({
					text: "הכל טוב, לא מחקנו כלום :)",
					buttons: "סבבה",
					icon: "info"
				});
			};
		});

}

function editGift(id) {
	var gid = id.substring(1);
	$.ajax({
		type: "GET",
		datatype: "JSON",
		url: "../includes/manageGifts.php",
		data: {
			gid: gid
		},
		success: function (data) {
			var data = JSON.parse(data);
			$('#editName').val(data[0].name);
			$('#editPrice').val(data[0].price);
			$('#editAmount').val(data[0].amount);
			$('#editDescription').val(data[0].description);
			$("#edit-image-label").html(data[0].image);
			$('#gid').val(gid);
			$("input:file").change(function () {
				var fileName = $(this).val();
				$("#edit-image-label").html(fileName);
			});
		}
	});
}

function viewGift(id) {
	var gid = id.substring(1);
	$.ajax({
		type: "GET",
		datatype: "JSON",
		url: "../includes/manageGifts.php",
		data: {
			gid: gid,
			view: true
		},
		success: function (data) {
			var data1 = JSON.parse(data);
			console.log(data1)
			if(data1.length == 0){
			    swal({
			        title: 'אין חדש, הכל טוב',
			        text : 'אין עובד שרכש את המתנה ולא קיבל אותה',
			        icon: "info",
			        buttons: 'אחלה'
		        });
			}else{
			$('#content').remove();
			var content = $('<div id="content" class="modal-body">');
			var div = $('<div class="row text-center">');
			for (var i = 0; i < data1.length; i++) {
				var col1 = $('<div class="col-6 my-2">');
				var col2 = $('<div class="col-6 my-2 ">');
				var h4 = $('<h4>');
				var button = $('<button class="btn btn-primary" name ="' + gid + '" id="' + data1[i].uid + '" onclick="giftStatus(' + data1[i].uid + ')">');
				h4.html(data1[i].fname + ' ' + data1[i].lname);
				button.html('אשר קבלה');
				col1.append(h4);
				col2.append(button);
				div.append(col1);
				div.append(col2);
			}
			content.append(div);
			$('#modal-body-content').append(content);
			$('#viewModal').modal('show');

		}
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
				var data2 = data;
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

function giftStatus(id) {
    console.log(id);
	var uid = id;
	var gid = $('#' + uid).attr("name");
	$.ajax({
		type: "GET",
		url: "../includes/manageGifts.php",
		data: {
			gid: gid,
			uid: uid,
			status: true
		},
		success: function (data) {
	
			if (data != 0) {
				swal({
					title: "העובד קיבל את המתנה",
					icon: "success",
					buttons: 'אחלה '
				}).then(function () {
					$('#' + uid).html('המתנה התקבלה');
					$('#' + uid).removeClass('btn-primary');
					$('#' + uid).addClass('btn-danger');
					$('#' + uid).attr('disabled','disabled');
					
				});
			}
		}
	});
}