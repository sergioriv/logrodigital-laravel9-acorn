/**
 *
 * ChartsExtend
 *
 * Extends and configures charts with static methods.
 *
 *
 */

class ChartsExtend {
  static LegendLabels() {
    return {
      padding: 20,
      usePointStyle: true,
      fontSize: 14,
      boxWidth: 10,
      fontFamily: Globals.font,
    };
  }

  static ChartTooltip() {
    return {
      backgroundColor: Globals.foreground,
      titleFontColor: Globals.primary,
      borderColor: Globals.separator,
      borderWidth: 1,
      bodyFontColor: Globals.body,
      fontFamily: Globals.font,
      bodySpacing: 10,
      xPadding: 15,
      yPadding: 15,
      cornerRadius: parseInt(Globals.borderRadiusMd),
      displayColors: false,
      intersect: false,
    };
  }

  static ChartTooltipForCrosshair() {
    return {
      backgroundColor: Globals.foreground,
      titleFontColor: Globals.primary,
      borderColor: Globals.separator,
      borderWidth: 1,
      bodyFontColor: Globals.body,
      fontFamily: Globals.font,
      bodySpacing: 10,
      xPadding: 15,
      yPadding: 15,
      cornerRadius: parseInt(Globals.borderRadiusMd),
      displayColors: false,
      intersect: false,
      position: 'nearest',
      mode: 'index',
    };
  }

  static Crosshair() {
    return {
      line: {
        color: Globals.separator,
        width: 1,
      },
      sync: {
        enabled: false,
      },
      pan: {
        incrementer: 3,
      },
      zoom: {
        enabled: false,
      },
    };
  }

  static CenterTextPlugin() {
    return {
      afterDatasetsUpdate: function (chart) {},
      beforeDraw: function (chart) {
        var width = chart.chartArea.right;
        var height = chart.chartArea.bottom;
        var ctx = chart.chart.ctx;
        ctx.restore();
        var activeLabel = chart.data.labels[0];
        var activeValue = chart.data.datasets[0].data[0];
        var dataset = chart.data.datasets[0];
        var meta = dataset._meta[Object.keys(dataset._meta)[0]];
        var total = meta.total;
        var activePercentage = parseFloat(((activeValue / total) * 100).toFixed(1));
        activePercentage = chart.legend.legendItems[0].hidden ? 0 : activePercentage;
        if (chart.pointAvailable) {
          activeLabel = chart.data.labels[chart.pointIndex];
          activeValue = chart.data.datasets[chart.pointDataIndex].data[chart.pointIndex];
          dataset = chart.data.datasets[chart.pointDataIndex];
          meta = dataset._meta[Object.keys(dataset._meta)[0]];
          total = meta.total;
          activePercentage = parseFloat(((activeValue / total) * 100).toFixed(1));
          activePercentage = chart.legend.legendItems[chart.pointIndex].hidden ? 0 : activePercentage;
        }
        ctx.font = '28px' + Globals.font;
        ctx.textBaseline = 'middle';
        ctx.fillStyle = Globals.body;
        var text = activePercentage + '%',
          textX = Math.round((width - ctx.measureText(text).width) / 2),
          textY = height / 2;
        ctx.fillText(text, textX, textY);
        ctx.fillStyle = Globals.alternate;
        ctx.font = '12px' + Globals.font;
        ctx.textBaseline = 'top';
        var text2 = activeLabel.toLocaleUpperCase(),
          textX = Math.round((width - ctx.measureText(text2).width) / 2),
          textY = height / 2 - 30;
        ctx.fillText(text2, textX, textY);
        ctx.save();
      },
      beforeEvent: function (chart, event, options) {
        var firstPoint = chart.getElementAtEvent(event)[0];
        if (firstPoint) {
          chart.pointIndex = firstPoint._index;
          chart.pointDataIndex = firstPoint._datasetIndex;
          chart.pointAvailable = true;
        }
      },
    };
  }

