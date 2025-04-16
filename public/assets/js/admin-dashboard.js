$(function() {
	'use strict';

	var colors = {
			primary: "#104ea4",
			secondary: "#9babc3",
			success: "#05a34a",
			info: "#66d1d1",
			warning: "#f3d27f",
			danger: "#8c123d",
			light: "#e9ecef",
			dark: "#000000",
			muted: "#9babc3",
			gridBorder: "rgba(77, 138, 240, .15)",
			bodyColor: "#000",
			cardBg: "#fff"
	};

	var fontFamily = "'Roboto', Helvetica, sans-serif";

	function createChart(elementId, color, label) {
			var element = document.getElementById(elementId);
			if (element) {
					var value = element.getAttribute('data-value');

					var options = {
							chart: {
									height: 230,
									type: "radialBar"
							},
							series: [parseInt(value, 10)],
							colors: [color],
							plotOptions: {
									radialBar: {
											hollow: {
													margin: 15,
													size: "70%"
											},
											track: {
													show: true,
													background: colors.light,
													strokeWidth: '100%',
													opacity: 1,
													margin: 5,
											},
											dataLabels: {
													showOn: "always",
													name: {
															offsetY: -11,
															show: true,
															color: colors.muted,
															fontSize: "13px"
													},
													value: {
															color: colors.bodyColor,
															fontSize: "30px",
															show: true,
															formatter: function (val) {
																	return val; // Display the value as a number
															}
													}
											}
									}
							},
							fill: {
									opacity: 1
							},
							stroke: {
									lineCap: "round",
							},
							labels: [label]
					};

					var chart = new ApexCharts(element, options);
					chart.render();
			}
	}

	createChart('penggunaAdmin', colors.primary, 'Admin');
	createChart('penggunaProdi', colors.secondary, 'Prodi');
	createChart('penggunaAuditor', colors.info, 'Auditor');

	if ($('#jumlahProdi').length) {
			var options = {
					chart: {
							type: 'bar',
							height: '318',
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
							opacity: .9
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
							name: '',
							data: [prodiS1, prodiS2, prodiS3, prodiD3, prodiS1T, prodiS2T, prodiS3T, prodiPPG]
					}],
					xaxis: {
							type: 'category',
							categories: ['S1', 'S2', 'S3', 'D3', 'S1T', 'S2T', 'S3T', 'PPG'],
							axisBorder: {
									color: colors.gridBorder,
							},
							axisTicks: {
									color: colors.gridBorder,
							},
					},
					yaxis: {
							title: {
									text: '',
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
			}

			var apexBarChart = new ApexCharts(document.querySelector("#jumlahProdi"), options);
			apexBarChart.render();
	}

	if ($('#persenAmi').length) {
			var options = {
					chart: {
							height: 300,
							type: "pie",
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
					colors: [colors.primary, colors.warning, colors.danger, colors.info],
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
							colors: ['rgba(0,0,0,0)']
					},
					dataLabels: {
							enabled: false
					},
					series: [Diajukanami, Diterimaami, Koreksiami, Selesaiami],
					labels: ['Diajukan', 'Diterima', 'Koreksi', 'Selesai']
			};

			var chart = new ApexCharts(document.querySelector("#persenAmi"), options);
			chart.render();
	}

	if ($('#jumlahAmi').length) {
    var options = {
        chart: {
            type: 'bar',
            height: '318',
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
        colors: [colors.primary, colors.warning, colors.danger, colors.info],
        fill: {
            opacity: .9
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
            name: 'Diajukan',
            data: [amiSelesai.amiD3Diajukan, amiSelesai.amiS1Diajukan, amiSelesai.amiS2Diajukan, amiSelesai.amiS3Diajukan, amiSelesai.amiPPGDiajukan, amiSelesai.amiS1TDiajukan, amiSelesai.amiS2TDiajukan, amiSelesai.amiS3TDiajukan]
        }, {
            name: 'Diterima',
            data: [amiSelesai.amiD3Diterima, amiSelesai.amiS1Diterima, amiSelesai.amiS2Diterima, amiSelesai.amiS3Diterima, amiSelesai.amiPPGDiterima, amiSelesai.amiS1TDiterima, amiSelesai.amiS2TDiterima, amiSelesai.amiS3TDiterima]
        }, {
            name: 'Koreksi',
            data: [amiSelesai.amiD3Koreksi, amiSelesai.amiS1Koreksi, amiSelesai.amiS2Koreksi, amiSelesai.amiS3Koreksi, amiSelesai.amiPPGKoreksi, amiSelesai.amiS1TKoreksi, amiSelesai.amiS2TKoreksi, amiSelesai.amiS3TKoreksi]
        }, {
            name: 'Selesai',
            data: [amiSelesai.amiD3Selesai, amiSelesai.amiS1Selesai, amiSelesai.amiS2Selesai, amiSelesai.amiS3Selesai, amiSelesai.amiPPGSelesai, amiSelesai.amiS1TSelesai, amiSelesai.amiS2TSelesai, amiSelesai.amiS3TSelesai]
        }],
        xaxis: {
            type: 'category',
            categories: ['D3', 'S1', 'S2', 'S3', 'PPG', 'S1T', 'S2T', 'S3T'],
            axisBorder: {
                color: colors.gridBorder,
            },
            axisTicks: {
                color: colors.gridBorder,
            },
        },
        yaxis: {
            title: {
                text: '',
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
                },
                stacked: true
            },
        },
    }

    var apexBarChart = new ApexCharts(document.querySelector("#jumlahAmi"), options);
    apexBarChart.render();
	}
});
