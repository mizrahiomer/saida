$(function () {
	createAreaChart(new Date().getFullYear());
	getYearlyCustomers(new Date().getFullYear());
	getMonthlyCustomers();
	$('#choosenMonth').hide();
});
$('#chosenYear').change(function () {
	createAreaChart($(this).val());
});
$('#monthYear').click(function () {
	createMonthChart();
});
$('#yearlyCst').change(function () {
	getYearlyCustomers($('#yearlyCst').val());
});

function createAreaChart(year) {
	$('#myAreaChart').remove();
	$('.chart-area').append('<canvas id="myAreaChart"><canvas>');
	var ctx = $("#myAreaChart");
	$.ajax({
		url: "data.php",
		type: "GET",
		data: {
			year: year
		},
		dataType: "JSON",
		success: function (data) {
			var sum = new Array(12).fill(0);
			var yearlySum = 0;
			for (var i = 0; i < data.length; i++) {
				sum[data[i].month - 1] = data[i].sum;
				yearlySum = yearlySum + parseInt(data[i].sum);
			}
			var d = new Date();
			var m = d.getMonth();
			document.getElementById("monthlyIncome").innerText = sum[m];
			document.getElementById("yearlyIncome").innerHTML = yearlySum + " ₪";
			var areaChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: ["ינו", "פבר", "מרץ", "אפר", "מאי", "יונ", "יול", "אוג", "ספט", "אוק", "נוב", "דצמ"],
					datasets: [{
						label: "הכנסות",
						lineTension: 0.3,
						backgroundColor: "#78C2AD",
						borderColor: "#00632b",
						pointRadius: 3,
						pointBackgroundColor: "#00632b",
						pointBorderColor: "#00632b",
						pointHoverRadius: 3,
						pointHoverBackgroundColor: "#00632b",
						pointHoverBorderColor: "#00632b",
						pointHitRadius: 10,
						pointBorderWidth: 2,
						data: sum,
					}],
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					layout: {
						padding: {
							left: 10,
							right: 25,
							top: 25,
							bottom: 0
						}
					},
					scales: {
						xAxes: [{
							time: {
								unit: 'date'
							},
							gridLines: {
								display: false,
								drawBorder: false
							},
							ticks: {
								maxTicksLimit: 7
							}
						}],
						yAxes: [{
							ticks: {
								maxTicksLimit: 5,
								padding: 10,
							},
							gridLines: {
								color: "rgb(234, 236, 244)",
								zeroLineColor: "rgb(234, 236, 244)",
								drawBorder: false,
								borderDash: [2],
								zeroLineBorderDash: [2]
							}
						}],
					},
					legend: {
						display: false
					},
					tooltips: {
						backgroundColor: "rgb(255,255,255)",
						bodyFontColor: "#858796",
						titleMarginBottom: 10,
						titleFontColor: '#6e707e',
						titleFontSize: 14,
						borderColor: '#dddfeb',
						borderWidth: 1,
						xPadding: 15,
						yPadding: 15,
						displayColors: false,
						intersect: false,
						mode: 'index',
						caretPadding: 10,
					}
				}
			});
		}
	});
}

