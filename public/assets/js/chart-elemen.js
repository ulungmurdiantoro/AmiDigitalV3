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

    // Function to convert hex color to RGBA
    function hexToRgba(hex, opacity) {
        var bigint = parseInt(hex.replace("#", ""), 16);
        var r = (bigint >> 16) & 255;
        var g = (bigint >> 8) & 255;
        var b = bigint & 255;

        return "rgba(" + r + ", " + g + ", " + b + ", " + opacity + ")";
    }

    // Check if standarData is defined
    if (typeof standarData === 'undefined' || !Array.isArray(standarData)) {
        console.error("standarData is not defined or is not an array.");
        return;
    }

    // Initialize the global element index
    var globalElementIndex = 0;

    standarData.forEach((data, index) => {
        let canvasId = `StatistikSpiderweb-${index}`;
        let canvas = document.getElementById(canvasId);

        if (canvas) {
            if (!Array.isArray(data)) {
                console.error(`Data at index ${index} is not an array.`);
                return;
            }

            // Generate continuous labels across charts
            let labels = data.map((_, i) => `Elemen ${globalElementIndex + i + 1}`);

            // Update the global element index
            globalElementIndex += data.length;

            let maxDataValue = 4;

            if (!isFinite(maxDataValue)) {
                maxDataValue = 1;
            }

            let chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {},
            };

            if (data.length < 3) {
                // Bar chart options
                chartOptions.scales = {
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
                        min: 0,
                        suggestedMax: maxDataValue,
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
                    }
                };

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: '',
                                backgroundColor: hexToRgba(colors.primary, 0.2),
                                borderColor: colors.primary,
                                data: data
                            }
                        ]
                    },
                    options: chartOptions
                });
            } else {
                // Radar chart options
                chartOptions.scales = {
                    r: {
                        angleLines: {
                            display: true,
                            color: colors.gridBorder,
                        },
                        grid: {
                            color: colors.gridBorder
                        },
                        min: 0,
                        suggestedMax: maxDataValue,
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
                };

                new Chart(canvas, {
                    type: 'radar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: '',
                                fill: true,
                                backgroundColor: hexToRgba(colors.primary, 0.2),
                                borderColor: colors.primary,
                                pointBorderColor: colors.primary,
                                pointBackgroundColor: colors.cardBg,
                                pointBorderWidth: 2,
                                pointHoverBorderWidth: 3,
                                data: data
                            }
                        ]
                    },
                    options: chartOptions
                });
            }
        } else {
            console.error(`Canvas with ID ${canvasId} not found`);
        }
    });
});
