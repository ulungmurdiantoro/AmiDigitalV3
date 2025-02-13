document.addEventListener("DOMContentLoaded", function () {
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

	console.log("Document loaded");
	console.log(colors, fontFamily); // Log colors and fontFamily

	standarData.forEach((data, index) => {
    let canvasId = `StatistikSpiderweb-${index}`;
    let canvas = document.getElementById(canvasId);

    console.log(`Processing ${canvasId}`, canvas); // Log canvas ID and element

    if (canvas) {
        if (data.length < 3) {
            console.log(`Creating bar chart for ${canvasId}`); // Log bar chart creation
            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: data.map((_, i) => `Elemen ${i + 1}`), // Dynamically create labels
                    datasets: [
                        {
                            label: '',
                            backgroundColor: "rgba(54, 162, 235, 0.2)", // Light transparent blue
                            borderColor: colors.primary,
                            data: data // Use the data from standarData
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: colors.gridBorder
                            },
                            ticks: {
                                color: colors.bodyColor,
                                font: {
                                    size: 13,
                                    family: fontFamily
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            min: 0, // Explicitly set minimum value to zero
                            grid: {
                                color: colors.gridBorder
                            },
                            suggestedMin: 0, // Set suggested minimum value to zero
                            suggestedMax: Math.max(...data), // Adjust max scale dynamically
                            ticks: {
                                color: colors.bodyColor,
                                font: {
                                    size: 13,
                                    family: fontFamily
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
                        }
                    }
                }
            });
        } else {
            console.log(`Creating radar chart for ${canvasId}`); // Log radar chart creation
            new Chart(canvas, {
                type: 'radar',
                data: {
                    labels: data.map((_, i) => `Elemen ${i + 1}`), // Dynamically create labels
                    datasets: [
                        {
                            label: '',
                            fill: true,
                            backgroundColor: "rgba(54, 162, 235, 0.2)", // Light transparent blue
                            borderColor: colors.primary,
                            pointBorderColor: colors.primary,
                            pointBackgroundColor: colors.cardBg,
                            pointBorderWidth: 2,
                            pointHoverBorderWidth: 3,
                            data: data // Use the data from standarData
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
                            min: 0, // Explicitly set minimum value to zero
                            suggestedMin: 0, // Set suggested minimum value to zero
                            suggestedMax: Math.max(...data), // Adjust max scale dynamically
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
                        }
                    }
                }
            });
        }
    } else {
        console.error(`Canvas with ID ${canvasId} not found`); // Log error if canvas not found
    }
});



});