  static CenterTextPluginSmall() {
    return {
      afterDatasetsUpdate: function (chart) {},
      beforeDraw: function (chart) {
        var width = chart.chartArea.right;
        var height = chart.chartArea.bottom;
        var ctx = chart.chart.ctx;
        ctx.restore();
        var activeValue = chart.data.datasets[0].data[0];
        var dataset = chart.data.datasets[0];
        var meta = dataset._meta[Object.keys(dataset._meta)[0]];
        var total = meta.total;
        var activePercentage = parseFloat(((activeValue / total) * 100).toFixed(1));
        activePercentage = chart.data.datasets[0].data[0] / total;
        activePercentage = parseFloat((activePercentage * 100).toFixed(1));
        ctx.font = '12px' + Globals.font;
        ctx.textBaseline = 'middle';
        ctx.fillStyle = Globals.primary;
        var text = activePercentage + '%',
          textX = Math.round((width - ctx.measureText(text).width) / 2),
          textY = height / 2;
        ctx.fillText(text, textX, textY);
        ctx.save();
      },
      beforeEvent: function (chart, event, options) {
        var firstPoint = chart.getElementAtEvent(event)[0];
        if (firstPoint) {
          chart.pointIndex = firstPoint._index;
          chart.pointDataIndex = firstPoint._datasetIndex;
          chart.pointAvailable = true;
        }
      },
    };
  }

  static LargeLineChartTooltip() {
    const tooltips = {
      enabled: false,
      custom: function (tooltip) {
        var container = this._chart.canvas.closest('.chart-container');
        if (tooltip.body) {
          var chart = this;
          var index = tooltip.dataPoints[0].index;
          var datasetIndex = tooltip.dataPoints[0].datasetIndex;
          var icon = container.querySelector('.icon');
          icon.setAttribute('data-acorn-icon', chart._data.datasets[datasetIndex].icons[index]);
          new AcornIcons().replace();
          container.querySelector('.title').innerHTML = chart._data.datasets[datasetIndex].label;
          container.querySelector('.text').innerHTML = chart._data.labels[index].toLocaleUpperCase();
          container.querySelector('.value').innerHTML = Helpers.AddCommas(chart._data.datasets[datasetIndex].data[index]);
        }
        container.style.opacity = 1;
      },
    };
    return Object.assign(tooltips, ChartsExtend.ChartTooltipForCrosshair());
  }

