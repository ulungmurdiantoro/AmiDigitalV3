document.addEventListener("DOMContentLoaded", function () {
  var colors = {
    primary: "#104ea4",
    gridBorder: "rgba(77, 138, 240, .15)",
    bodyColor: "#000",
    cardBg: "#fff"
  };

  var fontFamily = "'Roboto', Helvetica, sans-serif";

  function hexToRgba(hex, opacity) {
    var bigint = parseInt(hex.replace("#", ""), 16);
    var r = (bigint >> 16) & 255;
    var g = (bigint >> 8) & 255;
    var b = bigint & 255;
    return "rgba(" + r + ", " + g + ", " + b + ", " + opacity + ")";
  }

  if (typeof standarData === "undefined" || !Array.isArray(standarData)) {
    console.error("standarData is not defined or is not an array.");
    return;
  }

  // Helper: hitung score elemen (persen memenuhi) dari indikator-indikatornya
  function elementScore(element) {
    const indicators = Array.isArray(element?.indicators) ? element.indicators : [];
    const total = indicators.length;
    if (total === 0) return 0;

    const sum = indicators.reduce((acc, ind) => {
      // hasOne => dokumen_nilais object|null
      const v = ind?.dokumen_nilais?.hasil_nilai ?? 0; // 0/1
      return acc + (Number(v) || 0);
    }, 0);

    // return persen (0-100). Kalau mau rata2 0-1 tinggal sum/total.
    return Math.round((sum / total) * 100);
  }

  // supaya label elemen berlanjut antar chart
  let globalElementIndex = 0;

  standarData.forEach((standard, index) => {
    const canvasId = `StatistikSpiderweb-${index}`;
    const canvas = document.getElementById(canvasId);

    if (!canvas) {
      console.error(`Canvas with ID ${canvasId} not found`);
      return;
    }

    const elements = Array.isArray(standard?.elements) ? standard.elements : [];

    // data angka per elemen
    const dataValues = elements.map(elementScore);

    // label elemen (berlanjut)
    const labels = elements.map((_, i) => `Elemen ${globalElementIndex + i + 1}`);
    globalElementIndex += elements.length;

    // max 100 karena persen
    const maxDataValue = 100;

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {}
    };

    if (dataValues.length < 3) {
      chartOptions.scales = {
        x: {
          grid: { color: colors.gridBorder },
          ticks: { color: colors.bodyColor, font: { size: 13, family: fontFamily } }
        },
        y: {
          beginAtZero: true,
          min: 0,
          suggestedMax: maxDataValue,
          grid: { color: colors.gridBorder },
          ticks: { color: colors.bodyColor, font: { size: 13, family: fontFamily } }
        }
      };

      new Chart(canvas, {
        type: "bar",
        data: {
          labels,
          datasets: [{
            backgroundColor: hexToRgba(colors.primary, 0.2),
            borderColor: colors.primary,
            data: dataValues
          }]
        },
        options: chartOptions
      });
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

      new Chart(canvas, {
        type: "radar",
        data: {
          labels,
          datasets: [{
            fill: true,
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
    }
  });
});
