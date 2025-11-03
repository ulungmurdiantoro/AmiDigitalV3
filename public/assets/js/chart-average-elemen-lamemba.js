document.addEventListener("DOMContentLoaded", function () {
  const colors = {
    primary: "#104ea4",
    gridBorder: "rgba(77, 138, 240, .15)",
    bodyColor: "#000",
    cardBg: "#fff",
    muted: "#9babc3"
  };
  const fontFamily = "'Roboto', Helvetica, sans-serif";

    if (!Array.isArray(chartData)) return;

    const categories = chartData.map(({ nama }) => nama.slice(0, 10));
    const totalNilai = chartData.map(item => item.total_nilai);
    const totalCount = chartData.map(item => item.total_count);

console.log("chartData:", chartData);

  // ✅ Apex Bar Chart
  if ($('#DiagramBatang').length) {
    const options = {
      chart: {
        type: 'bar',
        height: 318,
        parentHeightOffset: 0,
        foreColor: colors.bodyColor,
        background: colors.cardBg,
        toolbar: { show: false }
      },
      theme: { mode: 'light' },
      tooltip: { theme: 'light' },
      colors: [colors.primary, "#f3d27f"],
      fill: { opacity: 0.9 },
      grid: {
        padding: { bottom: -4 },
        borderColor: colors.gridBorder,
        xaxis: { lines: { show: true } }
      },
      series: [
        { name: 'Total Nilai', data: totalNilai },
        // { name: 'Jumlah Indikator', data: totalCount }
      ],
      xaxis: {
        type: 'category',
        categories: categories,
        axisBorder: { color: colors.gridBorder },
        axisTicks: { color: colors.gridBorder }
      },
      yaxis: {
        title: {
          text: 'Nilai & Indikator',
          style: { size: 9, color: colors.muted }
        }
      },
      legend: {
        show: true,
        position: "top",
        horizontalAlign: 'center',
        fontFamily: fontFamily,
        itemMargin: { horizontal: 8, vertical: 0 }
      },
      stroke: { width: 0 },
      dataLabels: {
        enabled: true,
        style: { fontSize: '10px', fontFamily: fontFamily },
        offsetY: -27
      },
      plotOptions: {
        bar: {
          columnWidth: "50%",
          borderRadius: 4,
          dataLabels: {
            position: 'top',
            orientation: 'vertical'
          }
        }
      }
    };

    new ApexCharts(document.querySelector("#DiagramBatang"), options).render();
  }

  // ✅ Chart.js Radar Chart
  if ($('#StatistikSpiderweb').length) {
    new Chart($('#StatistikSpiderweb'), {
      type: 'radar',
      data: {
        labels: categories,
        datasets: [
          {
            label: "Total Nilai",
            fill: true,
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: colors.primary,
            pointBorderColor: colors.primary,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            data: totalNilai
          },
        //   {
        //     label: "Jumlah Indikator",
        //     fill: true,
        //     backgroundColor: "rgba(255, 206, 86, 0.2)",
        //     borderColor: "#f3d27f",
        //     pointBorderColor: "#f3d27f",
        //     pointBackgroundColor: colors.cardBg,
        //     pointBorderWidth: 2,
        //     pointHoverBorderWidth: 3,
        //     data: totalCount
        //   }
        ]
      },
      options: {
        aspectRatio: 2,
        scales: {
          r: {
            angleLines: { display: true, color: colors.gridBorder },
            grid: { color: colors.gridBorder },
            suggestedMin: 0,
            suggestedMax: Math.max(...totalCount, ...totalNilai),
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
