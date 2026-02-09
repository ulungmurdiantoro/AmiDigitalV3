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
    console.error("standarData is not an array.", standarData);
    return;
  }

  standarData.forEach((standard, index) => {
    const canvasId = `StatistikSpiderweb-${index}`;
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;

    const labels = [];
    const dataValues = [];

    standard.elements?.forEach((element) => {
      element.indicators?.forEach((indicator) => {

        // === HANDLE hasOne / hasMany ===
        const dn = indicator.dokumen_nilais;

        // label indikator (lebih masuk akal daripada dokumen.nama)
        const label = indicator.nama ?? `Indikator ${labels.length + 1}`;

        // nilai dari hasOne
        let nilai = 0;

        // kalau ternyata array (hasMany)
        if (Array.isArray(dn)) {
          // ambil total / atau ambil pertama (pilih salah satu)
          // nilai = dn.reduce((sum, x) => sum + (Number(x?.hasil_nilai) || 0), 0);
          nilai = Number(dn?.[0]?.hasil_nilai) || 0;
        } else {
          nilai = Number(dn?.hasil_nilai) || 0;
        }

        labels.push(label);
        dataValues.push(nilai);
      });
    });

    // kalau tidak ada data, jangan bikin chart
    if (!labels.length) {
      console.warn(`No data for ${canvasId}`, standard);
      return;
    }

    // sesuaikan max biar tidak "nge-press" (contoh nilai 0-4)
    const maxDataValue = Math.max(1, ...dataValues);

    const chartType = dataValues.length < 3 ? "bar" : "radar";

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: chartType === "bar"
        ? {
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
          }
        : {
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
          }
    };

    new Chart(canvas, {
      type: chartType,
      data: {
        labels,
        datasets: [{
          label: "",
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