function createMonthChart() {
	var month = $('#chosenMonth').val();
	var year = $('#chosenYear2').val();
	$.ajax({
		url: "data.php",
		type: "GET",
		data: {
			month: month,
			year: year
		},
		dataType: "JSON",
		success: function (data) {
			if (data[0] == null) {
				swal({
					title: "לא טוב בכלל",
					text: "אין נתונים של החודש הנבחר",
					icon: "error",
					dangerMode: true
				});
			} else {
				$('#myMonthChart').remove();
				$('.chart-month').append('<canvas id="myMonthChart"><canvas>');
				var ctx = $("#myMonthChart");
				var sum = new Array(31).fill(0);
				var monthlySum = 0;
				for (var i = 0; i < data.length; i++) {
					sum[data[i].day - 1] = data[i].sum;
					monthlySum = monthlySum + parseInt(data[i].sum);
				}
				$('#choosenMonthIncome').text(monthlySum + " ₪");
				$('#choosenMonth').show();
				var days = new Array(31);
				for (var i = 1; i <= days.length; i++) {
					if (i < 10) {
						days[i - 1] = "0" + i;
					} else {
						days[i - 1] = i;
					}
				}
				var areaChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: days,
						datasets: [{
							label: "הכנסות",
							lineTension: 0.3,
							backgroundColor: "#78C2AD",
							borderColor: "#00632b",
							pointRadius: 3,
							pointBackgroundColor: "#00632b",
							pointBorderColor: "#00632b",
							pointHoverRadius: 3,
							pointHoverBackgroundColor: "#00632b",
							pointHoverBorderColor: "#00632b",
							pointHitRadius: 10,
							pointBorderWidth: 2,
							data: sum,
						}],
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						layout: {
							padding: {
								left: 10,
								right: 25,
								top: 25,
								bottom: 0
							}
						},
						scales: {
							xAxes: [{
								time: {
									unit: 'date'
								},
								gridLines: {
									display: false,
									drawBorder: false
								},
								ticks: {
									maxTicksLimit: 7
								}
							}],
							yAxes: [{
								ticks: {
									maxTicksLimit: 5,
									padding: 10,
								},
								gridLines: {
									color: "rgb(234, 236, 244)",
									zeroLineColor: "rgb(234, 236, 244)",
									drawBorder: false,
									borderDash: [2],
									zeroLineBorderDash: [2]
								}
							}],
						},
						legend: {
							display: false
						},
						tooltips: {
							callbacks: {
								label: function (tooltipItem, data) {
									return data['datasets'][0]['data'][tooltipItem['index']] + " \u20AA";
								},
							},
							backgroundColor: "rgb(255,255,255)",
							bodyFontColor: "#858796",
							titleMarginBottom: 10,
							titleFontColor: '#6e707e',
							titleFontSize: 14,
							borderColor: '#dddfeb',
							borderWidth: 1,
							xPadding: 15,
							yPadding: 15,
							displayColors: false,
							intersect: false,
							mode: 'index',
							caretPadding: 10,
						}
					}
				});
			}
		}
	});
}

function getMonthlyCustomers() {
	var monthlyCst = 0;
	$.ajax({
		url: "data.php",
		type: "GET",
		data: {
			customers: true
		},
		dataType: "JSON",
		success: function (data) {
			if (data[0].customers != null) {
				monthlyCst = data[0].customers;
			}
			document.getElementById("monthlyCst").innerText = monthlyCst;
		}
	});

}

function getYearlyCustomers(year) {
	$('#pieChart').remove();
	$('.chart-pie').append('<canvas id="pieChart"><canvas>');
	var ctx = document.getElementById("pieChart");
	$.ajax({
		url: "data.php",
		method: "GET",
		data: {
			yearly: year,
		},
		dataType: "JSON",
		success: function (data) {
			console.log("fff" + data[0]);
			var sum = new Array(12).fill(0);
			for (var i = 0; i < data.length; i++) {
				sum[i] = data[i].customers;
			}
			var data = {
				labels: [
					"ינואר",
					"פברואר",
					"מרץ",
					"אפריל",
					"מאי",
					"יוני",
					"יולי",
					"אוגוסט",
					"ספטמבר",
					"אוקטובר",
					"נובמבר",
					"דצמבר"
				],
				datasets: [{
					data: sum,
					backgroundColor: [
						"#56e2cf",
						"#56aee2",
						"#5668e2",
						"#8a56e2",
						"#cf56e2",
						"#e256ae",
						"#e25668",
						"#e28956",
						"#e2cf56",
						"#aee256",
						"#68e256",
						"#56e289"
					],
				}]
			};
			// And for a doughnut chart
			var myPieChart = new Chart(ctx, {
				type: 'pie',
				data: data,
				options: {
					plugins: {
						labels: {
							fontStyle: 'bold',
							fontColor: 'white'
						}
					},
					legend: {
						position: 'right'
					},
				}
			});
		}
	});
}