  static LargeLineChart(chartId, chartData) {
    if (document.getElementById(chartId)) {
      const lineChart = document.getElementById(chartId).getContext('2d');
      const largeChart = new Chart(lineChart, {
        type: 'line',
        options: {
          events: ['mousemove', 'touchmove', 'touchstart'],
          plugins: {
            crosshair: false,
          },
          hover: {
            mode: 'index',
            intersect: false,
          },
          layout: {
            padding: {
              left: 10,
              right: 15,
              top: 35,
              bottom: 0,
            },
          },
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [
              {
                gridLines: {display: false, drawBorder: false},
                ticks: {display: false},
              },
            ],
            xAxes: [
              {
                gridLines: {display: false, drawBorder: false},
                ticks: {display: false},
              },
            ],
          },
          plugins: {
            datalabels: {
              backgroundColor: 'transparent',
              borderRadius: parseInt(Globals.borderRadiusMd),
              borderWidth: 1,
              padding: 5,
              borderColor: function (context) {
                return context.dataset.borderColor;
              },
              color: function (context) {
                return Globals.alternate;
              },
              font: {
                size: 10,
              },
              formatter: Math.round,
            },
          },
          legend: {display: false},
          tooltips: ChartsExtend.LargeLineChartTooltip(),
        },
        data: chartData,
      });

      // Calling hover for last one
      var meta = largeChart.getDatasetMeta(0);
      var rect = largeChart.canvas.getBoundingClientRect();
      var point = meta.data[meta.data.length - 1].getCenterPoint();
      var evt = new MouseEvent('mousemove', {
        clientX: rect.left + point.x,
        clientY: rect.top + point.y,
      });
      var node = largeChart.canvas;
      node.dispatchEvent(evt);
      return largeChart;
    }
  }

  static SmallDoughnutChart(chartId, chartData, title) {
    if (!Chart.defaults.doughnutSmall) {
      Chart.defaults.doughnutSmall = Chart.defaults.doughnut;
      Chart.controllers.doughnutSmall = Chart.controllers.doughnut.extend({
        draw: function (ease) {
          Chart.controllers.doughnut.prototype.draw.call(this, ease);
          var ctx = this.chart.chart.ctx;
          var firstSegment = this.chart.getDatasetMeta(0).data[0]._model;
          var gap = ((firstSegment.outerRadius - firstSegment.innerRadius) * (1 - 0.3)) / 2;
          ctx.save();
          ctx.fillStyle = firstSegment.backgroundColor;
          ctx.beginPath();
          ctx.arc(firstSegment.x, firstSegment.y, firstSegment.outerRadius - gap, 0, 2 * Math.PI);
          ctx.arc(firstSegment.x, firstSegment.y, firstSegment.innerRadius + gap, 0, 2 * Math.PI, true);
          ctx.closePath();
          ctx.fill();
          ctx.restore();
        },
      });
    }

    if (document.getElementById(chartId)) {
      const ctx = document.getElementById(chartId).getContext('2d');
      var smallChart = new Chart(ctx, {
        type: 'doughnutSmall',
        data: {
          labels: [title],
          datasets: [
            {
              data: chartData,
              backgroundColor: [Globals.primary, 'rgba(0,0,0,0)'],
              borderWidth: [0, 0],
            },
          ],
        },
        plugins: [ChartsExtend.CenterTextPluginSmall()],
        options: {
          cutoutPercentage: 85,
          plugins: {
            crosshair: false,
            datalabels: {display: false},
          },
          legend: false,
          tooltips: {
            enabled: false,
          },
          aspectRatio: 1,
          legendCallback: function (chart) {
            const legendContainer = chart.canvas.parentElement.parentElement.querySelector('.custom-legend-container');
            legendContainer.innerHTML = '';
            const legendItem = chart.canvas.parentElement.parentElement.querySelector('.custom-legend-item');
            var itemClone = legendItem.content.cloneNode(true);
            itemClone.querySelector('.text').innerHTML = chart.data.labels[0].toLocaleUpperCase();
            itemClone.querySelector('.value').innerHTML =
              chart.data.datasets[0].data[0] + ' / ' + (chart.data.datasets[0].data[1] + chart.data.datasets[0].data[0]);
            legendContainer.appendChild(itemClone);
          },
        },
      });
      smallChart.generateLegend();
      return smallChart;
    }
  }

  static SmallLineChart(chartId, chartData) {
    const tooltips = {
      enabled: false,
      custom: function (tooltip) {
        var tooltipEl = this._chart.canvas.parentElement.parentElement.querySelector('.custom-tooltip');
        if (tooltip.body) {
          var chart = this;
          var index = tooltip.dataPoints[0].index;
          var datasetIndex = tooltip.dataPoints[0].datasetIndex;
          var icon = tooltipEl.querySelector('.icon');
          icon.setAttribute('data-acorn-icon', chart._data.datasets[datasetIndex].icons[index]);
          new AcornIcons().replace();
          tooltipEl.querySelector('.title').innerHTML = chart._data.datasets[datasetIndex].label;
          tooltipEl.querySelector('.text').innerHTML = chart._data.labels[index];
          tooltipEl.querySelector('.value').innerHTML = Helpers.AddCommas(chart._data.datasets[datasetIndex].data[index]);
        }
        tooltipEl.style.opacity = 1;
      },
    };
    Object.assign(tooltips, ChartsExtend.ChartTooltipForCrosshair());

    if (document.getElementById(chartId)) {
      const lineChart = document.getElementById(chartId).getContext('2d');
      const smallChart = new Chart(lineChart, {
        type: 'line',
        options: {
          events: ['mousemove', 'touchmove', 'touchstart'],
          plugins: {
            crosshair: false,
            datalabels: {display: false},
          },
          hover: {
            mode: 'index',
            intersect: false,
          },
          layout: {
            padding: {
              left: 6,
              right: 6,
              top: 6,
              bottom: 6,
            },
          },
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [
              {
                gridLines: {display: false, drawBorder: false},
                ticks: {display: false},
              },
            ],
            xAxes: [
              {
                gridLines: {display: false, drawBorder: false},
                ticks: {display: false},
              },
            ],
          },
          legend: {display: false},
          tooltips: tooltips,
        },
        data: chartData,
      });

      // Calling hover for last one
      var meta = smallChart.getDatasetMeta(0);
      var rect = smallChart.canvas.getBoundingClientRect();
      var point = meta.data[meta.data.length - 1].getCenterPoint();
      var evt = new MouseEvent('mousemove', {
        clientX: rect.left + point.x,
        clientY: rect.top + point.y,
      });
      var node = smallChart.canvas;
      node.dispatchEvent(evt);
      return smallChart;
    }
  }
}
