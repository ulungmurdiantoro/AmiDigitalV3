document.addEventListener("DOMContentLoaded", function () {
  const colors = {
    primary: "#104ea4",
    gridBorder: "rgba(77, 138, 240, .15)",
    bodyColor: "#000",
    cardBg: "#fff"
  };

  const fontFamily = "'Roboto', Helvetica, sans-serif";

  function hexToRgba(hex, opacity) {
    const bigint = parseInt(hex.replace("#", ""), 16);
    const r = (bigint >> 16) & 255;
    const g = (bigint >> 8) & 255;
    const b = bigint & 255;
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
  }

  if (!Array.isArray(standarData)) {
    console.error("standarData is not an array.");
    return;
  }

  let globalElementIndex = 0;

  standarData.forEach((standard, index) => {
    const canvasId = `StatistikSpiderweb-${index}`;
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const dokumenNilaiData = [];

    standard.elements?.forEach((element) => {
      element.indicators?.forEach((indicator) => {
        indicator.dokumen_nilais?.forEach((dokumen) => {
          dokumenNilaiData.push({
            nama: dokumen.nama ?? `Indikator ${dokumenNilaiData.length + 1}`,
            nilai: dokumen.hasil_nilai ?? 0
          });
        });
      });
    });

    const labels = dokumenNilaiData.map((d) => d.nama);
    const dataValues = dokumenNilaiData.map((d) => d.nilai);
    const maxDataValue = 1;

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {}
    };

    const chartType = dataValues.length < 3 ? "bar" : "radar";

    if (chartType === "bar") {
      chartOptions.scales = {
        x: {
          beginAtZero: true,
          grid: { color: colors.gridBorder },
          ticks: {
            color: colors.bodyColor,
            font: { size: 13, family: fontFamily }
          }
        },
        y: {
          beginAtZero: true,
          min: 0,
          suggestedMax: maxDataValue,
          grid: { color: colors.gridBorder },
          ticks: {
            color: colors.bodyColor,
            font: { size: 13, family: fontFamily }
          }
        }
      };
    } else {
      chartOptions.scales = {
        r: {
          angleLines: { display: true, color: colors.gridBorder },
          grid: { color: colors.gridBorder },
          min: 0,
          suggestedMax: maxDataValue,
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
      };
    }

    new Chart(canvas, {
      type: chartType,
      data: {
        labels: labels,
        datasets: [{
          label: '',
          backgroundColor: hexToRgba(colors.primary, 0.2),
          borderColor: colors.primary,
          pointBorderColor: colors.primary,
          pointBackgroundColor: colors.cardBg,
          pointBorderWidth: 2,
          pointHoverBorderWidth: 3,
          data: dataValues
        }]
      },
      options: chartOptions
    });
  });
});
