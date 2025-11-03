$(function () {
  'use strict';

  const colors = {
    primary: "#104ea4",
    gridBorder: "rgba(77, 138, 240, .15)",
    bodyColor: "#000",
    cardBg: "#fff",
    muted: "#9babc3"
  };
  const fontFamily = "'Roboto', Helvetica, sans-serif";

  // Bar Chart (ApexCharts)
  if (document.getElementById('DiagramBatang')) {
    const barOptions = {
      chart: {
        type: 'bar',
        height: 400,
        foreColor: colors.bodyColor,
        background: colors.cardBg,
        toolbar: { show: false }
      },
      theme: { mode: 'light' },
      colors: [colors.primary],
      series: [{
        name: 'Nilai',
        data: nilaiValues
      }],
      xaxis: {
        categories: nilaiLabels,
        labels: {
          rotate: -45,
          style: {
            fontSize: '10px',
            fontFamily: fontFamily
          }
        },
        axisBorder: { color: colors.gridBorder },
        axisTicks: { color: colors.gridBorder }
      },
      yaxis: {
        title: {
          text: 'Nilai',
          style: { size: 9, color: colors.muted }
        }
      },
      dataLabels: {
        enabled: true,
        style: {
          fontSize: '10px',
          fontFamily: fontFamily
        },
        offsetY: -20
      },
      plotOptions: {
        bar: {
          columnWidth: "40%",
          borderRadius: 3,
          dataLabels: {
            position: 'top'
          }
        }
      },
      grid: {
        borderColor: colors.gridBorder,
        xaxis: { lines: { show: false } }
      },
      legend: {
        show: false
      },
      stroke: { width: 0 },
      fill: { opacity: 0.9 }
    };

    new ApexCharts(document.querySelector("#DiagramBatang"), barOptions).render();
  }

  // Radar Chart (Chart.js)
  if (document.getElementById('StatistikSpiderweb')) {
    new Chart(document.getElementById('StatistikSpiderweb'), {
      type: 'radar',
      data: {
        labels: nilaiLabels,
        datasets: [{
          label: "Nilai",
          fill: true,
          backgroundColor: "rgba(54, 162, 235, 0.2)",
          borderColor: colors.primary,
          pointBorderColor: colors.primary,
          pointBackgroundColor: colors.cardBg,
          pointBorderWidth: 2,
          pointHoverBorderWidth: 3,
          data: nilaiValues
        }]
      },
      options: {
        aspectRatio: 2,
        scales: {
          r: {
            angleLines: { display: true, color: colors.gridBorder },
            grid: { color: colors.gridBorder },
            suggestedMin: 0,
            suggestedMax: Math.max(...nilaiValues),
            ticks: {
              backdropColor: colors.cardBg,
              color: colors.bodyColor,
              font: { size: 11, family: fontFamily }
            },
            pointLabels: {
              color: colors.bodyColor,
              font: { family: fontFamily, size: 13 }
            }
          }
        },
        plugins: {
          legend: {
            display: true,
            labels: {
              color: colors.bodyColor,
              font: { size: 13, family: fontFamily }
            }
          }
        }
      }
    });
  }
});
