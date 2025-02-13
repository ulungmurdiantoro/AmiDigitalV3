$(function() {
  'use strict'

  var colors = {
    primary        : "#104ea4",
    secondary      : "#9babc3",
    success        : "#05a34a",
    info           : "#66d1d1",
    warning        : "#f3d27f",
    danger         : "#8c123d",
    light          : "#e9ecef",
    dark           : "#000000",
    muted          : "#9babc3",
    gridBorder     : "rgba(77, 138, 240, .15)",
    bodyColor      : "#000",
    cardBg         : "#fff"
  }

  var fontFamily = "'Roboto', Helvetica, sans-serif"

	if (document.getElementById('DiagramBatang')) {
		var options = {
				chart: {
						type: 'bar',
						height: 318,
						parentHeightOffset: 0,
						foreColor: colors.bodyColor,
						background: colors.cardBg,
						toolbar: {
								show: false
						},
				},
				theme: {
						mode: 'light'
				},
				tooltip: {
						theme: 'light'
				},
				colors: [colors.primary],  
				fill: {
						opacity: 0.9
				}, 
				grid: {
						padding: {
								bottom: -4
						},
						borderColor: colors.gridBorder,
						xaxis: {
								lines: {
										show: true
								}
						}
				},
				series: [{
						name: 'Average Score',
						data: averages
				}],
				xaxis: {
						type: 'category',
						categories: categories,
						axisBorder: {
								color: colors.gridBorder,
						},
						axisTicks: {
								color: colors.gridBorder,
						},
				},
				yaxis: {
						title: {
								text: 'Average Score',
								style: {
										size: 9,
										color: colors.muted
								}
						},
				},
				legend: {
						show: true,
						position: "top",
						horizontalAlign: 'center',
						fontFamily: fontFamily,
						itemMargin: {
								horizontal: 8,
								vertical: 0
						},
				},
				stroke: {
						width: 0
				},
				dataLabels: {
						enabled: true,
						style: {
								fontSize: '10px',
								fontFamily: fontFamily,
						},
						offsetY: -27
				},
				plotOptions: {
						bar: {
								columnWidth: "50%",
								borderRadius: 4,
								dataLabels: {
										position: 'top',
										orientation: 'vertical',
								}
						},
				},
		};

		var apexBarChart = new ApexCharts(document.querySelector("#DiagramBatang"), options);
		apexBarChart.render();
}

// Chart.js Radar Chart
if (document.getElementById('StatistikSpiderweb')) {
		new Chart(document.getElementById('StatistikSpiderweb'), {
				type: 'radar',
				data: {
						labels: categories,
						datasets: [
								{
										label: "Average Score",
										fill: true,
										backgroundColor: "rgba(54, 162, 235, 0.2)", 
										borderColor: colors.primary,
										pointBorderColor: colors.primary,
										pointBackgroundColor: colors.cardBg,
										pointBorderWidth: 2,
										pointHoverBorderWidth: 3,
										data: averages
								}
						]
				},
				options: {
						aspectRatio: 2,
						scales: {
								r: {
										angleLines: {
												display: true,
												color: colors.gridBorder,
										},
										grid: {
												color: colors.gridBorder
										},
										suggestedMin: 0,
										suggestedMax: Math.max(...averages), 
										ticks: {
												backdropColor: colors.cardBg,
												color: colors.bodyColor,
												font: {
														size: 11,
														family: fontFamily
												}
										},
										pointLabels: {
												color: colors.bodyColor,
												font: {
														family: fontFamily,
														size: 13
												}
										}
								}
						},
						plugins: {
								legend: { 
										display: true,
										labels: {
												color: colors.bodyColor,
												font: {
														size: 13,
														family: fontFamily
												}
										}
								},
						},
				}
		});
}

});