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

  if($('#monthlySalesChart').length) {
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
      } , 
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
        name: 'Sales',
        data: [3,3,4,6,5,7,8,1]
      }],
      xaxis: {
        type: 'category',
        categories: ['S1','S2','S3','D3','PPG','S1T', 'S2T','S3T'],
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
          style:{
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
    
    var apexBarChart = new ApexCharts(document.querySelector("#monthlySalesChart"), options);
    apexBarChart.render();
  }

  if ($('#storageChart1').length) {
    var options = {
      chart: {
        height: 230,
        type: "radialBar"
      },
      series: [70],
      colors: [colors.primary],
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
      labels: ["Admin"]
    };
    
    var chart = new ApexCharts(document.querySelector("#storageChart1"), options);
    chart.render();    
  }

  if ($('#storageChart2').length) {
    var options = {
      chart: {
        height: 230,
        type: "radialBar"
      },
      series: [67],
      colors: [colors.secondary],
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
      labels: ["Prodi"]
    };
    
    var chart = new ApexCharts(document.querySelector("#storageChart2"), options);
    chart.render();    
  }

  if ($('#storageChart3').length) {
    var options = {
      chart: {
        height: 230,
        type: "radialBar"
      },
      series: [67],
      colors: [colors.info],
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
      labels: ["Auditor"]
    };
    
    var chart = new ApexCharts(document.querySelector("#storageChart3"), options);
    chart.render();    
  }
  
  if ($('#apexPie').length) {
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
      colors: [colors.primary,colors.warning,colors.danger, colors.info],
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
      series: [44, 55, 13],
      labels: ['Diajukan', 'Diproses', 'Selesai']
    };
    
    var chart = new ApexCharts(document.querySelector("#apexPie"), options);
    chart.render();  
  }
  
  if ($('#BarStacked').length) {
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
        colors: [colors.primary, colors.warning, colors.danger ],  
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
            data: [3, 3, 4, 6, 5, 7, 8, 1]
        }, {
            name: 'Diproses',
            data: [2, 2, 3, 5, 4, 6, 7, 2]
        }, {
            name: 'Selesai',
            data: [4, 5, 7, 1, 3, 8, 9, 5]
        }],
        xaxis: {
            type: 'category',
            categories: ['S1', 'S2', 'S3', 'D3', 'PPG', 'S1T', 'S2T', 'S3T'],
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

    var apexBarChart = new ApexCharts(document.querySelector("#BarStacked"), options);
    apexBarChart.render();
  }

});